<?php
/**
 * Base Page Builder Trait
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Base trait for page builder integrations.
 *
 * Provides common functionality for rendering saved templates
 * across different page builders.
 *
 * @since 4.2.0
 */
trait Base_Page_Builder {

	/**
	 * Get saved templates list.
	 *
	 * @since 4.2.0
	 *
	 * @return array Template ID => Title pairs
	 */
	public function get_saved_templates_list() {
		return Template_List::options( '0' );
	}

	/**
	 * Render saved template content.
	 *
	 * @since 4.2.0
	 *
	 * @param int  $template_id Template post ID.
	 * @param bool $is_editor   Whether rendering in page builder editor.
	 * @return string Rendered content or error message.
	 */
	public function render_template( $template_id, $is_editor = false ) {
		if ( empty( $template_id ) || 0 === $template_id ) {
			return $this->error_message( esc_html__( 'Please select a saved template', 'testimonial-free' ) );
		}

		$template_post = get_post( $template_id );
		if ( ! $template_post || 'publish' !== $template_post->post_status ) {
			return $this->error_message( esc_html__( 'Template not found or not published', 'testimonial-free' ) );
		}

		// Print the template CSS next to the markup, on the frontend as well as in the editor.
		$css_output = Template_Css::get( $template_id );

		return $css_output . do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );
	}

	/**
	 * Print CSS of a saved template.
	 *
	 * Kept for builder modules that echo their output instead of returning it.
	 *
	 * @since 4.2.0
	 *
	 * @param int $template_id Template post ID.
	 * @return void
	 */
	protected function enqueue_editor_css( $template_id ) {
		echo Template_Css::get( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS markup is escaped in Template_Css.
	}

	/**
	 * Generate error message HTML.
	 *
	 * @since 4.2.0
	 *
	 * @param string $message Error message.
	 * @return string Error HTML.
	 */
	protected function error_message( $message ) {
		return sprintf(
			'<div style="text-align:center;padding:20px;border:2px dashed #ccc;color:#999;font-size:14px;">%s</div>',
			esc_html( $message )
		);
	}
}
