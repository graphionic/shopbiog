<?php
function nasa_wpb_exit_intent_popup() {
    $imgs_url = elessi_import_upload('/elementor/wp-content/uploads/2024/03/pop-up-intent.jpg', '3094', array(
        'post_title' => 'exit-popup-intent',
        'post_name' => 'Exit popup intent',
    ));
    $imgs_url_src = $imgs_url ? wp_get_attachment_image_url($imgs_url, 'full') : 'https://dummyimage.com/500x500?text=500x500';
    
    return array(
        'post' => array(
            'post_title' => 'WPB Exit Intent Popup',
            'post_name' => 'wpb-exit-intent-popup',
            'post_content' => '[vc_row equal_height="yes"][vc_column width="1/2" css=".vc_custom_1730867460112{background-image: url('.$imgs_url_src.'?id='.$imgs_url.') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}" el_class="nasa-flex flex-column jc"][vc_column_text css=""] <div class="fs-30 tablet-fs-25 mobile-fs-25 margin-top-10 nasa-bold" style="line-height: 1.2; color: #ffffff; text-align: center;">Wait! before you leave...</div> <div class="fs-16 mobile-fs-14 margin-top-30" style="color: #ffffff; line-height: 1.4; text-align: center;">Get 30% off for your first order</div> <p style="text-align: center;"><a class="button margin-top-30 nasa-tip nasa-tip-right nasa-copy-to-clipboard outline-white small fs-16" style="text-transform: capitalize; background-color: #fff; border-color: #fff; color: #000; height: 40px;" tabindex="0"><span class="ns-copy" title="Copy to clipboard sussces">CODE30OFF</span><span class="nasa-tip-content" style="letter-spacing: 0;">Copy to clipboard</span></a></p> <div class="margin-top-30 fs-16 mobile-fs-14" style="color: #ffffff; line-height: 1.4; text-align: center;">Use above code to get 30% off for your first order when checkout</div> [/vc_column_text][vc_column_text css="" el_class="nasa-bold margin-top-30 margin-bottom-30"] <p style="text-align: center;"><a class="button outline-white small fs-16 force-radius-20" style="height: 40px; border-color: #fff; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff; margin-top: 10px; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now <span class="fs-20 margin-left-5">→</span></a></p> [/vc_column_text][/vc_column][vc_column width="1/2" el_class="padding-left-30 padding-right-30 padding-top-30 padding-bottom-30"][vc_column_text css=".vc_custom_1730866930155{padding-bottom: 15px !important;}"] <h4><span style="font-size: 100%;">Recommended Products</span></h4> [/vc_column_text][nasa_products style="list" number="3" columns_number="1" columns_number_tablet="1" columns_number_small="1"][/vc_column][/vc_row]'
        ),
        'post_meta' => array(
            '_wpb_shortcodes_custom_css' => '.vc_custom_1730867460112{background-image: url('.$imgs_url_src.'?id='.$imgs_url.') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}.vc_custom_1730866930155{padding-bottom: 15px !important;}'
        )
    );
}
