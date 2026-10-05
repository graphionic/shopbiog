<?php
/**
 * Bricks Builder - Real Testimonials Element
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Bricks;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_List;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Real Testimonials Bricks Builder Element.
 */
class RTP_Bricks_Testimonial_Element extends \Bricks\Element {

	/**
	 * Element properties.
	 *
	 * @var string
	 */
	public $category = 'general';

	/**
	 * Element name.
	 *
	 * @var string
	 */
	public $name = 'rtp-bricks-testimonial';

	/**
	 * Element icon.
	 *
	 * Bricks prints the icon as `<i class="…">`, so the shared plugin icon is
	 * painted on this class from Bricks/init.php.
	 *
	 * @var string
	 */
	public $icon = Builder_Icon::CSS_CLASS;

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	public $css_selector = '.rtp-bricks-testimonial-wrapper';

	/**
	 * Element tags for search.
	 *
	 * @var array
	 */
	public $tags = array( 'testimonial', 'review', 'slider', 'carousel' );

	/**
	 * Get element label.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Real Testimonials', 'testimonial-free' );
	}

	/**
	 * Enqueue block scripts for the Bricks builder editor.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Only load in Bricks builder editor.
		if ( ! $this->is_bricks_builder() ) {
			return;
		}

		Builder_Assets::enqueue();

		// Re-initialise the block frontend after Bricks re-renders the element.
		Builder_Assets::reinit_script(
			'rtp-bricks-testimonial-wrapper',
			array(
				'bricksElementsReady',
				'bricks/setup_frontend',
				'bricks/pages/render',
				'bricks/element/after_render',
				'bricksAjaxRender',
			)
		);
	}

	/**
	 * Enqueue block styles for the Bricks builder editor.
	 *
	 * Bricks calls this alongside enqueue_scripts(); the shared emitter covers both,
	 * so this only has to make sure styles land when Bricks calls styles first.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! $this->is_bricks_builder() ) {
			return;
		}

		Builder_Assets::enqueue();
	}

	/**
	 * Set controls for the element.
	 *
	 * @return void
	 */
	public function set_controls() {
		$this->controls['template_id'] = array(
			'type'        => 'select',
			'label'       => esc_html__( 'Saved Template', 'testimonial-free' ),
			'options'     => $this->get_templates_list(),
			'clearable'   => false,
			'default'     => '0',
			'pasteStyles' => true,
			'inline'      => true,
		);

		$this->controls['separator'] = array(
			'type' => 'separator',
		);

		$this->controls['help'] = array(
			'type'    => 'info',
			'content' => 'Please Select a Saved Template',
		);
	}

	/**
	 * Get saved templates list.
	 *
	 * @since 4.2.0
	 *
	 * @return array Templates list.
	 */
	private function get_templates_list() {
		return Template_List::options( '0' );
	}

	/**
	 * Check if currently rendering in Bricks Builder.
	 *
	 * @return bool
	 */
	private function is_bricks_builder() {
		// Check if Bricks builder is active via URL parameter or function.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
		if ( isset( $_GET['bricks'] ) ) {
			return true;
		}

		// Check if Bricks builder functions exist.
		if ( function_exists( 'bricks_is_builder_main' ) && bricks_is_builder_main() ) {
			return true;
		}

		if ( function_exists( 'bricks_is_builder_call' ) && bricks_is_builder_call() ) {
			return true;
		}

		return false;
	}

	/**
	 * Render the element output on the frontend.
	 *
	 * Called by Bricks Builder when rendering the element
	 * on the page — both in the editor and on the frontend.
	 *
	 * @return void
	 */
	public function render() {

		// Get the selected template ID from element settings.
		$template_id = isset( $this->settings['template_id'] ) ? (int) $this->settings['template_id'] : 0;

		// Show a placeholder if no template has been selected.
		if ( empty( $template_id ) ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">' . esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) . '</div>';
			return;
		}

		// Get template post.
		$template_post = get_post( $template_id );
		if ( ! $template_post || 'publish' !== $template_post->post_status ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">' . esc_html__( 'Saved template not found or not published.', 'testimonial-free' ) . '</div>';
			return;
		}

		$content = $template_post->post_content;
		if ( empty( $content ) ) {
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">' . esc_html__( 'Saved template content is empty.', 'testimonial-free' ) . '</div>';
			return;
		}

		// Print the template CSS next to the markup, on the frontend as well as in the editor.
		$output = Template_Css::get( $template_id );

		// Execute the testimonial shortcode.
		$shortcode_output = do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );

		// Output the wrapping element tag (Bricks handles root attributes).
		echo '<div ' . $this->render_attributes( '_root' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are properly escaped in render_attributes method.
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS output is already escaped.

		// Output the shortcode content.
		echo '<div class="rtp-bricks-testimonial-wrapper" data-builder-template-id="' . esc_attr( $template_id ) . '">';
		echo $shortcode_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output may contain HTML and is expected to be rendered as-is.
		echo '</div>';

		echo '</div>';
	}
}
