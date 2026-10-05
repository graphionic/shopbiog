<?php
/**
 * Divi Builder Integration
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Base_Page_Builder;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register the Real Testimonials module with Divi Builder.
 *
 * @since 4.2.0
 *
 * @return void
 */
function rtp_template_divi_modules() {

	// Prevent duplicate registration.
	static $registered = false;
	if ( $registered ) {
		return;
	}

	// Check if Divi Builder is active.
	if ( ! class_exists( 'ET_Builder_Module' ) ) {
		return;
	}

	// Check if module class already exists.
	if ( class_exists( 'ET_Builder_Module_Real_Testimonial', false ) ) {
		return;
	}

	/**
	 * Divi Module class
	 */
	class ET_Builder_Module_Real_Testimonial extends \ET_Builder_Module {

		private $helper;

		/**
		 * Module slug.
		 *
		 * @var string
		 */
		public $slug = 'et_pb_real_testimonial';

		/**
		 * Visual Builder support.
		 *
		 * @var string
		 */
		public $vb_support = 'partial';

		/**
		 * Module credits.
		 *
		 * @var array
		 */
		protected $module_credits = array(
			'module_uri' => 'https://shapedplugin.com',
			'author'     => 'ShapedPlugin',
		);

		/**
		 * Initialize the module.
		 *
		 * @since 4.2.0
		 *
		 * @return void
		 */
		public function init() {
			$this->name   = esc_html__( 'Real Testimonials Saved Template', 'testimonial-free' );
			$this->helper = new Divi_Helper();

			// Enqueue scripts for page builder.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_page_builder_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_page_builder_scripts' ) );
		}

		/**
		 * Get fields.
		 *
		 * @since 4.2.0
		 *
		 * @return array
		 */
		public function get_fields() {
			$template_list = $this->helper->get_saved_templates_list();

			return array(
				'template_id' => array(
					'label'           => esc_html__( 'Saved Template', 'testimonial-free' ),
					'type'            => 'select',
					'option_category' => 'basic_option',
					'options'         => $template_list,
					'default'         => '0',
					'description'     => esc_html__( 'Select a saved testimonial template', 'testimonial-free' ),
					'toggle_slug'     => 'main_content',
				),
			);
		}

		/**
		 * Render module.
		 *
		 * @since 4.2.0
		 *
		 * @param array  $attrs       Module attributes.
		 * @param string $content     Module content.
		 * @param string $render_slug Module render slug.
		 * @return string Rendered content.
		 */
		public function render( $attrs, $content = null, $render_slug = '' ) {
			// Check attrs first, fallback to props.
			$template_id = isset( $attrs['template_id'] ) ? $attrs['template_id'] : '0';
			if ( empty( $template_id ) && isset( $this->props['template_id'] ) ) {
				$template_id = $this->props['template_id'];
			}

			// Ensure template_id is a string.
			$template_id = (string) $template_id;

			// Show placeholder if no template selected.
			if ( '0' === $template_id || empty( $template_id ) ) {
				return $this->error_message( esc_html__( 'Please Select a Saved Template', 'testimonial-free' ) );
			}

			// Check if template exists.
			$template_post = get_post( $template_id );
			if ( ! $template_post || 'publish' !== $template_post->post_status ) {
				return $this->error_message( esc_html__( 'Template not found or not published', 'testimonial-free' ) );
			}

			$template_content = $template_post->post_content;
			if ( empty( $template_content ) ) {
				return $this->error_message( esc_html__( 'Template content is empty.', 'testimonial-free' ) );
			}

			// Print the template CSS next to the markup, on the frontend as well as in the editor.
			$css_output = Template_Css::get( $template_id );

			// Render the shortcode.
			$shortcode_output = do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );
			// Ensure shortcode output is a string.
			$shortcode_output = (string) $shortcode_output;

			// Wrap output with builder-specific class, include CSS at the top.
			return $css_output . '<div class="rtp-divi-testimonial-wrapper" data-builder-template-id="' . esc_attr( $template_id ) . '">' .
				$shortcode_output .
			'</div>';
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

		/**
		 * Enqueue page builder scripts.
		 *
		 * @since 4.2.0
		 *
		 * @return void
		 */
		public function enqueue_page_builder_scripts() {
			// Only load in Divi builder editor, not the regular frontend.
			if ( ! rtp_template_divi_is_builder_editor() ) {
				return;
			}

			rtp_template_divi_enqueue_builder_assets();

			// Re-initialise the block frontend after Divi swaps module markup in.
			Builder_Assets::reinit_script(
				'rtp-divi-testimonial-wrapper',
				array( 'divi:module:updated', 'divi:ajax:render:success', 'divi:builder:save' )
			);
		}
	}

	new ET_Builder_Module_Real_Testimonial();

	// Mark as registered to prevent duplicates.
	$registered = true;
}

