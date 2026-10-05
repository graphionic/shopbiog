<?php
/**
 * Since 1.6.5
 */
defined('ABSPATH') or die(); // Exit if accessed directly

if (class_exists('WC_AJAX')) :
    class ELESSI_WC_AJAX extends WC_AJAX {

        /**
         * Hook in ajax handlers.
         */
        public static function nasa_init() {
            add_action('init', array(__CLASS__, 'define_ajax'), 0);
            add_action('template_redirect', array(__CLASS__, 'do_wc_ajax'), 0);
            
            self::nasa_add_ajax_events();
        }

        /**
         * Hook in methods - uses WordPress ajax handlers (admin-ajax).
         */
        public static function nasa_add_ajax_events() {
            /**
             * Register ajax event
             */
            $ajax_events = array(
                'nasa_ajax_static_content',
                'nasa_quick_view',
                'nasa_quickview_gallery_variation',
                'nasa_get_gallery_variation',
                'nasa_get_deal_variation',
                'nasa_single_add_to_cart',
                'nasa_combo_products',
                'nasa_load_compare',
                'nasa_add_compare_product',
                'nasa_remove_compare_product',
                'nasa_remove_all_compare',
                // 'nasa_after_add_to_cart',
                'nasa_load_wishlist',
                'nasa_add_to_wishlist',
                'nasa_remove_from_wishlist',
                'nasa_remove_wishlist_hidden',
                'nasa_search_products',
                'nasa_quantity_mini_cart',
                'nasa_ext_mini_cart',
                'nasa_all_ext_mini_cart',
                'nasa_mini_cart_note',
                'nasa_ext_cart_ajax_nonce',
                'nasa_mini_cart_calculate_shipping',
                'nasa_mini_cart_apply_coupon',
                'nasa_mini_cart_remove_coupon',
                'nasa_validate_checkout_modern',
                'nasa_get_cross_sell_mini_cart',
                'nasa_get_site_map_and_located',
                'nasa_wc_clear_all_notices',
                'nasa_wc_nonce_fields_rf',
                'nasa_clear_cart',
                'nasa_vote_review',
            );

            foreach ($ajax_events as $ajax_event) {
                add_action('wp_ajax_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));
                add_action('wp_ajax_nopriv_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));

                // WC AJAX can be used for frontend ajax requests.
                add_action('wc_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
            }
        }
        
        /**
         * Init WPBakery shortcodes
         */
        protected static function init_wpb_shortcodes() {
            if (class_exists('WPBMap')) {
                WPBMap::addAllMappedShortcodes();
            }
        }

        /**
         * Static content
         * 
         * @global type $nasa_opt
         */
        public static function nasa_ajax_static_content() {
            $data = array('success' => '', 'content' => array());
            
            $yith_wishlist = isset($_REQUEST['reload_yith_wishlist']) && $_REQUEST['reload_yith_wishlist'] ? true : false;
            
            // if (NASA_WISHLIST_ENABLE && $yith_wishlist) {
            //     $data['content']['#nasa-wishlist-sidebar-content'] = elessi_mini_wishlist_sidebar(true);
            // }

            if (defined('NASA_PLG_CACHE_ACTIVE') && NASA_PLG_CACHE_ACTIVE) {
                if (NASA_WISHLIST_ENABLE && $yith_wishlist) {
                    $data['content']['.nasa-wishlist-count.wishlist-number'] = elessi_get_count_wishlist();
                }
            }
            
            // Reload logged in / out
            // if (
            //     (NASA_CORE_USER_LOGGED && isset($_REQUEST['reload_my_account']) && $_REQUEST['reload_my_account']) ||
            //     (!NASA_CORE_USER_LOGGED && isset($_REQUEST['reload_login_register']) && $_REQUEST['reload_login_register'])) {
            //     $data['content']['.nasa-menus-account'] = elessi_tiny_account(true);
            // }
            
            if (!empty($data['content'])) {
                $data['success'] = '1';
            }

            wp_send_json($data);
        }

        /**
         * Get a refreshed cart fragment, including the mini cart HTML.
         */
        public static function nasa_quick_view() {
            global $nasa_opt;
            
            $result = array(
                'content' => ''
            );

            if (isset($_REQUEST["product"])) {
                $cache_enable = !isset($nasa_opt['nasa_cache_qv']) || $nasa_opt['nasa_cache_qv'] ? true : false;
                $key_cache = false;
                $qv_content = false;
                
                $style_quickview = isset($nasa_opt['style_quickview']) && in_array($nasa_opt['style_quickview'], array('sidebar', 'popup')) ? $nasa_opt['style_quickview'] : 'sidebar';
                
                $prod_id = intval($_REQUEST["product"]);
                $prod_id_org = $prod_id;
                
                if ($cache_enable && function_exists('nasa_get_cache')) {
                    $currency = function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : null;
                    
                    if ($currency) {
                        $key_cache = $prod_id_org  . '_' . $style_quickview . '_' . $currency;
                        $qv_content = nasa_get_cache($key_cache, 'nasa-quickview');
                    }
                }
                
                if (!$qv_content) {
                    global $product, $post;

                    self::init_wpb_shortcodes();
                    $post_object = get_post($prod_id);
                    setup_postdata($GLOBALS['post'] =& $post_object);

                    $GLOBALS['product'] = wc_get_product($prod_id);
                    $product_lightbox = $GLOBALS['product'];

                    if ($product_lightbox) {
                        $product_type = $product_lightbox->get_type();

                        if ($product_type == 'variation') {
                            $variation_data = wc_get_product_variation_attributes($prod_id);
                            $prod_id = wp_get_post_parent_id($prod_id);

                            $post_object = get_post($prod_id);
                            setup_postdata($GLOBALS['post'] =& $post_object);

                            $GLOBALS['product'] = wc_get_product($prod_id);

                            if (!empty($variation_data)) {
                                foreach ($variation_data as $key => $value) {
                                    if ($value != '') {
                                        $_REQUEST[$key] = $value;
                                    }
                                }
                            }
                        } 

                        if ($product_type == 'grouped') {
                            $GLOBALS['product_lightbox'] = $product_lightbox;
                        }

                        $file = ELESSI_CHILD_PATH . '/includes/nasa-single-product-lightbox.php';
                        ob_start();
                        include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-single-product-lightbox.php';
                        $qv_content = ob_get_clean();
                        
                        if (function_exists('nasa_set_cache') && $key_cache) {
                            nasa_set_cache($key_cache, 'nasa-quickview', $qv_content);
                        }
                    }
                }
                
                $result['content'] = $qv_content;
            }

            wp_send_json($result);
        }

        /**
         * Quick view gallery variation
         */
        public static function nasa_quickview_gallery_variation() {
            $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : array();

            if (!isset($data['variation_id'])) {
                die;
            }

            $productId = $data['variation_id'];
            $main_id = isset($data['main_id']) && (int) $data['main_id'] ? (int) $data['main_id'] : 0;
            $imageMain = $main_id ? wp_get_attachment_image($main_id, apply_filters('woocommerce_gallery_image_size', 'woocommerce_single')) : null;
            
            $hasThumb = (bool) $imageMain;

            $attachment_ids = array();
            if (isset($data['gallery'])) {
                $attachments = $data['gallery'] ? explode(',', $data['gallery']) : array();

                if ($attachments) {
                    foreach ($attachments as $img_id) {
                        $img_id = (int) trim($img_id);
                        
                        if ($img_id) {
                            $attachment_ids[] = $img_id;
                        }
                    }
                }
            }

            $show_images = isset($data['show_images']) ? $data['show_images'] : apply_filters('nasa_quickview_number_imgs', 2);

            $result = array();

            /**
             * Main images
             */
            $file = ELESSI_CHILD_PATH . '/includes/nasa-single-product-lightbox-gallery.php';
            ob_start();
            include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-single-product-lightbox-gallery.php';

            $result['quickview_gallery'] = ob_get_clean();

            wp_send_json($result);
        }
        
        /**
         * Gallery variation
         */
        public static function nasa_get_gallery_variation() {
            $data = isset($_REQUEST['data']) ? $_REQUEST['data'] : array();

            if (!isset($data['variation_id'])) {
                die;
            }

            $productId = $data['variation_id'];

            $main_id = isset($data['main_id']) && (int) $data['main_id'] ? (int) $data['main_id'] : 0;
            $gallery_id = array();
            if (isset($data['gallery'])) {
                $attachments = $data['gallery'] ? explode(',', $data['gallery']) : array();

                if ($attachments) {
                    foreach ($attachments as $img_id) {
                        $img_id = (int) trim($img_id);
                        if ($img_id) {
                            $gallery_id[] = $img_id;
                        }
                    }
                }
            }

            $attachment_count = count($gallery_id);

            $result = array();

            /**
             * Main images
             */
            $file = ELESSI_CHILD_PATH . '/includes/nasa-variation-main-images.php';
            ob_start();
            include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-variation-main-images.php';

            $result['main_image'] = ob_get_clean();

            /**
             * Thumb images
             */
            $file2 = ELESSI_CHILD_PATH . '/includes/nasa-variation-thumb-images.php';
            ob_start();
            include is_file($file2) ? $file2 : ELESSI_THEME_PATH . '/includes/nasa-variation-thumb-images.php';

            $result['thumb_image'] = ob_get_clean();

            /**
             * Deal time
             */
            if (isset($data['deal_variation']) && $data['deal_variation']) {
                $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
                $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
                $time_sale = ((int) $time_to < NASA_TIME_NOW || (int) $time_from > NASA_TIME_NOW) ?
                    false : (int) $time_to;

                if ($time_sale) {
                    $result['deal_variation'] = elessi_time_sale($time_sale);
                }
            }

            wp_send_json($result);
        }
        
        /**
         * Get Deal variation
         */
        public static function nasa_get_deal_variation() {
            $result = array('success' => '0');
            
            if (isset($_REQUEST["pid"])) {
                $productId = $_REQUEST["pid"];
                $time_from = get_post_meta($productId, '_sale_price_dates_from', true);
                $time_to = get_post_meta($productId, '_sale_price_dates_to', true);
                $time_sale = ((int) $time_to < NASA_TIME_NOW || (int) $time_from > NASA_TIME_NOW) ?
                    false : (int) $time_to;

                $result['content'] = elessi_time_sale($time_sale);
                if ($result['content'] !== '') {
                    $result['success'] = '1';
                }
            }

            wp_send_json($result);
        }
        
        /**
         * validate variation
         */
        protected static function nasa_validate_variation($product, $variation_id, $variation, $quantity) {
            if (empty($variation_id) || empty($product)) {
                return array('validate' => false);
            }

            $missing_attributes = array();
            $variations         = array();
            $attributes         = $product->get_attributes();
            $variation_data     = wc_get_product_variation_attributes($variation_id);

            foreach ($attributes as $attribute) {
                if (!$attribute['is_variation']) {
                    continue;
                }

                $taxonomy = 'attribute_' . sanitize_title($attribute['name']);

                if (isset($variation[$taxonomy])) {
                    // Get value from post data
                    if ($attribute['is_taxonomy']) {
                        // Don't use wc_clean as it destroys sanitized characters
                        $value = sanitize_title(stripslashes($variation[$taxonomy]));
                    } else {
                        $value = wc_clean(stripslashes($variation[$taxonomy]));
                    }
                    
                    if (trim($value) == '') {
                        $missing_attributes[] = wc_attribute_label($attribute['name']);
                    } else {
                        // Get valid value from variation
                        $valid_value = isset($variation_data[$taxonomy]) ? $variation_data[$taxonomy] : '';

                        // Allow if valid or show error.
                        if ($valid_value === $value || (in_array($value, $attribute->get_slugs()))) {
                            $variations[$taxonomy] = $value;
                        } else {
                            return array('validate' => false);
                        }
                    }
                } else {
                    $missing_attributes[] = wc_attribute_label($attribute['name']);
                }
            }
            
            if (!empty($missing_attributes)) {
                return array(
                    'validate' => false,
                    'missing_attributes' => $missing_attributes
                );
            }

            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product->get_id(), $quantity, $variation_id, $variations);

            return array(
                'validate' => $passed_validation
            );
        }

        /**
         * Single add to cart | Quick view add to cart
         */
        public static function nasa_single_add_to_cart() {
            /**
             * Add to cart in single
             */
            if (isset($_REQUEST['add-to-cart']) && is_numeric(wp_unslash($_REQUEST['add-to-cart']))) {
                $product_id = wp_unslash($_REQUEST['add-to-cart']);
                $error = (0 === wc_notice_count('error')) ? false : true;
                $woo_mess = wc_print_notices(true);
                
                $data = array();
                
                /**
                 * Error Add to Cart
                 */
                if ($error) {
                    $data['error'] = $error;
                    $data['message'] = $woo_mess;
                    $data['product_url'] = apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id);
                }
                
                ob_start();
                woocommerce_mini_cart();
                $mini_cart = ob_get_clean();

                $frag_mess = empty($woo_mess) ? '<div class="woocommerce-message text-center" role="alert">' . esc_html__('Product added to cart successfully!', 'elessi-theme') . '</div>' : $woo_mess;

                $data['fragments'] = apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                        '.woocommerce-message' => $frag_mess
                    )
                );

                $data['cart_hash'] = WC()->cart->get_cart_hash();
                
                /**
                 * Clear Old Notices
                 */
                wc_clear_notices();
                
                wp_send_json($data);
            }
            
            /**
             * Add to cart in Loop
             */
            else {
                /**
                 * Clear Old Notices
                 */
                wc_clear_notices();
                
                if (!isset($_REQUEST['product_id']) || !is_numeric(wp_unslash($_REQUEST['product_id']))){
                    wc_add_notice(esc_html__('Sorry, Product is not existing.', 'elessi-theme'), 'error');
                    wp_send_json(array(
                        'error' => true,
                        'message' => wc_print_notices(true)
                    ));
                    
                    /**
                     * Clear Old Notices
                     */
                    wc_clear_notices();

                    wp_die();
                }

                $error      = false;
                $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product_id']));
                $quantity   = empty($_REQUEST['quantity']) ? 1 : wc_stock_amount($_REQUEST['quantity']);
                $type       = !isset($_REQUEST['product_type']) || !in_array($_REQUEST['product_type'], array('simple', 'variation', 'variable')) ? false : $_REQUEST['product_type'];
                
                $variation_id = false;
                
                if (!$type) {
                    wc_add_notice(esc_html__('Sorry, Product is not existing.', 'elessi-theme'), 'error');
                    wp_send_json(array(
                        'error' => true,
                        'message' => wc_print_notices(true)
                    ));

                    wp_die();
                }

                $variation = isset($_REQUEST['variation']) ? $_REQUEST['variation'] : array();
                $validate_attr = array('validate' => true);
                if ($type == 'variation') {
                    if (!isset($_REQUEST['variation_id']) || !$_REQUEST['variation_id']) {
                        $variation_id = $product_id;
                        $product_id = wp_get_post_parent_id($product_id);
                        $type = 'variable';
                    } else {
                        $variation_id = (int) $_REQUEST['variation_id'];
                    }
                }

                $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
                $product_status    = get_post_status($product_id);

                $product = wc_get_product((int) $product_id);
                $product_type = false;

                if (!$product) {
                    $error = true;
                } else {
                    $product_type = $product->get_type();
                    if ((!$variation || !$variation_id) && $product_type == 'variable'){
                        $error = true;
                    }

                    if (!$error && $product_type == 'variable') {
                        $validate_attr = self::nasa_validate_variation($product, $variation_id, $variation, $quantity);
                    }
                }

                if (!$error && $validate_attr['validate'] && $passed_validation && 'publish' === $product_status && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation)) {

                    do_action('woocommerce_ajax_added_to_cart', $product_id);

                    if ('yes' !== get_option('woocommerce_cart_redirect_after_add')) {
                        // Return fragments
                        ob_start();
                        woocommerce_mini_cart();
                        $mini_cart = ob_get_clean();

                        // Fragments and mini cart are returned
                        $data = array(
                            'fragments' => apply_filters(
                                'woocommerce_add_to_cart_fragments',
                                array(
                                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                                )
                            ),
                            'cart_hash' => WC()->cart->get_cart_hash()
                        );
                    } else {
                        wc_add_to_cart_message(array($product_id => $quantity), true);
                        $data = array(
                            'redirect' => wc_get_cart_url()
                        );
                    }

                    // Remove wishlist
                    if (NASA_WISHLIST_ENABLE && $product_type && $product_type != 'external' && get_option('yith_wcwl_remove_after_add_to_cart') == 'yes') {
                        $nasa_logined_id = get_current_user_id();
                        $detail = isset($_REQUEST['data_wislist']) ? $_REQUEST['data_wislist'] : array();
                        if (!empty($detail) && isset($detail['from_wishlist']) && $detail['from_wishlist'] == '1') {
                            $detail['remove_from_wishlist'] = $product_id;
                            $detail['user_id'] = $nasa_logined_id;

                            $data['wishlist'] = '';
                            $data['wishlistcount'] = 0;

                            /**
                             * Yith_WCWL 2.x or Lower
                             */
                            if (!NASA_WISHLIST_NEW_VER) {
                                if ($nasa_logined_id) {
                                    $nasa_wishlist = new YITH_WCWL($detail);
                                    if (elessi_remove_wishlist_item($nasa_wishlist)) {
                                        $data['wishlist'] = elessi_mini_wishlist_sidebar(true);
                                        $count = yith_wcwl_count_products();
                                        global $nasa_opt;
                                        if (!isset($nasa_opt['compact_number']) || $nasa_opt['compact_number']) {
                                            $count = (int) $count > 9 ? '9+' : (int) $count;
                                        }
                                        
                                        $data['wishlistcount'] = $count;
                                    }
                                }
                            }

                            /**
                             * Yith_WCWL 3x or Higher
                             */
                            else {
                                try {
                                    YITH_WCWL()->remove($detail);
                                    $data['wishlist'] = elessi_mini_wishlist_sidebar(true);
                                    $count = yith_wcwl_count_products();
                                    
                                    global $nasa_opt;
                                    if (!isset($nasa_opt['compact_number']) || $nasa_opt['compact_number']) {
                                        $count = (int) $count > 9 ? '9+' : (int) $count;
                                    }

                                    $data['wishlistcount'] = $count;
                                }
                                catch (Exception $e){
                                    // $data['message'] = $e->getMessage();
                                }
                            }
                        }
                    }

                    wp_send_json($data);
                } else {
                    // If there was an error adding to the cart, redirect to the product page to show any errors
                    if (isset($validate_attr['missing_attributes'])) {
                        wc_add_notice(sprintf(_n('%s is a required field', '%s are required fields', count($validate_attr['missing_attributes']), 'elessi-theme'), wc_format_list_of_items($validate_attr['missing_attributes'])), 'error');
                    } else {
                        wc_add_notice(esc_html__('Sorry, Maybe product empty in stock.', 'elessi-theme'), 'error');
                    }

                    $data = array(
                        'error' => true,
                        'message' => wc_print_notices(true),
                        'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                    );
                    
                    /**
                     * Clear Old Notices
                     */
                    wc_clear_notices();

                    wp_send_json($data);
                }
            }
        }
        
        /**
         * Combo product
         */
        public static function nasa_combo_products(){
            $output = array();

            if (!defined('YITH_WCPB')) {
                wp_send_json($output);
            }

            global $woocommerce, $nasa_opt;

            if (!$woocommerce || !isset($_REQUEST['id']) || !(int) $_REQUEST['id']){
                wp_send_json($output);
            }

            $product = wc_get_product((int) $_REQUEST['id']);
            if ($product->get_type() != NASA_COMBO_TYPE || !$combo = $product->get_bundled_items()) {
                wp_send_json($output);
            }

            $file = ELESSI_CHILD_PATH . '/includes/nasa-combo-products.php';
            $file = is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-combo-products.php';
            
            ob_start();
            include $file;
            $output['content'] = ob_get_clean();

            wp_send_json($output);
        }
        
        /**
         * Load compare in bottom
         * 
         * Not used from 5.1.5
         */
        public static function nasa_load_compare() {
            $data = array('success' => '0', 'content' => '');
            
            ob_start();
            do_action('nasa_show_mini_compare');
            $data['content'] = ob_get_clean();
            
            if (!empty($data['content'])) {
                $data['success'] = '1';
            }
            
            wp_send_json($data);
        }

        /**
         * Add compare item
         */
        public static function nasa_add_compare_product() {
            $result = array(
                'result_compare' => 'error',
                'mess_compare' => esc_html__('Error!', 'elessi-theme'),
                'mini_compare' => 'no-change',
                'count_compare' => 0
            );
            if (!isset($_REQUEST['pid']) || !(int) $_REQUEST['pid']) {
                wp_send_json($result);
                wp_die();
            }

            global $nasa_opt, $yith_woocompare;
            $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
            
            if (!$nasa_compare) {
                wp_send_json($result);
                wp_die();
            }

            $max_compare = isset($nasa_opt['max_compare']) ? (int) $nasa_opt['max_compare'] : 4;
            
            if (!in_array((int) $_REQUEST['pid'], $nasa_compare->products_list)) {
                if (count($nasa_compare->products_list) >= $max_compare) {
                    while (count($nasa_compare->products_list) >= $max_compare) {
                        array_shift($nasa_compare->products_list);
                    }
                }

                $nasa_compare->add_product_to_compare((int) $_REQUEST['pid']);
                $result['mess_compare'] = esc_html__('Product added to compare !', 'elessi-theme');
            } else {
                $result['mess_compare'] = esc_html__('Product already exists in Compare list !', 'elessi-theme');
            }

            ob_start();
            do_action('nasa_show_mini_compare');
            $result['mini_compare'] = ob_get_clean();

            if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
                $result['result_table'] = elessi_products_compare_content();
            }
            
            $result['count_compare'] = count($nasa_compare->products_list);
            $result['result_compare'] = 'success';

            wp_send_json($result);
        }
        
        /**
         * Remove compare item
         */
        public static function nasa_remove_compare_product() {
            $result = array(
                'result_compare' => 'error',
                'mess_compare' => esc_html__('Error!', 'elessi-theme'),
                'mini_compare' => 'no-change',
                'count_compare' => 0
            );
            
            if (!isset($_REQUEST['pid']) || !(int) $_REQUEST['pid']) {
                wp_send_json($result);
                wp_die();
            }

            global $yith_woocompare;
            $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
            if (!$nasa_compare) {
                wp_send_json($result);
                wp_die();
            }

            if (in_array((int) $_REQUEST['pid'], $nasa_compare->products_list)) {
                $nasa_compare->remove_product_from_compare((int) $_REQUEST['pid']);
                $result['mess_compare'] = esc_html__('Removed product from compare !', 'elessi-theme');
            } else {
                $result['mess_compare'] = esc_html__('Product not already exists in Compare list !', 'elessi-theme');
            }
            
            ob_start();
            do_action('nasa_show_mini_compare');
            $result['mini_compare'] = ob_get_clean();

            if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
                $result['result_table'] = elessi_products_compare_content();
            }

            $result['count_compare'] = count($nasa_compare->products_list);
            $result['result_compare'] = 'success';

            wp_send_json($result);
        }
        
        /**
         * Remove all item compare
         */
        public static function nasa_remove_all_compare() {
            $result = array(
                'result_compare' => 'error',
                'mess_compare' => esc_html__('Error!', 'elessi-theme'),
                'mini_compare' => 'no-change',
                'count_compare' => 0
            );

            /**
             * Yith WooCommerce Compare - v3.x
             */
            if (defined('YITH_WOOCOMPARE_VERSION') && version_compare(YITH_WOOCOMPARE_VERSION, '3.0.0', '>=')) {
                $yth_list = YITH_WooCompare_Products_List::instance();

                if (!empty($yth_list->get())) {
                    if ($yth_list->empty() && $yth_list->maybe_save()) {
                        $result['mess_compare'] = esc_html__('Removed all products from compare !', 'elessi-theme');
                    }
                } else {
                    $result['mess_compare'] = esc_html__('Compare products were empty !', 'elessi-theme');
                }

                ob_start();
                YITH_WooCompare_Frontend::instance()->output_preview_bar();
                $result['mini_compare'] = ob_get_clean();

                $result['count_compare'] = $yth_list->count();
                $result['result_compare'] = 'success';
            }
            
            /**
             * Yith WooCommerce Compare - v2.x
             */
            else {
                global $yith_woocompare;
                
                $nasa_compare = isset($yith_woocompare->obj) ? $yith_woocompare->obj : $yith_woocompare;
                if (!$nasa_compare) {
                    wp_send_json($result);
                    wp_die();
                }

                if (!empty($nasa_compare->products_list)) {
                    $nasa_compare->remove_product_from_compare('all');
                    $result['mess_compare'] = esc_html__('Removed all products from compare !', 'elessi-theme');
                } else {
                    $result['mess_compare'] = esc_html__('Compare products were empty !', 'elessi-theme');
                }

                ob_start();
                do_action('nasa_show_mini_compare');
                $result['mini_compare'] = ob_get_clean();
                
                if (isset($_REQUEST['compare_table']) && $_REQUEST['compare_table']) {
                    $result['result_table'] = elessi_products_compare_content();
                }

                $result['count_compare'] = count($nasa_compare->products_list);
                $result['result_compare'] = 'success';
            }
            
            wp_send_json($result);
        }
        
        /**
         * After Added To Cart get Cross sell
         * 
         * Get Cross-Sells product show in Mini Cart | Popup Cart v2
         */
        public static function nasa_get_cross_sell_mini_cart() {
            $result = array(
                'success' => '0',
                'content' => ''
            );
            
            ob_start();
            woocommerce_cross_sell_display(8, 4);
            $cross_sell = ob_get_clean();
            
            /**
             * Empty Cross-sell
             */
            if (trim($cross_sell) == '') {
                wp_send_json($result);
                wp_die();
            }
            
            $cross_sell = str_replace(
                array(
                    'margin-top-50',
                    'margin-bottom-20',
                    'text-center'
                ),
                array(
                    '',
                    'margin-bottom-10',
                    ''
                ),
                $cross_sell
            );
            
            $result['content'] = $cross_sell;
            $result['success'] = '1';
            
            /**
             * Clear Old Notices
             */
            wc_clear_notices();
            
            wp_send_json($result);
        }


        /**
         * Get site map and located customer in menu map
        */
        public static function nasa_get_site_map_and_located() {
            global $nasa_opt;

            $result = array(
                'success' => '0',
                'content' => ''
            );

            $content_logo = elessi_logo();

            $title = '<a class="ns-map-lcl nasa-bold" href="javascript:void(0);">' .  esc_html__('The page location you are viewing is marked with', 'elessi-theme') . '<svg width="32" height="32" viewBox="0 0 22 32"><path d="M11.001 0.006c-5.889 0-10.663 4.775-10.663 10.663 0 1.945 0.523 3.762 1.432 5.332l9.23 15.994 9.23-15.994c0.909-1.57 1.432-3.387 1.432-5.332 0-5.888-4.774-10.663-10.662-10.663zM11.001 13.334c-1.472 0-2.666-1.193-2.666-2.665 0-1.471 1.194-2.665 2.666-2.665s2.665 1.194 2.665 2.665c0 1.472-1.193 2.665-2.665 2.665z" fill="currentColor"/></svg></a>';

            $content = '<div class="nasa-title"><a class="nasa-close-site-map nasa-stclose" href="javascript:void(0);" title="Close"></a>'. $content_logo . $title .'</div>';


            if (isset($nasa_opt['where_you_are_menu']) && $nasa_opt['where_you_are_menu']) {
                $menu_map = nasa_get_menu_map();
                $content .= '<div class="nasa-site-map"><h1 class="text-center">' . esc_html__('Menu Map', 'elessi-theme') . '</h1>' . $menu_map .'</div>';
            }

            if (isset($nasa_opt['where_you_are_cat']) && $nasa_opt['where_you_are_cat']) {
                $cat_map = nasa_get_categories_map();
                $content .= '<div class="nasa-cat-map"><h1 class="text-center">' . esc_html__('Categories Map', 'elessi-theme') . '</h1>' . $cat_map . '</div>';
            }

            $result['content'] =  $content;
            $result['success'] = '1';
    
            /**
             * Clear Old Notices
             */
            wc_clear_notices();
            
            wp_send_json($result);
        }

        /**
         * Clear All Product In Cart
         */
        public static function nasa_clear_cart() {
            WC()->cart->empty_cart();

            $notice = wc_print_notice(
                wp_kses_post(
                    /**
                     * Filter empty cart message text.
                     *
                     * @since 3.1.0
                     * @param string $message Default empty cart message.
                     * @return string
                     */
                    apply_filters( 'nasa_clear_cart_message', __( 'Your checkout time has expired.', 'elessi-theme' ) )
                ),
                'notice',
                array(),
                true
            );

            $data = array(
                'success' => '1',
                'notice' => $notice
            );

            wp_send_json($data);
        }
        
        /**
         * NasaTheme Load product of Nasa Wishlist
         */
        public static function nasa_load_wishlist() {
            $data = array('success' => '0', 'content' => '');
            
            if (function_exists('elessi_woo_wishlist')) {
                $nasa_wishlist = elessi_woo_wishlist();
                
                if ($nasa_wishlist) {
                    $data = array(
                        'success' => '1',
                        'content' => elessi_mini_wishlist_sidebar(true)
                    );
                }
            }
            
            wp_send_json($data);
        }
        
        /**
         * NasaTheme Add product to wishlist
         */
        public static function nasa_add_to_wishlist() {
            $data = array('success' => '0', 'mess' => '');
            
            if (function_exists('elessi_woo_wishlist') && isset($_REQUEST["product_id"])) {
                $nasa_wishlist = elessi_woo_wishlist();
                
                if ($nasa_wishlist->add_to_wishlist($_REQUEST["product_id"])) {
                    $data = array(
                        'success' => '1',
                        'mess' => apply_filters('nasa_wishlist_mess_add_success', sprintf(
                            '<div class="woocommerce-message text-center" role="alert">%s</div>',
                            esc_html__('Product added to wishlist successfully!', 'elessi-theme')
                        )),
                        'count' => $nasa_wishlist->count_items()
                    );
                    
                    if (isset($_REQUEST['show_content']) && $_REQUEST['show_content']) {
                        $data['content'] = elessi_mini_wishlist_sidebar(true);
                    }
                }
            }
            
            wp_send_json($data);
        }
        
        /**
         * NasaTheme Remove product from wishlist
         */
        public static function nasa_remove_from_wishlist() {
            $data = array('success' => '0', 'mess' => '');
            
            if (function_exists('elessi_woo_wishlist') && isset($_REQUEST["product_id"])) {
                $nasa_wishlist = elessi_woo_wishlist();
                
                if ($nasa_wishlist->remove_from_wishlist($_REQUEST["product_id"])) {
                    $data = array(
                        'success' => '1',
                        'mess' => apply_filters('nasa_wishlist_mess_remove_success', sprintf(
                            '<div class="woocommerce-message text-center" role="alert">%s</div>',
                            esc_html__('Product removed from wishlist successfully!', 'elessi-theme')
                        )),
                        'count' => $nasa_wishlist->count_items()
                    );
                    
                    if (isset($_REQUEST['show_content']) && $_REQUEST['show_content']) {
                        $data['content'] = elessi_mini_wishlist_sidebar(true);
                    }
                }
            }
            
            wp_send_json($data);
        }
        /**
         * NasaTheme Remove wishlist hidden
         */
        public static function nasa_remove_wishlist_hidden() {
            $data = array('success' => '0', 'mess' => '');
            
            if (function_exists('elessi_woo_wishlist') && isset($_REQUEST["product_ids"]) && !empty($_REQUEST["product_ids"])) {
                $nasa_wishlist = elessi_woo_wishlist();
                foreach ($_REQUEST["product_ids"] as $product_id) {
                    $nasa_wishlist->remove_from_wishlist($product_id);
                }
                
                $data = array(
                    'success' => '1',
                    'mess' => sprintf(
                        '<div class="woocommerce-message text-center" role="alert">%s</div>',
                        esc_html__('Product removed from wishlist successfully!', 'elessi-theme')
                    ),
                    'count' => $nasa_wishlist->count_items()
                );
            }
            
            wp_send_json($data);
        }
        
        /**
         * Live Search Products
         */
        public static function nasa_search_products() {
            $data = array();
            
            if (!isset($_REQUEST['s']) || trim($_REQUEST['s']) == '') {
                wp_send_json($data);
                
                return;
            }
            
            global $nasa_opt;
            
            /**
             * Support - Search By SKU
             */
            if (isset($nasa_opt['sp_search_sku']) && $nasa_opt['sp_search_sku']) {
                add_filter('posts_join', 'elessi_sku_search_join');
                add_filter('posts_where', 'elessi_sku_search_where');
                add_filter('posts_groupby', 'elessi_sku_search_groupby');
            }

            $limit = (isset($nasa_opt['limit_results_search']) && (int) $nasa_opt['limit_results_search'] > 0) ? (int) $nasa_opt['limit_results_search'] : 5;
            
            $args = array(
                's' => wc_clean($_REQUEST['s']),
                'post_type' => 'product',
                'post_status' => 'publish',
                'orderby' => 'relevance',
                'order' => 'DESC',
                'posts_per_page' => $limit,
                'paged' => 1
            );
            
            $args['meta_query'] = array();
            $args['meta_query'][] = WC()->query->stock_status_meta_query();
            $args['meta_query'][] = WC()->query->visibility_meta_query();
            
            $args['tax_query'] = array('relation' => 'AND');
            
            $visibility_terms = wc_get_product_visibility_term_ids();
            $terms_not_in = array($visibility_terms['exclude-from-search']);

            // Hide out of stock products.
            $check_instock = false;
            
            if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
                $terms_not_in[] = $visibility_terms['outofstock'];
                $check_instock = true;
            }

            if (!empty($terms_not_in)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'term_taxonomy_id',
                    'terms' => $terms_not_in,
                    'operator' => 'NOT IN',
                );
            }
            
            $query = new WP_Query(apply_filters('nasa_live_search_query_args', $args));
            
            if (isset($query->post_count) && $query->post_count) {
                
                $image_size = apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail');
                
                while ($query->have_posts()) {
                    $query->the_post();

                    global $product;
                    
                    if (empty($product) || !$product->is_visible() || ($check_instock && 'outofstock' == $product->get_stock_status())) {
                        continue;
                    }
                    
                    $sku = isset($nasa_opt['sp_search_sku']) && $nasa_opt['sp_search_sku'] ? $product->get_sku() : '';
                    
                    $data[] = array(
                        'title' => $product->get_name(),
                        'sku' => $sku ? $sku : esc_html__('N/A', 'elessi-theme'),
                        'url' => $product->get_permalink(),
                        'image' => $product->get_image($image_size),
                        'price' => $product->get_price_html()
                    );
                }
            }

            wp_send_json($data);
        }
        
        /**
         * Update quantity mini cart
         */
        public static function nasa_quantity_mini_cart() {
            if (!isset($_REQUEST['hash']) || !isset($_REQUEST['quantity'])) {
                wp_die();
            }
            
            // Set item key as the hash found in input.qty's name
            $cart_item_key = $_REQUEST['hash'];

            // Get the array of values owned by the product we're updating
            $product_values = WC()->cart->get_cart_item($cart_item_key);

            // Get the quantity of the item in the cart
            $product_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_REQUEST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);

            // Update cart validation
            $passed_validation  = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $product_values, $product_quantity);

            // Update the quantity of the item in the cart
            if ($passed_validation) {
                WC()->cart->set_quantity($cart_item_key, $product_quantity, true);
            }
            
            // do_action('woocommerce_update_quantity_mini_cart');

            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            $woo_notices = wc_print_notices(true);
            
            $woo_mess = empty($woo_notices) ? '<div class="woocommerce-message text-center" role="alert">' . esc_html__('Product quantity updated successfully!', 'elessi-theme') . '</div>' : $woo_notices;
            
            if (isset($_REQUEST['no-mess']) && $_REQUEST['no-mess']) {
                $woo_mess = false;
            }

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
            );
            
            if ($woo_mess) {
                $data['woocommerce_add_to_cart_fragments']['.woocommerce-message'] = $woo_mess;
            }
            
            if (WC()->cart->is_empty()) {
                $data['url_redirect'] = apply_filters('nasa_url_redirect_after_update_quantity', esc_url(wc_get_cart_url()));
            }

            wp_send_json($data);
        }
        
        /**
         * ext mini cart node
         */
        public static function nasa_ext_mini_cart() {
            if (!isset($_REQUEST['act']) || !isset($_REQUEST['act'])) {
                wp_die();
            }
            
            $_act = $_REQUEST['act'];
            
            $content = '<div class="ext-node mini-cart-' . esc_attr($_act) . '">';
            $content .= '<a href="javascript:void(0);" title="Close" class="nasa-close-node nasa-stclose" rel="nofollow"></a>';
            
            /**
             * Note
             */
            if ($_act == 'note') {
                ob_start();
                $file = ELESSI_CHILD_PATH . '/includes/nasa-mini-cart-note.php';
                include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-mini-cart-note.php';
                $content .= ob_get_clean();
            }
            
            /**
             * Shipping
             */
            if ($_act == 'shipping') {
                $content .= '<p class="node-title nasa-bold fs-20 mobile-fs-18">' . esc_html__('Estimate shipping rates', 'elessi-theme') . '</p>';
                ob_start();
                woocommerce_shipping_calculator();
                $content .= ob_get_clean();
            }
            
            /**
             * Coupon
             */
            if ($_act == 'coupon') {
                ob_start();
                $file = ELESSI_CHILD_PATH . '/includes/nasa-mini-cart-add-coupon.php';
                include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-mini-cart-add-coupon.php';
                $content .= ob_get_clean();
            }
            
            $content .= '</div>';
            
            $data = array(
                'content' => $content
            );
            
            wp_send_json($data);
        }
        
        /**
         * ext mini cart node
         */
        public static function nasa_all_ext_mini_cart() {
            global $nasa_opt;
            
            $content = '';
            
            /**
             * Add Note
             */
            if (isset($nasa_opt['mini_cart_note']) && $nasa_opt['mini_cart_note'] && apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) {
                $content .= '<div class="ext-node mini-cart-note">';
                $content .= '<a href="javascript:void(0);" title="Close" class="nasa-close-node nasa-stclose" rel="nofollow"></a>';
                
                ob_start();
                $file = ELESSI_CHILD_PATH . '/includes/nasa-mini-cart-note.php';
                include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-mini-cart-note.php';
                $content .= ob_get_clean();
                
                $content .= '</div>';
            }
            
            /**
             * Add Shipping
             */
            if (
                isset($nasa_opt['mini_cart_shipping']) &&
                $nasa_opt['mini_cart_shipping']
            ) {
                $shipping_enable = 'no' === get_option('woocommerce_enable_shipping_calc') || !WC()->cart->needs_shipping() ? false : true;
                if ($shipping_enable) {
                    $content .= '<div class="ext-node mini-cart-shipping">';
                    $content .= '<a href="javascript:void(0);" title="Close" class="nasa-close-node nasa-stclose" rel="nofollow"></a>';
                    $content .= '<p class="node-title nasa-bold fs-20">' . esc_html__('Estimate shipping rates', 'elessi-theme') . '</p>';
                    
                    ob_start();
                    woocommerce_shipping_calculator();
                    $content .= ob_get_clean();
                    
                    $content .= '</div>';
                }
            }
            
            /**
             * Add Coupon
             */
            if (isset($nasa_opt['mini_cart_coupon']) && $nasa_opt['mini_cart_coupon'] && wc_coupons_enabled()) {
                $content .= '<div class="ext-node mini-cart-coupon">';
                $content .= '<a href="javascript:void(0);" title="Close" class="nasa-close-node nasa-stclose" rel="nofollow"></a>';
                
                ob_start();
                $file = ELESSI_CHILD_PATH . '/includes/nasa-mini-cart-add-coupon.php';
                include is_file($file) ? $file : ELESSI_THEME_PATH . '/includes/nasa-mini-cart-add-coupon.php';
                $content .= ob_get_clean();
                
                $content .= '</div>';
            }
            
            if ($content !== '') {
                $content .= self::mini_cart_get_ajax_nonce();
            }
            
            $data = array(
                'content' => $content
            );
            
            wp_send_json($data);
        }
        
        /**
         * ext mini cart Calculate Shipping
         */
        public static function nasa_mini_cart_calculate_shipping() {
            WC_Shortcode_Cart::calculate_shipping();
            
            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    )
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'mess' => wc_print_notices(true)
            );

            wp_send_json($data);
        }

        /**
         * ext mini cart Remove Coupon
         */
        public static function nasa_mini_cart_apply_coupon() {
            check_ajax_referer('apply-coupon', 'security');

            $coupon = isset($_POST['coupon_code']) ? wc_format_coupon_code(wp_unslash($_POST['coupon_code'])) : null;

            if (empty($coupon)) {
                wc_add_notice(WC_Coupon::get_generic_coupon_error(WC_Coupon::E_WC_COUPON_PLEASE_ENTER), 'error');
            } else {
                WC()->cart->add_discount($coupon);
            }
            
            $mess = wc_print_notices(true);
            
            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    )
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'mess' => $mess
            );

            wp_send_json($data);
        }
        
        /**
         * ext mini cart Remove Coupon
         */
        public static function nasa_mini_cart_remove_coupon() {
            check_ajax_referer('remove-coupon', 'security');

            $coupon = isset($_POST['coupon']) ? wc_format_coupon_code(wp_unslash($_POST['coupon'])) : null;

            if (empty($coupon)) {
                $mess = sprintf(
                    '<div class="woocommerce-message nasa-error" role="alert">%s</div>',
                    esc_html__('Sorry there was a problem removing this coupon.', 'elessi-theme')
                );
            } else {
                WC()->cart->remove_coupon($coupon);
                
                $mess = sprintf(
                    '<div class="woocommerce-message" role="alert">%s</div>',
                    esc_html__('Coupon has been removed.', 'elessi-theme')
                );
                
                WC()->cart->calculate_totals();
            }
            
            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    )
                ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'mess' => $mess
            );

            wp_send_json($data);
        }
        
        /**
         * ext mini cart Add Note
         */
        public static function nasa_mini_cart_note() {
            $note = isset($_POST['order_comments']) ? $_POST['order_comments'] : null;

            WC()->session->set('nasa_order_comments' , $note);

            // Fragments and mini cart are returned
            $data = array(
                'mess' => sprintf(
                    '<div class="woocommerce-message" role="alert">%s</div>',
                    esc_html__('Your order notes saved.', 'elessi-theme')
                )
            );

            wp_send_json($data);
        }
        
        /**
         * Ext mini cart _ajax_nonce
         */
        public static function nasa_ext_cart_ajax_nonce() {
            wp_send_json(array(
                'fds' => self::mini_cart_get_ajax_nonce(),
                'scalc' => wp_create_nonce('woocommerce-shipping-calculator')
            ));
        }
        
        /**
         * get_ajax_nonce
         */
        protected static function mini_cart_get_ajax_nonce() {
            $ajax_none = '<div class="mini-cart-ajax-nonce ns-nonce-loaded hidden">';
            $ajax_none .= wp_nonce_field('apply-coupon', 'apply_coupon_nonce', false, false);
            $ajax_none .= wp_nonce_field('remove-coupon', 'remove_coupon_nonce', false, false);
            $ajax_none .= '</div>';
            
            return $ajax_none;
        }
        
        /**
         * Validate Form Checkout Modern
         */
        public static function nasa_validate_checkout_modern() {
            if (!class_exists('Elessi_WC_Checkout_Ext')) {
                require ELESSI_THEME_PATH . '/cores/nasa-wc-checkout-ext.php';
            }
            
            if (class_exists('Elessi_WC_Checkout_Ext')) {
                wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);
                $checkout_ext = Elessi_WC_Checkout_Ext::instance_child();
                $checkout_ext->validate_form_checkout();
            }
            
            wp_die(0);
        }
        
        /**
         * Clear All Notices
         */
        public static function nasa_wc_clear_all_notices() {
            wc_clear_notices();
            wp_die(1);
        }
        
        /**
         * Refresh Nonce fields Login / Register Popup with Cache plugin
         */
        public static function nasa_wc_nonce_fields_rf() {
            $data = array('success' => '', 'content' => array());

            if (!NASA_CORE_USER_LOGGED) {
                $data['content'] = array(
                    'ln' => wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce', true, false),
                    'rn' => wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce', true, false)
                );
            }
            
            if (!empty($data['content'])) {
                $data['success'] = '1';
            }

            wp_send_json($data);
        }

        /**
         * Votes review 
         */
        public static function nasa_vote_review() {
            global $nasa_opt;
            
            if (!isset($nasa_opt['ns_comment_helpful']) || !$nasa_opt['ns_comment_helpful']) {
                wc_add_notice(esc_html__('You are implementing an unavailable function.', 'elessi-theme'), 'error');
                wp_send_json_error(array('message' => wc_print_notices(true)));
            }

            $security = isset($_POST['security']) ? $_POST['security'] : '';

            if ($security === '') {
                wc_add_notice(esc_html__('An error has occurred. Please reload the website and try again.', 'elessi-theme'), 'error');
                wp_send_json_error(array('message' => wc_print_notices(true)));
            } elseif (!wp_verify_nonce($security,'ns_vote_review_nonce')) {
                wc_add_notice(esc_html__('You are trying to make an invalid request.', 'elessi-theme'), 'error');
                wp_send_json_error(array('message' => wc_print_notices(true)));
            }

            $reviewID = isset($_POST['reviewID']) ? intval($_POST['reviewID']) : 0;
        
            if ( $reviewID <= 0 ) {
                wc_add_notice(esc_html__('An error has occurred. Please reload the website and try again.', 'elessi-theme'), 'error');
                wp_send_json_error(array('message' =>  wc_print_notices(true)));
                wc_clear_notices();
            }

            $new_vote = [
                'id' => get_current_user_id(), 
                'ip' => $_SERVER['REMOTE_ADDR']
            ];

            if (isset($nasa_opt['ns_comment_helpful_registered']) && $nasa_opt['ns_comment_helpful_registered']) {
                if ($new_vote['id'] <= 0) {
                    wc_add_notice(esc_html__('Registered users are allowed only.', 'elessi-theme'), 'error');
                    wp_send_json_error(array('message' =>  wc_print_notices(true)));
                    wc_clear_notices();
                }
            }
        
            $nasa_review_votes = get_comment_meta($reviewID, 'nasa_review_votes', true);
            $undo_down = $undo_up = false;
            $upvote = isset($_POST['upvote']) ? filter_var($_POST['upvote'], FILTER_VALIDATE_BOOLEAN) : true;
        
            $nasa_review_votes = !empty($nasa_review_votes) ? maybe_unserialize($nasa_review_votes) : array('upvote' => array(), 'downvote' => array());
            $nasa_review_upvote = is_array($nasa_review_votes['upvote']) ? $nasa_review_votes['upvote'] : array();
            $nasa_review_downvote = is_array($nasa_review_votes['downvote']) ? $nasa_review_votes['downvote'] : array();
            
            $nasa_review_upvote = !$undo_down && !empty($nasa_review_upvote) ? array_filter($nasa_review_upvote, function ($vote) use ($new_vote, &$undo_up) {
                if ($new_vote['id'] <= 0) {
                    if ((isset($vote['ip']) && $vote['ip'] == $new_vote['ip'])) {
                        $undo_up = true;
                        return false;
                    }
                } else {
                    if ((isset($vote['id']) && $vote['id'] == $new_vote['id'])) {
                        $undo_up = true;
                        return false;
                    }
                }
                return true;
            }) : $nasa_review_upvote;
            
            $nasa_review_downvote = !$undo_up && !empty($nasa_review_downvote) ? array_filter($nasa_review_downvote, function ($vote) use ($new_vote, &$undo_down) {
                if ($new_vote['id'] <= 0) {
                    if ((isset($vote['ip']) && $vote['ip'] == $new_vote['ip'])) {
                        $undo_down = true;
                        return false;
                    }
                } else {
                    if ((isset($vote['id']) && $vote['id'] == $new_vote['id'])) {
                        $undo_down = true;
                        return false;
                    }
                }
                
                return true;
                
            }) : $nasa_review_downvote;
        
            if ($upvote) {
                if (!$undo_up) {
                    $nasa_review_upvote[] = $new_vote;
                }
            } else {
                if (!$undo_down) {
                    $nasa_review_downvote[] = $new_vote;
                }
            }
        
            $nasa_review_votes = array(
                'upvote' => $nasa_review_upvote,
                'downvote' => $nasa_review_downvote
            );
            
            update_comment_meta($reviewID, 'nasa_review_votes', $nasa_review_votes);
            
            wp_send_json_success(array('html' => esc_html__('Helpful?', 'elessi-theme') . 
            '<span class="nasa-flex"> 
                <span data-vote="like" class="ns-vote ns-like nasa-flex jc '. ($upvote && !$undo_up ? 'ns-active' : '') .'"> 
                    <svg viewBox="0 0 512 512" fill="currentColor"><path d="M323.8 34.8c-38.2-10.9-78.1 11.2-89 49.4l-5.7 20c-3.7 13-10.4 25-19.5 35l-51.3 56.4c-8.9 9.8-8.2 25 1.6 33.9s25 8.2 33.9-1.6l51.3-56.4c14.1-15.5 24.4-34 30.1-54.1l5.7-20c3.6-12.7 16.9-20.1 29.7-16.5s20.1 16.9 16.5 29.7l-5.7 20c-5.7 19.9-14.7 38.7-26.6 55.5c-5.2 7.3-5.8 16.9-1.7 24.9s12.3 13 21.3 13L448 224c8.8 0 16 7.2 16 16c0 6.8-4.3 12.7-10.4 15c-7.4 2.8-13 9-14.9 16.7s.1 15.8 5.3 21.7c2.5 2.8 4 6.5 4 10.6c0 7.8-5.6 14.3-13 15.7c-8.2 1.6-15.1 7.3-18 15.2s-1.6 16.7 3.6 23.3c2.1 2.7 3.4 6.1 3.4 9.9c0 6.7-4.2 12.6-10.2 14.9c-11.5 4.5-17.7 16.9-14.4 28.8c.4 1.3 .6 2.8 .6 4.3c0 8.8-7.2 16-16 16l-97.5 0c-12.6 0-25-3.7-35.5-10.7l-61.7-41.1c-11-7.4-25.9-4.4-33.3 6.7s-4.4 25.9 6.7 33.3l61.7 41.1c18.4 12.3 40 18.8 62.1 18.8l97.5 0c34.7 0 62.9-27.6 64-62c14.6-11.7 24-29.7 24-50c0-4.5-.5-8.8-1.3-13c15.4-11.7 25.3-30.2 25.3-51c0-6.5-1-12.8-2.8-18.7C504.8 273.7 512 257.7 512 240c0-35.3-28.6-64-64-64l-92.3 0c4.7-10.4 8.7-21.2 11.8-32.2l5.7-20c10.9-38.2-11.2-78.1-49.4-89zM32 192c-17.7 0-32 14.3-32 32L0 448c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-224c0-17.7-14.3-32-32-32l-64 0z"/></svg>
                </span>
                <span class="ns-like-count">' . count($nasa_review_upvote) . '</span>
            </span>
            <span class="nasa-flex">
                <span data-vote="dislike" class="ns-vote ns-dislike nasa-flex jc ' . (!$upvote && !$undo_down ? 'ns-active' : '') . '">
                    <svg viewBox="0 0 512 512" fill="currentColor"><path d="M323.8 477.2c-38.2 10.9-78.1-11.2-89-49.4l-5.7-20c-3.7-13-10.4-25-19.5-35l-51.3-56.4c-8.9-9.8-8.2-25 1.6-33.9s25-8.2 33.9 1.6l51.3 56.4c14.1 15.5 24.4 34 30.1 54.1l5.7 20c3.6 12.7 16.9 20.1 29.7 16.5s20.1-16.9 16.5-29.7l-5.7-20c-5.7-19.9-14.7-38.7-26.6-55.5c-5.2-7.3-5.8-16.9-1.7-24.9s12.3-13 21.3-13L448 288c8.8 0 16-7.2 16-16c0-6.8-4.3-12.7-10.4-15c-7.4-2.8-13-9-14.9-16.7s.1-15.8 5.3-21.7c2.5-2.8 4-6.5 4-10.6c0-7.8-5.6-14.3-13-15.7c-8.2-1.6-15.1-7.3-18-15.2s-1.6-16.7 3.6-23.3c2.1-2.7 3.4-6.1 3.4-9.9c0-6.7-4.2-12.6-10.2-14.9c-11.5-4.5-17.7-16.9-14.4-28.8c.4-1.3 .6-2.8 .6-4.3c0-8.8-7.2-16-16-16l-97.5 0c-12.6 0-25 3.7-35.5 10.7l-61.7 41.1c-11 7.4-25.9 4.4-33.3-6.7s-4.4-25.9 6.7-33.3l61.7-41.1c18.4-12.3 40-18.8 62.1-18.8L384 32c34.7 0 62.9 27.6 64 62c14.6 11.7 24 29.7 24 50c0 4.5-.5 8.8-1.3 13c15.4 11.7 25.3 30.2 25.3 51c0 6.5-1 12.8-2.8 18.7C504.8 238.3 512 254.3 512 272c0 35.3-28.6 64-64 64l-92.3 0c4.7 10.4 8.7 21.2 11.8 32.2l5.7 20c10.9 38.2-11.2 78.1-49.4 89zM32 384c-17.7 0-32-14.3-32-32L0 128c0-17.7 14.3-32 32-32l64 0c17.7 0 32 14.3 32 32l0 224c0 17.7-14.3 32-32 32l-64 0z"/></svg>
                </span>
                <span class="ns-dislike-count">' . count($nasa_review_downvote) . '</span>
            </span>'));
        }
    }

    /**
     * Init ELESSI WC AJAX
     */
    if (isset($_REQUEST['wc-ajax'])) {
        add_action('init', 'elessi_init_wc_ajax');
        if (!function_exists('elessi_init_wc_ajax')) :
            function elessi_init_wc_ajax() {
                ELESSI_WC_AJAX::nasa_init();
            }
        endif;
    }

endif;
