<?php

use Automattic\WooCommerce\Admin\API\Data;

defined('ABSPATH') || exit;

/**
 * Init Admin Brand
 */
// add_action('init', array('Nasa_Header_Builder', 'getInstance'));
// add_action('admin_init', array('Nasa_Header_Builder', 'getInstance'));

if (!class_exists('Nasa_Header_Builder')) {
    class Nasa_Header_Builder
    {
        protected $post_type = 'nasa_header_custom';
        protected $of_options_header = [];
        protected $of_options_header_section = [];
        protected $of_options_header_wrap = [];
        protected $has_cat_fillter = false;
        protected static $instance = null;
        protected $common_option = array();
        protected $align_keys = [
            'hw-align',
            'hw-justify-content',
            'hw-wrap-allow',
            'hw-direction',
            'section-width',
            'hide-for-sticky'
        ];
        protected $css = '';
        protected $css_desktop = '';
        protected $css_tablet = '';
        protected $css_mobile = '';
        protected $css_normal = '';

        public static function getInstance()
        {
            if (!is_admin() && !function_exists('elessi_check_header_custom_available') || !elessi_check_header_custom_available()) {
                return null;
            }

            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            add_action('nasa_header_custom_after', array($this, 'nasa_header_custom_css'), 10, 1);

            if (is_admin()) {
                add_action('init', [$this, 'init_admin']);
            }
        }

        public function init_admin()
        {
            global $pagenow;

            $current_post_type = $this->get_current_post_type();

            if ($current_post_type !== $this->post_type) {
                return;
            }

            if (!in_array($pagenow, ['post.php', 'post-new.php'], true)) {
                return;
            }

            if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'trash') {
                return;
            }

            add_action('save_post_nasa_header_custom', array($this, 'nasa_header_custom_save_post'));

            $this->nasa_common_option_init();
            $this->nasa_items_option_init();
            $this->nasa_section_option_init();
            $this->nasa_header_wrap_option_init();

            add_action('admin_enqueue_scripts', array($this, 'nasa_header_custom_scripts_libs'), 15);

            add_action('add_meta_boxes', function () {
                add_meta_box(
                    'nasa_header_custom_editor',
                    'Advanced Details',
                    array($this, 'nasa_header_custom_admin_editor'),
                    'nasa_header_custom',
                    'advanced',
                    'default'
                );
            });
        }

        protected function get_current_post_type()
        {
            global $post_type, $post;

            if (!isset($post_type)) {
                $post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : null;
            }

            if (empty($post_type) && (isset($post) || isset($_REQUEST['post']))) {
                $post_type = isset($post) ? $post->post_type : get_post_type($_REQUEST['post']);
            }

            return $post_type;
        }

        public function nasa_common_option_init()
        {
            $this->common_option = array(
                array(
                    "name" => __("Responsive Visibility", 'nasa-core'),
                    "std" => 0,
                    "type" => "info",
                ),
                array(
                    "name" => __("Hide In Mobile", 'nasa-core'),
                    "id" => "hide-for-mobile",
                    "std" => 0,
                    "type" => "select",
                ),
                array(
                    "name" => __("Hide In Taplet", 'nasa-core'),
                    "id" => "hide-for-taplet",
                    "std" => 0,
                    "type" => "select",
                ),
                array(
                    "name" => __("Hide In Desktop", 'nasa-core'),
                    "id" => "hide-for-desktop",
                    "std" => 0,
                    "type" => "select",
                ),

                array(
                    "name" => __("Device", 'nasa-core'),
                    "std" => 'ns-otp-desktop-show',
                    "type" => "toggle",
                    "options" => array(
                        'ns-otp-desktop-show' => '<svg viewBox="-2 -2 24 24" height="30px" width="30px" version="1.1" xmlns="" fill="currentColor"><g id="SVGRepo_iconCarrier">    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-100.000000, -7159.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M62.0006998,7012 C62.0006998,7012.552 61.5528565,7013 61.0010496,7013 L47.0059479,7013 C46.4541411,7013 46.0062978,7012.552 46.0062978,7012 L46.0062978,7002 C46.0062978,7001.448 46.4541411,7001 47.0059479,7001 L61.0010496,7001 C61.5528565,7001 62.0006998,7001.448 62.0006998,7002 L62.0006998,7012 Z M61.9907033,6999 L45.9963013,6999 L45.9923027,6999 C44.888689,6999 44,6999.915 44,7001.02 L44.0069976,7001.04 L44.0069976,7013.04 L44,7013.02 C44,7014.125 44.888689,7015 45.9923027,7015 L45.9963013,7015 L51.9902034,7015 C52.5430099,7015 53.0038487,7015.468 53.0038487,7016.02 L53.0038487,7016.01 C53.0038487,7016.562 52.5430099,7017 51.9902034,7017 L48.9912531,7017 C48.4394462,7017 48.005598,7017.468 48.005598,7018.02 L48.005598,7018.01 C48.005598,7018.562 48.4394462,7019 48.9912531,7019 L58.9877543,7019 C59.5405608,7019 60.0013995,7018.572 60.0013995,7018.02 L60.0013995,7018.01 C60.0013995,7017.458 59.5405608,7017 58.9877543,7017 L55.9888039,7017 C55.4369971,7017 55.0031489,7016.572 55.0031489,7016.02 L55.0031489,7016.01 C55.0031489,7015.458 55.4369971,7015 55.9888039,7015 L61.9907033,7015 L61.9977008,7015.02 C63.0993152,7015.02 64,7014.146 64,7013.043 L64,7013.04 L64,7001.04 L64,7001.037 C64,6999.934 63.0993152,6999.02 61.9977008,6999.02 L61.9907033,6999 Z" id="desktop-[#232]"> </path> </g> </g> </g> </g></svg>',

                        'ns-otp-tablet-show' => '<svg viewBox="0 0 24 24" height="30px" width="30px" fill="none"><g id="SVGRepo_iconCarrier"> <path d="M4 7C4 5.11438 4 4.17157 4.58579 3.58579C5.17157 3 6.11438 3 8 3H12H16C17.8856 3 18.8284 3 19.4142 3.58579C20 4.17157 20 5.11438 20 7V12V17C20 18.8856 20 19.8284 19.4142 20.4142C18.8284 21 17.8856 21 16 21H12H8C6.11438 21 5.17157 21 4.58579 20.4142C4 19.8284 4 18.8856 4 17V12V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path> <path d="M11.5 18H12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path> </g></svg>',

                        'ns-otp-mobile-show' => '<svg viewBox="0 0 24 24" height="30px" width="30px" fill="none"><g id="SVGRepo_iconCarrier"> <rect x="6" y="2" width="12" height="20" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect> <path d="M11.95 18H12.05" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>',
                    )
                ),

                array(
                    "name" => __("Padding", 'nasa-core'),
                    "std" => 0,
                    "type" => "info",
                ),
                array(
                    "name" => __("Desktop - padding", 'nasa-core'),
                    "id" => "desktop-padding",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-desktop-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),
                array(
                    "name" => __("Tablet - padding", 'nasa-core'),
                    "id" => "tablet-padding",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-tablet-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),
                array(
                    "name" => __("Mobile - padding", 'nasa-core'),
                    "id" => "mobile-padding",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-mobile-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),

                array(
                    "name" => __("Desktop - margin", 'nasa-core'),
                    "id" => "desktop-margin",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-desktop-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),
                array(
                    "name" => __("Tablet - margin", 'nasa-core'),
                    "id" => "tablet-margin",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-tablet-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),
                array(
                    "name" => __("Mobile - margin", 'nasa-core'),
                    "id" => "mobile-margin",
                    "std" => '',
                    "type" => "text",
                    "class" => 'ns-spacing-input ns-otp-mobile-show',
                    "desc" => __('top right bottom left (vertical horizontal)', 'nasa-core'),
                    "placeholder" => 'XX XX XX XX (XX XX)'
                ),

                array(
                    "name" => __("Extra Class", 'nasa-core'),
                    "id" => "extra-class",
                    "std" => '',
                    "type" => "text",
                    "placeholder" => __('Extra Class', 'nasa-core')
                )
            );
        }

        public function nasa_items_option_init()
        {
            $this->of_options_header = [];

            /**
             * logo item option
             */
            $this->of_options_header['ns-logo'] = array(
                "name" => __("logo", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 64 64" height="30px" width="30px" stroke-width="3" stroke="currentColor" fill="none"> <g id="SVGRepo_iconCarrier"> <path d="M39.93,55.72A24.86,24.86,0,1,1,56.86,32.15a37.24,37.24,0,0,1-.73,6"></path> <path d="M37.86,51.1A47,47,0,0,1,32,56.7"></path> <path d="M32,7A34.14,34.14,0,0,1,43.57,30a34.07,34.07,0,0,1,.09,4.85"></path> <path d="M32,7A34.09,34.09,0,0,0,20.31,32.46c0,16.2,7.28,21,11.66,24.24"></path> <line x1="10.37" y1="19.9" x2="53.75" y2="19.9"></line> <line x1="32" y1="6.99" x2="32" y2="56.7"></line> <line x1="11.05" y1="45.48" x2="37.04" y2="45.48"></line> <line x1="7.14" y1="32.46" x2="56.86" y2="31.85"></line> <path d="M53.57,57,58,52.56l-8-8,4.55-2.91a.38.38,0,0,0-.12-.7L39.14,37.37a.39.39,0,0,0-.46.46L42,53.41a.39.39,0,0,0,.71.13L45.57,49Z"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Site logo URL", 'nasa-core'),
                        "id" => "site_logo_url",
                        "std" => esc_url(home_url('/')),
                        "type" => "text",
                        'desc' => __("The URL of the site logo. If empty, it will use the site URL.", 'nasa-core'),
                    ),
                    array(
                        "name" => __("Site logo Image", 'nasa-core'),
                        "id" => "site_bg_image",
                        "std" => '',
                        "type" => "media",
                        'class' => 'nasa-site_layout',
                    ),
                    array(
                        "name" => __("Site logo Image Width", 'nasa-core'),
                        "id" => "site_logo_width",
                        "std" => '',
                        "type" => "number",
                        'desc' => __("Width in pixels e.g. 20px", 'nasa-core'),
                        'placeholder' => '20',
                    ),
                    array(
                        "name" => __("Site logo Image Height", 'nasa-core'),
                        "id" => "site_logo_height",
                        "std" => '',
                        "type" => "number",
                        'desc' => __("Height in pixels e.g. 20px", 'nasa-core'),
                        'placeholder' => '20',
                    )
                )
            );

            /**
             * Main Menu item option
             */
            $this->of_options_header['ns-main-menu'] = array(
                "name" => __("Main Menu", 'nasa-core'),
                "svg" => '<svg fill="currentColor" height="30px" width="30px" version="1.2" baseProfile="tiny" id="shape" viewBox="0 0 256 256" xml:space="preserve"> <g id="SVGRepo_iconCarrier"> <path d="M237,19H19c-8.2,0-14.9,6.7-14.9,14.9v188.3c0,8.2,6.7,14.9,14.9,14.9h218c8.2,0,14.9-6.7,14.9-14.9V33.9 C251.9,25.7,245.2,19,237,19z M199.8,28.9c5.4,0,9.9,4.5,9.9,9.9c0,5.5-4.5,9.9-9.9,9.9s-9.9-4.5-9.9-9.9S194.4,28.9,199.8,28.9z M172.6,28.9c5.4,0,9.9,4.5,9.9,9.9c0,5.5-4.5,9.9-9.9,9.9s-9.9-4.5-9.9-9.9S167.1,28.9,172.6,28.9z M237,223.9H19V58.6h218V223.9z M227.1,48.7c-5.4,0-9.9-4.5-9.9-9.9s4.5-9.9,9.9-9.9s9.9,4.5,9.9,9.9C237,44.3,232.5,48.7,227.1,48.7z M96,74h76.1v14.4H96V74z M126,106.9h96.3v14.3H126V106.9z M126,135.5h96.3v14.3H126V135.5z M126,164.5h96.3v14.3H126V164.5z M32.6,193.1h189.8v14.3H32.6 V193.1z M32.6,106.9h67.8v70.7H32.6V106.9z"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Menu Display", 'nasa-core'),
                        "id" => "menu-display",
                        "std" => '',
                        "type" => "select",
                        "options" => nasa_meta_get_list_menus()
                    ),

                )
            );


            /**
             * Sub Menu item option
             */
            $this->of_options_header['ns-vertical-menu'] = array(
                "name" => __("Vertical Menu", 'nasa-core'),
                "svg" => '<svg fill="currentColor" height="30px" width="30px" version="1.2" baseProfile="tiny" id="shape" viewBox="0 0 256 256" xml:space="preserve"> <g id="SVGRepo_iconCarrier"> <path d="M237,19H19c-8.2,0-14.9,6.7-14.9,14.9v188.3c0,8.2,6.7,14.9,14.9,14.9h218c8.2,0,14.9-6.7,14.9-14.9V33.9 C251.9,25.7,245.2,19,237,19z M199.8,28.9c5.4,0,9.9,4.5,9.9,9.9c0,5.5-4.5,9.9-9.9,9.9s-9.9-4.5-9.9-9.9S194.4,28.9,199.8,28.9z M172.6,28.9c5.4,0,9.9,4.5,9.9,9.9c0,5.5-4.5,9.9-9.9,9.9s-9.9-4.5-9.9-9.9S167.1,28.9,172.6,28.9z M237,223.9H19V58.6h218V223.9z M227.1,48.7c-5.4,0-9.9-4.5-9.9-9.9s4.5-9.9,9.9-9.9s9.9,4.5,9.9,9.9C237,44.3,232.5,48.7,227.1,48.7z M96,74h76.1v14.4H96V74z M126,106.9h96.3v14.3H126V106.9z M126,135.5h96.3v14.3H126V135.5z M126,164.5h96.3v14.3H126V164.5z M32.6,193.1h189.8v14.3H32.6 V193.1z M32.6,106.9h67.8v70.7H32.6V106.9z"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Vertical Menu Display", 'nasa-core'),
                        "id" => "vertical_menu_selected",
                        "std" => '',
                        "type" => "select",
                        "options" => nasa_meta_get_list_menus()
                    ),

                    array(
                        "name" => __("Vertical Menu Float", 'nasa-core'),
                        "id" => "vertical_menu_float",
                        "std" => '0',
                        "type" => "select",
                        "options" => array(
                            "0" => __("No", 'nasa-core'),
                            "1" => __("Yes", 'nasa-core'),
                        )
                    ),
                    array(
                        "name" => __("Item Hover Background - Vertical Menu Float", 'nasa-core'),
                        "id" => "vfmenu-item-hover-bg",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Vertical Menu Root", 'nasa-core'),
                        "id" => "v_root",
                        "std" => '0',
                        "type" => "select",
                        "options" => array(
                            "0" => __("No", 'nasa-core'),
                            "1" => __("Yes", 'nasa-core'),
                        )
                    ),
                    array(
                        "name" => __("Vertical Menu Always Visible", 'nasa-core'),
                        "id" => "v_menu_visible",
                        "std" => '0',
                        "type" => "select",
                        "options" => array(
                            "0" => __("No", 'nasa-core'),
                            "1" => __("Yes", 'nasa-core'),
                        )
                    ),

                    array(
                        "name" => __("Vertical Menu Root Limit", 'nasa-core'),
                        "id" => "v_root_limit",
                        "std" => '0',
                        "type" => "numeric",
                    ),

                    array(
                        "name" => __("Vertical Menu Width", 'nasa-core'),
                        "id" => "v-width",
                        "std" => '0',
                        "type" => "numeric",
                    ),
                    array(
                        "name" => __("Radius", 'nasa-core'),
                        "id" => "border-radius",
                        "std" => '0',
                        "type" => "numeric",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Background Color", 'nasa-core'),
                        "id" => "background-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Background Color - Dropdown", 'nasa-core'),
                        "id" => "background-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color - Dropdown", 'nasa-core'),
                        "id" => "text-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover - Dropdown", 'nasa-core'),
                        "id" => "text-color-hover-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Separator Line Color - Dropdown", 'nasa-core'),
                        "id" => "separator-line-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );

            /**
             * Mobile Menu item option
             */
            $this->of_options_header['ns-mobile-menu'] = array(
                "name" => __("Mobile Menu", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 24 24" height="30px" width="30px" fill="none" xmlns="http://www.w3.org/2000/svg"> <g id="SVGRepo_iconCarrier"> <path d="M1 12C1 11.4477 1.44772 11 2 11H22C22.5523 11 23 11.4477 23 12C23 12.5523 22.5523 13 22 13H2C1.44772 13 1 12.5523 1 12Z" fill="currentColor"></path> <path d="M1 4C1 3.44772 1.44772 3 2 3H22C22.5523 3 23 3.44772 23 4C23 4.55228 22.5523 5 22 5H2C1.44772 5 1 4.55228 1 4Z" fill="currentColor"></path> <path d="M1 20C1 19.4477 1.44772 19 2 19H22C22.5523 19 23 19.4477 23 20C23 20.5523 22.5523 21 22 21H2C1.44772 21 1 20.5523 1 20Z" fill="currentColor"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Get Menu From", 'nasa-core'),
                        "id" => "nasa_manual_menu",
                        "std" => '0',
                        "type" => "select",
                        "options" => array(
                            "0" => __("Auto get from main and vertical menu theme option", 'nasa-core'),
                            "1" => __("Manual setting", 'nasa-core'),
                        )
                    ),
                    array(
                        "name" => __("Main Menu Display", 'nasa-core'),
                        "id" => "main_menu_selected",
                        "std" => '',
                        "type" => "select",
                        "options" => nasa_meta_get_list_menus()
                    ),
                    array(
                        "name" => __("Vertical Menu Display", 'nasa-core'),
                        "id" => "vertical_menu_selected",
                        "std" => '',
                        "type" => "select",
                        "options" => nasa_meta_get_list_menus()
                    ),
                    array(
                        "name" => __("Ordering Mobile Menu", 'nasa-core'),
                        "id" => "order_mobile_menus",
                        "std" => '',
                        "type" => "select",
                        "options" => array(
                            '' => __("Main Menu > Vertical Menu", 'nasa-core'),
                            'v-focus' => __("Vertical Menu > Main Menu", 'nasa-core'),
                        ),
                        'desc' => __("Manual setting mode only", 'nasa-core'),
                    )
                )
            );

            /**
             * Cart item option
             */
            $this->of_options_header['ns-cart'] = array(
                "name" => __("Cart", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 24 24" fill="none" height="30px" width="30px"> <g id="SVGRepo_iconCarrier"> <path d="M6.29977 5H21L19 12H7.37671M20 16H8L6 3H3M9 20C9 20.5523 8.55228 21 8 21C7.44772 21 7 20.5523 7 20C7 19.4477 7.44772 19 8 19C8.55228 19 9 19.4477 9 20ZM20 20C20 20.5523 19.5523 21 19 21C18.4477 21 18 20.5523 18 20C18 19.4477 18.4477 19 19 19C19.5523 19 20 19.4477 20 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );

            /**
             * Search item option
             */
            $this->of_options_header['ns-search'] = array(
                "name" => __("Search", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 24 24" fill="none" height="30px" width="30px"> <g id="SVGRepo_iconCarrier"> <path d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Layout Search Type", 'nasa-core'),
                        "id" => "type",
                        "std" => 0,
                        "type" => "select",
                        "options" => array(
                            "icon" => __("Icon", 'nasa-core'),
                            "full" => __("Full", 'nasa-core'),
                        )
                    ),
                    array(
                        "name" => __("Live Search Layout", 'nasa-core'),
                        "id" => "layout",
                        "std" => 0,
                        "type" => "select",
                        "options" => array(
                            "classic" => __("Classic", 'nasa-core'),
                            "modern" => __("Modern", 'nasa-core')
                        ),
                        'desc' => __("Modern for icon mode only", 'nasa-core'),
                    ),
                    array(
                        "name" => __("Popular Keywords", 'nasa-core'),
                        "id" => "popkeys_search",
                        "std" => '',
                        "type" => "textarea",
                        'desc' => __("Please input the Popular Searches keywords (ex: Sweater, Jacket, T-shirt).", 'nasa-core'),
                    ),
                    array(
                        "name" => __("Suggested Keywords", 'nasa-core'),
                        "id" => "hotkeys_search",
                        "std" => '',
                        "type" => "textarea",
                        'desc' => __("Please input the Popular Searches keywords (ex: Sweater, Jacket, T-shirt).", 'nasa-core'),
                    )
                )

            );

            /**
             * HTML item option
             */
            $this->of_options_header['ns-html'] = array(
                "name" => __("HTML", 'nasa-core'),
                "svg" => '<svg fill="currentColor" version="1.1" id="Capa_1" height="30px" width="30px" viewBox="0 0 550.801 550.801" xml:space="preserve"> <g id="SVGRepo_iconCarrier"> <g> <g> <path d="M475.095,131.986c-0.032-2.525-0.844-5.015-2.568-6.992L366.324,3.684c-0.021-0.029-0.053-0.045-0.084-0.071 c-0.633-0.712-1.36-1.289-2.141-1.803c-0.232-0.15-0.465-0.29-0.707-0.422c-0.686-0.372-1.393-0.669-2.131-0.891 c-0.2-0.058-0.379-0.145-0.59-0.188C359.87,0.114,359.037,0,358.203,0H97.2C85.292,0,75.6,9.688,75.6,21.601v507.6 c0,11.907,9.692,21.601,21.6,21.601H453.6c11.908,0,21.601-9.693,21.601-21.601V133.197 C475.2,132.791,475.137,132.393,475.095,131.986z M97.2,21.601h250.203v110.51c0,5.962,4.831,10.8,10.8,10.8H453.6l0.011,223.837 H97.2V21.601z M180.457,499.311h-21.642v-41.26h-35.744v41.26h-21.769v-98.613h21.769v37.895h35.744v-37.895h21.642V499.311z M265.874,419.429h-26.188v79.882h-21.779v-79.882h-25.763v-18.731h73.73V419.429z M359.416,499.311l-1.424-37.747 c-0.422-11.85-0.854-26.188-0.854-40.532h-0.422c-2.996,12.583-6.982,26.631-10.685,38.19l-11.665,38.476H317.43l-10.252-38.18 c-3.133-11.56-6.412-25.608-8.69-38.486h-0.285c-0.564,13.321-1.002,28.535-1.692,40.827l-1.72,37.457h-20.07l6.117-98.613h28.903 l9.397,32.917c2.995,11.412,5.975,23.704,8.121,35.264h0.422c2.711-11.417,5.975-24.427,9.112-35.406l10.252-32.774h28.329 l5.263,98.613h-21.221V499.311z M457.238,499.311h-59.938v-98.613h21.779v79.882h38.153v18.731H457.238z"></path> <polygon points="154.132,249.086 236.872,287.523 236.872,269.254 174.295,241.851 174.295,241.505 236.872,214.094 236.872,195.827 154.132,234.262 "></polygon> <polygon points="249.642,294.416 267.047,294.416 303.93,169.452 286.527,169.452 "></polygon> <polygon points="313.938,214.094 377.895,241.505 377.895,241.851 313.938,269.254 313.938,287.523 396.668,249.605 396.668,233.745 313.938,195.827 "></polygon> </g> </g> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("HTML", 'nasa-core'),
                        "id" => "html_content",
                        "std" => '',
                        "type" => "html",
                    ),
                )
            );

            /**
             * Account
             */
            $this->of_options_header['ns-account'] = array(
                "name" => __("Login / Register", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 1024 1024" fill="currentColor" class="icon" height="30px" width="30px" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M962.4 1012.8s0 0.8 0 0h25.6-25.6zM704 338.4C704 195.2 588.8 78.4 445.6 78.4S187.2 195.2 187.2 338.4s116 260 258.4 260S704 481.6 704 338.4z m-472 0c0-118.4 96-214.4 213.6-214.4s213.6 96 213.6 214.4-96 214.4-213.6 214.4S232 456.8 232 338.4z" fill=""></path><path d="M456.8 621.6c196.8 0 361.6 136 394.4 324h45.6C863.2 732 677.6 576.8 456 576.8c-221.6 0-406.4 155.2-440.8 368.8h45.6C96 756.8 260 621.6 456.8 621.6z" fill=""></path><path d="M770.4 578.4l-24-8.8 20.8-14.4c65.6-46.4 104.8-122.4 103.2-202.4-1.6-128-102.4-232.8-228-241.6v47.2c100 8.8 180 92.8 180.8 194.4 0.8 52.8-19.2 102.4-56 140.8-36.8 37.6-86.4 59.2-139.2 60-24.8 0-50.4 0-75.2 1.6-15.2 1.6-41.6 0-54.4 9.6-1.6 0.8-3.2 0-4.8 0l-9.6 12c-0.8 1.6-2.4 3.2-4 4.8 0.8 1.6-0.8 16 0 17.6 12 4 71.2 0 156.8 2.4 179.2 1.6 326.4 160.8 340.8 338.4l47.2 3.2c-9.6-156-108-310.4-254.4-364.8z" fill=""></path></g></svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("SVG Account", 'nasa-core'),
                        "id" => "svg_account",
                        "std" => '',
                        "type" => "html",
                        'desc' => __("Add your html code here", 'nasa-core'),
                    ),
                    array(
                        "name" => __("SVG Account Height Width Mode", 'nasa-core'),
                        "id" => "svg_size_mode",
                        "std" => 'default',
                        "type" => "select",
                        "options" => array(
                            "default" => __("Default", 'nasa-core'),
                            "auto" => __("Auto", 'nasa-core'),
                        )
                    ),
                    array(
                        "name" => __("Display Text", 'nasa-core'),
                        "id" => "display_text",
                        "std" => 1,
                        "type" => "select",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Background Color - Dropdown", 'nasa-core'),
                        "id" => "background-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color - Dropdown", 'nasa-core'),
                        "id" => "text-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover - Dropdown", 'nasa-core'),
                        "id" => "text-color-hover-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Separator Line Color - Dropdown", 'nasa-core'),
                        "id" => "separator-line-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );


            /**
             * Wishlist item option
             */
            $this->of_options_header['ns-wishlist'] = array(
                "name" => __("Wishlist", 'nasa-core'),
                "svg" => '<svg viewBox="0 0 24 24" fill="none" height="30px" width="30px"> <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );


            /**
             * Compare item option
             */
            $this->of_options_header['ns-compare'] = array(
                "name" => __("Compare", 'nasa-core'),
                "svg" => '<svg class="nasa-flip-vertical nasa-icon compare-icon" viewBox="0 30 512 512" width="30" height="30" fill="currentColor"><path d="M276 467c0 8 6 21-2 23l-26 0c-128-7-230-143-174-284 5-13 13-23 16-36-18 0-41 23-54 5 5-15 25-18 41-23 15-5 36-7 48-15-2 10 23 95 6 100-21 5-13-39-18-57-8-5-8 8-11 13-71 126 29 297 174 274z m44 13c-8 0-10 5-20 3 0-6-3-13-3-18 5-3 13-3 18-5 2 7 5 15 5 20z m38-18c-5 3-10 8-18 10-2-7-5-12-7-18 5-2 10-7 18-7 2 5 7 7 7 15z m34-31c0-33-18-71-5-99 23 2 12 38 17 58 90-117-7-314-163-289 0-8-3-10-3-20 131-5 233 84 220 225-2 36-20 66-30 92 12 0 51-26 53-2 3 17-82 28-89 35z m-233-325c5-2 13-5 18-10 0 8 5 10 7 18-5 2-10 8-18 8 0-8-7-8-7-16z m38-18c8 0 10-5 21-5 0 5 2 13 2 18-5 3-13 3-18 5 0-5-5-10-5-18z"></path></svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );

            /**
             * Cat fillter item option
             */
            $this->of_options_header['ns-cat-fillter'] = array(
                "name" => __("Cat Fillter", 'nasa-core'),
                "svg" => '<svg class="nasa-icon" width="30" height="30" viewBox="0 0 32 32">
                <path d="M6.937 21.865c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 27.195c-1.176 0-2.132-0.956-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M6.937 3.738c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 9.069c-1.176 0-2.132-0.956-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M6.937 12.779c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 18.11c-1.176 0-2.132-0.957-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M16 21.865c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 27.195c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M16 3.738c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 9.069c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M16 12.779c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 18.11c-1.176 0-2.133-0.957-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M25.063 21.865c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM25.063 27.195c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                <path d="M25.063 10.135c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199zM25.063 4.805c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132z" fill="currentColor"></path>
                <path d="M25.063 12.779c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM25.063 18.11c-1.176 0-2.133-0.957-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"></path>
                </svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Top Filter Categories - display", 'nasa-core'),
                        "id" => "show_icon_cat_top",
                        "std" => "show-in-shop",
                        "type" => "select",
                        "options" => array(
                            'show-in-shop' => __('On Archive Product Pages', 'nasa-core'),
                            'show-all-site' => __('On All Pages', 'nasa-core'),
                            'not-show' => __('Off', 'nasa-core'),
                        ),
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Background Color - Dropdown", 'nasa-core'),
                        "id" => "background-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color - Dropdown", 'nasa-core'),
                        "id" => "text-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover - Dropdown", 'nasa-core'),
                        "id" => "text-color-hover-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Separator Line Color - Dropdown", 'nasa-core'),
                        "id" => "separator-line-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );

            /**
             * Cat fillter item option
             */
            $this->of_options_header['ns-multi-languages'] = array(
                "name" => __("Multi Languages", 'nasa-core'),
                "svg" => '<svg fill="currentColor" version="1.1" width="30" height="30" id="Layer_1" viewBox="796 796 200 200" enable-background="new 796 796 200 200" xml:space="preserve"><g id="SVGRepo_iconCarrier"> <g> <path d="M973.166,818.5H818.833c-12.591,0-22.833,10.243-22.833,22.833v109.333c0,12.59,10.243,22.833,22.833,22.833h154.333 c12.59,0,22.834-10.243,22.834-22.833V841.333C996,828.743,985.756,818.5,973.166,818.5z M896,961.5h-77.167 c-5.973,0-10.833-4.859-10.833-10.833V841.333c0-5.974,4.86-10.833,10.833-10.833H896V961.5z M978.58,872.129 c-0.547,9.145-5.668,27.261-20.869,39.845c4.615,1.022,9.629,1.573,14.92,1.573v12c-10.551,0-20.238-1.919-28.469-5.325 c-7.689,3.301-16.969,5.325-28.125,5.325v-12c5.132,0,9.924-0.501,14.366-1.498c-8.412-7.016-13.382-16.311-13.382-26.78h11.999 c0,8.857,5.66,16.517,14.884,21.623c4.641-2.66,8.702-6.112,12.164-10.351c5.628-6.886,8.502-14.521,9.754-20.042h-49.785v-12 h22.297v-11.986h12V864.5h21.055c1.986,0,3.902,0.831,5.258,2.28C977.986,868.199,978.697,870.155,978.58,872.129z"></path> <g> <g> <path d="M839.035,914.262l-4.45,11.258h-15.971l26.355-61.09h15.971l25.746,61.09h-16.583l-4.363-11.258H839.035z M852.475,879.876l-8.902,22.604h17.629L852.475,879.876z"></path> </g> </g> </g> </g></svg>',
                "option" => array(
                    array(
                        "name" => __("General Options", 'nasa-core'),
                        "std" => 0,
                        "type" => "info",
                    ),
                    array(
                        "name" => __("Text Color", 'nasa-core'),
                        "id" => "text-color",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover", 'nasa-core'),
                        "id" => "text-color-hover",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Background Color - Dropdown", 'nasa-core'),
                        "id" => "background-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color - Dropdown", 'nasa-core'),
                        "id" => "text-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Text Color Hover - Dropdown", 'nasa-core'),
                        "id" => "text-color-hover-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                    array(
                        "name" => __("Border Color - Dropdown", 'nasa-core'),
                        "id" => "separator-line-color-dropdown",
                        "std" => '',
                        "type" => "color",
                    ),
                )
            );
        }

        public function nasa_section_option_init()
        {
            $this->of_options_header_section = array(
                array(
                    "name" => __("General Options", 'nasa-core'),
                    "std" => 0,
                    "type" => "info",
                ),
                array(
                    "name" => __("Section Width - Mode", 'nasa-core'),
                    "id" => "section-width",
                    "std" => '0',
                    "type" => "select",
                    "options" => array(
                        "0" => __("Auto", 'nasa-core'),
                        "section-width-custom" => __("Custom", 'nasa-core'),
                        "section-width-full" => __("Full", 'nasa-core'),
                        "section-width-fill" => __("Fill", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Section Width - Custom", 'nasa-core'),
                    "id" => "section-width-custom",
                    "std" => '0',
                    "type" => "number",
                ),
                array(
                    "name" => __("Section Width - Unit", 'nasa-core'),
                    "id" => "section-width-unit",
                    "std" => '0',
                    "type" => "select",
                    "options" => array(
                        "px" => "px",
                        "%" => "%",
                    ),
                ),
                array(
                    "name" => __("Justify Content Item", 'nasa-core'),
                    "id" => "hw-justify-content",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Default", 'nasa-core'),
                        "jc" => __("Center", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                        "je" => __("End", 'nasa-core'),
                        "jbw" => __("Space Between", 'nasa-core'),
                        "jse" => __("Space Evenly", 'nasa-core'),
                        "jsa" => __("Space Around", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Align Item", 'nasa-core'),
                    "id" => "hw-align",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Default", 'nasa-core'),
                        "align-center" => __("Center", 'nasa-core'),
                        "align-start" => __("Start", 'nasa-core'),
                        "align-end" => __("End", 'nasa-core'),
                        "align-baseline" => __("Baseline", 'nasa-core'),
                        "align-stretch" => __("Stretch", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Wrap Allow ", 'nasa-core'),
                    "id" => "hw-wrap-allow",
                    "std" => 'flex-wrap',
                    "type" => "select",
                    "options" => array(
                        "flex-wrap" => __("Wrap", 'nasa-core'),
                        "flex-nowrap" => __("No Wrap", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Direction Flex", 'nasa-core'),
                    "id" => "hw-direction",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Row", 'nasa-core'),
                        "flex-column" => __("Column", 'nasa-core'),
                        "jdc-r" => __("Column Reverse", 'nasa-core'),
                        "jdr-r" => __("Row Reverse", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Background Color", 'nasa-core'),
                    "id" => "background-color",
                    "std" => '',
                    "type" => "color",
                ),
                array(
                    "name" => __("Text Color", 'nasa-core'),
                    "id" => "text-color",
                    "std" => '',
                    "type" => "color",
                ),
                array(
                    "name" => __("Text Color Hover", 'nasa-core'),
                    "id" => "text-color-hover",
                    "std" => '',
                    "type" => "color",
                ),
            );
        }


        public function nasa_header_wrap_option_init()
        {
            $this->of_options_header_wrap = array(
                array(
                    "name" => __("General Options", 'nasa-core'),
                    "std" => 0,
                    "type" => "info",
                ),
                array(
                    "name" => __("Hide In sticky", 'nasa-core'),
                    "id" => "hide-for-sticky",
                    "std" => 0,
                    "type" => "select",
                    "options" => array(
                        "" => __("No", 'nasa-core'),
                        "nasa-sticky-hide" => __("Yes", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Justify Content Item", 'nasa-core'),
                    "id" => "hw-justify-content",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Default", 'nasa-core'),
                        "jc" => __("Center", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                        "je" => __("End", 'nasa-core'),
                        "jbw" => __("Space Between", 'nasa-core'),
                        "jse" => __("Space Evenly", 'nasa-core'),
                        "jsa" => __("Space Around", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                        "jst" => __("Start", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Align Item", 'nasa-core'),
                    "id" => "hw-align",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Default", 'nasa-core'),
                        "align-center" => __("Center", 'nasa-core'),
                        "align-start" => __("Start", 'nasa-core'),
                        "align-end" => __("End", 'nasa-core'),
                        "align-baseline" => __("Baseline", 'nasa-core'),
                        "align-stretch" => __("Stretch", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Wrap Allow ", 'nasa-core'),
                    "id" => "hw-wrap-allow",
                    "std" => 'flex-wrap',
                    "type" => "select",
                    "options" => array(
                        "flex-wrap" => __("Wrap", 'nasa-core'),
                        "flex-nowrap" => __("No Wrap", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Direction Flex", 'nasa-core'),
                    "id" => "hw-direction",
                    "std" => '',
                    "type" => "select",
                    "options" => array(
                        "" => __("Row", 'nasa-core'),
                        "flex-column" => __("Column", 'nasa-core'),
                        "jdc-r" => __("Column Reverse", 'nasa-core'),
                        "jdr-r" => __("Row Reverse", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Style & Color", 'nasa-core'),
                    "std" => 0,
                    "type" => "info",
                ),
                array(
                    "name" => __("Range Of Influence Settings", 'nasa-core'),
                    "id" => "range-of-influence",
                    "std" => '1',
                    "type" => "select",
                    "options" => array(
                        "0" => __("All Row", 'nasa-core'),
                        "1" => __("Just Wrap", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Add Separator Line In To", 'nasa-core'),
                    "id" => "separator-line",
                    "std" => '0',
                    "type" => "select",
                    "options" => array(
                        "0" => __("No", 'nasa-core'),
                        "1" => __("Top", 'nasa-core'),
                        "2" => __("Bottom", 'nasa-core'),
                    ),
                ),
                array(
                    "name" => __("Separator Line - Color", 'nasa-core'),
                    "id" => "separator-line-color",
                    "std" => '',
                    "type" => "color",
                ),
                array(
                    "name" => __("Separator Line - Width", 'nasa-core'),
                    "id" => "separator-line-width",
                    "std" => '1',
                    "type" => "number",
                    "class" => 'ns-spacing-input',
                    "placeholder" => '1'
                ),
                array(
                    "name" => __("Background Color", 'nasa-core'),
                    "id" => "background-color",
                    "std" => '',
                    "type" => "color",
                ),
                array(
                    "name" => __("Text Color", 'nasa-core'),
                    "id" => "text-color",
                    "std" => '',
                    "type" => "color",
                ),
                array(
                    "name" => __("Text Color Hover", 'nasa-core'),
                    "id" => "text-color-hover",
                    "std" => '',
                    "type" => "color",
                ),
            );
        }

        // Script nasa-core
        public function nasa_header_custom_scripts_libs()
        {
            // Remove default select2
            wp_dequeue_script('select2');
            wp_dequeue_style('select2');

            // Enqueue custom select2
            wp_enqueue_style('nasa-select2', NASA_CORE_PLUGIN_URL . 'admin/assets/select2/select2.min.css');
            wp_enqueue_script('nasa-select2', NASA_CORE_PLUGIN_URL . 'admin/assets/select2/select2.min.js');

            // jQuery UI components
            $jquery_ui_scripts = [
                'jquery-ui-core',
                'jquery-ui-draggable',
                'jquery-ui-droppable',
                'jquery-ui-dialog',
                'jquery-ui-sortable',
                'jquery-ui-slider',
                'jquery-effects-core',
            ];
            foreach ($jquery_ui_scripts as $script) {
                wp_enqueue_script($script);
            }

            wp_enqueue_style('wp-jquery-ui-dialog');

            // Admin styles
            wp_enqueue_style('nasa-theme-admin-style', ELESSI_ADMIN_DIR_URI . 'assets/css/admin-style.css', [], NASA_VERSION);
            wp_enqueue_style('jquery-ui-custom-admin', ELESSI_ADMIN_DIR_URI . 'assets/css/jquery-ui-custom.css');

            // Color picker
            if (!wp_style_is('wp-color-picker', 'registered')) {
                wp_register_style('wp-color-picker', ELESSI_ADMIN_DIR_URI . 'assets/css/color-picker.min.css');
            }
            wp_enqueue_style('wp-color-picker');

            // Additional scripts
            wp_enqueue_script('jquery-input-mask', ELESSI_ADMIN_DIR_URI . 'assets/js/jquery.maskedinput-1.2.2.js', ['jquery']);
            wp_enqueue_script('cookie', ELESSI_ADMIN_DIR_URI . 'assets/js/cookie.js', ['jquery']);

            // builder scripts & styles
            wp_enqueue_script('nasa-admin-header-bulider-script', NASA_CORE_PLUGIN_URL . 'admin/assets/nasa-header-builder-editor.js', [], NASA_VERSION, true);
            wp_enqueue_style('nasa-theme-header-builder-editor-style', NASA_CORE_PLUGIN_URL . 'admin/assets/nasa-header-builder-editor.css', [], NASA_VERSION);

            wp_enqueue_media();
        }

        // Render Nasa Header Custom Admin Editor
        public function nasa_header_custom_admin_editor($post)
        {

            wp_nonce_field('nasa_save_meta_action', 'nasa_header_update_meta_nonce');

            include_once NASA_CORE_PLUGIN_PATH . 'admin/views/header-custom-editor.php';

            $html = '<div id="popup-header-editor"';
            $html .= ' title="' . esc_attr__('Please select the item you want to add.', 'nasa-core') . '"';
            $html .= ' style="display:none;"';
            $html .= ' data-text-close="' . esc_attr__('Close', 'nasa-core') . '">';
            $html .= '<div class="popup-items-wrap">';

            foreach ($this->of_options_header as $id => $item) {
                $html .= '<div class="popup-item-wrap">';
                $html .= '<a href="javascript:void(0);" data-name="' . esc_attr($item['name']) . '" class="popup-item" data-item="' . esc_attr($id) . '">';

                if (!empty($item['svg'])) {
                    $html .= $item['svg'];
                }

                $html .= '<p>' . esc_html($item['name']) . '</p>';
                $html .= '</a>';
                $html .= ' <script type="text/template" id="tmpl-nasa-' . esc_attr($id) . '">';
                $html .= '<form class="' . esc_attr($id) . '" data-id="{{item-id}}">';
                $html .= '<table class="form-table">';
                $html .= '<tbody>';
                $html .= $this->nasa_item_option_render($item['option']);
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</form></script>';
                $html .= '</div>';
            }

            $html .= '</div></div>';


            $html .= '<script type="text/template" id="tmpl-nasa-section-wrap">';
            $html .= '<form class="nasa-section-wrap" data-id="{{item-id}}">';
            $html .= '<table class="form-table">';
            $html .= '<tbody>';
            $html .= $this->nasa_item_option_render($this->of_options_header_section);
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</form>';
            $html .= '</script>';

            $html .= '<script type="text/template" id="tmpl-nasa-header-wrap">';
            $html .= '<form class="nasa-header-wrap" data-id="{{item-id}}">';
            $html .= '<table class="form-table">';
            $html .= '<tbody>';
            $html .= $this->nasa_item_option_render($this->of_options_header_wrap);
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</form>';
            $html .= '</script>';

            echo $html;
        }

        public function nasa_item_option_render($array = array())
        {

            // if (empty($array) || !is_array($array)) {
            //     return '<h3>' . esc_html('Look for this change in theme options', 'nasa-core') . '</h3>';
            // }

            $output = '';

            $array = array_merge($array, $this->common_option);

            foreach ($array as $key => $value) {
                if (empty($value) || !is_array($value)) {
                    continue;
                }

                $dfAttr = array(
                    'type' => '',
                    'name' => '',
                    'desc' => '',
                    'id' => '',
                    'std' => '',
                    'class' => '',
                    'options' => array(
                        "0" => __("No", 'nasa-core'),
                        "1" => __("Yes", 'nasa-core'),
                    ),
                    'placeholder' => ''
                );

                extract(shortcode_atts($dfAttr, $value));

                // Only render supported types
                if (!isset($type) || $type == '' || !in_array($type, array('select', 'media', 'textarea', 'html', 'color', 'text', 'info', 'number', 'numeric', 'toggle'))) {
                    continue;
                }

                $output .= '<tr class="form-field ns-advance-field ns-open ' . esc_attr($class) . '">';
                // Table header cell for name/label

                if (!in_array($type, array('toggle', 'info'))) {
                    $output .= '<th scope="row">';
                    $output .= '<label for="' . esc_attr($id) . '">' . esc_html($name) . '</label>';
                    $output .= '</th>';
                } else {
                    $output .= $type == 'info' ? '<th scope="row" colspan="2">' : '<th scope="row">';
                    $output .= '<h3>' . esc_html($name) . '</h3>';
                    $output .= '</th>';
                }

                $desc_html = !empty($desc) && $desc != '' ? '<p class="description">' . esc_html($desc) . '</p>' : '';

                // Table data cell for input/option
                switch ($type) {
                    case 'toggle':
                        $output .= '<td>';
                        $output .= '<div class="nasa-flex ns-toggle-otp" data-std="' . esc_attr($std) . '">';
                        if (!empty($options) && is_array($options)) {
                            foreach ($options as $opt_key => $val) {
                                $class = 'button';
                                $output .= '<a href="javascript:void(0);" class="' . esc_attr($class) . '" data-target="' . $opt_key . '"> ' . $val . '</a>';
                            }
                        }
                        $output .= $desc_html;
                        $output .= '</div>';
                        $output .= '</td>';
                        break;
                    case 'info':
                        break;
                    case 'select':
                        $output .= '<td>';
                        $output .= '<select id="' . esc_attr($id) . '" name="' . esc_attr($id) . '">';
                        if (!empty($options) && is_array($options)) {
                            foreach ($options as $opt_key => $val) {
                                $selected = ($std == $opt_key) ? ' selected="selected"' : '';
                                $output .= '<option value="' . esc_attr($opt_key) . '"' . $selected . '>' . esc_html($val) . '</option>';
                            }
                        }
                        $output .= '</select>';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'media':
                        $output .= '<td>';
                        $output .= '<div class="ns-option-media-wrap">';
                        $output .= '<input type="hidden" class="ns-media-input" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="" />';
                        $output .= '<img src="' . esc_attr($std) . '" height="60" onerror="this.onerror=null;this.src=\'' . esc_url($std) . '\';">';
                        $output .= '<button class="button nasa-media-upload" data-text="' . esc_html__('Use image', 'nasa-core') . '" data-title="' . esc_attr($name) . '"data-target="' . esc_attr($id) . '">' . esc_html__('Upload', 'nasa-core') . '</button>';
                        $output .= '<button class="button nasa-media-remove" data-url-placeholder="' . esc_url($std) . '"data-target="' . esc_attr($id) . '" style="display: none;">' . esc_html__('Remove', 'nasa-core') . '</button>';
                        $output .= '</div>';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'textarea':
                        $output .= '<td>';
                        $output .= '<textarea class="nasa-html-editor" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '"></textarea>';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'number':
                    case 'numeric':
                        $output .= '<td>';
                        $output .= '<input type="number" id="' . esc_attr($id) . '" min="0" placeholder="' . esc_attr($placeholder) . '" name="' . esc_attr($id) . '" value="' . esc_attr($std) . '" />';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'color':
                        $output .= '<td>';
                        $output .= '<input type="text" class="nasa-color-picker nasa-color-field" min="0" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($std) . '" />';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'html':
                        $output .= '<td>';
                        $output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" class="nasa-html-editor">' . $std . '</textarea>';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;

                    case 'text':
                    default:
                        $output .= '<td>';
                        $output .= '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" placeholder="' . esc_attr($placeholder) . '"  value="' . esc_attr($std) . '" />';
                        $output .= $desc_html;
                        $output .= '</td>';

                        break;
                }

                $output .= '</tr>';
            }
            return $output;
        }

        /**
         * Save post nasa_header_custom
         */
        public function nasa_header_custom_save_post($post_id)
        {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

            if (wp_is_post_revision($post_id)) return;

            if (!current_user_can('edit_post', $post_id)) return;

            if (get_post_type($post_id) !== $this->post_type) return;

            if (!isset($_POST['nasa_header_update_meta_nonce']) || !wp_verify_nonce($_POST['nasa_header_update_meta_nonce'], 'nasa_save_meta_action')) {
                return;
            }

            if (isset($_POST['nasa_header_custom_editor_data'])) {
                $json_raw = wp_unslash($_POST['nasa_header_custom_editor_data']);
                $json_array = json_decode($json_raw, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    update_post_meta($post_id, 'nasa_header_custom_editor_data', $json_array);
                }
            }
        }

        /**
         * Get Header Custom Data
         */
        public function nasa_header_custom_get_data($items, $dragable_tmp)
        {
            if (!empty($items) && is_array($items)) {

                foreach ($items as $item) {

                    $replacements = [
                        'name'              => esc_attr($this->of_options_header[$item['item']]['name'] ?? ''),
                        'data-json'         => esc_attr(json_encode($item)) ?? '',
                        'id'                => esc_attr($item['id']),
                        'data-id-dialog'    => esc_attr($item['id']),
                    ];

                    $rendered = preg_replace_callback('/\{\{(.+?)\}\}/', function ($matches) use ($replacements) {
                        $key = $matches[1];
                        return isset($replacements[$key]) ? $replacements[$key] : '';
                    }, $dragable_tmp);

                    echo $rendered;
                }
            }
        }

        public function nasa_get_header_custom_structure()
        {
            $nasa_custom_header = function_exists('elessi_check_header_custom_available') ? elessi_check_header_custom_available() : false;

            if ($nasa_custom_header) {

                remove_action('nasa_after_static_content', 'elessi_script_template_responsive_header');
                remove_action('nasa_static_content', 'elessi_static_for_mobile', 12);

                $meta_value = get_post_meta($nasa_custom_header, 'nasa_header_custom_editor_data', true);

                $file_path = NASA_CORE_PLUGIN_PATH . 'includes/shortcodes_header/nasa_header_template.php';

                if (file_exists($file_path)) {
                    include $file_path;
                }
            }
        }

        public function nasa_header_custom_css($meta_value)
        {
            global $nasa_opt;
            if ($nasa_opt['color_primary']) {
                $param = $nasa_opt['color_primary'];
                $this->css_normal .= "body{--ns-color-primary: {$param};}\n";
            }
            foreach ($meta_value as $key => $value) {
                $settings = $value['settings'];

                $this->nasa_header_custom_style_render($settings);
            }

            // var_dump($nasa_opt['color_primary']);

            $this->css .= $this->css_normal;
            $this->css .= "@media only screen and (min-width: 1024px) {{$this->css_desktop}}\n";
            $this->css .= "@media only screen and (min-width: 768px) and (max-width: 1024px) {{$this->css_tablet}}\n";
            $this->css .= "@media only screen and (max-width: 768px) {{$this->css_mobile}}\n";



            if ($this->css !== '') {
                echo "<style>{$this->css}</style>";
            }
        }

        public function render_header_section($array)
        {
            global $nasa_opt;
            $in_mobile = isset($nasa_opt['nasa_in_mobile']) && $nasa_opt['nasa_in_mobile'] ? true : false;


            if (!empty($array)) {
                foreach ($array as $item) {
                    if (isset($item['item']) && $item['item'] != '') {

                        $data = $item['data'];

                        $ex_class = $this->nasa_header_custom_class($item, '', 'item');

                        $ex_class = trim($ex_class);

                        $ex_class .= isset($data['extra-class']) && $data['extra-class'] != '' ? ' ' . $data['extra-class'] : '';

                        $this->nasa_header_custom_style_render($item);

                        switch ($item['item']) {
                            case 'ns-wishlist':
                                echo function_exists('elessi_icon_wishlist') ? elessi_icon_wishlist($ex_class) : '';
                                break;
                            case 'ns-compare':
                                echo function_exists('elessi_icon_compare') ? elessi_icon_compare($ex_class) : '';
                                break;
                            case 'ns-cart':
                                echo function_exists('elessi_mini_cart') ? '<div class="' . esc_attr($ex_class) . '">' .  elessi_mini_cart(true) . '</div>' : '';
                                break;
                            case 'ns-cat-fillter':

                                if ($in_mobile) break;

                                if (NASA_WOO_ACTIVED) {
                                    $show_icon_cat_top = isset($data['show_icon_cat_top']) ? $data['show_icon_cat_top'] : 'show-in-shop';

                                    switch ($show_icon_cat_top) {
                                        case 'show-all-site':
                                            $show_icon = true;
                                            break;

                                        case 'not-show':
                                            $show_icon = false;
                                            break;

                                        case 'show-in-shop':
                                        default:
                                            $show_icon = (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) ? false : true;
                                            break;
                                    }

                                    $keypad = '<svg class="nasa-icon" width="28" height="28" viewBox="0 0 32 32">
                                    <path d="M6.937 21.865c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 27.195c-1.176 0-2.132-0.956-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M6.937 3.738c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 9.069c-1.176 0-2.132-0.956-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M6.937 12.779c-1.766 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM6.937 18.11c-1.176 0-2.132-0.957-2.132-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M16 21.865c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 27.195c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M16 3.738c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 9.069c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M16 12.779c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM16 18.11c-1.176 0-2.133-0.957-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M25.063 21.865c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM25.063 27.195c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    <path d="M25.063 10.135c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199zM25.063 4.805c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133c-1.176 0-2.133-0.956-2.133-2.133s0.956-2.132 2.133-2.132z" fill="currentColor"/>
                                    <path d="M25.063 12.779c-1.767 0-3.199 1.432-3.199 3.198s1.432 3.199 3.199 3.199c1.766 0 3.199-1.432 3.199-3.199s-1.433-3.198-3.199-3.198zM25.063 18.11c-1.176 0-2.133-0.957-2.133-2.133s0.956-2.132 2.133-2.132c1.176 0 2.133 0.956 2.133 2.132s-0.956 2.133-2.133 2.133z" fill="currentColor"/>
                                    </svg>';

                                    if ($show_icon) {
                                        $this->has_cat_fillter = true;
                                        $icon = apply_filters('nasa_mini_icon_filter_cats', $keypad);
                                        // $icon .= '<span class="icon-text">' . esc_html__('Categories', 'nasa-core') . '</span>';

                                        $nasa_icon_cat =
                                            '<a class="filter-cat-icon nasa-flex nasa-hide-for-mobile ' . esc_attr($ex_class) . '" href="javascript:void(0);" title="' . esc_attr__('Product Categories', 'nasa-core') . '" rel="nofollow">' .
                                            $icon .
                                            '</a>' .
                                            '<a class="filter-cat-icon-mobile inline-block ' . esc_attr($ex_class) . '" href="javascript:void(0);" title="' . esc_attr__('Product Categories', 'nasa-core') . '" rel="nofollow">' .
                                            $icon .
                                            '</a>';
                                        echo !$in_mobile ? $nasa_icon_cat : '';
                                    }
                                }
                                break;
                            case 'ns-multi-languages':
                                elessi_multi_languages($ex_class);
                                break;
                            case 'ns-search';

                                if (!function_exists('elessi_search')) {
                                    break;
                                }

                                if (isset($data['popkeys_search']) && trim($data['popkeys_search']) !== '') {
                                    $nasa_opt['popkeys_search'] = trim($data['popkeys_search']);
                                }

                                if (isset($data['hotkeys_search']) && trim($data['hotkeys_search']) !== '') {
                                    $nasa_opt['hotkeys_search'] = trim($data['hotkeys_search']);
                                }

                                $icon = apply_filters('nasa_mini_icon_search', '<svg class="nasa-icon nasa-search" fill="currentColor" viewBox="0 0 80 80" width="28" height="28"><path d="M74.3,72.2L58.7,56.5C69.9,44,69,24.8,56.5,13.5s-31.7-10.3-43,2.2s-10.3,31.7,2.2,43c11.6,10.5,29.3,10.5,40.9,0 l15.7,15.7L74.3,72.2z M36.1,63.5c-15.1,0-27.4-12.3-27.4-27.4C8.7,20.9,21,8.7,36.1,8.7c15.1,0,27.4,12.3,27.4,27.4 C63.5,51.2,51.2,63.5,36.1,63.5z"/><path d="M36.1,12.8v3c11.2,0,20.3,9.1,20.3,20.3h3C59.4,23.2,49,12.8,36.1,12.8z"/></svg>');

                                if ($in_mobile) :
                                    add_action('nasa_static_content', 'elessi_static_for_mobile', 12);

                                    echo '<a class="nasa-icon mobile-search ' . esc_attr($ex_class) . '" href="javascript:void(0);" aria-label="' . esc_attr__('Toggle mobile search', 'nasa-core') . '" title="' . esc_attr__('Search', 'nasa-core') . '" rel="nofollow">' . $icon . '</a>';
                                else:
                                    if ($data['type'] == 'full') {
                                        $ex_class .= ' nasa-header-search-full';
                                        echo '<div class="nasa-header-search-wrap ' . esc_attr($ex_class) . '">' .
                                            elessi_search('full') .
                                            '</div>';
                                    } else {
                                        $layout = isset($data['layout']) && in_array($data['layout'], array('classic', 'modern')) ? $data['layout'] : 'classic';

                                        echo '<div class="nasa-wrap-event-search ' . esc_attr($ex_class) . '">' .
                                            '<a class="search-icon desk-search nasa-flex" href="javascript:void(0);" data-open="0" title="' . esc_attr__('Search', 'nasa-core') . '" rel="nofollow">' .
                                            $icon .
                                            '</a>' .
                                            '<div class="nasa-header-search-wrap">' .
                                            elessi_search('icon', true, $layout) .
                                            '</div>
                                            </div>';
                                    }
                                endif;


                                break;

                            case 'ns-mobile-menu';
                                $manual_menu = isset($data['nasa_manual_menu']) && $data['nasa_manual_menu'] ? true : false;
                                $order_mobile_menus = isset($data['order_mobile_menus']) && $data['order_mobile_menus'] ? ' nasa-focus-menu' : '';
                                $ex_class .= $manual_menu ? ' nasa_manual_menu' : '';

                                echo '<a href="javascript:void(0);" aria-label="Toggle mobile menu" class="nasa-icon nasa-mobile-menu_toggle mobile_toggle nasa-mobile-menu-icon nasa-flex ' . esc_attr($ex_class) . '" rel="nofollow">' .
                                    '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="3" y1="12" x2="21" y2="12"></line>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <line x1="3" y1="18" x2="21" y2="18"></line>
                                    </svg>' .
                                    '</a>';

                                if ($manual_menu) {
                                    $mega = class_exists('Nasa_Nav_Menu');
                                    $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
                                    $depth = apply_filters('nasa_max_depth_main_menu', 3);

                                    if (isset($data['main_menu_selected']) && $data['main_menu_selected'] && $data['main_menu_selected'] !== '') {
                                        $menu_id = $data['main_menu_selected'];

                                        $nasa_main_menu = wp_nav_menu(array(
                                            'echo' => false,
                                            'menu' => $menu_id,
                                            'container' => false,
                                            'items_wrap' => '%3$s',
                                            'depth' => (int) $depth,
                                            'walker' => $walker
                                        ));

                                        $tmpl = '<template id="tmpl-nasa-mobile-menu">';
                                        $tmpl .= '<div class="nav-wrapper main-menu-warpper">';
                                        $tmpl .= '<ul id="site-navigation" class="header-nav nasa-to-menu-mobile nasa-main-menu">';
                                        $tmpl .= $nasa_main_menu;
                                        $tmpl .= '</ul>';
                                        $tmpl .= '</div>';
                                        $tmpl .= '</template>';
                                        echo $tmpl;
                                    }

                                    if (isset($data['vertical_menu_selected']) &&  $data['vertical_menu_selected'] && $data['vertical_menu_selected'] !== '') {
                                        $menu_id = $data['vertical_menu_selected'];

                                        $nasa_main_menu = wp_nav_menu(array(
                                            'echo' => false,
                                            'menu' => $menu_id,
                                            'container' => false,
                                            'items_wrap' => '%3$s',
                                            'depth' => (int) $depth,
                                            'walker' => $walker
                                        ));

                                        $tmpl = '<template id="tmpl-nasa-mobile-ver-menu">';
                                        $tmpl .= '<div class="vertical-menu-container">';
                                        $tmpl .= '<h5 class="section-title nasa-title-vertical-menu nasa-flex"><span> ' . esc_html__('Browse Categories', 'nasa-core') . '</span></h5>';
                                        $tmpl .= '<ul class="vertical-menu-wrapper ' . esc_attr($order_mobile_menus) . '">';
                                        $tmpl .= $nasa_main_menu;
                                        $tmpl .= '</ul>';
                                        $tmpl .= '</div>';
                                        $tmpl .= '</template>';
                                        echo $tmpl;
                                    }
                                }

                                break;
                            case 'ns-html':
                                $html = '<div class="nasa_ns-html ' . esc_attr($ex_class) . '">';
                                $html .= $data['html_content'];
                                $html .= '</div>';
                                echo $html;
                                break;

                            default:
                                $file_path = NASA_CORE_PLUGIN_PATH . 'includes/shortcodes_header/header_custom_item/nasa_' . $item['item'] . '.php';

                                if (file_exists($file_path)) {
                                    $data = isset($item['data']) ? $item['data'] : [];
                                    include $file_path;
                                }
                                break;
                        }
                    }
                }
            }
        }

        public function nasa_header_custom_class($settings, $class = '', $item = 'item')
        {

            $class .= $item == 'item' ? ' ns-header-custom-item' : '';

            if (!is_array($settings) || empty($settings)) {
                return $class;
            } else {
                $data = $settings['data'];
                $class .= isset($settings['id']) ? ' ns-' . $settings['id'] : '';
            }

            if (!empty($data['hide-for-mobile']) && $data['hide-for-mobile'] === '1') {
                $class .= ' hide-for-mobile';
            }

            if (!empty($data['hide-for-taplet']) && $data['hide-for-taplet'] === '1') {
                $class .= ' hide-for-taplet';
            }

            if (!empty($data['hide-for-desktop']) && $data['hide-for-desktop'] === '1') {
                $class .= ' hide-for-desktop';
            }

            foreach ($data as $key => $value) {
                if (in_array($key, ['hide-for-mobile', 'hide-for-taplet', 'hide-for-desktop'])) {
                    continue;
                }
                if ($value !== '0' && $value !== '' && $value !== null) {
                    if (in_array($key, $this->align_keys)) {
                        $class .= " {$value}";
                    }
                }
            }

            return trim($class);
        }

        public function nasa_id_check($id)
        {
            if (isset($id) && $id !== '') {
                return esc_attr('ns-' . $id);
            }
            return '';
        }

        public function nasa_get_vertical_menu_float()
        {
            global $nasa_vertical_menu_custom_float, $vfmenu_custom;

            if (function_exists('is_checkout') && is_checkout()) {
                return;
            }

            if (!$nasa_vertical_menu_custom_float) {

                if (isset($vfmenu_custom) && $vfmenu_custom != '-1') {
                    $mega = class_exists('Nasa_Nav_Menu');
                    $walker = $mega ? new Nasa_Nav_Menu(false) : new Walker_Nav_Menu();
                    $class = $mega ? '' : ' nasa-wp-simple-nav-menu';

                    $depth = apply_filters('nasa_max_depth_vertical_float_menu', 3);

                    $menu_name = esc_html__('Browse Categories', 'nasa-core');

                    $browse_heading = '<a class="nasa-flex button" href="javascript:void(0);" rel="nofollow">' .
                        '<svg class="ns-v-icon" fill="currentColor" width="20" height="20" viewBox="0 0 512 512"><path d="M43 469c-23 0-43-19-43-42 0-24 20-44 43-44 24 0 42 20 42 44 0 23-18 42-42 42z m0-171c-23 0-43-19-43-42 0-23 20-43 43-43 24 0 42 20 42 43 0 23-18 42-42 42z m0-169c-23 0-43-20-43-44 0-23 20-42 43-42 24 0 42 19 42 42 0 24-18 44-42 44z m100 312l0-28 369 0 0 28z m0-199l369 0 0 28-369 0z m0-171l369 0 0 28-369 0z"/></svg><span>' . $menu_name . '</span></a>';

                    $browse_heading = apply_filters('nasa_vertical_float_menu_heading', $browse_heading);

                    ob_start();
?>
                    <a class="nasa-vertical-menu-float-mobile-toggle" href="javascript:void(0);" rel="nofollow">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none">
                            <path d="M3 12H15M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <div id="vertical-menu-float">
                        <div class="vertical_float_menu_title">
                            <?php echo apply_filters('nasa_vertical_float_menu_heading', $browse_heading); ?>
                        </div>
                        <div class="vertical-menu-float-container">
                            <ul class="vertical-menu-float-wrapper style-2 <?php echo esc_attr($class); ?>">
                                <?php
                                wp_nav_menu(array(
                                    'menu' => $vfmenu_custom,
                                    'container' => false,
                                    'items_wrap' => '%3$s',
                                    'depth' => (int) $depth,
                                    'walker' => $walker
                                ));
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                    $nasa_vertical_menu_custom_float = ob_get_clean();
                    $GLOBALS['nasa_vertical_menu_custom_float'] = $nasa_vertical_menu_custom_float;
                }
            }

            echo $nasa_vertical_menu_custom_float ? $nasa_vertical_menu_custom_float : '';
        }

        public function nasa_param_check($param)
        {
            if (!is_string($param)) {
                return '';
            }

            $param = preg_replace('/[^0-9 ]/', '', $param);
            $param = preg_replace('/\s+/', ' ', $param);
            $param = trim($param);

            $parts = $param !== '' ? explode(' ', $param) : [];
            $parts = array_slice($parts, 0, 4);
            $parts = array_map(function ($val) {
                return $val . 'px';
            }, $parts);

            return implode(' ', $parts);
        }

        public function nasa_header_custom_style_render($settings)
        {

            if (empty($settings) || empty($settings['id']) || empty($settings['item']) || empty($settings['data'])) {
                return;
            }

            $id = $settings['id'];
            $item_tmpl = $settings['item'];
            $data = $settings['data'];
            $css_desktop_item = '';
            $css_tablet_item = '';
            $css_mobile_item = '';
            $css_normal_item = '';

            // css global
            if (isset($data['section-width']) && $data['section-width'] === 'section-width-custom') {
                $param = isset($data['section-width-custom']) && (int)$data['section-width-custom'] > 0 ? (int)$data['section-width-custom'] : 0;

                $unit = isset($data['section-width-unit']) && in_array($data['section-width-unit'], ['px', '%'], true) ? $data['section-width-unit'] : 'px';

                if ($param > 0) {
                    $css_normal_item .= "width: {$param}{$unit}; \n";
                }
            }

            // css desktop
            if (isset($data['desktop-padding']) && $data['desktop-padding'] !== '') {
                $param = $this->nasa_param_check($data['desktop-padding']);
                $css_desktop_item .= "padding: {$param};\n";
            }

            if (isset($data['desktop-margin']) && $data['desktop-margin'] !== '') {
                $param = $this->nasa_param_check($data['desktop-margin']);
                $css_desktop_item .= "margin: {$param};\n";
            }

            // css tablet
            if (isset($data['tablet-padding']) && $data['tablet-padding'] !== '') {
                $param = $this->nasa_param_check($data['tablet-padding']);
                $css_tablet_item .= "padding: {$param};\n";
            }

            if (isset($data['tablet-margin']) && $data['tablet-margin'] !== '') {
                $param = $this->nasa_param_check($data['tablet-margin']);
                $css_tablet_item .= "margin: {$param};\n";
            }

            // css mobile
            if (isset($data['mobile-padding']) && $data['mobile-padding'] !== '') {
                $param = $this->nasa_param_check($data['mobile-padding']);
                $css_mobile_item .= "padding: {$param};\n";
            }

            if (isset($data['mobile-margin']) && $data['mobile-margin'] !== '') {
                $param = $this->nasa_param_check($data['mobile-margin']);
                $css_mobile_item .= "margin: {$param};\n";
            }

            if (!in_array($item_tmpl, ['section-wrap', 'header-wrap'])) {
                if (isset($data['text-color']) && !empty($data['text-color'])) {
                    $css_normal_item .= "--nsch-t-color: " . $data['text-color'] . ";\n";
                }

                if (isset($data['text-color-hover']) && !empty($data['text-color-hover'])) {
                    $css_normal_item .= "--nsch-t-color-hover: " . $data['text-color-hover'] . ";\n";
                }

                if (isset($data['background-color']) && !empty($data['background-color'])) {
                    $css_normal_item .= "--nsch-bg-color: " . $data['background-color'] . ";\n";
                }
            }

            switch ($item_tmpl) {
                case 'ns-logo':
                    if (isset($data['site_logo_width']) && (int)$data['site_logo_width'] > 0) {
                        $css_normal_item .= "width: " . (int) $data['site_logo_width'] . "px;\n";
                    }

                    if (isset($data['site_logo_height']) && (int)$data['site_logo_height'] > 0) {
                        $css_normal_item .= "height: " . (int) $data['site_logo_height'] . "px;\n";
                    }
                    break;
                    
                case 'ns-cat-fillter':
                case 'ns-account':
                case 'ns-multi-languages':
                case 'ns-vertical-menu':
                    $css = '';

                    if (isset($data['background-color-dropdown']) && !empty($data['background-color-dropdown'])) {
                        $css .= "--nsch-drop-bg-color: " . $data['background-color-dropdown'] . ";\n";
                    }

                    if (isset($data['text-color-dropdown']) && !empty($data['text-color-dropdown'])) {
                        $css .= "--nsch-drop-t-color: " . $data['text-color-dropdown'] . ";\n";
                    }

                    if (isset($data['text-color-hover-dropdown']) && !empty($data['text-color-hover-dropdown'])) {
                        $css .= "--nsch-drop-t-color-hover: " . $data['text-color-hover-dropdown'] . ";\n";
                    }

                    if (isset($data['separator-line-color-dropdown']) && !empty($data['separator-line-color-dropdown'])) {
                        $css .= "--nsch-drop-separator-line-color: " . $data['separator-line-color-dropdown'] . ";\n";
                    }

                    if (isset($data['v-width']) && $data['v-width'] > 0) {
                        $css .= "--nsch-v-width: " . $data['v-width'] . "px;\n";
                    }

                    if (isset($data['border-radius']) && (int)$data['border-radius'] >= 0) {
                        $css .= "--border-radius: " . $data['border-radius'] . "px;\n";
                    }

                    if ($item_tmpl == 'ns-cat-fillter') {
                        $this->css_normal .= $css != '' ? "\n.nasa-cat-filter-wrap {\n {$css}}\n" : '';
                    } else {
                        $css_normal_item .= $css;

                        if (isset($data['vertical_menu_float']) && $data['vertical_menu_float']) {

                            if (isset($data['text-color']) && !empty($data['text-color'])) {
                                $css .= "--nsch-t-color: " . $data['text-color'] . ";\n";
                            }
                            if (isset($data['text-color-hover']) && !empty($data['text-color-hover'])) {
                                $css .= "--nsch-t-color-hover: " . $data['text-color-hover'] . ";\n";
                            }
                            if (isset($data['background-color']) && !empty($data['background-color'])) {
                                $css .= "--nsch-bg-color: " . $data['background-color'] . ";\n";
                            }
                            if (isset($data['vfmenu-item-hover-bg']) && !empty($data['vfmenu-item-hover-bg'])) {
                                $css .= "--nsch-vfmenu-item-hover-bg: " . $data['vfmenu-item-hover-bg'] . ";\n";
                            }
                            $this->css_normal .= $css != '' ? "\n#vertical-menu-float {\n {$css}}\n" : '';
                        }
                    }

                    break;
                    
                case 'section-wrap':
                case 'header-wrap':
                    $css = '';
                    if (isset($data['separator-line']) && in_array($data['separator-line'], [1, 2])) {
                        $line_w = (int)($data['separator-line-width'] ?? 1) ?: 1;
                        $css .= "--line_wt: " . ($data['separator-line'] == 1 ? $line_w : 0) . "px;\n";
                        $css .= "--line_wb: " . ($data['separator-line'] == 1 ? 0 : $line_w) . "px;\n";
                        $css .= "--line_c: " . ($data['separator-line-color'] ?? '#ececec') . ";\n";
                    }

                    if (isset($data['background-color']) && !empty($data['background-color'])) {
                        $css .= "--nsch-wrap-bg-color: " . $data['background-color'] . ";\n";
                    }

                    if (isset($data['text-color']) && !empty($data['text-color'])) {
                        $css .= "--nsch-t-color: " . $data['text-color'] . ";\n";
                    }

                    if (isset($data['text-color-hover']) && !empty($data['text-color-hover'])) {
                        $css .= "--nsch-t-color-hover: " .  $data['text-color-hover'] . ";\n";
                    }

                    if ($css == '') {
                        break;
                    }

                    if (isset($data['range-of-influence']) && $data['range-of-influence'] == 0) {
                        $this->css_normal .= "\n.ns-row-wrap:has(.ns-{$id}) {\n {$css}}\n";
                    } else {
                        $css_normal_item .= $css;
                    }
                    break;
                    
                default:
                    break;
            }


            //total css item
            $this->css_normal  .= $css_normal_item != '' ? "\n.ns-{$id} { \n{$css_normal_item} }\n" : '';
            $this->css_desktop .= $css_desktop_item != '' ? "\n.ns-{$id} { \n{$css_desktop_item} }\n" : '';
            $this->css_tablet  .= $css_tablet_item != '' ? "\n.ns-{$id} { \n{$css_tablet_item} }\n" : '';
            $this->css_mobile  .= $css_mobile_item != '' ? "\n.ns-{$id} { \n{$css_mobile_item} }\n" : '';
        }
    }

    if (is_admin()) {
        new Nasa_Header_Builder();
    }
}
