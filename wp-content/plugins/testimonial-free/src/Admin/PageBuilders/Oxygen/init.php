<?php
/**
 * Oxygen Builder - Initialization
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Oxygen;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register element with Oxygen Builder.
 *
 * @return void
 */
function rtp_oxygen_builder_register() {
	// Check if Oxygen is active.
	if ( ! class_exists( 'OxyEl' ) ) {
		return;
	}

	// Check if integration is enabled.
	$options        = get_option( 'sp_testimonial_pro_options', array() );
	$oxygen_enabled = isset( $options['integrations']['oxygen']['is_active'] )
		? (bool) $options['integrations']['oxygen']['is_active']
		: true;

	if ( ! $oxygen_enabled ) {
		return;
	}

	// Require the element file first.
	require_once __DIR__ . '/Oxygen_Builder.php';

	// Register the element.
	new RTP_Oxygen_Testimonial_Element();
}

// Register on init hook with priority 11 (after Manager loads at priority 10).
add_action( 'init', __NAMESPACE__ . '\rtp_oxygen_builder_register', 11 );
