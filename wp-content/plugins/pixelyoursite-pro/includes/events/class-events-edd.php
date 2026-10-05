<?php

namespace PixelYourSite;

class EventsEdd extends EventsFactory {
    private $events = array(
        'edd_frequent_shopper',
        'edd_vip_client',
        'edd_big_whale',
	    'edd_purchase',
        'edd_view_content',
        'edd_view_category',
        'edd_add_to_cart_on_checkout_page',
        'edd_remove_from_cart',
        'edd_initiate_checkout',
        'edd_add_to_cart_on_button_click',
        'edd_subscribe',
        'edd_license',
        // Subscription events
        'edd_start_trial',
        'edd_subscription_created',
        'edd_subscription_renewal',
        'edd_subscription_expired',
        'edd_subscription_canceled',
        // License events
        'edd_license_created',
        'edd_license_upgrade',
        'edd_license_renewal',
        'edd_license_expired'
    );

    private $isNewCustomer = null;

    private $eddCustomerTotals = array();

    /**
     * Cache for purchase event IDs to avoid multiple DB lookups
     * @var array
     */
    private $purchaseEventIdCache = array();
    private static $_instance;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    private function __construct() {
        add_filter("pys_event_factory",[$this,"register"]);
    }

    function register($list) {
        $list[] = $this;
        return $list;
    }

    /**
     * Get or create a unique eventID for purchase events.
     * This ensures server and browser events use the same eventID for deduplication.
     *
     * @param int $order_id EDD payment ID
     * @param string $event_type Event type (e.g., 'edd_purchase')
     * @return string The eventID
     */
    public function getOrCreatePurchaseEventId( $order_id, $event_type = 'edd_purchase' ) {
        // Check cache first
        $cache_key = $event_type . '_' . $order_id;
        if ( isset( $this->purchaseEventIdCache[ $cache_key ] ) ) {
            return $this->purchaseEventIdCache[ $cache_key ];
        }

        $meta_key = '_pys_purchase_event_id';

        // Check if eventID already exists (server-first scenario)
        $existing_event_id = edd_get_payment_meta( $order_id, $meta_key, true );

        if ( ! empty( $existing_event_id ) ) {
            $this->purchaseEventIdCache[ $cache_key ] = $existing_event_id;
            return $existing_event_id;
        }

        // Generate new eventID
        $event_id = EventIdGenerator::guidv4();

        // Store for browser-side pickup and server-side deduplication
        edd_update_payment_meta( $order_id, $meta_key, $event_id );

        $this->purchaseEventIdCache[ $cache_key ] = $event_id;

        return $event_id;
    }

    static function getSlug() {
        return "edd";
    }

    function getEvents() {
        return $this->events;
    }

    function getCount()
    {
        $size = 0;
        if(!$this->isEnabled()) {
            return 0;
        }
        foreach ($this->events as $event) {
            if($this->isActive($event)){
                $size++;
            }
        }
        return $size;
    }

    function isEnabled()
    {
        return isEddActive();
    }

