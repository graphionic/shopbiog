<?php
function nasa_elm_book()
{
    $placeholder_src = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : 'https://dummyimage.com/1200x800?text=1200x800';

    $imgs_1 = elessi_import_upload('/elementor/wp-content/uploads/2025/04/book-store-banner-3-3.jpg', '3105', array(
        'post_title' => 'book-store-banner-3-3.jpg',
        'post_name' => 'book-store-banner-3-3.jpg',
    ));
    $imgs_1_src = $imgs_1 ? wp_get_attachment_image_url($imgs_1, 'full') : 'https://dummyimage.com/1020x732?text=1020x732';

    $imgs_2 = elessi_import_upload('/elementor/wp-content/uploads/2025/04/book-store-banner-2-1.jpg', '3105', array(
        'post_title' => 'book-store-banner-2-1.jpg',
        'post_name' => 'book-store-banner-2-1.jpg',
    ));
    $imgs_2_src = $imgs_2 ? wp_get_attachment_image_url($imgs_2, 'full') : 'https://dummyimage.com/1020x732?text=1020x732';

    $imgs_3 = elessi_import_upload('/elementor/wp-content/uploads/2025/04/book-store-banner-1-5.jpg', '3105', array(
        'post_title' => 'book-store-banner-1-5.jpg',
        'post_name' => 'book-store-banner-1-5.jpg',
    ));
    $imgs_3_src = $imgs_3 ? wp_get_attachment_image_url($imgs_3, 'full') : 'https://dummyimage.com/1020x732?text=1020x732';

    $imgs_4 = elessi_import_upload('/elementor/wp-content/uploads/2025/03/book-store-banner-4.jpg', '3105', array(
        'post_title' => 'book-store-banner-4.jpg',
        'post_name' => 'book-store-banner-4.jpg',
    ));
    $imgs_4_src = $imgs_4 ? wp_get_attachment_image_url($imgs_4, 'full') : 'https://dummyimage.com/315x732?text=315x732';

    $imgs_5 = elessi_import_upload('/elementor/wp-content/uploads/2025/03/book-store-banner-5.jpg', '3105', array(
        'post_title' => 'book-store-banner-5.jpg',
        'post_name' => 'book-store-banner-5.jpg',
    ));
    $imgs_5_src = $imgs_5 ? wp_get_attachment_image_url($imgs_5, 'full') : 'https://dummyimage.com/671x351?text=671x351';

    $imgs_6 = elessi_import_upload('/elementor/wp-content/uploads/2025/03/book-store-banner-6.jpg', '3105', array(
        'post_title' => 'book-store-banner-6.jpg',
        'post_name' => 'book-store-banner-6.jpg',
    ));
    $imgs_6_src = $imgs_6 ? wp_get_attachment_image_url($imgs_6, 'full') : 'https://dummyimage.com/671x351?text=671x351';

    $imgs_7 = elessi_import_upload('/elementor/wp-content/uploads/2025/03/book-store-cats-background-scaled-1.jpg', '3105', array(
        'post_title' => 'book-store-cats-background-scaled-1.jpg',
        'post_name' => 'book-store-cats-background-scaled-1.jpg',
    ));
    $imgs_7_src = $imgs_7 ? wp_get_attachment_image_url($imgs_7, 'full') : 'https://dummyimage.com/2560x438?text=2560x438';

    $imgs_8 = elessi_import_upload('/elementor/wp-content/uploads/2025/03/book-store-preview-1.png', '3105', array(
        'post_title' => 'book-store-preview-1.png',
        'post_name' => 'book-store-preview-1.png',
    ));
    $imgs_8_src = $imgs_8 ? wp_get_attachment_image_url($imgs_8, 'full') : 'https://dummyimage.com/761x618?text=761x618';

    return array(
        'post' => array(
            'post_title' => 'ELM Book',
            'post_name' => 'elm-book',
        ),
        'post_meta' => array(
            '_elementor_data' => '[{
        "id": "6e0b6d50",
        "settings": {
            "structure": "32"
        },
        "elements": [
            {
                "id": "586d3a8",
                "settings": {
                    "_column_size": 50,
                    "_inline_size": null,
                    "css_classes": "nasa-nutrition-small-banner-wrap"
                },
                "elements": [
                    {
                        "id": "56f5783d",
                        "settings": {
                            "wp": {
                                "title": "",
                                "align": "left",
                                "bullets": "true",
                                "bullets_pos": "inside",
                                "bullets_style": "default",
                                "bullets_align": "left",
                                "navigation": "true",
                                "column_number": "1",
                                "column_number_small": "1",
                                "column_number_tablet": "1",
                                "gap_items": "no",
                                "padding_item": "",
                                "padding_item_small": "",
                                "padding_item_medium": "",
                                "force": "false",
                                "autoplay": "false",
                                "loop_slide": "false",
                                "paginationspeed": "800",
                                "effect_silde_dismis_reload": "false",
                                "el_class": "force-radius-10 nasa-over-hide",
                                "sliders": {
                                    "1741862015078": {
                                        "img_src": "' . $imgs_1 . '",
                                        "link": "",
                                        "content_width": "",
                                        "align": "left",
                                        "move_x": "",
                                        "valign": "top",
                                        "text_align": "text-left",
                                        "content": "<p class=\"fs-18 margin-bottom-0\">RECOMMENDED SERIES<\/p>\r\n<h3 class=\"hide-for-mobile fs-30\" style=\"line-height:1.2;\"> FALL FOR <br> <span class=\"primary-color\">YOUNG LOVE<\/span>\r\n<\/h3>\r\n<p class=\"\">\r\n    Embrace the beauty of youth, where love <br> and adventure paint the journey ahead\r\n<\/p>\r\n<a class=\"button\">Shop now<\/a>",
                                        "effect_text": "fadeInUp",
                                        "data_delay": "",
                                        "hover": "zoom",
                                        "border_inner": "no",
                                        "border_outner": "no",
                                        "hide_in_m": "",
                                        "el_class": ""
                                    },
                                    "1741862032580": {
                                        "img_src": "' . $imgs_2 . '",
                                        "link": "",
                                        "content_width": "",
                                        "align": "left",
                                        "move_x": "",
                                        "valign": "top",
                                        "text_align": "text-left",
                                        "content": "<p class=\"fs-18 margin-bottom-0\">BIG DEALS<\/p>\r\n<h3 class=\"hide-for-mobile fs-30\" style=\"line-height:1.2;\"> BOOK DAY <br> <span class=\"primary-color\">SUPPER SALES<\/span>\r\n<\/h3>\r\n<p class=\"\">\r\nCelebrate the joy of reading with exclusive <br>\u2028discounts on bestsellers and new arrivals\r\n<\/p>\r\n<a class=\"button\">Shop now<\/a>",
                                        "effect_text": "fadeInUp",
                                        "data_delay": "",
                                        "hover": "zoom",
                                        "border_inner": "no",
                                        "border_outner": "no",
                                        "hide_in_m": "",
                                        "el_class": ""
                                    },
                                    "1741862040259": {
                                        "img_src": "' . $imgs_3 . '",
                                        "link": "",
                                        "content_width": "",
                                        "align": "left",
                                        "move_x": "",
                                        "valign": "top",
                                        "text_align": "text-left",
                                        "content": "<p class=\"fs-18 margin-bottom-0\">DISCOVER GREAT BOOKS<\/p>\r\n<h3 class=\"hide-for-mobile fs-30\" style=\"line-height:1.2;\"> COMBO <span class=\"primary-color\">GREAT READ<\/span>\r\n<\/h3>\r\n<p class=\"\">\r\n    Uncover stories of amazing booksf youth, where love <br> where every page opens a new world\r\n<\/p>\r\n<a class=\"button\">Shop now<\/a>",
                                        "effect_text": "fadeInUp",
                                        "data_delay": "",
                                        "hover": "zoom",
                                        "border_inner": "no",
                                        "border_outner": "no",
                                        "hide_in_m": "",
                                        "el_class": ""
                                    }
                                }
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "wp-widget-nasa_sliders_2_sc",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "58396e97",
                "settings": {
                    "_column_size": 25,
                    "_inline_size": 16.6666,
                    "css_classes": "nasa-nutrition-small-banner-wrap hide-for-mobile"
                },
                "elements": [
                    {
                        "id": "3bf6c572",
                        "settings": {
                            "img_src": {
                                "id": ' . $imgs_4 . ',
                                "url": ' . json_encode($imgs_4_src) . '
                            },
                            "el_class": "force-radius-10 nasa-over-hide",
                            "align": "center",
                            "text_align": "text-center",
                            "content_banner": "<p class=\"fs-15 margin-bottom-0\">SPECIAL OFFERS<\/p><h3 class=\"fs-25 nasa-bold primary-color margin-top-0 margin-bottom-20\" style=\"line-height: 1.2;\">SUPER SALE<\/h3><p><a class=\"button fs-10 ns-book-height-auto padding-top-10 padding-bottom-10 padding-left-15 padding-right-15\">Shop now<\/a><\/p>",
                            "effect_text": "fadeInUp",
                            "hover": "zoom"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-banner-v2",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "1195bfce",
                "settings": {
                    "_column_size": 25,
                    "_inline_size": 33.333,
                    "css_classes": "nasa-nutrition-small-banner-wrap",
                    "padding_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "10",
                        "bottom": "0",
                        "left": "10",
                        "isLinked": false
                    }
                },
                "elements": [
                    {
                        "id": "6c8e8d5b",
                        "settings": {
                            "img_src": {
                                "id": ' . $imgs_5 . ',
                                "url": ' . json_encode($imgs_5_src) . '
                            },
                            "el_class": "force-radius-10 nasa-over-hide",
                            "content_banner": "<p class=\"fs-15 margin-bottom-0\">EXCLUSIVE DEALS<\/p>\n\n<h3 class=\"fs-25 nasa-bold primary-color margin-top-0\" style=\"line-height: 1.2;\">BIG BOOK\nFAIR<\/h3>\n<a class=\"fs-14 nasa-flex ns-ht-shop-now padding-left-0\" title=\"Shop Now\" href=\"#\"><span class=\"margin-right-5 rtl-margin-right-0 rtl-margin-left-5\">Shop<\/span>now<\/a>",
                            "_css_classes": "margin-bottom-0",
                            "effect_text": "fadeInUp",
                            "hover": "zoom"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-banner-v2",
                        "elType": "widget"
                    },
                    {
                        "id": "26784b2b",
                        "settings": {
                            "img_src": {
                                "id": ' . $imgs_6 . ',
                                "url": ' . json_encode($imgs_6_src) . '
                            },
                            "el_class": "force-radius-10 nasa-over-hide",
                            "content_banner": "<p class=\"fs-15 margin-bottom-0\">LIMITED OFFERS<\/p><h3 class=\"fs-25 nasa-bold primary-color margin-top-0\" style=\"line-height: 1.2;\">READING<br \/>FEST<\/h3><p><a class=\"fs-14 nasa-flex ns-ht-shop-now padding-left-0\" title=\"Shop Now\" href=\"#\"><span class=\"margin-right-5 rtl-margin-right-0 rtl-margin-left-5\">Shop<\/span>now<br \/><\/a><\/p>",
                            "effect_text": "fadeInUp",
                            "hover": "zoom"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-banner-v2",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "444a613a",
        "settings": {
            "structure": "50",
            "margin": {
                "unit": "px",
                "top": "20",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "35",
                "left": 0,
                "isLinked": true
            }
        },
        "elements": [
            {
                "id": "6a5ff43e",
                "settings": {
                    "_column_size": 20,
                    "_inline_size": null,
                    "padding_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "10",
                        "bottom": "0",
                        "left": "10",
                        "isLinked": false
                    },
                    "_inline_size_tablet": 50
                },
                "elements": [
                    {
                        "id": "70dbfa6b",
                        "settings": {
                            "service_title": "Discount",
                            "service_desc": "Discount for Member",
                            "service_html": "<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"50\" height=\"36\" viewBox=\"0 0 36 36\" fill=\"none\">\n<path d=\"M5.98196 21.981L3.69855 19.6976C2.76715 18.7662 2.76715 17.2339 3.69855 16.3025L5.98196 14.019C6.37255 13.6284 6.68802 12.8623 6.68802 12.3215V9.09159C6.68802 7.76961 7.76964 6.68802 9.09163 6.68802H12.3215C12.8623 6.68802 13.6284 6.37259 14.019 5.982L16.3024 3.69855C17.2338 2.76715 18.7661 2.76715 19.6975 3.69855L21.981 5.982C22.3716 6.37259 23.1377 6.68802 23.6785 6.68802H26.9084C28.2303 6.68802 29.312 7.76961 29.312 9.09159V12.3215C29.312 12.8623 29.6274 13.6284 30.018 14.019L32.3015 16.3025C33.2328 17.2339 33.2328 18.7662 32.3015 19.6976L30.018 21.981C29.6274 22.3716 29.312 23.1377 29.312 23.6785V26.9082C29.312 28.2302 28.2303 29.312 26.9084 29.312H23.6785C23.1377 29.312 22.3716 29.6274 21.981 30.018L19.6975 32.3015C18.7661 33.2328 17.2338 33.2328 16.3024 32.3015L14.019 30.018C13.6284 29.6274 12.8623 29.312 12.3215 29.312H9.09163C7.76964 29.312 6.68802 28.2302 6.68802 26.9082V23.6785C6.68802 23.1227 6.37255 22.3565 5.98196 21.981Z\" stroke=\"#027735\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<path d=\"M13 23L23 13\" stroke=\"#027735\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<path d=\"M22.1582 22.1667H22.1743\" stroke=\"#027735\" stroke-width=\"3\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<path d=\"M13.8242 13.8333H13.8403\" stroke=\"#027735\" stroke-width=\"3\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<\/svg>",
                            "service_hover": "buzz_effect",
                            "el_class": "margin-bottom-0",
                            "_padding": {
                                "unit": "px",
                                "top": "10",
                                "right": "10",
                                "bottom": "10",
                                "left": "10",
                                "isLinked": true
                            },
                            "_border_border": "solid",
                            "_border_width": {
                                "unit": "px",
                                "top": "1",
                                "right": "1",
                                "bottom": "1",
                                "left": "1",
                                "isLinked": true
                            },
                            "_border_color": "#EFEFEF",
                            "_border_radius": {
                                "unit": "px",
                                "top": "5",
                                "right": "5",
                                "bottom": "5",
                                "left": "5",
                                "isLinked": true
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-service-box",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "4305dbb8",
                "settings": {
                    "_column_size": 20,
                    "_inline_size": null,
                    "_inline_size_tablet": 50
                },
                "elements": [
                    {
                        "id": "4efbed2e",
                        "settings": {
                            "service_title": "Franchise",
                            "service_desc": "Open your new store ",
                            "service_html": "<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"50\" height=\"36\" viewBox=\"0 0 36 36\" fill=\"none\">\n<path d=\"M23.179 21.5V27.8C23.179 29.2702 23.179 30.0052 22.8967 30.5667C22.6485 31.0606 22.2523 31.4622 21.7651 31.7139C21.2111 32 20.4861 32 19.0358 32H10.0589C8.60868 32 7.88355 32 7.32962 31.7139C6.84238 31.4622 6.44623 31.0606 6.19796 30.5667C5.91572 30.0052 5.91572 29.2702 5.91572 27.8V14.5M30.0843 14.5V32M5.91572 25H23.179M6.87862 5.5478L3.46242 12.4739C3.13152 13.1448 2.96607 13.4802 3.00581 13.7529C3.04051 13.991 3.17043 14.2041 3.36499 14.342C3.58781 14.5 3.95776 14.5 4.69768 14.5H31.3024C32.0423 14.5 32.4123 14.5 32.6351 14.342C32.8297 14.2041 32.9595 13.991 32.9942 13.7529C33.0339 13.4802 32.8685 13.1448 32.5376 12.4739L29.1214 5.5478C28.8443 4.98595 28.7057 4.70502 28.499 4.49977C28.3162 4.31827 28.0959 4.18023 27.8541 4.09564C27.5804 4 27.2706 4 26.651 4H9.34915C8.72947 4 8.41963 4 8.14609 4.09564C7.90418 4.18023 7.68385 4.31827 7.50105 4.49977C7.29432 4.70502 7.15576 4.98595 6.87862 5.5478Z\" stroke=\"#027735\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<\/svg>",
                            "service_hover": "buzz_effect",
                            "el_class": "margin-bottom-0",
                            "_padding": {
                                "unit": "px",
                                "top": "10",
                                "right": "10",
                                "bottom": "10",
                                "left": "10",
                                "isLinked": true
                            },
                            "_border_border": "solid",
                            "_border_width": {
                                "unit": "px",
                                "top": "1",
                                "right": "1",
                                "bottom": "1",
                                "left": "1",
                                "isLinked": true
                            },
                            "_border_color": "#EFEFEF",
                            "_border_radius": {
                                "unit": "px",
                                "top": "5",
                                "right": "5",
                                "bottom": "5",
                                "left": "5",
                                "isLinked": true
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-service-box",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "44ee5cd5",
                "settings": {
                    "_column_size": 20,
                    "_inline_size": null,
                    "padding_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "10",
                        "bottom": "0",
                        "left": "10",
                        "isLinked": false
                    },
                    "_inline_size_tablet": 50
                },
                "elements": [
                    {
                        "id": "76a12c67",
                        "settings": {
                            "service_title": "Free Shipping",
                            "service_desc": "For order over $200",
                            "service_html": "<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"50\" height=\"40\" viewBox=\"0 0 36 36\" fill=\"none\">\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M2.5845 24.4L2.57711 7.53387L19.9248 7.53333V24.4H11.2512V25.9333H23.8681V24.4H21.5025V7.53387C21.5025 6.69291 20.7946 6 19.9248 6H2.57766C1.7127 6 1 6.68818 1 7.53387V24.3995C1 25.2419 1.71244 25.9333 2.5845 25.9333H4.94278V24.4H2.5845Z\" fill=\"#027735\"\/>\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M27.8155 10.6C27.9728 10.6 28.2469 10.7506 28.3276 10.8814L32.2522 17.2408C32.4039 17.4866 32.5432 17.973 32.5432 18.2631V24.4035C32.5432 24.4083 30.1775 24.4 30.1775 24.4V25.9333H32.5358C33.4237 25.9333 34.1203 25.2543 34.1203 24.4035V18.2631C34.1203 17.6943 33.9029 16.9354 33.6045 16.4519L29.68 10.0925C29.314 9.49944 28.5264 9.06665 27.8155 9.06665H20.7148V10.6H27.8155Z\" fill=\"#027735\"\/>\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M26.2261 12.1334C25.3594 12.1334 24.6562 12.8059 24.6562 13.6521V18.2813C24.6562 19.1249 25.3531 19.8 26.2297 19.8H33.3304V18.2667H26.2297C26.2229 18.2667 26.2334 13.6521 26.2334 13.6521C26.2334 13.6584 30.1761 13.6667 30.1761 13.6667V12.1334H26.2261Z\" fill=\"#027735\"\/>\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.09903 29C10.2766 29 12.0418 27.2838 12.0418 25.1667C12.0418 23.0496 10.2766 21.3334 8.09903 21.3334C5.92149 21.3334 4.15625 23.0496 4.15625 25.1667C4.15625 27.2838 5.92149 29 8.09903 29ZM8.09903 27.4667C9.40556 27.4667 10.4647 26.4369 10.4647 25.1667C10.4647 23.8964 9.40556 22.8667 8.09903 22.8667C6.79251 22.8667 5.73336 23.8964 5.73336 25.1667C5.73336 26.4369 6.79251 27.4667 8.09903 27.4667Z\" fill=\"#027735\"\/>\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M27.0229 29C29.2004 29 30.9656 27.2838 30.9656 25.1667C30.9656 23.0496 29.2004 21.3334 27.0229 21.3334C24.8453 21.3334 23.0801 23.0496 23.0801 25.1667C23.0801 27.2838 24.8453 29 27.0229 29ZM27.0229 27.4667C28.3294 27.4667 29.3885 26.4369 29.3885 25.1667C29.3885 23.8964 28.3294 22.8667 27.0229 22.8667C25.7163 22.8667 24.6572 23.8964 24.6572 25.1667C24.6572 26.4369 25.7163 27.4667 27.0229 27.4667Z\" fill=\"#027735\"\/>\n<\/svg>",
                            "service_hover": "buzz_effect",
                            "el_class": "margin-bottom-0",
                            "_padding": {
                                "unit": "px",
                                "top": "10",
                                "right": "10",
                                "bottom": "10",
                                "left": "10",
                                "isLinked": true
                            },
                            "_border_border": "solid",
                            "_border_width": {
                                "unit": "px",
                                "top": "1",
                                "right": "1",
                                "bottom": "1",
                                "left": "1",
                                "isLinked": true
                            },
                            "_border_color": "#EFEFEF",
                            "_border_radius": {
                                "unit": "px",
                                "top": "5",
                                "right": "5",
                                "bottom": "5",
                                "left": "5",
                                "isLinked": true
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-service-box",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "59941809",
                "settings": {
                    "_column_size": 20,
                    "_inline_size": null,
                    "_inline_size_tablet": 50
                },
                "elements": [
                    {
                        "id": "3ab37faa",
                        "settings": {
                            "service_title": "Insurance",
                            "service_desc": "For all order over $200",
                            "service_html": "<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"50\" height=\"30\" viewBox=\"3 3 30 30\" fill=\"none\">\n<path d=\"M13.2738 18.0017L16.424 21.1518L22.7243 14.8515M30.5996 18.0017C30.5996 25.0282 21.9997 30.1198 19.0093 31.6781C18.6859 31.8466 18.5242 31.9309 18.2999 31.9745C18.125 32.0085 17.873 32.0085 17.6982 31.9745C17.4739 31.9309 17.3121 31.8466 16.9888 31.6781C13.9983 30.1198 5.39844 25.0282 5.39844 18.0017V12.0441C5.39844 10.7848 5.39844 10.1552 5.60439 9.61393C5.78633 9.13578 6.08199 8.70916 6.4658 8.37091C6.90027 7.98802 7.48982 7.76695 8.66892 7.32477L17.1142 4.15782C17.4416 4.03503 17.6053 3.97363 17.7738 3.94928C17.9231 3.9277 18.075 3.9277 18.2243 3.94928C18.3928 3.97363 18.5565 4.03503 18.8839 4.15782L27.3292 7.32477C28.5083 7.76695 29.0978 7.98802 29.5322 8.37091C29.9161 8.70916 30.2117 9.13578 30.3936 9.61393C30.5996 10.1552 30.5996 10.7848 30.5996 12.0441V18.0017Z\" stroke=\"#027735\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"\/>\n<\/svg>",
                            "service_hover": "buzz_effect",
                            "el_class": "margin-bottom-0",
                            "_padding": {
                                "unit": "px",
                                "top": "10",
                                "right": "10",
                                "bottom": "10",
                                "left": "10",
                                "isLinked": true
                            },
                            "_border_border": "solid",
                            "_border_width": {
                                "unit": "px",
                                "top": "1",
                                "right": "1",
                                "bottom": "1",
                                "left": "1",
                                "isLinked": true
                            },
                            "_border_color": "#EFEFEF",
                            "_border_radius": {
                                "unit": "px",
                                "top": "5",
                                "right": "5",
                                "bottom": "5",
                                "left": "5",
                                "isLinked": true
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-service-box",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "2010b6b4",
                "settings": {
                    "_column_size": 20,
                    "_inline_size": null,
                    "padding_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "10",
                        "bottom": "0",
                        "left": "10",
                        "isLinked": false
                    },
                    "css_classes": "hide-for-medium"
                },
                "elements": [
                    {
                        "id": "5703886",
                        "settings": {
                            "service_title": "Secure Payment",
                            "service_desc": "100% Secure Payment",
                            "service_html": "<svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"50\" height=\"34\" viewBox=\"0 0 30 30\" fill=\"none\">\n<path d=\"M2.25 6C1.00736 6 0 7.00736 0 8.25V23.25C0 24.4926 1.00736 25.5 2.25 25.5H27.75C28.9926 25.5 30 24.4926 30 23.25V8.25C30 7.00736 28.9926 6 27.75 6H2.25ZM2.19727 7.5C2.21485 7.49938 2.23242 7.49938 2.25 7.5H27.75C28.1642 7.5 28.5 7.83579 28.5 8.25V9H1.5V8.25C1.49902 7.85554 1.80378 7.52773 2.19727 7.5ZM1.5 10.5H28.5V13.4941H1.5V10.5ZM1.5 15H28.5V23.25C28.5 23.6642 28.1642 24 27.75 24H2.25C1.83579 24 1.5 23.6642 1.5 23.25V15ZM3 16.5V18H19.5V16.5H3Z\" fill=\"#027735\"\/>\n<\/svg>",
                            "service_hover": "buzz_effect",
                            "el_class": "margin-bottom-0",
                            "_padding": {
                                "unit": "px",
                                "top": "10",
                                "right": "10",
                                "bottom": "10",
                                "left": "10",
                                "isLinked": true
                            },
                            "_border_border": "solid",
                            "_border_width": {
                                "unit": "px",
                                "top": "1",
                                "right": "1",
                                "bottom": "1",
                                "left": "1",
                                "isLinked": true
                            },
                            "_border_color": "#EFEFEF",
                            "_border_radius": {
                                "unit": "px",
                                "top": "5",
                                "right": "5",
                                "bottom": "5",
                                "left": "5",
                                "isLinked": true
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-service-box",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "40d4e5ea",
        "settings": {
            "background_background": "classic",
            "background_image": {
                "id":' . $imgs_7 . ',
                "url": ' . json_encode($imgs_7_src) . '
            },
            "css_classes": "ns-hafl-bg",
            "margin": {
                "unit": "px",
                "top": "30",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "f72961a",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "26beb78d",
                        "settings": {
                            "title": "Popular Categories",
                            "_margin": {
                                "unit": "px",
                                "top": "30",
                                "right": "0",
                                "bottom": "20",
                                "left": "0",
                                "isLinked": false
                            },
                            "typography_typography": "custom",
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            },
                            "typography_font_size": {
                                "unit": "px",
                                "size": 29,
                                "sizes": []
                            },
                            "_css_classes": "margin-bottom-0",
                            "_margin_mobile": {
                                "unit": "px",
                                "top": "20",
                                "right": "0",
                                "bottom": "12",
                                "left": "0",
                                "isLinked": false
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "2209b433",
                        "settings": {
                            "list_cats": "",
                            "columns_number": "6",
                            "el_class": "items-padding-40"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-product-categories",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "68cb58e7",
        "settings": {
            "layout": "full_width",
            "background_background": "classic",
            "background_color": "#F9F5F0",
            "margin": {
                "unit": "px",
                "top": "050",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "2290707a",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "6cdf74e8",
                        "settings": {
                            "html": "<div class=\"nasa-custom-animate\">\r\n    <div style=\"gap: 30px;\" class=\"nasa-flex infinities-slide\">\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">20,898<\/span>books sold<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">97%<\/span>happy customer<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">15,254<\/span>total books<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">1258<\/span>author<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">20,898<\/span>books sold<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">97%<\/span>happy customer<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">15,254<\/span>total books<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">1258<\/span>author<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n    <\/div>\r\n    <div style=\"gap: 30px;\" class=\"nasa-flex  infinities-slide\">\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">20,898<\/span>books sold<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">97%<\/span>happy customer<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">15,254<\/span>total books<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">1258<\/span>author<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">20,898<\/span>books sold<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">97%<\/span>happy customer<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">15,254<\/span>total books<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n        <p class=\"margin-bottom-0 text-center  fs-20\"><span\r\n                class=\"primary-color padding-right-5 padding-left-5\">1258<\/span>author<\/p>\r\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"18\" height=\"18\" viewBox=\"0 0 18 18\" fill=\"none\">\r\n            <path\r\n                d=\"M8.41142 0.973424C8.53903 0.328767 9.46097 0.328765 9.58858 0.973422L10.2986 4.56042C10.3817 4.98036 10.8653 5.18065 11.221 4.94248L14.2595 2.90817C14.8055 2.54256 15.4574 3.19447 15.0918 3.74054L13.0575 6.779C12.8194 7.13472 13.0196 7.61826 13.4396 7.70139L17.0266 8.41142C17.6712 8.53903 17.6712 9.46097 17.0266 9.58858L13.4396 10.2986C13.0196 10.3817 12.8194 10.8653 13.0575 11.221L15.0918 14.2595C15.4574 14.8055 14.8055 15.4574 14.2595 15.0918L11.221 13.0575C10.8653 12.8194 10.3817 13.0196 10.2986 13.4396L9.58858 17.0266C9.46097 17.6712 8.53903 17.6712 8.41142 17.0266L7.70139 13.4396C7.61826 13.0196 7.13472 12.8194 6.779 13.0575L3.74054 15.0918C3.19447 15.4574 2.54256 14.8055 2.90816 14.2595L4.94248 11.221C5.18065 10.8653 4.98036 10.3817 4.56042 10.2986L0.973424 9.58858C0.328767 9.46097 0.328765 8.53903 0.973422 8.41142L4.56042 7.70139C4.98036 7.61826 5.18065 7.13472 4.94248 6.779L2.90816 3.74054C2.54256 3.19447 3.19447 2.54256 3.74054 2.90816L6.779 4.94248C7.13472 5.18065 7.61826 4.98036 7.70139 4.56042L8.41142 0.973424Z\"\r\n                fill=\"#E2BB82\" \/>\r\n        <\/svg>\r\n    <\/div>\r\n<\/div>"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "html",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "43a94e6d",
        "settings": {
            "margin": {
                "unit": "px",
                "top": "050",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "2523f71a",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "3a532616",
                        "settings": {
                            "wp": {
                                "title": "Featured Books",
                                "title_font_size": "l",
                                "desc": "",
                                "alignment": "right",
                                "style": "2d-has-bg-none",
                                "el_class": "nasa-books-product-tabs",
                                "tabs": {
                                    "1739782301707": {
                                        "before_tab_title": "",
                                        "tab_title": "New Arrivals",
                                        "after_tab_title": "",
                                        "style": "grid",
                                        "style_viewmore": "1",
                                        "style_row": "1",
                                        "pos_nav": "top",
                                        "title_align": "left",
                                        "arrows": "1",
                                        "dots": "false",
                                        "auto_slide": "false",
                                        "loop_slide": "false",
                                        "auto_delay_time": "6",
                                        "number": "10",
                                        "columns_number": "5",
                                        "columns_number_small": "2",
                                        "columns_number_small_slider": "2",
                                        "columns_number_tablet": "3",
                                        "cat": "",
                                        "ns_tags": "",
                                        "not_in": "",
                                        "el_class": ""
                                    },
                                    "1739782313864": {
                                        "before_tab_title": "",
                                        "tab_title": "Best Sellers",
                                        "after_tab_title": "",
                                        
                                        "style": "grid",
                                        "style_viewmore": "1",
                                        "style_row": "1",
                                        "pos_nav": "top",
                                        "title_align": "left",
                                        "arrows": "1",
                                        "dots": "false",
                                        "auto_slide": "false",
                                        "loop_slide": "false",
                                        "auto_delay_time": "6",
                                        "number": "10",
                                        "columns_number": "5",
                                        "columns_number_small": "2",
                                        "columns_number_small_slider": "2",
                                        "columns_number_tablet": "3",
                                        "cat": "",
                                        "ns_tags": "",
                                        "not_in": "",
                                        "el_class": ""
                                    },
                                    "1739782328516": {
                                        "before_tab_title": "",
                                        "tab_title": "On Sales",
                                        "after_tab_title": "",
                                        
                                        "style": "grid",
                                        "style_viewmore": "1",
                                        "style_row": "1",
                                        "pos_nav": "top",
                                        "title_align": "left",
                                        "arrows": "1",
                                        "dots": "false",
                                        "auto_slide": "false",
                                        "loop_slide": "false",
                                        "auto_delay_time": "6",
                                        "number": "10",
                                        "columns_number": "5",
                                        "columns_number_small": "2",
                                        "columns_number_small_slider": "2",
                                        "columns_number_tablet": "3",
                                        "cat": "",
                                        "ns_tags": "",
                                        "not_in": "",
                                        "el_class": ""
                                    }
                                }
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "wp-widget-nasa_products_tabs_sc",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "14352ced",
        "settings": {
            "margin": {
                "unit": "px",
                "top": "40",
                "right": 0,
                "bottom": "30",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "6865fe8b",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "351ba49e",
                        "settings": {
                            "editor": "<h3 class=\"fs-32 margin-bottom-0 mobile-fs-27\" style=\"font-weight: 600 !important;\">Preview The First Page<\/h3><p><span class=\"fs-32 mobile-fs-27\" style=\"color: #e4583d; font-weight: 600 !important;\"><i>FOR FREE<i><\/i><\/i><\/span><\/p>",
                            "align": "center"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "text-editor",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "2bfca3c8",
        "settings": {
            "structure": "20"
        },
        "elements": [
            {
                "id": "2d5fd90f",
                "settings": {
                    "_column_size": 50,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "2afc9b5f",
                        "settings": {
                            "img_src": {
                                "id": ' . $imgs_8 . ',
                                "url": ' . json_encode($imgs_8_src) . '
                            },
                            "content_banner": "",
                            "_padding": {
                                "unit": "px",
                                "top": "20",
                                "right": "70",
                                "bottom": "0",
                                "left": "70",
                                "isLinked": false
                            },
                            "_padding_mobile": {
                                "unit": "px",
                                "top": "0",
                                "right": "20",
                                "bottom": "0",
                                "left": "20",
                                "isLinked": false
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-banner-v2",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "5515ee60",
                "settings": {
                    "_column_size": 50,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "168721df",
                        "settings": {
                            "tabs": [
                                {
                                    "tab_title": "Chapter 1",
                                    "tab_content": "<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged .<\/p><p>Lorem Ipsum\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged<\/p>",
                                    "_id": "c84dbbc"
                                },
                                {
                                    "tab_title": "Chapter 2",
                                    "tab_content": "<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged .<\/p><p>Lorem Ipsum\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged<\/p>",
                                    "_id": "f0241b5"
                                },
                                {
                                    "_id": "27d3e9c",
                                    "tab_title": "Chapter 3",
                                    "tab_content": "<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged .<\/p><p>Lorem Ipsum\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry`ss standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged<\/p>"
                                }
                            ],
                            "selected_icon": {
                                "value": {
                                    "id": 33645,
                                    "url": "https:\/\/elessi.nasatheme.com\/elementor\/wp-content\/uploads\/2025\/03\/more.svg"
                                },
                                "library": "svg"
                            },
                            "selected_active_icon": {
                                "value": {
                                    "id": 33646,
                                    "url": "https:\/\/elessi.nasatheme.com\/elementor\/wp-content\/uploads\/2025\/03\/less.svg"
                                },
                                "library": "svg"
                            },
                            "title_html_tag": "h4",
                            "faq_schema": "yes",
                            "space_between": {
                                "unit": "px",
                                "size": 0,
                                "sizes": []
                            },
                            "title_padding": {
                                "unit": "px",
                                "top": "20",
                                "right": "0",
                                "bottom": "20",
                                "left": "0",
                                "isLinked": false
                            },
                            "icon_align": "right",
                            "icon_space": {
                                "unit": "px",
                                "size": 0,
                                "sizes": []
                            },
                            "content_padding": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "20",
                                "left": "0",
                                "isLinked": false
                            },
                            "border_width": {
                                "unit": "px",
                                "size": 1,
                                "sizes": []
                            },
                            "_css_classes": "ns-book-preview",
                            "title_typography_typography": "custom",
                            "title_typography_font_size_mobile": {
                                "unit": "px",
                                "size": 18,
                                "sizes": []
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "toggle",
                        "elType": "widget"
                    },
                    {
                        "id": "27a954bb",
                        "settings": {
                            "html": "<div class=\"nasa-flex flex-wrap\" >\n    <a href=\"javascript:void(0);\" class=\"button nasa-flex ns-book-btn margin-right-20 rtl-margin-right-0 rtl-margin-left-20 mobile-margin-right-10 rtl-mobile-margin-right-0 rtl-mobile-margin-left-10\" style=\"gap:10px;\" rel=\"nofollow\">\n        <svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 16 16\" fill=\"none\">\n<path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M7.64663 10.854C7.69308 10.9006 7.74825 10.9375 7.809 10.9627C7.86974 10.9879 7.93486 11.0009 8.00063 11.0009C8.0664 11.0009 8.13152 10.9879 8.19227 10.9627C8.25301 10.9375 8.30819 10.9006 8.35463 10.854L10.3546 8.854C10.4011 8.80751 10.438 8.75232 10.4632 8.69158C10.4883 8.63084 10.5013 8.56574 10.5013 8.5C10.5013 8.43426 10.4883 8.36916 10.4632 8.30842C10.438 8.24768 10.4011 8.19249 10.3546 8.146C10.3081 8.09951 10.253 8.06264 10.1922 8.03748C10.1315 8.01232 10.0664 7.99937 10.0006 7.99937C9.93489 7.99937 9.86979 8.01232 9.80905 8.03748C9.74831 8.06264 9.69312 8.09951 9.64663 8.146L8.50063 9.293V5.5C8.50063 5.36739 8.44795 5.24021 8.35419 5.14645C8.26042 5.05268 8.13324 5 8.00063 5C7.86802 5 7.74085 5.05268 7.64708 5.14645C7.55331 5.24021 7.50063 5.36739 7.50063 5.5V9.293L6.35463 8.146C6.26075 8.05211 6.13341 7.99937 6.00063 7.99937C5.86786 7.99937 5.74052 8.05211 5.64663 8.146C5.55275 8.23989 5.5 8.36722 5.5 8.5C5.5 8.63278 5.55275 8.76011 5.64663 8.854L7.64663 10.854Z\" fill=\"white\"\/>\n<path d=\"M4.406 3.342C5.40548 2.48014 6.68024 2.00414 8 2C10.69 2 12.923 4 13.166 6.579C14.758 6.804 16 8.137 16 9.773C16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318C0 7.555 1.266 6.095 2.942 5.725C3.085 4.862 3.64 4.002 4.406 3.342ZM5.059 4.099C4.302 4.752 3.906 5.539 3.906 6.155V6.603L3.461 6.652C2.064 6.805 1 7.952 1 9.318C1 10.785 2.23 12 3.781 12H12.687C13.98 12 15 10.988 15 9.773C15 8.557 13.98 7.545 12.687 7.545H12.187V7.045C12.188 4.825 10.328 3 8 3C6.91988 3.00431 5.87684 3.39343 5.059 4.099Z\" fill=\"white\"\/>\n<\/svg> FREE STORY<\/a>\n    <a href=\"javascript:void(0);\" class=\"button nasa-flex ns-book-btn-view\" style=\"gap:10px; background-color: transparent; color: inherit;border-color: #cccccc;\" rel=\"nofollow\"><svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 16 16\" fill=\"none\">\n<g clip-path=\"url(#clip0_2158_678)\">\n<path d=\"M8.33267 11.9999C4.52733 11.9999 1.52467 8.03459 1.39933 7.86592L1.25 7.66659L1.39933 7.46725C1.52467 7.29859 4.52733 3.33325 8.33267 3.33325C12.138 3.33325 15.1407 7.29859 15.266 7.46725L15.4153 7.66659L15.266 7.86592C15.1407 8.03459 12.138 11.9999 8.33267 11.9999ZM2.09267 7.66659C2.746 8.45859 5.326 11.3333 8.33267 11.3333C11.3393 11.3333 13.9193 8.45859 14.5727 7.66659C13.9193 6.87459 11.3393 3.99992 8.33267 3.99992C5.326 3.99992 2.746 6.87459 2.09267 7.66659ZM8.33267 5.33325C7.87118 5.33325 7.42005 5.4701 7.03634 5.72649C6.65262 5.98288 6.35355 6.3473 6.17695 6.77366C6.00034 7.20002 5.95414 7.66917 6.04417 8.1218C6.1342 8.57442 6.35643 8.99018 6.68275 9.3165C7.00907 9.64282 7.42483 9.86505 7.87746 9.95508C8.33008 10.0451 8.79923 9.99891 9.22559 9.8223C9.65196 9.6457 10.0164 9.34663 10.2728 8.96292C10.5292 8.5792 10.666 8.12808 10.666 7.66659C10.666 7.04775 10.4202 6.45425 9.98258 6.01667C9.545 5.57908 8.95151 5.33325 8.33267 5.33325ZM8.33267 9.33325C8.00303 9.33325 7.6808 9.2355 7.40672 9.05237C7.13263 8.86923 6.91901 8.60893 6.79287 8.30439C6.66672 7.99985 6.63372 7.66474 6.69802 7.34143C6.76233 7.01813 6.92107 6.72116 7.15416 6.48807C7.38724 6.25499 7.68421 6.09625 8.00752 6.03194C8.33082 5.96763 8.66593 6.00064 8.97047 6.12679C9.27502 6.25293 9.53531 6.46655 9.71845 6.74063C9.90159 7.01472 9.99933 7.33695 9.99933 7.66659C9.9988 8.10845 9.82304 8.53207 9.51059 8.84451C9.19815 9.15696 8.77453 9.33272 8.33267 9.33325Z\" fill=\"currentColor\"\/>\n<\/g>\n<defs>\n<clipPath id=\"clip0_2158_678\">\n<rect width=\"16\" height=\"16\" fill=\"white\"\/>\n<\/clipPath>\n<\/defs>\n<\/svg>VIEW BOOK<\/a>\n<\/div>",
                            "_css_classes": "ns-group-btn-preview"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "html",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "2a925ffc",
        "settings": {
            "margin": {
                "unit": "px",
                "top": "100",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "c17807a",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "40b90058",
                        "settings": {
                            "title": "10 Top Rated Books",
                            "header_size": "h3",
                            "typography_typography": "custom",
                            "typography_font_size": {
                                "unit": "px",
                                "size": 29,
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "5ccfefd1",
                        "settings": {
                            "style": "carousel",
                            "number": 10,
                            "columns_number": "5",
                            "arrows": "1",
                            
                            "cat": ""
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-products",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "1eac1e02",
        "settings": {
            "structure": "30",
            "margin": {
                "unit": "px",
                "top": "50",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "35",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "7b9d3073",
                "settings": {
                    "_column_size": 33,
                    "_inline_size": null,
                    "margin_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "0",
                        "bottom": "20",
                        "left": "0",
                        "isLinked": false
                    }
                },
                "elements": [
                    {
                        "id": "2e7b17f1",
                        "settings": {
                            "title": "Top Rated",
                            "title_color": "#333333",
                            "typography_typography": "custom",
                            "_margin": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "_margin_mobile": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "typography_font_size": {
                                "unit": "px",
                                "size": 30,
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            },
                            "typography_font_weight": "600"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "84132fd",
                        "settings": {
                            "title_align": "right",
                            "style": "list",
                            "style_row": "3",
                            "pos_nav": "top",
                            "number": 3,
                            "columns_number": "1",
                            "columns_number_tablet": "1",
                            "columns_number_small": "1",
                            "columns_number_small_slider": "1",
                            
                            "cat": ""
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-products",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "23a9ea9c",
                "settings": {
                    "_column_size": 33,
                    "_inline_size": null,
                    "margin_mobile": {
                        "unit": "px",
                        "top": "0",
                        "right": "0",
                        "bottom": "20",
                        "left": "0",
                        "isLinked": false
                    }
                },
                "elements": [
                    {
                        "id": "5a556d9d",
                        "settings": {
                            "title": "Best Selling",
                            "header_size": "h4",
                            "title_color": "#333333",
                            "typography_typography": "custom",
                            "_margin": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "_margin_mobile": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "typography_font_size": {
                                "unit": "px",
                                "size": 30,
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            },
                            "typography_font_weight": "600"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "4a716043",
                        "settings": {
                            "title_align": "right",
                            "style": "list",
                            "style_row": "3",
                            "pos_nav": "top",
                            "number": 3,
                            "columns_number": "1",
                            "columns_number_tablet": "1",
                            "columns_number_small": "1",
                            "columns_number_small_slider": "1",
                            
                            "cat": ""
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-products",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            },
            {
                "id": "73181813",
                "settings": {
                    "_column_size": 33,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "6e9164d3",
                        "settings": {
                            "title": "On Sale",
                            "header_size": "h4",
                            "title_color": "#333333",
                            "typography_typography": "custom",
                            "_margin": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "_margin_mobile": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "5",
                                "left": "0",
                                "isLinked": false
                            },
                            "typography_font_size": {
                                "unit": "px",
                                "size": 30,
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            },
                            "typography_font_weight": "600"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "1a62e9d7",
                        "settings": {
                            "title_align": "right",
                            "style": "list",
                            "style_row": "3",
                            "pos_nav": "top",
                            "number": 3,
                            "columns_number": "1",
                            "columns_number_tablet": "1",
                            "columns_number_small": "1",
                            "columns_number_small_slider": "1",
                            "cat": ""
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-products",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "3ce0eb87",
        "settings": {
            "margin": {
                "unit": "px",
                "top": "50",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "035",
                "right": 0,
                "bottom": "0",
                "left": 0,
                "isLinked": false
            }
        },
        "elements": [
            {
                "id": "5d70ffb9",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "1eff0da1",
                        "settings": {
                            "title": "News & events",
                            "align": "center",
                            "typography_typography": "custom",
                            "typography_font_size": {
                                "unit": "px",
                                "size": 35,
                                "sizes": []
                            },
                            "typography_font_size_tablet": {
                                "unit": "custom",
                                "size": "",
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 27,
                                "sizes": []
                            },
                            "_margin": {
                                "unit": "px",
                                "top": "0",
                                "right": "0",
                                "bottom": "20",
                                "left": "0",
                                "isLinked": false
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "df3057f",
                        "settings": {
                            "cats_enable": "",
                            "author_enable": "",
                            "page_blogs": "",
                            "arrows": "1",
                            "post_category": "book"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "nasa-post",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    },
    {
        "id": "25135904",
        "settings": {
            "margin": {
                "unit": "px",
                "top": "0",
                "right": 0,
                "bottom": "50",
                "left": 0,
                "isLinked": false
            },
            "margin_mobile": {
                "unit": "px",
                "top": "",
                "right": 0,
                "bottom": "",
                "left": 0,
                "isLinked": true
            }
        },
        "elements": [
            {
                "id": "36484674",
                "settings": {
                    "_column_size": 100,
                    "_inline_size": null
                },
                "elements": [
                    {
                        "id": "6d845e4d",
                        "settings": {
                            "title": "Online bookstore for various genres and publication",
                            "typography_typography": "custom",
                            "typography_font_size": {
                                "unit": "px",
                                "size": 28,
                                "sizes": []
                            },
                            "typography_font_size_mobile": {
                                "unit": "px",
                                "size": 22,
                                "sizes": []
                            }
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "heading",
                        "elType": "widget"
                    },
                    {
                        "id": "3c7280e0",
                        "settings": {
                            "html": "\n<div class=\"jdc-r nasa-flex jst align-start\">\n    <label class=\"button read-more-book margin-top-20\" for=\"showblock\" >Read more <svg   width=\"30\" height=\"30\" viewBox=\"0 0 32 32\" fill=\"currentColor\"><path d=\"M15.233 19.175l0.754 0.754 6.035-6.035-0.754-0.754-5.281 5.281-5.256-5.256-0.754 0.754 3.013 3.013z\"><\/path><\/svg><\/label>\n<input type=\"checkbox\" id=\"showblock\" \/>\n<div id=\"block\">\n\t<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.&nbsp;<\/p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.&nbsp;<\/p>\t\t\n\t    <span class=\"read-more-book-shd\"><\/span>\n\t<\/div>\n<\/div>",
                            "_css_classes": "ns-book-genres"
                        },
                        "elements": [],
                        "isInner": false,
                        "widgetType": "html",
                        "elType": "widget"
                    }
                ],
                "isInner": false,
                "elType": "column"
            }
        ],
        "isInner": false,
        "elType": "section"
    }
]',
            '_elementor_controls_usage' => '',
            '_elementor_css' => '.elementor-[inserted_id] .elementor-element.elementor-element-13babacd{margin-top:20px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-2147fb66 > .elementor-widget-container{padding:10px 10px 10px 10px;border-style:solid;border-width:1px 1px 1px 1px;border-color:#EFEFEF;border-radius:5px 5px 5px 5px;}.elementor-[inserted_id] .elementor-element.elementor-element-20997fa2 > .elementor-widget-container{padding:10px 10px 10px 10px;border-style:solid;border-width:1px 1px 1px 1px;border-color:#EFEFEF;border-radius:5px 5px 5px 5px;}.elementor-[inserted_id] .elementor-element.elementor-element-68b8546 > .elementor-widget-container{padding:10px 10px 10px 10px;border-style:solid;border-width:1px 1px 1px 1px;border-color:#EFEFEF;border-radius:5px 5px 5px 5px;}.elementor-[inserted_id] .elementor-element.elementor-element-7a0802a2 > .elementor-widget-container{padding:10px 10px 10px 10px;border-style:solid;border-width:1px 1px 1px 1px;border-color:#EFEFEF;border-radius:5px 5px 5px 5px;}.elementor-[inserted_id] .elementor-element.elementor-element-c973cc3 > .elementor-widget-container{padding:10px 10px 10px 10px;border-style:solid;border-width:1px 1px 1px 1px;border-color:#EFEFEF;border-radius:5px 5px 5px 5px;}.elementor-[inserted_id] .elementor-element.elementor-element-4d809c35:not(.elementor-motion-effects-element-type-background), .elementor-[inserted_id] .elementor-element.elementor-element-4d809c35 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-image:url(' . $imgs_7_src . ');}.elementor-[inserted_id] .elementor-element.elementor-element-4d809c35{transition:background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;margin-top:30px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-4d809c35 > .elementor-background-overlay{transition:background 0.3s, border-radius 0.3s, opacity 0.3s;}.elementor-[inserted_id] .elementor-element.elementor-element-795050ab > .elementor-widget-container{margin:30px 0px 20px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-795050ab .elementor-heading-title{font-size:29px;}.elementor-[inserted_id] .elementor-element.elementor-element-72c4916d:not(.elementor-motion-effects-element-type-background), .elementor-[inserted_id] .elementor-element.elementor-element-72c4916d > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#F9F5F0;}.elementor-[inserted_id] .elementor-element.elementor-element-72c4916d{transition:background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;margin-top:050px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-72c4916d > .elementor-background-overlay{transition:background 0.3s, border-radius 0.3s, opacity 0.3s;}.elementor-[inserted_id] .elementor-element.elementor-element-317def48{margin-top:050px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-b5095a{margin-top:40px;margin-bottom:30px;}.elementor-[inserted_id] .elementor-element.elementor-element-4d963db1{text-align:center;}.elementor-[inserted_id] .elementor-element.elementor-element-11c68e2 > .elementor-widget-container{padding:20px 70px 0px 70px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-tab-title{border-width:1px;padding:20px 0px 20px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-tab-content{border-width:1px;padding:0px 0px 20px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-toggle-item:not(:last-child){margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-toggle-icon.elementor-toggle-icon-left{margin-right:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-toggle-icon.elementor-toggle-icon-right{margin-left:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-2d121790{margin-top:100px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-58f5379b .elementor-heading-title{font-size:29px;}.elementor-[inserted_id] .elementor-element.elementor-element-79d49c75{margin-top:50px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-51a60f2a > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-51a60f2a .elementor-heading-title{font-size:30px;font-weight:600;color:#333333;}.elementor-[inserted_id] .elementor-element.elementor-element-6371ed2b > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-6371ed2b .elementor-heading-title{font-size:30px;font-weight:600;color:#333333;}.elementor-[inserted_id] .elementor-element.elementor-element-5b891939 > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-5b891939 .elementor-heading-title{font-size:30px;font-weight:600;color:#333333;}.elementor-[inserted_id] .elementor-element.elementor-element-6b1bfbc6{margin-top:50px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-712512e4 > .elementor-widget-container{margin:0px 0px 20px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-712512e4{text-align:center;}.elementor-[inserted_id] .elementor-element.elementor-element-712512e4 .elementor-heading-title{font-size:35px;}.elementor-[inserted_id] .elementor-element.elementor-element-7604ab3f{margin-top:0px;margin-bottom:50px;}.elementor-[inserted_id] .elementor-element.elementor-element-714003e6 .elementor-heading-title{font-size:28px;}@media(max-width:767px){.elementor-[inserted_id] .elementor-element.elementor-element-349e67cc > .elementor-element-populated{padding:0px 10px 0px 10px;}.elementor-[inserted_id] .elementor-element.elementor-element-13babacd{margin-top:35px;margin-bottom:35px;}.elementor-[inserted_id] .elementor-element.elementor-element-594640e4 > .elementor-element-populated{padding:0px 10px 0px 10px;}.elementor-[inserted_id] .elementor-element.elementor-element-25ebae06 > .elementor-element-populated{padding:0px 10px 0px 10px;}.elementor-[inserted_id] .elementor-element.elementor-element-1f50af38 > .elementor-element-populated{padding:0px 10px 0px 10px;}.elementor-[inserted_id] .elementor-element.elementor-element-4d809c35{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-795050ab > .elementor-widget-container{margin:20px 0px 12px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-795050ab .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-72c4916d{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-317def48{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-b5095a{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-11c68e2 > .elementor-widget-container{padding:0px 20px 0px 20px;}.elementor-[inserted_id] .elementor-element.elementor-element-59682dcd .elementor-toggle-title{font-size:18px;}.elementor-[inserted_id] .elementor-element.elementor-element-2d121790{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-58f5379b .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-79d49c75{margin-top:35px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-67979e29 > .elementor-element-populated{margin:0px 0px 20px 0px;--e-column-margin-right:0px;--e-column-margin-left:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-51a60f2a > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-51a60f2a .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-2a22fbc6 > .elementor-element-populated{margin:0px 0px 20px 0px;--e-column-margin-right:0px;--e-column-margin-left:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-6371ed2b > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-6371ed2b .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-5b891939 > .elementor-widget-container{margin:0px 0px 5px 0px;}.elementor-[inserted_id] .elementor-element.elementor-element-5b891939 .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-6b1bfbc6{margin-top:035px;margin-bottom:0px;}.elementor-[inserted_id] .elementor-element.elementor-element-712512e4 .elementor-heading-title{font-size:27px;}.elementor-[inserted_id] .elementor-element.elementor-element-714003e6 .elementor-heading-title{font-size:22px;}}@media(min-width:768px){.elementor-[inserted_id] .elementor-element.elementor-element-1d5b0406{width:16.6666%;}.elementor-[inserted_id] .elementor-element.elementor-element-349e67cc{width:33.333%;}}@media(max-width:1024px) and (min-width:768px){.elementor-[inserted_id] .elementor-element.elementor-element-594640e4{width:50%;}.elementor-[inserted_id] .elementor-element.elementor-element-4d094da8{width:50%;}.elementor-[inserted_id] .elementor-element.elementor-element-25ebae06{width:50%;}.elementor-[inserted_id] .elementor-element.elementor-element-d39e8cc{width:50%;}}',

            '_nasa_page_css_custom_enable' => '1',
            '_nasa_page_css_custom' => '.nasa-books-product-tabs .nasa-tabs .nasa-tab>a { font-size: 160%; letter-spacing: normal; } .ns-book-btn-view:hover { border-color: #026d31 !important; color: #026d31 !important; } .service-block.style-1 .box { display: flex; align-items: center; gap: 10px; } .service-block.style-1 .service-icon { width: auto; } .ns-book-genres #block { overflow: hidden; transition: all 300ms linear; position: relative; max-height: 100px; } .ns-book-genres .read-more-book { cursor: pointer; letter-spacing: normal; text-transform: capitalize !important; background-color: #F3F3F3 !important; color: #333333 !important; border: none !important; } .ns-book-genres .read-more-book:hover { background-color: #cccccc !important; } .ns-book-genres .read-more-book-shd { position: absolute; left: 0; bottom: 0; width: 100%; box-shadow: 0 0 30px 30px #fff; } .ns-book-genres #showblock { display: none; } .ns-book-genres #showblock:checked+#block { max-height: 2000px; } .ns-book-genres #showblock:checked+#block .read-more-book-shd { opacity: 0; visibility: hidden; } .ns-book-genres:has(#showblock:checked) label.read-more-book svg{ transform: rotate(180deg); } .nasa-blog-carousel .blog-image-attachment img { border-radius: 15px; } .product_list_widget .nasa-item-img .attachment-thumbnail { border-radius: 5px; } .ns-hafl-bg { background-size: 100% 70%; background-repeat: no-repeat; } .ns-book-height-auto { height: auto; } .ns-ht-shop-now>span { position: relative; } .ns-ht-shop-now>span::after { content: \'\'; width: 100%; height: 1px; background-color: #027735; position: absolute; left: 0; bottom: -2px; -webkit-transition: all 350ms ease; -moz-transition: all 350ms ease; -o-transition: all 350ms ease; transition: all 350ms ease; } .ns-ht-shop-now:hover>span::after { left: 100%; } .nasa-category-horizontal-4 .nasa-cat-title { font-size: 150%; } .nasa-category-horizontal-4 .nasa-cat-thumb { padding-top: 20px; } .nasa-category-horizontal-4 .nasa-cat-link:hover img { transform: translateY(-10px); box-shadow: none !important; } .nasa-custom-animate .infinities-slide p { min-width: auto; margin-bottom: 0; white-space: nowrap; } body .nasa-content-promotion-news { background-color: #027735; } .ns-book-preview .elementor-toggle div:last-of-type h4, .ns-book-preview .elementor-toggle div:last-of-type .elementor-active { border: none !important; } .nasa-category-horizontal-4 .nasa-nav-arrow { margin-top: -15px; opacity: 1; visibility: visible; top: -35px; margin: 0 !important; transform: translate(0, -50%) !important; } .nasa-category-horizontal-4 .nasa-nav-arrow.slick-prev { left: auto !important; right: 50px !important; } .nasa-category-horizontal-4 .nasa-nav-arrow.slick-next { right: 0px !important; } .nasa-category-horizontal-4 .nasa-nav-arrow svg { border-radius: 5px; background-color: transparent; border: 1px solid #c6c6c6 !important; color: #c6c6c6; } .nasa-category-horizontal-4 .nasa-nav-arrow:not(.slick-disabled):hover svg { border-color: #333 !important; color: #333 !important; } @media only screen and (max-width: 767px) { .service-block .box { margin-bottom: 0px; } .nasa-nutrition-small-banner-wrap .elementor-widget-wrap { gap: 10px; align-content: stretch; } .nasa-category-horizontal-4 .nasa-cat-title, .nasa-books-product-tabs .nasa-tabs .nasa-tab>a { font-size: 130%; } .ns-group-btn-preview a.button { padding: 0; flex: 1; } .nasa-nav-arrow svg { width: 30px; height: 30px; } .nasa-category-horizontal-4 .nasa-nav-arrow.slick-prev { right: 35px !important; } } body.nasa-rtl .nasa-category-horizontal-4 .nasa-nav-arrow.slick-prev { left: 0px !important; right: auto !important; } body.nasa-rtl .nasa-category-horizontal-4 .nasa-nav-arrow.slick-next { left: 50px !important; right: auto !important; } .elementor-toggle .elementor-tab-title .elementor-toggle-icon svg { height: 24px; width: 24px; }',
        ),

        'globals' => array(
            'header-type' => '9',
            'plus_wide_width' => '200',
            'color_primary' => '#027735',
            'loop_layout_buttons' => 'modern-10',

            'footer_mode' => 'builder-e',
            'footer_elm' => elessi_elm_fid_by_slug('hfe-footer-book'),
            'footer_elm_mobile' => elessi_elm_fid_by_slug('hfe-footer-mobile'),
            'type_font_select' => 'google',
            'type_headings' => 'Fraunces',
            'type_texts' => 'Fraunces',
            'type_nav' => 'Fraunces',
            'type_banner' => 'Fraunces',
            'type_price' => 'Fraunces',
            'enable_post_top' => '1',
            'enable_promo_slide' => '1',
            'type_display' => 'custom',
            'content_custom' => '<a href="javascript:void(0);" class="nasa-bold nasa-flex jc" style="text-decoration: none;" rel="nofollow">Sale off 30 for all books every weekend</a><a href="javascript:void(0);" class="nasa-bold nasa-flex jc" style="text-decoration: none;" rel="nofollow">Sale off 30 for all books every weekend</a>',
            'promo_slide_direction' => 'vertical',
            't_promotion_color' => '#fff',
            'bg_promotion' => '#027735',
            'background_area' => ''
        ),
    );
}
