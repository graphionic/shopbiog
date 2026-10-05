<?php
namespace PixelYourSite;

use WC_Subscriptions_Product;

class EventsWoo extends EventsFactory {
    private $isNewCustomer = null;

    /**
     * Cache for purchase event IDs to avoid multiple DB lookups
     * @var array
     */
    private $purchaseEventIdCache = array();

	private $events = array(
		"woo_view_content",
		"woo_view_category",
		"woo_view_cart",
		"woo_view_item_list",
		"woo_view_item_list_single",
		"woo_view_item_list_search",
		"woo_view_item_list_shop",
		"woo_view_item_list_tag",
		"woo_add_to_cart_on_cart_page",
		"woo_add_to_cart_on_checkout_page",
		"woo_initiate_checkout",
		"woo_FirstTimeBuyer",
		"woo_ReturningCustomer",
		"woo_frequent_shopper",
		"woo_vip_client",
		"woo_big_whale",
		"woo_purchase",
		"woo_initiate_set_checkout_option",
		"woo_initiate_checkout_progress_f",
		"woo_initiate_checkout_progress_l",
		"woo_initiate_checkout_progress_e",
		"woo_initiate_checkout_progress_o",
		"woo_remove_from_cart",
		"woo_add_to_cart_on_button_click",
		"woo_affiliate",
		"woo_paypal",
		"woo_select_content_category",
		"woo_select_content_single",
		"woo_select_content_search",
		"woo_select_content_shop",
		"woo_select_content_tag",
        // Subscription events
        'woo_start_trial',
        'woo_subscription_created',
        'woo_subscription_renewal',
        'woo_subscription_expired',
        'woo_subscription_canceled',
	);
	public $doingAMP = false;

    public $wooCustomerTotals = array();


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
     * @param int $order_id WooCommerce order ID
     * @param string $event_type Event type (e.g., 'woo_purchase')
     * @return string The eventID
     */
    public function getOrCreatePurchaseEventId( $order_id, $event_type = 'woo_purchase' ) {
        // Check cache first
        $cache_key = $event_type . '_' . $order_id;
        if ( isset( $this->purchaseEventIdCache[ $cache_key ] ) ) {
            return $this->purchaseEventIdCache[ $cache_key ];
        }

        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            // Fallback: generate ephemeral ID (won't be persisted)
            $event_id = EventIdGenerator::guidv4();
            $this->purchaseEventIdCache[ $cache_key ] = $event_id;
            return $event_id;
        }



        $meta_key = '_pys_purchase_event_id';

        // Check if eventID already exists (server-first scenario)
        $existing_event_id = $order->get_meta( $meta_key, true );

        if ( ! empty( $existing_event_id ) ) {
            $this->purchaseEventIdCache[ $cache_key ] = $existing_event_id;
            return $existing_event_id;
        }

        // Generate new eventID
        $event_id = EventIdGenerator::guidv4();

        // Store for browser-side pickup and server-side deduplication
        $order->update_meta_data( $meta_key, $event_id );
        $order->save();

        $this->purchaseEventIdCache[ $cache_key ] = $event_id;

