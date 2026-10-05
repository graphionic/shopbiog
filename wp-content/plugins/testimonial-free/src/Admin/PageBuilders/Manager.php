<?php
/**
 * Page Builder Integration Manager
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load Divi 5 server-side module on plugins_loaded with priority 10.
// This runs when Divi has loaded and defined ET_BUILDER_VERSION.
add_action( 'init', array( Manager::class, 'load_divi5_server_module' ), 10 );

// Load Divi 5 REST API independently on rest_api_init.
// This ensures REST endpoints are available even if server module fails to load.
add_action( 'rest_api_init', array( Manager::class, 'load_divi5_rest_api' ), 10 );

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi5\Divi5_Builder;

/**
 * Page builder integrations manager.
 *
 * Conditionally loads integrations based on:
 * 1. Whether the page builder is active
 * 2. User's integration settings
 *
 * @since 4.2.0
 */
class Manager {

	/**
	 * Get available integrations.
	 *
	 * @since 4.2.0
	 *
	 * @return array Integration key => class name.
	 */
	private static function get_integrations() {
		return array(
			'elementor' => Elementor\Elementor_Builder::class,
			'divi'      => Divi\Divi_Builder::class,
			'wpbakery'  => WPBakery\WPBakery_Builder::class,
		);
	}

	/**
	 * Check if a page builder is active.
	 *
	 * @since 4.2.0
	 *
	 * @param string $builder Builder key.
	 * @return bool
	 */
	private static function is_builder_active( $builder ) {
		switch ( $builder ) {
			case 'elementor':
				return defined( 'ELEMENTOR_VERSION' );
			case 'divi':
				return defined( 'ET_BUILDER_VERSION' );
			case 'bricks':
				return defined( 'BRICKS_VERSION' );
			case 'beaver':
				return class_exists( 'FLBuilder' );
			case 'wpbakery':
				return defined( 'WPB_VC_VERSION' );
			case 'oxygen':
				return defined( 'CT_VERSION' ) || class_exists( 'OxyEl' );
			default:
				return false;
		}
	}

	/**
	 * Check if an integration is enabled in settings.
	 *
	 * @since 4.2.0
	 *
	 * @param string $builder Builder key.
	 * @return bool
	 */
	private static function is_integration_enabled( $builder ) {
		$options = get_option( 'sp_testimonial_pro_options', array() );

		// Default to enabled if no setting exists.
		if ( empty( $options ) ) {
			return true;
		}

		// Check integrations settings (matches JavaScript structure).
		if ( isset( $options['integrations'][ $builder ]['is_active'] ) ) {
			return (bool) $options['integrations'][ $builder ]['is_active'];
		}

		// Default to enabled if integration not found in options.
		return true;
	}

