<?php
/**
 * Elementor Builder Integration
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Elementor;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor builder integration.
 *
 * Registers the Real Testimonials widget with Elementor.
 *
 * @since 4.2.0
 */
class Elementor_Builder {

	/**
	 * Initialize the integration.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function init() {
		// Register widget.
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widget' ) );

		// Enqueue styles and scripts for Elementor preview.
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'enqueue_preview_styles' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( __CLASS__, 'enqueue_preview_scripts' ) );

		// Enqueue admin icon.
		add_action( 'elementor/editor/before_enqueue_scripts', array( __CLASS__, 'enqueue_admin_icon' ) );

		// Register the block asset set so the widget's declared dependencies resolve.
		add_action( 'elementor/editor/before_enqueue_scripts', array( Builder_Assets::class, 'register' ) );
	}

	/**
	 * Register the widget with Elementor.
	 *
	 * @since 4.2.0
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public static function register_widget( $widgets_manager ) {
		require_once __DIR__ . '/Elementor_Widget.php';
		$widgets_manager->register( new Elementor_Widget() );
	}

	/**
	 * Enqueue block styles in the Elementor preview.
	 *
	 * Registers the set first: `enqueue_block_assets` is short-circuited in admin, so
	 * the handles the widget declares in get_style_depends() may not exist yet.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function enqueue_preview_styles() {
		Builder_Assets::enqueue();
	}

	/**
	 * Enqueue block scripts in the Elementor preview.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function enqueue_preview_scripts() {
		Builder_Assets::enqueue();

		// Re-initialise the block frontend after Elementor re-renders the widget.
		Builder_Assets::reinit_script(
			'rtp-elementor-testimonial-wrapper',
			array( 'elementor/render/start', 'elementor/frontend/init' )
		);
	}

	/**
	 * Enqueue admin icon for Elementor.
	 *
	 * Elementor renders the widget icon as `<i class="…">`, so the shared plugin
	 * icon is painted on Builder_Icon::CSS_CLASS. The fontello sheet stays because
	 * other admin surfaces share its glyph set.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public static function enqueue_admin_icon() {
		wp_enqueue_style( 'sprtp_element_block_icon', SP_TFREE_URL . 'Admin/assets/css/fontello.min.css', array(), SP_TFREE_VERSION, 'all' );
		Builder_Icon::enqueue_css( 'rtp-elementor-widget-icon' );
	}
}
