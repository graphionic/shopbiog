<?php
$class_slide = 'nasa-slick-slider nasa-slick-nav nasa-category-horizontal-7';
$auto_slide = isset($auto_slide) ? $auto_slide : 'false';
$loop_slide = isset($loop_slide) ? $loop_slide : 'false';
$auto_delay_time = isset($auto_delay_time) && (int) $auto_delay_time ? (int) $auto_delay_time * 1000 : 6000;

/**
 * Attributes sliders
 */
$data_attrs = array();
$data_attrs[] = 'data-columns="' . esc_attr($columns_number) . '"';
$data_attrs[] = 'data-columns-small="' . esc_attr($columns_number_small) . '"';
$data_attrs[] = 'data-columns-tablet="' . esc_attr($columns_number_tablet) . '"';
$data_attrs[] = 'data-autoplay="' . esc_attr($auto_slide) . '"';
$data_attrs[] = 'data-delay="' . esc_attr($auto_delay_time) . '"';
$data_attrs[] = 'data-loop="' . esc_attr($loop_slide) . '"';
$data_attrs[] = 'data-slides-all="' . esc_attr($auto_slide) . '"';
$data_attrs[] = 'data-switch-tablet="' . nasa_switch_tablet() . '"';
$data_attrs[] = 'data-switch-desktop="' . nasa_switch_desktop() . '"';

$attrs_str = !empty($data_attrs) ? ' ' . implode(' ', $data_attrs) : '';
?>

<div class="nasa-flex align-start flex-wrap category-slider nasa-category-slider-horizontal-7 nasa-category-slider-horizontal<?php echo esc_attr($el_class); ?>">
    <div class="large-3 small-12 nasa-category-slider-left padding-right-20 rtl-padding-right-0 rtl-padding-left-20">
        <h3 class="section-title primary-color margin-bottom-0 margin-top-0">
            <?php echo esc_attr($title); ?>
        </h3>
        <div class="section-desc">
            <?php echo html_entity_decode($description_cats); ?>
        </div>
        <div class="section-nav nasa-flex">
            <a class="nasa-nav-arrow slick-prev slick-arrow slick-disabled" href="javascript:void(0);" rel="nofollow" aria-disabled="false" style="">
                <svg viewBox="-1 -2 19 19" fill="currentColor" class="bi bi-arrow-right-short" transform="rotate(180)">
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"></path>
                    </g>
                </svg>
            </a>
            <a class="nasa-nav-arrow slick-next slick-arrow" href="javascript:void(0);" rel="nofollow" style="" aria-disabled="true">
                <svg viewBox="-1 -1 19 19" fill="currentColor" class="bi bi-arrow-right-short" transform="rotate(0)">
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"></path>
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="large-9 small-12 nasa-category-slider-right">
        <div class="<?php echo esc_attr($class_slide); ?>" <?php echo $attrs_str; ?>>
            <?php foreach ($product_categories as $category) : ?>
                <div class="product-category nasa-slider-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($delay_animation_product); ?>ms">
                    <div class="nasa-cat-link">
                        <a class="nasa-cat-thumb" href="<?php echo get_term_link($category, 'product_cat'); ?>" title="<?php echo esc_attr($category->name); ?>">
                            <?php nasa_category_thumbnail($category, 'nasa-medium'); ?>
                        </a>
                        <div class="nasa-cat-info">
                            <p class="header-title text-start nasa-cat-title margin-top-0 margin-bottom-0">
                                <a href="<?php echo get_term_link($category, 'product_cat'); ?>" title="<?php echo esc_attr($category->name); ?>">
                                    <?php echo $category->name; ?>
                                </a>
                            </p>
                            <?php if ($category->description != ''): ?>
                                <p class="header-title text-start nasa-cat-title margin-top-0 margin-bottom-0">
                                    <?php echo $category->description; ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-start nasa-cat-count">
                                <?php echo sprintf(_n('%d product', '%d products', $category->count, 'nasa-core'), $category->count); ?>
                            </p>
                        </div>
                        <?php do_action('woocommerce_after_subcategory_title', $category); ?>
                        <a class="button nasa-cat-link-float" href="<?php echo get_term_link($category, 'product_cat'); ?>" title="<?php echo esc_attr($category->name); ?>">
                            <svg viewBox="-2 -2 20 20" fill="currentColor" class="bi bi-arrow-right-short" transform="rotate(-45)">
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"></path>
                                </g>
                            </svg>
                        </a>
                    </div>

                </div>
            <?php
                $delay_animation_product += $_delay_item;
            endforeach;
            ?>
        </div>
    </div>
</div>