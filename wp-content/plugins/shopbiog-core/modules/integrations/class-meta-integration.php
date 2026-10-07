<?php
/**
 * Meta (Facebook) Integration Module for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Integrations
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Meta_Integration {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Meta_Integration|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Meta_Integration
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
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks.
     */
    private function init_hooks() {
        /**
         * Disable Meta for WooCommerce browser pixel injection.
         * 
         * Purpose (Migrated from WPCode Snippet #9180):
         * Keeps Meta for WooCommerce catalog synchronization (Facebook/Instagram Shop) active
         * while preventing its browser pixel from double-firing alongside PixelYourSite PRO.
         */
        add_filter('facebook_for_woocommerce_integration_pixel_enabled', '__return_false');
    }
}
