<?php
/**
 * Testimonial Module for ShopBiOG Core
 * Replaces Real Testimonials third-party plugin.
 *
 * @package ShopBiOG\Core\Modules\Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Testimonials {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Testimonials|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return ShopBiOG_Testimonials
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
        // Register shortcode handlers.
        add_shortcode('shopbiog_testimonials', array($this, 'render_testimonials'));
        add_shortcode('sp_testimonial', array($this, 'render_testimonials'));
        add_shortcode('sp_real_template', array($this, 'render_testimonials'));
    }

    /**
     * Render testimonial grid/slider markup.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render_testimonials($atts = array()) {
        $atts = shortcode_atts(array(
            'id'    => '',
            'limit' => 6,
        ), $atts);

        // Fetch testimonials from spt_testimonial CPT or custom query
        $args = array(
            'post_type'      => 'spt_testimonial',
            'posts_per_page' => intval($atts['limit']),
            'post_status'    => 'publish',
        );
        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return '';
        }

        ob_start();
        ?>
        <div class="shopbiog-testimonials-wrap">
            <div class="shopbiog-testimonials-grid">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $post_id = get_the_ID();
                    $rating = get_post_meta($post_id, '_tfree_rating', true);
                    $rating_val = $rating ? intval($rating) : 5;
                    $identity = get_post_meta($post_id, '_tfree_identity', true);
                    $designation = get_post_meta($post_id, '_tfree_designation', true);
                    ?>
                    <div class="shopbiog-testimonial-card">
                        <div class="testimonial-rating-stars">
                            <?php echo str_repeat('<span class="star">&#9733;</span>', $rating_val); ?>
                        </div>
                        <h4 class="testimonial-title"><?php the_title(); ?></h4>
                        <div class="testimonial-content"><?php the_content(); ?></div>
                        <?php if ($identity || $designation) : ?>
                            <div class="testimonial-author">
                                <strong><?php echo esc_html($identity); ?></strong>
                                <?php if ($designation) : ?>
                                    <span class="designation"><?php echo esc_html($designation); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
