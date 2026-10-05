<?php
/**
 * Real Testimonials ManageAPI File.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/API
 */

namespace ShapedPlugin\TestimonialFree\Blocks\API;

use ShapedPlugin\TestimonialFree\Blocks\Includes\TestimonialQuery;
use ShapedPlugin\TestimonialFree\Blocks\Templates\CardTemplate;
use ShapedPlugin\TestimonialFree\Admin\Dashboard\DashboardHelper;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * ManageAPI Class.
 */
class ManageAPI {
	/**
	 * Single class instance.
	 *
	 * @var ManageAPI
	 */
	private static $instance;

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	private const REST_NAMESPACE = 'sp-rtp/v2';

	/**
	 * Get main Blocks Instance.
	 *
	 * Ensures only one instance exists in memory at any time.
	 * Prevents needing to define globals all over the place.
	 *
	 * @static
	 * @return ManageAPI The one true Blocks instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}


	/**
	 * Initialize actions and hooks.
	 *
	 * @return void
	 */
	private function init() {
		// Rest API Init.
		add_action( 'rest_api_init', array( $this, 'sp_real_rest_api_register' ) );
		add_action( 'wp_ajax_sp_real_query_testimonials', array( $this, 'sp_real_query_testimonials' ) );
		add_action( 'wp_ajax_nopriv_sp_real_query_testimonials', array( $this, 'sp_real_query_testimonials' ) );
		// Testimonial Submission Form handlers.
		add_action( 'wp_ajax_sp_real_tsf_submit', array( $this, 'sp_real_tsf_submit_ajax' ) );
		add_action( 'wp_ajax_nopriv_sp_real_tsf_submit', array( $this, 'sp_real_tsf_submit_ajax' ) );
		add_action( 'admin_post_sp_real_tsf_submit_normal', array( $this, 'sp_real_tsf_submit_normal' ) );
		add_action( 'admin_post_nopriv_sp_real_tsf_submit_normal', array( $this, 'sp_real_tsf_submit_normal' ) );
	}

