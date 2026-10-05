<?php
/**
 * Empty cart page
 *
 * @author  NasaTheme
 * @package Elessi-theme/WooCommerce
 * @version 7.0.1
 */
defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0) : ?>
    <p class="return-to-shop margin-bottom-80">
        <a class="button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
            <?php
            /**
             * Filter "Return To Shop" text.
             *
             * @since 4.6.0
             * @param string $default_text Default text.
             */
            echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'elessi-theme')));
            ?>
        </a>
    </p>
    <div class="row nasa-empty-cart-recommend-product">
        <?php do_action('nasa_recommend_product'); ?>
    </div>
<?php 
endif; 
do_action('nasa_carts_empty_after');
