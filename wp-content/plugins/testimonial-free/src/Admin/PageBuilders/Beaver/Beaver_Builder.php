<?php
/**
 * Beaver Builder - Real Testimonials Module
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_List;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Cannot access directly.
}

/**
 * Real Testimonials Beaver Builder Module.
 *
 * Registers a custom module in Beaver Builder with a
 * Select field to choose and render a testimonial
 * saved template via shortcode.
 */
class RTP_Beaver_Testimonial_Module extends \FLBuilderModule {

	/**
	 * Constructor - defines module properties.
	 */
	public function __construct() {
		parent::__construct(
			array(
				// Module name shown in the builder panel.
				'name'            => __( 'Real Testimonials', 'testimonial-free' ),

				// Short description shown on hover.
				'description'     => __( 'Display a testimonial saved template.', 'testimonial-free' ),

				// Module category in the builder panel.
				'category'        => __( 'SP Plugins', 'testimonial-free' ),

				// Folder path to this module's files.
				// frontend.php must be inside this directory.
				'dir'             => plugin_dir_path( __FILE__ ),

				// URL path to this module's files.
				'url'             => plugin_dir_url( __FILE__ ),

				// Module icon (SVG).
				'icon'            => $this->get_module_icon(),

				// Editor export enabled.
				'editor_export'   => true,

				// Module is enabled by default.
				'enabled'         => true,

				// Partial refresh on setting change (no full reload).
				'partial_refresh' => true,
			)
		);

		// Enqueue scripts for page builder.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Get module icon SVG.
	 *
	 * Beaver accepts raw SVG markup for the module icon, so the shared plugin
	 * icon is inlined straight from disk.
	 *
	 * @since 4.2.0
	 *
	 * @return string SVG icon content.
	 */
	private function get_module_icon() {
		$icon_svg = Builder_Icon::svg();
		if ( ! empty( $icon_svg ) ) {
			return $icon_svg;
		}
		return 'ti-comment'; // Fallback icon.
	}

	/**
	 * Enqueue block assets for the Beaver Builder editor.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Only load in Beaver builder editor.
		if ( ! \FLBuilderModel::is_builder_active() ) {
			return;
		}

		Builder_Assets::enqueue();

		// Re-initialise the block frontend after Beaver re-renders the layout.
		Builder_Assets::reinit_script(
			'rtp-beaver-testimonial-wrapper',
			array( 'fl-builder-preview-render', 'fl-builder-layout-rendered' )
		);
	}
}

/**
 * Initialize and register the Beaver Builder module.
 *
 * All registration must happen inside this function so that
 * FLBuilder is guaranteed to be available at call time.
 *
 * @return void
 */
function rtp_beaver_init_module() {

	// Check if Beaver Builder is active.
	if ( ! class_exists( 'FLBuilder' ) ) {
		return;
	}

	// Get saved templates list.
	$templates = rtp_beaver_get_templates_list();

	// Register the module class together with its settings fields.
	// FLBuilder::register_module() must receive both the class name
	// and the settings array — never call it without arguments.
	\FLBuilder::register_module(
		'RTP_Beaver_Testimonial_Module',
		array(
			'general' => array(
				'title'    => __( 'General', 'testimonial-free' ),
				'sections' => array(
					'content' => array(
						'title'  => __( 'Real Testimonials', 'testimonial-free' ),
						'fields' => array(
							'template_id' => array(
								'type'    => 'select',
								'label'   => __( 'Saved Templates', 'testimonial-free' ),
								'default' => '',
								'options' => $templates,
								'help'    => __( 'Please Select a Saved Template', 'testimonial-free' ),
							),
						),
					),
				),
			),
		)
	);
}

// Use Beaver Builder's own hook to load modules at the right time.
add_action( 'init', 'rtp_beaver_init_module' );

/**
 * Get saved templates list for the Beaver Builder module.
 *
 * Beaver stores an empty string when nothing is selected.
 *
 * @since 4.2.0
 *
 * @return array Templates list.
 */
function rtp_beaver_get_templates_list() {
	return Template_List::options( '' );
}
