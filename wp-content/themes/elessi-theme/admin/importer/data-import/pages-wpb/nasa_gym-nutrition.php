<?php
function nasa_wpb_gym_nutrition() {
    $placeholder_src = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : 'https://dummyimage.com/1200x800?text=1200x800';

    $imgs_1 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nuitrition-banner-1.jpg', '3105',array(
        'post_title' => 'gym-nutrition-banner-1',
        'post_name' => 'gym-nutrition-banner-1',
    ));
    $imgs_1_src = $imgs_1 ? wp_get_attachment_image_url($imgs_1, 'full') : 'https://dummyimage.com/688x491?text=688x491';

    $imgs_2 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-7.jpg', '3105',array(
        'post_title' => 'gym-nutrition-banner-7',
        'post_name' => 'gym-nutrition-banner-7',
    ));
    $imgs_2_src = $imgs_2 ? wp_get_attachment_image_url($imgs_2, 'full') : 'https://dummyimage.com/688x491?text=688x491';

    $imgs_3 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-8.jpg', '3105',array(
        'post_title' => 'gym-nutrition-banner-8',
        'post_name' => 'gym-nutrition-banner-8',
    ));
    $imgs_3_src = $imgs_3 ? wp_get_attachment_image_url($imgs_3, 'full') : 'https://dummyimage.com/688x491?text=688x491';

    $imgs_4 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-2.jpg', '3136',array(
        'post_title' => 'gym-nutrition-banner-2',
        'post_name' => 'gym-nutrition-banner-2',
    ));
    $imgs_4_src = $imgs_4 ? wp_get_attachment_image_url($imgs_4, 'full') : 'https://dummyimage.com/327x209?text=327x209';

    $imgs_5 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-3.jpg', '3136',array(
        'post_title' => 'gym-nutrition-banner-3',
        'post_name' => 'gym-nutrition-banner-3',
    ));
    $imgs_5_src = $imgs_5 ? wp_get_attachment_image_url($imgs_5, 'full') : 'https://dummyimage.com/327x209?text=327x209';

    $imgs_6 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-shop-by-goal-improve-strength.jpg', '3144',array(
        'post_title' => 'gym-shop-by-goal-improve-strength',
        'post_name' => 'gym-shop-by-goal-improve-strength',
    ));
    $imgs_6_src = $imgs_6 ? wp_get_attachment_image_url($imgs_6, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_7 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-shop-by-goal-diet-loss-weight.jpg', '3144',array(
        'post_title' => 'gym-shop-by-goal-diet-loss-weight',
        'post_name' => 'gym-shop-by-goal-diet-loss-weight',
    ));
    $imgs_7_src = $imgs_7 ? wp_get_attachment_image_url($imgs_7, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_8 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-shop-by-goal-recover-rehab.jpg', '3144',array(
        'post_title' => 'gym-shop-by-goal-recover-rehab',
        'post_name' => 'gym-shop-by-goal-recover-rehab',
    ));
    $imgs_8_src = $imgs_8 ? wp_get_attachment_image_url($imgs_8, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_9 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-shop-by-goal-improve-for-sport.jpg', '3144',array(
        'post_title' => 'gym-shop-by-goal-improve-for-sport',
        'post_name' => 'gym-shop-by-goal-improve-for-sport',
    ));
    $imgs_9_src = $imgs_9 ? wp_get_attachment_image_url($imgs_9, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_10 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-shop-by-goal-calisthenics.jpg', '3144',array(
        'post_title' => 'gym-nutrition-shop-by-goal-calisthenics',
        'post_name' => 'gym-nutrition-shop-by-goal-calisthenics',
    ));
    $imgs_10_src = $imgs_10 ? wp_get_attachment_image_url($imgs_10, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_11 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-shop-by-goal-build-muscle.jpg', '3144',array(
        'post_title' => 'gym-shop-by-goal-build-muscle',
        'post_name' => 'gym-shop-by-goal-build-muscle',
    ));
    $imgs_11_src = $imgs_11 ? wp_get_attachment_image_url($imgs_11, 'full') : 'https://dummyimage.com/256x330?text=256x330';

    $imgs_12 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-4.jpg', '3139',array(
        'post_title' => 'gym-nutrition-banner-4',
        'post_name' => 'gym-nutrition-banner-4',
    ));
    $imgs_12_src = $imgs_12 ? wp_get_attachment_image_url($imgs_12, 'full') : 'https://dummyimage.com/477x266?text=477x266';

    $imgs_13 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-5.jpg', '3139',array(
        'post_title' => 'gym-nutrition-banner-5',
        'post_name' => 'gym-nutrition-banner-5',
    ));
    $imgs_13_src = $imgs_13 ? wp_get_attachment_image_url($imgs_13, 'full') : 'https://dummyimage.com/477x266?text=477x266';

    $imgs_14 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-banner-6.jpg', '3139',array(
        'post_title' => 'gym-nutrition-banner-6',
        'post_name' => 'gym-nutrition-banner-6',
    ));
    $imgs_14_src = $imgs_14 ? wp_get_attachment_image_url($imgs_14, 'full') : 'https://dummyimage.com/477x266?text=477x266';

    $brand_1 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-6.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-6',
        'post_name' => 'gym-nutrition-brand-6',
    ));
    $brand_2 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-5.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-5',
        'post_name' => 'gym-nutrition-brand-5',
    ));
    $brand_3 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-4.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-4',
        'post_name' => 'gym-nutrition-brand-4',
    ));
    $brand_4 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-3.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-3',
        'post_name' => 'gym-nutrition-brand-3',
    ));
    $brand_5 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-2.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-2',
        'post_name' => 'gym-nutrition-brand-2',
    ));
    $brand_6 = elessi_import_upload('/elementor/wp-content/uploads/2025/01/gym-nutrition-brand-1.png', '3074',array(
        'post_title' => 'gym-nutrition-brand-1',
        'post_name' => 'gym-nutrition-brand-1',
    ));

    return array(
        'post' => array(
            'post_title' => 'WPB Gym Nutrition',
            'post_name' => 'wpb-gym-nutrition',
            'post_content' => '[vc_row equal_height="yes" el_class="nasa-row-nowrap" css=".vc_custom_1737013887693{padding-top: 10px !important;}"][vc_column width="1/4" el_class="nutrition-ver-menu hide-for-mobile hide-for-medium" css=".vc_custom_1737013794871{padding-top: 10px !important;padding-bottom: 10px !important;}"][vc_raw_html css=""]JTNDZGl2JTIwc3R5bGUlM0QlMjJtaW4taGVpZ2h0JTNBJTIwNDkycHglM0IlMjIlM0UlM0MlMkZkaXYlM0U=[/vc_raw_html][/vc_column][vc_column width="2/4" el_class="nasa-flex-1 medium-6 nasa-nutrition-small-banner-wrap" css=".vc_custom_1737013767300{padding-top: 10px !important;padding-bottom: 10px !important;}"][nasa_slider column_number="1" column_number_small="1" column_number_tablet="1" el_class="nasa-bullets-inside nasa-bullets-left nasa-over-hide force-radius-10 bulet-margin-l55"][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_1 . '"]
<p class="fs-14 nasa-bold-500 margin-bottom-0" style="color: #fff;">MUSCLEPHARM GYM COMBO</p>

<h2 class="mobile-fs-20" style="color: #fff; line-height: 1.1; font-weight: 700 !important; font-size: 45px;">POWDER
FOR
<span style="color: #8dfa64;">POWER</span></h2>
<span class="fs-17 nasa-bold-500 hide-for-mobile" style="color: #fff;">Boost energy &amp; strength</span>
<button class="margin-top-20 mobile-margin-top-0" style="line-height: 1;" tabindex="0">Shop now</button>[/nasa_banner_2][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_2 . '"]
<p class="fs-14 nasa-bold-500 margin-bottom-0" style="color: #fff;">VITAMIN GYM PRODUCTS</p>

<h2 class="mobile-fs-20" style="color: #fff; line-height: 1.1; font-weight: 700 !important; font-size: 45px;">GAIN
HEATH
<span style="color: #8dfa64;">MUSCLE</span></h2>
<span class="fs-17 nasa-bold-500 hide-for-mobile" style="color: #fff;">Vitamin supplements</span>
<button class="margin-top-20 mobile-margin-top-0" style="line-height: 1;" tabindex="0">Shop now</button>[/nasa_banner_2][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_3 . '"]
<p class="fs-14 nasa-bold-500 margin-bottom-0" style="color: #fff;">SUMMON LABS SERIES</p>

<h2 class="mobile-fs-20" style="color: #fff; line-height: 1.1; font-weight: 700 !important; font-size: 45px;">SUMMON
YOUR
<span style="color: #8dfa64;">MUSCLE</span></h2>
<span class="fs-17 nasa-bold-500 hide-for-mobile" style="color: #fff;">Great for gymers &amp; athletes
</span><button class="margin-top-20 mobile-margin-top-0" style="line-height: 1;" tabindex="0">Shop now</button>

[/nasa_banner_2][/nasa_slider][/vc_column][vc_column width="1/4" el_class="nasa-nutrition-small-banner-wrap hide-for-small nasa-flex align-stretch flex-wrap medium-4" css=".vc_custom_1737013779369{padding-top: 10px !important;padding-bottom: 10px !important;}"][nasa_banner_2 effect_text="zoomIn" hover="zoom" img_src="' . $imgs_4 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-20 nasa-height-auto"]
<h4 class="" style="margin-bottom: 10px; font-weight: 700 !important; color: #fff; line-height: 1.1;">NEW
<span style="color: #8dfa64;">ARRIVAL</span></h4>
<a class="nasa-bold fs-14" style="text-decoration: underline; color: #fff;" title="Shop Now" href="#">Shop now</a>[/nasa_banner_2][nasa_banner_2 effect_text="zoomIn" hover="zoom" img_src="' . $imgs_5 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0 nasa-height-auto"]
<h4 class="" style="margin-bottom: 10px; font-weight: 700 !important; color: #fff; line-height: 1.2;">BIG
SAVINGS</h4>
<a class="nasa-bold fs-14" style="text-decoration: underline; color: #fff;" title="Shop Now" href="#">Shop now</a>[/nasa_banner_2][/vc_column][/vc_row][vc_row equal_height="yes" css=".vc_custom_1737363786397{margin-top: 50px !important;margin-bottom: 50px !important;}" el_class="ns-ovhd"][vc_column width="1/6" el_class="nutrition-ver-menu margin-bottom-20 nasa-flex"][vc_column_text css=""]
<h4 class="fs-40 padding-right-10 margin-bottom-20 rtl-padding-right-0 rtl-padding-left-10" style="font-weight: 800 !important; line-height: 1.1; letter-spacing: 0;"><span class="primary-color">SEASON</span> COLLECTION</h4>
<p class="fs-18">Must-have pieces selected every month</p>
[/vc_column_text][/vc_column][vc_column width="5/6" el_class="nutrition-ver-menu-max"][nasa_product_categories list_cats="" columns_number="6" columns_number_small="2" columns_number_tablet="4" el_class="items-padding-20px"][/vc_column][/vc_row][vc_row][vc_column][vc_column_text css="" el_class="margin-bottom-30"]
<h4 class="fs-40" style="margin: 0px; font-weight: 800 !important; line-height: 1.4; letter-spacing: 0;"><span style="color: #c1c1c1;">SHOP BY</span> GOALS</h4>
[/vc_column_text][nasa_slider bullets="false" column_number="5" column_number_small="2" column_number_tablet="3" gap_items="yes" effect_silde_dismis_reload="true" el_class="nasa-nutrition-shop-by-goals"][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_6 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">BUILD MUSCLE</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Gain size and power</span>
<a class="button margin-top-5 outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_7 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">IMPROVE STRENGTH</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Lift heavier every day</span>
<a class="margin-top-5 button outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_8 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">DIET &amp; LOSS WEIGHT</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Shed fat, stay fit</span>
<a class="margin-top-5 button outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_9 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">RECOVER &amp; REHAB</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Heal faster, move better</span>
<a class="button margin-top-5 outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_10 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">IMPROVE FOR SPORT</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Dominate your game</span>
<a class="button margin-top-5 outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" valign="bottom" text_align="text-center" effect_text="fadeInUp" img_src="' . $imgs_11 . '" el_class="nasa-nutrition-banner"]
<div class="fs-25" style="line-height: 1.2; font-weight: 700 !important; color: #fff;">UNLOCK STRENGTH</div>
<p class="nasa-baner-show-up"><span class="fs-18" style="line-height: 1.2; color: #fff;">Master your body weight</span>
<a class="button margin-top-5 outline-white ns-baner_btn_zoom fs-13 force-radius-20" style="height: 35px; text-transform: capitalize; letter-spacing: 0; background: transparent; color: #fff !important; padding: 15px;" tabindex="0" title="Shop now" href="#">Shop Now</a></p>
[/nasa_banner_2][/nasa_slider][/vc_column][/vc_row][vc_row css=".vc_custom_1736506216288{margin-top: 50px !important;margin-bottom: 50px !important;}"][vc_column][vc_tta_tabs el_class="nasa-nutrition-product-tabs"][vc_tta_section title="NEW ARRIVALS" tab_id="1736506221815-dbe4eb2a-9ff5"][nasa_products columns_number="4" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="BEST SELLERS" tab_id="1736506221825-b9902de8-5977"][nasa_products columns_number="4" columns_number_tablet="3" cat=""][/vc_tta_section][vc_tta_section title="ON SALES" tab_id="1736506265705-54a76683-10a6"][nasa_products columns_number="4" columns_number_tablet="3" cat=""][/vc_tta_section][/vc_tta_tabs][/vc_column][/vc_row][vc_row][vc_column width="1/3"][nasa_banner_2 align="right" valign="middle" hover="zoom" img_src="' . $imgs_12 . '" el_class="nasa-over-hide force-radius-10"]
<p class="fs-18 margin-bottom-5 tablet-margin-bottom-0" style="color: #fff;">ON SALE</p>

<div class="fs-30 tablet-fs-20" style="color: #fff; line-height: 1.2; font-weight: 700 !important;">UNLOOK
FULL
POTENTIAL</div>
<button class="margin-top-20 padding-left-15 tablet-margin-top-5 padding-right-15" style="height: auto; padding: 10px; line-height: 1;" tabindex="0">Shop now</button>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 align="right" valign="middle" hover="zoom" img_src="' . $imgs_13 . '" el_class="nasa-over-hide force-radius-10"]
<p class="fs-18 margin-bottom-5 tablet-margin-bottom-0" style="color: #fff;">ON SALE</p>

<div class="fs-30 tablet-fs-20" style="color: #fff; line-height: 1.2; font-weight: 700 !important;">MUSCLE
AND
BALANCE</div>
<button class="margin-top-20 padding-left-15 tablet-margin-top-5 padding-right-15" style="height: auto; padding: 10px; line-height: 1;" tabindex="0">Shop now</button>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 align="right" valign="middle" hover="zoom" img_src="' . $imgs_14 . '" el_class="nasa-over-hide force-radius-10"]
<p class="fs-18 margin-bottom-5 tablet-margin-bottom-0" style="color: #fff;">ON SALE</p>

<div class=" fs-30 tablet-fs-20" style="color: #fff; line-height: 1.2; font-weight: 700 !important;">WHEY
PROTEIN
POWER</div>
<button class="margin-top-20 padding-left-15 tablet-margin-top-5 padding-right-15" style="height: auto; padding: 10px; line-height: 1;" tabindex="0">Shop now</button>[/nasa_banner_2][/vc_column][/vc_row][vc_row css=".vc_custom_1736506903023{margin-top: 50px !important;margin-bottom: 50px !important;}"][vc_column][nasa_pin_products_banner pin_slug="pin-banner-580x530" marker_style="plus" slide_pin_product="yes" el_class="nasa-nutrition-pin-banner"]<span class="fs-40" style="font-weight: 800 !important; color: #c1c1c1; margin: 0; letter-spacing: 0;">SHOP THIS <span style="color: #000;">LOOK</span></span>[/nasa_pin_products_banner][/vc_column][/vc_row][vc_row fullwidth="1"][vc_column][vc_raw_html css=""]JTNDc3R5bGUlM0UlMEQlMEElMjAlMjAlMjAlMjAubmFzYS1jdXN0b20tYW5pbWF0ZSUyMHAlMjAlN0IlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjBmb250LXdlaWdodCUzQSUyMDEwMDAlMjAlMjFpbXBvcnRhbnQlM0IlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjBmb250LWZhbWlseSUzQSUyMCUyMlNvZmlhJTIwU2FucyUyMENvbmRlbnNlZCUyMiUyMCUyMWltcG9ydGFudCUzQiUwRCUwQSUyMCUyMCUyMCUyMCU3RCUwRCUwQSUwRCUwQSUyMCUyMCUyMCUyMC5mcy01MCUyMCU3QiUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMGZvbnQtc2l6ZSUzQSUyMDUwcHglMjAlMjFpbXBvcnRhbnQlM0IlMEQlMEElMjAlMjAlMjAlMjAlN0QlMEQlMEElMEQlMEElMjAlMjAlMjAlMjAubmFzYS1jdXN0b20tYW5pbWF0ZSUyMHAlM0Fub3QlMjgubmFzYS1uby1ib2RlciUyOSU3QiUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMC13ZWJraXQtdGV4dC1zdHJva2UlM0ElMjAxcHglMjAlMjNjMWMxYzElM0IlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjBjb2xvciUzQSUyMHRyYW5zcGFyZW50JTNCJTBEJTBBJTBEJTBBJTIwJTIwJTIwJTIwJTdEJTBEJTBBJTNDJTJGc3R5bGUlM0UlMEQlMEElM0NkaXYlMjBjbGFzcyUzRCUyMm5hc2EtY3VzdG9tLWFuaW1hdGUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlM0NkaXYlMjBzdHlsZSUzRCUyMmdhcCUzQSUyMDMwcHglM0IlMjIlMjBjbGFzcyUzRCUyMm5hc2EtZmxleCUyMG5hc2EtYm9sZC01MDAlMjBpbmZpbml0aWVzLXNsaWRlJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIwbmFzYS1uby1ib2RlciUyMiUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIyJTIwJTNFSk9JTiUyMFdJVEglMjBVUyUzQyUyRnAlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NzdmclMjB4bWxucyUzRCUyMmh0dHAlM0ElMkYlMkZ3d3cudzMub3JnJTJGMjAwMCUyRnN2ZyUyMiUyMHdpZHRoJTNEJTIyMzIlMjIlMjBoZWlnaHQlM0QlMjIzMyUyMiUyMGNsYXNzJTNEJTIycHJpbWFyeS1jb2xvciUyMiUyMHZpZXdCb3glM0QlMjIwJTIwMCUyMDMyJTIwMzMlMjIlMjBmaWxsJTNEJTIybm9uZSUyMiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3BhdGglMjBkJTNEJTIyTTE2JTIwMC41TDE4LjMzODglMjAxMC44NTM4TDI3LjMxMzclMjA1LjE4NjI5TDIxLjY0NjIlMjAxNC4xNjEyTDMyJTIwMTYuNUwyMS42NDYyJTIwMTguODM4OEwyNy4zMTM3JTIwMjcuODEzN0wxOC4zMzg4JTIwMjIuMTQ2MkwxNiUyMDMyLjVMMTMuNjYxMiUyMDIyLjE0NjJMNC42ODYyOSUyMDI3LjgxMzdMMTAuMzUzOCUyMDE4LjgzODhMMCUyMDE2LjVMMTAuMzUzOCUyMDE0LjE2MTJMNC42ODYyOSUyMDUuMTg2MjlMMTMuNjYxMiUyMDEwLjg1MzhMMTYlMjAwLjVaJTIyJTIwZmlsbCUzRCUyMmN1cnJlbnRDb2xvciUyMiUyRiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQyUyRnN2ZyUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3AlMjBjbGFzcyUzRCUyMm1hcmdpbi1ib3R0b20tMCUyMHRleHQtY2VudGVyJTIwbmFzYS1ib2xkJTIwZnMtNTAlMjBuYXNhLW5vLWJvZGVyJTIyJTIwJTNFSk9JTiUyMFdJVEglMjBVUyUzQyUyRnAlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NzdmclMjB4bWxucyUzRCUyMmh0dHAlM0ElMkYlMkZ3d3cudzMub3JnJTJGMjAwMCUyRnN2ZyUyMiUyMHdpZHRoJTNEJTIyMzIlMjIlMjBoZWlnaHQlM0QlMjIzMyUyMiUyMGNsYXNzJTNEJTIycHJpbWFyeS1jb2xvciUyMiUyMHZpZXdCb3glM0QlMjIwJTIwMCUyMDMyJTIwMzMlMjIlMjBmaWxsJTNEJTIybm9uZSUyMiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3BhdGglMjBkJTNEJTIyTTE2JTIwMC41TDE4LjMzODglMjAxMC44NTM4TDI3LjMxMzclMjA1LjE4NjI5TDIxLjY0NjIlMjAxNC4xNjEyTDMyJTIwMTYuNUwyMS42NDYyJTIwMTguODM4OEwyNy4zMTM3JTIwMjcuODEzN0wxOC4zMzg4JTIwMjIuMTQ2MkwxNiUyMDMyLjVMMTMuNjYxMiUyMDIyLjE0NjJMNC42ODYyOSUyMDI3LjgxMzdMMTAuMzUzOCUyMDE4LjgzODhMMCUyMDE2LjVMMTAuMzUzOCUyMDE0LjE2MTJMNC42ODYyOSUyMDUuMTg2MjlMMTMuNjYxMiUyMDEwLjg1MzhMMTYlMjAwLjVaJTIyJTIwZmlsbCUzRCUyMmN1cnJlbnRDb2xvciUyMiUyRiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQyUyRnN2ZyUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3AlMjBjbGFzcyUzRCUyMm1hcmdpbi1ib3R0b20tMCUyMHRleHQtY2VudGVyJTIwbmFzYS1ib2xkJTIwZnMtNTAlMjIlMjAlM0VKT0lOJTIwV0lUSCUyMFVTJTNDJTJGcCUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3N2ZyUyMHhtbG5zJTNEJTIyaHR0cCUzQSUyRiUyRnd3dy53My5vcmclMkYyMDAwJTJGc3ZnJTIyJTIwd2lkdGglM0QlMjIzMiUyMiUyMGhlaWdodCUzRCUyMjMzJTIyJTIwY2xhc3MlM0QlMjJwcmltYXJ5LWNvbG9yJTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMyUyMiUyMGZpbGwlM0QlMjJub25lJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcGF0aCUyMGQlM0QlMjJNMTYlMjAwLjVMMTguMzM4OCUyMDEwLjg1MzhMMjcuMzEzNyUyMDUuMTg2MjlMMjEuNjQ2MiUyMDE0LjE2MTJMMzIlMjAxNi41TDIxLjY0NjIlMjAxOC44Mzg4TDI3LjMxMzclMjAyNy44MTM3TDE4LjMzODglMjAyMi4xNDYyTDE2JTIwMzIuNUwxMy42NjEyJTIwMjIuMTQ2Mkw0LjY4NjI5JTIwMjcuODEzN0wxMC4zNTM4JTIwMTguODM4OEwwJTIwMTYuNUwxMC4zNTM4JTIwMTQuMTYxMkw0LjY4NjI5JTIwNS4xODYyOUwxMy42NjEyJTIwMTAuODUzOEwxNiUyMDAuNVolMjIlMjBmaWxsJTNEJTIyY3VycmVudENvbG9yJTIyJTJGJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDJTJGc3ZnJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMG5hc2Etbm8tYm9kZXIlMjIlMjAlM0VKT0lOJTIwV0lUSCUyMFVTJTNDJTJGcCUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3N2ZyUyMHhtbG5zJTNEJTIyaHR0cCUzQSUyRiUyRnd3dy53My5vcmclMkYyMDAwJTJGc3ZnJTIyJTIwd2lkdGglM0QlMjIzMiUyMiUyMGhlaWdodCUzRCUyMjMzJTIyJTIwY2xhc3MlM0QlMjJwcmltYXJ5LWNvbG9yJTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMyUyMiUyMGZpbGwlM0QlMjJub25lJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcGF0aCUyMGQlM0QlMjJNMTYlMjAwLjVMMTguMzM4OCUyMDEwLjg1MzhMMjcuMzEzNyUyMDUuMTg2MjlMMjEuNjQ2MiUyMDE0LjE2MTJMMzIlMjAxNi41TDIxLjY0NjIlMjAxOC44Mzg4TDI3LjMxMzclMjAyNy44MTM3TDE4LjMzODglMjAyMi4xNDYyTDE2JTIwMzIuNUwxMy42NjEyJTIwMjIuMTQ2Mkw0LjY4NjI5JTIwMjcuODEzN0wxMC4zNTM4JTIwMTguODM4OEwwJTIwMTYuNUwxMC4zNTM4JTIwMTQuMTYxMkw0LjY4NjI5JTIwNS4xODYyOUwxMy42NjEyJTIwMTAuODUzOEwxNiUyMDAuNVolMjIlMjBmaWxsJTNEJTIyY3VycmVudENvbG9yJTIyJTJGJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDJTJGc3ZnJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIwbmFzYS1uby1ib2RlciUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlM0MlMkZkaXYlM0UlMEQlMEElMjAlMjAlMjAlMjAlM0NkaXYlMjBzdHlsZSUzRCUyMmdhcCUzQSUyMDMwcHglM0IlMjIlMjBjbGFzcyUzRCUyMm5hc2EtZmxleCUyMG5hc2EtYm9sZC01MDAlMjBpbmZpbml0aWVzLXNsaWRlJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIwbmFzYS1uby1ib2RlciUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIyJTIwJTNFSk9JTiUyMFdJVEglMjBVUyUzQyUyRnAlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NzdmclMjB4bWxucyUzRCUyMmh0dHAlM0ElMkYlMkZ3d3cudzMub3JnJTJGMjAwMCUyRnN2ZyUyMiUyMHdpZHRoJTNEJTIyMzIlMjIlMjBoZWlnaHQlM0QlMjIzMyUyMiUyMGNsYXNzJTNEJTIycHJpbWFyeS1jb2xvciUyMiUyMHZpZXdCb3glM0QlMjIwJTIwMCUyMDMyJTIwMzMlMjIlMjBmaWxsJTNEJTIybm9uZSUyMiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3BhdGglMjBkJTNEJTIyTTE2JTIwMC41TDE4LjMzODglMjAxMC44NTM4TDI3LjMxMzclMjA1LjE4NjI5TDIxLjY0NjIlMjAxNC4xNjEyTDMyJTIwMTYuNUwyMS42NDYyJTIwMTguODM4OEwyNy4zMTM3JTIwMjcuODEzN0wxOC4zMzg4JTIwMjIuMTQ2MkwxNiUyMDMyLjVMMTMuNjYxMiUyMDIyLjE0NjJMNC42ODYyOSUyMDI3LjgxMzdMMTAuMzUzOCUyMDE4LjgzODhMMCUyMDE2LjVMMTAuMzUzOCUyMDE0LjE2MTJMNC42ODYyOSUyMDUuMTg2MjlMMTMuNjYxMiUyMDEwLjg1MzhMMTYlMjAwLjVaJTIyJTIwZmlsbCUzRCUyMmN1cnJlbnRDb2xvciUyMiUyRiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQyUyRnN2ZyUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3AlMjBjbGFzcyUzRCUyMm1hcmdpbi1ib3R0b20tMCUyMHRleHQtY2VudGVyJTIwbmFzYS1ib2xkJTIwZnMtNTAlMjBuYXNhLW5vLWJvZGVyJTIyJTIwJTNFSk9JTiUyMFdJVEglMjBVUyUzQyUyRnAlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NzdmclMjB4bWxucyUzRCUyMmh0dHAlM0ElMkYlMkZ3d3cudzMub3JnJTJGMjAwMCUyRnN2ZyUyMiUyMHdpZHRoJTNEJTIyMzIlMjIlMjBoZWlnaHQlM0QlMjIzMyUyMiUyMGNsYXNzJTNEJTIycHJpbWFyeS1jb2xvciUyMiUyMHZpZXdCb3glM0QlMjIwJTIwMCUyMDMyJTIwMzMlMjIlMjBmaWxsJTNEJTIybm9uZSUyMiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3BhdGglMjBkJTNEJTIyTTE2JTIwMC41TDE4LjMzODglMjAxMC44NTM4TDI3LjMxMzclMjA1LjE4NjI5TDIxLjY0NjIlMjAxNC4xNjEyTDMyJTIwMTYuNUwyMS42NDYyJTIwMTguODM4OEwyNy4zMTM3JTIwMjcuODEzN0wxOC4zMzg4JTIwMjIuMTQ2MkwxNiUyMDMyLjVMMTMuNjYxMiUyMDIyLjE0NjJMNC42ODYyOSUyMDI3LjgxMzdMMTAuMzUzOCUyMDE4LjgzODhMMCUyMDE2LjVMMTAuMzUzOCUyMDE0LjE2MTJMNC42ODYyOSUyMDUuMTg2MjlMMTMuNjYxMiUyMDEwLjg1MzhMMTYlMjAwLjVaJTIyJTIwZmlsbCUzRCUyMmN1cnJlbnRDb2xvciUyMiUyRiUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQyUyRnN2ZyUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3AlMjBjbGFzcyUzRCUyMm1hcmdpbi1ib3R0b20tMCUyMHRleHQtY2VudGVyJTIwbmFzYS1ib2xkJTIwZnMtNTAlMjIlMjAlM0VKT0lOJTIwV0lUSCUyMFVTJTNDJTJGcCUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3N2ZyUyMHhtbG5zJTNEJTIyaHR0cCUzQSUyRiUyRnd3dy53My5vcmclMkYyMDAwJTJGc3ZnJTIyJTIwd2lkdGglM0QlMjIzMiUyMiUyMGhlaWdodCUzRCUyMjMzJTIyJTIwY2xhc3MlM0QlMjJwcmltYXJ5LWNvbG9yJTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMyUyMiUyMGZpbGwlM0QlMjJub25lJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcGF0aCUyMGQlM0QlMjJNMTYlMjAwLjVMMTguMzM4OCUyMDEwLjg1MzhMMjcuMzEzNyUyMDUuMTg2MjlMMjEuNjQ2MiUyMDE0LjE2MTJMMzIlMjAxNi41TDIxLjY0NjIlMjAxOC44Mzg4TDI3LjMxMzclMjAyNy44MTM3TDE4LjMzODglMjAyMi4xNDYyTDE2JTIwMzIuNUwxMy42NjEyJTIwMjIuMTQ2Mkw0LjY4NjI5JTIwMjcuODEzN0wxMC4zNTM4JTIwMTguODM4OEwwJTIwMTYuNUwxMC4zNTM4JTIwMTQuMTYxMkw0LjY4NjI5JTIwNS4xODYyOUwxMy42NjEyJTIwMTAuODUzOEwxNiUyMDAuNVolMjIlMjBmaWxsJTNEJTIyY3VycmVudENvbG9yJTIyJTJGJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDJTJGc3ZnJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMG5hc2Etbm8tYm9kZXIlMjIlMjAlM0VKT0lOJTIwV0lUSCUyMFVTJTNDJTJGcCUzRSUwRCUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMCUzQ3N2ZyUyMHhtbG5zJTNEJTIyaHR0cCUzQSUyRiUyRnd3dy53My5vcmclMkYyMDAwJTJGc3ZnJTIyJTIwd2lkdGglM0QlMjIzMiUyMiUyMGhlaWdodCUzRCUyMjMzJTIyJTIwY2xhc3MlM0QlMjJwcmltYXJ5LWNvbG9yJTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMyUyMiUyMGZpbGwlM0QlMjJub25lJTIyJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcGF0aCUyMGQlM0QlMjJNMTYlMjAwLjVMMTguMzM4OCUyMDEwLjg1MzhMMjcuMzEzNyUyMDUuMTg2MjlMMjEuNjQ2MiUyMDE0LjE2MTJMMzIlMjAxNi41TDIxLjY0NjIlMjAxOC44Mzg4TDI3LjMxMzclMjAyNy44MTM3TDE4LjMzODglMjAyMi4xNDYyTDE2JTIwMzIuNUwxMy42NjEyJTIwMjIuMTQ2Mkw0LjY4NjI5JTIwMjcuODEzN0wxMC4zNTM4JTIwMTguODM4OEwwJTIwMTYuNUwxMC4zNTM4JTIwMTQuMTYxMkw0LjY4NjI5JTIwNS4xODYyOUwxMy42NjEyJTIwMTAuODUzOEwxNiUyMDAuNVolMjIlMjBmaWxsJTNEJTIyY3VycmVudENvbG9yJTIyJTJGJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDJTJGc3ZnJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDcCUyMGNsYXNzJTNEJTIybWFyZ2luLWJvdHRvbS0wJTIwdGV4dC1jZW50ZXIlMjBuYXNhLWJvbGQlMjBmcy01MCUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwJTIwY2xhc3MlM0QlMjJtYXJnaW4tYm90dG9tLTAlMjB0ZXh0LWNlbnRlciUyMG5hc2EtYm9sZCUyMGZzLTUwJTIwbmFzYS1uby1ib2RlciUyMiUyMCUzRUpPSU4lMjBXSVRIJTIwVVMlM0MlMkZwJTNFJTBEJTBBJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTIwJTNDc3ZnJTIweG1sbnMlM0QlMjJodHRwJTNBJTJGJTJGd3d3LnczLm9yZyUyRjIwMDAlMkZzdmclMjIlMjB3aWR0aCUzRCUyMjMyJTIyJTIwaGVpZ2h0JTNEJTIyMzMlMjIlMjBjbGFzcyUzRCUyMnByaW1hcnktY29sb3IlMjIlMjB2aWV3Qm94JTNEJTIyMCUyMDAlMjAzMiUyMDMzJTIyJTIwZmlsbCUzRCUyMm5vbmUlMjIlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0NwYXRoJTIwZCUzRCUyMk0xNiUyMDAuNUwxOC4zMzg4JTIwMTAuODUzOEwyNy4zMTM3JTIwNS4xODYyOUwyMS42NDYyJTIwMTQuMTYxMkwzMiUyMDE2LjVMMjEuNjQ2MiUyMDE4LjgzODhMMjcuMzEzNyUyMDI3LjgxMzdMMTguMzM4OCUyMDIyLjE0NjJMMTYlMjAzMi41TDEzLjY2MTIlMjAyMi4xNDYyTDQuNjg2MjklMjAyNy44MTM3TDEwLjM1MzglMjAxOC44Mzg4TDAlMjAxNi41TDEwLjM1MzglMjAxNC4xNjEyTDQuNjg2MjklMjA1LjE4NjI5TDEzLjY2MTIlMjAxMC44NTM4TDE2JTIwMC41WiUyMiUyMGZpbGwlM0QlMjJjdXJyZW50Q29sb3IlMjIlMkYlM0UlMEQlMEElMjAlMjAlMjAlMjAlMjAlMjAlMjAlMjAlM0MlMkZzdmclM0UlMEQlMEElMjAlMjAlMjAlMjAlM0MlMkZkaXYlM0UlMEQlMEElM0MlMkZkaXYlM0U=[/vc_raw_html][/vc_column][/vc_row][vc_row css=".vc_custom_1736507068386{margin-top: 50px !important;margin-bottom: 50px !important;}"][vc_column][vc_column_text css="" el_class="margin-bottom-30"]
<h4 class="fs-40" style="letter-spacing: 0; margin: 0px; font-weight: 800 !important; line-height: 1.4;"><span style="color: #c1c1c1;">ARTICLES</span> &amp; ADVICE</h4>
[/vc_column_text][nasa_post show_type="list_2" posts="6" category="" cats_enable="no" page_blogs="no"][/vc_column][/vc_row][vc_row el_class="nasa-nutrition-banner-brand" css=".vc_custom_1736568152540{margin-bottom: 50px !important;}"][vc_column el_class="nasa-nutrition-banner-brand"][nasa_slider bullets="false" column_number="7" column_number_small="2" column_number_tablet="4" gap_items="yes" effect_silde_dismis_reload="true" el_class="nasa-nutrition-banner-brand"][nasa_banner_2 img_src="' . $brand_1 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_2 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_3 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_4 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_5 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_6 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_1 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_2 . '"][/nasa_banner_2][nasa_banner_2 img_src="' . $brand_2 . '"][/nasa_banner_2][/nasa_slider][/vc_column][/vc_row]'
        ),
        
        'post_meta' => array(
            // '_nasa_header_block' => 'static-header-1',
            // '_nasa_el_class_header' => 'main-home-fix',
            '_nasa_vertical_menu_allways_show' => 'on',
            '_nasa_type_font_select' => 'google',
            '_nasa_type_headings' => 'Sofia Sans Condensed',
            '_nasa_type_texts' => 'Sofia Sans Condensed',
            '_nasa_type_nav' => 'Sofia Sans Condensed',
            '_nasa_type_banner' => 'Sofia Sans Condensed',
            '_nasa_type_price' => 'Sofia Sans Condensed',
            '_wpb_shortcodes_custom_css' => '.vc_custom_1728464009184{margin-top: 30px !important;}.vc_custom_1728465514197{margin-top: 50px !important;}.vc_custom_1728632343697{margin-top: 60px !important;}.vc_custom_1730369208236{padding-top: 0px !important;padding-bottom: 0px !important;}.vc_custom_1730369192247{margin-right: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-bottom: 0px !important;background-color: #F3F6FD !important;}.vc_custom_1730434206273{margin-top: -37px !important;margin-bottom: -23px !important;}.vc_custom_1728468640510{padding-top: 20px !important;}'
        ),
        
        'globals' => array(
            'header-type' => '6',
            'plus_wide_width' => '200',
            'color_primary' => '#5dba01',
            'loop_layout_buttons' => 'modern-9',

            'footer_mode' => 'builder',
            'footer-type' => 'wpb-footer-gym-nutrition',
        ),
    );
}
