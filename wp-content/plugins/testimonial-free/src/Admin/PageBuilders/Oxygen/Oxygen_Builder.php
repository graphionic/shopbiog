<?php
/**
 * Oxygen Builder - Real Testimonials Element
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Oxygen;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_List;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Real Testimonials Oxygen Builder Element.
 */
class RTP_Oxygen_Testimonial_Element extends \OxyEl {

	/**
	 * Element name.
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Real Testimonials', 'testimonial-free' );
	}

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function slug() {
		return 'sp-real-testimonial';
	}

	/**
	 * Element icon.
	 *
	 * @return string
	 */
	public function icon() {
		return Builder_Icon::url();
	}

	/**
	 * Button priority.
	 *
	 * @return int
	 */
	public function button_priority() {
		return 9;
	}

	/**
	 * Element tag.
	 *
	 * @return string
	 */
	public function tag() {
		return 'div';
	}

	/**
	 * Enable full CSS.
	 *
	 * @return bool
	 */
	public function enableFullCSS() {
		return true;
	}

	/**
	 * After init hook.
	 *
	 * @return void
	 */
	public function afterInit() {
		// Enqueue scripts for page builder.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_page_builder_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_page_builder_scripts' ) );
	}

	/**
	 * Check if currently in Oxygen builder editor.
	 *
	 * @return bool
	 */
	private function is_oxygen_builder_editor() {
		// Check if Oxygen builder is active via function.
		if ( function_exists( 'ct_get_current_screen' ) && ct_get_current_screen() === 'oxy_settings_iframe' ) {
			return true;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			return true;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
		if ( isset( $_GET['action'] ) && false !== strpos( sanitize_key( wp_unslash( $_GET['action'] ) ), 'oxy_render_oxy' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Enqueue block assets for the Oxygen builder editor.
	 *
	 * @return void
	 */
	public function enqueue_page_builder_scripts() {
		// Only load in Oxygen builder editor.
		if ( ! $this->is_oxygen_builder_editor() ) {
			return;
		}

		Builder_Assets::enqueue();

		// Re-initialise the block frontend after Oxygen swaps element markup in.
		Builder_Assets::reinit_script(
			'rtp-oxygen-testimonial-wrapper',
			array( 'oxygen-ajax-element-loaded' )
		);
	}

	/**
	 * Render element output.
	 *
	 * @param array  $options   Element options.
	 * @param array  $defaults  Default values.
	 * @param string $content   Inner content.
	 * @return void
	 */
	public function render( $options, $defaults, $content ) {
		$template_id = isset( $options['template_id'] ) ? absint( $options['template_id'] ) : 0;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only builder-context detection, no state change.
		$is_ajax_render = isset( $_GET['action'] ) && false !== strpos( sanitize_key( wp_unslash( $_GET['action'] ) ), 'oxy_render_oxy' );

		if ( $template_id ) {
			// Get template post.
			$template_post = get_post( $template_id );
			if ( ! $template_post || 'publish' !== $template_post->post_status ) {
				echo '<div style="
					text-align: center;
					padding: 20px;
					border: 2px dashed #ccc;
					color: #999;
					font-size: 14px;
				">
					' . esc_html__( 'Template not found or not published.', 'testimonial-free' ) . '
				</div>';
				return;
			}

			$template_content = $template_post->post_content;
			if ( empty( $template_content ) ) {
				echo '<div style="
					text-align: center;
					padding: 20px;
					border: 2px dashed #ccc;
					color: #999;
					font-size: 14px;
				">
					' . esc_html__( 'Template content is empty.', 'testimonial-free' ) . '
				</div>';
				return;
			}

			// Print the template CSS next to the markup, on the frontend as well as in the editor.
			echo Template_Css::get( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS markup is escaped in Template_Css.

			// Always wrap with builder-specific class.
			echo '<div class="rtp-oxygen-testimonial-wrapper" data-builder-template-id="' . esc_attr( $template_id ) . '">';
			echo do_shortcode( '[sp_real_template id="' . $template_id . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';

		} elseif ( $is_ajax_render ) {
			// Show placeholder if no template selected.
			echo '<div style="
				text-align: center;
				padding: 20px;
				border: 2px dashed #ccc;
				color: #999;
				font-size: 14px;
			">
				' . esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) . '
			</div>';
		}
	}

	/**
	 * Element controls.
	 *
	 * @return void
	 */
	public function controls() {
		// Get template list.
		$template_list = $this->rtp_get_template_list();

		$this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => esc_html__( 'Saved Template', 'testimonial-free' ),
				'slug'    => 'template_id',
				'default' => 0,
			)
		)->setValue( $template_list )->rebuildElementOnChange();
	}

	/**
	 * Retrieve all published testimonial templates.
	 *
	 * @return array Template list.
	 */
	private function rtp_get_template_list() {
		return Template_List::options( 0 );
	}
}
