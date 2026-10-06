<?php
/**
 * Last Modified Date Handler for ShopBiOG Core
 * Replaces third-party WP Last Modified Info plugin functionality.
 *
 * @package ShopBiOG\Core\Modules\Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Last_Modified {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Last_Modified|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Last_Modified
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
     * Initialize hooks and shortcodes.
     */
    private function init_hooks() {
        // Register shortcode for displaying last modified date.
        add_shortcode('shopbiog_last_modified', array($this, 'render_shortcode'));
        
        // Compatibility shortcode alias for WP Last Modified Info.
        add_shortcode('lmt-post-modified-info', array($this, 'render_shortcode'));
        add_shortcode('lmt-page-modified-info', array($this, 'render_shortcode'));
    }

    /**
     * Render shortcode output.
     *
     * @param array $atts Shortcode attributes.
     * @return string Formatted modified date HTML.
     */
    public function render_shortcode($atts = array()) {
        if (!apply_filters('shopbiog_enable_last_modified', true)) {
            return '';
        }

        $atts = shortcode_atts(array(
            'format' => get_option('date_format'),
            'label'  => __('Last updated on:', 'shopbiog-core'),
            'post_id' => get_the_ID(),
        ), $atts, 'shopbiog_last_modified');

        $post_id = intval($atts['post_id']);
        if (!$post_id) {
            return '';
        }

        $modified_date = get_the_modified_date($atts['format'], $post_id);
        if (!$modified_date) {
            return '';
        }

        $label = esc_html($atts['label']);
        $date_display = esc_html($modified_date);
        $iso_date = esc_attr(get_the_modified_date('c', $post_id));

        return sprintf(
            '<span class="shopbiog-last-modified"><span class="label">%s</span> <time class="updated" datetime="%s">%s</time></span>',
            $label,
            $iso_date,
            $date_display
        );
    }
}
