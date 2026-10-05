<?php
function nasa_wpb_hand_tool() {

    $placeholder_src = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : 'https://dummyimage.com/1200x800?text=1200x800';

    
    $imgs_1 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-1.png', '3105',array(
        'post_title' => 'hand-tool-banner-1',
        'post_name' => 'hand-tool-banner-1',
    ));
    $imgs_1_src = $imgs_1 ? wp_get_attachment_image_url($imgs_1, 'full') : 'https://dummyimage.com/686x686?text=686x686';

    $imgs_2 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-2.png', '3105',array(
        'post_title' => 'hand-tool-banner-2',
        'post_name' => 'hand-tool-banner-2',
    ));
    $imgs_2_src = $imgs_2 ? wp_get_attachment_image_url($imgs_2, 'full') : 'https://dummyimage.com/686x686?text=686x686';

    $imgs_3 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-3.png', '3105',array(
        'post_title' => 'hand-tool-banner-3',
        'post_name' => 'hand-tool-banner-3',
    ));
    $imgs_3_src = $imgs_3 ? wp_get_attachment_image_url($imgs_3, 'full') : 'https://dummyimage.com/686x686?text=686x686';

    $imgs_4 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-4.jpg', '3094',array(
        'post_title' => 'hand-tool-banner-4',
        'post_name' => 'hand-tool-banner-4',
    ));
    $imgs_4_src = $imgs_4 ? wp_get_attachment_image_url($imgs_4, 'full') : 'https://dummyimage.com/518x506?text=518x506';

    $imgs_5 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-5.jpg', '3094',array(
        'post_title' => 'hand-tool-banner-5',
        'post_name' => 'hand-tool-banner-5',
    ));
    $imgs_5_src = $imgs_5 ? wp_get_attachment_image_url($imgs_5, 'full') : 'https://dummyimage.com/518x506?text=518x506';

    $imgs_6 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-6.jpg', '3094',array(
        'post_title' => 'hand-tool-banner-6',
        'post_name' => 'hand-tool-banner-6',
    ));
    $imgs_6_src = $imgs_6 ? wp_get_attachment_image_url($imgs_6, 'full') : 'https://dummyimage.com/518x506?text=518x506';

    $imgs_7 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-7.jpg', '3094',array(
        'post_title' => 'hand-tool-banner-7',
        'post_name' => 'hand-tool-banner-7',
    ));
    $imgs_7_src = $imgs_7 ? wp_get_attachment_image_url($imgs_7, 'full') : 'https://dummyimage.com/518x506?text=518x506';

    $imgs_8 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-8.jpg', '3556',array(
        'post_title' => 'hand-tool-banner-8',
        'post_name' => 'hand-tool-banner-8',
    ));
    $imgs_8_src = $imgs_8 ? wp_get_attachment_image_url($imgs_8, 'full') : 'https://dummyimage.com/671x360?text=671x360';

    $imgs_9 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-9.jpg', '3556',array(
        'post_title' => 'hand-tool-banner-9',
        'post_name' => 'hand-tool-banner-9',
    ));
    $imgs_9_src = $imgs_9 ? wp_get_attachment_image_url($imgs_9, 'full') : 'https://dummyimage.com/671x360?text=671x360';

    $imgs_10 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tools-banner-10.jpg', '3556',array(
        'post_title' => 'hand-tool-banner-10',
        'post_name' => 'hand-tool-banner-10',
    ));
    $imgs_10_src = $imgs_10 ? wp_get_attachment_image_url($imgs_10, 'full') : 'https://dummyimage.com/671x360?text=671x360';

    $imgs_11 = elessi_import_upload('/elementor/wp-content/uploads/2025/02/hand-tool-advertisement.jpg', '3319',array(
        'post_title' => 'hand-tool-advertisement',
        'post_name' => 'hand-tool-advertisement',
    ));
    $imgs_11_src = $imgs_11 ? wp_get_attachment_image_url($imgs_11, 'full') : 'https://dummyimage.com/2100x288?text=2100x288';

    return array(
        'post' => array(
            'post_title' => 'WPB Hand Tool',
            'post_name' => 'wpb-hand-tool',
            'post_content' => '[vc_row equal_height="yes" css=".vc_custom_1739950748392{padding-top: 20px !important;}"][vc_column width="1/4" el_class="ht-banner-side nasa-flex flex-wrap align-stretch"][vc_raw_html css="" el_class="hidden-tag"]JTNDc3R5bGUlM0UlMEElMEElMEElM0Fyb290JTIwJTdCJTBBJTIwJTIwJTIwJTIwLS1uc2h0LXByaW1hcnktY29sb3IyJTNBJTIwJTIzRkNCODAyJTNCJTBBJTIwJTIwJTIwJTIwLS1iYW4xLWNvbG9yJTNBJTIwJTIzRjZGNkYxJTNCJTBBJTIwJTIwJTIwJTIwLS1iYW4yLWNvbG9yJTNBJTIwJTIzRkJGOEY4JTNCJTBBJTIwJTIwJTIwJTIwLS1iYW4zLWNvbG9yJTNBJTIwJTIzRjFGNUY2JTNCJTBBJTdEJTBBJTBBLm5hc2EtY2F0ZWdvcnktc2xpZGVyLWhvcml6b250YWwtNyUyMGgzLnNlY3Rpb24tdGl0bGUlMjAlN0IlMEElMjAlMjAlMjAlMjB0ZXh0LWRlY29yYXRpb24lM0ElMjB1bmRlcmxpbmUlM0IlMEElMjAlMjAlMjAlMjBmb250LXNpemUlM0ElMjAxMDAlMjUlM0IlMEElN0QlMEElMEEubmFzYS1oYW5kLXRvb2wtcHJvZHVjdC10YWJzJTIwLm5hc2EtdGFicyUyMC5uYXNhLXRhYiUzRWElMjAlN0IlMEElMjAlMjAlMjAlMjBmb250LXNpemUlM0ElMjAyMHB4JTNCJTBBJTIwJTIwJTIwJTIwZm9udC13ZWlnaHQlM0ElMjA2MDAlMjAlMjFpbXBvcnRhbnQlM0IlMEElMjAlMjAlMjAlMjBsZXR0ZXItc3BhY2luZyUzQSUyMG5vcm1hbCUzQiUwQSU3RCUwQSUwQS5uYXNhLWJhbmVyLW1haW4taGFuZC10b29sJTIwLm5hc2EtYmFubmVyJTIwLm5hc2EtYmFubmVyLWltYWdlJTIwJTdCJTBBJTIwJTIwJTIwJTIwLXdlYmtpdC10cmFuc2l0aW9uJTNBJTIwYWxsJTIwMjUwbXMlMjBlYXNlJTNCJTBBJTIwJTIwJTIwJTIwLW1vei10cmFuc2l0aW9uJTNBJTIwYWxsJTIwMjUwbXMlMjBlYXNlJTNCJTBBJTIwJTIwJTIwJTIwLW8tdHJhbnNpdGlvbiUzQSUyMGFsbCUyMDI1MG1zJTIwZWFzZSUzQiUwQSUyMCUyMCUyMCUyMHRyYW5zaXRpb24lM0ElMjBhbGwlMjAyNTBtcyUyMGVhc2UlM0IlMEElN0QlMEElMEEubmFzYS1iYW5lci1tYWluLWhhbmQtdG9vbCUyMC5uYXNhLWJhbm5lciUzQWhvdmVyJTIwLm5hc2EtYmFubmVyLWltYWdlJTIwJTdCJTBBJTIwJTIwJTIwJTIwdHJhbnNmb3JtJTNBJTIwdHJhbnNsYXRlWCUyODIwcHglMjklM0IlMEElN0QlMEElMEEubmFzYS1iYW5lci1tYWluLWhhbmQtdG9vbCUyMC5uYXNhLWJhbm5lci5iYW4xJTIwJTdCJTBBJTIwJTIwJTIwJTIwYmFja2dyb3VuZC1jb2xvciUzQSUyMHZhciUyOC0tYmFuMS1jb2xvciUyOSUzQiUwQSU3RCUwQSUwQS5uYXNhLWJhbmVyLW1haW4taGFuZC10b29sJTIwLm5hc2EtYmFubmVyLmJhbjIlMjAlN0IlMEElMjAlMjAlMjAlMjBiYWNrZ3JvdW5kLWNvbG9yJTNBJTIwdmFyJTI4LS1iYW4yLWNvbG9yJTI5JTNCJTBBJTdEJTBBJTBBLm5hc2EtYmFuZXItbWFpbi1oYW5kLXRvb2wlMjAubmFzYS1iYW5uZXIuYmFuMyUyMCU3QiUwQSUyMCUyMCUyMCUyMGJhY2tncm91bmQtY29sb3IlM0ElMjB2YXIlMjgtLWJhbjMtY29sb3IlMjklM0IlMEElN0QlMEElMEEubnMtaHQtc2hvcC1ub3clMjAlM0VzcGFuJTIwJTdCJTBBJTIwJTIwJTIwJTIwcG9zaXRpb24lM0ElMjByZWxhdGl2ZSUzQiUwQSU3RCUwQSUwQS5ucy1odC1zaG9wLW5vdyUyMCUzRXNwYW4lM0ElM0FhZnRlciUyMCU3QiUwQSUyMCUyMCUyMCUyMGNvbnRlbnQlM0ElMjAlMjclMjclM0IlMEElMjAlMjAlMjAlMjB3aWR0aCUzQSUyMDEwMCUyNSUzQiUwQSUyMCUyMCUyMCUyMGhlaWdodCUzQSUyMDFweCUzQiUwQSUyMCUyMCUyMCUyMGJhY2tncm91bmQtY29sb3IlM0ElMjB2YXIlMjgtLW5zaHQtcHJpbWFyeS1jb2xvcjIlMjklM0IlMEElMjAlMjAlMjAlMjBwb3NpdGlvbiUzQSUyMGFic29sdXRlJTNCJTBBJTIwJTIwJTIwJTIwbGVmdCUzQSUyMDAlM0IlMEElMjAlMjAlMjAlMjBib3R0b20lM0ElMjAtMnB4JTNCJTBBJTIwJTIwJTIwJTIwLXdlYmtpdC10cmFuc2l0aW9uJTNBJTIwYWxsJTIwMzUwbXMlMjBlYXNlJTNCJTBBJTIwJTIwJTIwJTIwLW1vei10cmFuc2l0aW9uJTNBJTIwYWxsJTIwMzUwbXMlMjBlYXNlJTNCJTBBJTIwJTIwJTIwJTIwLW8tdHJhbnNpdGlvbiUzQSUyMGFsbCUyMDM1MG1zJTIwZWFzZSUzQiUwQSUyMCUyMCUyMCUyMHRyYW5zaXRpb24lM0ElMjBhbGwlMjAzNTBtcyUyMGVhc2UlM0IlMEElN0QlMEElMEEubnMtaHQtc2hvcC1ub3clM0Fob3ZlciUyMCUzRXNwYW4lM0ElM0FhZnRlciUyMCU3QiUwQSUyMCUyMCUyMCUyMGxlZnQlM0ElMjAxMDAlMjUlM0IlMEElN0QlMEEuaHQtYmFubmVyLXNpZGUlMjAubmFzYS1iYW5uZXItdjIlMjAlN0IlMEF3aWR0aCUzQTEwMCUyNSUzQiUwQSU3RCUwQSUyMCUyMCUyMCUyMC5odC1iYW5uZXItc2lkZSUyMC5uYXNhLWJhbm5lci12MiUyMGltZyUyMCU3QiUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMGhlaWdodCUzQSUyMDEwMCUyNSUzQiUwQSUyMCUyMCUyMCUyMCUyMCUyMCUyMCUyMG9iamVjdC1maXQlM0ElMjBjb3ZlciUzQiUwQSUyMCUyMCUyMCUyMCU3RCUwQSUzQyUyRnN0eWxlJTNF[/vc_raw_html][nasa_banner_2 align="center" text_align="text-center" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_3 . '" el_class="nasa-over-hide force-radius-10"]
<p class="fs-14 margin-top-0 margin-bottom-0 tablet-margin-bottom-10 " style="letter-spacing: 1px;">NEW ARRIVALS</p>

<h5 class="fs-25 ">Brand New Series That Must Try</h5>
<p class="nasa-flex jc"><a class="fs-14 nasa-flex jc ns-ht-shop-now" style="text-decoration: none;" title="Shop Now" href="#"><span class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5">Shop</span>now ↗
</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" text_align="text-center" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_5 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0"]
<p class="fs-14 margin-top-0 margin-bottom-0 tablet-margin-bottom-10 " style="letter-spacing: 1px;">EXCLUSIVE DEAL</p>

<h4 class="fs-25 ">Save Up to 20%</h4>
<p class="fs-14 margin-top-0 margin-bottom-0 tablet-margin-bottom-10 " style="letter-spacing: 1px;">For all tools combos</p>
<p class="nasa-flex jc"><a class="fs-14 nasa-flex jc ns-ht-shop-now" style="text-decoration: none;" title="Shop Now" href="#"><span class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5">Shop</span>now ↗</a></p>
[/nasa_banner_2][/vc_column][vc_column width="1/2" el_class="mobile-margin-bottom-10 mobile-margin-top-10"][nasa_slider bullets_pos="inside" bullets_style="counter" bullets_align="left" column_number="1" column_number_small="1" column_number_tablet="1" el_class="nasa-over-hide force-radius-10 nasa-baner-main-hand-tool"][nasa_banner_2 img_src="' . $imgs_1 . '" el_class="ban1"]
<h3>Combo New Generation
Hand Tools</h3>
<span class="fs-14 margin-top-0 margin-bottom-25 tablet-margin-bottom-10" style="letter-spacing: 1px;">New Generation Hand Tools recommended
products that you should have for your next
project</span>
<a class="button small fs-15 tablet-fs-11 force-radius-5 margin-top-20" style="letter-spacing: 0; text-transform: none; height: 49px;" tabindex="0" title="Shop now" href="#">Shop now</a>[/nasa_banner_2][nasa_banner_2 img_src="' . $imgs_2 . '" el_class="ban2"]
<h3 class="">All At Once With
A Cheap Price</h3>
<span class="fs-14 margin-top-0 margin-bottom-25 tablet-margin-bottom-10 " style="letter-spacing: 1px;">All recommended tools in one combo of with
a reasonable price</span>
<a class=" button small fs-15 tablet-fs-11 force-radius-5 margin-top-20" style="letter-spacing: 0; text-transform: none; height: 49px;" tabindex="0" title="Shop now" href="#">Shop now</a>[/nasa_banner_2][nasa_banner_2 img_src="' . $imgs_3 . '" el_class="ban3"]
<h3 class="">Sale Off 20% In
Products Comboe</h3>
<span class="fs-14 margin-top-0 margin-bottom-25 tablet-margin-bottom-10 " style="letter-spacing: 1px;">Big sale when you buy all products combo hand
tools, with all recommended products</span>
<a class=" button small fs-15 tablet-fs-11 force-radius-5 margin-top-20" style="letter-spacing: 0; text-transform: none; height: 49px;" tabindex="0" title="Shop now" href="#">Shop now</a>[/nasa_banner_2][/nasa_slider][/vc_column][vc_column width="1/4" el_class="ht-banner-side nasa-flex flex-wrap align-stretch"][nasa_banner_2 align="center" text_align="text-center" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_6 . '" el_class="nasa-over-hide force-radius-10"]
<p class="fs-14 margin-top-0 margin-bottom-0 tablet-margin-bottom-10 " style="letter-spacing: 1px;">BUYER’S PICKS</p>

<h4 class="fs-25 ">Durable Hand Tools</h4>
<p class="nasa-flex jc"><a class="fs-14 nasa-flex jc ns-ht-shop-now" style="text-decoration: none;" title="Shop Now" href="#"><span class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5">Shop</span>now ↗</a></p>
[/nasa_banner_2][nasa_banner_2 align="center" text_align="text-center" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_7 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0"]
<p class="fs-14 margin-top-0 margin-bottom-0 tablet-margin-bottom-10 " style="letter-spacing: 1px;">RECOMMENDATIONS</p>

<h4 class="fs-25 ">Must-Try Home Tools</h4>
<p class="nasa-flex jc"><a class="fs-14 nasa-flex jc ns-ht-shop-now" style="text-decoration: none;" title="Shop Now" href="#"><span class="margin-right-5 rtl-margin-right-0 rtl-margin-left-5">Shop</span>now ↗</a></p>
[/nasa_banner_2][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column][vc_row_inner css=".vc_custom_1741248086366{border-top-width: 1px !important;border-right-width: 1px !important;border-bottom-width: 1px !important;border-left-width: 1px !important;padding-top: 15px !important;padding-right: 15px !important;padding-bottom: 15px !important;padding-left: 15px !important;border-left-style: solid !important;border-right-style: solid !important;border-top-style: solid !important;border-bottom-style: solid !important;border-radius: 5px !important;border-color: #ECECEC !important;}" el_class="margin-left-0 margin-right-0"][vc_column_inner el_class="padding-top-10 padding-bottom-10" width="1/5" offset="vc_col-md-6"][nasa_service_box service_html="JTNDc3ZnJTIwd2lkdGglM0QlMjIzOCUyMiUyMGhlaWdodCUzRCUyMjM4JTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMiUyMiUyMGZpbGwlM0QlMjIlMjNGQ0I4MDIlMjIlM0UlM0NwYXRoJTIwZCUzRCUyMk05LjEyMyUyMDMwLjQ2NGwtMS4zMy02LjI2OC02LjMxOC0xLjM5NyUyMDEuMjkxLTIuNDc1JTIwNS43ODUtMC4zMTZjMC4yOTctMC4zODYlMjAwLjk2LTEuMjM0JTIwMS4zNzQtMS42NDhsNS4yNzEtNS4yNzEtMTAuOTg5LTUuMzg4JTIwMi43ODItMi43ODIlMjAxMy45MzIlMjAyLjQ0NCUyMDQuOTMzLTQuOTMzYzAuNTg1LTAuNTg1JTIwMS40OTYtMC44OTQlMjAyLjYzNC0wLjg5NCUyMDAuNzc2JTIwMCUyMDEuMzk1JTIwMC4xNDMlMjAxLjQyMSUyMDAuMTQ5bDAuMyUyMDAuMDcwJTIwMC4wODklMjAwLjI5NWMwLjQ2OSUyMDEuNTUlMjAwLjE4NyUyMDMuMjk4LTAuNjclMjA0LjE1NWwtNC45NTYlMjA0Ljk1NiUyMDIuNDM0JTIwMTMuODc1LTIuNzgyJTIwMi43ODItNS4zNjctMTAuOTQ1LTQuOTIzJTIwNC45MjRjLTAuNTE4JTIwMC41MTctMS42MjMlMjAxLjUzNi0yLjAzMyUyMDEuOTEybC0wLjQzMSUyMDUuNDI1LTIuNDQ5JTIwMS4zMjl6TTMuMDY1JTIwMjIuMDU5bDUuNjMlMjAxLjI0NCUyMDEuMTc2JTIwNS41NDQlMjAwLjY4NS0wLjM3MiUyMDAuNDE4LTUuMjY4JTIwMC4xNTUtMC4xNDJjMC4wMTYtMC4wMTQlMjAxLjU0Mi0xLjQwOSUyMDIuMTUzLTIuMDIwbDUuOTc4LTUuOTc5JTIwNS4zNjclMjAxMC45NDUlMjAxLjMzNC0xLjMzNS0yLjQzNC0xMy44NzYlMjA1LjM0OS01LjM0OGMwLjQ2NC0wLjQ2NCUyMDAuNzQ1LTEuNTk4JTIwMC40ODQtMi43ODMtMC4yMTYtMC4wMzItMC41MjYtMC4wNjYtMC44Ny0wLjA2Ni0wLjU5MyUyMDAtMS4zOTklMjAwLjEwMS0xLjg4MSUyMDAuNTgybC01LjMyNSUyMDUuMzI1LTEzLjkzMy0yLjQ0NC0xLjMzNSUyMDEuMzM0JTIwMTAuOTg5JTIwNS4zODgtNi4zMjYlMjA2LjMyNmMtMC40ODMlMjAwLjQ4Mi0xLjQxOCUyMDEuNzIyLTEuNDI4JTIwMS43MzRsLTAuMTQ5JTIwMC4xOTgtNS42NzIlMjAwLjMxLTAuMzY2JTIwMC43MDJ6JTIyJTNFJTNDJTJGcGF0aCUzRSUzQyUyRnN2ZyUzRQ==" service_hover="buzz_effect" service_title="Free Shipping" service_desc="For all order over $200" el_class="margin-bottom-0"][/vc_column_inner][vc_column_inner el_class="padding-top-10 padding-bottom-10" width="1/5" offset="vc_col-md-6"][nasa_service_box service_html="JTNDc3ZnJTIwd2lkdGglM0QlMjIzOCUyMiUyMGhlaWdodCUzRCUyMjM4JTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMjclMjAzMiUyMiUyMGZpbGwlM0QlMjIlMjNGQ0I4MDIlMjIlM0UlM0NwYXRoJTIwZCUzRCUyMk0yMC40OCUyMDE4LjYxM2wtMS4xMiUyMDcuODQlMjA3LjYyNy0xLjE3My0yLjk4Ny0zLjA0MGMxLjAxMy0xLjg2NyUyMDEuNi0zLjk0NyUyMDEuNi02LjI0JTIwMC03LjA5My01LjcwNy0xMi44LTEyLjgtMTIuOHYxLjA2N2M2LjQ1MyUyMDAlMjAxMS43MzMlMjA1LjI4JTIwMTEuNzMzJTIwMTEuNzMzJTIwMCUyMDEuOTczLTAuNDglMjAzLjc4Ny0xLjMzMyUyMDUuNDRsLTIuNzItMi44Mjd6TTIwLjU4NyUyMDI1LjE3M2wwLjY0LTQuMjY3JTIwMy41MiUyMDMuNjI3LTQuMTYlMjAwLjY0eiUyMiUzRSUzQyUyRnBhdGglM0UlM0NwYXRoJTIwZCUzRCUyMk0xLjA2NyUyMDE2YzAtMi44MjclMjAxLjAxMy01LjM4NyUyMDIuNjY3LTcuNDEzbDMuMjUzJTIwMy4zMDclMjAxLjEyLTcuODQtNy42MjclMjAxLjIyNyUyMDIuNDUzJTIwMi41NmMtMS44MTMlMjAyLjE4Ny0yLjkzMyUyMDUuMDY3LTIuOTMzJTIwOC4xNiUyMDAlMjA3LjA5MyUyMDUuNzA3JTIwMTIuOCUyMDEyLjglMjAxMi44di0xLjA2N2MtNi40NTMlMjAwLTExLjczMy01LjI4LTExLjczMy0xMS43MzN6TTYuODI3JTIwNS4zODdsLTAuNjQlMjA0LjI2Ny0zLjUyLTMuNjI3JTIwNC4xNi0wLjY0eiUyMiUzRSUzQyUyRnBhdGglM0UlM0MlMkZzdmclM0U=" service_hover="buzz_effect" service_title="30 Days Return" service_desc="For all order over $200" el_class="margin-bottom-0"][/vc_column_inner][vc_column_inner el_class="padding-top-10 padding-bottom-10" width="1/5" offset="vc_col-md-6"][nasa_service_box service_html="JTNDc3ZnJTIwd2lkdGglM0QlMjIzOCUyMiUyMGhlaWdodCUzRCUyMjM4JTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzIlMjAzMiUyMiUyMGZpbGwlM0QlMjIlMjNGQ0I4MDIlMjIlM0UlM0NwYXRoJTIwZCUzRCUyMk0yMi41NDQlMjA5LjYwMmMwLjg4OS0wLjc4MiUyMDEuNDUzLTEuOTI0JTIwMS40NTMtMy4xOTglMjAwLTIuMzUyLTEuOTEzLTQuMjY1LTQuMjY1LTQuMjY1LTEuNjA1JTIwMC0zLjAwNCUyMDAuODkyLTMuNzMyJTIwMi4yMDUtMC43MjgtMS4zMTMtMi4xMjctMi4yMDUtMy43MzItMi4yMDUtMi4zNTIlMjAwLTQuMjY1JTIwMS45MTMtNC4yNjUlMjA0LjI2NSUyMDAlMjAxLjI3NCUyMDAuNTY0JTIwMi40MTYlMjAxLjQ1MyUyMDMuMTk4aC02LjI1MXYyMC4yNTloMjUuNTl2LTIwLjI1OWgtNi4yNTF6TTI3LjcyOSUyMDEwLjY2OHY4LjUzaC0xMS4xOTZ2LTguNTNoMC42ODFsMi41ODglMjA0LjUzJTIwMC45MjYtMC41MjktMi4yODYtNC4wMDFoOS4yODd6TTE2JTIwOC41NDRsMC42MDQlMjAxLjA1OGgtMS4yMDlsMC42MDQtMS4wNTh6TTE5LjczMiUyMDMuMjA1YzEuNzY0JTIwMCUyMDMuMTk5JTIwMS40MzUlMjAzLjE5OSUyMDMuMTk5cy0xLjQzNSUyMDMuMTk4LTMuMTk5JTIwMy4xOThjLTEuNzY0JTIwMC0zLjE5OS0xLjQzNC0zLjE5OS0zLjE5OHMxLjQzNS0zLjE5OSUyMDMuMTk5LTMuMTk5ek05LjA2OSUyMDYuNDA0YzAtMS43NjQlMjAxLjQzNS0zLjE5OSUyMDMuMTk5LTMuMTk5czMuMTk5JTIwMS40MzUlMjAzLjE5OSUyMDMuMTk5YzAlMjAxLjc2My0xLjQzNSUyMDMuMTk4LTMuMTk5JTIwMy4xOThzLTMuMTk5LTEuNDM0LTMuMTk5LTMuMTk4ek0xMy41NTglMjAxMC42NjhsLTIuMjg2JTIwNCUyMDAuOTI2JTIwMC41MjklMjAyLjU4OC00LjUzaDAuNjgxdjguNTNoLTExLjE5NnYtOC41M2g5LjI4N3pNNC4yNzElMjAyMC4yNjVoMTEuMTk2djguNTNoLTExLjE5NnYtOC41M3pNMTYuNTMzJTIwMjguNzk0di04LjUzaDExLjE5NnY4LjUzaC0xMS4xOTZ6JTIyJTNFJTNDJTJGcGF0aCUzRSUzQyUyRnN2ZyUzRQ==" service_hover="buzz_effect" service_title="Secure Payment" service_desc="100% Secure Payment" el_class="margin-bottom-0"][/vc_column_inner][vc_column_inner el_class="padding-top-10 padding-bottom-10" width="1/5" offset="vc_col-md-6"][nasa_service_box service_html="JTNDc3ZnJTIwd2lkdGglM0QlMjIzOCUyMiUyMGhlaWdodCUzRCUyMjM4JTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMjclMjAzMiUyMiUyMGZpbGwlM0QlMjIlMjNGQ0I4MDIlMjIlM0UlM0NwYXRoJTIwZCUzRCUyMk0yNi42NjclMjAxNC40YzAtNy4zNi01Ljk3My0xMy4zMzMtMTMuMzMzLTEzLjMzM3MtMTMuMzMzJTIwNS45NzMtMTMuMzMzJTIwMTMuMzMzdjUuNDRoMC4wNTNjMCUyMDAuMTYlMjAwJTIwMC4zMiUyMDAlMjAwLjQyNyUyMDAlMjAzLjUyJTIwMi44MjclMjA2LjQlMjA2LjM0NyUyMDYuNHYwLTEyLjhoLTAuMDUzYy0yLjI0JTIwMC00LjE2JTIwMS4xMi01LjI4JTIwMi44OHYtMi4zNDdjMC02Ljc3MyUyMDUuNDkzLTEyLjI2NyUyMDEyLjI2Ny0xMi4yNjdzMTIuMjY3JTIwNS40OTMlMjAxMi4yNjclMjAxMi4yNjd2Mi4zNDdjLTEuMTItMS43MDctMy4wOTMtMi44OC01LjI4LTIuODhoLTAuMDUzdjEyLjhoMC4wNTNjMC41MzMlMjAwJTIwMS4wMTMtMC4wNTMlMjAxLjU0Ny0wLjIxMy0xLjYlMjAxLjU0Ny0zLjU3MyUyMDIuNjEzLTUuODEzJTIwMy4xNDd2LTEuODY3aC01LjM4N3YzLjJoNS4zMzN2LTAuMjEzYzMuNDEzLTAuNjkzJTIwNi4zNDctMi42NjclMjA4LjI2Ny01LjQ0JTIwMS40NC0xLjE3MyUyMDIuNC0yLjk4NyUyMDIuNC01LjAxMyUyMDAtMC4xNiUyMDAtMC4zMiUyMDAtMC40Mjd2MC01LjQ0ek01LjMzMyUyMDE1LjA0MHYxMC40NTNjLTIuNC0wLjQ4LTQuMjY3LTIuNjY3LTQuMjY3LTUuMjI3czEuODY3LTQuNzQ3JTIwNC4yNjctNS4yMjd6TTE0LjkzMyUyMDI5Ljg2N2gtMy4ydi0xLjA2N2gzLjJ2MS4wNjd6TTIxLjMzMyUyMDI1LjQ5M3YtMTAuNDUzYzIuNDUzJTIwMC40OCUyMDQuMjY3JTIwMi42NjclMjA0LjI2NyUyMDUuMjI3cy0xLjgxMyUyMDQuNzQ3LTQuMjY3JTIwNS4yMjd6JTIyJTNFJTNDJTJGcGF0aCUzRSUzQyUyRnN2ZyUzRQ==" service_hover="buzz_effect" service_title="Help Center" service_desc="24/7 Support System" el_class="margin-bottom-0"][/vc_column_inner][vc_column_inner el_class="padding-top-10 padding-bottom-10" width="1/5" offset="vc_hidden-md vc_hidden-sm"][nasa_service_box service_html="JTNDc3ZnJTIwd2lkdGglM0QlMjIzOCUyMiUyMGhlaWdodCUzRCUyMjM4JTIyJTIwdmlld0JveCUzRCUyMjAlMjAwJTIwMzAlMjAzMiUyMiUyMGZpbGwlM0QlMjIlMjNGQ0I4MDIlMjIlM0UlM0NwYXRoJTIwZCUzRCUyMk0yNy43ODclMjAxNC45MzNoLTAuNTg3Yy0wLjgtMi4wODAtMi40LTMuODQtNC41MzMtNS4xMiUyMDAtMi44MjclMjAwLjI2Ny0zLjQ2NyUyMDAuODUzLTUuMjgtMi4xODclMjAwLjMyLTQuMzczJTIwMS42NTMtNS40OTMlMjAzLjU3My0wLjc0Ny0wLjE2LTEuNDkzLTAuMjEzLTIuMjQtMC4yNjclMjAwLjEwNy0wLjQyNyUyMDAuMjEzLTAuOTA3JTIwMC4yMTMtMS40NCUyMDAtMi42NjctMi4xMzMtNC44LTQuOC00LjhzLTQuOCUyMDIuMTMzLTQuOCUyMDQuOGMwJTIwMS4zMzMlMjAwLjUzMyUyMDIuNTA3JTIwMS40NCUyMDMuNDEzLTIuMDgwJTIwMS4yMjctMy42OCUyMDIuOTMzLTQuNTMzJTIwNC45Ni0xLjQ0LTAuNTMzLTIuMjQtMS4zMzMtMi4yNC0yLjM0NyUyMDAtMS4xMiUyMDAuOTA3LTIuMTMzJTIwMS43MDctMi40bC0wLjMyLTEuMDEzYy0xLjMzMyUyMDAuMzczLTIuNDUzJTIwMS44NjctMi40NTMlMjAzLjMwNyUyMDAlMjAwLjg1MyUyMDAuMzczJTIwMi40NTMlMjAyLjkzMyUyMDMuNDEzLTAuMjEzJTIwMC42OTMtMC4yNjclMjAxLjM4Ny0wLjI2NyUyMDIuMTMzJTIwMCUyMDIuNjY3JTIwMS4zMzMlMjA1LjEyJTIwMy40NjclMjA2LjkzM2wtMC42NCUyMDEuODY3Yy0wLjQyNyUyMDEuMjglMjAwLjIxMyUyMDIuNjEzJTIwMS40OTMlMjAzLjA0MGwxLjQ5MyUyMDAuNTMzYzAuMjY3JTIwMC4xMDclMjAwLjUzMyUyMDAuMTYlMjAwLjglMjAwLjE2JTIwMS4wMTMlMjAwJTIwMS45Mi0wLjY0JTIwMi4yOTMtMS42bDAuNDI3LTEuMjI3YzEuMDY3JTIwMC4yMTMlMjAyLjEzMyUyMDAuMzczJTIwMy4zMDclMjAwLjM3MyUyMDAuNzQ3JTIwMCUyMDEuNDkzLTAuMDUzJTIwMi4xODctMC4xNmwwLjY0JTIwMS4zMzNjMC40MjclMjAwLjg1MyUyMDEuMjglMjAxLjMzMyUyMDIuMTg3JTIwMS4zMzMlMjAwLjM3MyUyMDAlMjAwLjY5My0wLjA1MyUyMDEuMDY3LTAuMjY3bDEuMzg3LTAuNjkzYzEuMTczLTAuNTg3JTIwMS43MDctMi4wMjclMjAxLjEyLTMuMmwtMC4zNzMtMC44YzEuNDQtMC45NiUyMDIuNTYtMi4xODclMjAzLjM2LTMuNjI3aDEuMDY3YzEuNDQlMjAwJTIwMi42MTMtMS4xNzMlMjAyLjYxMy0yLjYxM3YtMS43MDdjLTAuMTYtMS40NC0xLjMzMy0yLjYxMy0yLjc3My0yLjYxM3pNNy40NjclMjA2LjRjMC0yLjA4MCUyMDEuNjUzLTMuNzMzJTIwMy43MzMtMy43MzNzMy43MzMlMjAxLjY1MyUyMDMuNzMzJTIwMy43MzNjMCUyMDAuNTMzLTAuMTA3JTIwMS4wMTMtMC4zMiUyMDEuNDkzLTIuMTMzJTIwMC4wNTMtNC4xMDclMjAwLjU4Ny01LjgxMyUyMDEuMzg3LTAuOC0wLjY5My0xLjMzMy0xLjcwNy0xLjMzMy0yLjg4ek0yOS4zMzMlMjAxOS4yNTNjMCUyMDAuODUzLTAuNjkzJTIwMS41NDctMS41NDclMjAxLjU0N2gtMS43MDdsLTAuMzIlMjAwLjUzM2MtMC42OTMlMjAxLjIyNy0xLjcwNyUyMDIuMzQ3LTIuOTg3JTIwMy4yNTNsLTAuNzQ3JTIwMC41MzMlMjAwLjQyNyUyMDAuOCUyMDAuMzczJTIwMC44YzAuMzIlMjAwLjY0JTIwMC4wNTMlMjAxLjQ5My0wLjY0JTIwMS44MTNsLTEuMzg3JTIwMC42NGMtMC4xNiUyMDAuMTA3LTAuMzczJTIwMC4xNi0wLjU4NyUyMDAuMTYtMC41MzMlMjAwLTAuOTYtMC4yNjctMS4yMjctMC43NDdsLTAuNjQtMS4zMzMtMC4zMi0wLjY5My0wLjglMjAwLjEwN2MtMC42OTMlMjAwLjEwNy0xLjM4NyUyMDAuMTYtMi4wMjclMjAwLjE2LTEuMDEzJTIwMC0yLjA4MC0wLjEwNy0zLjA5My0wLjMybC0wLjkwNy0wLjIxMy0wLjI2NyUyMDAuOTA3LTAuNDI3JTIwMS4yMjdjLTAuMjEzJTIwMC41MzMtMC42OTMlMjAwLjkwNy0xLjI4JTIwMC45MDctMC4xNiUyMDAtMC4zMiUyMDAtMC40MjctMC4wNTNsLTEuNDkzLTAuNTMzYy0wLjY5My0wLjI2Ny0xLjA2Ny0xLjAxMy0wLjgtMS43MDdsMC42NC0xLjkyJTIwMC4yNjctMC42OTMtMC41ODctMC40OGMtMi4wMjctMS42NTMtMy4wOTMtMy43ODctMy4wOTMtNi4wODAlMjAwLTQuOTA3JTIwNS4xNzMtOC45NiUyMDExLjQ2Ny04Ljk2JTIwMC44NTMlMjAwJTIwMS43NiUyMDAuMTA3JTIwMi42MTMlMjAwLjIxM2wwLjc0NyUyMDAuMTYlMjAwLjM3My0wLjY0YzAuNjQtMS4xMiUyMDEuNzYtMi4wMjclMjAyLjk4Ny0yLjU2LTAuMjEzJTIwMC45MDctMC4zMiUyMDEuOTItMC4zNzMlMjAzLjczM3YwLjU4N2wwLjUzMyUyMDAuMzJjMS45MiUyMDEuMTczJTIwMy40MTMlMjAyLjc3MyUyMDQuMTA3JTIwNC41ODdsMC4yNjclMjAwLjY5M2gxLjMzM2MwLjg1MyUyMDAlMjAxLjU0NyUyMDAuNjkzJTIwMS41NDclMjAxLjU0N3YxLjcwN3olMjIlM0UlM0MlMkZwYXRoJTNFJTNDcGF0aCUyMGQlM0QlMjJNMjMuNDY3JTIwMTUuNzMzYzAlMjAwLjczNi0wLjU5NyUyMDEuMzMzLTEuMzMzJTIwMS4zMzNzLTEuMzMzLTAuNTk3LTEuMzMzLTEuMzMzYzAtMC43MzYlMjAwLjU5Ny0xLjMzMyUyMDEuMzMzLTEuMzMzczEuMzMzJTIwMC41OTclMjAxLjMzMyUyMDEuMzMzeiUyMiUzRSUzQyUyRnBhdGglM0UlM0MlMkZzdmclM0U=" service_hover="buzz_effect" service_title="Discount" service_desc="Discount for Member" el_class="margin-bottom-0"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column][nasa_product_categories list_cats="angle-grinder,clamps,drills,ratchet,No description tape-measure,utility-knife" disp_type="Horizontal7" columns_number="4" columns_number_small="2" columns_number_tablet="3" title="CATEGORIES"]
<h4 class="fs-35 margin-bottom-25 margin-top-20 tablet-fs-23" style="font-weight: 600 !important; line-height: 1.1; letter-spacing: normal;">Exploring Top  Categories</h4>
<p class="fs-14 padding-bottom-15">Hand Tools Store are renowned for being the first to showcase new gadgets and devices[/nasa_product_categories][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column width="1/3"][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_8 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 none-weight" style="line-height: 1.2; color: #333333;"><span class="nasa-bold">PowerMax </span>Pipe
Threader</div>
<div class="fs-18 margin-top-10 none-weight nasa-flex" style="color: #333333; gap: 8px;">Sale off<span class="nasa-bold fs-25 primary-color" style="color: #fcb802;">30%</span></div>
<a class="button margin-top-15 fs-14 force-radius-5 hide-for-medium" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_9 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0"]

<p class="fs-14 margin-top-0 margin-bottom-0 primary-color tablet-margin-bottom-10 primary-color" style="letter-spacing: 1px; color: #fcb802;">Weekend Discount</p>

<h4 class="fs-25" style="line-heigth: 1.6;">Compact
Dust Extractor</h4>
<a class="small fs-14 tablet-fs-11 force-radius-5" title="Shop now" href="#">Shop now</a>[/nasa_banner_2][/vc_column][vc_column width="1/3"][nasa_banner_2 valign="middle" effect_text="fadeInUp" hover="zoom" img_src="' . $imgs_10 . '" el_class="nasa-over-hide force-radius-10 margin-bottom-0"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2; color: #333333;"><span style="color: #fcb802;">Builder</span></div>
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2; color: #333333;">Portable Boxes</div>
<a class="button margin-top-15 fs-14 force-radius-5 hide-for-medium" style="text-transform: capitalize; height: 40px; letter-spacing: 0; padding: 0 15px;" title="Shop now" href="#">Shop Now</a>[/nasa_banner_2][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column][vc_tta_tabs alignment="right" tabs_display_type="2d-has-bg" tabs_bg_color="" el_class="nasa-hand-tool-product-tabs" title="Featured Products"][vc_tta_section title="New Arrivals" tab_id="1739954138099-94cbad09-65e4"][nasa_products number="10" columns_number="5" columns_number_tablet="3" cat="" not_in="24016"][/vc_tta_section][vc_tta_section title="Best Sellers" tab_id="1739954138117-01de3367-c178"][nasa_products type="best_selling" number="10" columns_number="5" columns_number_tablet="3" cat="" not_in="24016"][/vc_tta_section][vc_tta_section title="On Sales" tab_id="1739954206052-bbe6985a-ea30"][nasa_products number="10" columns_number="5" columns_number_tablet="3" cat="" not_in="24016"][/vc_tta_section][/vc_tta_tabs][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column][nasa_banner_2 valign="middle" hover="zoom" img_src="' . $imgs_11 . '"]
<div class="fs-25 tablet-fs-20 mobile-fs-20 nasa-bold" style="line-height: 1.2;">Recommended <span class="primary-color">Service</span></div>
<p class="fs-18 nasa-bold hide-for-mobile">Suggested Hand Tools Ecommerce store</p>
[/nasa_banner_2][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column][nasa_products_special_deal limit="5" cat="" style="multi-2" arrows="1"][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35"][vc_column width="1/3"][nasa_title title_text="Top Rated" title_type="h4" font_size="m" el_class="margin-bottom-20"][nasa_products style="list" number="3" columns_number="1" columns_number_tablet="1" columns_number_small="1"][/vc_column][vc_column width="1/3"][nasa_title title_text="Best Selling" title_type="h4" font_size="m" el_class="margin-bottom-20"][nasa_products style="list" number="3" columns_number="1" columns_number_tablet="1" columns_number_small="1"][/vc_column][vc_column width="1/3"][nasa_title title_text="On Sale" title_type="h4" font_size="m" el_class="margin-bottom-20"][nasa_products style="list" number="3" columns_number="1" columns_number_tablet="1" columns_number_small="1"][/vc_column][/vc_row][vc_row el_class="margin-top-50 mobile-margin-top-35 margin-bottom-50 mobile-margin-bottom-35"][vc_column][nasa_post show_type="list_2" posts="6" cats_enable="no" author_enable="no" page_blogs="no"][/vc_column][/vc_row]'
        ),
        
        'post_meta' => array(
            '_wpb_shortcodes_custom_css' => ''
        ),
        
        'globals' => array(
            'header-type' => '8',
            'plus_wide_width' => '200',
            'color_primary' => '#fcb802',
            'button_text_color' => '#000000',
            'loop_layout_buttons' => 'modern-9',

            'footer_mode' => 'builder',
            'footer-type' => 'wpb-footer-hand-tool',

            'type_font_select' => 'google',
            'type_headings' => 'Work Sans',
            'type_texts' => 'Work Sans',
            'type_nav' => 'Work Sans',
            'type_banner' => 'Work Sans',
            'type_price' => 'Work Sans',

            'header-block-type_4' => 'wpb-hand-tool-block-beside-header',
            'nasa_popup_static_block' => 'wpb-drone-popup-block',

            'bg_color_header' => '#fcb802',
            'text_color_header' => '#000000',
            'text_color_hover_header' => '#ffffff',

            'bg_color_topbar' => '#fcb802',
            'text_color_topbar' => '#000000',
            'text_color_hover_topbar' => '#ffffff',

            'text_color_v_menu' => '#000000',

            'vertical_menu_float'=> '1',
            'vertical_menu_float_selected' => '194',
        ),
    );
}
