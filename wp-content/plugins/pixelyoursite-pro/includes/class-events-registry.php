<?php

namespace PixelYourSite;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Unified Events Registry
 * 
 * Provides a centralized interface for managing events and their parameters
 * across all platforms (Facebook, TikTok, Pinterest, Google Analytics, Bing, etc.)
 * 
 * @since 1.0.0
 */
class PYS_Events_Registry {

    private static $_instance;
    
    /**
     * Registry of all events by platform
     *
     * Structure - Direct from CustomEvent definitions:
     * [
     *   'platform_slug' => [
     *     'EventName' => [
     *       ['type' => 'input', 'label' => 'field_name', 'name' => '...', 'input_type' => 'string', 'required' => false],
     *       ...
     *     ]
     *   ]
     * ]
     *
     * Note: We store the raw CustomEvent format and let the EST application parse it.
     * This eliminates the need for conversion and keeps a single source of truth.
     */
    private $events = array();

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        // Load event definitions from external files first
        $this->load_event_definitions();

        // Allow other plugins/modules to register their events
        do_action( 'pys_events_registry_init', $this );
    }

    /**
     * Load event definitions from external files
     * This ensures all definitions are loaded before use
     * Uses PYS_Event_Definitions class for centralized access
     */
    private function load_event_definitions() {
        // Load all platform events using the centralized Event Definitions class
        $this->events['facebook'] = PYS_Event_Definitions::get_facebook_events();
        $this->events['tiktok'] = PYS_Event_Definitions::get_tiktok_events();
        $this->events['reddit'] = PYS_Event_Definitions::get_reddit_events();
        $this->events['bing'] = PYS_Event_Definitions::get_bing_events();
        $this->events['pinterest'] = PYS_Event_Definitions::get_pinterest_events();
        $this->events['google_analytics'] = PYS_Event_Definitions::get_ga_events();
        $this->events['gtm'] = PYS_Event_Definitions::get_gtm_events();
    }

    /**
     * Get all supported platforms
     * 
     * @return array Array of platform slugs
     */
    public function get_supported_platforms() {
        return array_keys( $this->events );
    }

    /**
     * Get all events for a specific platform
     * 
     * @param string $platform Platform slug (facebook, tiktok, pinterest, etc.)
     * @return array|false Array of events or false if platform not found
     */
    public function get_events_for_platform( $platform ) {
        if ( !isset( $this->events[$platform] ) ) {
            return false;
        }
        
        return $this->events[$platform];
    }

    /**
     * Get fields for a specific event
     * 
     * @param string $platform Platform slug
     * @param string $event_slug Event slug
     * @return array|false Array of field names or false if not found
     */
    public function get_event_fields( $platform, $event_slug ) {
        $event_data = $this->get_event_data( $platform, $event_slug );
        
        if ( $event_data === false ) {
            return false;
        }
        
        return isset( $event_data['fields'] ) ? $event_data['fields'] : array();
    }

    /**
     * Get complete data for a specific event
     * 
     * @param string $platform Platform slug
     * @param string $event_slug Event slug
     * @return array|false Event data array or false if not found
     */
    public function get_event_data( $platform, $event_slug ) {
        if ( !isset( $this->events[$platform] ) || !isset( $this->events[$platform][$event_slug] ) ) {
            return false;
        }
        
        return $this->events[$platform][$event_slug];
    }

    /**
     * Get all events from all platforms
     * 
     * @return array Complete events registry
     */
    public function get_all_events() {
        return $this->events;
    }

    /**
     * Register a new event or update existing one
     * 
     * @param string $platform Platform slug
     * @param string $event_slug Event slug
     * @param array $event_data Event data (label, fields)
     * @return bool Success status
     */
    public function register_event( $platform, $event_slug, $event_data ) {
        if ( !isset( $this->events[$platform] ) ) {
            $this->events[$platform] = array();
        }
        
        $this->events[$platform][$event_slug] = $event_data;
        
        return true;
    }

    /**
     * Register multiple events for a platform
     *
     * @param string $platform Platform slug
     * @param array $events Array of events
     * @return bool Success status
     */
    public function register_platform_events( $platform, $events ) {
        $this->events[$platform] = $events;

        return true;
    }

    /**
     * Get events formatted for EST API
     * Returns events in the format expected by EST API endpoints
     *
     * @param string $platform Platform slug
     * @return array|false Formatted events array or false if platform not found
     */
    public function get_events_for_est_api( $platform ) {
        $events = $this->get_events_for_platform( $platform );

        if ( $events === false ) {
            return false;
        }

        // For Google Analytics, return grouped format
        if ( $platform === 'google_analytics' || $platform === 'gtm' ) {
            return $this->format_ga_events_for_est_api( $events );
        }

        // For other platforms, return flat array format
        $formatted = array();
        foreach ( $events as $event_slug => $event_data ) {
            $formatted[] = array(
                'value' => $event_slug,
                'label' => $event_slug,
                'params' => $event_data ?? array()
            );
        }

        return $formatted;
    }

    /**
     * Format Google Analytics events for EST API (grouped format)
     *
     * @param array $ga_events GA events from registry
     * @return array Formatted grouped events
     */
    private function format_ga_events_for_est_api( $ga_events ) {
        $grouped_events = array();

        foreach ( $ga_events as $group_name => $events ) {
            $group_events = array();

            foreach ( $events as $event_slug => $event_data ) {
                $group_events[] = array(
                    'value' => $event_slug,
                    'label' => $event_slug,
                    'params' => $event_data ?? array(),
                    'group' => $group_name
                );
            }

            $grouped_events[] = array(
                'group' => $group_name,
                'events' => $group_events
            );
        }

        return $grouped_events;
    }

    /**
     * Get event options for select dropdown (backward compatibility)
     * Used by renderFacebookEventTypeInput, renderPinterestEventTypeInput, etc.
     *
     * @param string $platform Platform slug
     * @return array Associative array of event_slug => label
     */
    public function get_event_options( $platform ) {
        $events = $this->get_events_for_platform( $platform );

        if ( $events === false ) {
            return array();
        }

        // For Google Analytics, flatten grouped structure
        if ( $platform === 'google_analytics' ) {
            $options = array();
            foreach ( $events as $group_name => $group_events ) {
                foreach ( $group_events as $event_slug => $event_data ) {
                    $options[$event_slug] = $event_data['label'];
                }
            }
            return $options;
        }

        // For other platforms, simple mapping
        $options = array();
        foreach ( $events as $event_slug => $event_data ) {
            $options[$event_slug] = $event_data['label'];
        }

        return $options;
    }

    /**
     * Get TikTok events in old format (for backward compatibility)
     * Returns events in the format expected by CustomEvent::$tikTokEvents
     *
     * @return array TikTok events in old format
     */
    public function get_tiktok_events_legacy_format() {
        $events = $this->get_events_for_platform( 'tiktok' );

        if ( $events === false ) {
            return array();
        }

        $legacy_format = array();

        foreach ( $events as $event_slug => $event_data ) {
            $fields = isset( $event_data['fields'] ) ? $event_data['fields'] : array();
            $field_inputs = array();

            // Convert field names to input format
            foreach ( $fields as $field ) {
                $field_inputs[] = array(
                    'type' => 'input',
                    'label' => $field,
                    'name' => 'pys[event][tiktok_params][' . $field . ']'
                );
            }

            $legacy_format[$event_slug] = $field_inputs;
        }

        return $legacy_format;
    }

    /**
     * Get Facebook events in old format (for backward compatibility)
     * Returns events in the format expected by CustomEvent::$facebookEvents
     *
     * @return array Facebook events in old format
     */
    public function get_facebook_events_legacy_format() {
        $events = $this->get_events_for_platform( 'facebook' );

        if ( $events === false ) {
            return array();
        }

        $legacy_format = array();

        foreach ( $events as $event_slug => $event_data ) {
            $fields = isset( $event_data['fields'] ) ? $event_data['fields'] : array();
            $field_inputs = array();

            // Convert field names to input format
            foreach ( $fields as $field ) {
                $field_inputs[] = array(
                    'type' => 'input',
                    'label' => $field,
                    'name' => 'pys[event][facebook_params][' . $field . ']'
                );
            }

            $legacy_format[$event_slug] = $field_inputs;
        }

        return $legacy_format;
    }
}

/**
 * Get Events Registry instance
 * 
 * @return PYS_Events_Registry
 */
function PYS_Events_Registry() {
    return PYS_Events_Registry::instance();
}

// Initialize the registry
PYS_Events_Registry();

