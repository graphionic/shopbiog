<?php
/**
 * Server Events Manager
 *
 * Centralizes registration of WooCommerce and EDD hooks for server-side events
 *
 * @package PixelYourSite
 */

namespace PixelYourSite;

use WC_Subscription;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class ServerEventsManager
 *
 * Manages server-side event hooks for WooCommerce and EDD
 */
class ServerEventsManager {

    /**
     * Singleton instance
     *
     * @var ServerEventsManager
     */
    private static $_instance;

    /**
     * Stores pixel IDs that have fired during current request
     *
     * @var array
     */
    private static $firedPixels = array();

    /**
     * Logger instance
     *
     * @var object
     */
    private $logger;

    /**
     * Current subscription ID for event context
     *
     * @var int|null
     */
    private $current_subscription_id = null;

    /**
     * Current license ID for event context
     *
     * @var int|null
     */
    private $current_license_id = null;

    /**
     * Current payment ID for event context
     *
     * @var int|null
     */
    private $current_payment_id = null;

    /**
     * Get singleton instance
     *
     * @return ServerEventsManager
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Add fired pixel ID(s) to the tracking array
     *
     * @param string $platform Platform name (facebook, ga, tiktok, pinterest)
     * @param string|array $pixel_ids Pixel ID or array of pixel IDs
     * @return void
     */
    public static function addFiredPixel( $platform, $pixel_ids ) {
        if ( ! is_array( $pixel_ids ) ) {
            $pixel_ids = array( $pixel_ids );
        }

        if ( ! isset( self::$firedPixels[ $platform ] ) ) {
            self::$firedPixels[ $platform ] = array();
        }

        foreach ( $pixel_ids as $pixel_id ) {
            if ( ! empty( $pixel_id ) && ! in_array( $pixel_id, self::$firedPixels[ $platform ], true ) ) {
                self::$firedPixels[ $platform ][] = $pixel_id;
            }
        }
    }

    /**
     * Get all fired pixels
     *
     * @return array Array of fired pixels grouped by platform
     */
    public static function getFiredPixels() {
        return self::$firedPixels;
    }

