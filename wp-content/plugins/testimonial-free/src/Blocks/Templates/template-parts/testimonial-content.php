<?php
/**
 * Testimonial content template part.
 *
 * Renders the testimonial body, honoring the HTML-stripping option.
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

$sp_real_raw_content = $testimonial_data['content'] ?? '';
if ( empty( $sp_real_raw_content ) ) {
	return;
}
$sp_real_strip_html_tags = $attributes['stripAllHTMLTags'] ?? true;

?>
<div class="sp-real-testimonial-content">
	<div class="sp-real-testimonial-text">
		<div class="sp-real-excerpt">
			<?php
			if ( $sp_real_strip_html_tags ) {
				echo esc_html( wp_strip_all_tags( $sp_real_raw_content ) );
			} else {
				echo wp_kses_post( $sp_real_raw_content );
			}
			?>
		</div>
	</div>
</div>
