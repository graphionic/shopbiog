<?php
/**
 * API management class for admin dashboard.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\Dashboard;

use ShapedPlugin\TestimonialFree\Admin\Dashboard\DashboardHelper;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ManageAPI Class.
 *
 * Handles all REST API and AJAX endpoints for the dashboard.
 */
class ManageAPI {

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	private const REST_NAMESPACE = 'sp-rtp/v2';

	/**
	 * Constructor.
	 */
	public function __construct() {
		// REST API endpoints.
		add_action( 'rest_api_init', array( $this, 'sp_real_dashboard_register_rest_routes' ) );
		// AJAX handlers (backward compatibility).
		add_action( 'wp_ajax_sp_real_get_user_consent', array( $this, 'sp_real_get_user_consent' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function sp_real_dashboard_register_rest_routes() {
		// Get/update dashboard settings.
		register_rest_route(
			self::REST_NAMESPACE,
			'/settings',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'sp_real_get_plugin_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'sp_real_update_plugin_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		// Get changelog.
		register_rest_route(
			self::REST_NAMESPACE,
			'/changelog',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'sp_real_get_changelog' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		// User consent.
		register_rest_route(
			self::REST_NAMESPACE,
			'/consent',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_handle_consent' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
		// Saved templates.
		register_rest_route(
			self::REST_NAMESPACE,
			'/saved-templates',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_saved_templates' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		// Export post options — Testimonial Views / Forms for the Tools page.
		register_rest_route(
			self::REST_NAMESPACE,
			'/export-posts',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'sp_real_get_export_posts' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'args'                => array(
					'type' => array(
						'required' => true,
						'type'     => 'string',
						'enum'     => array( 'spt_shortcodes', 'spt_testimonial_form' ),
					),
				),
			)
		);
	}

	/**
	 * REST API: Get {id,value,label} options for a Tools-export post type.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function sp_real_get_export_posts( $request ) {
		$type    = $request->get_param( 'type' );
		$allowed = array( 'spt_shortcodes', 'spt_testimonial_form' );
		if ( ! in_array( $type, $allowed, true ) ) {
			return new \WP_REST_Response( array(), 200 );
		}
		return new \WP_REST_Response( DashboardHelper::sp_real_post_options( $type ), 200 );
	}

	/**
	 * REST API: Get dashboard settings.
	 *
	 * @return \WP_REST_Response
	 */
	public function sp_real_get_plugin_settings() {
		return new \WP_REST_Response( $this->sp_real_api_response_data(), 200 );
	}

	/**
	 * REST API: Update dashboard settings.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function sp_real_update_plugin_settings( $request ) {
		$query_data   = $request->get_json_params();
		$new_settings = $query_data['settings'] ?? array();

		// Validate input is array.
		if ( ! is_array( $new_settings ) ) {
			return new \WP_Error(
				'invalid_data',
				__( 'Invalid settings data.', 'testimonial-free' ),
				array( 'status' => 400 )
			);
		}

		if ( ! empty( $new_settings ) ) {
			update_option( DashboardHelper::$sp_real_plugin_settings_key, $new_settings );
		}

		return new \WP_REST_Response( $this->sp_real_api_response_data(), 200 );
	}

	/**
	 * Method sp_real_api_response_data.
	 *
	 * @return array
	 */
	public function sp_real_api_response_data() {
		$current_user = wp_get_current_user();

		return array(
			'dashboardInfo' => array(
				'pluginUrl'     => SP_RT_PLUGIN_URL,
				'pluginVersion' => SP_TFREE_VERSION,
				'homeUrl'       => home_url( '/' ),
				'userName'      => $current_user->display_name ?? '',
				'adminUrl'      => admin_url(),
				'allBlockList'  => array(
					'main'      => BlocksHelper::sp_real_get_all_block_list( 'main' ),
					'parent'    => BlocksHelper::sp_real_get_all_block_list( 'parent' ),
					'child'     => BlocksHelper::sp_real_get_all_block_list( 'child' ),
					'proBlocks' => BlocksHelper::sp_real_get_all_block_list( 'pro_blocks' ),
				),
			),
			'settings'      => DashboardHelper::get_plugin_settings(),
			'rtpShortcodes' => DashboardHelper::sp_testimonial_pro_post_list(),
		);
	}

	/**
	 * REST API: Get changelog.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function sp_real_get_changelog( $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$changelog = get_transient( 'sp_rtp_changelogs' );

		if ( empty( $changelog ) ) {
			$changelog = $this->sp_real_fetch_changelog_from_api();
		}

		return new \WP_REST_Response(
			array(
				'changelog' => $changelog,
			),
			200
		);
	}

	/**
	 * REST API: Handle user consent.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function rest_handle_consent( $request ) {
		$query_data = $request->get_json_params();

		// user consent.
		$allow_anonymous_data = false;
		$user_consent         = isset( $query_data['shareData'] ) ? rest_sanitize_boolean( $query_data['shareData'] ) : false;
		if ( $user_consent ) {
			$allow_anonymous_data = true;
			$this->sp_real_send_data_to_rtp_sdr();
		}

		// update option data.
		DashboardHelper::update_plugin_settings( 'rtp_visited_setup_wizard', true );
		DashboardHelper::update_plugin_settings( 'rtp_allow_anonymous_data', $allow_anonymous_data );

		return new \WP_REST_Response(
			array(
				'message' => 'Successfully done',
			),
			200
		);
	}

	/**
	 * REST API: Get saved templates.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_saved_templates( $request ) {
		$search   = sanitize_text_field( $request->get_param( 'search' ) ?? '' );
		$page     = max( 1, absint( $request->get_param( 'page' ) ?? 1 ) );
		$per_page = max( 1, min( 100, absint( $request->get_param( 'per_page' ) ?? 10 ) ) );

		$args = array(
			'post_type'      => array( 'sp_real_template', 'spt_shortcodes' ),
			'post_status'    => 'any',
			'posts_per_page' => $per_page,
			'offset'         => ( $page - 1 ) * $per_page,
			's'              => $search,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$query = new \WP_Query( $args );

		$templates = array();
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post ) {
				$templates[] = array(
					'id'          => $post->ID,
					'title'       => array(
						'rendered' => get_the_title( $post->ID ),
						'raw'      => $post->post_title,
					),
					'status'      => $post->post_status,
					'modified'    => $post->post_modified,
					'post_type'   => $post->post_type,
					'editor_type' => ( 'sp_real_template' === $post->post_type ) ? 'block' : 'classic',
				);
			}
		}

		// Global count of classic (legacy shortcode) posts — drives the editor-type column toggle.
		$classic_query = new \WP_Query(
			array(
				'post_type'      => 'spt_testimonial',
				'post_status'    => 'any',
				's'              => $search,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => false,
			)
		);

		return new \WP_REST_Response(
			array(
				'templates'     => $templates,
				'total_items'   => (int) $query->found_posts,
				'classic_count' => (int) $classic_query->found_posts,
			),
			200
		);
	}

	/**
	 * Fetches changelog from the remote API.
	 * Stores the changelog in a transient for caching (1 day).
	 *
	 * @return string The changelog content or empty string on failure.
	 */
	protected function sp_real_fetch_changelog_from_api() {
		$api_url  = 'https://api.wordpress.org/plugins/info/1.0/testimonial-free.json';
		$response = wp_safe_remote_get( esc_url_raw( $api_url ), array( 'timeout' => 15 ) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return '';
		}

		$api_data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $api_data['sections']['changelog'] ) ) {
			return '';
		}

		$changelog = wp_kses_post( $api_data['sections']['changelog'] );

		set_transient( 'sp_rtp_changelogs', $changelog, DAY_IN_SECONDS );

		return $changelog;
	}

	/**
	 * Handle AJAX request to get and save user consent.
	 *
	 * This function processes user consent preferences for anonymous data sharing.
	 * It requires user authentication and nonce verification for security.
	 * Updates options for setup wizard visit status, anonymous data sharing preference,
	 * and website type. If consent is granted, it sends collected site data to the
	 * Real Testimonials SDR service.
	 *
	 * @return void Sends JSON response with success or error message.
	 */
	public function sp_real_get_user_consent() {
		// Check user capability.
		if ( ! $this->check_permission() ) {
			wp_send_json_error( __( 'Unauthorized access.', 'testimonial-free' ), 403 );
		}
		// Verify nonce.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, DashboardHelper::$sp_real_dashboard_nonce_key ) ) {
			wp_send_json_error( __( 'Invalid nonce.', 'testimonial-free' ) );
		}

		// query data - sanitized when individual keys are accessed below.
		$query_data = isset( $_POST['queryData'] ) ? wp_unslash( $_POST['queryData'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Nonce verified, sanitized after JSON decode.
		$query_data = json_decode( $query_data, true );
		$query_data = is_array( $query_data ) ? $query_data : array();
		// user consent.
		$allow_anonymous_data = false;
		$user_consent         = isset( $query_data['shareData'] ) ? rest_sanitize_boolean( $query_data['shareData'] ) : false;
		if ( $user_consent ) {
			$allow_anonymous_data = true;
			$this->sp_real_send_data_to_rtp_sdr();
		}
		// update option data.
		DashboardHelper::update_plugin_settings( 'rtp_visited_setup_wizard', true );
		DashboardHelper::update_plugin_settings( 'rtp_allow_anonymous_data', $allow_anonymous_data );
		// send response.
		wp_send_json_success(
			array(
				'message' => 'Successfully done',
			)
		);
	}

	/**
	 * Collects and sends anonymous data to remote server.
	 * Uses DashboardHelper methods for data collection and sending.
	 */
	protected function sp_real_send_data_to_rtp_sdr() {
		$data = DashboardHelper::collect_anonymous_data();
		if ( empty( $data ) ) {
			return; // Local site or no data to send.
		}
		DashboardHelper::send_data_to_remote( $data );
	}

	/**
	 * Check permission for REST API requests.
	 *
	 * @return bool
	 */
	public function check_permission() {
		return current_user_can( apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' ) );
	}
}