    /**
     * Reset fired pixels array
     *
     * @return void
     */
    public static function resetFiredPixels() {
        self::$firedPixels = array();
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->logger = PYS()->getLog();
        // Skip if cache preload

        add_action( 'plugins_loaded', array( $this, 'init' ), 30 );
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    public function init() {
        if ( PYS()->isCachePreload() ) {
            return;
        }

        // WooCommerce hooks
        if ( isWooCommerceActive() ) {
            // 1. Extracting an array of statuses from settings
            $selected_statuses = (array) PYS()->getOption('woo_order_advanced_purchase_status');

            // 2. We go through each status in a loop and assign the same function to it.
            foreach ( $selected_statuses as $status_with_prefix ) {

                // We clear it from 'wc-', since the hook woocommerce_order_status_{status} does not contain it.
                $clean_status = str_replace( 'wc-', '', $status_with_prefix );

                // Registering a dynamic hook
                add_action( 'woocommerce_order_status_' . $clean_status, array( $this, 'woo_completed_purchase' ), 10, 2 );
            }
            add_action( 'woocommerce_order_status_refunded', array( $this, 'woo_refund_order' ), 30 );

            if(class_exists('WC_Subscriptions')){
                add_action('woocommerce_subscription_payment_complete', array($this, 'woo_subscription_created'), 10, 1);
                add_action('woocommerce_subscription_status_updated', array($this, 'woo_subscription_status_change'), 10, 3);
            }
        }

        // EDD hooks
        if ( isEddActive() ) {
            add_action( 'edd_recurring_record_payment', array( $this, 'edd_recurring_payment' ), 5, 4 );
            add_action( 'edd_complete_purchase', array( $this, 'edd_completed_purchase' ), 5, 1 );
            add_action( 'edd_refund_order', array( $this, 'edd_refund_order' ), 10, 3 );

            // EDD Recurring Subscription hooks
            if ( class_exists( 'EDD_Recurring' ) ) {
                add_action( 'edd_subscription_status_change', array( $this, 'edd_subscription_status_change' ), 10, 3 );
                add_action( 'edd_subscription_post_create', array( $this, 'edd_subscription_created' ), 5, 2 );
                add_action( 'edd_subscription_post_renew', array( $this, 'edd_subscription_renewed' ), 5, 4 );
            }
            // EDD Software Licensing hooks
            if ( class_exists( 'EDD_Software_Licensing' ) ) {
                add_action( 'edd_sl_post_set_status', array( $this, 'edd_license_status_change' ), 10, 2 );
                add_action( 'edd_sl_license_upgraded', array( $this, 'edd_license_upgraded' ), 10, 2 );
                add_action( 'edd_sl_post_license_renewal', array( $this, 'edd_license_renewed' ), 10, 2 );
                add_action( 'edd_sl_store_license', array( $this, 'edd_license_created' ), 10, 4 );
            }

        }

    }

    /**
     * Tracks a completed WooCommerce purchase
     *
     * @param int $order_id The order ID
     * @return void
     */
    public function woo_completed_purchase( $order_id, $order ) {
        $log = $this->logger;

        if ( ! PYS()->getOption( 'woo_advance_purchase_fb_enabled' )
            && ! PYS()->getOption( 'woo_advance_purchase_ga_enabled' )
            && ( ! Tiktok()->enabled() || ( Tiktok()->enabled() && ! Tiktok()->getOption( 'woo_advance_purchase_enabled' ) ) )
            && ( ! Pinterest()->enabled() || ( Pinterest()->enabled() && ! Pinterest()->getOption( 'woo_advance_purchase_enabled' ) ) )
            && !PYS()->getOption("woo_poas_enabled")
        ) {
            return false;
        }

        if ( ! $order
            || ! PYS()->getOption( 'woo_purchase_enabled' )
            || isDisabledForUserRole( $order->get_user() ) ) {
            return false;
        }

        add_filter( 'pys_woo_checkout_order_id', function () use ( $order_id ) {
            return $order_id;
        } );
        if ( $order->get_meta( '_pys_purchase_event_fired', true ) || (PYS()->getOption( 'woo_purchase_on_transaction' ) && $order->get_meta( '_pys_advance_purchase_event_fired', true ))) {
            return false;
        }

        $event = EventsWoo()->getEvent('woo_purchase', false, true);

        if ( $event == null ) {
            return false;
        }
        // eventID is now generated in EventsWoo::getEvent() for proper server-browser deduplication
        // Do NOT override it here - the event already has eventID in payload
        $event->addParams( array( 'advanced_purchase_tracking' => true ) );

        // Send GA server events
        if ( PYS()->getOption( 'woo_advance_purchase_ga_enabled' ) ) {
            GA()->getLog()->debug( "Send completed purchase GA" );
            $gaEvents = GA()->generateEvents( $event );
            if ( ! empty( $gaEvents ) ) {
                GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
            }
        }


// Fire POAS before the Purchase fire-once gate — POAS has its own deduplication
        firePoasForOrder( $order_id );
        // Protection #1: Block ALL AJAX requests (frontend checkout)
        // Note: wp_doing_ajax() only returns true for admin-ajax.php requests
        // Payment gateway webhooks use wc-api parameter and do NOT trigger wp_doing_ajax()
        if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || isset( $_REQUEST['wc-ajax'] ) ) {
            $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'unknown';
            $log->debug( "Blocked woo_completed_purchase: AJAX request detected - action: {$action}" );
            return;
        }

        // Protection #2: Block AngellEYE PayPal for WooCommerce API requests
        // AngellEYE uses wc-api parameter with "angelleye" in the value (case-insensitive)
        if ( isset( $_REQUEST['wc-api'] ) && stripos( $_REQUEST['wc-api'], 'angelleye' ) !== false ) {
            $wc_api = sanitize_text_field( $_REQUEST['wc-api'] );
            $log->debug( "Blocked woo_completed_purchase: AngellEYE PayPal API request detected - wc-api: {$wc_api}" );
            return;
        }

        // Send Facebook server events
        if ( PYS()->getOption( 'woo_advance_purchase_fb_enabled' ) ) {
            Facebook()->getLog()->debug( "Send FB" );
            $fbEvents = Facebook()->generateEvents( $event );
            FacebookServer()->sendEventsNow( $fbEvents );
        }

        // Send TikTok server events
        if ( Tiktok()->enabled()
            && Tiktok()->isServerApiEnabled()
            && Tiktok()->getOption( 'woo_advance_purchase_enabled' )
        ) {
            Tiktok()->getLog()->debug( 'Send Completed Payment Tiktok' );
            $tiktokEvents = Tiktok()->generateEvents( $event );
            TikTokServer()->sendEventsNow( $tiktokEvents );
        }

        // Send Pinterest server events
        if ( Pinterest()->enabled()
            && method_exists( Pinterest(), 'isServerApiEnabled' )
            && Pinterest()->isServerApiEnabled()
            && Pinterest()->getOption( 'woo_advance_purchase_enabled' )
        ) {
            Pinterest()->getLog()->debug( 'Send Checkout Pinterest' );
            $pinterestEvents = Pinterest()->generateEvents( $event );
            PinterestServer()->sendEventsNow( $pinterestEvents );
        }

        // Get fired pixels before saving
        $firedPixels = self::getFiredPixels();

        if ( isWooCommerceVersionGte( '3.0.0' ) ) {
            // WooCommerce >= 3.0
            if ( $order ) {
                $order->update_meta_data( '_pys_advance_purchase_event_fired', true );

                // Save fired pixels to order meta
                if ( ! empty( $firedPixels ) ) {
                    $order->update_meta_data( '_pys_server_fired_pixels', $firedPixels );
                }

                $order->save();
            }
        } else {
            // WooCommerce < 3.0
            update_post_meta( $order_id, '_pys_advance_purchase_event_fired', true );

            // Save fired pixels to order meta
            if ( ! empty( $firedPixels ) ) {
                update_post_meta( $order_id, '_pys_server_fired_pixels', $firedPixels );
            }
        }

        // Reset fired pixels for next request
        self::resetFiredPixels();
    }

