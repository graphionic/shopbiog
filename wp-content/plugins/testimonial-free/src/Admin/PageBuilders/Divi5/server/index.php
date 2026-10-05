<?php
/**
 * Divi 5 Module - Server Side Rendering
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders/Divi5
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi5;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Cannot access directly.
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;
use ET\Builder\Packages\Module\Module;
use ET\Builder\Packages\Module\Layout\Components\ModuleElements\ModuleElements;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;


/**
 * Real Testimonials Divi 5 Module - Server Side.
 *
 * @since 4.2.4
 */
class D5RealTestimonialProModule implements DependencyInterface {

	/**
	 * Module instance for singleton pattern.
	 *
	 * @var D5RealTestimonialProModule
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @since 4.2.4
	 *
	 * @return D5RealTestimonialProModule
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the module and registers render callback.
	 * Called by Divi 5's dependency tree system.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public function load() {
		$module_json_folder_path = SP_RT_PLUGIN_PATH . 'dist/rtp-divi5-testimonial/';

		// Fallback to the source tree when the build step has not run. `app/` is excluded
		// from the release zip by build-zip.js, so this path only exists in a dev tree.
		if ( ! file_exists( $module_json_folder_path . 'module.json' ) ) {
			$module_json_folder_path = SP_RT_PLUGIN_PATH . 'app/divi-5/src/';
		}

		// Register module immediately - don't hook into init.
		ModuleRegistration::register_module(
			$module_json_folder_path,
			array(
				'render_callback' => array( __CLASS__, 'render_callback' ),
			)
		);
	}

	/**
	 * Render callback for the module.
	 *
	 * @since 4.2.4
	 *
	 * @param array          $attrs    Module attributes.
	 * @param string         $content  Module content.
	 * @param \WP_Block      $block    Block object.
	 * @param ModuleElements $elements ModuleElements instance.
	 * @return string Rendered HTML.
	 */
	public static function render_callback( $attrs, $content, $block, $elements ) {
		// Extract template_id from nested attrs structure.
		$template_id = '0';
		if ( isset( $attrs['templateId']['innerContent']['desktop']['value'] ) ) {
			$template_id = $attrs['templateId']['innerContent']['desktop']['value'];
		}

		// Convert to integer for comparison.
		$template_id = absint( $template_id );

		if ( empty( $template_id ) || 0 === $template_id ) {
			$testimonial_html = self::error_message( esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) );
		} else {
			// Check if template exists and is published.
			$template_post = get_post( $template_id );
			if ( ! $template_post || 'publish' !== $template_post->post_status ) {
				$testimonial_html = self::error_message( esc_html__( 'Template not found or not published.', 'testimonial-free' ) );
			} elseif ( empty( $template_post->post_content ) ) {
				$testimonial_html = self::error_message( esc_html__( 'Template content is empty.', 'testimonial-free' ) );
			} else {
				// Render the shortcode.
				$testimonial_html = do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );
			}
		}

		// Print the template CSS next to the markup, on the frontend as well as in the editor.
		if ( $template_id > 0 ) {
			$testimonial_html = Template_Css::get( $template_id ) . $testimonial_html;
		}

		// Use Module::render() for proper Divi 5 rendering in both frontend and visual builder.
		return Module::render(
			array(
				// Frontend only params.
				'orderIndex'         => $block->parsed_block['orderIndex'] ?? 0,
				'storeInstance'      => $block->parsed_block['storeInstance'] ?? '',

				// Visual builder params.
				'attrs'              => $attrs,
				'elements'           => $elements,
				'id'                 => $block->parsed_block['id'] ?? '',
				'moduleClassName'    => 'rtp_divi5_testimonial',
				'name'               => $block->block_type->name ?? 'rtp/divi5-testimonial',
				'classnamesFunction' => array( __CLASS__, 'module_classnames' ),
				'moduleCategory'     => $block->block_type->category ?? 'module',
				'children'           => self::render_module_inner( $testimonial_html, $template_id ),
			)
		);
	}

	/**
	 * Render module inner content.
	 *
	 * @since 4.2.4
	 *
	 * @param string $testimonial_html Rendered testimonial HTML.
	 * @param int    $template_id       Template ID.
	 * @return string Module inner HTML.
	 */
	private static function render_module_inner( $testimonial_html, $template_id ) {
		// Module style components (decorations).
		$module_elements = ''; // Will be populated by Module::render() automatically.

		// Module inner container.
		$module_inner = sprintf(
			'<div class="et_pb_module_inner rtp-divi5-testimonial-wrapper" data-builder-template-id="%s">%s</div>',
			esc_attr( $template_id ),
			$testimonial_html
		);

		return $module_elements . $module_inner;
	}

	/**
	 * Module classnames callback.
	 *
	 * @since 4.2.4
	 *
	 * @param array $args Callback arguments.
	 * @return void
	 */
	public static function module_classnames( $args ) {
		$classnames_instance = $args['classnamesInstance'] ?? null;
		$attrs               = $args['attrs'] ?? array();

		if ( $classnames_instance && method_exists( $classnames_instance, 'add' ) ) {
			// Add element classnames.
			$classnames_instance->add( 'rtp_divi5_testimonial' );
		}
	}

	/**
	 * Check if the current request is for Divi 5 Builder.
	 *
	 * @since 4.2.4
	 *
	 * @return bool True if in Divi 5 builder context.
	 */
	protected static function is_divi5_builder_editor() {
		// Check Divi Frontend Builder.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce not required for builder context check.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- isset() check only, value not used.
		if ( isset( $_GET['et_fb'] ) ) {
			return true;
		}

		// Check if Divi is enabled.
		if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
			return true;
		}

		// Check Divi AJAX requests.
		if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 0 === strpos( sanitize_key( wp_unslash( $_REQUEST['action'] ) ), 'et_' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Generate error message HTML.
	 *
	 * @since 4.2.4
	 *
	 * @param string $message Error message.
	 * @return string Error HTML.
	 */
	protected static function error_message( $message ) {
		return sprintf(
			'<div style="text-align:center;padding:20px;border:2px dashed #ccc;color:#999;font-size:14px;">%s</div>',
			esc_html( $message )
		);
	}
}

D5RealTestimonialProModule::get_instance()->load();
