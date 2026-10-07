<?php
/**
 * WooCommerce Performance Optimization for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_WooCommerce_Performance {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_WooCommerce_Performance|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_WooCommerce_Performance
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
     * Initialize hooks.
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'optimize_cart_fragments'), 99);
    }

    /**
     * Conditionally dequeue wc-cart-fragments on purely non-commerce static pages (e.g. Contact, FAQ)
     * while preserving it on Homepage, Shop, Product, Cart, Checkout, and My Account pages.
     */
    public function optimize_cart_fragments() {
        if (is_admin()) {
            return;
        }

        // Keep cart-fragments enabled on all ecommerce routes, homepage, and shop pages
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_front_page() || is_shop() || is_product()) {
            return;
        }

        // On static non-commerce pages (e.g. Contact, FAQ, static landing pages), dequeue cart-fragments to save AJAX load
        if (is_page(['contact', 'faq']) || (is_page() && !is_cart() && !is_checkout() && !is_account_page() && !is_front_page() && !is_shop())) {
            wp_dequeue_script('wc-cart-fragments');
        }
    }
}
