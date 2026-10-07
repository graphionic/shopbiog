<?php
/**
 * Database & Background Scheduler Maintenance Module for ShopBiOG Core
 *
 * Provides safe, controlled maintenance routines for Action Scheduler,
 * WP-Cron, WooCommerce session data, and database hygiene.
 *
 * NOTE: Execution is CLI or admin-only; NEVER executed automatically on frontend requests.
 *
 * @package ShopBiOG\Core\Modules\Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Database_Maintenance {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Database_Maintenance|null
     */
    private static $instance = null;

    /**
     * Get instance.
     *
     * @return ShopBiOG_Database_Maintenance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        // Registered for WP-CLI or manual admin action invocation only.
    }

    /**
     * Purge historical Action Scheduler completed, canceled, and resolved failed actions.
     *
     * @param int $days_to_keep Number of days of completed/canceled history to retain (default: 30).
     * @return array Results summary.
     */
    public function run_action_scheduler_cleanup($days_to_keep = 30) {

        global $wpdb;
        $as_actions_table = $wpdb->prefix . 'actionscheduler_actions';
        $cutoff_date = date('Y-m-d H:i:s', strtotime("-$days_to_keep days"));

        $purged_completed = 0;
        $purged_canceled = 0;
        $purged_failed = 0;

        if (class_exists('ActionScheduler_Store')) {
            $store = ActionScheduler_Store::instance();

            // 1. Purge completed actions older than cutoff date
            $completed_ids = $wpdb->get_col($wpdb->prepare("
                SELECT action_id FROM $as_actions_table 
                WHERE status = 'complete' AND scheduled_date_gmt < %s
            ", $cutoff_date));

            foreach ($completed_ids as $id) {
                try {
                    $store->delete_action($id);
                    $purged_completed++;
                } catch (\Exception $e) {
                    // Ignore deletion error
                }
            }

            // 2. Purge canceled actions older than cutoff date
            $canceled_ids = $wpdb->get_col($wpdb->prepare("
                SELECT action_id FROM $as_actions_table 
                WHERE status = 'canceled' AND scheduled_date_gmt < %s
            ", $cutoff_date));

            foreach ($canceled_ids as $id) {
                try {
                    $store->delete_action($id);
                    $purged_canceled++;
                } catch (\Exception $e) {
                    // Ignore deletion error
                }
            }

            // 3. Purge historical resolved failed actions (e.g. stale GLA Jetpack failures from July/August 2026)
            $failed_ids = $wpdb->get_col($wpdb->prepare("
                SELECT action_id FROM $as_actions_table 
                WHERE status = 'failed' AND (hook LIKE 'gla/jobs/%%' OR hook = 'rocket_preload_job_check_finished' OR hook = 'action_scheduler/migration_hook') AND scheduled_date_gmt < %s
            ", $cutoff_date));

            foreach ($failed_ids as $id) {
                try {
                    $store->delete_action($id);
                    $purged_failed++;
                } catch (\Exception $e) {
                    // Ignore deletion error
                }
            }
        }

        return [
            'purged_completed' => $purged_completed,
            'purged_canceled' => $purged_canceled,
            'purged_failed' => $purged_failed,
            'cutoff_date' => $cutoff_date,
        ];
    }

    /**
     * Unschedule registered WP-Cron events that belong exclusively to confirmed uninstalled plugins.
     *
     * @return array List of unscheduled cron hooks.
     */
    public function unschedule_orphaned_cron_events() {
        $cron = _get_cron_array();
        $unscheduled = [];

        // Confirmed uninstalled plugin cron hooks
        $confirmed_orphaned_hooks = [
            'wplmi/fetch_plugin_data' => true,
            'wpcode_usage_tracking_cron' => true,
            'ai1wm_storage_cleanup' => true,
            'wpseo-reindex' => true,
            'wpseo_permalink_structure_check' => true,
            'pum_weekly_scheduled_events' => true,
            'mnx_daily_cron_event' => true,
        ];

        if (is_array($cron)) {
            foreach ($cron as $timestamp => $cronhooks) {
                foreach ($cronhooks as $hook => $events) {
                    if (isset($confirmed_orphaned_hooks[$hook])) {
                        foreach ($events as $key => $event) {
                            wp_unschedule_event($timestamp, $hook, $event['args']);
                            $unscheduled[] = $hook;
                        }
                    }
                }
            }
        }

        return array_unique($unscheduled);
    }


    /**
     * Clean expired transients from wp_options.
     *
     * @return int Number of deleted expired transients.
     */
    public function clean_expired_transients() {
        global $wpdb;
        $now = time();
        $expired_names = $wpdb->get_col($wpdb->prepare("
            SELECT option_name FROM {$wpdb->options}
            WHERE option_name LIKE %s AND option_value < %d
        ", '_transient_timeout_%', $now));

        $count = 0;
        foreach ($expired_names as $transient_timeout) {
            $transient = str_replace('_transient_timeout_', '', $transient_timeout);
            delete_transient($transient);
            $count++;
        }

        return $count;
    }

    /**
     * Clean expired WooCommerce sessions.
     *
     * @return int Number of sessions cleaned.
     */
    public function clean_expired_wc_sessions() {
        if (function_exists('wc_cleanup_session_data')) {
            wc_cleanup_session_data();
            return 1;
        }
        return 0;
    }
}
