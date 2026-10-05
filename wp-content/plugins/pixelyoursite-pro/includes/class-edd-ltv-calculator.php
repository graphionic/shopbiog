<?php
/**
 * EDD LTV Calculator
 * 
 * Calculates Predicted Lifetime Value for EDD Recurring subscriptions
 * using churn-based models and historical data.
 *
 * @package PixelYourSite
 */

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class EDD_LTV_Calculator extends Settings {

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
        parent::__construct( 'EddSettings' );
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Schedule daily churn calculation
        if ( ! wp_next_scheduled( 'pys_calculate_edd_churn_rates' ) ) {
            wp_schedule_event( time(), 'daily', 'pys_calculate_edd_churn_rates' );

            // Run initial calculation if no churn rates exist
            $this->maybe_run_initial_calculation();
        }

        add_action( 'pys_calculate_edd_churn_rates', array( $this, 'calculate_and_save_churn_rates' ) );

        // Manual recalculation hook
        add_action( 'admin_post_pys_recalculate_churn', array( $this, 'manual_recalculate_churn' ) );
    }

    /**
     * Run initial calculation if no churn rates exist
     */
    private function maybe_run_initial_calculation() {
        // Check if we have any churn rates calculated
        $has_churn_data = $this->getOption( 'edd_churn_month', false );

        if ( $has_churn_data === false ) {
            // No churn data exists, run initial calculation
            $this->calculate_and_save_churn_rates();
        }
    }

    /**
     * C. Calculate Predicted LTV for a subscription
     *
     * @param object $subscription EDD_Subscription object
     * @return float Predicted LTV
     */
    public function calculate_predicted_ltv( $subscription ) {
        if ( ! $subscription || ! $subscription->id ) {
            return 0;
        }

        $initial_amount = (float) $subscription->initial_amount;
        $recurring_amount = (float) $subscription->recurring_amount;
        $period = $subscription->period;
        $bill_times = (int) $subscription->bill_times;

        // Calculate expected billings
        $expected_billings = 0;

        if ( $bill_times > 0 ) {
            // Limited subscription - exact calculation
            $expected_billings = $bill_times;
        } else {
            // Unlimited subscription - churn-based calculation
            $churn_rate = $this->get_cached_churn_rate( $period );

            if ( $churn_rate > 0 ) {
                $expected_billings = 1 / $churn_rate;
            } else {
                // Fallback: conservative estimate
                $expected_billings = $this->get_fallback_billings( $period );
            }
        }

        // Predicted LTV = initial_amount + (recurring_amount × expected_billings)
        $predicted_ltv = $initial_amount + ( $recurring_amount * $expected_billings );

        PYS()->getLog()->debug( 'EDD LTV Calculator: Calculated Predicted LTV', array(
            'subscription_id' => $subscription->id,
            'period' => $period,
            'bill_times' => $bill_times,
            'initial_amount' => $initial_amount,
            'recurring_amount' => $recurring_amount,
            'expected_billings' => $expected_billings,
            'predicted_ltv' => $predicted_ltv
        ) );

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
        $option_name = 'edd_churn_' . $period;
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

        if ( ! class_exists( 'EDD_Subscriptions_DB' ) ) {
            PYS()->getLog()->debug( 'EDD LTV Calculator: EDD Recurring not available' );
            return;
        }

        $periods = array( 'day', 'week', 'month', 'quarter', 'semi-year', 'year' );

        foreach ( $periods as $period ) {
            $churn_rate = $this->calculate_churn_for_period( $period );

            // Save to individual option: update_option( 'edd_churn_month', 0.08 )
            $option_name = 'edd_churn_' . $period;
            $this->addOption( $option_name, 'number', 0.05);
            $this->updateOptions( array($option_name => $churn_rate) );
        }

        // Save last calculation timestamp
        $this->addOption('edd_churn_last_calculated', 'number', 0);
        $this->updateOptions( array('edd_churn_last_calculated' => current_time( 'mysql' ) ) );

    }

    /**
     * Calculate churn rate for a specific period
     *
     * @param string $period Subscription period
     * @return float Churn rate (0-1)
     */
    private function calculate_churn_for_period( $period ) {
        global $wpdb;

        // Get all subscriptions for this period
        $subscriptions_table = $wpdb->prefix . 'edd_subscriptions';

        // Count total subscriptions created for this period
        $total_query = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$subscriptions_table} WHERE period = %s AND status != 'pending'",
            $period
        );
        $total_subscriptions = (int) $wpdb->get_var( $total_query );

        if ( $total_subscriptions === 0 ) {
            return $this->get_default_churn_rate( $period );
        }

        // Count cancelled/expired subscriptions for this period
        $churned_query = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$subscriptions_table}
            WHERE period = %s
            AND status IN ('cancelled', 'expired', 'failing')",
            $period
        );
        $churned_subscriptions = (int) $wpdb->get_var( $churned_query );

        // Calculate churn rate
        $churn_rate = $churned_subscriptions / $total_subscriptions;

        // Ensure churn rate is within reasonable bounds (1% - 50%)
        $churn_rate = max( 0.01, min( 0.50, $churn_rate ) );

        return round( $churn_rate, 4 );
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
     * @param int $customer_id Customer ID
     * @param int $subscription_id Subscription ID
     * @param float $predicted_ltv Predicted LTV value
     */
    public function save_customer_ltv( $customer_id, $subscription_id, $predicted_ltv ) {
        if ( ! $customer_id || ! $subscription_id ) {
            return;
        }

        // Save to customer meta
        edd_update_customer_meta( $customer_id, 'pys_predicted_ltv_' . $subscription_id, $predicted_ltv );
        edd_update_customer_meta( $customer_id, 'pys_predicted_ltv_calculated_at_' . $subscription_id, current_time( 'mysql' ) );

    }

    /**
     * Get customer LTV from meta
     *
     * @param int $customer_id Customer ID
     * @param int $subscription_id Subscription ID
     * @return float|null Predicted LTV or null if not found
     */
    public function get_customer_ltv( $customer_id, $subscription_id ) {
        if ( ! $customer_id || ! $subscription_id ) {
            return null;
        }

        return edd_get_customer_meta( $customer_id, 'pys_predicted_ltv_' . $subscription_id, true );
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
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pys_recalculate_churn' ) ) {
            wp_die( 'Invalid nonce' );
        }

        // Run calculation
        $this->calculate_and_save_churn_rates();

        // Redirect back with success message
        wp_redirect( add_query_arg( array(
            'page' => 'pixelyoursite',
            'pys_churn_recalculated' => '1'
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
        return $this->getOption( 'edd_churn_last_calculated', null );
    }
}

/**
 * Get singleton instance
 *
 * @return EDD_LTV_Calculator
 */
function EDD_LTV_Calculator() {
    return EDD_LTV_Calculator::instance();
}

// Initialize the manager
EDD_LTV_Calculator();