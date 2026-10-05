<?php
function nasa_wpb_footer_drone_camera() {
    $imgs_1 = elessi_import_upload('/elementor/wp-content/uploads/2024/10/googleplay-appstore-logo.png', '3319', array(
        'post_title' => 'googleplay-appstore-logo',
        'post_name' => 'googleplay-appstore-logo',
    ));

    $imgs_2 = elessi_import_upload('/elementor/wp-content/uploads/2024/10/checkout-support-logo.png', '1698', array(
        'post_title' => 'checkout-support-logo',
        'post_name' => 'checkout-support-logo',
    ));
    
    return array(
        'post' => array(
            'post_title' => 'WPB Footer Drone & Camera',
            'post_name' => 'wpb-footer-drone-camera',
            'post_content' => '[vc_row equal_height="yes" css=".vc_custom_1732265390054{background-color: #FAFAFA !important;}" el_class="padding-top-40 padding-bottom-40"][vc_column][nasa_image alt="Elessi Logo" link_text="#" image="1703" el_class="skip-lazy"][vc_row_inner css=".vc_custom_1730432672900{margin-top: 20px !important;}"][vc_column_inner width="1/4"][nasa_contact_us title="NasaTheme." contact_address="Calista Wise 7292 Dictum Av.
Antonio, Italy." contact_phone="(+01)-800-3456-88" contact_email="your-email@yourdomain.com" contact_website="yourdomain.com" class="desktop-padding-right-20"][nasa_follow twitter="#" facebook="#" email="#" instagram="#"][/vc_column_inner][vc_column_inner el_class="mobile-margin-bottom-30" width="1/4"][nasa_menu menu="menu-footer-drone-camera" el_class="ns-footer-drone margin-top-0"][/vc_column_inner][vc_column_inner width="1/4"][nasa_menu menu="information" el_class="ns-footer-drone margin-top-0"][/vc_column_inner][vc_column_inner width="1/4" css=".vc_custom_1728640854712{padding-top: 10px !important;padding-bottom: 10px !important;}"][vc_column_text css=""]
<div class="nasa-bold fs-20">Dowload App on Mobile</div>
<div class="margin-top-20">15% discount on your first purchase</div>
[/vc_column_text][nasa_image link_text="#" image="' . $imgs_1 . '" el_class="skip-lazy margin-top-20 max-w250"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="padding-top-25 padding-bottom-25" css=".vc_custom_1734341936115 {border-top-width: 1px !important;border-top-style: solid !important;border-color: #EFEFEF !important;}"][vc_column width="1/2"][vc_column_text css=""]© ' . date('Y') . ' - All Right reserved![/vc_column_text][/vc_column][vc_column width="1/2"][nasa_image alt="Visa Trust" link_text="#" image="' . $imgs_2 . '" align="right" hide_in_m="1"][/vc_column][/vc_row]'
        ),
        'post_meta' => array(
            '_wpb_shortcodes_custom_css' => '.vc_custom_1732265390054{background-color: #FAFAFA !important;}.vc_custom_1734341936115 {border-top-width: 1px !important;border-top-style: solid !important;border-color: #EFEFEF !important;}.vc_custom_1730432672900{margin-top: 20px !important;}.vc_custom_1728640854712{padding-top: 10px !important;padding-bottom: 10px !important;}'
        )
    );
}