    function getOptions()
    {
        if($this->isEnabled()) {
            return array(
                'enabled'                       => true,
                'enabled_save_data_to_orders'  => PYS()->getOption('edd_enabled_save_data_to_orders'),
                'addToCartOnButtonEnabled'      => isEventEnabled( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_button_click' ),
                'addToCartOnButtonValueEnabled' => PYS()->getOption( 'edd_add_to_cart_value_enabled' ),
                'addToCartOnButtonValueOption'  => PYS()->getOption( 'edd_add_to_cart_value_option' ),
                'edd_purchase_on_transaction'   => PYS()->getOption( 'edd_purchase_on_transaction' )
            );
        } else {
            return array(
                'enabled'                       => false
            );
        }
    }

    function isReadyForFire($event)
    {
        switch ($event) {
            case 'edd_add_to_cart_on_button_click': {
                return PYS()->getOption( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_button_click' );
            }
            case 'edd_purchase': {
                return $this->checkEddPurchase();
            }
            case 'edd_initiate_checkout': {
                return  PYS()->getOption( 'edd_initiate_checkout_enabled' ) && edd_is_checkout();
            }
            case 'edd_remove_from_cart': {
                return PYS()->getOption( 'edd_remove_from_cart_enabled') && edd_is_checkout();
            }
            case 'edd_add_to_cart_on_checkout_page' : {
                return PYS()->getOption( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_checkout_page' )
                    && edd_is_checkout();
            }
            case 'edd_view_category': {
                return PYS()->getOption( 'edd_view_category_enabled' ) && is_tax( 'download_category' );
            }
            case 'edd_view_content' : {
                return PYS()->getOption( 'edd_view_content_enabled' ) && is_singular( 'download' );
            }
            case 'edd_vip_client': {
                $customerTotals = $this->getEddCustomerTotals();
                if(edd_is_success_page() && PYS()->getOption( 'edd_vip_client_enabled' ) && is_array( $customerTotals )) {
                    $orders_count = (int) PYS()->getOption( 'edd_vip_client_transactions' );
                    $avg = (int) PYS()->getOption( 'edd_vip_client_average_value' );
                    return $customerTotals['orders_count'] >= $orders_count && $customerTotals['avg_order_value'] >= $avg;
                }
                return false;
            }
            case 'edd_big_whale': {
                $customerTotals = $this->getEddCustomerTotals();
                if(edd_is_success_page() && PYS()->getOption( 'edd_big_whale_enabled' ) && is_array( $customerTotals )) {
                    $ltv = (int) PYS()->getOption( 'edd_big_whale_ltv' );
                    return $customerTotals['ltv'] >= $ltv;
                }
                return false;
            }
            case 'edd_frequent_shopper': {
                $customerTotals = $this->getEddCustomerTotals();
                if(edd_is_success_page() && PYS()->getOption( 'edd_frequent_shopper_enabled' ) && is_array( $customerTotals )) {
                    $orders_count = (int) PYS()->getOption( 'edd_frequent_shopper_transactions' );
                    return $customerTotals['orders_count'] >= $orders_count;
                }
                return false;
            }

            // Subscription events
            case 'edd_start_trial': {
                // Check if we're in the context of subscription status change
                if ( ! did_action( 'edd_subscription_status_change' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_subscriptions' );
            }
            case 'edd_subscription_created': {
                // Check if we're in the context of subscription status change
                if ( ! did_action( 'edd_subscription_status_change' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_subscriptions' );
            }
            case 'edd_subscription_renewal': {
                // Check if we're in the context of subscription renewal
                if ( ! did_action( 'edd_subscription_post_renew' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_subscriptions' );
            }
            case 'edd_subscription_expired': {
                // Check if we're in the context of subscription status change
                if ( ! did_action( 'edd_subscription_status_change' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_subscriptions' );
            }
            case 'edd_subscription_canceled': {
                // Check if we're in the context of subscription status change or cancellation
                if ( ! did_action( 'edd_subscription_status_change' ) && ! did_action( 'edd_subscription_cancelled' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_subscriptions' );
            }

            // License events
            case 'edd_license_created': {
                // Check if we're in the context of download purchase completion
                if ( ! did_action( 'edd_complete_download_purchase' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_licenses' );
            }
            case 'edd_license_upgrade': {
                // Check if we're in the context of license upgrade
                if ( ! did_action( 'edd_sl_license_upgraded' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_licenses' );
            }
            case 'edd_license_renewal': {
                // Check if we're in the context of license renewal
                if ( ! did_action( 'edd_sl_post_license_renewal' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_licenses' );
            }
            case 'edd_license_expired': {
                // Check if we're in the context of license status change
                if ( ! did_action( 'edd_sl_post_set_status' ) ) {
                    return false;
                }
                return PYS()->getOption( 'edd_track_licenses' );
            }

        }
        return false;
    }

    function getEvent($eventId, $isRealEvent = true)
    {
        switch ($eventId) {

            case 'edd_view_category': {
                $event = new SingleEvent($eventId, EventTypes::$STATIC, self::getSlug());
                return $event;
            }
            case 'edd_view_content': {
                global  $post;
                $event = new SingleEvent($eventId, EventTypes::$STATIC, self::getSlug());
                $event->args = ['products' => [$this->getEddProductParams($post->ID)]] ;
                return $event;
        }

            case 'edd_remove_from_cart': {
                return $this->getRemoveFromCartEvents($eventId);
            }
            case 'edd_add_to_cart_on_button_click': {

                return new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
            }
            case 'edd_add_to_cart_on_checkout_page':
            case 'edd_initiate_checkout': {
                $event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
                $event->args = ['products' => $this->getEddCartProducts()] ;
                return $event;
            }
            case 'edd_vip_client':
            case 'edd_big_whale':
            case 'edd_frequent_shopper': {
                $order_id =  $this->getEddOrderId();
                if(!$order_id) return null;
		        if ( PYS()->getOption( 'edd_purchase_on_transaction' ) &&
		             edd_get_payment_meta( $order_id, '_pys_purchase_event_fired', true )) {
			        return null; // skip woo_purchase if this transaction was fired
		        }
                $event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
                $event->args = ['products' => $this->getEddCheckOutProducts($order_id)] ;
                return $event;
            }
            case 'edd_purchase': {
                $order_id = $this->getEddOrderId();
                if ( ! $order_id ) {
                    return null;
                }

                if ( PYS()->getOption( 'edd_new_customer_enabled' ) ) {
                    $this->setNewCustomer( $order_id );
                }

                $payload = [];

                if ( $isRealEvent ) {
                    // 1. Collect all options and meta data once (cache in variables)
                    $is_prod_mode  = PYS()->getOption( 'edd_purchase_on_transaction' );
                    $mode          = PYS()->getOption( 'edd_purchase_on_transaction_mode', 'advanced' );

                    $browser_fired = edd_get_payment_meta( $order_id, '_pys_purchase_event_fired', true );
                    $server_fired  = edd_get_payment_meta( $order_id, '_pys_advance_purchase_event_fired', true );
                    $is_unlocked   = edd_get_payment_meta( $order_id, '_pys_purchase_browser_unlocked', true );

                    $server_fire_pixels = edd_get_payment_meta($order_id, '_pys_server_fired_pixels', true );
                    // 2. Basic duplicate protection (already fired)
                    if ( $is_prod_mode && $browser_fired ) {
                        return null;
                    }
                    // 3. Main distribution logic
                    if ( ! $is_unlocked && $server_fired ) {
                        if ( $mode === 'single' ) {
                            // Server fired first. Block browser.
                            $payload['send_server_event'] = $server_fire_pixels;
                            edd_update_payment_meta( $order_id, '_pys_purchase_browser_unlocked', true );
                        }

                        if ( $mode === 'advanced' ) {
                            // Server fired, browser also fires, but without deduplication (server already knows)
                            if(isset($server_fire_pixels['ga'])){
								$payload['send_server_event']['ga'] = $server_fire_pixels['ga'];
							}
                            $payload['dont_send_ajax'] = true;
                        }
                        $event_id = $this->getOrCreatePurchaseEventId( $order_id, 'edd_purchase' );
                        if ( ! empty( $event_id ) ) {
                            $payload['eventID'] = $event_id;
                        }

                    } elseif ( $mode === 'single' ) {
                        // Branch if we fire first in single mode, or if this is F5 (unlocked == true)
                        $payload['dont_send_ajax'] = true;
                    }

                    // 4. Optimized status saving to DB
                    if ( ! $is_unlocked ) {
                        edd_update_payment_meta( $order_id, '_pys_purchase_browser_unlocked', true );
                    }

                    if ( ! $browser_fired ) {
                        edd_update_payment_meta( $order_id, '_pys_purchase_event_fired', true );
                    }
                } else {
                    $event_id = $this->getOrCreatePurchaseEventId( $order_id, 'edd_purchase' );
                    if ( ! empty( $event_id ) ) {
                        $payload['eventID'] = $event_id;
                    }
                }

                $event = $this->getPurchaseEvent( $eventId, $order_id );

                if ( $event && !empty( $payload ) ) {
                    $event->addPayload( $payload );
                }

                return $event;
            }
            case 'edd_refund': {
                $order_id = $this->getEddOrderId();
                // Reset all purchase event flags on refund
                edd_update_payment_meta( $order_id, '_pys_purchase_event_fired', false );
                edd_update_payment_meta( $order_id, '_pys_advance_purchase_event_fired', false );
                edd_update_payment_meta( $order_id, '_pys_purchase_browser_unlocked', false );
                return $this->getRefundEvent( $eventId, $order_id );
            }

            // Subscription events
            case 'edd_start_trial': {
                return $this->getStartTrialEvent( $eventId );
            }
            case 'edd_subscription_created': {
                return $this->getSubscriptionCreatedEvent( $eventId );
            }
            case 'edd_subscription_renewal': {
                return $this->getSubscriptionRenewalEvent( $eventId );
            }
            case 'edd_subscription_expired': {
                return $this->getSubscriptionExpiredEvent( $eventId );
            }
            case 'edd_subscription_canceled': {
                return $this->getSubscriptionCanceledEvent( $eventId );
            }

            // License events
            case 'edd_license_created': {
                return $this->getLicenseCreatedEvent( $eventId );
            }
            case 'edd_license_upgrade': {
                return $this->getLicenseUpgradeEvent( $eventId );
            }
            case 'edd_license_renewal': {
                return $this->getLicenseRenewalEvent( $eventId );
            }
            case 'edd_license_expired': {
                return $this->getLicenseExpiredEvent( $eventId );
            }
        }
    }

    private function isActive($event)
    {
        switch ($event) {
            case 'edd_add_to_cart_on_button_click': {
                return PYS()->getOption( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_button_click' );
            }
            case 'edd_purchase': {
                return PYS()->getOption( 'edd_purchase_enabled' );
            }
            case 'edd_initiate_checkout': {
                return  PYS()->getOption( 'edd_initiate_checkout_enabled' ) ;
            }
            case 'edd_remove_from_cart': {
                return PYS()->getOption( 'edd_remove_from_cart_enabled');
            }
            case 'edd_add_to_cart_on_checkout_page' : {
                return PYS()->getOption( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_checkout_page' );
            }
            case 'edd_view_category': {
                return PYS()->getOption( 'edd_view_category_enabled' ) ;
            }
            case 'edd_view_content' : {
                return PYS()->getOption( 'edd_view_content_enabled' ) ;
            }
            case 'edd_vip_client': {
                return PYS()->getOption( 'edd_vip_client_enabled' );
            }
            case 'edd_big_whale': {
                return PYS()->getOption( 'edd_big_whale_enabled' );
            }
            case 'edd_frequent_shopper': {
                return PYS()->getOption( 'edd_frequent_shopper_enabled' );
            }
        }
        return false;
    }

    private function getRemoveFromCartEvents($eventId) {
        $events = [];


        foreach (edd_get_cart_contents() as $cart_item_key => $cart_item) {
            $event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
            $event->args = ['key'=>$cart_item_key,'item'=>$cart_item];
            $events[]=$event;
        }
        return $events;
    }

    public function getEddCustomerTotals($order_id = null) {
        // setup and cache params

        if ( empty( $this->eddCustomerTotals ) ) {
            $this->eddCustomerTotals = getEddCustomerTotals(0,$order_id);
        }
        return $this->eddCustomerTotals;
    }

    private function checkEddPurchase() {
        if(PYS()->getOption( 'edd_purchase_enabled' ) && edd_is_success_page()) {
            /**
             * When a payment gateway used, user lands to Payment Confirmation page first, which does automatic
             * redirect to Purchase Confirmation page. We filter Payment Confirmation to avoid double Purchase event.
             */
            if ( isset( $_GET['payment-confirmation'] ) ) {
                //@fixme: some users will not reach success page and event will not be fired
                //return;
            }
            $order_id = $this->getEddOrderId();
            $status = edd_get_payment_status( $order_id );

            // pending payment status used because we can't fire event on IPN
            if ( strtolower( $status ) != 'publish' && strtolower( $status ) != 'pending' &&  strtolower( $status ) != 'complete' ) {
                return false;
            }

            return true;
        }
        return false;
    }

    function getEddOrderId() {
        $payment_key = getEddPaymentKey();
        $order_id = (int) edd_get_purchase_id_by_key( $payment_key );
        return (int)apply_filters("pys_edd_checkout_order_id",$order_id);
    }

    /**
     * Get current subscription ID from filter
     *
     * @return int|null
     */
    private function getSubscriptionInfo($filter = null) {

        $subscribe_info = apply_filters( 'pys_edd_subscription', null );

        if(!empty($filter) && isset( $subscribe_info[$filter] ))
        {
            return $subscribe_info[$filter];
        }

        return false;
    }

    private function getSubscriptionByInfo($context = '')
    {
        PYS()->getLog()->debug( "{$context}: Creating event" );

        $subscription_id = $this->getSubscriptionInfo('subscription_id');

        if ( ! $subscription_id ) {
            PYS()->getLog()->debug( "{$context}: No subscription_id found" );
            return null;
        }

        // Load subscription object
        if ( ! class_exists( '\EDD\Recurring\Subscriptions\Subscription' ) ) {
            PYS()->getLog()->debug( "{$context}: EDD Recurring not available" );
            return null;
        }

        $subscription = new \EDD\Recurring\Subscriptions\Subscription( $subscription_id );

        if ( ! $subscription || ! $subscription->id ) {
            PYS()->getLog()->debug( "{$context}: Failed to load subscription", array( 'subscription_id' => $subscription_id ) );
            return null;
        }

        return $subscription;
    }

    /**
     * Get current license ID from filter
     *
     * @return int|null
     */
    private function getLicenseId() {
        return apply_filters( 'pys_edd_license_id', null );
    }

    function getEddProductParams($productId, $quantity = 1) {
        $post = get_post(  $productId );
        $tags = getObjectTerms( 'download_tag', $productId );
        $categories = getObjectTermsWithId( 'download_category', $productId );
        $data = [
            'product_id'    => $productId,
            'name'          => $post->post_title,
            'tags'          => $tags,
            'categories'    => $categories,
            'quantity'      => $quantity,
            'price_index'   => null
        ];

        return $data;
    }

    function getEddCartProducts() {
        $products = [];
        foreach (edd_get_cart_contents() as $cart_item_key => $cart_item) {
            $productId = (int) $cart_item['id'];
            $post = get_post(  $productId );
            $tags = getObjectTerms( 'download_tag', $productId );
            $categories = getObjectTermsWithId( 'download_category', $productId );

            if ( ! empty( $cart_item['options'] ) &&  !empty($cart_item['options']['price_id']) ) {
                $price_index = $cart_item['options']['price_id'];
            } else {
                $price_index = null;
            }

            $products[] = [
                'cart_item_key' => $cart_item_key,
                'product_id'    => $productId,
                'name'          => $post->post_title,
                'tags'          => $tags,
                'categories'    => $categories,
                'quantity'      => $cart_item['quantity'],
                'price_index'   => $price_index
            ];
        }
        return $products;
    }

    function getPurchaseEvent($eventId,$order_id) {

        if(!$order_id) return null;

        $payment = new \EDD_Payment($order_id);

        if(!$payment) return null;

        $event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
        $event->addPayload(['edd_order'=>$order_id]);
        $args = [
            'products' => $this->getEddPurchaseProducts($payment),
            'order_id'=>$order_id,
        ];
        $allFee = $payment->get_fees();
        $feeAmount = 0;
        foreach ( $allFee as $fee) {
            $feeAmount += $fee['amount'];
        }
        $payment->decrease_tax();

        $args['fee'] = $feeAmount;
        $args['fee_tax'] =  round( edd_calculate_tax($feeAmount), edd_currency_decimal_filter() );

        $user = edd_get_payment_meta_user_info( $order_id );
        // coupons
        $coupons = isset( $user['discount'] ) && $user['discount'] != 'none' ? $user['discount'] : null;

        if ( ! empty( $coupons ) ) {
            $coupons = explode( ', ', $coupons );
            $args['coupon'] = $coupons[0];
        } else {
            $args['coupon'] = '';
        }
        if(!is_null($this->isNewCustomer)) {
            $args['new_customer'] = $this->isNewCustomer;
        }

        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($payment->_ID);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }
        $event->args = $args;
        return $event;
    }
    function getRefundEvent($eventId,$order_id) {

        if(!$order_id) return null;
        $payment = new \EDD_Payment($order_id);

        if(!$payment) return null;

        $event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
        $event->addPayload(['edd_order'=>$order_id]);
        $args = [
            'products' => $this->getEddPurchaseProducts($payment),
            'order_id'=>$order_id,
        ];
        $allFee = $payment->get_fees();
        $feeAmount = 0;
        foreach ( $allFee as $fee) {
            $feeAmount += $fee['amount'];
        }
        $payment->decrease_tax();

        $args['fee'] = $feeAmount;
        $args['fee_tax'] =  round( edd_calculate_tax($feeAmount), edd_currency_decimal_filter() );

        $user = edd_get_payment_meta_user_info( $order_id );

        $event->args = $args;

        return $event;
    }
    /**
     * @param EDD_Payment $payment
     * @return array
     */
    function getEddPurchaseProducts($payment) {
        $products = [];


        $cart_details = $payment->cart_details;

        foreach ($cart_details as $cart_item_key => $cart_item) {
            $productId = (int) $cart_item['id'];
            $post = get_post(  $productId );
            $tags = getObjectTerms( 'download_tag', $productId );
            $categories = getObjectTermsWithId( 'download_category', $productId );

            $options = $cart_item['item_number']['options'];
            if ( ! empty( $options ) && $options !== 0 ) {
                $price_index = $options['price_id'];
            } else {
                $price_index = null;
            }

            $products[] = [
                'cart_item_key' => $cart_item_key,
                'product_id' => $productId,
                'name'  => $post->post_title,
                'tags'          => $tags,
                'categories'    => $categories,
                'quantity'  => $cart_item['quantity'],
                'subtotal'  =>  $cart_item['subtotal'],
                'tax'  => $cart_item['tax'] ,
                'discount'  => $cart_item['discount'],
                'price'  => $cart_item['price'],
                'price_index'=>$price_index
            ];
        }
        return $products;
    }

    function getEddCheckOutProducts($orderId) {
        $products = [];
        $cart = edd_get_payment_meta_cart_details($orderId, true );
        foreach ($cart as $cart_item_key => $cart_item) {
            $productId = (int) $cart_item['id'];
            $post = get_post(  $productId );
            $tags = getObjectTerms( 'download_tag', $productId );
            $categories = getObjectTermsWithId( 'download_category', $productId );

            $options = $cart_item['item_number']['options'];
            if ( ! empty( $options ) && $options !== 0 ) {
                $price_index = $options['price_id'];
            } else {
                $price_index = null;
            }

            $products[] = [
                'cart_item_key' => $cart_item_key,
                'product_id' => $productId,
                'name'  => $post->post_title,
                'tags'          => $tags,
                'categories'    => $categories,
                'quantity'  => $cart_item['quantity'] ?? 1,
                'subtotal'  =>  $cart_item['subtotal'] ?? 0,
                'tax'  => $cart_item['tax'] ?? 0 ,
                'discount'  => $cart_item['discount'] ?? 0,
                'price'  => $cart_item['price'],
                'price_index'=>$price_index
            ];
        }
        return $products;
    }

    /**
     * @param SingleEvent $event
     * @param $filter
     */
    static function filterEventProductsBy($event,$filters,$pixel) {
        $products = [];

        foreach ($event->args['products'] as $productData) {
            $includeProduct = ($pixel->logicConditionalTrack === 'track'); // Initially include for 'track', exclude for 'dont_track'
            foreach ($filters as $filter) {
                if ($filter == 'in_download_category') {
                    $ids = array_column($productData['categories'], 'id');
                    if ($pixel->logicConditionalTrack == 'track') {
                        if (in_array($filter['sub_id'], $ids)) {
                            $includeProduct = true; // Product matches
                            break; // Stop checking
                        } else {
                            $includeProduct = false; // Does not match the filter
                        }
                    } elseif ($pixel->logicConditionalTrack == 'dont_track') {
                        if (in_array($filter['sub_id'], $ids)) {
                            $includeProduct = false; // The product should be excluded
                            break; // Stop checking
                        } else {
                            $includeProduct = true; // The product remains on the list
                        }
                    }
                } elseif ($filter == 'in_download_tag') {
                    if ($pixel->logicConditionalTrack == 'track') {
                        if (isset($productData['tags'][$filter['sub_id']])) {
                            $includeProduct = true;
                            break;
                        } else {
                            $includeProduct = false;
                        }
                    } elseif ($pixel->logicConditionalTrack == 'dont_track') {
                        if (isset($productData['tags'][$filter['sub_id']])) {
                            $includeProduct = false;
                            break;
                        } else {
                            $includeProduct = true;
                        }
                    }
                } else {
                    if ($pixel->logicConditionalTrack == 'track') {
                        if ($productData['product_id'] == $filter['sub_id']) {
                            $includeProduct = true;
                            break;
                        } else {
                            $includeProduct = false;
                        }
                    } elseif ($pixel->logicConditionalTrack == 'dont_track') {
                        if ($productData['product_id'] == $filter['sub_id']) {
                            $includeProduct = false;
                            break;
                        } else {
                            $includeProduct = true;
                        }
                    }
                }
            }
            if ($includeProduct) {
                $products[] = $productData; // We add a product only if it passes all filters
            }
        }
        return $products;
    }
    public function setNewCustomer($order_id)
    {
        if(!is_null($this->isNewCustomer)) return;
            $payment_id = $order_id;
            $payment = edd_get_payment($payment_id);

            $exclude_payment_id = $payment_id;
            $start_date = strtotime('540 days ago');
            $end_date = time();

            if (!empty($payment)) {
                $customer_id = $payment->customer_id;
                if ($customer_id && $customer_id != 0) {
                    // Retrieve the customer's orders (payments) from the last 540 days, excluding the current order
                    $args = array(
                        'output'        => 'payments', // Specify output as 'payments' for retrieving EDD payment objects
                        'customer'   => $customer_id, // Filter payments by customer ID
                        'post__not_in'  => array($exclude_payment_id), // Exclude the current payment ID
                        'start_date'        => date('Y-m-d H:i:s', $start_date), // Start date for the range (540 days ago)
                        'end_date'          => date('Y-m-d H:i:s', $end_date), // End date for the range (today)
                        'number'        => 1, // Retrieve only 1 payment to determine if there is at least one previous order
                    );

                    // Get the payments for the customer based on the specified criteria
                    $payments = edd_get_payments($args);

                    // Check if there are no payments found; if none, this is a new customer
                    $this->isNewCustomer = empty($payments);
                }
            }

    }

    public function getNewCustomer()
    {
        return $this->isNewCustomer;
    }

    /**
     * Get License Created event with license data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getLicenseCreatedEvent( $eventId ) {
        PYS()->getLog()->debug( 'getLicenseCreatedEvent: Creating event' );

        $license_id = $this->getLicenseId();

        if ( ! $license_id ) {
            PYS()->getLog()->debug( 'getLicenseCreatedEvent: No license_id found' );
            return null;
        }

        // Load license object
        if ( ! class_exists( 'EDD_SL_License' ) ) {
            PYS()->getLog()->debug( 'getLicenseCreatedEvent: EDD Software Licensing not available' );
            return null;
        }

        $license = edd_software_licensing()->get_license( $license_id );

        if ( ! $license ) {
            PYS()->getLog()->debug( 'getLicenseCreatedEvent: Failed to load license', array( 'license_id' => $license_id ) );
            return null;
        }

        // Get payment for currency
        $payment_id = $license->payment_id;
        $payment = edd_get_payment( $payment_id );
        $currency = $payment ? $payment->currency : edd_get_currency();

        // Get license name (product title + price option)
        $license_name = '';
        if ( $license->download_id ) {
            $product = get_post( $license->download_id );
            if ( $product ) {
                $license_name = $product->post_title;

                if ( $license->price_id !== null && edd_has_variable_prices( $license->download_id ) ) {
                    $prices = edd_get_variable_prices( $license->download_id );
                    if ( isset( $prices[ $license->price_id ]['name'] ) ) {
                        $license_name .= ' - ' . $prices[ $license->price_id ]['name'];
                    }
                }
            }
        }

        // Get license price
        $value = 0;
        if ( $payment_id ) {
            $cart_details = edd_get_payment_meta_cart_details( $payment_id );
            if ( ! empty( $cart_details ) ) {
                foreach ( $cart_details as $item ) {
                    if ( $item['id'] == $license->download_id ) {
                        $value = (float) $item['price'];
                        break;
                    }
                }
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array( 'license_id' => $license_id, 'edd_order' => $payment_id ) );

        // Required fields
        $args = array(
            'license_id' => $license_id,
            'license_name' => $license_name,
            'order_id' => eddMapOrderId($payment_id),
            'value' => round( $value, 2 ),
            'currency' => $currency,
        );
        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($payment_id);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }
        // Add product information
        if ( $license->download_id ) {
            $args['products'] = array( $this->getEddProductParams( $license->download_id ) );
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get License Upgrade event with license data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getLicenseUpgradeEvent( $eventId ) {
        PYS()->getLog()->debug( 'getLicenseUpgradeEvent: Creating event' );

        $license_id = $this->getLicenseId();

        if ( ! $license_id ) {
            PYS()->getLog()->debug( 'getLicenseUpgradeEvent: No license_id found' );
            return null;
        }

        $license = edd_software_licensing()->get_license( $license_id );

        if ( ! $license ) {
            PYS()->getLog()->debug( 'getLicenseUpgradeEvent: Failed to load license', array( 'license_id' => $license_id ) );
            return null;
        }

        $payment_id = $license->payment_id;
        $payment = edd_get_payment( $payment_id );
        $currency = $payment ? $payment->currency : edd_get_currency();

        // Get license name (product title + price option)
        $license_name = '';
        if ( $license->download_id ) {
            $product = get_post( $license->download_id );
            if ( $product ) {
                $license_name = $product->post_title;

                if ( $license->price_id !== null && edd_has_variable_prices( $license->download_id ) ) {
                    $prices = edd_get_variable_prices( $license->download_id );
                    if ( isset( $prices[ $license->price_id ]['name'] ) ) {
                        $license_name .= ' - ' . $prices[ $license->price_id ]['name'];
                    }
                }
            }
        }

        // Get upgrade price from payment (value of the upgrade payment)
        $value = 0;
        if ( $payment_id ) {
            $value = (float) edd_get_payment_amount( $payment_id );
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array( 'license_id' => $license_id, 'edd_order' => $payment_id ) );

        // Required fields
        $args = array(
            'license_id' => $license_id,
            'license_name' => $license_name,
            'order_id' => eddMapOrderId($payment_id),
            'value' => round( $value, 2 ),
            'currency' => $currency,
        );
        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($payment_id);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }
        // Add product information
        if ( $license->download_id ) {
            $args['products'] = array( $this->getEddProductParams( $license->download_id ) );
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get License Renewal event with license data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getLicenseRenewalEvent( $eventId ) {
        PYS()->getLog()->debug( 'getLicenseRenewalEvent: Creating event' );

        $license_id = $this->getLicenseId();

        if ( ! $license_id ) {
            PYS()->getLog()->debug( 'getLicenseRenewalEvent: No license_id found' );
            return null;
        }

        $license = edd_software_licensing()->get_license( $license_id );

        if ( ! $license ) {
            PYS()->getLog()->debug( 'getLicenseRenewalEvent: Failed to load license', array( 'license_id' => $license_id ) );
            return null;
        }

        // Get payment IDs (this can be an array of all renewal payments)
        $payment_ids = $license->get_meta('_edd_sl_payment_id', false);
        // Get the most recent payment ID
        $payment_id = $license->payment_id;
        if ( ! empty( $payment_ids ) ) {
            if ( is_array( $payment_ids ) ) {
                // Get last payment (most recent renewal)
                $payment_id = end( $payment_ids );
                PYS()->getLog()->debug( 'getLicenseRenewalEvent: Multiple payments found, using most recent', array(
                    'total_payments' => count( $payment_ids ),
                    'selected_payment_id' => $payment_id
                ) );
            } elseif ( is_numeric( $payment_ids ) ) {
                // Single payment ID
                $payment_id = $payment_ids;
            }
        }

        // Get payment object with validation
        $payment = $payment_id ? edd_get_payment( $payment_id ) : null;
        $currency = $payment ? $payment->currency : edd_get_currency();

        // Get license name (product title + price option)
        $license_name = '';
        if ( $license->download_id ) {
            $product = get_post( $license->download_id );
            if ( $product ) {
                $license_name = $product->post_title;

                if ( $license->price_id !== null && edd_has_variable_prices( $license->download_id ) ) {
                    $prices = edd_get_variable_prices( $license->download_id );
                    if ( isset( $prices[ $license->price_id ]['name'] ) ) {
                        $license_name .= ' - ' . $prices[ $license->price_id ]['name'];
                    }
                }
            }
        }

        // Get renewal price
        $value = 0;
        if ( $payment_id && $payment ) {
            $value = (float) edd_get_payment_amount( $payment_id );
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array( 'license_id' => $license_id, 'edd_order' => $payment_id ) );

        // Required fields
        $args = array(
            'license_id' => $license_id,
            'license_name' => $license_name,
            'order_id' => eddMapOrderId($payment_id),
            'value' => round( $value, 2 ),
            'currency' => $currency,
        );
        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($payment_id);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }

        // Add product information
        if ( $license->download_id ) {
            $args['products'] = array( $this->getEddProductParams( $license->download_id ) );
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get License Expired event with license data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getLicenseExpiredEvent( $eventId ) {
        PYS()->getLog()->debug( 'getLicenseExpiredEvent: Creating event' );

        $license_id = $this->getLicenseId();

        if ( ! $license_id ) {
            PYS()->getLog()->debug( 'getLicenseExpiredEvent: No license_id found' );
            return null;
        }

        $license = edd_software_licensing()->get_license( $license_id );

        if ( ! $license ) {
            PYS()->getLog()->debug( 'getLicenseExpiredEvent: Failed to load license', array( 'license_id' => $license_id ) );
            return null;
        }

        // Get license name (product title + price option)
        $license_name = '';
        if ( $license->download_id ) {
            $product = get_post( $license->download_id );
            if ( $product ) {
                $license_name = $product->post_title;

                if ( $license->price_id !== null && edd_has_variable_prices( $license->download_id ) ) {
                    $prices = edd_get_variable_prices( $license->download_id );
                    if ( isset( $prices[ $license->price_id ]['name'] ) ) {
                        $license_name .= ' - ' . $prices[ $license->price_id ]['name'];
                    }
                }
            }
        }

        $payment_id = $license->payment_id;

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array( 'license_id' => $license_id, 'edd_order' => $payment_id ) );

        // Required field only
        $args = array(
            'license_id' => $license_id,
            'license_name' => $license_name,
        );
        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($payment_id);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }

        // Add product information
        if ( $license->download_id ) {
            $args['products'] = array( $this->getEddProductParams( $license->download_id ) );
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get Start Trial event with subscription data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getStartTrialEvent( $eventId ) {

        $subscription = $this->getSubscriptionByInfo($eventId);
        if(!$subscription){
            return null;
        }
        // Get subscription name (product title + price option)
        $subscription_name = '';
        if ( $subscription->product_id ) {
            $product = get_post( $subscription->product_id );
            if ( $product ) {
                $subscription_name = $product->post_title;

                // Add price option name if variable pricing
                if ( $subscription->price_id !== null && edd_has_variable_prices( $subscription->product_id ) ) {
                    $prices = edd_get_variable_prices( $subscription->product_id );
                    if ( isset( $prices[ $subscription->price_id ]['name'] ) ) {
                        $subscription_name .= ' - ' . $prices[ $subscription->price_id ]['name'];
                    }
                }
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $edd_order_id = $subscription->parent_payment_id;
        $event->addPayload( array( 'subscription_id' => $subscription->id, 'edd_order' => $edd_order_id ) );

        // Required fields only
        $args = array(
            'subscription_id' => $subscription->id,
            'subscription_name' => $subscription_name,
            'order_id' => eddMapOrderId($edd_order_id),
        );
        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($edd_order_id);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get Subscription Created event with subscription data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getSubscriptionCreatedEvent( $eventId ) {
        $subscription = $this->getSubscriptionByInfo($eventId);
        if(!$subscription){
            return null;
        }
        // Get parent payment/order for currency
        $parent_order = null;
        if ( $subscription->parent_payment_id ) {
            $parent_order = edd_get_payment( $subscription->parent_payment_id );
        }

        // Get currency
        $currency = $parent_order ? $parent_order->currency : edd_get_currency();

        // Get subscription name (product title + price option)
        $subscription_name = '';
        if ( $subscription->product_id ) {
            $product = get_post( $subscription->product_id );
            if ( $product ) {
                $subscription_name = $product->post_title;

                // Add price option name if variable pricing
                if ( $subscription->price_id !== null && edd_has_variable_prices( $subscription->product_id ) ) {
                    $prices = edd_get_variable_prices( $subscription->product_id );
                    if ( isset( $prices[ $subscription->price_id ]['name'] ) ) {
                        $subscription_name .= ' - ' . $prices[ $subscription->price_id ]['name'];
                    }
                }
            }
        }

        // Calculate predicted LTV using LTV Calculator
        $predicted_ltv = 0;
        if ( function_exists( '\PixelYourSite\EDD_LTV_Calculator' ) ) {
            $ltv_calculator = \PixelYourSite\EDD_LTV_Calculator();
            $predicted_ltv = $ltv_calculator->calculate_predicted_ltv( $subscription );

            // Save LTV to customer meta
            if ( $subscription->customer_id ) {
                $ltv_calculator->save_customer_ltv( $subscription->customer_id, $subscription->id, $predicted_ltv );
            }
        } else {
            // Fallback: simple calculation if LTV Calculator not available
            $predicted_ltv = (float) $subscription->initial_amount;

            if ( $subscription->bill_times > 0 ) {
                // Limited subscription - we know exact number of payments
                $predicted_ltv += ( (float) $subscription->recurring_amount * $subscription->bill_times );
            } else {
                // Unlimited subscription - estimate 12 months worth of payments
                $estimated_payments = 12;
                switch ( $subscription->period ) {
                    case 'day':
                        $estimated_payments = 365;
                        break;
                    case 'week':
                        $estimated_payments = 52;
                        break;
                    case 'month':
                        $estimated_payments = 12;
                        break;
                    case 'quarter':
                        $estimated_payments = 4;
                        break;
                    case 'semi-year':
                        $estimated_payments = 2;
                        break;
                    case 'year':
                        $estimated_payments = 1;
                        break;
                }
                $predicted_ltv += ( (float) $subscription->recurring_amount * $estimated_payments );
            }
        }

        // Value for subscription created is the initial amount
        $value = (float) $subscription->initial_amount;

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $edd_order_id = $parent_order ? (isset($parent_order->_ID) ? $parent_order->_ID : $parent_order->ID) : $subscription->parent_payment_id;
        $event->addPayload( array( 'subscription_id' => $subscription->id, 'edd_order' => $edd_order_id ) );

        // Required fields
        $args = array(
            'subscription_id' => $subscription->id,
            'subscription_name' => $subscription_name,
            'value' => round( $value, 2 ),
            'currency' => $currency,
            'predicted_ltv' => round( $predicted_ltv, 2 ),
            'order_id' => eddMapOrderId($edd_order_id),
        );

        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($parent_order);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }
        // Add product information
        $args['products'] = $this->getEddPurchaseProducts($parent_order);

        PYS()->getLog()->debug('debug:', $args);
        $event->args = $args;

        return $event;
    }

    /**
     * Get Subscription Renewal event with subscription data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getSubscriptionRenewalEvent( $eventId ) {
        $subscription = $this->getSubscriptionByInfo($eventId);
        if(!$subscription){
            return null;
        }

        // Get parent payment/order for currency
        $parent_order = null;
        if ( $this->getSubscriptionInfo('payment_id') ) {
            $parent_order = edd_get_payment( $this->getSubscriptionInfo('payment_id') );
        }

        PYS()->getLog()->debug('$subscription->parent_payment_id', $this->getSubscriptionInfo('payment_id'));

        // Get currency
        $currency = $parent_order ? $parent_order->currency : edd_get_currency();

        // Get subscription name (product title + price option)
        $subscription_name = '';
        if ( $subscription->product_id ) {
            $product = get_post( $subscription->product_id );
            if ( $product ) {
                $subscription_name = $product->post_title;

                // Add price option name if variable pricing
                if ( $subscription->price_id !== null && edd_has_variable_prices( $subscription->product_id ) ) {
                    $prices = edd_get_variable_prices( $subscription->product_id );
                    if ( isset( $prices[ $subscription->price_id ]['name'] ) ) {
                        $subscription_name .= ' - ' . $prices[ $subscription->price_id ]['name'];
                    }
                }
            }
        }

        // Value for renewal is the recurring amount
        $value = (float) $parent_order->total;

        // Calculate number of transactions (renewals + initial purchase)
        $number_of_transactions = 1; // Start with initial purchase

        // Get all renewal payments for this subscription
        $renewal_payments = $subscription->get_renewal_orders();

        if ( ! empty( $renewal_payments ) ) {
            $number_of_transactions += count( $renewal_payments );
        }

        // Calculate subscription value (all renewals + initial purchase)
        $subscription_value = (float) $subscription->initial_amount;

        if ( ! empty( $renewal_payments ) ) {
            foreach ( $renewal_payments as $payment ) {
               $subscription_value += (float) edd_get_payment_amount( $payment );
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $edd_order_id = $parent_order ? (isset($parent_order->_ID) ? $parent_order->_ID : $parent_order->ID) : $subscription->parent_payment_id;
        $event->addPayload( array( 'subscription_id' => $subscription->id, 'edd_order' => $edd_order_id ) );

        // Required fields
        $args = array(
            'subscription_id' => $subscription->id,
            'subscription_name' => $subscription_name,
            'value' => round( $value, 2 ),
            'currency' => $currency,
            'number_of_transactions' => $number_of_transactions,
            'subscription_value' => round( $subscription_value, 2 ),
            'order_id' => eddMapOrderId($edd_order_id),
        );

        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($parent_order);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }

        $args['products'] = $this->getEddPurchaseProducts($parent_order);

        $event->args = $args;

        return $event;
    }

    /**
     * Get Subscription Expired event with subscription data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getSubscriptionExpiredEvent( $eventId ) {
        $subscription = $this->getSubscriptionByInfo($eventId);
        if(!$subscription){
            return null;
        }

        $parent_order = null;
        if ( $subscription->parent_payment_id ) {
            $parent_order = edd_get_payment( $subscription->parent_payment_id );
        }

        // Get subscription name (product title + price option)
        $subscription_name = '';
        if ( $subscription->product_id ) {
            $product = get_post( $subscription->product_id );
            if ( $product ) {
                $subscription_name = $product->post_title;

                // Add price option name if variable pricing
                if ( $subscription->price_id !== null && edd_has_variable_prices( $subscription->product_id ) ) {
                    $prices = edd_get_variable_prices( $subscription->product_id );
                    if ( isset( $prices[ $subscription->price_id ]['name'] ) ) {
                        $subscription_name .= ' - ' . $prices[ $subscription->price_id ]['name'];
                    }
                }
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $edd_order_id = $parent_order ? (isset($parent_order->_ID) ? $parent_order->_ID : $parent_order->ID) : $subscription->parent_payment_id;
        $event->addPayload( array( 'subscription_id' => $subscription->id, 'edd_order' => $edd_order_id ) );

        // Required field only
        $args = array(
            'subscription_id' => $subscription->id,
            'subscription_name' => $subscription_name,
        );

        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($parent_order);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }

        if(!empty($parent_order)) {
            $args['order_id'] = $edd_order_id;
        }
        $args['currency'] = $parent_order ? $parent_order->currency : edd_get_currency();

        // Add product information
        if ( $subscription->product_id ) {
            $args['products'] = array( $this->getEddProductParams( $subscription->product_id ) );
        }

        $event->args = $args;

        return $event;
    }

    /**
     * Get Subscription Canceled event with subscription data
     *
     * @param string $eventId Event ID
     * @return SingleEvent|null
     */
    private function getSubscriptionCanceledEvent( $eventId ) {
        $subscription = $this->getSubscriptionByInfo($eventId);
        if(!$subscription){
            return null;
        }

        $parent_order = null;
        if ( $subscription->parent_payment_id ) {
            $parent_order = edd_get_payment( $subscription->parent_payment_id );
        }

        // Get subscription name (product title + price option)
        $subscription_name = '';
        if ( $subscription->product_id ) {
            $product = get_post( $subscription->product_id );
            if ( $product ) {
                $subscription_name = $product->post_title;

                // Add price option name if variable pricing
                if ( $subscription->price_id !== null && edd_has_variable_prices( $subscription->product_id ) ) {
                    $prices = edd_get_variable_prices( $subscription->product_id );
                    if ( isset( $prices[ $subscription->price_id ]['name'] ) ) {
                        $subscription_name .= ' - ' . $prices[ $subscription->price_id ]['name'];
                    }
                }
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $edd_order_id = $parent_order ? (isset($parent_order->_ID) ? $parent_order->_ID : $parent_order->ID) : $subscription->parent_payment_id;
        $event->addPayload( array( 'subscription_id' => $subscription->id, 'edd_order' => $edd_order_id ) );

        // Required field only
        $args = array(
            'subscription_id' => $subscription->id,
            'subscription_name' => $subscription_name,
        );

        if (PYS()->getOption('enable_edd_payment_method')){
            $gateway = edd_get_payment_gateway($parent_order);
            $args['payment_method'] = $gateway ? edd_get_gateway_admin_label($gateway) : '';
        }
        if(!empty($parent_order)) {
            $args['order_id'] = $edd_order_id;
        }
        $args['currency'] = $parent_order ? $parent_order->currency : edd_get_currency();
        // Add product information
        if ( $subscription->product_id ) {
            $args['products'] = array( $this->getEddProductParams( $subscription->product_id ) );
        }

        $event->args = $args;

        return $event;
    }

}

/**
 * @return EventsEdd
 */
function EventsEdd() {
    return EventsEdd::instance();
}

EventsEdd();