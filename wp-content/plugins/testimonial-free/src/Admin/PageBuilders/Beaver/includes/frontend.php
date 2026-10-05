<?php
/**
 * Beaver Builder - Real Testimonials Module Frontend Template
 *
 * This file is automatically loaded by Beaver Builder when
 * rendering the module output on the frontend.
 *
 * The $module, $id, and $settings variables are available
 * automatically by Beaver Builder's rendering engine.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 *
 * @var RTP_Beaver_Testimonial_Module $module   The module instance.
 * @var string                          $id       The module's unique node ID.
 * @var object                          $settings The module's saved settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Cannot access directly.
}

// Get the selected template ID from module settings.
$template_id = isset( $settings->template_id ) ? (int) $settings->template_id : 0;

// Show a placeholder if no template has been selected.
if ( empty( $template_id ) ) :
	?>
	<div style="
		text-align: center;
		padding: 20px;
		border: 2px dashed #ccc;
		color: #999;
		font-size: 14px;
	">
		<?php esc_html_e( 'Please Select a Saved Template', 'testimonial-free' ); ?>
	</div>
	<?php
else :
	// Print the template CSS next to the markup, on the frontend as well as in the builder.
	echo \ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css::get( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS markup is escaped in Template_Css.
	?>
	<div class="rtp-beaver-testimonial-wrapper" data-builder-template-id="<?php echo esc_attr( $template_id ); ?>">
		<?php
		// Execute the testimonial shortcode and output the result.
		echo do_shortcode( '[sp_real_template id="' . absint( $template_id ) . '"]' );
		?>
	</div>
	<?php
endif;
