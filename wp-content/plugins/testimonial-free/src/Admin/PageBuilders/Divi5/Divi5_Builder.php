<?php
/**
 * Divi 5 Builder Integration
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi5;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Cannot access directly.
}

/**
 * Divi 5 Builder class for initialization.
 *
 * @since 4.2.4
 */
class Divi5_Builder {

	/**
	 * Initialize the integration.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function init() {
		// Only load if Divi 5 is active.
		if ( ! self::is_divi_5_active() ) {
			return;
		}

		// Check if integration is enabled in dashboard settings.
		$options = get_option( 'sp_testimonial_pro_options', array() );
		$divi_enabled = true;

		// Check integrations settings (matches JavaScript structure).
		if ( isset( $options['integrations']['divi']['is_active'] ) ) {
			$divi_enabled = (bool) $options['integrations']['divi']['is_active'];
		}

		if ( ! $divi_enabled ) {
			return;
		}

		// Note: server/index.php is already loaded in Manager.php at file level
		// to ensure the dependency tree hook registers before Divi fires it.

		// Divi 4 compatibility surfaces only — the D5 module list icon ships in the
		// bundle. See register_module_icon().
		add_filter( 'et_builder_module_icons', array( __CLASS__, 'register_module_icon' ) );

		// Enqueue visual builder assets.
		add_action( 'divi_visual_builder_assets_before_enqueue_scripts', array( __CLASS__, 'enqueue_visual_builder_assets' ) );

		// Enqueue frontend assets (outside builder).
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
	}

	/**
	 * Check if Divi 5 is active.
	 *
	 * @since 4.2.4
	 *
	 * @return bool True if Divi 5.x is active.
	 */
	public static function is_divi_5_active() {
		// Check for Divi 5.x using the d5_enabled function.
		if ( function_exists( 'et_builder_d5_enabled' ) && et_builder_d5_enabled() ) {
			return true;
		}

		// Version constant check.
		if ( defined( 'ET_BUILDER_VERSION' ) ) {
			return version_compare( ET_BUILDER_VERSION, '5.0', '>=' );
		}

		return false;
	}

	/**
	 * Register the shared plugin icon on Divi's legacy icon array.
	 *
	 * **This does not paint the Divi 5 module list.** `et_builder_module_icons` is
	 * read by `ET_Builder_Element::get_module_icons()`, a Divi 4 API whose array is
	 * keyed by `ET_Builder_Element` subclass slugs. A module registered through
	 * `ModuleRegistration::register_module()` never creates one, and the D5 module
	 * library is a React store that never reads that array. The D5 icon instead
	 * travels `module.json`'s `moduleIcon` name → the `divi.iconLibrary.icon.map`
	 * JS registry, both shipped in the bundle (`app/divi-5/src/icons/`).
	 *
	 * Kept because Divi 4 rendering surfaces can still resolve the `d4Shortcode`
	 * slug on a Divi 5 site, and painting a slug that is never looked up costs
	 * nothing.
	 *
	 * @since 4.2.5
	 *
	 * @param array $icons Registered module icons.
	 * @return array
	 */
	public static function register_module_icon( $icons ) {
		return Builder_Icon::divi_module_icons(
			$icons,
			array( 'rtp/divi5-testimonial', 'et_pb_real_testimonial' )
		);
	}

	/**
	 * Enqueue visual builder assets.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function enqueue_visual_builder_assets() {
		if ( ! function_exists( 'et_core_is_fb_enabled' ) || ! et_core_is_fb_enabled() ) {
			return;
		}

		if ( ! function_exists( 'et_builder_d5_enabled' ) || ! et_builder_d5_enabled() ) {
			return;
		}

		$plugin_url = SP_RT_PLUGIN_URL;
		$version    = defined( 'SP_TFREE_VERSION' ) ? SP_TFREE_VERSION : '4.2.4';

		// Register the React module build.
		if ( class_exists( 'ET\Builder\VisualBuilder\Assets\PackageBuildManager' ) ) {
			\ET\Builder\VisualBuilder\Assets\PackageBuildManager::register_package_build(
				array(
					'name'    => 'rtp-divi5-testimonial-visual-builder',
					'version' => $version,
					'script'  => array(
						'src'                => $plugin_url . 'dist/rtp-divi5-testimonial/rtp-divi5-testimonial.js',
						'deps'               => array( 'react', 'jquery', 'divi-module-library', 'wp-hooks', 'divi-rest' ),
						'enqueue_app_window' => true,
					),
				)
			);
		}

		Builder_Assets::enqueue();

		// Re-initialise the block frontend after Divi 5 swaps module markup in.
		Builder_Assets::reinit_script(
			'rtp-divi5-testimonial-wrapper',
			array( 'divi:module:updated', 'divi:ajax:render:success', 'divi:builder:save' )
		);

		// Hook to add localized data after scripts are enqueued.
		add_action( 'divi_visual_builder_assets_after_enqueue_scripts', array( __CLASS__, 'localize_script_data' ), 999 );
	}

	/**
	 * Localize script data for visual builder.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function localize_script_data() {
		$data = array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'sp_real-divi5-nonce' ),
			'pluginUrl' => SP_RT_PLUGIN_URL,
		);

		printf(
			'<script type="text/javascript">window.rtpDivi5Data = %s;</script>',
			wp_json_encode( $data )
		);
	}

	/**
	 * Enqueue block assets on a published Divi 5 page.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function enqueue_frontend_assets() {
		// Only load if NOT in builder context (the builder path enqueues its own).
		if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
			return;
		}

		if ( ! self::is_divi_5_active() ) {
			return;
		}

		Builder_Assets::enqueue();
	}
}
