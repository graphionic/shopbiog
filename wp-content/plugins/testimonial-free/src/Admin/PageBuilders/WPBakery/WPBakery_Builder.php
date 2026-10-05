<?php
/**
 * WPBakery Integration
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\WPBakery;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_List;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Real Testimonials element with WPBakery.
 *
 * @since 4.2.0
 * @return void
 */
function rtp_wpbakery_register_element() {

	// Check if WPBakery is active.
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		return;
	}

	vc_map(
		array(
			// ── Basic Info ────────────────────────────────────────────────
			'name'        => esc_html__( 'Real Testimonials', 'testimonial-free' ),
			'base'        => 'vc_real_testimonial',
			'description' => esc_html__( 'Display testimonial from saved template', 'testimonial-free' ),
			'category'    => esc_html__( 'Real Testimonials', 'testimonial-free' ),
			'icon'        => Builder_Icon::CSS_CLASS,

			// ── Fields (Params) ───────────────────────────────────────────
			'params'      => array(

				// Select field to choose a template.
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Saved Template', 'testimonial-free' ),
					'param_name'  => 'template_id',
					'value'       => rtp_wpbakery_get_template_list(),
					'std'         => '0',
					'description' => esc_html__( 'Select a saved template to display', 'testimonial-free' ),
					'save_always' => true,
				),

			),
		)
	);
}

// Register the element after WPBakery has fully loaded.
add_action( 'vc_before_init', __NAMESPACE__ . '\rtp_wpbakery_register_element' );

/**
 * Enqueue block assets for the WPBakery frontend editor.
 *
 * Gated on rtp_wpbakery_is_builder_editor(): this used to run on every frontend page
 * of the site, loading the classic scripts and a large inline script everywhere.
 *
 * @since 4.2.0
 * @return void
 */
function rtp_wpbakery_enqueue_scripts() {
	if ( ! rtp_wpbakery_is_builder_editor() ) {
		return;
	}

	Builder_Assets::enqueue();

	// Re-initialise the block frontend after WPBakery re-renders the element.
	Builder_Assets::reinit_script(
		'rtp-wpbakery-testimonial-wrapper',
		array( 'vc_js_reload', 'vc_frontend_default_editor_loaded', 'vc_frontend_render' )
	);
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\rtp_wpbakery_enqueue_scripts' );

/**
 * Register block assets and the element icon for the WPBakery backend editor.
 *
 * `enqueue_block_assets` never fires here (wp_common_block_scripts_and_styles() bails in
 * admin), so the block handles have to be registered by hand.
 *
 * @since 4.2.0
 * @return void
 */
function rtp_wpbakery_admin_enqueue_styles() {
	// Check if WPBakery is active.
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base ) {
		return;
	}

	Builder_Assets::enqueue();
	rtp_wpbakery_enqueue_element_icon();
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\rtp_wpbakery_admin_enqueue_styles' );

/**
 * Paint the shared plugin icon on the element in the WPBakery element list.
 *
 * WPBakery only accepts a class name for `icon`, and it already sizes the
 * `.vc_element-icon` box itself, so no sizing box is emitted.
 *
 * @since 4.2.0
 * @return void
 */
function rtp_wpbakery_enqueue_element_icon() {
	// Compound selector so the rule outranks WPBakery's own `.vc_element-icon` background.
	$selector = sprintf( '.vc_element-icon.%1$s,.%1$s', Builder_Icon::CSS_CLASS );

	Builder_Icon::enqueue_css( 'rtp-wpbakery-element-icon', $selector, false );
}

// The frontend editor builds its element list outside admin_enqueue_scripts.
add_action( 'vc_frontend_editor_enqueue_js_css', __NAMESPACE__ . '\rtp_wpbakery_enqueue_element_icon' );

/**
 * Retrieve all published testimonial templates for the dropdown.
 *
 * WPBakery dropdown options format: array( 'Label' => 'value' ).
 *
 * @since 4.2.0
 * @return array Template list.
 */
function rtp_wpbakery_get_template_list() {
	return Template_List::flipped_options( 'none' );
}

/**
 * Shortcode handler for [vc_real_testimonial].
 *
 * This function is called when WPBakery renders the element
 * on the frontend. It executes the testimonial shortcode
 * with the selected template ID.
 *
 * @since 4.2.0
 * @param array  $atts    Shortcode attributes.
 * @param string $content Inner content (not used).
 * @return string HTML output.
 */
function rtp_wpbakery_render_element( $atts, $content = null ) {

	// Extract and sanitize shortcode attributes.
	$atts = shortcode_atts(
		array(
			'template_id' => '',
		),
		$atts,
		'vc_real_testimonial'
	);

	$template_id = (int) $atts['template_id'];

	// Show a placeholder if no template has been selected.
	if ( empty( $template_id ) ) {
		return '<div style="
			text-align: center;
			padding: 20px;
			border: 2px dashed #ccc;
			color: #999;
			font-size: 14px;
		">
			' . esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) . '
		</div>';
	}

	// Check if template exists and is published.
	$template_post = get_post( $template_id );
	if ( ! $template_post || 'publish' !== $template_post->post_status ) {
		return '<div style="
			text-align: center;
			padding: 20px;
			border: 2px dashed #ccc;
			color: #999;
			font-size: 14px;
		">
			' . esc_html__( 'Template not found or not published', 'testimonial-free' ) . '
		</div>';
	}

	// Check if template content is empty.
	if ( empty( $template_post->post_content ) ) {
		return '<div style="
			text-align: center;
			padding: 20px;
			border: 2px dashed #ccc;
			color: #999;
			font-size: 14px;
		">
			' . esc_html__( 'Template content is empty', 'testimonial-free' ) . '
		</div>';
	}

	// Print the template CSS next to the markup, on the frontend as well as in the editor.
	$css_output = Template_Css::get( $template_id );

	// Execute the shortcode and return the rendered output.
	$output = do_shortcode( '[sp_real_template id="' . $template_id . '"]' );

	return $css_output . '<div class="rtp-wpbakery-testimonial-wrapper" data-builder-template-id="' . esc_attr( $template_id ) . '">' . $output . '</div>';
}

// Register the shortcode handler for the WPBakery element.
add_shortcode( 'vc_real_testimonial', __NAMESPACE__ . '\rtp_wpbakery_render_element' );

/**
 * Check if the current request is for WPBakery Builder editor.
 *
 * @since 4.2.0
 * @return bool True if in WPBakery builder context.
 */
function rtp_wpbakery_is_builder_editor() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
	if ( isset( $_GET['vc_editable'] ) ) {
		return true;
	}

	// Check WPBakery via function.
	if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
		return true;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
	if ( isset( $_GET['vc_preview'] ) ) {
		return true;
	}

	return false;
}

