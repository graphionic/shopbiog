<?php

namespace PixelYourSite;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class EST_API {

    private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );

        // Register AJAX handlers for EST
        add_action( 'wp_ajax_pys_save_est_event', array( $this, 'ajax_save_est_event' ) );
        add_action( 'wp_ajax_pys_test_est_ajax', array( $this, 'ajax_test_est_ajax' ) );
    }

    public function register_routes() {
        register_rest_route( 'pys-est/v1', '/modules', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_active_modules' ),
            'permission_callback' => array( $this, 'check_permissions' ),
        ) );

        register_rest_route( 'pys-est/v1', '/post-types', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_post_types' ),
            'permission_callback' => array( $this, 'check_permissions' ),
        ) );

    }

    /**
     * Permission callback for the EST REST endpoints.
     *
     * EST is an admin-only tool that runs on the public frontend (overlay UI),
     * so the request is always made from a logged-in browser session.
     *
     * Nonce transport (checked in this order):
     *   - X-PYS-EST-Nonce header  (preferred, survives redirects / cache)
     *   - X-WP-Nonce header       (WordPress default REST header)
     *   - pys_est_nonce parameter (current minified bundle uses this in the URL)
     *   - _wpnonce parameter      (generic fallback)
     *
     * The frontend uses two distinct nonces under the same `pys_est_nonce` key
     * (see class-pys.php enqueue: `nonce` => pys_event_setup_tool for
     * /modules; `saveEventNonce` => pys_save_est_event for /post-types), so
     * both actions are accepted here.
     *
     * REST cookie-auth note: WordPress REST resets the current user to 0 if
     * the request authenticated via cookies but does NOT carry a `wp_rest`
     * nonce (see rest_cookie_check_errors). EST does not send a `wp_rest`
     * nonce, so by the time this callback runs the user context has been
     * wiped even though the auth cookie is valid. We therefore re-validate
     * the auth cookie here and restore the user before checking the EST
     * nonce (which is session/uid-bound) and the `manage_pys` capability.
     *
     * @param \WP_REST_Request $request The request object.
     * @return bool
     */
    public function check_permissions( $request ) {
        $nonce = $request->get_header( 'x_pys_est_nonce' );
        if ( empty( $nonce ) ) {
            $nonce = $request->get_header( 'x_wp_nonce' );
        }
        if ( empty( $nonce ) ) {
            $nonce = $request->get_param( 'pys_est_nonce' );
        }
        if ( empty( $nonce ) ) {
            $nonce = $request->get_param( '_wpnonce' );
        }

        if ( empty( $nonce ) ) {
            return false;
        }

        // Restore the logged-in user that rest_cookie_check_errors() may have
        // wiped because no `wp_rest` nonce was supplied. We re-validate the
        // browser auth cookie ourselves and trust it only insofar as the EST
        // nonce verification below succeeds for the matching user/session.
        if ( ! is_user_logged_in() ) {
            $cookie_user_id = wp_validate_auth_cookie( '', 'logged_in' );
            if ( ! $cookie_user_id ) {
                return false;
            }
            wp_set_current_user( $cookie_user_id );
        }

        $valid_actions = array( 'pys_event_setup_tool', 'pys_save_est_event' );
        $nonce_ok      = false;
        foreach ( $valid_actions as $action ) {
            if ( wp_verify_nonce( $nonce, $action ) ) {
                $nonce_ok = true;
                break;
            }
        }

        if ( ! $nonce_ok ) {
            return false;
        }

        // Defense in Depth: only users that may manage PYS may use the EST endpoints.
        if ( ! current_user_can( 'manage_pys' ) ) {
            return false;
        }

        return true;
    }

    public function get_active_modules( $request )
    {
        $modules = array();
        $registry = PYS_Events_Registry();

        $custom_params_modules = array(
            'facebook',
            'google_analytics',
            'google_gtm',
            'tiktok',
            'pinterest',
            'bing'
        );

        $custom_event_modules = array(
            'facebook',
            'google_analytics',
            'google_gtm',
            'tiktok',
            'pinterest',
            'bing'
        );

        // Facebook/Meta
        if (Facebook()->enabled()) {
            $pixel_ids = Facebook()->getPixelIDs();
            $pixels = array();

            // Add main pixels with (global) label
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => $pixel_id . ' (global)',
                );
            }

            // Add additional pixels from SuperPack with (conditional) label
            if (isSuperPackActive('3.0.0')
                && SuperPack()->getOption('enabled')
                && SuperPack()->getOption('additional_ids_enabled')) {
                $additionalPixels = SuperPack()->getFbAdditionalPixel();
                foreach ($additionalPixels as $aPixel) {
                    $pixels[] = array(
                        'id' => $aPixel->pixel,
                        'name' => $aPixel->pixel . ' (conditional)',
                    );
                }
            }

            // Get event types from registry (single source of truth)
            $event_types = $registry->get_events_for_est_api('facebook');

            $modules['facebook'] = array(
                'id' => 'facebook',
                'name' => 'Meta',
                'logo' => PYS_URL . '/dist/images/meta-logo.svg',
                'enabled' => Facebook()->enabled(),
                'configured' => Facebook()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $event_types,
                'supports_custom_params' => in_array('facebook', $custom_params_modules, true),
                'supports_custom_events' => in_array('facebook', $custom_event_modules, true),
            );
        }

        // Google Analytics (merged with Google Ads)
        if ((Ads()->enabled() && !empty(Ads()->getPixelIDs())) || (GA()->enabled() && !empty(GA()->getPixelIDs()))) {
            $pixels = array();

            // Add GA4 pixels with (global) label
            $ga_pixel_ids = GA()->getPixelIDs();
            foreach ($ga_pixel_ids as $pixel_id) {
                // Filter out UA- pixels (not supported)
                if (strpos($pixel_id, 'UA-') === false) {
                    $pixels[] = array(
                        'id' => $pixel_id,
                        'name' => $pixel_id . ' (global)',
                    );
                }
            }

            // Add Google Ads pixels with (global) label
            $ads_pixel_ids = Ads()->getPixelIDs();
            foreach ($ads_pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => $pixel_id . ' (global)',
                );
            }

            // Add additional GA pixels from SuperPack with (conditional) label
            if (isSuperPackActive('3.0.0')
                && SuperPack()->getOption('enabled')
                && SuperPack()->getOption('additional_ids_enabled')) {
                $additionalPixels = SuperPack()->getGaAdditionalPixel();
                foreach ($additionalPixels as $aPixel) {
                    // Filter out UA- pixels (not supported)
                    if (strpos($aPixel->pixel, 'UA-') === false) {
                        $pixels[] = array(
                            'id' => $aPixel->pixel,
                            'name' => $aPixel->pixel . ' (conditional)',
                        );
                    }
                }
            }

            // Add additional Google Ads pixels from SuperPack with (conditional) label
            if (isSuperPackActive('3.0.0')
                && SuperPack()->getOption('enabled')
                && SuperPack()->getOption('additional_ids_enabled')) {
                $additionalPixels = SuperPack()->getAdsAdditionalPixel();
                foreach ($additionalPixels as $aPixel) {
                    $pixels[] = array(
                        'id' => $aPixel->pixel,
                        'name' => $aPixel->pixel . ' (conditional)',
                    );
                }
            }

            // Get grouped GA events from registry (single source of truth)
            $grouped_events = $registry->get_events_for_est_api('google_analytics');


            $modules['google_analytics'] = array(
                'id' => 'google_analytics',
                'name' => 'Google Tag',
                'logo' => PYS_URL . '/dist/images/google-tags-logo.svg',
                'enabled' => GA()->enabled() || Ads()->enabled(),
                'configured' => (GA()->enabled() || Ads()->enabled()) && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $grouped_events,
                'supports_custom_params' => in_array('google_analytics', $custom_params_modules, true),
                'supports_custom_events' => in_array('google_analytics', $custom_event_modules, true),
            );
        }

        // TikTok
        if (Tiktok()->enabled()) {
            $pixel_ids = Tiktok()->getPixelIDs();
            $pixels = array();
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => 'TikTok ' . $pixel_id,
                );
            }

            // Get event types from registry (single source of truth)
            $event_types = $registry->get_events_for_est_api('tiktok');

            $modules['tiktok'] = array(
                'id' => 'tiktok',
                'name' => 'TikTok',
                'logo' => PYS_URL . '/dist/images/tiktok-logo.svg',
                'enabled' => Tiktok()->enabled(),
                'configured' => Tiktok()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $event_types,
                'supports_custom_params' => in_array('tiktok', $custom_params_modules, true),
                'supports_custom_events' => in_array('tiktok', $custom_event_modules, true),
            );
        }

        // Bing
        if (Bing()->enabled()) {
            $pixel_ids = Bing()->getPixelIDs();
            $pixels = array();
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => 'Bing ' . $pixel_id,
                );
            }

            // Get event types from registry (single source of truth)
            $event_types = $registry->get_events_for_est_api('bing');

            $modules['bing'] = array(
                'id' => 'bing',
                'name' => 'Bing',
                'logo' => PYS_URL . '/dist/images/bing-logo.svg',
                'enabled' => Bing()->enabled(),
                'configured' => Bing()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $event_types,
                'supports_custom_params' => in_array('bing', $custom_params_modules, true),
                'supports_custom_events' => in_array('bing', $custom_event_modules, true),
            );
        }

        // Pinterest
        if (Pinterest()->enabled()) {
            $pixel_ids = Pinterest()->getPixelIDs();
            $pixels = array();
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => 'Pinterest ' . $pixel_id,
                );
            }

            // Get event types from registry (single source of truth)
            $event_types = $registry->get_events_for_est_api('pinterest');

            $modules['pinterest'] = array(
                'id' => 'pinterest',
                'name' => 'Pinterest',
                'logo' => PYS_URL . '/dist/images/pinterest-logo.svg',
                'enabled' => Pinterest()->enabled(),
                'configured' => Pinterest()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $event_types,
                'supports_custom_params' => in_array('pinterest', $custom_params_modules, true),
                'supports_custom_events' => in_array('pinterest', $custom_event_modules, true),
            );
        }

        if (Reddit()->enabled()) {
            $pixel_ids = Reddit()->getPixelIDs();
            $pixels = array();
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => 'Reddit ' . $pixel_id,
                );
            }

            // Get event types from registry (single source of truth)
            $event_types = $registry->get_events_for_est_api('reddit');

            $modules['reddit'] = array(
                'id' => 'reddit',
                'name' => 'Reddit',
                'logo' => PYS_URL . '/dist/images/reddit-logo.svg',
                'enabled' => Reddit()->enabled(),
                'configured' => Reddit()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $event_types,
                'supports_custom_params' => false,
                'supports_custom_events' => false,
            );
        }

        // Google Tag Manager
        if (GTM()->enabled()) {
            $pixel_ids = GTM()->getPixelIDs();
            $pixels = array();
            foreach ($pixel_ids as $pixel_id) {
                $pixels[] = array(
                    'id' => $pixel_id,
                    'name' => 'GTM ' . $pixel_id,
                );
            }

            // Get grouped GTM events from registry (single source of truth)
            $grouped_events = $registry->get_events_for_est_api('gtm');

            $modules['google_gtm'] = array(
                'id' => 'google_gtm',
                'name' => 'Google Tag Manager',
                'logo' => PYS_URL . '/dist/images/gtm-logo.svg',
                'enabled' => GTM()->enabled(),
                'configured' => GTM()->enabled() && !empty($pixel_ids),
                'pixels' => $pixels,
                'event_types' => $grouped_events,
                'supports_custom_params' => in_array('google_gtm', $custom_params_modules, true),
                'supports_custom_events' => in_array('google_gtm', $custom_event_modules, true),
            );
        }

        return rest_ensure_response($modules);
    }



    public function get_post_types( $request ) {
        $types = get_post_types( null, "objects" );
        $post_types = array();

        foreach ( $types as $type ) {
            // Skip built-in WordPress post types that are not commonly used for events
            if ( in_array( $type->name, array( 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block' ) ) ) {
                continue;
            }

            $post_types[] = array(
                'value' => $type->name,
                'label' => $type->label ? $type->label . ' (' . $type->name . ')' : ucfirst( $type->name ),
                'description' => isset( $type->description ) ? $type->description : ''
            );
        }

        return rest_ensure_response( $post_types );
    }


    /**
     * AJAX handler for saving EST events
     */
    public function ajax_save_est_event() {

        // Log the request for debugging
        PYS()->getLog()->debug('EST AJAX Request received');
        PYS()->getLog()->debug('POST data: ' , print_r($_POST, true));
        PYS()->getLog()->debug('REQUEST data: ' , print_r($_REQUEST, true));

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'pys_save_est_event' ) ) {
            PYS()->getLog()->debug('EST AJAX: Nonce verification failed');
            wp_send_json_error( array( 'message' => 'Security check failed' ) );
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'manage_pys' ) ) {
            PYS()->getLog()->debug('EST AJAX: User does not have manage_pys capability');
            wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
            return;
        }

        // Get and validate EST data
        $est_data_json = isset( $_POST['est_data'] ) ? stripslashes( $_POST['est_data'] ) : '';

        if ( empty( $est_data_json ) ) {
            PYS()->getLog()->debug('EST AJAX: No est_data provided');
            wp_send_json_error( array( 'message' => 'No event data provided' ) );
            return;
        }

        $est_data = json_decode( $est_data_json, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            PYS()->getLog()->debug('EST AJAX: JSON decode error: ' . json_last_error_msg());
            wp_send_json_error( array( 'message' => 'Invalid JSON data' ) );
            return;
        }

        PYS()->getLog()->debug('EST AJAX: Decoded data: ' , print_r($est_data, true));

        try {
            // Transform EST data to PYS format
            $pys_event_data = $this->transform_est_data_to_pys_event( $est_data );
            PYS()->getLog()->debug('EST AJAX: Transformed data: ' , print_r($pys_event_data, true));

            // Create the event using CustomEventFactory
            $event = CustomEventFactory::create( $pys_event_data );

            if ( $event && is_object( $event ) ) {
                $event_id = $event->getPostId();
                PYS()->getLog()->debug('EST AJAX: Event created successfully with ID: ' . $event_id);

                // Clear cache
                purgeCache();

                wp_send_json_success( array(
                    'message' => 'Event created successfully!',
                    'event_id' => $event_id,
                    'event_title' => $event->getTitle(),
                    'redirect_url' => buildAdminUrl( 'pixelyoursite', 'events' )
                ) );
            } else {
                PYS()->getLog()->debug('EST AJAX: Failed to create event - CustomEventFactory returned: ' . gettype($event));
                wp_send_json_error( array( 'message' => 'Failed to create event' ) );
            }

        } catch ( Exception $e ) {
            PYS()->getLog()->debug('EST AJAX: Exception occurred: ' . $e->getMessage());
            wp_send_json_error( array( 'message' => 'Error: ' . $e->getMessage() ) );
        }
    }

    /**
     * Transform EST data format to PYS event format
     */
    private function transform_est_data_to_pys_event( $est_data ) {

        // Base event data structure matching CustomEvent::update expectations
        $pys_data = array(
            // General settings
            'title' => sanitize_text_field( $est_data['general']['eventName'] ),
            'enabled' => isset( $est_data['general']['enabled'] ) ? (bool) $est_data['general']['enabled'] : true,
            'enable_time_window' => isset( $est_data['general']['enableTimeWindow'] ) ? (bool) $est_data['general']['enableTimeWindow'] : false,
            'time_window' => isset( $est_data['general']['timeWindow'] ) ? (int) $est_data['general']['timeWindow'] : 24,
            'delay' => 0,
            // Conditions settings (defaults)
            'conditions_enabled' => false,
            'conditions_logic' => 'OR',
            // Trigger logic and fire frequency
            'trigger_logic' => 'OR',
            'fire_frequency' => 'every_time',
        );

        // Handle triggers - EST sends array of triggers, transform to PYS format
        $pys_data['triggers'] = array();
        
        if ( isset( $est_data['triggers'] ) && is_array( $est_data['triggers'] ) ) {
            foreach ( $est_data['triggers'] as $trigger ) {
                $allowed_triggers = array(
                    'page_visit', 'home_page', 'add_to_cart', 'purchase', 'number_page_visit',
                    'url_click', 'css_click', 'css_mouseover', 'copy_element', 'scroll_pos', 'post_type',
                    'video_view', 'video_speed', 'email_link', 'form_field'
                );

                if ( isset( $trigger['type'] ) && in_array( $trigger['type'], $allowed_triggers ) ) {
                    $pys_trigger = array(
                        'trigger_type' => sanitize_text_field( $trigger['type'] )
                    );

                    // Add trigger-specific data based on CustomEvent structure
                    switch ( $trigger['type'] ) {
                        case 'css_click':
                            if ( isset( $trigger['selectedElement'] ) ) {
                                $pys_trigger['css_click_triggers'] = array(
                                    array(
                                        'value' => sanitize_text_field( $trigger['selectedElement']['selector'] ?? '' )
                                    )
                                );
                            }
                            if ( isset( $trigger['click_count'] ) ) {
                                $pys_trigger['click_count'] = max( 1, (int) $trigger['click_count'] );
                            }
                            $pys_trigger['click_time_limit'] = isset( $trigger['click_time_limit'] ) ? max( 0, (int) $trigger['click_time_limit'] ) : 0;
                            break;

                        case 'css_mouseover':
                            if ( isset( $trigger['selectedElement'] ) ) {
                                $pys_trigger['css_mouseover_triggers'] = array(
                                    array(
                                        'value' => sanitize_text_field( $trigger['selectedElement']['selector'] ?? '' )
                                    )
                                );
                            }
                            break;

                        case 'copy_element':
                            if ( isset( $trigger['selectedElement'] ) ) {
                                $pys_trigger['copy_element_triggers'] = array(
                                    array(
                                        'value' => sanitize_text_field( $trigger['selectedElement']['selector'] ?? '' )
                                    )
                                );
                            }
                            break;

                        case 'url_click':
                        case 'email_link':
                            if ( isset( $trigger['selectedElement'] ) ) {
                                $pys_trigger[$trigger['type'] . '_triggers'] = array(
                                    array(
                                        'rule' => sanitize_text_field( $trigger['rule'] ?? 'match' ),
                                        'value' => sanitize_text_field( $trigger['selectedElement']['selector'] ?? '' )
                                    )
                                );
                            }
                            break;

                        case 'form_field':
                            // Handle form field trigger data
                            if ( isset( $trigger['formFieldUrls'] ) && is_array( $trigger['formFieldUrls'] ) ) {
                                $pys_trigger['form_field_urls'] = array_map( 'sanitize_text_field', $trigger['formFieldUrls'] );
                            }
                            
                            if ( isset( $trigger['formFieldData'] ) ) {
                                $pys_trigger['form_field_data'] = sanitize_text_field( $trigger['formFieldData'] );
                            }
                            
                            if ( isset( $trigger['formField'] ) && is_array( $trigger['formField'] ) ) {
                                $pys_trigger['form_field'] = array(
                                    'form' => sanitize_text_field( $trigger['formField']['form'] ?? '' ),
                                    'field' => sanitize_text_field( $trigger['formField']['field'] ?? '' ),
                                    'type' => sanitize_text_field( $trigger['formField']['type'] ?? 'text' )
                                );
                            }
                            
                            // Mark as EST created
                            $pys_trigger['created_via_est'] = true;
                            break;

                        case 'video_view':
                            // video_view_urls - URL of the page where scanning occurred
                            if ( isset( $trigger['scanUrl'] ) ) {
                                $pys_trigger['video_view_urls'] = array( sanitize_text_field( $trigger['scanUrl'] ) );
                            } else {
                                // Fallback to current page URL without parameters
                                $current_url = get_site_url() . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
                                $pys_trigger['video_view_urls'] = array( $current_url );
                            }
                            
                            // video_view_data - complete video information in JSON format
                            if ( isset( $trigger['videoData'] ) && is_array( $trigger['videoData'] ) ) {
                                $sanitized_video_data = map_deep( $trigger['videoData'], 'sanitize_text_field' );
                                $pys_trigger['video_view_data'] = wp_json_encode( $sanitized_video_data, JSON_UNESCAPED_UNICODE );
                            } else {
                                $pys_trigger['video_view_data'] = wp_json_encode( array(), JSON_UNESCAPED_UNICODE );
                            }
                            
                            // video_view_triggers - IDs of selected videos
                            if ( isset( $trigger['selectedVideos'] ) && is_array( $trigger['selectedVideos'] ) ) {
                                $pys_trigger['video_view_triggers'] = array_map( 'sanitize_text_field', $trigger['selectedVideos'] );
                            }
                            
                            if ( isset( $trigger['videoPlayTrigger'] ) ) {
                                $pys_trigger['video_view_play_trigger'] = sanitize_text_field( $trigger['videoPlayTrigger'] );
                            }
                            if ( isset( $trigger['videoDisableWatchVideo'] ) ) {
                                $pys_trigger['video_view_disable_watch_video'] = (bool) $trigger['videoDisableWatchVideo'];
                            }
                            break;

                        case 'video_speed':
                            if ( isset( $trigger['scanUrl'] ) ) {
                                $pys_trigger['video_speed_urls'] = array( sanitize_text_field( $trigger['scanUrl'] ) );
                            } else {
                                $current_url = get_site_url() . strtok( $_SERVER['REQUEST_URI'] ?? '/', '?' );
                                $pys_trigger['video_speed_urls'] = array( $current_url );
                            }

                            if ( isset( $trigger['videoData'] ) && is_array( $trigger['videoData'] ) ) {
                                $sanitized_video_data = map_deep( $trigger['videoData'], 'sanitize_text_field' );
                                $pys_trigger['video_speed_data'] = wp_json_encode( $sanitized_video_data, JSON_UNESCAPED_UNICODE );
                            } else {
                                $pys_trigger['video_speed_data'] = wp_json_encode( array(), JSON_UNESCAPED_UNICODE );
                            }

                            if ( isset( $trigger['selectedVideos'] ) && is_array( $trigger['selectedVideos'] ) ) {
                                $pys_trigger['video_speed_triggers'] = array_map( 'sanitize_text_field', $trigger['selectedVideos'] );
                            }

                            $allowed_rates = array( 'increase', 'decrease', 'any' );
                            $rate = isset( $trigger['videoSpeedRate'] ) ? $trigger['videoSpeedRate'] : 'any';
                            $pys_trigger['video_speed_rate'] = in_array( $rate, $allowed_rates ) ? $rate : 'any';
                            break;

                        case 'scroll_pos':
                            $pys_trigger['scroll_pos_triggers'] = array(
                                array(
                                    'value' => isset( $trigger['scrollPosition'] ) ? (int) $trigger['scrollPosition'] : 50
                                )
                            );
                            break;

                        case 'number_page_visit':
                            $pys_trigger['number_visit'] = isset( $trigger['pageVisitCount'] ) ? (int) $trigger['pageVisitCount'] : 1;
                            $pys_trigger['conditional_number_visit'] = isset( $trigger['conditionalNumberVisit'] ) ? 
                                sanitize_text_field( $trigger['conditionalNumberVisit'] ) : 'equal';
                            
                            // Handle number page visit URL triggers
                            if ( isset( $trigger['numberPageVisitTriggers'] ) && is_array( $trigger['numberPageVisitTriggers'] ) ) {
                                $pys_trigger['number_page_visit_triggers'] = array();
                                foreach ( $trigger['numberPageVisitTriggers'] as $url_trigger ) {
                                    $pys_trigger['number_page_visit_triggers'][] = array(
                                        'rule' => sanitize_text_field( $url_trigger['rule'] ?? 'contains' ),
                                        'value' => sanitize_text_field( $url_trigger['value'] ?? '' )
                                    );
                                }
                            }
                            break;

                        case 'post_type':
                            $pys_trigger['post_type_value'] = isset( $trigger['postType'] ) ?
                                sanitize_text_field( $trigger['postType'] ) : 'post';
                            break;

                        case 'page_visit':
                            // Handle page visit URL triggers
                            if ( isset( $trigger['pageVisitTriggers'] ) && is_array( $trigger['pageVisitTriggers'] ) ) {
                                $pys_trigger['page_visit_triggers'] = array();
                                foreach ( $trigger['pageVisitTriggers'] as $url_trigger ) {
                                    $pys_trigger['page_visit_triggers'][] = array(
                                        'rule' => sanitize_text_field( $url_trigger['rule'] ?? 'contains' ),
                                        'value' => sanitize_text_field( $url_trigger['value'] ?? '' )
                                    );
                                }
                            }
                            break;

                        case 'form_field':
                            // Handle form field trigger data
                            if ( isset( $trigger['formFieldUrls'] ) && is_array( $trigger['formFieldUrls'] ) ) {
                                $pys_trigger['form_field_urls'] = array_map( 'sanitize_text_field', $trigger['formFieldUrls'] );
                            }
                            
                            if ( isset( $trigger['formFieldData'] ) ) {
                                $pys_trigger['form_field_data'] = sanitize_text_field( $trigger['formFieldData'] );
                            }
                            
                            if ( isset( $trigger['formField'] ) && is_array( $trigger['formField'] ) ) {
                                $pys_trigger['form_field'] = array(
                                    'form' => sanitize_text_field( $trigger['formField']['form'] ?? '' ),
                                    'field' => sanitize_text_field( $trigger['formField']['field'] ?? '' ),
                                    'type' => sanitize_text_field( $trigger['formField']['type'] ?? 'text' )
                                );
                            }
                            
                            // Mark as EST created
                            $pys_trigger['created_via_est'] = true;
                            break;
                    }

                    // Add delay if present
                    if ( isset( $trigger['delay'] ) ) {
                        $pys_trigger['delay'] = (int) $trigger['delay'];
                    }

                    $pys_data['triggers'][] = $pys_trigger;
                }
            }
        } else {
            // Fallback for old single trigger format
        $allowed_triggers = array(
            'page_visit', 'home_page', 'add_to_cart', 'purchase', 'number_page_visit',
            'url_click', 'css_click', 'css_mouseover', 'scroll_pos', 'post_type',
            'video_view', 'email_link', 'form_field'
        );

        if ( isset( $est_data['trigger'] ) && in_array( $est_data['trigger'], $allowed_triggers ) ) {
                $pys_trigger = array(
                    'trigger_type' => sanitize_text_field( $est_data['trigger'] )
                );

            // Add trigger-specific data based on CustomEvent structure
            switch ( $est_data['trigger'] ) {
                case 'css_click':
                case 'css_mouseover':
                    if ( isset( $est_data['selectedElement']['selector'] ) ) {
                            $pys_trigger[$est_data['trigger'] . '_triggers'] = array(
                                array(
                                    'value' => sanitize_text_field( $est_data['selectedElement']['selector'] )
                                )
                            );
                    }
                    break;

                case 'url_click':
                    if ( isset( $est_data['selectedElement']['href'] ) ) {
                            $pys_trigger['url_click_triggers'] = array(
                                array(
                                    'rule' => sanitize_text_field( $est_data['rule'] ?? 'match' ),
                                    'value' => sanitize_text_field( $est_data['selectedElement']['href'] )
                                )
                            );
                    }
                    break;

                case 'video_view':
                    // video_view_urls - URL of the page where scanning occurred
                    if ( isset( $est_data['scanUrl'] ) ) {
                            $pys_trigger['video_view_urls'] = array( sanitize_text_field( $est_data['scanUrl'] ) );
                    } else {
                            // Fallback to current page URL without parameters
                            $current_url = get_site_url() . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
                            $pys_trigger['video_view_urls'] = array( $current_url );
                    }
                    
                    // video_view_data - complete video information in JSON format
                    if ( isset( $est_data['videoData'] ) && is_array( $est_data['videoData'] ) ) {
                            $sanitized_video_data = map_deep( $est_data['videoData'], 'sanitize_text_field' );
                            $pys_trigger['video_view_data'] = wp_json_encode( $sanitized_video_data, JSON_UNESCAPED_UNICODE );
                    } else {
                            $pys_trigger['video_view_data'] = wp_json_encode( array(), JSON_UNESCAPED_UNICODE );
                    }
                    
                    // video_view_triggers - IDs of selected videos
                    if ( isset( $est_data['selectedVideos'] ) && is_array( $est_data['selectedVideos'] ) ) {
                            $pys_trigger['video_view_triggers'] = array_map( 'sanitize_text_field', $est_data['selectedVideos'] );
                    }
                    
                    if ( isset( $est_data['videoPlayTrigger'] ) ) {
                            $pys_trigger['video_view_play_trigger'] = sanitize_text_field( $est_data['videoPlayTrigger'] );
                    }
                    if ( isset( $est_data['videoDisableWatchVideo'] ) ) {
                            $pys_trigger['video_view_disable_watch_video'] = (bool) $est_data['videoDisableWatchVideo'];
                    }
                    break;

                case 'form_field':
                    if ( isset( $est_data['selectedElement']['selector'] ) ) {
                            $pys_trigger['form_field_urls'] = array( sanitize_text_field( $est_data['selectedElement']['selector'] ) );
                            $pys_trigger['form_field_data'] = json_encode( array() ); // Empty data for now
                    }
                    
                    // Mark as EST created
                    $pys_trigger['created_via_est'] = true;
                    break;

                case 'scroll_pos':
                        $pys_trigger['scroll_pos_triggers'] = array(
                            array(
                                'value' => 50 // Default scroll position
                            )
                        );
                    break;

                case 'number_page_visit':
                        $pys_trigger['number_visit'] = isset( $est_data['numberVisit'] ) ?
                        (int) $est_data['numberVisit'] : 1;
                    break;

                case 'post_type':
                        $pys_trigger['post_type_value'] = isset( $est_data['postType'] ) ?
                        sanitize_text_field( $est_data['postType'] ) : 'post';
                    break;
                }

                $pys_data['triggers'][] = $pys_trigger;
            } else {
                // Default trigger type
                $pys_data['triggers'][] = array(
                    'trigger_type' => 'page_visit'
                );
            }
        }

        // Handle conditions - EST sends conditions object with enabled, logic, and conditions array
        if ( isset( $est_data['conditions'] ) && is_array( $est_data['conditions'] ) ) {
            // Update conditions_enabled and conditions_logic from the conditions object
            $pys_data['conditions_enabled'] = isset( $est_data['conditions']['enabled'] ) ? (bool) $est_data['conditions']['enabled'] : false;
            $pys_data['conditions_logic'] = isset( $est_data['conditions']['logic'] ) ? sanitize_text_field( $est_data['conditions']['logic'] ) : 'OR';
            
            // Transform the conditions array
            $pys_data['conditions'] = $this->transform_conditions_to_pys_format( $est_data['conditions']['conditions'] ?? array() );
        } else {
            $pys_data['conditions'] = array();
        }

        // Handle trigger logic
        if ( isset( $est_data['trigger_logic'] ) && in_array( $est_data['trigger_logic'], array( 'AND', 'OR' ) ) ) {
            $pys_data['trigger_logic'] = $est_data['trigger_logic'];
        }

        // Handle fire frequency
        if ( isset( $est_data['fire_frequency'] ) && in_array( $est_data['fire_frequency'], array( 'once', 'every_time' ) ) ) {
            $pys_data['fire_frequency'] = $est_data['fire_frequency'];
        }

        // Initialize all module settings to false first
        $pys_data['facebook_enabled'] = false;
        $pys_data['ga_enabled'] = false;
        $pys_data['gtm_enabled'] = false;
        $pys_data['pinterest_enabled'] = false;
        $pys_data['tiktok_enabled'] = false;
        $pys_data['bing_enabled'] = false;

        // Handle modules (Facebook, GA, etc.)
        if ( isset( $est_data['modules'] ) && is_array( $est_data['modules'] ) ) {
            foreach ( $est_data['modules'] as $module_id => $module_config ) {
                if ( isset( $module_config['enabled'] ) && $module_config['enabled'] ) {
                    switch ( $module_id ) {
                        case 'facebook':
                            $pys_data['facebook_enabled'] = true;

                            // Handle pixel ID selection (can be array for multi-select or string for single)
                            if ( isset( $module_config['pixelId'] ) ) {
                                if ( is_array( $module_config['pixelId'] ) ) {
                                    // Multi-select: sanitize each pixel ID
                                    $pys_data['facebook_pixel_id'] = array_map( 'sanitize_text_field', $module_config['pixelId'] );
                                } else {
                                    // Single select: wrap in array
                                    $pys_data['facebook_pixel_id'] = array( sanitize_text_field( $module_config['pixelId'] ) );
                                }
                            } else {
                                $pys_data['facebook_pixel_id'] = array( 'all' );
                            }

                            $pys_data['facebook_event_type'] = isset( $module_config['eventType'] ) ?
                                sanitize_text_field( $module_config['eventType'] ) : 'ViewContent';

                            // Handle custom event type
                            if ( $pys_data['facebook_event_type'] === 'CustomEvent' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['facebook_custom_event_type'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            // Handle parameters
                            $pys_data['facebook_params_enabled'] = isset( $module_config['paramsEnabled'] ) ?
                                (bool) $module_config['paramsEnabled'] : false;

                            if ( $pys_data['facebook_params_enabled'] && isset( $module_config['params'] ) ) {
                                $pys_data['facebook_params'] = $this->sanitize_event_params( $module_config['params'] );
                            }

                            $facebook_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['facebook_custom_params'] = $pys_data['facebook_params_enabled'] ? $facebook_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['facebook_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['facebook_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;

                        case 'ga':
                        case 'google_analytics':
                            $pys_data['ga_ads_enabled'] = true;

                            // Handle pixel ID selection (can be array for multi-select or string for single)
                            if ( isset( $module_config['pixelId'] ) ) {
                                if ( is_array( $module_config['pixelId'] ) ) {
                                    // Multi-select: sanitize each pixel ID
                                    $pys_data['ga_ads_pixel_id'] = array_map( 'sanitize_text_field', $module_config['pixelId'] );
                                } else {
                                    // Single select: wrap in array
                                    $pys_data['ga_ads_pixel_id'] = array( sanitize_text_field( $module_config['pixelId'] ) );
                                }
                            } else {
                                $pys_data['ga_ads_pixel_id'] = array( 'all' );
                            }

                            // Handle grouped GA events from GroupedEventSelector
                            if ( isset( $module_config['selectedGAEvent'] ) && is_array( $module_config['selectedGAEvent'] ) ) {
                                $selected_event = $module_config['selectedGAEvent'];
                                $pys_data['ga_ads_event_action'] = sanitize_text_field( $selected_event['value'] );

                                // Save the group information
                                if ( isset( $selected_event['group'] ) ) {
                                    $pys_data['ga_ads_event_action_group'] = sanitize_text_field( $selected_event['group'] );
                                }

                                // Sanitize all params and filter by selected event params
                                $sanitized_params = $this->sanitize_event_params( $module_config['params'] ?? array() );
                                foreach ($selected_event['params'] as $param) {
                                    if ( isset( $sanitized_params[$param['name']] ) ) {
                                        $pys_data['ga_ads_params'][$param['name']] = $sanitized_params[$param['name']];
                                    }
                                }
                            } else {
                                // Fallback for old format or direct eventAction
                                $pys_data['ga_ads_event_action'] = isset( $module_config['eventAction'] ) ?
                                    sanitize_text_field( $module_config['eventAction'] ) : 'view_item';
                            }

                            // Handle custom event action (for CustomEvent type)
                            if ( $pys_data['ga_ads_event_action'] === 'CustomEvent' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['ga_ads_custom_event_action'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            $ga_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['ga_ads_custom_params'] = ! empty( $module_config['paramsEnabled'] ) ? $ga_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['ga_ads_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['ga_ads_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;

                            // Handle conversion label (optional)
                            // Use same sanitization as in CustomEvent::updateUnifyGA()
                            $sanitizeGoogleAdsConversionLabel = function ($label) {
                                return wp_kses_post( trim( stripslashes( $label ) ) );
                            };
                            $pys_data['ga_ads_conversion_label'] = ! empty( $module_config['conversionLabel'] )
                                ? $sanitizeGoogleAdsConversionLabel( $module_config['conversionLabel'] )
                                : null;
                            break;

                        case 'gtm':
                        case 'google_gtm':
                            $pys_data['gtm_enabled'] = true;

                            // Handle pixel ID selection (can be array for multi-select or string for single)
                            if ( isset( $module_config['pixelId'] ) ) {
                                if ( is_array( $module_config['pixelId'] ) ) {
                                    // Multi-select: sanitize each pixel ID
                                    $pys_data['gtm_pixel_id'] = array_map( 'sanitize_text_field', $module_config['pixelId'] );
                                } else {
                                    // Single select: wrap in array
                                    $pys_data['gtm_pixel_id'] = array( sanitize_text_field( $module_config['pixelId'] ) );
                                }
                            } else {
                                // GTM requires specific pixel, get first available
                                $all_gtm = GTM()->getAllPixels();
                                $pys_data['gtm_pixel_id'] = !empty( $all_gtm ) ? array( $all_gtm[0] ) : array();
                            }

                        // Handle grouped GA events from GroupedEventSelector
                        if ( isset( $module_config['selectedGAEvent'] ) && is_array( $module_config['selectedGAEvent'] ) ) {
                            $selected_event = $module_config['selectedGAEvent'];
                            $pys_data['gtm_event_action'] = sanitize_text_field( $selected_event['value'] );

                            // Save the group information
                            if ( isset( $selected_event['group'] ) ) {
                                $pys_data['gtm_event_action_group'] = sanitize_text_field( $selected_event['group'] );
                            }

                            // Sanitize all params and filter by selected event params
                            $sanitized_params = $this->sanitize_event_params( $module_config['params'] ?? array() );
                            foreach ($selected_event['params'] as $param) {
                                if ( isset( $sanitized_params[$param['name']] ) ) {
                                    $pys_data['gtm_params'][$param['name']] = $sanitized_params[$param['name']];
                                }
                            }
                        } else {
                            // Fallback for old format or direct eventAction
                            $pys_data['gtm_event_action'] = isset( $module_config['eventAction'] ) ?
                                sanitize_text_field( $module_config['eventAction'] ) : 'view_item';
                        }

                            // Handle custom event action (for CustomEvent type)
                            if ( $pys_data['gtm_event_action'] === 'CustomEvent' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['gtm_custom_event_action'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            $gtm_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['gtm_custom_params'] = ! empty( $module_config['paramsEnabled'] ) ? $gtm_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['gtm_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['gtm_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;

                        case 'tiktok':
                            $pys_data['tiktok_enabled'] = true;

                            // Handle pixel ID selection
                            if ( isset( $module_config['pixelId'] ) ) {
                                $pys_data['tiktok_pixel_id'] = sanitize_text_field( $module_config['pixelId'] );
                            } else {
                                $pys_data['tiktok_pixel_id'] = 'all';
                            }

                            $pys_data['tiktok_event_type'] = isset( $module_config['eventType'] ) ?
                                sanitize_text_field( $module_config['eventType'] ) : 'ViewContent';

                            // Handle custom event type (TikTok uses "CustomEvent" not "custom")
                            if ( $pys_data['tiktok_event_type'] === 'CustomEvent' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['tiktok_custom_event_type'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            // Handle parameters
                            $pys_data['tiktok_params_enabled'] = isset( $module_config['paramsEnabled'] ) ?
                                (bool) $module_config['paramsEnabled'] : false;

                            // Handle tiktok_params - structured params with value, selector, dynamic
                            if ( isset( $module_config['params'] ) && is_array( $module_config['params'] ) ) {
                                $pys_data['tiktok_params'] = $this->sanitize_event_params( $module_config['params'] );
                            }

                            $tiktok_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['tiktok_custom_params'] = $pys_data['tiktok_params_enabled'] ? $tiktok_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['tiktok_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['tiktok_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;

                        case 'bing':
                            $pys_data['bing_enabled'] = true;

                            // Handle pixel ID selection
                            if ( isset( $module_config['pixelId'] ) ) {
                                $pys_data['bing_pixel_id'] = sanitize_text_field( $module_config['pixelId'] );
                            } else {
                                $pys_data['bing_pixel_id'] = 'all';
                            }

                            $pys_data['bing_event_type'] = isset( $module_config['eventType'] ) ?
                                sanitize_text_field( $module_config['eventType'] ) : 'ViewContent';

                            // Handle custom event type
                            if ( $pys_data['bing_event_type'] === 'custom' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['bing_custom_event_type'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            // Handle parameters
                            $pys_data['bing_params_enabled'] = isset( $module_config['paramsEnabled'] ) ?
                                (bool) $module_config['paramsEnabled'] : false;

                            // Handle bing_params - structured params with value, selector, dynamic
                            if ( isset( $module_config['params'] ) && is_array( $module_config['params'] ) ) {
                                $pys_data['bing_params'] = $this->sanitize_event_params( $module_config['params'] );
                            }

                            $bing_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['bing_custom_params'] = $pys_data['bing_params_enabled'] ? $bing_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['bing_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['bing_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;

                        case 'pinterest':
                            // Pinterest is a paid addon and doesn't have pixel_id field in CustomEvent
                            // Only event type and parameters are supported
                            $pys_data['pinterest_enabled'] = true;

                            $pys_data['pinterest_event_type'] = isset( $module_config['eventType'] ) ?
                                sanitize_text_field( $module_config['eventType'] ) : 'ViewContent';

                            // Handle custom event type (Pinterest uses "custom" event type)
                            if ( $pys_data['pinterest_event_type'] === 'custom' && isset( $module_config['customEventType'] ) ) {
                                $pys_data['pinterest_custom_event_type'] = sanitize_text_field( $module_config['customEventType'] );
                            }

                            // Handle parameters
                            $pys_data['pinterest_params_enabled'] = isset( $module_config['paramsEnabled'] ) ?
                                (bool) $module_config['paramsEnabled'] : false;

                            // Handle pinterest_params - structured params with value, selector, dynamic
                            if ( isset( $module_config['params'] ) && is_array( $module_config['params'] ) ) {
                                $pys_data['pinterest_params'] = $this->sanitize_event_params( $module_config['params'] );
                            }

                            $pinterest_custom_params = $this->sanitize_custom_params( $module_config['customParams'] ?? array() );
                            $pys_data['pinterest_custom_params'] = $pys_data['pinterest_params_enabled'] ? $pinterest_custom_params : array();

                            // Handle WooCommerce tracking options
                            $pys_data['pinterest_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['pinterest_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;

                        case 'reddit':
                            $pys_data['reddit_enabled'] = true;

                            // Handle pixel ID selection
                            if ( isset( $module_config['pixelId'] ) ) {
                                $pys_data['reddit_pixel_id'] = sanitize_text_field( $module_config['pixelId'] );
                            } else {
                                $pys_data['reddit_pixel_id'] = 'all';
                            }

                            $pys_data['reddit_event_type'] = isset( $module_config['eventType'] ) ?
                                sanitize_text_field( $module_config['eventType'] ) : 'ViewContent';

                            // Handle custom event type for Reddit
                            if ( isset( $module_config['eventType'] ) && $module_config['eventType'] === 'Custom' ) {
                                $pys_data['reddit_custom_event_type'] = isset( $module_config['customEventType'] ) ?
                                    sanitize_text_field( $module_config['customEventType'] ) : '';
                            }

                            // Handle parameters
                            $pys_data['reddit_params_enabled'] = isset( $module_config['paramsEnabled'] ) ?
                                (bool) $module_config['paramsEnabled'] : false;

                            // Handle reddit_params - structured params with value, selector, dynamic
                            if ( isset( $module_config['params'] ) && is_array( $module_config['params'] ) ) {
                                $pys_data['reddit_params'] = $this->sanitize_event_params( $module_config['params'] );
                            }

                            // Handle WooCommerce tracking options
                            $pys_data['reddit_track_single_woo_data'] = isset( $module_config['trackSingleWooData'] ) ?
                                (bool) $module_config['trackSingleWooData'] : false;
                            $pys_data['reddit_track_cart_woo_data'] = isset( $module_config['trackCartWooData'] ) ?
                                (bool) $module_config['trackCartWooData'] : false;
                            break;
                    }
                }
            }
        }
        return $pys_data;
    }

    /**
     * Transform EST conditions data to PYS format expected by CustomEvent::update()
     *
     * Expected PYS format structure:
     * - Each condition must have 'condition_type' key
     * - For url_filters, url_parameters, landing_page, source: nested structure with condition_rule and condition_value
     * - For device: direct 'device' key
     * - For user_role: direct 'user_role' key (userRoles is always an array from component)
     *
     * @param array $est_conditions - EST conditions array
     * @return array - Transformed conditions array for PYS
     */
    private function transform_conditions_to_pys_format( $est_conditions ) {
        $pys_conditions = array();

        if ( !is_array( $est_conditions ) ) {
            return $pys_conditions;
        }
        
        foreach ( $est_conditions as $condition ) {
            if ( !isset( $condition['condition_type'] ) ) {
                continue; // Skip invalid conditions
            }

            $condition_type = sanitize_text_field( $condition['condition_type'] );
            $pys_condition = array(
                'condition_type' => $condition_type
            );

            // Transform based on condition type
            switch ( $condition_type ) {
                case 'url_filters':
                case 'url_parameters':
                case 'landing_page':
                case 'source':
                    // These types require nested structure with condition_rule and condition_value
                    if ( isset( $condition[$condition_type]['condition_rule'] ) && isset( $condition[$condition_type]['condition_value'] ) ) {
                        $pys_condition[$condition_type] = array(
                            'condition_rule' => sanitize_text_field( $condition[$condition_type]['condition_rule'] ),
                            'condition_value' => sanitize_text_field( $condition[$condition_type]['condition_value'] )
                        );
                    } else {
                        // Skip incomplete conditions
                        continue 2;
                    }
                    break;

                case 'device':
                    // Device condition uses direct 'device' key
                    if ( isset( $condition['device'] ) ) {
                        $pys_condition['device'] = sanitize_text_field( $condition['device'] );
                    } else {
                        // Skip incomplete conditions
                        continue 2;
                    }
                    break;

                case 'user_role':
                    // User role condition uses direct 'user_role' key
                    // userRoles is always an array from the component
                    if ( isset( $condition['user_role'] ) && is_array( $condition['user_role'] ) && !empty( $condition['user_role'] ) ) {
                        $pys_condition['user_role'] = array_map( 'sanitize_text_field', $condition['user_role'] );
                    } else {
                        // Skip incomplete conditions
                        continue 2;
                    }
                    break;

                default:
                    // Unknown condition type, skip
                    continue 2;
            }

            $pys_conditions[] = $pys_condition;
        }
        
        return $pys_conditions;
    }

    /**
     * Sanitize custom parameters list.
     *
     * @param array|null $params
     *
     * @return array
     */
    private function sanitize_custom_params( $params ) {
        if ( ! is_array( $params ) ) {
            return array();
        }

        $sanitized = array();

        foreach ( $params as $param ) {
            $name  = isset( $param['name'] ) ? sanitize_text_field( $param['name'] ) : '';
            $value = isset( $param['value'] ) ? sanitize_text_field( $param['value'] ) : '';

            if ( $name === '' && $value === '' ) {
                continue;
            }

            $sanitized_param = array(
                'name'  => $name,
                'value' => $value,
            );

            // Add selector if present
            if ( isset( $param['selector'] ) && ! empty( $param['selector'] ) ) {
                $sanitized_param['selector'] = sanitize_text_field( $param['selector'] );
            }

            // Add dynamic flag if present
            if ( isset( $param['dynamic'] ) ) {
                $sanitized_param['dynamic'] = (bool) $param['dynamic'];
            }

            // Add input_type if present
            if ( isset( $param['input_type'] ) && ! empty( $param['input_type'] ) ) {
                $sanitized_param['input_type'] = sanitize_text_field( $param['input_type'] );
            }

            $sanitized[] = $sanitized_param;
        }

        return $sanitized;
    }

    /**
     * Sanitize event parameters (universal function for all modules)
     * Handles both old format (string) and new format (object with value, selector, dynamic, and input_type)
     */
    private function sanitize_event_params( $params ) {
        $sanitized = array();
        foreach ( $params as $key => $param ) {
            // New format: param is an object with 'value', 'selector', 'dynamic', and 'input_type'
            if ( is_array( $param ) && isset( $param['value'] ) ) {
                $sanitized_param = array(
                    'value' => sanitize_text_field( $param['value'] )
                );

                // Add selector if present
                if ( isset( $param['selector'] ) && ! empty( $param['selector'] ) ) {
                    $sanitized_param['selector'] = sanitize_text_field( $param['selector'] );
                }

                // Add dynamic flag if present
                if ( isset( $param['dynamic'] ) ) {
                    $sanitized_param['dynamic'] = (bool) $param['dynamic'];
                }

                // Add input_type if present
                if ( isset( $param['input_type'] ) && ! empty( $param['input_type'] ) ) {
                    $sanitized_param['input_type'] = sanitize_text_field( $param['input_type'] );
                }

                $sanitized[$key] = $sanitized_param;
            }
            // Old format: param is a string
            else {
                $sanitized[$key] = sanitize_text_field( $param );
            }
        }

        return $sanitized;
    }

    /**
     * Test AJAX handler to verify AJAX is working
     */
    public function ajax_test_est_ajax() {
        PYS()->getLog()->debug('EST Test AJAX called');
        wp_send_json_success( array( 'message' => 'AJAX is working!' ) );
    }

}

// API initialization
EST_API::instance();