    /**
     * Tracks a WooCommerce refund
     *
     * @param int $order_id The order ID
     * @return void
     */
    public function woo_refund_order( $order_id ) {
        $log = $this->logger;
        $log->debug( "Send woo_refund_order" );

        if ( ! PYS()->getOption( 'woo_track_refunds_GA' ) ) {
            return;
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }
        add_filter( 'pys_woo_checkout_order_id', function () use ( $order_id ) {
            return $order_id;
        } );

        $event = EventsWoo()->getEvent( 'woo_refund' );
        if ( $event == null ) {
            return;
        }
        $gaEvents = GA()->generateEvents( $event );
        GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
    }

    public function woo_subscription_created(WC_Subscription $subscription)
    {

        $subscription_id = is_object( $subscription ) ? $subscription->get_id() : $subscription;

        $subscription_info = array(
            'subscription_id' => $subscription_id
        );

        $this->logger->debug( 'Woo Subscription Created', array(
            'subscription_id' => $subscription_id,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'woo_track_subscriptions' ) ) {
            $this->logger->debug( 'Woo subscription tracking is disabled' );
            return;
        }

        if ( !$subscription || !$subscription_id ) {
            $this->logger->debug( 'Failed to load subscription object', array( 'subscription_id' => $subscription_id ) );
            return;
        }

        // Check if this is a renewal payment, not initial subscription creation
        $last_order = $subscription->get_last_order();
        $last_order_id = is_object( $last_order ) ? $last_order->get_id() : $last_order;

        // Determine event type based on subscription status
        $event_id = 'woo_subscription_created';
        $is_trial = false;

        if ( $last_order && function_exists('wcs_order_contains_renewal') && wcs_order_contains_renewal($last_order) ) {
            $event_id = 'woo_subscription_renewal';
            $subscription_info['subscription_id'] = $last_order_id;
            $this->logger->debug( 'Subscription Renewed event triggered', array(
                'subscription_id' => $last_order_id,
                'status' => $subscription->get_status()
            ) );
        } elseif ( ! empty( $subscription->get_trial_period()) ) {
            $event_id = 'woo_start_trial';
            $is_trial = true;
            $subscription_info['trial_period'] = $subscription->get_trial_period();
            $this->logger->debug( 'Start Trial event triggered', array(
                'subscription_id' => $subscription_id,
                'trial_period' => $subscription->get_trial_period(),
                'status' => $subscription->get_status()
            ) );
        } else {
            $this->logger->debug( 'Subscription Created event triggered', array(
                'subscription_id' => $subscription_id,
                'status' => $subscription->get_status()
            ) );
        }

        // Set filter to pass subscription_id to EventsEdd
        add_filter( 'pys_woo_subscription', function () use ( $subscription_info ) {
            return $subscription_info;
        } );

        // Get event from EventsEdd
        $event = EventsWoo()->getEvent( $event_id );

        // Remove filter
        remove_all_filters( 'pys_woo_subscription' );

        if ( $event == null ) {
            $this->logger->debug( 'Event is null, skipping', array( 'event_id' => $event_id ) );
            return;
        }

        // Add eventID for deduplication
        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );

