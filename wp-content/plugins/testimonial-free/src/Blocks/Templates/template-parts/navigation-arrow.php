<?php
/**
 * Swiper Navigation Arrow.
 *
 * Expects in scope: $attributes.
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

$sp_real_icon_name     = $attributes['navIconName'] ?? 'chevron-solid';
$sp_real_icon_position = $attributes['navIconPosition'] ?? 'vertical_center';

// Self-contained inline SVG for the chosen arrow style (unknown → chevron-solid).
$sp_real_nav_icon = BlocksHelper::get_navigation_arrow_svg_icon( $sp_real_icon_name );

?>

<div class="sp-real-swiper-nav-arrows sp-d-flex sp-align-center sp-justify-between sp-real-nav-pos-<?php echo esc_attr( $sp_real_icon_position ); ?>">
	<div class="sp-real-swiper-navigation sp-real-nav-prev sp-d-flex sp-align-center sp-justify-center">
		<?php echo $sp_real_nav_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_navigation_arrow_svg_icon(). ?>
	</div>
	<div class="sp-real-swiper-navigation sp-real-nav-next sp-d-flex sp-align-center sp-justify-center">
		<?php echo $sp_real_nav_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static inline SVG from BlocksHelper::get_navigation_arrow_svg_icon(). ?>
	</div>
</div>
