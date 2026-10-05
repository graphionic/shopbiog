<?php
/**
 * Swiper Pagination.
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

$sp_real_pagination_position = $attributes['paginationDotsPosition'] ?? 'center';
$sp_real_pagination_style    = $attributes['paginationStyle'] ?? 'dots';

?>

<div class="sp-real-swiper-pagination <?php echo 'scrollbar' === $sp_real_pagination_style ? 'swiper-scrollbar' : ( 'swiper-pagination sp-d-flex sp-align-center sp-justify-' . esc_attr( $sp_real_pagination_position ) ); ?> <?php echo esc_attr( $sp_real_pagination_style ); ?>"></div>