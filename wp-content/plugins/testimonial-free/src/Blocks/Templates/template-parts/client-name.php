<?php
/**
 * Client name template part.
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

$sp_real_name = $testimonial_data['name'] ?? '';
if ( empty( $sp_real_name ) ) {
	return;
}

$sp_real_name_html_tag = $attributes['nameHtmlTag'] ?? 'h6';
?>
<div class="sp-real-testimonial-client-name sp-d-flex sp-align-center">
	<<?php echo tag_escape( $sp_real_name_html_tag ); ?> class="sp-real-client-name"><?php echo esc_html( $sp_real_name ); ?></<?php echo tag_escape( $sp_real_name_html_tag ); ?>>
</div>