	/**
	 * Method sp_real_rest_api_register
	 *
	 * @return void
	 */
	public function sp_real_rest_api_register() {
		register_rest_route(
			self::REST_NAMESPACE,
			'/icon-list',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'sp_real_icon_list' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
		register_rest_route(
			self::REST_NAMESPACE,
			'/theme-colors',
			array(
				'methods'             => array( 'GET', 'POST' ),
				'callback'            => array( $this, 'sp_real_color_settings' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
		register_rest_route(
			self::REST_NAMESPACE,
			'/testimonial',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_testimonial_api_data' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
	}

	/**
	 * Get icon list.
	 *
	 * @return array
	 */
	public function sp_real_icon_list() {
		$icon_list = require_once SP_TFREE_PATH . 'Blocks/assets/icons/icon-list.php';
		return rest_ensure_response( wp_json_encode( $icon_list ) );
	}

	/**
	 * Get testimonial data for blocks.
	 *
	 * Uses TestimonialQuery class for shared query logic.
	 *
	 * @param \WP_REST_Request $request REST API request.
	 * @return \WP_REST_Response Testimonial data.
	 */
	public function get_testimonial_api_data( $request ) {
		$args  = TestimonialQuery::build_args_from_request( $request->get_params() );
		$query = TestimonialQuery::query( $args );

		$data = array();
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post ) {
				$data[] = TestimonialQuery::get_testimonial_data( $post );
			}
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'data'    => $data,
				'total'   => count( $data ),
			)
		);
	}

	/**
	 * Get theme colors for color picker.
	 *
	 * @param \WP_REST_Request $request REST API request.
	 * @return \WP_REST_Response Theme and custom colors.
	 */
	public function sp_real_color_settings( $request ) {
		// Handle POST - save custom colors.
		if ( isset( $request['colorSettingsData'] ) ) {
			$color_data = json_decode( $request->get_param( 'colorSettingsData' ), true );
			if ( is_array( $color_data ) ) {
				DashboardHelper::update_plugin_settings( 'custom_colors', $color_data );
			}
		}

		// Get theme colors from global settings.
		$global_settings  = wp_get_global_settings();
		$theme_colors     = isset( $global_settings['color']['palette']['theme'] ) ? $global_settings['color']['palette']['theme'] : array();
		$custom_colors    = isset( $global_settings['color']['palette']['custom'] ) ? $global_settings['color']['palette']['custom'] : array();
		$theme_all_colors = array_merge( $theme_colors, $custom_colors );

		return rest_ensure_response(
			array(
				'success'       => true,
				'theme_colors'  => $theme_all_colors,
				'custom_colors' => DashboardHelper::get_plugin_settings( 'custom_colors', array() ),
			)
		);
	}

	/**
	 * Unified AJAX handler for filter/search/pagination child blocks.
	 *
	 * Accepted POST params (all sanitized):
	 *  - nonce            sp_real_block_nonce
	 *  - paged            int  current page (default 1)
	 *  - posts_per_page   int  items per page (default 9)
	 *  - orderby          str  WP_Query orderby (default date)
	 *  - order            str  ASC|DESC (default DESC)
	 *  - search           str  keyword search
	 *  - rating           int  1-5 rating filter (0 disables)
	 *  - block_attributes json card attributes for CardTemplate render
	 *
	 * Returns JSON: { html, total, pages }.
	 *
	 * @return void
	 */
	public function sp_real_query_testimonials() {
		// Nonce verification.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp_real_block_nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed.' ), 403 );
			return;
		}

		// Sanitize inputs.
		$current_page = isset( $_POST['current_page'] ) ? max( 1, intval( $_POST['current_page'] ) ) : 1;
		$per_page     = isset( $_POST['per_page'] ) ? min( 100, max( 1, intval( $_POST['per_page'] ) ) ) : 6;
		$search       = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$rating       = isset( $_POST['rating'] ) ? sanitize_text_field( wp_unslash( $_POST['rating'] ) ) : '';

		// Decode block attributes (for CardTemplate render).
		$unique_id        = isset( $_POST['unique_id'] ) ? sanitize_text_field( wp_unslash( $_POST['unique_id'] ) ) : '';
		$post_id          = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		$block_attributes = BlocksHelper::find_block_attributes_by_id( $post_id, $unique_id ) ?? array();

		// Seed from block's saved query (orderBy, order, excludesItems) so paginated
		// requests run against the same pool the initial server-side render produced.
		$args = TestimonialQuery::build_args_from_attributes( $block_attributes );

		// Pagination — initial server render serves `limit` items, AJAX continues from there.
		// Random order + non-zero saved offset both break paging consistency; clear them.
		if ( isset( $_POST['current_page'] ) ) {
			$is_first_page = $current_page <= 1;
			$initial_limit = intval( $block_attributes['limit'] ?? $per_page );
			$offset        = $is_first_page ? 0 : $initial_limit + ( ( $current_page - 2 ) * $per_page );
			// args.
			$args['per_page']      = $is_first_page ? $initial_limit : $per_page;
			$args['offset']        = $offset;
			$args['enable_random'] = false;
		}

		// Live overrides from request.
		if ( '' !== $search ) {
			$args['search'] = $search;
		}
		if ( '' !== $rating ) {
			$args['rating'] = $rating;
		}

		$query = TestimonialQuery::query( $args );

		// Render cards into a buffer.
		$html = '';
		if ( $query->have_posts() ) {
			ob_start();
			foreach ( $query->posts as $post ) {
				$testimonial_data = TestimonialQuery::get_testimonial_data( $post );
				new CardTemplate( $block_attributes, $testimonial_data );
			}
			$html = ob_get_clean();
			wp_reset_postdata();
		}

		wp_send_json_success(
			array(
				'html'  => $html,
				'total' => intval( $query->found_posts ),
				'pages' => intval( $query->max_num_pages ),
			)
		);
	}

	/**
	 * Check permission for REST API requests.
	 *
	 * @return bool
	 */
	public function check_permission() {
		return current_user_can( apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' ) );
	}

	/**
	 * AJAX endpoint for the Testimonial Submission Form.
	 *
	 * @return void
	 */
	public function sp_real_tsf_submit_ajax() {
		$nonce = isset( $_POST['sp_real_tsf_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sp_real_tsf_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp_real_tsf_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'testimonial-free' ) ), 403 );
		}

		$response = APIHelper::process_tsf_submission( $_POST );
		if ( ! empty( $response['success'] ) ) {
			wp_send_json_success( $response );
		}
		wp_send_json_error( $response, 400 );
	}

	/**
	 * Non-AJAX (admin-post) endpoint for the Testimonial Submission Form.
	 *
	 * @return void
	 */
	public function sp_real_tsf_submit_normal() {
		$referer = wp_get_referer() ? wp_get_referer() : home_url();
		$nonce   = isset( $_POST['sp_real_tsf_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sp_real_tsf_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp_real_tsf_nonce' ) ) {
			wp_safe_redirect( add_query_arg( 'sp_real_tsf_error', 1, $referer ) );
			exit;
		}

		$response = APIHelper::process_tsf_submission( $_POST );
		$key      = ! empty( $response['success'] ) ? 'sp_real_tsf_submitted' : 'sp_real_tsf_error';
		wp_safe_redirect( add_query_arg( $key, 1, $referer ) );
		exit;
	}
}
