<?php
/**
 * Client image template part.
 *
 * Renders the reviewer avatar.
 *
 * Expects in scope: $attributes, $testimonial_data, $card_design.
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

$sp_real_thumbnails = $testimonial_data['thumbnails'] ?? array();

// Resolve image source by the chosen resolution, with graceful fallbacks.
$sp_real_resolution     = $attributes['imageResolution'] ?? 'original';
$sp_real_resolution_map = array(
	'original'  => 'full',
	'thumbnail' => 'thumbnail',
	'medium'    => 'medium',
	'large'     => 'large',
);
$sp_real_size_key       = $sp_real_resolution_map[ $sp_real_resolution ] ?? 'full';
$sp_real_image_url      = $sp_real_thumbnails[ $sp_real_size_key ] ?? ( $testimonial_data['thumbnail'] ?? '' );

// No reviewer image: resolve the configured fallback (mystery person / smart
// text avatar / gravatar / custom). 'No Fallback Image' ('none') renders nothing.
$sp_real_is_fallback = false;
if ( empty( $sp_real_image_url ) ) {
	$sp_real_is_fallback = true;
	$sp_real_fb_mode     = $attributes['reviewerFallbackImages'] ?? 'mystery_person';
	$sp_real_image_url   = BlocksHelper::reviewer_fallback_src( $sp_real_fb_mode, $testimonial_data );

	// 'none' mode (or any mode that resolves to nothing) → skip the avatar entirely.
	if ( '' === $sp_real_image_url ) {
		return;
	}
}

// Retina: serve the next-larger size at 2x via srcset (real images only).
$sp_real_retina_srcset = '';
if ( ! $sp_real_is_fallback && ! empty( $attributes['load2xInRetinaDisplay'] ) ) {
	$sp_real_retina_map = array(
		'thumbnail' => 'medium',
		'medium'    => 'large',
		'large'     => 'full',
		'full'      => 'full',
	);
	$sp_real_retina_key = $sp_real_retina_map[ $sp_real_size_key ] ?? 'full';
	$sp_real_retina_url = $sp_real_thumbnails[ $sp_real_retina_key ] ?? '';
	if ( ! empty( $sp_real_retina_url ) ) {
		$sp_real_retina_srcset = sprintf( '%1$s 1x, %2$s 2x', esc_url( $sp_real_image_url ), esc_url( $sp_real_retina_url ) );
	}
}

$sp_real_image_alt = $testimonial_data['title'] ?? __( 'Client Image', 'testimonial-free' );

$sp_real_wrapper_class = BlocksHelper::class_list(
	array(
		'sp-real-client-image',
		'sp-real-card-client-image',
	)
);

// Fallback avatars are inline SVG data URIs — esc_url() would strip the data:
// scheme, so escape the trusted, self-generated src with esc_attr instead.
$sp_real_src_escaped = ( 0 === strpos( $sp_real_image_url, 'data:' ) ) ? esc_attr( $sp_real_image_url ) : esc_url( $sp_real_image_url );

$sp_real_img_tag = sprintf(
	'<img decoding="async" src="%1$s"%3$s width="120" height="120" alt="%2$s" class="sp-real-img-tag sp-real-grayscale-none%4$s" />',
	$sp_real_src_escaped,
	esc_attr( $sp_real_image_alt ),
	$sp_real_retina_srcset ? ' srcset="' . esc_attr( $sp_real_retina_srcset ) . '"' : '',
	$sp_real_is_fallback ? ' sp-real-fallback-image' : ''
);

?>
<div class="<?php echo esc_attr( $sp_real_wrapper_class ); ?>">
	<div class="sp-real-client-image-wrap">
		<?php echo $sp_real_img_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Pre-escaped above. ?>
	</div>
</div>
