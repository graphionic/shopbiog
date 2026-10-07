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
        add_filter('nasa_google_font_weight', array($this, 'optimize_poppins_font_weights'));
        add_filter('wp_resource_hints', array($this, 'add_font_resource_hints'), 10, 2);
        
        // Ensure Elementor Inline Font Icons experiment is active for performance
        if (get_option('elementor_experiment-e_font_icon_svg') !== 'active') {
            update_option('elementor_experiment-e_font_icon_svg', 'active');
        }
    }

    /**
     * Optimize Google Fonts Poppins weights.
     * 
     * Eliminates 8 unused italic variants and weight 300, retaining only
     * actively used weights: 400, 500, 600, 700, 800, 900.
     *
     * @param string $weight Default weight string.
     * @return string
     */
    public function optimize_poppins_font_weights($weight) {
        return ':400,500,600,700,800,900';
    }

    /**
     * Add preconnect resource hints for Google Fonts.
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type Relation type (e.g. preconnect).
     * @return array
     */
    public function add_font_resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            $urls[] = array(
                'href' => 'https://fonts.googleapis.com',
            );
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin' => 'anonymous',
            );
        }
        return $urls;
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
