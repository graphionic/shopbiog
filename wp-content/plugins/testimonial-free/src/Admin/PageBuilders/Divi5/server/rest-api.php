<?php
/**
 * Divi 5 REST API Endpoint
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders/Divi5
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi5;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_List;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Cannot access directly.
}

/**
 * Register REST API endpoints for Divi 5 module.
 *
 * @since 4.2.4
 *
 * @return void
 */
function register_rest_routes() {
	// Get testimonial HTML endpoint.
	register_rest_route(
		'sp-rtp/divi5/v1',
		'/testimonial-html',
		array(
			array(
				'methods'             => 'GET',
				'callback'            => __NAMESPACE__ . '\\get_testimonial_html',
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'template_id' => array(
						'required'          => true,
						'sanitize_callback' => 'absint',
						'description'       => 'Testimonial template ID',
					),
				),
			),
		)
	);

	// Get saved templates list endpoint.
	register_rest_route(
		'sp-rtp/divi5/v1',
		'/saved-templates',
		array(
			array(
				'methods'             => 'GET',
				'callback'            => __NAMESPACE__ . '\\get_saved_templates',
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			),
		)
	);
}

/**
 * Get testimonial HTML for visual builder.
 *
 * @since 4.2.4
 *
 * @param \WP_REST_Request $request REST request object.
 * @return \WP_REST_Response REST response with HTML.
 */
function get_testimonial_html( $request ) {
	$template_id = $request->get_param( 'template_id' );

	// Validate template ID.
	if ( empty( $template_id ) || 0 === $template_id ) {
		return new \WP_REST_Response(
			array(
				'success' => false,
				'html'    => '<div style="padding:20px;text-align:center;color:#999;">Invalid template ID</div>',
			),
			400
		);
	}

	// Check if template exists and is published.
	$template_post = get_post( $template_id );
	if ( ! $template_post || 'publish' !== $template_post->post_status ) {
		return new \WP_REST_Response(
			array(
				'success' => false,
				'html'    => '<div style="padding:20px;text-align:center;color:#999;">Template not found or not published</div>',
			),
			404
		);
	}

	// Get dynamic CSS for this template (generated on the fly when the file is missing).
	$dynamic_css = Template_Css::get( $template_id );

	// Render the shortcode - this is server-side rendering.
	$testimonial_html = do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );

	// Prepend dynamic CSS to HTML.
	$testimonial_html = $dynamic_css . $testimonial_html;

	// Get font lists for Google Fonts.
	$css_info   = array();
	$font_lists = get_post_meta( $template_id, 'sp_real_dynamic_fonts', true );
	if ( ! empty( $font_lists ) && is_array( $font_lists ) ) {
		$font_lists        = array_unique( $font_lists );
		$css_info['fonts'] = array_values( $font_lists );
	}

	// Return HTML with CSS, and font info for React to render.
	return new \WP_REST_Response(
		array(
			'success'  => true,
			'html'     => $testimonial_html,
			'css_info' => $css_info,
		),
		200
	);
}

/**
 * Get saved testimonial templates list for settings dropdown.
 *
 * @since 4.2.4
 *
 * @param \WP_REST_Request $request REST request object.
 * @return \WP_REST_Response REST response with templates list.
 */
function get_saved_templates( $request ) {
	$result = array();

	// Divi 5's select control keys options by string.
	foreach ( Template_List::options( '0' ) as $id => $title ) {
		$result[ (string) $id ] = $title;
	}

	return new \WP_REST_Response( $result, 200 );
}
