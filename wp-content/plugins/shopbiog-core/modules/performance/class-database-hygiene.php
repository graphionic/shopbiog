<?php
/**
 * Database Hygiene & Safe Cleanup for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Database_Hygiene {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Database_Hygiene|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Database_Hygiene
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
        $this->init_hygiene();
    }

    /**
     * Execute safe database hygiene routines.
     */
    private function init_hygiene() {
        // Safe deletion of verified orphaned option
        if (get_option('yith_woocompare_fields_attrs') !== false) {
            delete_option('yith_woocompare_fields_attrs');
        }
    }

    /**
     * Clean expired transients safely.
     *
     * @return int Number of expired transients deleted.
     */
    public static function clean_expired_transients() {
        global $wpdb;
        $now = time();
        
        $sql = "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND CAST(option_value AS UNSIGNED) < %d";
        $expired = $wpdb->get_col($wpdb->prepare($sql, $now));

        $count = 0;
        if (!empty($expired)) {
            foreach ($expired as $transient_timeout) {
                $transient = str_replace('_transient_timeout_', '_transient_', $transient_timeout);
                delete_option($transient_timeout);
                delete_option($transient);
                $count++;
            }
        }
        return $count;
    }
}
