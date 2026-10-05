<?php
/**
 * WOO LTV Calculator
 *
 * Calculates Predicted Lifetime Value for WOO subscriptions
 * using churn-based models and historical data.
 *
 * @package PixelYourSite
 */

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WOO_LTV_Calculator extends Settings {

    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Get singleton instance
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        parent::__construct( 'WooSettings' );
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Schedule daily churn calculation
        if ( ! wp_next_scheduled( 'pys_calculate_woo_churn_rates' ) ) {
            wp_schedule_event( time(), 'daily', 'pys_calculate_woo_churn_rates' );

            // Run initial calculation if no churn rates exist
            $this->maybe_run_initial_calculation();
        }

        add_action( 'pys_calculate_woo_churn_rates', array( $this, 'calculate_and_save_churn_rates' ) );

        // Manual recalculation hook
        add_action( 'admin_post_pys_recalculate_woo_churn', array( $this, 'manual_recalculate_churn' ) );
    }

    /**
     * Run initial calculation if no churn rates exist
     */
    private function maybe_run_initial_calculation() {
        // Check if we have any churn rates calculated
        $has_churn_data = $this->getOption( 'woo_churn_month', false );

        if ( $has_churn_data === false ) {
            // No churn data exists, run initial calculation
            $this->calculate_and_save_churn_rates();
        }
    }

    /**
     * C. Calculate Predicted LTV for a subscription
     *
     * @param WC_Subscription $subscription WC_Subscription object
     * @return float Predicted LTV
     */
    public function calculate_predicted_ltv( $subscription ) {
        if ( ! $subscription || ! is_a( $subscription, 'WC_Subscription' ) ) {
            return 0;
        }

        // Get initial amount (sign-up fee + first payment)
        $initial_amount = (float) $subscription->get_total_initial_payment();

        // Get recurring amount
        $recurring_amount = (float) $subscription->get_total('recurring');

        // Get billing period and interval
        $period = $subscription->get_billing_period();
        $interval = (int) $subscription->get_billing_interval();

        // Normalize period to match our churn rate keys
        $normalized_period = $this->normalize_period( $period, $interval );

        // Get end date to determine if subscription is limited
        $end_date = $subscription->get_date( 'end' );
        $start_date = $subscription->get_date( 'start' );

        // Calculate expected billings
        $expected_billings = 0;

        if ( ! empty( $end_date ) && $end_date > 0 ) {
            // Limited subscription - calculate exact number of payments
            $expected_billings = $this->calculate_limited_billings( $start_date, $end_date, $period, $interval );
        } else {
            // Unlimited subscription - churn-based calculation
            $churn_rate = $this->get_cached_churn_rate( $normalized_period );

            if ( $churn_rate > 0 ) {
                $expected_billings = 1 / $churn_rate;
            } else {
                // Fallback: conservative estimate
                $expected_billings = $this->get_fallback_billings( $normalized_period );
            }
        }

        // Predicted LTV = initial_amount + (recurring_amount × expected_billings)
        $predicted_ltv = $initial_amount + ( $recurring_amount * $expected_billings );

        return round( $predicted_ltv, 2 );
    }

    /**
     * B. Get cached churn rate for a specific period
     *
     * @param string $period Subscription period (day, week, month, quarter, semi-year, year)
     * @return float Churn rate (0-1)
     */
    public function get_cached_churn_rate( $period ) {
        // Try to get from option
        $option_name = 'woo_churn_' . $period;
        $churn_rate = $this->getOption( $option_name, false );

        if ( $churn_rate !== false ) {
            return (float) $churn_rate;
        }

        // Fallback: default churn rates if not calculated yet
        $defaults = array(
            'day'       => 0.10,  // 10% daily churn
            'week'      => 0.08,  // 8% weekly churn
            'month'     => 0.05,  // 5% monthly churn (industry standard)
            'quarter'   => 0.04,  // 4% quarterly churn
            'semi-year' => 0.03,  // 3% semi-annual churn
            'year'      => 0.02,  // 2% annual churn
        );

        return isset( $defaults[ $period ] ) ? $defaults[ $period ] : 0.05;
    }

    /**
     * Normalize WooCommerce billing period and interval to our churn rate keys
     *
     * @param string $period WooCommerce billing period (day, week, month, year)
     * @param int $interval Billing interval (e.g., 3 for every 3 months)
     * @return string Normalized period key
     */
    private function normalize_period( $period, $interval ) {
        // Convert WooCommerce period to our format
        if ( $interval == 1 ) {
            return $period; // day, week, month, year
        }

        // Handle intervals
        if ( $period === 'month' ) {
            if ( $interval == 3 ) {
                return 'quarter';
            } elseif ( $interval == 6 ) {
                return 'semi-year';
            } elseif ( $interval == 12 ) {
                return 'year';
            }
        }

        // Default: use base period
        return $period;
    }

    /**
     * Calculate number of billings for a limited subscription
     *
     * @param string $start_date Start date
     * @param string $end_date End date
     * @param string $period Billing period
     * @param int $interval Billing interval
     * @return int Number of expected billings
     */
    private function calculate_limited_billings( $start_date, $end_date, $period, $interval ) {
        if ( empty( $start_date ) || empty( $end_date ) ) {
            return 0;
        }

        $start = strtotime( $start_date );
        $end = strtotime( $end_date );

        if ( $start >= $end ) {
            return 0;
        }

        // Use WooCommerce function if available
        if ( function_exists( 'wcs_estimate_periods_between' ) ) {
            return (int) ( wcs_estimate_periods_between( $start, $end, $period ) / $interval );
        }

        // Fallback calculation
        $diff_seconds = $end - $start;
        $period_seconds = $this->get_period_in_seconds( $period ) * $interval;

        return (int) ceil( $diff_seconds / $period_seconds );
    }

    /**
     * Get period duration in seconds
     *
     * @param string $period Period type
     * @return int Seconds in period
     */
    private function get_period_in_seconds( $period ) {
        switch ( $period ) {
            case 'day':
                return DAY_IN_SECONDS;
            case 'week':
                return WEEK_IN_SECONDS;
            case 'month':
                return MONTH_IN_SECONDS;
            case 'year':
                return YEAR_IN_SECONDS;
            default:
                return MONTH_IN_SECONDS;
        }
    }

    /**
     * Get fallback billings (conservative 12-month estimate)
     *
     * @param string $period Billing period
     * @return int Estimated number of billings
     */
    private function get_fallback_billings( $period ) {
        $estimated_billings = 12; // Default to 12 months worth

        switch ( $period ) {
            case 'day':
                $estimated_billings = 365; // 1 year
                break;
            case 'week':
                $estimated_billings = 52; // 1 year
                break;
            case 'month':
                $estimated_billings = 12; // 1 year
                break;
            case 'quarter':
                $estimated_billings = 4; // 1 year
                break;
            case 'semi-year':
                $estimated_billings = 2; // 1 year
                break;
            case 'year':
                $estimated_billings = 1; // 1 year
                break;
        }

        return $estimated_billings;
    }

    /**
     * A. Cron task - Calculate and save churn rates from historical data
     * This runs via cron daily
     */
    public function calculate_and_save_churn_rates() {
        global $wpdb;

        if ( ! function_exists( 'wcs_get_subscription' ) ) {
            PYS()->getLog()->debug( 'WOO LTV Calculator: WooCommerce Subscriptions not available' );
            return;
        }

        $periods = array( 'day', 'week', 'month', 'quarter', 'semi-year', 'year' );

        foreach ( $periods as $period ) {
            $churn_rate = $this->calculate_churn_for_period( $period );

            // Save to individual option
            $option_name = 'woo_churn_' . $period;
            $this->addOption( $option_name, 'number', 0.05);
            $this->updateOptions( array($option_name => $churn_rate) );
        }

        // Save last calculation timestamp
        $this->addOption('woo_churn_last_calculated', 'number', 0);
        $this->updateOptions( array('woo_churn_last_calculated' => current_time( 'mysql' ) ) );

    }

    /**
     * Calculate churn rate for a specific period
     *
     * @param string $period Subscription period
     * @return float Churn rate (0-1)
     */
    private function calculate_churn_for_period( $period ) {
        global $wpdb;

        // Map our period keys to WooCommerce billing period and interval
        $period_mapping = $this->get_period_mapping( $period );

        if ( ! $period_mapping ) {
            return $this->get_default_churn_rate( $period );
        }

        $billing_period = $period_mapping['period'];
        $billing_interval = $period_mapping['interval'];

        PYS()->getLog()->debug('WOO LTV Calculator: Calculating churn rate for period', array($billing_period, $billing_interval));

        // Check if HPOS is enabled
        $is_hpos = function_exists( 'wcs_is_custom_order_tables_usage_enabled' ) && wcs_is_custom_order_tables_usage_enabled();

        if ( $is_hpos ) {
            // Query using HPOS tables
            $total_query = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}wc_orders AS o
                INNER JOIN {$wpdb->prefix}wc_orders_meta AS m1 ON o.id = m1.order_id AND m1.meta_key = '_billing_period'
                INNER JOIN {$wpdb->prefix}wc_orders_meta AS m2 ON o.id = m2.order_id AND m2.meta_key = '_billing_interval'
                WHERE o.type = 'shop_subscription'
                AND o.status != 'wc-pending'
                AND m1.meta_value = %s
                AND m2.meta_value = %d",
                $billing_period,
                $billing_interval
            );

            $churned_query = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}wc_orders AS o
                INNER JOIN {$wpdb->prefix}wc_orders_meta AS m1 ON o.id = m1.order_id AND m1.meta_key = '_billing_period'
                INNER JOIN {$wpdb->prefix}wc_orders_meta AS m2 ON o.id = m2.order_id AND m2.meta_key = '_billing_interval'
                WHERE o.type = 'shop_subscription'
                AND o.status IN ('wc-cancelled', 'wc-expired', 'wc-on-hold', 'wc-pending-cancel')
                AND m1.meta_value = %s
                AND m2.meta_value = %d",
                $billing_period,
                $billing_interval
            );
        } else {
            // Query using CPT (posts table)
            $total_query = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->posts} AS p
                INNER JOIN {$wpdb->postmeta} AS m1 ON p.ID = m1.post_id AND m1.meta_key = '_billing_period'
                INNER JOIN {$wpdb->postmeta} AS m2 ON p.ID = m2.post_id AND m2.meta_key = '_billing_interval'
                WHERE p.post_type = 'shop_subscription'
                AND p.post_status != 'wc-pending'
                AND m1.meta_value = %s
                AND m2.meta_value = %d",
                $billing_period,
                $billing_interval
            );

            $churned_query = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->posts} AS p
                INNER JOIN {$wpdb->postmeta} AS m1 ON p.ID = m1.post_id AND m1.meta_key = '_billing_period'
                INNER JOIN {$wpdb->postmeta} AS m2 ON p.ID = m2.post_id AND m2.meta_key = '_billing_interval'
                WHERE p.post_type = 'shop_subscription'
                AND p.post_status IN ('wc-cancelled', 'wc-expired', 'wc-on-hold', 'wc-pending-cancel')
                AND m1.meta_value = %s
                AND m2.meta_value = %d",
                $billing_period,
                $billing_interval
            );
        }


        $total_subscriptions = (int) $wpdb->get_var( $total_query );

        if ( $total_subscriptions === 0 ) {
            return $this->get_default_churn_rate( $period );
        }

        $churned_subscriptions = (int) $wpdb->get_var( $churned_query );
        PYS()->getLog()->debug('WOO LTV Calculator: Calculating churn rate for period', array($total_subscriptions, $churned_subscriptions));

        // Calculate churn rate
        $churn_rate = $churned_subscriptions / $total_subscriptions;
        PYS()->getLog()->debug('WOO LTV Calculator: Calculating churn rate for period', array($churn_rate));

        // Ensure churn rate is within reasonable bounds (1% - 50%)
        $churn_rate = max( 0.01, min( 0.50, $churn_rate ) );

        return round( $churn_rate, 4 );
    }

    /**
     * Map our period keys to WooCommerce billing period and interval
     *
     * @param string $period Our period key
     * @return array|false Array with 'period' and 'interval' keys, or false
     */
    private function get_period_mapping( $period ) {
        $mappings = array(
            'day'       => array( 'period' => 'day', 'interval' => 1 ),
            'week'      => array( 'period' => 'week', 'interval' => 1 ),
            'month'     => array( 'period' => 'month', 'interval' => 1 ),
            'quarter'   => array( 'period' => 'month', 'interval' => 3 ),
            'semi-year' => array( 'period' => 'month', 'interval' => 6 ),
            'year'      => array( 'period' => 'year', 'interval' => 1 ),
        );

        return isset( $mappings[ $period ] ) ? $mappings[ $period ] : false;
    }

    /**
     * Get default churn rate for a period
     *
     * @param string $period Subscription period
     * @return float Default churn rate
     */
    private function get_default_churn_rate( $period ) {
        $defaults = array(
            'day'       => 0.10,
            'week'      => 0.08,
            'month'     => 0.05,
            'quarter'   => 0.04,
            'semi-year' => 0.03,
            'year'      => 0.02,
        );

        return isset( $defaults[ $period ] ) ? $defaults[ $period ] : 0.05;
    }

    /**
     * Save customer LTV to meta
     *
     * @param int $customer_id Customer ID (WordPress user ID)
     * @param int $subscription_id Subscription ID
     * @param float $predicted_ltv Predicted LTV value
     */
    public function save_customer_ltv( $customer_id, $subscription_id, $predicted_ltv ) {
        if ( ! $customer_id || ! $subscription_id ) {
            return;
        }

        // Save to user meta
        update_user_meta( $customer_id, 'pys_predicted_ltv_' . $subscription_id, $predicted_ltv );
        update_user_meta( $customer_id, 'pys_predicted_ltv_calculated_at_' . $subscription_id, current_time( 'mysql' ) );

    }

    /**
     * Get customer LTV from meta
     *
     * @param int $customer_id Customer ID (WordPress user ID)
     * @param int $subscription_id Subscription ID
     * @return float|null Predicted LTV or null if not found
     */
    public function get_customer_ltv( $customer_id, $subscription_id ) {
        if ( ! $customer_id || ! $subscription_id ) {
            return null;
        }

        return get_user_meta( $customer_id, 'pys_predicted_ltv_' . $subscription_id, true );
    }

    /**
     * Manual recalculation handler (for admin UI)
     */
    public function manual_recalculate_churn() {
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        // Check nonce
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pys_recalculate_woo_churn' ) ) {
            wp_die( 'Invalid nonce' );
        }

        // Run calculation
        $this->calculate_and_save_churn_rates();

        // Redirect back with success message
        wp_redirect( add_query_arg( array(
            'page' => 'pixelyoursite',
            'pys_woo_churn_recalculated' => '1'
        ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Get all churn rates
     *
     * @return array Churn rates by period
     */
    public function get_all_churn_rates() {
        $periods = array( 'day', 'week', 'month', 'quarter', 'semi-year', 'year' );
        $churn_rates = array();

        foreach ( $periods as $period ) {
            $churn_rates[ $period ] = $this->get_cached_churn_rate( $period );
        }

        return $churn_rates;
    }

    /**
     * Get last calculation timestamp
     *
     * @return string|null Timestamp or null
     */
    public function get_last_calculation_time() {
        return $this->getOption( 'woo_churn_last_calculated', null );
    }
}

/**
 * Get singleton instance
 *
 * @return WOO_LTV_Calculator
 */
function WOO_LTV_Calculator() {
    return WOO_LTV_Calculator::instance();
}

// Initialize the manager
WOO_LTV_Calculator();