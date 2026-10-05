<?php

/**
 * Checkout coupon form
 * 
 * @author  NasaTheme
 * @package Elessi-theme/WooCommerce
 * @version 9.8.0
 */

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) : // @codingStandardsIgnoreLine.
    return;
endif;

defined('NASA_CHECKOUT_LAYOUT') or define('NASA_CHECKOUT_LAYOUT', 'default');
?>

<div class="woocommerce-form-coupon-toggle nasa-toggle-coupon-checkout">
    <?php wc_print_notice(apply_filters('woocommerce_checkout_coupon_message', __('Have a coupon?', 'elessi-theme') . ' <a href="#" class="showcoupon" aria-label="' . esc_attr__('Enter your coupon code', 'elessi-theme') . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false">' . __('Click here to enter your code', 'elessi-theme') . '<svg width="30" height="30" viewBox="0 0 32 32"><path d="M15.233 19.175l0.754 0.754 6.035-6.035-0.754-0.754-5.281 5.281-5.256-5.256-0.754 0.754 3.013 3.013z" fill="currentColor" /></svg></a>'), 'notice'); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display: none">
    <div class="row">
        <div class="large-12 columns">
            <p><?php esc_html_e('If you have a coupon code, please apply it below.', 'elessi-theme'); ?></p>

            <div class="form-row form-row-first coupon">
                <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e('Coupon code', 'elessi-theme'); ?>" id="coupon_code" value="" />
                <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'elessi-theme'); ?>"><?php esc_html_e('Apply coupon', 'elessi-theme'); ?></button>
            </div>
        </div>
        
        <div class="large-12 columns title-align-left">
            <?php 
            if (NASA_CHECKOUT_LAYOUT !== 'modern') :
                do_action('nasa_woocommerce_checkout_coupon_form_end');
            endif;
            ?>
        </div>
    </div>
</form>