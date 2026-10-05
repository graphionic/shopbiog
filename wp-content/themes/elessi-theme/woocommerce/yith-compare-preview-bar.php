<?php
/**
 * Woocommerce Preview Bar template
 *
 * @author  NasaTheme
 * @package Elessi-theme/WooCommerce
 * @version 3.0.0
 *
 * Template variables:
 *
 * @var $products WC_Product[]
 * @var $has_more bool
 * @var $remaining int
 * @var $compare_button_text string
 * @var $compare_button_classes string
 * @var $compare_url string
 */
defined('YITH_WOOCOMPARE') || exit; // Exit if accessed directly.

global $nasa_opt;

$max_compare = isset($nasa_opt['max_compare']) ? (int) $nasa_opt['max_compare'] : 4;

$count_compare = count($products) + (int) $remaining;
$has_more = $max_compare < $count_compare;
$remaining = $has_more ? $count_compare - ($max_compare - 1) : 0;
$products = $has_more ? array_slice($products, 0, ($max_compare - 1), true) : $products;
$placeholders_to_show = $max_compare - count($products);

$nasa_src_no_image = wc_placeholder_img_src();
$image_size = apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail');

$compare_url = isset($nasa_opt['nasa-page-view-compage']) && (int) $nasa_opt['nasa-page-view-compage'] ? get_permalink((int) $nasa_opt['nasa-page-view-compage']) : home_url('/');
?>

<div id="yith-woocompare-preview-bar" class="<?php echo!empty($products) ? 'shown' : ''; ?>" data-count-compare="<?php echo esc_attr($count_compare); ?>" data-compare-url="<?php echo esc_url($compare_url); ?>">
    <a class="nasa-close-mini-compare nasa-stclose" href="javascript:void(0)" rel="nofollow"></a>
    
    <div class="container">
        <header>
            <h5 class="clearfix text-center mobile-text-left rtl-mobile-text-right nasa-compare-label">
                <span class="nasa-block mobile-text-center mobile-fs-23">
                    <?php echo esc_html__('Compare Products', 'elessi-theme'); ?>
                </span>
                <span class="color-gray hide-for-mobile">
                    (<?php echo $count_compare . ' ' . ($count_compare == 1 ? esc_html__('Product', 'elessi-theme') : esc_html__('Products', 'elessi-theme')); ?>)
                </span>
            </h5>
        </header>
        
        <div class="content">
            <?php if (!empty($products)) : ?>
                <ul class="compare-list">
                    <?php
                    foreach ($products as $product_id => $product) :
                        $nasa_title = $product->get_name();
                        $nasa_href = $product->get_permalink();
                        ?>
                        <li>
                            <div class="image-wrap">
                                <?php YITH_WooCompare_Table::instance()->output_remove_anchor($product_id); ?>
                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                    <?php echo $product->get_image('thumbnail'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?>
                                </a>
                                <div class="nasa-compare-item-hover">
                                    <div class="nasa-compare-item-hover-wraper">
                                        <a href="<?php echo esc_url($nasa_href); ?>" title="<?php echo esc_attr($nasa_title); ?>">
                                            <?php echo $product->get_image($image_size, array('alt' => esc_attr($nasa_title))); ?>
                                            <h5 class="margin-top-10">
                                                <?php echo esc_html($nasa_title); ?>
                                            </h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    
                    <?php if (!$has_more) : ?>
                        <?php for ($i = 0; $i < $placeholders_to_show; $i++) : ?>
                            <li class="product-placeholder">
                                <img src="<?php echo esc_url($nasa_src_no_image); ?>" width="65" height="65" alt="<?php echo esc_attr__("Compare Product", 'elessi-theme'); ?>" />
                            </li>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <?php if ($has_more) : ?>
                        <li class="product-placeholder jc nasa-flex">
                            <span>
                                <?php
                                // translators: 1. Number of products in the comparison that exceed 5 previewed.
                                echo esc_html(sprintf(esc_html__('+%d', 'elessi-theme'), $remaining));
                                ?>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <footer>
            <?php if ($count_compare): ?>
                <a class="nasa-compare-clear-all nasa-hover-underline color-gray" href="javascript:void(0);" title="<?php echo esc_attr__('Clear All', 'elessi-theme'); ?>" rel="nofollow">
                    <?php echo esc_html__('Clear All', 'elessi-theme'); ?>
                </a>
                
                <a href="<?php echo esc_attr($compare_url); ?>" title="<?php echo esc_attr($compare_button_text); ?>" class="nasa-compare-view btn button">
                    <?php echo esc_html($compare_button_text); ?>
                </a>
            <?php endif; ?>
        </footer>
    </div>
</div>
