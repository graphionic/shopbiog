<?php
/**
 * WP Rocket Cache Compatibility & Safety Layer for ShopBiOG
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Cache_Compatibility {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Cache_Compatibility|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Cache_Compatibility
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
        // Filter WP Rocket cache rejected URIs
        add_filter('rocket_cache_reject_uri', [$this, 'exclude_ecommerce_uris']);

        // Filter WP Rocket cache rejected cookies
        add_filter('rocket_cache_reject_cookies', [$this, 'exclude_ecommerce_cookies']);

        // Prevent WP Rocket from lazy-loading high-priority LCP images
        add_filter('rocket_lazyload_excluded_attributes', [$this, 'exclude_lcp_attributes']);
        add_filter('rocket_lazyload_excluded_src', [$this, 'exclude_lcp_sources']);
    }

    /**
     * Exclude dynamic eCommerce, gateway, FunnelKit, and REST API URIs from WP Rocket page caching.
     *
     * @param array $urls Array of rejected URI regex patterns.
     * @return array
     */
    public function exclude_ecommerce_uris($urls) {
        if (!is_array($urls)) {
            $urls = [];
        }

        $exclusions = [
            '/cart/(.*)',
            '/shopping-cart/(.*)',
            '/checkout/(.*)',
            '/my-account/(.*)',
            '/checkouts/(.*)',
            '/offer/(.*)',
            '/upsell/(.*)',
            '/thank-you/(.*)',
            '/wp-json/(.*)',
            '/wc-api/(.*)',
        ];

        foreach ($exclusions as $exclusion) {
            if (!in_array($exclusion, $urls, true)) {
                $urls[] = $exclusion;
            }
        }

        return $urls;
    }

    /**
     * Ensure WooCommerce cart and session cookies trigger cache rejection / dynamic rendering.
     *
     * @param array $cookies Array of rejected cookie regex patterns.
     * @return array
     */
    public function exclude_ecommerce_cookies($cookies) {
        if (!is_array($cookies)) {
            $cookies = [];
        }

        $woo_cookies = [
            'woocommerce_items_in_cart',
            'woocommerce_cart_hash',
            'wp_woocommerce_session_',
        ];

        foreach ($woo_cookies as $cookie) {
            if (!in_array($cookie, $cookies, true)) {
                $cookies[] = $cookie;
            }
        }

        return $cookies;
    }

    /**
     * Exclude attributes from WP Rocket LazyLoad (protect Wave 4 LCP priority images).
     *
     * @param array $attributes Excluded attributes.
     * @return array
     */
    public function exclude_lcp_attributes($attributes) {
        if (!is_array($attributes)) {
            $attributes = [];
        }

        $attributes[] = 'fetchpriority="high"';
        $attributes[] = 'data-no-lazy="1"';

        return $attributes;
    }

    /**
     * Exclude image source patterns from WP Rocket LazyLoad.
     *
     * @param array $srcs Excluded image sources.
     * @return array
     */
    public function exclude_lcp_sources($srcs) {
        if (!is_array($srcs)) {
            $srcs = [];
        }

        // Exclude primary product gallery & hero logo images from lazyloading
        $srcs[] = 'logo';

        return $srcs;
    }
}