/**
 * Check if the current request is for Divi Builder or Visual Builder.
 *
 * Detects Frontend Builder (et_fb), Backend Builder (et_bfb), and AJAX rendering modes.
 *
 * @since 4.2.0
 *
 * @return bool True if in Divi builder context, false otherwise.
 */
function rtp_template_divi_is_builder_editor() {
	// 1. Check Divi Frontend Builder (Visual Builder) via URL parameter.
	if ( isset( $_GET['et_fb'] ) ) {
		return true;
	}

	// 2. Check Divi Backend Builder (New Experience) via URL parameter.
	if ( isset( $_GET['et_bfb'] ) && is_admin() ) {
		return true;
	}

	// 3. Check Divi preview mode.
	if ( isset( $_GET['et_pb_preview'] ) ) {
		return true;
	}

	// 4. Check global variable set during Frontend Builder.
	if ( isset( $GLOBALS['et_fb'] ) && $GLOBALS['et_fb'] ) {
		return true;
	}

	// 5. Check Divi AJAX requests (for module rendering).
	if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 0 === strpos( sanitize_key( wp_unslash( $_REQUEST['action'] ) ), 'et_' ) ) {
		return true;
	}

	// 6. Check if Divi JSON request (used by Visual Builder for module rendering).
	if ( wp_is_json_request() && function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
		return true;
	}

	// 7. Check if builder is loaded (works for Backend Builder in admin context).
	if ( function_exists( 'et_builder_is_loaded' ) && et_builder_is_loaded() ) {
		// Additional check: only return true in admin context for Backend Builder reliability.
		if ( is_admin() ) {
			return true;
		}
		// For Frontend Builder, verify with et_core_is_fb_enabled().
		if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
			return true;
		}
	}

	// 8. Final fallback: check if Frontend Builder is enabled.
	if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
		return true;
	}

	return false;
}

/**
 * Register and enqueue block frontend assets needed by saved templates in Divi.
 *
 * Thin wrapper over the shared emitter so the handle/source map lives in one place.
 *
 * @since 4.2.0
 *
 * @return void
 */
function rtp_template_divi_enqueue_builder_assets() {
	Builder_Assets::enqueue();
}

/**
 * Helper class for Divi module.
 *
 * @since 4.2.0
 */
class Divi_Helper {
	use Base_Page_Builder;
}

/**
 * Divi Builder class for initialization.
 *
 * @since 4.2.0
 */
class Divi_Builder {

	/**
	 * Initialize the integration.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function init() {
		// Hook to et_builder_ready to register module.
		add_action( 'et_builder_ready', __NAMESPACE__ . '\rtp_template_divi_modules' );

		// Show the shared plugin icon on the module in the Add Module list.
		add_filter( 'et_builder_module_icons', array( __CLASS__, 'register_module_icon' ) );
	}

	/**
	 * Register the shared plugin icon for the module.
	 *
	 * @since 4.2.5
	 *
	 * @param array $icons Registered module icons.
	 * @return array
	 */
	public static function register_module_icon( $icons ) {
		return Builder_Icon::divi_module_icons( $icons, 'et_pb_real_testimonial' );
	}
}
