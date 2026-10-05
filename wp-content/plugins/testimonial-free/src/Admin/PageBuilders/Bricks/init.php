<?php
/**
 * Bricks Builder - Initialization
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Bricks;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register element with Bricks Builder.
 *
 * @return void
 */
function rtp_bricks_builder_register() {
	// Check if Bricks is active.
	if ( ! defined( 'BRICKS_VERSION' ) ) {
		return;
	}

	// Check if integration is enabled.
	$options        = get_option( 'sp_testimonial_pro_options', array() );
	$bricks_enabled = isset( $options['integrations']['bricks']['is_active'] )
		? (bool) $options['integrations']['bricks']['is_active']
		: true;

	if ( ! $bricks_enabled ) {
		return;
	}

	// Register element with file path and explicit class name.
	// Third parameter ensures correct class is used when file contains multiple classes.
	\Bricks\Elements::register_element(
		__DIR__ . '/Bricks_Builder.php',
		'rtp-bricks-testimonial',
		__NAMESPACE__ . '\RTP_Bricks_Testimonial_Element'
	);
}

// Register on init hook with priority 11 to ensure Bricks is loaded first.
add_action( 'init', __NAMESPACE__ . '\rtp_bricks_builder_register', 11 );

/**
 * Paint the shared plugin icon on the element in the Bricks builder panel.
 *
 * Bricks only accepts a class name for `$icon`, so the icon itself is delivered
 * as CSS loaded in the builder window.
 *
 * @return void
 */
function rtp_bricks_enqueue_element_icon() {
	if ( ! function_exists( 'bricks_is_builder_main' ) || ! bricks_is_builder_main() ) {
		return;
	}

	Builder_Icon::enqueue_css( 'rtp-bricks-element-icon' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\rtp_bricks_enqueue_element_icon' );
