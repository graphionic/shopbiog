<?php
/**
 * Performance Assets Optimization for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Performance_Assets {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Performance_Assets|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Performance_Assets
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
        add_action('wp_enqueue_scripts', array($this, 'optimize_block_styles'), 100);
    }

    /**
     * Conditionally dequeue unused WooCommerce Block CSS on pages not using Gutenberg blocks.
     */
    public function optimize_block_styles() {
        if (is_admin()) {
            return;
        }

        // Check if page contains WooCommerce block content
        global $post;
        $has_wc_blocks = false;
        if (is_a($post, 'WP_Post') && has_blocks($post->post_content)) {
            if (strpos($post->post_content, 'wp:woocommerce/') !== false) {
                $has_wc_blocks = true;
            }
        }

        // Dequeue WooCommerce block CSS if no WooCommerce blocks are present
        if (!$has_wc_blocks) {
            wp_dequeue_style('wc-blocks-style');
            wp_dequeue_style('wc-blocks-vendors-style');
            wp_dequeue_style('wc-blocks-packages-style');
        }
    }
}
