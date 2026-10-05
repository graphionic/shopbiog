<?php
defined('ABSPATH') || exit;

/**
 * Publish Coupons
 */
$publish_couponts = elessi_wc_publish_coupons();
$style = isset($nasa_opt['coupon_display_style']) && in_array($nasa_opt['coupon_display_style'], array('style-1', 'style-2')) ? $nasa_opt['coupon_display_style'] : 'style-1';
$coupons_used = WC()->cart->get_applied_coupons();
$attrs_str = '';
$publish_coupon_wrap_class = 'publish-coupons nasa-flex flex-column';
$data_padding = count($publish_couponts) < 2 ? '0' : '20%';
if ($style == 'style-2') {
    $data_attrs = [
        'data-columns="1"',
        'data-columns-small="1"',
        'data-columns-tablet="1"',
        'data-padding="'.$data_padding.'"',
        'data-padding-small="'.$data_padding.'"',
        'data-padding-medium="'.$data_padding.'"',
    ];        
    
    $arr_attrs = apply_filters('elessi_attrs_coupon_wrap_mini_cart', $data_attrs);
    
    $attrs_str = !empty($arr_attrs) ? ' ' . implode(' ', $arr_attrs) : '';

    $publish_coupon_wrap_class .= ' nasa-slick-slider padding-left-30 padding-right-30 mobile-padding-left-15 mobile-padding-right-20';
}

if (!empty($publish_couponts)) : ?>
    <p class="node-title nasa-bold fs-20 mobile-fs-18">
        <?php echo esc_html__('Select an available coupon', 'elessi-theme'); ?>
    </p>

    
    <div class=" <?php echo $publish_coupon_wrap_class; ?>" data-style="<?php echo esc_attr($style)?>" <?php echo $attrs_str; ?>>
        <?php foreach ($publish_couponts as $coupon) :
            $code = $coupon->get_code();
            $amount = $coupon->get_amount();
            $discount_type = $coupon->get_discount_type();
            $discount_lbl = '';
            $publish_coupon_class = in_array($code, $coupons_used) ? ' nasa-actived' : '';
            
            if ($discount_type == 'fixed_cart') :
                $discount_lbl = sprintf(esc_html__('%s&nbsp;Discount', 'elessi-theme'), wc_price($amount));
            endif;
            
            if ($discount_type == 'percent') :
                $discount_lbl = sprintf(esc_html__('%s&nbsp;Discount', 'elessi-theme'), $amount . '%');
            endif;
            
            if ($discount_type == 'fixed_product') :
                $discount_lbl = sprintf(esc_html__('%s&nbsp;Product Discount', 'elessi-theme'), wc_price($amount));
            endif;
            
            $date_expires = $coupon->get_date_expires();
            $date_expires_lbl = !$date_expires ? esc_html__('Never expire', 'elessi-theme') : '<span class="hide-for-small">' . esc_html__('Valid until ', 'elessi-theme') . '</span>' . get_date_from_gmt(date('Y-m-d H:i:s', strtotime($date_expires)), apply_filters('nasa_coupon_date_expire_format', 'm/d/Y'));
            
            $desc = $coupon->get_description();
            
            $min_amout = $coupon->get_minimum_amount();
            $min_amout_lbl = $min_amout ? sprintf(esc_html__('The minimum spend for this coupon is&nbsp;%s', 'elessi-theme'), wc_price($min_amout)) : '';
            
            $max_amout = $coupon->get_maximum_amount();
            $max_amout_lbl = $max_amout ? sprintf(esc_html__('The maximum spend for this coupon is&nbsp;%s', 'elessi-theme'), wc_price($max_amout)) : '';


            if ( $date_expires && $date_expires->getTimestamp() < current_time('timestamp')) {
                $publish_coupon_class .= ' nasa-coupon-expired';
            }
            
            ?>
                <?php if ($style == 'style-1'):?>

                    <a href="javascript:void(0);" data-code="<?php echo esc_attr($code); ?>" class="publish-coupon fs-15<?php echo esc_html($publish_coupon_class)?>" title="<?php echo esc_attr( $publish_coupon_class == '' ? __('Click here to Apply this coupon.', 'elessi-theme') : __('This coupon is already applied.', 'elessi-theme') ); ?>">

                        <span class="discount-info fs-16 nasa-flex nasa-fullwidth nasa-bold"><?php echo $discount_lbl; ?></span>
                        
                        <span class="discount-code nasa-flex nasa-fullwidth flex-wrap">
                            <span class="nasa-bold nasa-uppercase margin-right-10 rtl-margin-right-0 rtl-margin-left-10"><?php echo $code; ?></span>
                            <span class="discount-exp"><?php echo $date_expires_lbl; ?></span>
                        </span>
                        
                        <?php echo $desc ? '<span class="discount-desc nasa-flex nasa-fullwidth">' . $desc . '</span>' : ''; ?>
                        <?php echo $min_amout_lbl ? '<span class="discount-min nasa-flex nasa-fullwidth">' . $min_amout_lbl . '</span>' : ''; ?>
                        <?php echo $max_amout_lbl ? '<span class="discount-max nasa-flex nasa-fullwidth">' . $max_amout_lbl . '</span>' : ''; ?>
                    </a>
                <?php elseif ($style == 'style-2'):?>
                    <span class="publish-coupon-wrap">
                        <a href="javascript:void(0);" data-code="<?php echo esc_attr($code); ?>" class="publish-coupon fs-15 <?php echo esc_html($publish_coupon_class)?>" title="<?php echo esc_attr( $publish_coupon_class == '' ? __('Click here to Apply this coupon.', 'elessi-theme') : __('This coupon is already applied.', 'elessi-theme') ); ?>">
                            <div class="ns-upper-ticket">
                                <span class="discount-info nasa-bold fs-14"><?php echo $discount_lbl; ?></span>
                                <span class="discount-exp"><?php echo $date_expires_lbl; ?></span>
                                <span class="discount-desc" title="<?php echo $desc; ?>"><?php echo $desc; ?></span>
                                <?php echo $min_amout_lbl ? '<span class="discount-min nasa-flex nasa-fullwidth">' . $min_amout_lbl . '</span>' : ''; ?>
                                <?php echo $max_amout_lbl ? '<span class="discount-max nasa-flex nasa-fullwidth">' . $max_amout_lbl . '</span>' : ''; ?>
                            </div>
                            <span class="ns-cp-rip nasa-flex"><hr></span>
                            <span class="nasa-bold discount-code nasa-uppercase"><?php echo $code; ?></span>
                        </a>
                    </span>
                <?php endif?>

        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p class="node-title nasa-bold ns-coupon-ask fs-20 mobile-fs-18">
    <?php echo esc_html__('Have a coupon code?', 'elessi-theme'); ?>
</p>

<div class="coupon-btns nasa-flex flex-column">
    <input type="text" name="coupon_code" class="input-text nasa-uppercase nasa-bold" id="mini-cart-add-coupon_code" value="" />
    <button type="submit" class="button nasa-fullwidth margin-top-10" name="mini-cart-apply_coupon" value="<?php echo esc_attr__('Apply coupon', 'elessi-theme'); ?>" id="mini-cart-apply_coupon">
        <?php echo esc_html__('Apply', 'elessi-theme'); ?>
    </button>
</div>
