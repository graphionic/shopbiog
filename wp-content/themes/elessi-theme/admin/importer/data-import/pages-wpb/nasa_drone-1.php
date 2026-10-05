<?php
function nasa_wpb_drone_1() {
    $placeholder_src = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : 'https://dummyimage.com/1200x800?text=1200x800';

    $imgs_1 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/drone-background-slider.jpg', '3151', array(
        'post_title' => 'drone-background-slider',
        'post_name' => 'drone-background-slider',
    ));
    $imgs_1_src = $imgs_1 ? wp_get_attachment_image_url($imgs_1, 'full') : 'https://dummyimage.com/1510x713?text=1510x713';

    $imgs_2 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/drone-model-image-2.png', '3146', array(
        'post_title' => 'drone-model-image-2',
        'post_name' => 'drone-model-image-2',
    ));

    $imgs_3 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/drone-banner-1-2.jpg', '3142', array(
        'post_title' => 'drone-banner-1-2',
        'post_name' => 'drone-banner-1-2',
    ));

    $imgs_4 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/drone-banner-2-2.jpg', '3142', array(
        'post_title' => 'drone-banner-2-2',
        'post_name' => 'drone-banner-2-2',
    ));

    $imgs_5 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/drone-banner-3-2.jpg', '3142', array(
        'post_title' => 'drone-banner-3-2',
        'post_name' => 'drone-banner-3-2',
    ));

    $imgs_6 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/camera-model-image.png', '3149', array(
        'post_title' => 'camera-model-image',
        'post_name' => 'camera-model-image',
    ));

    $imgs_7 = elessi_import_upload('/elementor/wp-content//uploads/2024/10/placeholder.png', '3157', array(
        'post_title' => 'placeholder',
        'post_name' => 'placeholder',
    ));

    return array(
        'post' => array(
            'post_title' => 'WPB Drone V1',
            'post_name' => 'wpb-drone-v1',
            'post_content' => '[vc_section css=".vc_custom_1730428827291{padding-bottom: 30px !important;background-image: url(' . $imgs_1_src . '?id=' . $imgs_1 . ') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_row content_placement="middle" hide_in_mobile="1" el_class="ns-ovhd"][vc_column width="5/12"][vc_column_text css=""]
<div class="fs-40 tablet-fs-30 mobile-fs-30 nasa-bold" style="line-height: 1.2; color: #333333;">Camera Drones</div>
<div class="fs-20 nasa-bold" style="color: #979797; line-height: 1.4;">DJI MAVIC - 2 PRO</div>
<div class=" margin-top-30 fs-14 " style="color: #979797; line-height: 1.4;">STARTING AT</div>
<span class="fs-40 tablet-fs-30 mobile-fs-30 nasa-bold" style="position: relative;">$1.850</span>[/vc_column_text][/vc_column][vc_column width="7/12" offset="vc_hidden-sm vc_hidden-xs" el_class="margin-top-20 margin-bottom-20"][nasa_image image="' . $imgs_2 . '"][/vc_column][/vc_row][vc_row el_class="mobile-padding-top-20"][vc_column][nasa_products title_dash_remove="0" type="recent_product" style="carousel" arrows="0" dots="true" number="7" columns_number="6" columns_number_tablet="3" cat="" el_class="ns-h-drone-product-slide"][/vc_column][/vc_row][/vc_section][vc_row el_class="margin-top-30 mobile-margin-top-10"][vc_column width="1/3"][nasa_banner_2 valign="middle" hover="zoom" img_src="' . $imgs_3 . '" el_class="ns-ovhd nasa-radius-10"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2; color: #333333;">Remote
Controller <span class="none-weight" style="color: #979797; line-height: 1.4;">Drone</span></div>
<div class="fs-20 tablet-fs-20 mobile-fs-20 margin-top-20 none-weight nasa-flex" style="line-height: 1.2; color: #333333; gap: 5px;">Sale off<span class="nasa-bold fs-35 primary-color">30%</span></div>
<a class="button margin-top-15 fs-16 force-radius-5 hide-for-medium" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 valign="middle" hover="zoom" img_src="' . $imgs_4 . '" el_class="ns-ovhd nasa-radius-10"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2; color: #333333;">2024 Global
Latest Release</div>
<a class="button margin-top-20 fs-16 force-radius-5 hide-for-medium" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 valign="middle" hover="zoom" img_src="' . $imgs_5 . '" el_class="ns-ovhd nasa-radius-10"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2; color: #333333;"><span class="none-weight" style="color: #979797; line-height: 1.4;">Drone</span>
Phone Holder</div>
<div class="fs-18 margin-top-20 none-weight" style="line-height: 1.2; color: #333333; gap: 5px;">Weekend Discount
<span class="fs-30 margin-top-5 primary-color nasa-bold">$69.99</span></div>
<a class="button margin-top-10 fs-16 force-radius-5 hide-for-medium" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/nasa_banner_2][/vc_column][/vc_row][vc_row el_class="bgw margin-bottom-20" css=".vc_custom_1728464009184{margin-top: 30px !important;}"][vc_column][vc_tta_tabs title_tag="h3" alignment="right" title_font_size="l" tabs_display_type="2d-has-bg" tabs_bg_color="" title="Featured drones" el_class="letter-spacing-none nasa-tabs-bg-transparent"][vc_tta_section title="Featured" tab_id="1728461352326-1a0883ba-aa06"][nasa_products type="recent_product" number="12" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="Top Rated" tab_id="1728461497407-41f4602d-e8ea"][nasa_products number="12" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="Best Selling" tab_id="1730367499600-3f9e5a7a-1c9f"][nasa_products type="recent_product" number="12" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="New" tab_id="1728461352315-a3facae5-2e23"][nasa_products number="12" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][/vc_tta_tabs][/vc_column][/vc_row][vc_row css=".vc_custom_1728618640619{margin-top: 30px !important;padding-top: 40px !important;padding-bottom: 50px !important;background-image: url(' .$imgs_1_src . '?id=' . $imgs_1 . ') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column width="1/2" el_class="desktop-padding-top-50"][vc_row_inner content_placement="middle"][vc_column_inner el_class="desktop-padding-top-20" width="2/5" offset="vc_col-xs-6"][vc_column_text css=""]
<div class="fs-40 tablet-fs-30 mobile-fs-30 nasa-bold" style="line-height: 1.2; color: #333333;">Insta360 GO 3S</div>
<div class="fs-14 nasa-bold" style="color: #979797; line-height: 1.4;">DJI MAVIC - 2 PRO</div>
<div class=" margin-top-30 fs-14 " style="color: #979797; line-height: 1.4;">STARTING AT</div>
<span class="fs-40 tablet-fs-30 mobile-fs-30 nasa-bold" style="position: relative;">$1.750</span>[/vc_column_text][/vc_column_inner][vc_column_inner width="3/5" offset="vc_col-xs-6"][nasa_image image="' . $imgs_6 . '" align="center"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/2" el_class="mobile-padding-top-20"][nasa_products title_shortcode="Top Selling Products" title_dash_remove="0" type="recent_product" style="carousel" arrows="1" columns_number="3" columns_number_tablet="2" cat="" el_class="ns-h-drone-product-slide"][/vc_column][/vc_row][vc_row el_class="bgw margin-bottom-20" css=".vc_custom_1728465514197{margin-top: 50px !important;}"][vc_column][vc_tta_tabs title_tag="h3" alignment="right" title_font_size="l" tabs_display_type="2d-has-bg" tabs_bg_color="" title="Featured drones" el_class="letter-spacing-none nasa-tabs-bg-transparent"][vc_tta_section title="Featured" tab_id="1728461352326-1a0883ba-aa06"][nasa_products title_dash_remove="0" type="recent_product" style="carousel" pos_nav="both" arrows="1" number="7" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="Top Rated" tab_id="1728461497407-41f4602d-e8ea"][nasa_products title_dash_remove="0" style="carousel" pos_nav="both" arrows="1" number="7" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="Best Selling" tab_id="1730367378482-f5587e64-d255"][nasa_products title_dash_remove="0" type="recent_product" style="carousel" pos_nav="both" arrows="1" number="7" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="New" tab_id="1728461352315-a3facae5-2e23"][nasa_products title_dash_remove="0" style="carousel" pos_nav="both" arrows="1" number="7" columns_number="6" columns_number_tablet="3" cat=""][/vc_tta_section][/vc_tta_tabs][/vc_column][/vc_row][vc_row hide_in_mobile="1" el_class="margin-top-50"][vc_column css=".vc_custom_1730369208236{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_row_inner content_placement="middle" css=".vc_custom_1730369192247{margin-right: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-bottom: 0px !important;background-color: #F3F6FD !important;}"][vc_column_inner el_class="jc" width="7/12"][vc_column_text css=""]
<div class="text-center fs-20 nasa-bold tablet-fs-20 mobile-fs-20" style="line-height: 1.2; color: #333333; text-align: center;">Healthy Saving Days <span style="color: #979797; line-height: 1.4;">Up to 80% Off on Health Monitors &amp; Devices</span></div>
[/vc_column_text][/vc_column_inner][vc_column_inner el_class="jc" width="1/6" offset="vc_col-xs-2"][vc_single_image image="' . $imgs_6 . '" css=".vc_custom_1730434206273{margin-top: -37px !important;margin-bottom: -23px !important;}" el_class="hide-for-small"][/vc_column_inner][vc_column_inner el_class="jc mobile-margin-top-20" width="1/4" offset="vc_col-xs-12"][vc_column_text css=""]<a class="button fs-16 force-radius-5" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row css=".vc_custom_1728632343697{margin-top: 60px !important;}"][vc_column][nasa_title title_text="Video From Your Client" font_size="l"][vc_row_inner css=".vc_custom_1728468640510{padding-top: 20px !important;}"][vc_column_inner el_class="mobile-margin-bottom-10" width="1/3"][vc_video link="https://www.youtube.com/watch?v=kcfs1-ryKWE" css=""][/vc_column_inner][vc_column_inner el_class="mobile-margin-bottom-10" width="1/3"][vc_video link="https://www.youtube.com/watch?v=W0PrnBQs_W4" css=""][/vc_column_inner][vc_column_inner width="1/3"][vc_video link="https://www.youtube.com/watch?v=C6cgcpfKt3g" css=""][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="padding-top-20 margin-bottom-40 mobile-padding-top-20 mobile-margin-bottom-0"][vc_column][nasa_brands images="' . elessi_imp_brands_str() . '" columns_number="7" columns_number_tablet="4" columns_number_small="3" is_ajax="no" custom_links="#,#,#,#,#,#,#"][/vc_column][/vc_row]'
        ),
        
        'post_meta' => array(
            // '_nasa_header_block' => 'static-header-1',
            // '_nasa_el_class_header' => 'main-home-fix',
            '_wpb_shortcodes_custom_css' => '.vc_custom_1728464009184{margin-top: 30px !important;}.vc_custom_1728465514197{margin-top: 50px !important;}.vc_custom_1728632343697{margin-top: 60px !important;}.vc_custom_1730369208236{padding-top: 0px !important;padding-bottom: 0px !important;}.vc_custom_1730369192247{margin-right: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-bottom: 0px !important;background-color: #F3F6FD !important;}.vc_custom_1730434206273{margin-top: -37px !important;margin-bottom: -23px !important;}.vc_custom_1728468640510{padding-top: 20px !important;}'
        ),
        
        'globals' => array(
            'header-type' => '4',
            'plus_wide_width' => '200',
            'color_primary' => '#0084ff',
            'v_root' => '1',
            'v_root_limit' => '8',
            'loop_layout_buttons' => 'hoz-buttons',

            'footer_mode' => 'builder',
            'footer-type' => 'wpb-footer-drone-camera',
            'header-block-type_4' => 'wpb-drone-block-beside-header',
            'nasa_popup_static_block' => 'wpb-drone-popup-block',
        ),
    );
}
