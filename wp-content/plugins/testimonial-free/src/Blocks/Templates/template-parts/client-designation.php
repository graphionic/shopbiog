<?php
/**
 * Client designation template part.
 *
 * Renders "{position} at {company}", falling back to whichever value is set.
 *
 * Expects in scope: $attributes, $testimonial_data.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$sp_real_position = $testimonial_data['position'] ?? '';
$sp_real_company  = $testimonial_data['company'] ?? '';

// Merge the company name into the label when present.
if ( ! empty( $sp_real_company ) ) {
	/* translators: 1: position, 2: company name */
	$sp_real_label = ! empty( $sp_real_position ) ? sprintf( __( '%1$s at %2$s', 'testimonial-free' ), $sp_real_position, $sp_real_company ) : $sp_real_company;
} else {
	$sp_real_label = $sp_real_position;
}

if ( '' === (string) $sp_real_label ) {
	return;
}
?>
<div class="sp-real-client-designation">
	<?php echo esc_html( $sp_real_label ); ?>
</div>
