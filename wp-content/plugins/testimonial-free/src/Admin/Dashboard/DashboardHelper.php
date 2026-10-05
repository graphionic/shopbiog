<?php
/**
 * The admin dashboard page for admin.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * DashboardHelper.
 */
class DashboardHelper {

	/**
	 * Sp_real_plugin_settings.
	 *
	 * @var string
	 */
	public static $sp_real_plugin_settings_key = 'sp_testimonial_pro_options';

	/**
	 * Rtp_admin_dashboard_nonce_key.
	 *
	 * @var string
	 */
	public static $sp_real_dashboard_nonce_key = 'sp_real_admin_dashboard_nonce';

	/**
	 * Method get_plugin_settings.
	 *
	 * @param string $field_key dashboard settings key name.
	 * @param string $field_default_value dashboard settings default value.
	 *
	 * @return mixed
	 */
	public static function get_plugin_settings( $field_key = '', $field_default_value = '' ) {
		$dashboard_settings = get_option( self::$sp_real_plugin_settings_key, array() );
		if ( ! empty( $field_key ) ) {
			$field_value = isset( $dashboard_settings[ $field_key ] ) ? $dashboard_settings[ $field_key ] : $field_default_value;
			return $field_value;
		}
		return $dashboard_settings;
	}

	/**
	 * Method update_plugin_settings.
	 *
	 * @param string $field_key dashboard settings key name.
	 * @param mixed  $field_value dashboard settings value.
	 *
	 * @return void
	 */
	public static function update_plugin_settings( $field_key, $field_value ) {
		$dashboard_settings               = self::get_plugin_settings();
		$dashboard_settings[ $field_key ] = $field_value;
		update_option( self::$sp_real_plugin_settings_key, $dashboard_settings );
	}