        // Send to platforms
        $this->send_subscription_event( $event, 'woo' );
    }
    /**
     * Handle WooCommerce subscription status change
     *
     * @param int $subscription_id Subscription ID
     * @param string $new_status New status
     * @param string $old_status Old status
     * @return void
     */
    public function woo_subscription_status_change(WC_Subscription $subscription, $new_status, $old_status ) {
        $subscription_info = array(
            'old_status' => $old_status,
            'new_status' => $new_status,
            'timestamp' => current_time( 'mysql' )
        );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'woo_track_subscriptions' ) ) {
            $this->logger->debug( 'WOO subscription tracking is disabled' );
            return;
        }

        // Get subscription ID
        $subscription_id = is_object( $subscription ) ? $subscription->get_id() : $subscription;

        $subscription_info['subscription_id'] = $subscription_id;

        add_filter( 'pys_woo_subscription', function () use ( $subscription_info ) {
            return $subscription_info;
        } );

        // Handle different status changes
        if ( $new_status === 'expired' && $old_status !== 'expired' ) {
            $this->logger->debug( 'Subscription expired event triggered', array( 'subscription_id' => $subscription_id ) );
            $event = EventsWoo()->getEvent( 'woo_subscription_expired' );

        } elseif ( $new_status === 'cancelled' && $old_status !== 'cancelled' ) {
            $this->logger->debug( 'Subscription canceled event triggered', array( 'subscription_id' => $subscription_id ) );
            $event = EventsWoo()->getEvent( 'woo_subscription_canceled' );
        }

        remove_all_filters( 'pys_woo_subscription' );
        if (empty($event)) {
            return;
        }
        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
        $this->send_subscription_event( $event, 'woo' );
    }

    /**
     * Tracks an EDD completed purchase
     *
     * @param int $payment_id The payment ID
     * @return void
     */
    public function edd_completed_purchase( $payment_id ) {
        if ( ! PYS()->getOption( 'edd_advance_purchase_fb_enabled' )
            && ! PYS()->getOption( 'edd_advance_purchase_ga_enabled' )
            && ( ! Tiktok()->enabled() || ( Tiktok()->enabled() && ! Tiktok()->getOption( 'edd_advance_purchase_enabled' ) ) )
            && ( ! Pinterest()->enabled() || ( Pinterest()->enabled() && ! Pinterest()->getOption( 'edd_advance_purchase_enabled' ) ) )
        ) {
            return;
        }

        $userId = edd_get_payment_user_id( $payment_id );
        $user = get_user_by( 'id', $userId );
        if ( isDisabledForUserRole( $user )
            || ! PYS()->getOption( 'edd_enabled_purchase_recurring' )
            || ! PYS()->getOption( 'edd_purchase_enabled' )
        ) {
            return;
        }

        add_filter( 'pys_edd_checkout_order_id', function () use ( $payment_id ) {
            return $payment_id;
        } );
        $fire_event = true;
        if ( edd_get_payment_meta( $payment_id, '_pys_purchase_event_fired', true ) || (PYS()->getOption( 'edd_purchase_on_transaction' ) && edd_get_payment_meta( $payment_id, '_pys_advance_purchase_event_fired', true ))) {
            $fire_event = false;
        }
        $event = EventsEdd()->getEvent('edd_purchase', false);
        if ( $event == null ) {
            return;
        }
        // eventID is now generated in EventsEdd::getEvent() for proper server-browser deduplication
        // Do NOT override it here - the event already has eventID in payload
        $event->addParams( array( 'advanced_purchase_tracking' => true ) );
        if ( $fire_event ) {
            // Send GA server events
            if ( PYS()->getOption( 'edd_advance_purchase_ga_enabled' ) ) {
                $gaEvents = GA()->generateEvents( $event );
                if ( ! empty( $gaEvents ) ) {
                    GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
                }
            }

            // Send Facebook server events
            if (PYS()->getOption('edd_advance_purchase_fb_enabled')) {
                $fbEvents = Facebook()->generateEvents($event);
                FacebookServer()->sendEventsNow($fbEvents);
            }

            // Send TikTok server events
            if (Tiktok()->enabled()
                && Tiktok()->isServerApiEnabled()
                && Tiktok()->getOption('edd_advance_purchase_enabled')
            ) {
                Tiktok()->getLog()->debug('Send Completed Payment Tiktok');
                $tiktokEvents = Tiktok()->generateEvents($event);
                TikTokServer()->sendEventsNow($tiktokEvents);
            }

            // Send Pinterest server events
            if (Pinterest()->enabled()
                && method_exists(Pinterest(), 'isServerApiEnabled')
                && Pinterest()->isServerApiEnabled()
                && Pinterest()->getOption('edd_advance_purchase_enabled')
            ) {
                Pinterest()->getLog()->debug('Send Checkout Pinterest');
                $pinterestEvents = Pinterest()->generateEvents($event);
                PinterestServer()->sendEventsNow($pinterestEvents);
            }
        }

        $firedPixels = self::getFiredPixels();

        if ( $payment_id ) {

            // Mark that server-side purchase event was fired
            edd_update_payment_meta( $payment_id, '_pys_advance_purchase_event_fired', true );

            // Save fired pixels to order meta
            if ( ! empty( $firedPixels ) ) {
                edd_update_payment_meta( $payment_id, '_pys_server_fired_pixels', $firedPixels );
            }
        }
        // Reset fired pixels for next request
        self::resetFiredPixels();
    }


    /**
     * Tracks an EDD refund
     *
     * @param int $payment_id The payment ID
     * @param int $refund_id The refund ID
     * @param bool $all_refunded Whether all items were refunded
     * @return void
     */
    public function edd_refund_order( $payment_id, $refund_id, $all_refunded ) {
        $log = $this->logger;

        if ( ! PYS()->getOption( 'edd_track_refunds_GA' ) && ! edd_get_payment_meta( $payment_id, '_pys_purchase_event_fired', true ) ) {
            return;
        }

        if ( ! $payment_id ) {
            return;
        }

        $userId = edd_get_payment_user_id( $payment_id );
        $user = get_user_by( 'id', $userId );
        if ( isDisabledForUserRole( $user ) ) {
            return;
        }

        // Reset all purchase event flags on refund
        edd_update_payment_meta( $payment_id, '_pys_purchase_event_fired', false );
        edd_update_payment_meta( $payment_id, '_pys_advance_purchase_event_fired', false );
        edd_update_payment_meta( $payment_id, '_pys_purchase_browser_unlocked', false );

        add_filter( 'pys_edd_checkout_order_id', function () use ( $payment_id ) {
            return $payment_id;
        } );
        $event = EventsEdd()->getEvent( 'edd_refund' );

        if ( $event == null ) {
            return;
        }
        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );

        if ( PYS()->getOption( 'edd_track_refunds_GA' ) ) {
            $gaEvents = GA()->generateEvents( $event );
            GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
            $log->debug( "Send completed refund GA" );
        }
    }

    /**
     * Tracks an EDD recurring payment
     *
     * @param int $payment_id The payment ID
     * @param int $parent_payment_id The parent payment ID
     * @param float $amount The payment amount
     * @param string $transaction_id The transaction ID
     * @return void
     */
    public function edd_recurring_payment( $payment_id, $parent_payment_id, $amount, $transaction_id ) {
        EnrichOrder()->edd_save_subscription_meta( $payment_id );

        if ( ! PYS()->getOption( 'edd_advance_purchase_fb_enabled' )
            && ! PYS()->getOption( 'edd_advance_purchase_ga_enabled' )
            && ( ! Tiktok()->enabled() || ( Tiktok()->enabled() && ! Tiktok()->getOption( 'edd_advance_purchase_enabled' ) ) )
            && ( ! Pinterest()->enabled() || ( Pinterest()->enabled() && ! Pinterest()->getOption( 'edd_advance_purchase_enabled' ) ) )
        ) {
            return;
        }

        $userId = edd_get_payment_user_id( $payment_id );
        $user = get_user_by( 'id', $userId );
        if ( isDisabledForUserRole( $user )
            || ! PYS()->getOption( 'edd_enabled_purchase_recurring' )
            || ! PYS()->getOption( 'edd_purchase_enabled' )
        ) {
            return;
        }
        $fire_event = true;
        if (edd_get_payment_meta( $payment_id, '_pys_purchase_event_fired', true ) || (PYS()->getOption( 'edd_purchase_on_transaction' ) && edd_get_payment_meta( $payment_id, '_pys_advance_purchase_event_fired', true ))) {
            $fire_event = false;
        }
        PYS()->getLog()->debug( "Purchase recurring Edd", $payment_id );
        add_filter( 'pys_edd_checkout_order_id', function () use ( $payment_id ) {
            return $payment_id;
        } );

        $event = EventsEdd()->getEvent('edd_purchase', false);
        if ( $event == null ) {
            return;
        }
        // eventID is now generated in EventsEdd::getEvent() for proper server-browser deduplication


        if ( $fire_event ) {
            // Send GA server events
            if ( PYS()->getOption( 'edd_advance_purchase_ga_enabled' ) ) {
                $gaEvents = GA()->generateEvents( $event );
                if ( ! empty( $gaEvents ) ) {
                    GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
                }
            }

            // Send Facebook server events
            if (PYS()->getOption('edd_advance_purchase_fb_enabled')) {
                $fbEvents = Facebook()->generateEvents($event);
                FacebookServer()->sendEventsNow($fbEvents);
            }

            // Send TikTok server events
            if (Tiktok()->enabled()
                && Tiktok()->isServerApiEnabled()
                && Tiktok()->getOption('edd_advance_purchase_enabled')
            ) {
                Tiktok()->getLog()->debug('Send Renewal Payment Tiktok');
                $tiktokEvents = Tiktok()->generateEvents($event);
                TikTokServer()->sendEventsNow($tiktokEvents);
            }

            // Send Pinterest server events
            if (Pinterest()->enabled()
                && method_exists(Pinterest(), 'isServerApiEnabled')
                && Pinterest()->isServerApiEnabled()
                && Pinterest()->getOption('edd_advance_purchase_enabled')
            ) {
                Pinterest()->getLog()->debug('Send Renewal Payment Pinterest');
                $pinterestEvents = Pinterest()->generateEvents($event);
                PinterestServer()->sendEventsNow($pinterestEvents);
            }
        }

        $firedPixels = self::getFiredPixels();

        if ( $payment_id ) {

            // Mark that server-side purchase event was fired
            edd_update_payment_meta( $payment_id, '_pys_advance_purchase_event_fired', true );

            // Save fired pixels to order meta
            if ( ! empty( $firedPixels ) ) {
                edd_update_payment_meta( $payment_id, '_pys_server_fired_pixels', $firedPixels );
            }
        }
        // Reset fired pixels for next request
        self::resetFiredPixels();
    }

    /**
     * Handle EDD subscription status change
     *
     * @param int $subscription_id Subscription ID
     * @param string $new_status New status
     * @param string $old_status Old status
     * @return void
     */
    public function edd_subscription_status_change( $old_status, $new_status, $subscription ) {
        if(empty($subscription) || empty($subscription->id)){ return; }
        $this->logger->debug( 'EDD Subscription Status Change', array(
            'old_status' => $old_status,
            'new_status' => $new_status,
            'subscription' => $subscription,
            'timestamp'  => current_time( 'mysql' ),
        ) );

        // Bail if status hasn't actually changed
        if ( $old_status === $new_status ) {
            return;
        }

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_subscriptions' ) ) {
            $this->logger->debug( 'EDD subscription tracking is disabled' );
            return;
        }

        // Get subscription ID
        $subscription_id = is_object( $subscription ) ? $subscription->id : $subscription;
        $this->logger->debug( 'Subscription ID', $subscription_id );
        if ( empty( $subscription_id ) ) {
            return;
        }

        $subscription_info = array(
            'subscription'    => $subscription,
            'subscription_id' => $subscription_id,
            'old_status'      => $old_status,
            'new_status'      => $new_status,
            'timestamp'       => current_time( 'mysql' ),
        );

        // Determine which event to fire based on the status transition
        $event_id = null;

        if ( $new_status === 'active' && $old_status !== 'trialling' ) {
            // Fire only when the subscription was previously skipped due to a pending payment
            // and later confirmed by the gateway (meta set in edd_subscription_created).
            $needs_event = edd_recurring_get_subscription_meta( $subscription_id, '_pys_needs_created_event', true );
            if ( $needs_event ) {
                edd_recurring_delete_subscription_meta( $subscription_id, '_pys_needs_created_event' );
                $event_id = 'edd_subscription_created';
                $this->logger->debug( 'Subscription Created (deferred) event triggered', array( 'subscription_id' => $subscription_id ) );
            }

        } elseif ( $new_status === 'expired' ) {
            $event_id = 'edd_subscription_expired';
            $this->logger->debug( 'Subscription Expired event triggered', array( 'subscription_id' => $subscription_id ) );

        } elseif ( $new_status === 'cancelled' ) {
            $event_id = 'edd_subscription_canceled';
            $this->logger->debug( 'Subscription Canceled event triggered', array( 'subscription_id' => $subscription_id ) );
        }

        if ( $event_id === null ) {
            return;
        }

        add_filter( 'pys_edd_subscription', function () use ( $subscription_info ) {
            return $subscription_info;
        } );

        $event = EventsEdd()->getEvent( $event_id );
        remove_all_filters( 'pys_edd_subscription' );

        if ( $event == null ) {
            $this->logger->debug( 'Event is null, skipping', array( 'event_id' => $event_id ) );
            return;
        }

        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
        $this->send_subscription_event( $event, 'edd' );
    }

    /**
     * Handle EDD subscription created
     *
     * @param int $subscription_id Subscription ID
     * @param array $args Subscription arguments
     * @return void
     */
    public function edd_subscription_created( $subscription_id, $args ) {

        $subscription_info = array(
            'subscription_id' => $subscription_id
        );

        $this->logger->debug( 'EDD Subscription Created', array(
            'subscription_id' => $subscription_id,
            'args' => $args,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_subscriptions' ) ) {
            $this->logger->debug( 'EDD subscription tracking is disabled' );
            return;
        }

        // ── Temporary-subscription filter ──────────────────────────────────────
        // EDD Recurring creates an intermediate subscription during checkout with
        // a "pending" parent payment. We skip that entry and wait for the final,
        // gateway-confirmed one.
        $parent_payment_id = ! empty( $args['parent_payment_id'] ) ? absint( $args['parent_payment_id'] ) : 0;

        if ( $parent_payment_id > 0 ) {

            $payment = new \EDD_Payment( $parent_payment_id );

            if ( $payment && $payment->ID ) {

                $order_number    = (string) $payment->number;
                $payment_status  = (string) $payment->status;
                $gateway         = (string) $payment->gateway;
                $total           = (float)  $payment->total;

                $log_context = wp_json_encode( array(
                    'subscription_id'   => $subscription_id,
                    'parent_payment_id' => $parent_payment_id,
                    'order_number'      => $order_number,
                    'payment_status'    => $payment_status,
                    'gateway'           => $gateway,
                    'total'             => $total,
                ) );

                // Skip when the order number itself signals a pending/draft entry.
                // Mark the subscription so edd_subscription_status_change can fire the event later.
                if ( stripos( $order_number, 'pending' ) !== false ) {
                    edd_recurring_update_subscription_meta( $subscription_id, '_pys_needs_created_event', 1 );
                    $this->logger->debug( '[EDD_FILTER] Skipped temporary sub #' . $subscription_id . ' – marked for deferred event', json_decode( $log_context, true ) );
                    return;
                }

                // Skip when the payment has not been confirmed by the gateway yet.
                // Mark the subscription so edd_subscription_status_change can fire the event later.
                if ( $payment_status === 'pending' ) {
                    edd_recurring_update_subscription_meta( $subscription_id, '_pys_needs_created_event', 1 );
                    $this->logger->debug( '[EDD_FILTER] Skipped temporary sub #' . $subscription_id . ' – marked for deferred event', json_decode( $log_context, true ) );
                    return;
                }

                $this->logger->debug( '[EDD_FILTER] Valid sub #' . $subscription_id . ' confirmed.', json_decode( $log_context, true ) );

            } else {
                // Payment object could not be loaded – log and skip to avoid
                // processing a subscription with no verifiable parent payment.
                $this->logger->debug( '[EDD_FILTER] Skipped temporary sub #' . $subscription_id . ' – EDD_Payment #' . $parent_payment_id . ' not found.' );
                return;
            }
        } else {
            // No parent_payment_id in $args – cannot verify the payment, skip.
            $this->logger->debug( '[EDD_FILTER] Skipped temporary sub #' . $subscription_id . ' – parent_payment_id missing from args.' );
        }
        // ── End temporary-subscription filter ─────────────────────────────────

        // Load subscription object to check status
        $subscription = new \EDD\Recurring\Subscriptions\Subscription( $subscription_id );

        if ( ! $subscription || ! $subscription->id ) {
            $this->logger->debug( 'Failed to load subscription object', array( 'subscription_id' => $subscription_id ) );
            return;
        }

        // Determine event type based on subscription status
        $event_id = 'edd_subscription_created';
        $is_trial = false;

        if ( $subscription->status === 'trialling' && ! empty( $subscription->trial_period ) ) {
            $event_id = 'edd_start_trial';
            $is_trial = true;
            $subscription_info['trial_period'] = $subscription->trial_period;
            $this->logger->debug( 'Start Trial event triggered', array(
                'subscription_id' => $subscription_id,
                'trial_period' => $subscription->trial_period,
                'status' => $subscription->status
            ) );
        } else {
            $this->logger->debug( 'Subscription Created event triggered', array(
                'subscription_id' => $subscription_id,
                'status' => $subscription->status
            ) );
        }

        // Set filter to pass subscription_id to EventsEdd
        add_filter( 'pys_edd_subscription', function () use ( $subscription_info ) {
            return $subscription_info;
        } );

        // Get event from EventsEdd
        $event = EventsEdd()->getEvent( $event_id );

        // Remove filter
        remove_all_filters( 'pys_edd_subscription' );

        if ( $event == null ) {
            $this->logger->debug( 'Event is null, skipping', array( 'event_id' => $event_id ) );
            return;
        }

        // Add eventID for deduplication
        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );

        // Send to platforms
        $this->send_subscription_event( $event, 'edd' );
    }

    /**
     * Handle EDD subscription renewed
     *
     * @param int $subscription_id Subscription ID
     * @param string $expiration Expiration date
     * @param object $subscription Subscription object
     * @param int $payment_id Payment ID
     * @return void
     */
    public function edd_subscription_renewed( $subscription_id, $expiration, $subscription, $payment_id ) {

        $subscription_info = array(
            'subscription_id' => $subscription_id,
            'expiration' => $expiration,
            'payment_id' => $payment_id,
            'timestamp' => current_time( 'mysql' )
        );
        $this->logger->debug( 'EDD Subscription Renewed', $subscription_info );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_subscriptions' ) ) {
            $this->logger->debug( 'EDD subscription tracking is disabled' );
            return;
        }

        // Set filter to pass subscription_id
        add_filter( 'pys_edd_subscription', function () use ( $subscription_info ) {
            return $subscription_info;
        } );

        // Get event from EventsEdd
        $event = EventsEdd()->getEvent( 'edd_subscription_renewal' );

        // Remove filter
        remove_all_filters( 'pys_edd_subscription' );

        if ( $event == null ) {
            $this->logger->debug( 'Event is null, skipping' );
            return;
        }

        // Add eventID for deduplication
        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );

        // Send to platforms
        $this->send_subscription_event( $event, 'edd' );
    }

    /**
     * Handle EDD license status change
     *
     * @param int $license_id License ID
     * @param string $status New status
     * @return void
     */
    public function edd_license_status_change( $license_id, $status ) {
        $this->logger->debug( 'EDD License Status Change', array(
            'license_id' => $license_id,
            'status' => $status,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'EDD license tracking is disabled' );
            return;
        }

        // Handle expired status
        if ( $status === 'expired' ) {
            $this->logger->debug( 'License expired event triggered', array( 'license_id' => $license_id ) );

            // Set filter to pass license_id
            add_filter( 'pys_edd_license_id', function () use ( $license_id ) {
                return $license_id;
            } );

            $event = EventsEdd()->getEvent( 'edd_license_expired' );
            remove_all_filters( 'pys_edd_license_id' );

            if ( $event == null ) {
                return;
            }

            $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
            $this->send_license_event( $event );
        }
    }

    /**
     * Handle EDD license upgraded
     *
     * @param int $license_id License ID
     * @param array $args Upgrade arguments
     * @return void
     */
    public function edd_license_upgraded( $license_id, $args ) {
        $this->logger->debug( 'EDD License Upgraded', array(
            'license_id' => $license_id,
            'args' => $args,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'EDD license tracking is disabled' );
            return;
        }

        $this->logger->debug( 'License upgrade event triggered', array( 'license_id' => $license_id ) );

        // Set filter to pass license_id
        add_filter( 'pys_edd_license_id', function () use ( $license_id ) {
            return $license_id;
        } );

        $event = EventsEdd()->getEvent( 'edd_license_upgrade' );
        remove_all_filters( 'pys_edd_license_id' );

        if ( $event == null ) {
            return;
        }

        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
        $this->send_license_event( $event );
    }

    /**
     * Handle EDD license renewed
     *
     * @param int $license_id License ID
     * @param string $new_expiration New expiration date
     * @return void
     */
    public function edd_license_renewed( $license_id, $new_expiration ) {
        $this->logger->debug( 'EDD License Renewed', array(
            'license_id' => $license_id,
            'new_expiration' => $new_expiration,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'EDD license tracking is disabled' );
            return;
        }

        $this->logger->debug( 'License renewal event triggered', array( 'license_id' => $license_id ) );

        // Set filter to pass license_id
        add_filter( 'pys_edd_license_id', function () use ( $license_id ) {
            return $license_id;
        } );

        $event = EventsEdd()->getEvent( 'edd_license_renewal' );
        remove_all_filters( 'pys_edd_license_id' );

        if ( $event == null ) {
            return;
        }

        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
        $this->send_license_event( $event );
    }

    /**
     * Handle EDD license created
     *
     * @param int $license_id License ID
     * @param int $download_id Download ID
     * @param int $payment_id Payment ID
     * @param string $type Purchase type
     * @return void
     */
    public function edd_license_created( $license_id, $download_id, $payment_id, $type ) {
        // Only process if this download has licensing enabled
        if ( ! function_exists( 'edd_software_licensing' ) ) {
            return;
        }

        // Check if this download has licensing
        $licensing_enabled = get_post_meta( $download_id, '_edd_sl_enabled', true );
        if ( ! $licensing_enabled ) {
            return;
        }

        $this->logger->debug( 'EDD License Created', array(
            'license_id' => $license_id,
            'download_id' => $download_id,
            'payment_id' => $payment_id,
            'type' => $type,
            'timestamp' => current_time( 'mysql' )
        ) );

        // Check if tracking is enabled
        if ( ! PYS()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'EDD license tracking is disabled' );
            return;
        }

        $this->logger->debug( 'License created event triggered', array(
            'license_id' => $license_id,
            'download_id' => $download_id,
            'payment_id' => $payment_id
        ) );

        if ( ! $license_id ) {
            $this->logger->debug( 'No license found for download', array( 'download_id' => $download_id ) );
            return;
        }

        // Set filter to pass license_id
        add_filter( 'pys_edd_license_id', function () use ( $license_id ) {
            return $license_id;
        } );

        $event = EventsEdd()->getEvent( 'edd_license_created' );
        remove_all_filters( 'pys_edd_license_id' );

        if ( $event == null ) {
            return;
        }

        $event->addPayload( array( 'eventID' => EventIdGenerator::guidv4() ) );
        $this->send_license_event( $event );
    }

    /**
     * Send subscription event to all enabled platforms
     *
     * @param object $event Event object
     * @param string $event_type Event type (trial, created, renewal, expired, canceled)
     * @return void
     */
    private function send_subscription_event( $event, $event_type = 'edd' ) {

        $option_name = $event_type.'_track_subscriptions';

        // Send Facebook server events
        if ( Facebook()->enabled()
            && Facebook()->isServerApiEnabled()
            && Facebook()->getOption( $option_name ) ) {
            $this->logger->debug( 'Sending to Facebook' );
            $fbEvents = Facebook()->generateEvents( $event );
            FacebookServer()->sendEventsNow( $fbEvents );
        }

        // Send GA server events
        if ( GA()->enabled()
            && GA()->isServerApiEnabled()
            && GA()->getOption( $option_name ) ) {
            $this->logger->debug( 'Sending to Google Analytics' );
            $gaEvents = GA()->generateEvents( $event );
            if ( ! empty( $gaEvents ) ) {
                GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
            }
        }

        // Send TikTok server events
        if ( Tiktok()->enabled()
            && Tiktok()->isServerApiEnabled()
            && Tiktok()->getOption( $option_name )
        ) {
            $this->logger->debug( 'Sending to TikTok' );
            $tiktokEvents = Tiktok()->generateEvents( $event );
            TikTokServer()->sendEventsNow( $tiktokEvents );
        }

        // Send Pinterest server events
        if ( Pinterest()->enabled()
            && method_exists( Pinterest(), 'isServerApiEnabled' )
            && Pinterest()->isServerApiEnabled()
            && Pinterest()->getOption( $option_name )
        ) {
            $this->logger->debug( 'Sending to Pinterest' );
            $pinterestEvents = Pinterest()->generateEvents( $event );
            PinterestServer()->sendEventsNow( $pinterestEvents );
        }
    }

    /**
     * Send license event to all enabled platforms
     *
     * @param object $event Event object
     * @param string $event_type Event type (created, upgrade, renewal, expired)
     * @return void
     */
    private function send_license_event( $event ) {

        // Send Facebook server events
        if ( Facebook()->enabled()
            && Facebook()->isServerApiEnabled()
            && Facebook()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'Sending to Facebook' );
            $fbEvents = Facebook()->generateEvents( $event );
            FacebookServer()->sendEventsNow( $fbEvents );
        }

        // Send GA server events
        if ( GA()->enabled()
            && GA()->isServerApiEnabled()
            && GA()->getOption( 'edd_track_licenses' ) ) {
            $this->logger->debug( 'Sending to Google Analytics' );
            $gaEvents = GA()->generateEvents( $event );
            if ( ! empty( $gaEvents ) ) {
                GaMeasurementProtocolAPI()->sendEventsNow( $gaEvents );
            }
        }

        // Send TikTok server events
        if ( Tiktok()->enabled()
            && Tiktok()->isServerApiEnabled()
            && Tiktok()->getOption( 'edd_track_licenses' )
        ) {
            $this->logger->debug( 'Sending to TikTok' );
            $tiktokEvents = Tiktok()->generateEvents( $event );
            TikTokServer()->sendEventsNow( $tiktokEvents );
        }

        // Send Pinterest server events
        if ( Pinterest()->enabled()
            && method_exists( Pinterest(), 'isServerApiEnabled' )
            && Pinterest()->isServerApiEnabled()
            && Pinterest()->getOption( 'edd_track_licenses' )
        ) {
            $this->logger->debug( 'Sending to Pinterest' );
            $pinterestEvents = Pinterest()->generateEvents( $event );
            PinterestServer()->sendEventsNow( $pinterestEvents );
        }


    }
}

/**
 * Get singleton instance
 *
 * @return ServerEventsManager
 */
function ServerEventsManager() {
    return ServerEventsManager::instance();
}

// Initialize the manager
ServerEventsManager();