        return $event_id;
    }

	static function getSlug() {
		return "woo";
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
		return isWooCommerceActive();
	}

	function getOptions() {

		if($this->isEnabled()) {
			global $post;
			$data = array(
				'enabled'                       => true,
				'enabled_save_data_to_orders'  => PYS()->getOption('woo_enabled_save_data_to_orders'),
				'addToCartOnButtonEnabled'      => PYS()->getOption( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_button_click' ),
				'addToCartOnButtonValueEnabled' => PYS()->getOption( 'woo_add_to_cart_value_enabled' ),
				'addToCartOnButtonValueOption'  => PYS()->getOption( 'woo_add_to_cart_value_option' ),
				'woo_purchase_on_transaction'   => PYS()->getOption( 'woo_purchase_on_transaction' ) ,
                'woo_view_content_variation_is_selected' => PYS()->getOption( 'woo_view_content_variation_is_selected' ) ,
				'singleProductId'               => isWooCommerceActive() && is_singular( 'product' ) ? $post->ID : null,
				'affiliateEnabled'              => PYS()->getOption( 'woo_affiliate_enabled' ),
				'removeFromCartSelector'        => isWooCommerceVersionGte( '3.0.0' )
					? 'form.woocommerce-cart-form .remove'
					: '.cart .product-remove .remove',
				'addToCartCatchMethod'  => PYS()->getOption('woo_add_to_cart_catch_method'),
				'is_order_received_page' => PYS()->woo_is_order_received_page(),
				'containOrderId' => wooIsRequestContainOrderId()
			);
			$woo_affiliate_custom_event_type = PYS()->getOption( 'woo_affiliate_custom_event_type' );
			if ( PYS()->getOption( 'woo_affiliate_event_type' ) == 'custom' && ! empty( $woo_affiliate_custom_event_type ) ) {
				$data['affiliateEventName'] = sanitizeKey( PYS()->getOption( 'woo_affiliate_custom_event_type' ) );
			} else {
				$data['affiliateEventName'] = PYS()->getOption( 'woo_affiliate_event_type' );
			}
			return $data;
		} else {
			return array(
				'enabled' => false,
			);
		}

	}

	function isReadyForFire($event)
	{
		switch ($event) {
			case 'woo_affiliate': {
				return PYS()->getOption( 'woo_affiliate_enabled' );
			}
			case 'woo_add_to_cart_on_button_click': {


				return PYS()->getOption( 'woo_add_to_cart_enabled' )
				       && PYS()->getOption( 'woo_add_to_cart_on_button_click' )
				       && PYS()->getOption('woo_add_to_cart_catch_method') == "add_cart_js"; // or use in hook
			}
			case 'woo_select_content_category': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && !$this->doingAMP && is_tax( 'product_cat' ) &&  !is_shop();
			}
			case 'woo_select_content_single': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && !$this->doingAMP && is_product();
			}
			case 'woo_select_content_search': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && !$this->doingAMP && is_search();
			}
			case 'woo_select_content_shop': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && !$this->doingAMP && is_shop()&& !is_search();
			}
			case 'woo_select_content_tag': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && !$this->doingAMP && is_product_tag();
			}
			case 'woo_paypal': {
				return PYS()->getOption( 'woo_paypal_enabled' ) && is_checkout() && ! is_wc_endpoint_url();
			}
			case 'woo_remove_from_cart': {
				return PYS()->getOption( 'woo_remove_from_cart_enabled') && is_cart();
			}
			case "woo_initiate_checkout_progress_f":
			case "woo_initiate_checkout_progress_l":
			case "woo_initiate_checkout_progress_e":
			case "woo_initiate_checkout_progress_o": {
				return PYS()->getOption( "woo_checkout_steps_enabled" ) && is_checkout() ;
			}
			case 'woo_initiate_set_checkout_option': {
				return PYS()->getOption( "woo_checkout_steps_enabled" )  && is_checkout() && ! is_wc_endpoint_url();
			}

			case 'woo_FirstTimeBuyer': {
				$status = PYS()->woo_is_order_received_page() && PYS()->getOption( 'woo_FirstTimeBuyer_enabled' ) &&
				          wooIsRequestContainOrderId();
				return $status;
			}
			case 'woo_ReturningCustomer':{
				$status = PYS()->woo_is_order_received_page() && PYS()->getOption('woo_ReturningCustomer_enabled') &&
				          wooIsRequestContainOrderId();
				return $status;
			}
			case 'woo_purchase' : {
				$status = PYS()->getOption( 'woo_purchase_enabled' ) // if purchase enable by plugin settings
				          && (PYS()->woo_is_order_received_page()  //if is order review(success) page
				              || (EventsWcf()->isEnabled() && PYS()->getOption('wcf_purchase_on') == 'all' && (isWcfUpSale() || isWcfDownSale())) // or wcf page
				          )
				          && wooIsRequestContainOrderId() // request have order key
				          && empty($_REQUEST['wc-api']); // if is not api request
				return $status;
			}
			case 'woo_frequent_shopper': {
				if(PYS()->woo_is_order_received_page() && PYS()->getOption( 'woo_frequent_shopper_enabled' ) &&
				   wooIsRequestContainOrderId()) {
					$customerTotals = $this->getCustomerTotals();
					$orders_count = (int) PYS()->getOption( 'woo_frequent_shopper_transactions' );
					return  !empty($customerTotals) && $customerTotals['orders_count'] >= $orders_count;
				}
				return false;
			}
			case 'woo_vip_client': {
				if(PYS()->woo_is_order_received_page() && PYS()->getOption( 'woo_vip_client_enabled' )&&
				   wooIsRequestContainOrderId()) {
					$customerTotals = $this->getCustomerTotals();
					$orders_count = (int) PYS()->getOption( 'woo_vip_client_transactions' );
					$avg = (int) PYS()->getOption( 'woo_vip_client_average_value' );
					return !empty($customerTotals) &&
                            $customerTotals['orders_count'] >= $orders_count &&
					        $customerTotals['avg_order_value'] >= $avg;
				}
				return false;
			}
			case 'woo_big_whale': {
				if(PYS()->woo_is_order_received_page() && PYS()->getOption( 'woo_big_whale_enabled' )&&
				   wooIsRequestContainOrderId()) {
					$customerTotals = $this->getCustomerTotals();
					$ltv = (int) PYS()->getOption( 'woo_big_whale_ltv' );
					return !empty($customerTotals) && $customerTotals['ltv'] >= $ltv;
				}
				return false;
			}

			case 'woo_view_content' : {
				return PYS()->getOption( 'woo_view_content_enabled' )
				       && is_product();
			}

			case 'woo_view_category': {
				return PYS()->getOption( 'woo_view_category_enabled' ) &&  is_tax( 'product_cat' );
			}
			case 'woo_view_item_list':{
				return (PYS()->getOption( 'woo_view_category_enabled' ) || PYS()->getOption( 'woo_view_cart_enabled' )) &&  is_tax( 'product_cat' ) && !is_shop();
			}
			case 'woo_view_cart': {
				return PYS()->getOption( 'woo_view_cart_enabled' ) &&  is_cart();
			}
			case 'woo_view_item_list_single': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && is_product();
			}
			case 'woo_view_item_list_search': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && is_search();
			}
			case 'woo_view_item_list_shop': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) && is_shop() && !is_search();
			}
			case 'woo_view_item_list_tag': {
				return ( PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' ) ) &&  is_product_tag();
			}

			case 'woo_add_to_cart_on_cart_page': {
				return PYS()->getOption( 'woo_add_to_cart_enabled' ) &&
				       PYS()->getOption( 'woo_add_to_cart_on_cart_page' ) &&
				       is_cart()
				       && count(WC()->cart->get_cart())>0;
			}
			case 'woo_add_to_cart_on_checkout_page': {
				return PYS()->getOption( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_checkout_page' )
				       && is_checkout() && ! is_wc_endpoint_url()
				       && count(WC()->cart->get_cart())>0;
			}

			case 'woo_initiate_checkout': {
				return PYS()->getOption( 'woo_initiate_checkout_enabled' ) && is_checkout() && ! is_wc_endpoint_url();
			}

            // Subscription events
            case 'woo_start_trial': {
                // Check if we're in the context of subscription creation
                if ( ! did_action( 'woocommerce_subscription_payment_complete' ) ) {
                    return false;
                }
                PYS()->getLog()->debug( 'isReadyForFire: woo_start_trial', PYS()->getOption( 'woo_track_subscriptions' ) );
                return PYS()->getOption( 'woo_track_subscriptions' );
            }
            case 'woo_subscription_created': {
                // Check if we're in the context of subscription creation
                if ( ! did_action( 'woocommerce_subscription_payment_complete' ) ) {
                    return false;
                }
                PYS()->getLog()->debug( 'isReadyForFire: woo_subscription_created', PYS()->getOption( 'woo_track_subscriptions' ) );
                return PYS()->getOption( 'woo_track_subscriptions' );
            }
            case 'woo_subscription_renewal': {
                // Check if we're in the context of subscription renewal
                if ( ! did_action( 'woocommerce_subscription_payment_complete' ) ) {
                    return false;
                }
                PYS()->getLog()->debug( 'isReadyForFire: woo_subscription_renewal', PYS()->getOption( 'woo_track_subscriptions' ) );
                return PYS()->getOption( 'woo_track_subscriptions' );
            }
            case 'woo_subscription_expired': {
                // Check if we're in the context of subscription status change
                if ( ! did_action( 'woocommerce_subscription_status_updated' ) ) {
                    return false;
                }
                PYS()->getLog()->debug( 'isReadyForFire: woo_subscription_expired', PYS()->getOption( 'woo_track_subscriptions' ) );
                return PYS()->getOption( 'woo_track_subscriptions' );
            }
            case 'woo_subscription_canceled': {
                // Check if we're in the context of subscription status change or cancellation
                if ( ! did_action( 'woocommerce_subscription_status_updated' ) ) {
                    return false;
                }
                PYS()->getLog()->debug( 'isReadyForFire: woo_subscription_canceled', PYS()->getOption( 'woo_track_subscriptions' ) );
                return PYS()->getOption( 'woo_track_subscriptions' );
            }

		}
		return false;
	}

	function getEvent($eventId, $isRealEvent = true, $isAdvancedPurchase = false)
	{
		switch ($eventId) {
			case 'woo_remove_from_cart': {
				return $this->getRemoveFromCartEvents($eventId);
			}
			case 'woo_select_content_search':
			case 'woo_select_content_shop':
			case 'woo_select_content_tag':
			case 'woo_select_content_single':
			case 'woo_select_content_category': {
				return $this->getSelectContentEvents($eventId);
			}
			case 'woo_initiate_checkout': {
				return $this->getInitCheckoutEvent($eventId);
			}
			case 'woo_view_cart': {
				return $this->getInitCheckoutEvent($eventId);
			}
			case 'woo_add_to_cart_on_cart_page':
			case 'woo_add_to_cart_on_checkout_page':
				return $this->getAddToCartOnCartEvent($eventId);
			case 'woo_initiate_set_checkout_option':

			case 'woo_view_item_list':
			case 'woo_view_item_list_tag':
			case 'woo_view_item_list_shop':
			case 'woo_view_item_list_search':
			case 'woo_view_item_list_single':
			case 'woo_view_category':
                return $this->getViewListEvents($eventId);
            case 'woo_purchase' : {
                $order_id = $this->getPurchaseOrderId($isAdvancedPurchase);
                $order = wc_get_order($order_id);
                if(!$order) return null;

                $payload = [];

                if ($isRealEvent) {
                    // 1. Collect all options and meta data once (cache in variables)
                    $is_prod_mode  = PYS()->getOption( 'woo_purchase_on_transaction' );
                    $mode          = PYS()->getOption( 'woo_purchase_on_transaction_mode', 'advanced' );

                    $browser_fired = $order->get_meta( '_pys_purchase_event_fired', true );
                    $server_fired  = $order->get_meta( '_pys_advance_purchase_event_fired', true );
                    $is_unlocked   = $order->get_meta( '_pys_purchase_browser_unlocked', true );

                    $server_fire_pixels = $order->get_meta( '_pys_server_fired_pixels', true );
                    // 2. Basic duplicate protection (already fired)
                    if ( $is_prod_mode && $browser_fired ) {
                        return null;
                    }
                    $needs_save = false;
                    // 3. Main distribution logic
                    if ( !$is_unlocked && $server_fired ) {
                        if ( $mode === 'single' ) {
                            // Server fired first. Block browser.
                            $payload['send_server_event'] = $server_fire_pixels;
                            $order->update_meta_data( '_pys_purchase_browser_unlocked', true );
                            $needs_save = true;
                        }
                        if ( $mode === 'advanced' ) {
                            // Server fired, browser also fires, but without deduplication (server already knows)
							if(isset($server_fire_pixels['ga'])){
								$payload['send_server_event']['ga'] = $server_fire_pixels['ga'];
							}
							
                            $payload['dont_send_ajax'] = true;
                        }
                        $event_id = $this->getOrCreatePurchaseEventId( $order_id, 'woo_purchase' );
                        if ( ! empty( $event_id ) ) {
                            $payload['eventID'] = $event_id;
                        }
                    } elseif ( $mode === 'single' ) {
                        // Branch if we fire first in single mode, or if this is F5 (unlocked == true)
                        $payload['dont_send_ajax'] = true;
                    }

                    // 4. Optimized status saving to DB
                    // Check if we need to save the order to avoid unnecessary updates

                    if ( !$is_unlocked ) {
                        $order->update_meta_data( '_pys_purchase_browser_unlocked', true );
                        $needs_save = true;
                    }
                    if ( !$browser_fired ) {
                        $order->update_meta_data( '_pys_purchase_event_fired', true );
                        $needs_save = true;
                    }
                    if ( $needs_save ) {
                        $order->save();
                    }
                    firePoasForOrder( $order_id );
                } else {
                    $event_id = $this->getOrCreatePurchaseEventId( $order_id, 'woo_purchase' );
                    if ( ! empty( $event_id ) ) {
                        $payload['eventID'] = $event_id;
                    }
                }

                $event = $this->create_purchase_event( $eventId, $order_id );

                if ( $event && !empty( $payload ) ) {
                    $event->addPayload( $payload );
                }

                return $event;
            }

			case 'woo_big_whale':
			case 'woo_vip_client':
			case 'woo_frequent_shopper':
			case 'woo_FirstTimeBuyer':
			case 'woo_ReturningCustomer': {
                $order_id = $this->getPurchaseOrderId($isAdvancedPurchase);
				$order = wc_get_order($order_id);
				if(!$order) return null;
                if ($eventId === 'woo_FirstTimeBuyer' && !$this->getNewCustomer()) {
                    return null;
                }
                if ($eventId === 'woo_ReturningCustomer' && $this->getNewCustomer()) {
                    return null;
                }
				if (PYS()->getOption( 'woo_purchase_on_transaction' ) &&
				    $order->get_meta( '_pys_purchase_event_fired', true ) ) {
					return null;  // skip woo_purchase if this transaction was fired
				}
				return new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
			}
			case 'woo_view_content': {
				$events = [];
				if( is_product() ) {
					global $post;
					$event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
					$event->args = [
						"id" => $post->ID,
						'quantity'  => 1
					];
					$events[] = $event;
				}

				return $events;
			}
			case 'woo_paypal':
				return $this->getPaypalEvent($eventId);
			case 'woo_affiliate':
				return new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
			case 'woo_add_to_cart_on_button_click':{
				$events = [];
				if(isNextWcfCheckoutPage()) {
					$wcfProducts = getWcfFlowCheckoutProducts();
					foreach($wcfProducts as $product) {
						$event =  new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
						$event->args = [
							"productId" => $product['product'],
							'quantity'  => $product['quantity'],
							'discount_value' => $product['discount_value'],
							'discount_type' => $product['discount_type']
						];
						$events[] = $event;
					}
				} else {
					$events[] =  new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
				}

				return $events;

			}

			case 'woo_refund' : {
				$order_id = $this->getRefundOrderId();
				$order = wc_get_order($order_id);
				if(!$order) return null;

				$order->update_meta_data( '_pys_purchase_event_fired', false );
				$order->update_meta_data( '_pys_poas_event_fired', false ); // Reset so profit event can re-fire if order is re-placed
				$order->save();
				return  $this->create_refund_event($eventId,$order_id);
			}
			case "woo_initiate_checkout_progress_f":
			case "woo_initiate_checkout_progress_l":
			case "woo_initiate_checkout_progress_e":
			case "woo_initiate_checkout_progress_o":{
				$single =  new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
				$shipping = '';
				$ch_ship_methods = WC()->session->get( 'chosen_shipping_methods' );
				if($ch_ship_methods && is_array($ch_ship_methods) && count($ch_ship_methods) > 0) {
					$shipping_id = reset($ch_ship_methods);
                    if ($shipping_id) {
					    $shipping_id = explode(":",(string)$shipping_id)[0];
					    if(isset(WC()->shipping->get_shipping_methods()[$shipping_id])) {
						    $shipping = WC()->shipping->get_shipping_methods()[$shipping_id]->id;
					    }
                    }
				}



				$single->args = [
					'products' => $this->getCartProductData(),
					'shipping' => $shipping,
					'coupon'   => $this->getCartCoupon()
				];
				return $single;
			}

            // Subscription events
            case 'woo_start_trial': {
                return $this->getStartTrialEvent( $eventId );
            }
            case 'woo_subscription_created': {
                return $this->getSubscriptionCreatedEvent( $eventId );
            }
            case 'woo_subscription_renewal': {
                return $this->getSubscriptionRenewalEvent( $eventId );
            }
            case 'woo_subscription_expired': {
                return $this->getSubscriptionExpiredEvent( $eventId );
            }
            case 'woo_subscription_canceled': {
                return $this->getSubscriptionCanceledEvent( $eventId );
            }
		}

		return null;
	}

	private function isActive($event)
	{
		switch ($event) {
			case 'woo_affiliate': {
				return PYS()->getOption( 'woo_affiliate_enabled' );
			}
			case 'woo_add_to_cart_on_button_click': {
				return PYS()->getOption( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_button_click' );
			}
			case 'woo_paypal': {
				return PYS()->getOption( 'woo_paypal_enabled' ) ;
			}
			case 'woo_remove_from_cart': {
				return PYS()->getOption( 'woo_remove_from_cart_enabled') ;
			}
			/* case "woo_initiate_checkout_progress_f":
			 case "woo_initiate_checkout_progress_l":
			 case "woo_initiate_checkout_progress_e":
			 case "woo_initiate_checkout_progress_o": {
				 return PYS()->getOption( "woo_checkout_steps_enabled" ) ;
			 }*/
			case 'woo_initiate_set_checkout_option': {
				return PYS()->getOption( "woo_checkout_steps_enabled" );
			}
			case 'woo_purchase' : {
				return PYS()->getOption( 'woo_purchase_enabled' );
			}
			case 'woo_frequent_shopper': {
				return PYS()->getOption( 'woo_frequent_shopper_enabled' );
			}
			case 'woo_vip_client': {
				return PYS()->getOption( 'woo_vip_client_enabled' );
			}
			case 'woo_big_whale': {
				return PYS()->getOption( 'woo_big_whale_enabled' );
			}

			case 'woo_view_content' : {
				return PYS()->getOption( 'woo_view_content_enabled' ) ;
			}
			case 'woo_view_category': {
				return PYS()->getOption( 'woo_view_category_enabled' );
			}

			case 'woo_view_cart': {
				return PYS()->getOption( 'woo_view_cart_enabled' );
			}
			case 'woo_select_content_category':{
				return PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' );
			}
			case 'woo_view_item_list': {
				return PYS()->getOption( 'woo_view_category_enabled' ) || PYS()->getOption( 'woo_view_item_list_enabled' ) || PYS()->getOption( 'woo_view_item_list_gtm_enabled' );
			}
			case 'woo_initiate_checkout': {
				return PYS()->getOption( 'woo_initiate_checkout_enabled' );
			}

		}
		return false;
	}
	private function getWooCartActiveCategories($activeIds) {
		$fireForCategory = array();
		foreach (WC()->cart->cart_contents as $cart_item_key => $cart_item) {
			$_product =  wc_get_product( $cart_item['product_id'] );
			if(!$_product) continue;
			$productCat = $_product->get_category_ids();
			foreach ($activeIds as $key => $value) {
				if(in_array($key,$productCat)) {
					$fireForCategory[] = $key;
				}
			}
		}
		return array_unique($fireForCategory);
	}

	private function getWooOrderActiveCategories($orderId,$activeIds) {
		$order = wc_get_order( $orderId );

		$fireForCategory = array();
		foreach ($order->get_items() as $item) {
			$_product =  wc_get_product( $item->get_product_id() );
			if(!$_product) continue;
			$productCat = $_product->get_category_ids();
			foreach ($activeIds as $key => $value) {
				if(in_array($key,$productCat)) { // fire initiate_checkout for all category pixel
					$fireForCategory[] = $key;
				}
			}
		}
		return array_unique($fireForCategory);
	}


	function getEvents() {
		return $this->events;
	}

	/**
	 * @return bool|\WC_Order
	 */
	function getOrder() {
		$order_id = wooGetOrderIdFromRequest();
		$order_id = apply_filters("pys_woo_checkout_order_id",$order_id);
		$order = wc_get_order($order_id);
		if(!$order) return false;

		if(EventsWcf()->isEnabled()) {
			$offer_orders_meta = $order->get_meta( '_cartflows_offer_child_orders' );

			$child_count = is_array($offer_orders_meta) ? count($offer_orders_meta) : 0;

			if(is_array($offer_orders_meta) && $child_count > 0) { // send info about last child order
				$keys = array_keys($offer_orders_meta);
				$child_id = $keys[count($keys)-1];
				$order_id = $child_id; // replace parent order to child
				$order = wc_get_order($order_id);
				if(!$order) return false;
			}
		}

		return $order;
	}

	private function getPurchaseOrderId($isAdvancedPurchase = false) {
		$order = $this->getOrder();
		if(!$order) return false;

		$status = "wc-".$order->get_status("edit");

        $advancedStatuses = (array) PYS()->getOption('woo_order_advanced_purchase_status');
        $disabledStatuses = (array) PYS()->getOption('woo_order_purchase_disabled_status');

        $isInvalidAdvanced = $isAdvancedPurchase && !in_array($status, $advancedStatuses, true);
        $isDisabledStandard  = !$isAdvancedPurchase && in_array($status, $disabledStatuses, true);

        if ($isInvalidAdvanced || $isDisabledStandard) {
            return false;
        }

		if(EventsWcf()->isEnabled()
		   && !PYS()->getOption('wcf_purchase_on_optin_enabled')
		   && $order->get_meta('pys_order_type',true) == "wcf-optin") {
			return false;
		}
        if( PYS()->getOption('woo_new_customer_enabled') ) {
            $this->setNewCustomer($order->get_id());
        }
        return $order->get_id();
	}
	private function getRefundOrderId() {
		$order = $this->getOrder();
		if(!$order) return false;
		return $order->get_id();
	}
	private function create_purchase_event($eventId,$order_id,$category_id = null) {
		$event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
		$wcf_offer_step_id = 0;
		$order = wc_get_order($order_id);
		$wcf_checkout_products = [];

		if(!$order) return null;

		// Store AWCDP order reference for later use
		$awcdp_order = null;
		if ($order instanceof \AWCDP_Order) {
			$awcdp_order = $order;
		}


		if(isWcfStep()) {

			//prevent duplicate
			if($order->get_meta("pys_wcf_purchase_".count($order->get_items()),true) && PYS()->getOption( 'woo_purchase_on_transaction' )) {
				return null;
			} else {
				$order->update_meta_data("pys_wcf_purchase_".count($order->get_items()),true);
				$order->save();
			}

			// try to find products only for upsell or downsell
			$prev = getPrevWcfStep();


			if(PYS()->getOption('wcf_purchase_on') == 'all' && ($prev['type'] == 'upsell' || $prev['type'] == 'downsell')) {
				$wcf_offer_step_id = $order->get_meta('pys_wcf_last_offer_step',true);
			}
			if($prev['type'] == 'checkout'){
				$wcf_checkout_products = getWcfFlowCheckoutProducts();
			}

		}

		// if no offers products  load all products from order

		$order_items = $this->filter_order_items($order,$wcf_offer_step_id);

		$products_data = $this->prepare_order_items($order_items,$wcf_offer_step_id,$wcf_checkout_products);


		if(empty($products_data) && !PYS()->getOption("woo_purchase_not_fire_for_zero_items")) return null;

		// add sipping to total value for offer wcf product
		if($wcf_offer_step_id && isWcfSeparateOrders()) {
			$shipping_cost = wcf_get_offer_shipping($wcf_offer_step_id);
			$shipping_tax = 0;
		} else {
			$shipping_tax = (float) $order->get_shipping_tax( 'edit' );
			$shipping_cost = ((float)$order->get_shipping_total( 'edit' ));
		}


		$args = [
			'order_id'      => $order_id,
			'currency'      => $order->get_currency(),
			'shipping_cost' => $shipping_cost,
			'shipping_tax'  => $shipping_tax,
			'products'      => $products_data,
			'coupon_used'   => 'no',
			'coupon_name'   => '',
			'shipping'      => '',
			'town'          => $order->get_billing_city(),
			'state'         => $order->get_billing_state(),
			'country'       => $order->get_billing_country(),
		];

        if(PYS()->getOption("enable_woo_payment_method") ) {
            $args['payment_method'] = $order->get_payment_method_title() ?? '';
        }

		// Add AWCDP order reference to args for value calculation
		if ($awcdp_order) {
			$args['awcdp_order'] = $awcdp_order;
		}

		$fees = $order->get_fees();
		$fee_amount = 0;

		foreach ($fees as $fee) {
			$fee_amount += $fee->get_total();
		}
		if($fee_amount > 0){
			$args['fees'] = $fee_amount;
		}

        if(!is_null($this->isNewCustomer)) {
            $args['new_customer'] = $this->isNewCustomer;
        }


		if( PYS()->getOption("enable_woo_transactions_count_param")
		    || PYS()->getOption("enable_woo_predicted_ltv_param")
		    || PYS()->getOption("enable_woo_average_order_param")) {
			$customer_params = $this->getCustomerTotals($order_id);

			$args['predicted_ltv'] = isset($customer_params['ltv']) ? $customer_params['ltv'] : 0;
			$args['average_order'] = isset($customer_params['avg_order_value']) ? $customer_params['avg_order_value'] : 0;
			$args['transactions_count'] = isset($customer_params['orders_count']) ? $customer_params['orders_count'] : 0;
		}

		// coupons
		if(isWooCommerceVersionGte("3.7.0")) {
			$couponCodes = $order->get_coupon_codes();
		} else {
			$couponCodes = $order->get_used_coupons();
		}

		if ( count($couponCodes) > 0 ) {

			$args['coupon_used'] = 'yes';
			$args['coupon_name'] = implode( ', ', $couponCodes );

		}

		// shipping method
		if ( $shipping_methods = $order->get_items( 'shipping' ) ) {

			$labels = array();
			foreach ( $shipping_methods as $shipping ) {
				$labels[] = $shipping['name'] ? $shipping['name'] : null;
			}
			$args['shipping'] = implode( ', ', $labels );
		}


		$event->args = $args;

		$event->addPayload(['woo_order' => $order_id]);
		return $event;
	}

	private function create_refund_event($eventId,$order_id,$category_id = null) {
		$event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
		$wcf_offer_step_id = 0;
		$order = wc_get_order($order_id);
		$wcf_checkout_products = [];

		if(!$order) return null;


		// if no offers products  load all products from order

		$order_items = $this->filter_order_items($order,$wcf_offer_step_id);

		$products_data = $this->prepare_order_items($order_items,$wcf_offer_step_id,$wcf_checkout_products);


		if(empty($products_data)) return null;

		// add sipping to total value for offer wcf product
		if($wcf_offer_step_id && isWcfSeparateOrders()) {
			$shipping_cost = wcf_get_offer_shipping($wcf_offer_step_id);
			$shipping_tax = 0;
		} else {
			$shipping_tax = (float) $order->get_shipping_tax( 'edit' );
			$shipping_cost = ((float)$order->get_shipping_total( 'edit' ));
		}

		$args = [
			'order_id'      => $order_id,
			'currency'      => $order->get_currency(),
			'shipping_cost' => $shipping_cost,
			'shipping_tax'  => $shipping_tax,
			'products'      => $products_data,
			'coupon_used'   => 'no',
			'coupon_name'   => '',
			'shipping'      => '',
			'town'          => $order->get_billing_city(),
			'state'         => $order->get_billing_state(),
			'country'       => $order->get_billing_country(),
			'payment_method'=> $order->get_payment_method_title() ?? '',
		];



		if( PYS()->getOption("enable_woo_transactions_count_param")
		    || PYS()->getOption("enable_woo_predicted_ltv_param")
		    || PYS()->getOption("enable_woo_average_order_param")) {
			$customer_params = $this->getCustomerTotals($order_id);

			$args['predicted_ltv'] = isset($customer_params['ltv']) ? $customer_params['ltv'] : 0;
            $args['average_order'] = isset($customer_params['avg_order_value']) ? $customer_params['avg_order_value'] : 0;
            $args['transactions_count'] = isset($customer_params['orders_count']) ? $customer_params['orders_count'] : 0;
		}

		// coupons
		if(isWooCommerceVersionGte("3.7.0")) {
			$couponCodes = $order->get_coupon_codes();
		} else {
			$couponCodes = $order->get_used_coupons();
		}

		if ( count($couponCodes) > 0 ) {

			$args['coupon_used'] = 'yes';
			$args['coupon_name'] = implode( ', ', $couponCodes );

		}

		// shipping method
		if ( $shipping_methods = $order->get_items( 'shipping' ) ) {

			$labels = array();
			foreach ( $shipping_methods as $shipping ) {
				$labels[] = $shipping['name'] ? $shipping['name'] : null;
			}
			$args['shipping'] = implode( ', ', $labels );
		}


		$event->args = $args;

		$event->addPayload([
            'woo_order' => $order_id,
            'eventID'   => EventIdGenerator::guidv4()
        ]);
		return $event;
	}


	private function filter_order_items($order,$wcf_offer_step_id) {
		$order_items = [];
		if($wcf_offer_step_id) {

			// remove from order all products except offer
			if(!isWcfSeparateOrders()) {

				$product = get_post_meta( $wcf_offer_step_id, 'wcf-offer-product', true );

				if(!empty($product)) {
					foreach ( $order->get_items() as $line_item ) {
						$product_id = empty($line_item['variation_id']) ? $line_item['product_id'] : $line_item['variation_id'];
						if($product_id == $product[0]) {
							$order_items[] = $line_item;
						}
					}
				}
			}
		}
		if(empty($order_items)) {
            if ($order instanceof \AWCDP_Order) {
                // For partial payment orders, get the parent order to retrieve actual products
                $parent_order_id = $order->get_parent_id();
                if ($parent_order_id) {
                    $parent_order = wc_get_order($parent_order_id);
                    if ($parent_order) {
                        // Use parent order items instead of partial payment order items
                        $order = $parent_order;
                    }
                }
            }
			$order_items = $order->get_items();
		}
		return $order_items;
	}
	/**
	 * @param \WC_Order_Item_Product[] $order_items
	 * @param $wcf_offer_step_id // optional id of offer step from cart flow plugin
	 * @return array
	 */
	private function prepare_order_items($order_items,$wcf_offer_step_id = false,$wcf_checkout_products = []) {
		$products_data = [];
		foreach ($order_items as $line_item) {
			if( !($line_item instanceof \WC_Order_Item_Product)) continue;

			$product_id = empty($line_item['variation_id']) ? $line_item['product_id'] : $line_item['variation_id'];
			$product = wc_get_product($product_id);

			if(!$product) continue;



			if ( $product->get_type() == 'variation' ) {
				$parent_id = $product->get_parent_id(); // get terms from parent
				$tags = getObjectTerms( 'product_tag', $parent_id );
				$categories = getObjectTermsWithId( 'product_cat', $parent_id );
				$variation_name = implode("/", $product->get_variation_attributes());
			} else {
				$tags = getObjectTerms( 'product_tag', $product->get_id() );
				$categories = getObjectTermsWithId( 'product_cat', $product->get_id() );
				$variation_name = "";
			}

			$sale_price = -1;
//            // need move to filter
			if($wcf_offer_step_id) {
				$sale_price = getWfcProductSalePrice($product,getWcfOfferProduct($wcf_offer_step_id)); // find sale prise for offer product
			} elseif (!empty($wcf_checkout_products)) {
				foreach ($wcf_checkout_products as $product_data) { // find sale prise for offer checkout products
					if($product_id == $product_data['product']) {
						$sale_price = getWfcProductSalePrice($product,$product_data);
					}
				}
			}
			$price = getWooProductPriceToDisplay($product->get_id(),1,$sale_price);

			$product_data = [
				'product_id'    => $product->get_id(),
				'parent_id'     => $product->get_parent_id(),
				'type'          => $product->get_type(),
				'tags'          => $tags,
				'categories'    => $categories,
				'quantity'      => $line_item['qty'],
				'price'         => $price, // price for single product
				'total'         => pys_round($line_item['total']),
				'total_tax'     => pys_round($line_item['total_tax']),
				'subtotal'      => pys_round($line_item['subtotal']),
				'subtotal_tax'  => pys_round($line_item['subtotal_tax']),
				'name'          => $product->get_name(),
				'variation_name'=> $variation_name
			];

			$products_data[] = $product_data;
		}
		return $products_data;
	}


	function getSelectContentEvents($eventId) {
		$events = [];
		if(!GA()->getOption('woo_select_content_enabled')) {
			return false;
		}
		if($eventId == 'woo_select_content_category' && GA()->getOption('woo_enable_list_category')) {
			global $posts;

			$product_category = "";
			$product_category_slug = "";
			$term = get_term_by( 'slug', get_query_var( 'term' ), 'product_cat' );

			if ( $term ) {
				$product_category = $term->name;
				$product_category_slug = $term->slug;
			}

			$list_name =  !empty($product_category) && GA()->getOption('woo_view_item_list_track_name') ? 'Category - '.$product_category : 'Category';
			$list_id =  !empty($product_category_slug) && GA()->getOption('woo_view_item_list_track_name') ? 'category_'.$product_category_slug : 'category';

			for ( $i = 0; $i < count( $posts ) && $i < 10; $i ++ ) {

				if ( $posts[ $i ]->post_type !== 'product' ) {
					continue;
				}



				$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
				$item = array(
					'id'            => GA\Helpers\getWooProductContentId($posts[ $i ]->ID),
					'name'          => $posts[ $i ]->post_title,
					'quantity'      => 1,
					'price'         => getWooProductPriceToDisplay( $posts[ $i ]->ID ),
					'item_list_name'     => $list_name,
					'item_list_id'  => $list_id,
					'affiliation' => PYS_SHOP_NAME
				);

				$category = GA()->getCategoryArrayWoo($posts[ $i ]->ID);
				if(!empty($category))
				{
					$item = array_merge($item, $category);
				}
				$brand = getBrandForWooItem($posts[ $i ]->ID);
				if($brand)
				{
					$item['item_brand'] = $brand;
				}

				$event->addParams(['items'           => array($item),]);
				$event->args = $posts[ $i ]->ID;
				$events[]=$event;
			}
		}
		if ($eventId == 'woo_select_content_single' && GA()->getOption('woo_enable_list_related')) {
			$product = wc_get_product( get_the_ID() );
			if ($product && is_a($product, 'WC_Product')){
                $default_args = array(
                    'posts_per_page' => 4,
                    'columns'        => 4,
                );

                $args = apply_filters( 'woocommerce_output_related_products_args', $default_args );

                $args = wp_parse_args( $args, $default_args );

				$related_products = array_map( 'wc_get_product', GA\Helpers\custom_wc_get_related_products( get_the_ID(), $args['posts_per_page'], $product->get_upsell_ids() ));
				$related_products = wc_products_array_orderby( $related_products, 'rand', 'desc' );

				if ( $product ) {
					$product_name = $product->get_name();
					$product_slug = $product->get_slug();
				}

				$list_name =  !empty($product_name) && GA()->getOption('woo_view_item_list_track_name') ? 'Related Products - '.$product_name : 'Related Products';
				$list_id =  !empty($product_slug) && GA()->getOption('woo_view_item_list_track_name') ? 'related_products_'.$product_slug : 'related_products';

				foreach ( $related_products as $relate) {

					if(!$relate) continue;
					$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
					$item = array(
						'id'            => GA\Helpers\getWooProductContentId($relate->get_id()),
						'name'          => $relate->get_title(),
						'quantity'      => 1,
						'price'         => getWooProductPriceToDisplay( $relate->get_id() ),
						'item_list_name'     => $list_name,
						'item_list_id'  => $list_id,
						'affiliation' => PYS_SHOP_NAME
					);
					$category = GA()->getCategoryArrayWoo($relate->get_id());
					if(!empty($category))
					{
						$item = array_merge($item, $category);
					}
					$brand = getBrandForWooItem($relate->get_id());
					if($brand)
					{
						$item['item_brand'] = $brand;
					}

					$event->addParams(['items' => array($item),]);
					$event->args = $relate->get_id();
					$events[]=$event;
				}
			}


		}
		if (($eventId == 'woo_select_content_shop' || $eventId == 'woo_select_content_search') && GA()->getOption('woo_enable_list_shop')) {
			global $posts;

			if($eventId == "woo_select_content_shop") {
				$list_name = GA()->getOption('woo_track_item_list_name') ? 'Shop page' : '';
				$list_id = GA()->getOption('woo_track_item_list_id') ? 'shop_page' : '';
			} else {
				$list_name = 'Search Results';
				$list_id = 'search_results';
			}

			foreach ($posts as $post) {
				if( $post->post_type != 'product') continue;
				$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());

				$item = array(
					'id'            => GA\Helpers\getWooProductContentId($post->ID),
					'name'          => $post->post_title ,
					'quantity'      => 1,
					'price'         => getWooProductPriceToDisplay( $post->ID ),
					'post_id'       => $post->ID,
					'item_list_name'     => $list_name,
					'item_list_id'  => $list_id,
					'affiliation' => PYS_SHOP_NAME
				);

				$category = GA()->getCategoryArrayWoo($post->ID);
				if(!empty($category))
				{
					$item = array_merge($item, $category);
				}
				$brand = getBrandForWooItem($post->ID);
				if($brand)
				{
					$item['item_brand'] = $brand;
				}

				$event->addParams(['items' => array($item),]);
				$event->args = $post->ID;
				$events[]=$event;
			}

		}
		if ($eventId == 'woo_select_content_tag' && GA()->getOption('woo_enable_list_tag')) {
			global $posts, $wp_query;

			$tag_obj = $wp_query->get_queried_object();
			if ( $tag_obj ) {
				$product_tag = single_tag_title( '', false );
				$product_tag_slug = $tag_obj->slug;
			}

			$list_name =  !empty($product_tag) &&  GA()->getOption('woo_view_item_list_track_name') ? 'Tag - '.$product_tag : 'Tag';
			$list_id =  !empty($product_tag_slug) &&  GA()->getOption('woo_view_item_list_track_name') ? 'tag_'.$product_tag_slug : 'tag';

			for ($i = 0, $iMax = count($posts); $i < $iMax; $i ++ ) {

				if ( $posts[ $i ]->post_type !== 'product' ) {
					continue;
				}
				$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
				$item = array(
					'id'            => GA\Helpers\getWooProductContentId($posts[ $i ]->ID),
					'name'          => $posts[ $i ]->post_title,
					'quantity'      => 1,
					'price'         => getWooProductPriceToDisplay( $posts[ $i ]->ID ),
					'item_list_name'     => $list_name,
					'item_list_id'  => $list_id,
					'affiliation' => PYS_SHOP_NAME
				);

				$category = GA()->getCategoryArrayWoo($post->ID);
				if(!empty($category))
				{
					$item = array_merge($item, $category);
				}
				$brand = getBrandForWooItem($post->ID);
				if($brand)
				{
					$item['item_brand'] = $brand;
				}
				$event->addParams(['items' => array($item),]);
				$event->args = $posts[ $i ]->ID;
				$events[]=$event;
			}
		}

		return $events;
	}

    function getViewListEvents($eventId) {
        $products_data = [];
        $event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());
        if(($eventId == 'woo_view_item_list' || $eventId == 'woo_view_category') && GA()->getOption('woo_enable_list_category')) {
            global $posts;
            for ($i = 0; $i < count($posts) && $i < 10; $i++) {
                // Check if the current post is a product
                if ($posts[$i]->post_type !== 'product') {
                    continue;
                }
                // Get the WooCommerce product object from the current post
                $product = wc_get_product($posts[$i]->ID); // Fetch the WooCommerce product
                if (!$product) {
                    continue; // Skip if the product object is not found
                }

                // Get tags and categories for the product
                $tags = getObjectTerms('product_tag', $product->get_id());
                $categories = getObjectTermsWithId('product_cat', $product->get_id());

                $price = getWooProductPriceToDisplay($product->get_id(), 1);

                $product_data = [
                    'product_id'    => $product->get_id(),
                    'parent_id'     => $product->get_parent_id(),
                    'type'          => $product->get_type(),
                    'tags'          => $tags,
                    'categories'    => $categories,
                    'price'         => $price,
                    'name'          => $product->get_name(),
                ];
                $products_data[] = $product_data;
            }
        }
        if(empty($products_data)) return null;

        $event->args = [
            'products'  => $products_data,
        ];

        return $event;
    }

	function getRemoveFromCartEvents($eventId) {
		$events = [];
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
			$event->args = ['key'=>$cart_item_key,'item'=>$cart_item];
			$events[]=$event;
		}
		return $events;
	}

	function getAddToCartOnCartEvent($eventId) {
		$event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());

		$products_data = $this->getCartProductData();
		if(count($products_data) == 0) return null;

		$event->args = [
			'products'  => $products_data,
			'coupon'    => $this->getCartCoupon()
		];
		return $event;
	}
	function getCartCoupon() {
		$coupons =  WC()->cart->get_applied_coupons();
		if ( count($coupons) > 0 ) {
			$firstCoupon = reset($coupons); // Получить первый элемент массива
			return $firstCoupon;
		}
		return null;
	}

	function getInitCheckoutEvent($eventId) {
		$event = new SingleEvent($eventId,EventTypes::$STATIC,self::getSlug());

		$products_data = $this->getCartProductData();
		if(count($products_data) == 0) return null;

		$event->args = [
			'products' => $products_data,
			'coupon'    => $this->getCartCoupon()
		];
		return $event;
	}

	function getPaypalEvent($eventId) {
		$event = new SingleEvent($eventId,EventTypes::$DYNAMIC,self::getSlug());
		$products_data = $this->getCartProductData();
		if(count($products_data) == 0) return null;

		$event->args = [
			'products' => $products_data,
			'coupon'    => $this->getCartCoupon()
		];
		return $event;
	}

	function getCartProductData() {
		$products_data = [];
		foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
			$product_id = empty($cart_item['variation_id']) ? $cart_item['product_id'] : $cart_item['variation_id'];
			$product = wc_get_product($product_id);

			if(!$product) continue;

			if ( $product->get_type() == 'variation' ) {
				$parent_id = $product->get_parent_id(); // get terms from parent
				$tags = getObjectTerms( 'product_tag', $parent_id );
				$categories = getObjectTermsWithId( 'product_cat', $parent_id );
				$variation_name = implode("/", $product->get_variation_attributes());
			} else {
				$tags = getObjectTerms( 'product_tag', $product->get_id() );
				$categories = getObjectTermsWithId( 'product_cat', $product->get_id() );
				$variation_name = "";
			}
			$sale_price = -1;


			$price = getWooProductPriceToDisplay($product_id, 1,$sale_price);
			$product_data = [
				'product_id'    => $product->get_id(),
				'parent_id'     => $product->get_parent_id(),
				'type'          => $product->get_type(),
				'tags'          => $tags,
				'categories'    => $categories,
				'quantity'      => $cart_item['quantity'],
				'price'         => $price,
				'total'         => isset($cart_item['line_total']) ? pys_round($cart_item['line_total']) : 0, // with coupon sale
				'total_tax'     => isset($cart_item['line_tax']) ? pys_round($cart_item['line_tax']) : 0,
				'subtotal'      => isset($cart_item['line_subtotal']) ? pys_round($cart_item['line_subtotal']) : 0,
				'subtotal_tax'  => isset($cart_item['line_subtotal_tax']) ? pys_round($cart_item['line_subtotal_tax']) : 0,
				'name'          => $product->get_name(),
				'variation_name'=> $variation_name
			];

			$products_data[] = $product_data;
		}

		return $products_data;
	}

	/**
	 * @param SingleEvent $event
	 * @param $filter
	 */
	static function filterEventProductsBy($event,$filters,$pixel) {
		$products = [];
        if(!isset($event->args['products'])) return $products;
        foreach ($event->args['products'] as $productData) {
            $includeProduct = ($pixel->logicConditionalTrack === 'track'); // Initially include for 'track', exclude for 'dont_track'

            foreach ($filters as $filter) {
                if ($filter['filter'] == 'in_product_cat') {
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
                } elseif ($filter['filter'] == 'in_product_tag') {
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
                } elseif ($filter['filter'] == 'product') {
                    $originalProduct = wc_get_product($productData['product_id']);
                    if ($originalProduct){
                        $product_id = $originalProduct->get_id();
                        $parent_id  = $originalProduct->get_parent_id();
                        if ($pixel->logicConditionalTrack == 'track') {
                            if (in_array($filter['sub_id'],[$product_id, $parent_id])) {
                                $includeProduct = true;
                                break;
                            } else {
                                $includeProduct = false;
                            }
                        } elseif ($pixel->logicConditionalTrack == 'dont_track') {
                            if (in_array($filter['sub_id'],[$product_id, $parent_id])) {
                                $includeProduct = false;
                                break;
                            } else {
                                $includeProduct = true;
                            }
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

	public function getCustomerTotals($order_id = null) {

        // setup and cache params
        if ( empty( $this->wooCustomerTotals ) ) {
            $this->wooCustomerTotals = getWooCustomerTotals(0,$order_id);
        }

        return $this->wooCustomerTotals;

	}
    public function setNewCustomer($order_id)
    {
        if(!is_null($this->isNewCustomer)) return;

        global $wpdb;
        $customer_id = 0;
        $customer_email = '';

        if (isWooUseHPStorage()) {
            $order_data = $wpdb->get_row($wpdb->prepare(
                "SELECT customer_id, billing_email FROM {$wpdb->prefix}wc_orders WHERE id = %d",
                $order_id
            ));
            if ($order_data) {
                $customer_id = (int) $order_data->customer_id;
                $customer_email = $order_data->billing_email;
            }
        } else {
            $customer_id = (int) get_post_meta($order_id, '_customer_user', true);
            $customer_email = get_post_meta($order_id, '_billing_email', true);
        }

        if (!$customer_id && !$customer_email) {
            $this->isNewCustomer = true;
            return;
        }

        $start_date_gmt = gmdate('Y-m-d H:i:s', time() - DAY_IN_SECONDS * 540);
        $found_order = null;

        if (isWooUseHPStorage()) {
            $table_name = $wpdb->prefix . 'wc_orders';
            if ($customer_id) {
                $query = $wpdb->prepare(
                    "SELECT id FROM {$table_name} 
                    WHERE id != %d 
                    AND status IN ('wc-completed', 'wc-processing', 'wc-on-hold') 
                    AND date_created_gmt > %s 
                    AND customer_id = %d 
                    LIMIT 1",
                    $order_id, $start_date_gmt, $customer_id
                );
            } else {
                $query = $wpdb->prepare(
                    "SELECT id FROM {$table_name} 
                    WHERE id != %d 
                    AND status IN ('wc-completed', 'wc-processing', 'wc-on-hold') 
                    AND date_created_gmt > %s 
                    AND billing_email = %s 
                    LIMIT 1",
                    $order_id, $start_date_gmt, $customer_email
                );
            }
            $found_order = $wpdb->get_var($query);
        } else {
            if ($customer_id) {
                $query = $wpdb->prepare(
                    "SELECT p.ID FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_customer_user'
                    WHERE p.ID != %d
                    AND p.post_type = 'shop_order'
                    AND p.post_status IN ('wc-completed', 'wc-processing', 'wc-on-hold')
                    AND p.post_date_gmt > %s
                    AND pm.meta_value = %d
                    LIMIT 1",
                    $order_id, $start_date_gmt, $customer_id
                );
            } else {
                $query = $wpdb->prepare(
                    "SELECT p.ID FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_billing_email'
                    WHERE p.ID != %d
                    AND p.post_type = 'shop_order'
                    AND p.post_status IN ('wc-completed', 'wc-processing', 'wc-on-hold')
                    AND p.post_date_gmt > %s
                    AND pm.meta_value = %s
                    LIMIT 1",
                    $order_id, $start_date_gmt, $customer_email
                );
            }
            $found_order = $wpdb->get_var($query);
        }

        $this->isNewCustomer = empty($found_order);
    }

    public function getNewCustomer()
    {
        return $this->isNewCustomer;
    }

    /**
     * Get current subscription ID from filter
     *
     * @return int|null
     */
    private function getSubscriptionInfo($filter = null) {

        $subscribe_info = apply_filters( 'pys_woo_subscription', null );

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
        if ( ! class_exists( 'WC_Subscription' ) ) {
            PYS()->getLog()->debug( "{$context}: WOO Subscription class not available" );
            return null;
        }

        $subscription = new \WC_Subscription( $subscription_id );

        if ( ! $subscription || ! $subscription_id ) {
            PYS()->getLog()->debug( "{$context}: Failed to load subscription", array( 'subscription_id' => $subscription_id ) );
            return null;
        }

        return $subscription;
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

        // Get subscription name (product title)
        $subscription_name = '';
        if ( $subscription->get_items() ) {
            foreach ( $subscription->get_items() as $item ) {
                $product = $item->get_product();

                if ( ! $product ) {
                    continue;
                }

                $subscription_name = $product->get_name();
                break;
            }
        }

        // Get parent order
        $parent_order = $subscription->get_parent();

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array(
            'subscription_id' => $subscription->get_id(),
            'woo_order' => $parent_order ? $parent_order->get_id() : 0
        ) );

        // Required fields
        $args = array(
            'subscription_id' => $subscription->get_id(),
            'subscription_name' => $subscription_name,
            'currency' => $subscription->get_currency(),
        );

        if ( $parent_order ) {
            $args['order_id'] = wooMapOrderId($parent_order->get_id());
        }

        // Add product information from subscription
        $args['products'] = $this->getWooSubscriptionProducts( $subscription );

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

        // Get currency
        $currency = $subscription->get_currency();

        // Get subscription name (product title)
        $subscription_name = '';
        if ( $subscription->get_items() ) {
            foreach ( $subscription->get_items() as $item ) {
                $product = $item->get_product();

                if ( ! $product ) {
                    continue;
                }

                $subscription_name = $product->get_name();
                break;
            }
        }

        $predicted_ltv = 0;
        // Calculate predicted LTV using LTV Calculator
        if(PYS()->getOption('enable_woo_predicted_ltv_param')){
            if ( function_exists( '\PixelYourSite\WOO_LTV_Calculator' ) ) {
                $ltv_calculator = \PixelYourSite\WOO_LTV_Calculator();
                $predicted_ltv = $ltv_calculator->calculate_predicted_ltv( $subscription );

                // Save LTV to customer meta
                if ( $subscription->get_customer_id() ) {
                    $ltv_calculator->save_customer_ltv( $subscription->get_customer_id(), $subscription->get_id(), $predicted_ltv );
                }
            } else {
                // Fallback: simple calculation if LTV Calculator not available
                $initial_amount = (float) $subscription->get_total_initial_payment();
                $recurring_amount = (float) $subscription->get_total();
                $predicted_ltv = $initial_amount;

                // Get end date to check if subscription is limited
                $end_date = $subscription->get_date( 'end' );

                if ( ! empty( $end_date ) && $end_date > 0 ) {
                    // Limited subscription - estimate based on dates
                    $start_date = $subscription->get_date( 'start' );
                    $period = $subscription->get_billing_period();
                    $interval = $subscription->get_billing_interval();

                    // Simple estimation: 12 months worth
                    $estimated_payments = 12;
                    $predicted_ltv += ( $recurring_amount * $estimated_payments );
                } else {
                    // Unlimited subscription - estimate 12 months worth of payments
                    $estimated_payments = 12;
                    $predicted_ltv += ( $recurring_amount * $estimated_payments );
                }
            }
            }

        // Value for subscription created is the initial amount
        $value = (float) $subscription->get_total_initial_payment();

        // Get parent order
        $parent_order = $subscription->get_parent();

        $shipping_tax = (float) $subscription->get_shipping_tax( 'edit' );
        $shipping_cost = (float) $subscription->get_shipping_total( 'edit' );

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array(
            'subscription_id' => $subscription->get_id(),
            'woo_order' => $parent_order ? $parent_order->get_id() : 0
        ) );
        // Required fields
        $args = array(
            'subscription_id' => $subscription->get_id(),
            'subscription_name' => $subscription_name,
            'shipping_cost' => $shipping_cost,
            'shipping_tax'  => $shipping_tax,
            'value' => round( $value, 2 ),
            'currency' => $currency,
            'predicted_ltv' => round( $predicted_ltv, 2 ),
        );

        // Add product information from subscription
        $args['products'] = $this->getWooSubscriptionProducts( $subscription );
        $args['order_id'] = $parent_order ? wooMapOrderId($parent_order->get_id()) : 0;

        $event->args = $args;

        return $event;
    }

    /**
     * Get products from WooCommerce subscription
     *
     * @param WC_Subscription $subscription Subscription object
     * @return array Products data
     */
    private function getWooSubscriptionProducts( $subscription ) {
        $products = array();

        if ( ! $subscription || ! $subscription->get_items() ) {
            return $products;
        }

        foreach ( $subscription->get_items() as $item ) {
            $product = $item->get_product();

            if ( ! $product ) {
                continue;
            }

            $product_id = $product->get_id();
            $parent_id = $product->get_parent_id();

            // Get categories
            $categories = array();
            $term_ids = $product->get_category_ids();
            foreach ( $term_ids as $term_id ) {
                $term = get_term( $term_id, 'product_cat' );
                if ( $term && ! is_wp_error( $term ) ) {
                    $categories[] = array(
                        'term_id' => $term_id,
                        'name' => $term->name,
                        'slug' => $term->slug,
                    );
                }
            }

            // Get tags
            $tags = array();
            $tag_ids = $product->get_tag_ids();
            foreach ( $tag_ids as $tag_id ) {
                $tag = get_term( $tag_id, 'product_tag' );
                if ( $tag && ! is_wp_error( $tag ) ) {
                    $tags[] = $tag->name;
                }
            }

            $product_data = array(
                'product_id' => $product_id,
                'parent_id' => $parent_id,
                'name' => $product->get_name(),
                'quantity' => $item->get_quantity(),
                'price' => (float) $item->get_total() / max( 1, $item->get_quantity() ),
                'total' => (float) $item->get_total(),
                'total_tax' => (float) $item->get_total_tax(),
                'subtotal' => (float) $item->get_subtotal(),
                'subtotal_tax' => (float) $item->get_subtotal_tax(),
                'type' => $product->get_type(),
                'categories' => $categories,
                'tags' => $tags,
            );

            $products[] = $product_data;
        }

        return $products;
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

        // Get currency
        $currency = $subscription->get_currency();

        // Get subscription name (product title)
        $subscription_name = '';
        if ( $subscription->get_items() ) {
            foreach ( $subscription->get_items() as $item ) {
                $product = $item->get_product();

                if ( ! $product ) {
                    continue;
                }

                $subscription_name = $product->get_name();
                break;
            }
        }

        // Get last order (renewal order)
        $last_order = $subscription->get_last_order('all');

        // Value for renewal is the last order total
        $value = $last_order ? (float) $last_order->get_total() : (float) $subscription->get_total();

        // Calculate number of transactions (renewals + initial purchase)
        $number_of_transactions = 1; // Start with initial purchase

        // Get all renewal orders for this subscription
        $renewal_orders = $subscription->get_related_orders( 'all', 'renewal' );

        if ( ! empty( $renewal_orders ) ) {
            $number_of_transactions += count( $renewal_orders );
        }

        // Calculate subscription value (all renewals + initial purchase)
        $subscription_value = (float) $subscription->get_total_initial_payment();

        if ( ! empty( $renewal_orders ) ) {
            foreach ( $renewal_orders as $order_id ) {
                $order = wc_get_order( $order_id );
                if ( $order ) {
                    $subscription_value += (float) $order->get_total();
                }
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array(
            'subscription_id' => $subscription->get_id(),
            'woo_order' => $last_order ? $last_order->get_id() : 0
        ) );

        // Required fields
        $args = array(
            'subscription_id' => $subscription->get_id(),
            'subscription_name' => $subscription_name,
            'value' => round( $value, 2 ),
            'currency' => $currency,
            'number_of_transactions' => $number_of_transactions,
            'subscription_value' => round( $subscription_value, 2 ),
        );

        // Add product information from subscription
        $args['products'] = $this->getWooSubscriptionProducts( $subscription );
        $args['order_id'] = $last_order ? wooMapOrderId($last_order->get_id()) : 0;

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

        // Get parent order
        $parent_order = $subscription->get_parent();

        // Get subscription name (product title)
        $subscription_name = '';
        if ( $subscription->get_items() ) {
            foreach ( $subscription->get_items() as $item ) {
                $product = $item->get_product();

                if ( ! $product ) {
                    continue;
                }

                $subscription_name = $product->get_name();
                break;
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array(
            'subscription_id' => $subscription->get_id(),
            'woo_order' => $parent_order ? $parent_order->get_id() : 0
        ) );

        // Required field only
        $args = array(
            'subscription_id' => $subscription->get_id(),
            'subscription_name' => $subscription_name,
        );

        if(!empty($parent_order)) {
            $args['order_id'] = $parent_order->get_id();
        }
        $args['currency'] = $subscription->get_currency();

        // Add product information from subscription
        $args['products'] = $this->getWooSubscriptionProducts( $subscription );

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

        // Get parent order
        $parent_order = $subscription->get_parent();

        // Get subscription name (product title)
        $subscription_name = '';
        if ( $subscription->get_items() ) {
            foreach ( $subscription->get_items() as $item ) {
                $product = $item->get_product();

                if ( ! $product ) {
                    continue;
                }

                $subscription_name = $product->get_name();
                break;
            }
        }

        // Create event
        $event = new SingleEvent( $eventId, EventTypes::$STATIC, self::getSlug() );
        $event->addPayload( array(
            'subscription_id' => $subscription->get_id(),
            'woo_order' => $parent_order ? $parent_order->get_id() : 0
        ) );

        // Required field only
        $args = array(
            'subscription_id' => $subscription->get_id(),
            'subscription_name' => $subscription_name,
        );
        if(!empty($parent_order)) {
            $args['order_id'] = $parent_order->get_id();
        }
        $args['currency'] = $subscription->get_currency();

        // Add product information from subscription
        $args['products'] = $this->getWooSubscriptionProducts( $subscription );

        $event->args = $args;

        return $event;
    }
}

/**
 * @return EventsWoo
 */
function EventsWoo() {
	return EventsWoo::instance();
}

EventsWoo();
