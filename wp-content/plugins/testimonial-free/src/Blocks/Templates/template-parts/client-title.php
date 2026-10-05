<?php
/**
 * Client title template part.
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

$sp_real_title = $testimonial_data['title'] ?? '';
if ( empty( $sp_real_title ) ) {
	return;
}

$sp_real_title_tag = $attributes['titleTag'] ?? 'span';
?>
<div class="sp-real-testimonial-client-title">
	<<?php echo tag_escape( $sp_real_title_tag ); ?> class="sp-real-client-title"><?php echo esc_html( $sp_real_title ); ?></<?php echo tag_escape( $sp_real_title_tag ); ?>>
</div>