	/**
	 * Initialize all integrations.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function init() {
		// Load Oxygen integration directly (uses its own init hook).
		self::load_oxygen_integration();

		// Load Beaver Builder integration directly.
		self::load_beaver_integration();

		// For Bricks, load on init hook with priority 10 (before init.php registers at priority 11).
		add_action( 'init', array( __CLASS__, 'load_bricks_integration' ), 10 );

		$integrations = self::get_integrations();

		foreach ( $integrations as $key => $class ) {
			// For Divi, delay check to init hook since Divi loads late.
			if ( 'divi' === $key ) {
				add_action( 'init', array( __CLASS__, 'check_and_load_divi' ), 20 );
				continue;
			}

			// Check if page builder is active.
			if ( ! self::is_builder_active( $key ) ) {
				continue;
			}

			// Check if integration is enabled in settings.
			if ( ! self::is_integration_enabled( $key ) ) {
				continue;
			}

			// Load the integration.
			if ( class_exists( $class ) ) {
				$class::init();
			}
		}
	}

	/**
	 * Load Oxygen integration.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	private static function load_oxygen_integration() {
		if ( ! class_exists( 'OxyEl' ) ) {
			return;
		}

		if ( ! self::is_integration_enabled( 'oxygen' ) ) {
			return;
		}

		require_once __DIR__ . '/Oxygen/init.php';
	}

	/**
	 * Load Beaver Builder integration.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	private static function load_beaver_integration() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		if ( ! self::is_integration_enabled( 'beaver' ) ) {
			return;
		}

		require_once __DIR__ . '/Beaver/Beaver_Builder.php';
	}

	/**
	 * Load Bricks Builder integration.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function load_bricks_integration() {
		// Load the init.php file (it handles its own BRICKS_VERSION and settings check).
		require_once __DIR__ . '/Bricks/init.php';
	}

	/**
	 * Check and load Divi integration on init hook.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function check_and_load_divi() {
		// First check for Divi 5.
		if ( self::is_divi_5_active() ) {
			$class = Divi5_Builder::class;

			// Check if integration is enabled in settings.
			if ( ! self::is_integration_enabled( 'divi' ) ) {
				return;
			}

			// Load Divi 5 integration.
			if ( class_exists( $class ) ) {
				$class::init();
			}
			return;
		}

		// Fall back to Divi 4.
		$class = Divi\Divi_Builder::class;

		// Check if integration is enabled in settings.
		if ( ! self::is_integration_enabled( 'divi' ) ) {
			return;
		}

		// Check if Divi is active on init hook.
		if ( ! defined( 'ET_BUILDER_VERSION' ) && ! class_exists( 'ET_Builder_Module' ) ) {
			return;
		}

		// Load the integration.
		if ( class_exists( $class ) ) {
			$class::init();
		}
	}

	/**
	 * Check if Divi 5 is active.
	 *
	 * @since 4.2.4
	 *
	 * @return bool True if Divi 5.x is active.
	 */
	private static function is_divi_5_active() {
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
	 * Load Divi 5 server-side module on plugins_loaded.
	 * This runs at priority 10 to ensure it loads BEFORE divi_module_library_modules_dependency_tree fires.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function load_divi5_server_module() {
		// Check if Divi theme or Divi Builder is active.
		// Use wp_get_theme() to check for Divi theme since ET_BUILDER_VERSION may not be defined yet.
		$divi_active = false;

		// Check if Divi theme is active.
		$theme = wp_get_theme();
		if ( 'Divi' === $theme->get( 'Name' ) || 'divi' === $theme->get( 'Template' ) ) {
			$divi_active = true;
		}

		// Also check for ET_BUILDER_VERSION constant (Divi Builder plugin).
		if ( defined( 'ET_BUILDER_VERSION' ) ) {
			$divi_active = true;
		}

		if ( ! $divi_active ) {
			return;
		}

		// Check if Divi 5.x.
		$is_divi_5 = false;
		if ( defined( 'ET_BUILDER_VERSION' ) ) {
			$is_divi_5 = version_compare( ET_BUILDER_VERSION, '5.0', '>=' );
		}

		// Also check the d5_enabled function if available.
		if ( ! $is_divi_5 && function_exists( 'et_builder_d5_enabled' ) ) {
			$is_divi_5 = et_builder_d5_enabled();
		}

		// Only proceed if we confirmed it's Divi 5.x.
		// Do NOT assume Divi 5 just because Divi theme is active (could be Divi 4).
		if ( ! $is_divi_5 ) {
			return;
		}

		// Check settings before loading.
		$options      = get_option( 'sp_testimonial_pro_options', array() );
		$divi_enabled = true;

		// Check integrations settings (matches JavaScript structure).
		if ( isset( $options['integrations']['divi']['is_active'] ) ) {
			$divi_enabled = (bool) $options['integrations']['divi']['is_active'];
		}

		if ( ! $divi_enabled ) {
			return;
		}

		// Load server-side module.
		require_once __DIR__ . '/Divi5/server/index.php';
	}

	/**
	 * Load Divi 5 REST API endpoints.
	 * Loads independently of server module to ensure endpoints are available.
	 *
	 * @since 4.2.4
	 *
	 * @return void
	 */
	public static function load_divi5_rest_api() {
		// Only load if Divi theme or builder is active.
		$theme       = wp_get_theme();
		$divi_active = 'Divi' === $theme->get( 'Name' ) || 'divi' === $theme->get( 'Template' );

		if ( ! $divi_active && ! defined( 'ET_BUILDER_VERSION' ) ) {
			return;
		}

		// Check if integration is enabled in settings.
		$options      = get_option( 'sp_testimonial_pro_options', array() );
		$divi_enabled = true;

		// Check integrations settings (matches JavaScript structure).
		if ( isset( $options['integrations']['divi']['is_active'] ) ) {
			$divi_enabled = (bool) $options['integrations']['divi']['is_active'];
		}

		if ( ! $divi_enabled ) {
			return;
		}

		// Load REST API endpoints file and register routes directly.
		// We can't rely on the hook inside the file since rest_api_init has already fired.
		require_once __DIR__ . '/Divi5/server/rest-api.php';

		// Call the registration function directly since we're already in rest_api_init context.
		if ( function_exists( 'ShapedPlugin\\TestimonialFree\\Admin\\PageBuilders\\Divi5\\register_rest_routes' ) ) {
			\ShapedPlugin\TestimonialFree\Admin\PageBuilders\Divi5\register_rest_routes();
		}
	}
}
