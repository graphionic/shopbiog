<?php
/**
 * Image & LCP Performance Optimization for ShopBiOG Core
 *
 * Handles fetchpriority, LCP image lazy-load exclusion, explicit dimension
 * attribute enforcement, and image decoding strategies.
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShopBiOG_Image_Performance {

    /**
     * Singleton instance.
     *
     * @var ShopBiOG_Image_Performance|null
     */
    private static $instance = null;

    /**
     * Known missing dimensions map.
     *
     * @var array
     */
    private $dimension_map = [
        'Anti-odor-logo.png' => ['width' => 200, 'height' => 50],
        'Toe-closure-logo.png' => ['width' => 200, 'height' => 50],
        'Reinforced-Heel-logo.png' => ['width' => 200, 'height' => 50],
        'Environment-Friendly-logo.png' => ['width' => 250, 'height' => 50],
        'Extra-padding-logo.png' => ['width' => 200, 'height' => 50],
        'Biodegradable-logo.png' => ['width' => 200, 'height' => 50],
        'Breathable-mesh-logo.png' => ['width' => 200, 'height' => 50],
        'Anti-Blister-logo.png' => ['width' => 200, 'height' => 50],
        'Blister-free-logo.png' => ['width' => 200, 'height' => 50],
        '404.png' => ['width' => 180, 'height' => 180],
    ];

    /**
     * Get instance.
     *
     * @return ShopBiOG_Image_Performance
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
        // Enforce LCP & attribute filters
        add_filter('wp_get_attachment_image_attributes', [$this, 'optimize_attachment_image_attributes'], 10, 3);
        add_filter('wp_content_img_tag', [$this, 'optimize_content_img_tags'], 10, 3);
        add_filter('wp_img_tag_add_loading_attr', [$this, 'disable_lazy_load_for_lcp'], 10, 3);
        add_filter('wp_img_tag_add_fetchpriority_attr', [$this, 'set_fetchpriority_for_lcp'], 10, 3);
    }

    /**
     * Optimize attachment image attributes before rendering.
     *
     * @param array        $attr       Attributes for the image tag.
     * @param WP_Post      $attachment Attachment post object.
     * @param string|array $size       Requested image size.
     * @return array
     */
    public function optimize_attachment_image_attributes($attr, $attachment, $size) {
        // Single / Variable Product Main Image (LCP Candidate)
        if (is_product() && isset($attr['class']) && strpos($attr['class'], 'wp-post-image') !== false) {
            $attr['fetchpriority'] = 'high';
            $attr['decoding'] = 'async';
            unset($attr['loading']); // Remove loading="lazy" from primary product LCP
        }

        // Homepage Hero Banner Image (Attachment ID 9158 or hero banner class)
        if (is_front_page() && isset($attachment->ID) && 9158 === (int)$attachment->ID) {
            $attr['fetchpriority'] = 'high';
            $attr['decoding'] = 'async';
            unset($attr['loading']); // Remove loading="lazy" from homepage hero LCP
        }

        return $attr;
    }

    /**
     * Optimize inline HTML img tags in post content.
     *
     * @param string $filtered_image Full img tag HTML.
     * @param string $context        Context (e.g., 'the_content').
     * @param int    $attachment_id  Attachment ID.
     * @return string
     */
    public function optimize_content_img_tags($filtered_image, $context, $attachment_id) {
        // Check for missing width or height attributes and inject intrinsic dimensions
        foreach ($this->dimension_map as $filename => $dims) {
            if (strpos($filtered_image, $filename) !== false) {
                if (strpos($filtered_image, 'width=') === false) {
                    $filtered_image = str_replace('<img ', '<img width="' . $dims['width'] . '" ', $filtered_image);
                }
                if (strpos($filtered_image, 'height=') === false) {
                    $filtered_image = str_replace('<img ', '<img height="' . $dims['height'] . '" ', $filtered_image);
                }
            }
        }

        return $filtered_image;
    }

    /**
     * Disable lazy loading for high-priority LCP images.
     *
     * @param string|bool $value   The loading attribute value ('lazy', false, etc.).
     * @param string      $image   The HTML img tag.
     * @param string      $context The context.
     * @return string|bool
     */
    public function disable_lazy_load_for_lcp($value, $image, $context) {
        // Exclude primary product image and homepage hero image from lazy loading
        if (strpos($image, 'wp-post-image') !== false || strpos($image, 'wp-image-9158') !== false || strpos($image, 'fetchpriority="high"') !== false) {
            return false;
        }
        return $value;
    }

    /**
     * Set fetchpriority="high" for primary LCP image tags.
     *
     * @param string|bool $value   The fetchpriority attribute value ('high', false, etc.).
     * @param string      $image   The HTML img tag.
     * @param string      $context The context.
     * @return string|bool
     */
    public function set_fetchpriority_for_lcp($value, $image, $context) {
        if (strpos($image, 'wp-post-image') !== false || strpos($image, 'wp-image-9158') !== false) {
            return 'high';
        }
        return $value;
    }
}
