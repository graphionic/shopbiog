<?php
/**
 * The plugin Gutenberg block Initializer.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Blocks;

use ShapedPlugin\TestimonialFree\Blocks\API\ManageAPI;
use ShapedPlugin\TestimonialFree\Blocks\Styles\DynamicStyle;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;
use ShapedPlugin\TestimonialFree\Blocks\Includes\ReadyPatterns;
use ShapedPlugin\TestimonialFree\Blocks\BlockTypes\ShortcodeBlock;
use ShapedPlugin\TestimonialFree\Blocks\BlockTypes\ShortcodeFormBlock;
use ShapedPlugin\TestimonialFree\Admin\Dashboard\DashboardHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blocks Init Class
 *
 * Initializes and registers all Gutenberg blocks for the plugin.
 * Handles block category registration and asset enqueuing.
 *
 * @since 4.0.0
 */
class BlocksInit {

	/**
	 * Single class instance.
	 *
	 * @var BlocksInit
	 */
	private static $instance;

	/**
	 * Get main Blocks Instance.
	 *
	 * Ensures only one instance exists in memory at any time.
	 * Prevents needing to define globals all over the place.
	 *
	 * @static
	 * @return BlocksInit The one true Blocks instance.
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
		// update block settings options if needed.
		$this->sp_real_add_block_settings_option();

		ManageAPI::instance();
		DynamicStyle::instance();

		// Classic shortcode/form blocks (server-rendered via legacy shortcodes).
		ShortcodeBlock::instance();
		ShortcodeFormBlock::instance();

		// Ready Patterns library REST routes.
		ReadyPatterns::instance();

		add_action( 'init', array( $this, 'sp_real_register_blocks' ), 10 );
		add_action( 'enqueue_block_assets', array( $this, 'sp_real_register_block_assets' ) );

		if ( version_compare( $GLOBALS['wp_version'], '5.7', '<' ) ) {
			add_filter( 'block_categories', array( $this, 'sp_real_category_register' ), 10, 2 );
		} else {
			add_filter( 'block_categories_all', array( $this, 'sp_real_category_register' ), 10, 2 );
		}
	}

	/**
	 * Register custom block category.
	 *
	 * Adds "Real Testimonials" category to Gutenberg block inserter.
	 *
	 * @param array $categories Existing block categories.
	 * @return array Modified categories with plugin category added.
	 */
	public function sp_real_category_register( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'sp-testimonial-pro',
					'title' => __( 'Real Testimonials', 'testimonial-free' ),
				),
				array(
					'slug'  => 'sp-testimonial-pro-blocks',
					'title' => __( 'Real Testimonials Pro Blocks', 'testimonial-free' ),
				),
			),
			$categories
		);
	}

	/**
	 * Enqueue block assets.
	 *
	 * Registers and enqueues editor scripts, styles, and localized data.
	 *
	 * @return void
	 */
	public function sp_real_register_block_assets() {
		// load dependencies.
		$dependencies = array();
		$asset_file   = SP_RT_PLUGIN_PATH . 'dist/rtp-blocks.asset.php';
		if ( is_admin() && file_exists( $asset_file ) ) {
			$asset = require $asset_file;
			if ( ! empty( $asset['dependencies'] ) && is_array( $asset['dependencies'] ) ) {
				$dependencies = $asset['dependencies'];
			}
		}

		// Register editor style for all blocks.
		wp_register_style( 'sp-real-blocks-editor', SP_RT_PLUGIN_URL . 'dist/rtp-blocks.css', array(), SP_TFREE_VERSION, 'all' );

		// Register editor script for all blocks.
		wp_register_script( 'sp-real-blocks-editor', SP_RT_PLUGIN_URL . 'dist/rtp-blocks.js', $dependencies, SP_TFREE_VERSION, true );

		// Register style for frontend and editor.
		wp_register_style( 'sp-real-blocks-style', SP_RT_PLUGIN_URL . 'dist/style-rtp-blocks.css', array(), SP_TFREE_VERSION, 'all' );

		// Register frontend script for carousel blocks.
		if ( ! is_admin() ) {
			wp_register_script( 'sp-real-blocks-frontend', SP_RT_PLUGIN_URL . 'src/Blocks/assets/js/script.js', array( 'sp-real-swiper' ), SP_TFREE_VERSION, true );
		}

		// Register Swiper JS for frontend.
		wp_register_script( 'sp-real-swiper', SP_RT_PLUGIN_URL . 'src/Frontend/assets/js/swiper.min.js', array(), SP_TFREE_VERSION, true );

		// Register Swiper CSS for frontend.
		wp_register_style( 'sp-real-swiper', SP_RT_PLUGIN_URL . 'src/Frontend/assets/css/swiper.min.css', array(), SP_TFREE_VERSION, 'all' );

		// Register fontello.
		wp_register_style( 'tpro-block-fontello', SP_RT_PLUGIN_URL . 'src/Admin/assets/css/fontello.min.css', array(), SP_TFREE_VERSION, 'all' );

		// Localize editor script with necessary data.
		$sp_real_plugin_settings = DashboardHelper::get_plugin_settings();
		$real_modules            = isset( $sp_real_plugin_settings['modules'] ) ? $sp_real_plugin_settings['modules'] : array();

		wp_localize_script(
			'sp-real-blocks-editor',
			'sp_real_localize_data',
			array(
				'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
				'pluginUrl'         => SP_RT_PLUGIN_URL,
				'uploadFile'        => wp_upload_dir(),
				'homeUrl'           => home_url( '/' ),
				'spRealAjaxNonce'   => wp_create_nonce( 'sp_real_block_nonce' ),
				'activeBlockList'   => BlocksHelper::sp_real_get_active_block_list(),
				'savedTemplatesUrl' => admin_url( 'edit.php?post_type=sp_real_template&page=rtp_dashboard#saved_templates' ),
				'dashboardSettings' => array( 'modules' => (array) $real_modules ),
			)
		);

		// Localize frontend script with necessary data.
		wp_localize_script(
			'sp-real-blocks-frontend',
			'sp_real_localize_data',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'sp_real_block_nonce' ),
			)
		);

		// Custom global js and css.
		$sp_real_custom_js = isset( $sp_real_plugin_settings['custom_js'] ) ? trim( html_entity_decode( $sp_real_plugin_settings['custom_js'] ) ) : '';
		if ( ! empty( $sp_real_custom_js ) ) {
			wp_add_inline_script( 'sp-real-blocks-frontend', $sp_real_custom_js );
			if ( is_admin() ) {
				wp_add_inline_script( 'sp-real-blocks-editor', $sp_real_custom_js );
			}
		}
		$sp_real_custom_css = isset( $sp_real_plugin_settings['custom_css'] ) ? trim( $sp_real_plugin_settings['custom_css'] ) : '';
		if ( ! empty( $sp_real_custom_css ) ) {
			wp_add_inline_style( 'sp-real-blocks-style', $sp_real_custom_css );
		}
	}

	/**
	 * Register all blocks.
	 *
	 * Iterates through block slugs, loads attributes, and instantiates block classes.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function sp_real_register_blocks() {
		$active_blocks_slug = BlocksHelper::sp_real_get_active_block_list();

		foreach ( $active_blocks_slug as $block_slug ) {
			$attributes          = array();
			$block_short_slug    = str_replace( 'sp-testimonial-pro/', '', $block_slug );
			$file_name           = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $block_short_slug ) ) );
			$attribute_file_path = SP_TFREE_PATH . 'Blocks/Includes/block-attributes.php';
			if ( file_exists( $attribute_file_path ) ) {
				$all_attributes = require $attribute_file_path;
				$attributes     = isset( $all_attributes[ $block_short_slug ] ) ? $all_attributes[ $block_short_slug ] : array();
			}

			$full_class = "\\ShapedPlugin\\TestimonialFree\\Blocks\\BlockTypes\\{$file_name}";
			if ( class_exists( $full_class ) ) {
				new $full_class( $attributes );
			}
		}
	}

	/**
	 * Method sp_real_add_block_settings_option
	 *
	 * @return void
	 */
	private function sp_real_add_block_settings_option() {
		// get all our blocks.
		$our_blocks = BlocksHelper::sp_real_get_all_block_list();
		// Fetch existing saved options.
		$existing_options = DashboardHelper::get_plugin_settings( 'active_blocks', array() );
		// Compare by block name (not count) so a renamed block doesn't masquerade as identical.
		$existing_names = is_array( $existing_options ) ? wp_list_pluck( $existing_options, 'name' ) : array();
		$expected_names = array_merge( $our_blocks, array( 'sp-testimonial-pro/shortcode', 'sp-testimonial-pro/form' ) );
		if ( empty( array_diff( $expected_names, $existing_names ) ) && empty( array_diff( $existing_names, $expected_names ) ) ) {
			return;
		}

		// prepare updated options array with all blocks, marking them as visible by default.
		$updated_options = array_map(
			function ( $block_name ) {
				return array(
					'name' => $block_name,
					'show' => true,
				);
			},
			$our_blocks
		);

		// Ensure the classic shortcode/form blocks are included in the options.
		$updated_options[] = array(
			'name' => 'sp-testimonial-pro/shortcode',
			'show' => true,
		);
		$updated_options[] = array(
			'name' => 'sp-testimonial-pro/form',
			'show' => true,
		);

		// insert if not exist on db.
		if ( empty( $existing_options ) ) {
			DashboardHelper::update_plugin_settings( 'active_blocks', $updated_options );
			return;
		}

		// final options.
		$final_options = array();
		foreach ( $updated_options as $block ) {
			$existing_block  = wp_list_filter( $existing_options, array( 'name' => $block['name'] ) );
			$existing_block  = reset( $existing_block );
			$final_options[] = $existing_block ? $existing_block : $block;
		}

		// update db.
		DashboardHelper::update_plugin_settings( 'active_blocks', $final_options );
	}
}
