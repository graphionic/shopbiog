<?php
/**
 * Woocommerce Compare button
 *
 * @author  NasaTheme
 * @package Elessi-theme/WooCommerce
 * @version 3.0.0
 * 
 * Template variables:
 *
 * @var $style string
 * @var $added bool
 * @var $product_id int
 * @var $button_target string
 * @var $compare_url string
 * @var $compare_classes string
 * @var $compare_label string
 */
defined('YITH_WOOCOMPARE') || exit; // Exit if accessed directly.

global $product, $nasa_opt;

$added = YITH_WooCompare_Products_List::instance()->has($product_id);

$class_btn = 'compare btn-compare btn-link compare-icon nasa-tip nasa-tip-left nasa-compare';
$class_btn .= $added ? ' added' : '';

$svg_compare = '<svg class="nasa-icon ns-refresh nasa-flip-vertical" viewBox="0 40 512 512" width="20" height="36" fill="currentColor"><path d="M276 467c0 8 6 21-2 23l-26 0c-128-7-230-143-174-284 5-13 13-23 16-36-18 0-41 23-54 5 5-15 25-18 41-23 15-5 36-7 48-15-2 10 23 95 6 100-21 5-13-39-18-57-8-5-8 8-11 13-71 126 29 297 174 274z m44 13c-8 0-10 5-20 3 0-6-3-13-3-18 5-3 13-3 18-5 2 7 5 15 5 20z m38-18c-5 3-10 8-18 10-2-7-5-12-7-18 5-2 10-7 18-7 2 5 7 7 7 15z m34-31c0-33-18-71-5-99 23 2 12 38 17 58 90-117-7-314-163-289 0-8-3-10-3-20 131-5 233 84 220 225-2 36-20 66-30 92 12 0 51-26 53-2 3 17-82 28-89 35z m-233-325c5-2 13-5 18-10 0 8 5 10 7 18-5 2-10 8-18 8 0-8-7-8-7-16z m38-18c8 0 10-5 21-5 0 5 2 13 2 18-5 3-13 3-18 5 0-5-5-10-5-18z"/></svg>';

$svg_check = '<svg class="nasa-icon ns-check" fill="none" height="36" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20"><polyline points="20 6 9 17 4 12"/></svg>';

/**
 * Apply Filters Icon
 */
// $icon = apply_filters('nasa_icon_compare', $svg_compare . $svg_check);
?>
<a
    href="<?php echo esc_url($compare_url); ?>"
    class="<?php echo esc_attr($class_btn); ?>"
    data-product_id="<?php echo (int) $product_id; ?>"
    target="<?php echo esc_attr($button_target); ?>"
    rel="nofollow"
    data-icon-text="<?php esc_attr_e('Compare', 'elessi-theme'); ?>" 
    data-added="<?php esc_attr_e('Added to Compare', 'elessi-theme'); ?>" 
    title="<?php esc_attr_e('Compare', 'elessi-theme'); ?>"
    data-prod="<?php echo (int) $product_id; ?>"
>
    <?php
    echo apply_filters('nasa_icon_compare', $svg_compare . $svg_check);

    if (
        isset($compare_label) &&
        is_array($compare_label) &&
        !empty($compare_label) &&
        !empty($compare_label['is_single']) &&
        $compare_label['is_single'] === true
    ) :
        echo isset($compare_label['html']) ? $compare_label['html'] : '';
    endif;
    ?>
</a>
<?php
// wp_enqueue_script('yith-woocompare-main');