	/**
	 * Find blocks by name recursively in a parsed block structure.
	 *
	 * @param array $blocks The array of blocks to search.
	 * @param array $used_blocks The array to store the found block names.
	 * @return void
	 */
	public static function find_real_blocks_recursive( $blocks, &$used_blocks ) {
		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) && strpos( $block['blockName'], 'sp-testimonial-pro/' ) === 0 ) {
				$used_blocks[] = $block['blockName'];
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				self::find_real_blocks_recursive( $block['innerBlocks'], $used_blocks );
			}
		}
	}

	/**
	 * Shortcode list.
	 *
	 * @return array
	 */
	public static function sp_testimonial_pro_post_list() {
		$shortcodes = get_posts(
			array(
				'post_type'      => 'spt_testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => 999,
			)
		);

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}
		// shortcode list.
		$shortcode_list = array();
		foreach ( $shortcodes as $shortcode ) {
			$option           = array(
				'id'    => absint( $shortcode->ID ),
				'value' => absint( $shortcode->ID ),
				'label' => esc_html( $shortcode->post_title ),
			);
			$shortcode_list[] = $option;
		}
		return $shortcode_list;
	}

	/**
	 * Build an {id,value,label} option list for a post type.
	 *
	 * Used by the dashboard Tools page export to select specific Testimonial
	 * Views (spt_shortcodes) or Forms (spt_testimonial_form).
	 *
	 * @param string $post_type Post type to query.
	 * @return array
	 */
	public static function sp_real_post_options( $post_type ) {
		$posts = get_posts(
			array(
				'post_type'      => $post_type,
				'post_status'    => array( 'publish', 'pending', 'inherit' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$options = array();
		foreach ( $posts as $post ) {
			$options[] = array(
				'id'    => absint( $post->ID ),
				'value' => absint( $post->ID ),
				'label' => esc_html( $post->post_title ),
			);
		}
		return $options;
	}
	/**
	 * Set the consent notice start time if it does not already exist.
	 *
	 * Stores the current timestamp to track when the consent notice
	 * delay period begins.
	 *
	 * @return void
	 */
	public static function sp_real_maybe_set_notice_start_time() {
		if ( ! self::get_plugin_settings( 'rtp_consent_notice_start_time', null ) ) {
			self::update_plugin_settings( 'rtp_consent_notice_start_time', time() );
		}
	}

	/**
	 * Check whether the consent notice delay period has passed.
	 *
	 * Compares the stored notice start time with the current time
	 * to determine if the specified delay duration has elapsed.
	 *
	 * @param int $days Number of days to wait before showing the notice.
	 * @return bool True if the delay has passed, false otherwise.
	 */
	public static function sp_real_has_notice_delay_passed( $days = 7 ) {
		$start_time = self::get_plugin_settings( 'rtp_consent_notice_start_time' );

		if ( ! $start_time ) {
			return false;
		}

		return ( time() - $start_time ) >= ( DAY_IN_SECONDS * $days );
	}

	/**
	 * Collect anonymous data for tracking.
	 *
	 * @return array Data to be sent.
	 */
	public static function collect_anonymous_data() {
		// Prevent sending data from local/dev sites.
		$site_url = home_url();
		$host     = wp_parse_url( $site_url, PHP_URL_HOST );

		// Polyfill for str_ends_with (PHP 8.0+).
		$ends_with = function ( $haystack, $needle ) {
			if ( function_exists( 'str_ends_with' ) ) {
				return str_ends_with( $haystack, $needle );
			}
			return substr( $haystack, -strlen( $needle ) ) === $needle;
		};

		// Check for local sites.
		if ( 'localhost' === $host || '127.0.0.1' === $host || '::1' === $host || $ends_with( $host, '.local' ) || $ends_with( $host, '.test' ) || $ends_with( $host, '.dev' ) || 1 === preg_match( '/^192\.168\./', $host ) || 1 === preg_match( '/^10\./', $host ) || 1 === preg_match( '/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $host ) ) {
			return array(); // Stop here, do not collect data.
		}

		$theme = wp_get_theme();
		// PHP version.
		$php_version = phpversion();

		// Database version.
		$db_version = get_option( 'testimonial_pro_db_version', SP_TFREE_VERSION );

		$site_language = get_locale();

		// Active plugins list.
		$active_plugins = array();
		$plugins        = get_plugins();

		foreach ( (array) get_option( 'active_plugins', array() ) as $plugin_path ) {
			if ( isset( $plugins[ $plugin_path ] ) ) {
				$active_plugins[] = array(
					'name'    => $plugins[ $plugin_path ]['Name'],
					'version' => $plugins[ $plugin_path ]['Version'],
				);
			}
		}

		// Get used blocks.
		$used_blocks = array();
		global $wpdb;

		// Search in posts, pages, custom post types, templates, etc.
		$post_contents = $wpdb->get_col( $wpdb->prepare( "SELECT post_content FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_content LIKE %s", '%<!-- wp:sp-testimonial-pro/%' ) );
		foreach ( $post_contents as $post_content ) {
			if ( has_blocks( $post_content ) ) {
				$blocks = parse_blocks( $post_content );
				self::find_real_blocks_recursive( $blocks, $used_blocks );
			}
		}

		// Search in widgets.
		$widget_blocks = get_option( 'widget_block' );
		if ( ! empty( $widget_blocks ) && is_array( $widget_blocks ) ) {
			foreach ( $widget_blocks as $widget_block ) {
				if ( is_array( $widget_block ) && isset( $widget_block['content'] ) ) {
					if ( has_blocks( $widget_block['content'] ) ) {
						$blocks = parse_blocks( $widget_block['content'] );
						self::find_real_blocks_recursive( $blocks, $used_blocks );
					}
				}
			}
		}

		$used_blocks = array_values( array_unique( $used_blocks ) );

		// Collect data.
		return array(
			'user_email'     => get_option( 'admin_email' ),
			'site_url'       => get_option( 'siteurl' ),
			'site_language'  => $site_language,
			'theme_name'     => $theme->get( 'Name' ),
			'plugin_version' => SP_TFREE_VERSION,
			'wp_version'     => function_exists( 'wp_get_wp_version' ) ? wp_get_wp_version() : get_bloginfo( 'version' ),
			'php_version'    => $php_version,
			'db_version'     => $db_version,
			'active_plugins' => $active_plugins,
			'used_blocks'    => $used_blocks,
		);
	}

	/**
	 * Send collected data to remote server.
	 *
	 * @param array $data Data to send.
	 * @return bool|\WP_Error Response or error.
	 */
	public static function send_data_to_remote( $data ) {
		return wp_remote_post(
			'https://api.shapedplugin.com/wp-json/spda/v1/real-testimonials-free-collect',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'x-api-key'    => SP_TFREE_SDR_KEY,
					'User-Agent'   => 'rtp-sdr-collector/' . home_url(),
				),
				'body'    => wp_json_encode( $data ),
			)
		);
	}
}
