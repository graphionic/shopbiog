<?php
/**
 * Client rating template part.
 *
 * Renders a 5-icon rating (full, half, empty) using the inline SVGs returned by
 * BlocksHelper::get_rating_svg_icon(). The active/inactive icon keys come from
 * the `ratingIconSet` attribute set by the rating style picker.
 *
 * Expects in scope: $testimonial_data, $attributes.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Templates
 */

use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$sp_real_max_rating  = 5;
$sp_real_rating      = (float) ( $testimonial_data['rating'] ?? 0 );
$sp_real_rating      = max( 0, min( $sp_real_rating, $sp_real_max_rating ) );
$sp_real_filled      = (int) floor( $sp_real_rating );
$sp_real_has_half    = ( $sp_real_rating - $sp_real_filled ) >= 0.5;
$sp_real_empty_count = $sp_real_max_rating - $sp_real_filled - ( $sp_real_has_half ? 1 : 0 );

// Resolve the chosen icon pair → inline SVG (keys into the rating icon set).
$sp_real_icon_set     = isset( $attributes['ratingIconSet'] ) && is_array( $attributes['ratingIconSet'] ) ? $attributes['ratingIconSet'] : array();
$sp_real_active_key   = ! empty( $sp_real_icon_set['active'] ) ? $sp_real_icon_set['active'] : 'star-fill';
$sp_real_inactive_key = ! empty( $sp_real_icon_set['inactive'] ) ? $sp_real_icon_set['inactive'] : 'star-stroke';

$sp_real_active_svg   = BlocksHelper::get_rating_svg_icon( $sp_real_active_key );
$sp_real_inactive_svg = BlocksHelper::get_rating_svg_icon( $sp_real_inactive_key );
if ( '' === $sp_real_active_svg || '' === $sp_real_inactive_svg ) {
	return;
}

?>
<div class="sp-real-client-rating sp-d-flex sp-align-center">
	<?php for ( $i = 0; $i < $sp_real_filled; $i++ ) { ?>
		<span class="sp-real-rating-icon sp-real-rating-full"><?php echo $sp_real_active_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_rating_svg_icon(). ?></span>
	<?php } ?>

	<?php if ( $sp_real_has_half ) { ?>
		<span class="sp-real-rating-icon sp-real-rating-half">
			<span class="sp-real-rating-base"><?php echo $sp_real_inactive_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_rating_svg_icon(). ?></span>
			<span class="sp-real-rating-fill"><?php echo $sp_real_active_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_rating_svg_icon(). ?></span>
		</span>
	<?php } ?>

	<?php for ( $i = 0; $i < $sp_real_empty_count; $i++ ) { ?>
		<span class="sp-real-rating-icon sp-real-rating-empty"><?php echo $sp_real_inactive_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_rating_svg_icon(). ?></span>
	<?php } ?>
</div>
