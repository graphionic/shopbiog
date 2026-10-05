<?php
/**
 * Testimonial Submission Form Block
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/BlockTypes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\BlockTypes;

use ShapedPlugin\TestimonialFree\Blocks\Abstracts\BlockBase;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Testimonial Submission Form Block Class
 *
 * Standalone block. Renders a customizable inline frontend form so visitors
 * can submit testimonials. Supports configurable input fields, AJAX
 * submission, and a moderation status (pending/private/draft) for new
 * submissions. Popup display, reCAPTCHA, email notifications, auto-publish,
 * and redirect control are Pro-only features.
 *
 * @since 4.0.0
 */
class TestimonialSubmissionForm extends BlockBase {

	/**
	 * Block name (slug) without namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'testimonial-submission-form';

	/**
	 * Render the submission form block on the frontend.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Inner block content (unused).
	 * @param array  $blocks     Block instances.
	 * @return string Rendered HTML output.
	 */
	public function render_callback( $attributes, $content = '', $blocks = array() ) {
		if ( BlocksHelper::is_editor_page() ) {
			return $content;
		}

		$align             = $attributes['align'] ?? 'wide';
		$unique_id         = $attributes['uniqueId'] ?? '';
		$block_name        = $attributes['blockName'] ?? '';
		$template          = $attributes['template'] ?? 'template-one';
		$custom_class_name = $attributes['customClassName'] ?? '';
		$custom_id_name    = $attributes['customIdName'] ?? '';

		ob_start();
		?>
		<div
			class="sp-real-testimonial-block align<?php echo esc_attr( $align . ( $custom_class_name ? " $custom_class_name" : '' ) ); ?>"
			<?php
			if ( $custom_id_name ) {
				echo 'id="' . esc_attr( $custom_id_name ) . '"';
			}
			?>
		>
			<div id="<?php echo esc_attr( $unique_id ); ?>" class="<?php echo esc_attr( BlocksHelper::class_list( array( 'sp-real-' . $block_name, 'sp-real-tsf--inline' ) ) ); ?> sp-real-block-frontend">
				<div class="sp-real-tsf-<?php echo esc_attr( $template ); ?> sp-real-template-wrapper">
					<?php $this->render_form( $attributes ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the form element itself (heading, fields, submit, messages).
	 *
	 * @param array $attributes Block attributes.
	 * @return void
	 */
	private function render_form( $attributes ) {
		$unique_id          = $attributes['uniqueId'] ?? '';
		$is_ajax_submission = $attributes['ajaxFormSubmission'] ?? true;
		$message_position   = $attributes['submissionMessagePosition'] ?? 'top';
		$success_message    = $attributes['successMessage'] ?? __( 'Thank you! Your testimonial has been received.', 'testimonial-free' );
		$error_message      = $attributes['errorMessage'] ?? __( 'We encountered an issue processing your testimonial.', 'testimonial-free' );
		$input_fields       = isset( $attributes['formFields'] ) && is_array( $attributes['formFields'] ) ? $attributes['formFields'] : array();
		$show_required      = $attributes['showRequiredNotice'] ?? true;
		$required_label     = $attributes['requiredNoticeLabel'] ?? '';
		$input_style        = $attributes['inputStyle'] ?? 'style-one';

		// Non-AJAX submit redirects back with a status flag; surface the matching
		// message on load (the AJAX path toggles these holders client-side).
		$force_show_notification = '';
		if ( ! $is_ajax_submission ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag set by our own redirect; no state change.
			if ( isset( $_GET['sp_real_tsf_submitted'] ) ) {
				$force_show_notification = 'success';
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_GET['sp_real_tsf_error'] ) ) {
				$force_show_notification = 'error';
			}
		}

		?>
		<form
			data-form-id="<?php echo esc_attr( $unique_id ); ?>"
			data-post-id="<?php echo esc_attr( (string) get_the_ID() ); ?>"
			data-message-position="<?php echo esc_attr( $message_position ); ?>"
			enctype="multipart/form-data"
			class="<?php echo esc_attr( BlocksHelper::class_list( array( 'sp-real-tsf-form sp-d-flex sp-flex-col', $is_ajax_submission ? 'sp-real-tsf-ajax' : '' ) ) ); ?>"
			<?php
			// method for normal submission.
			echo $is_ajax_submission ? '' : ' method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"';
			?>
		>
			<?php
			if ( 'top' === $message_position ) {
				$this->render_message_holder( $success_message, $error_message, 'top', $force_show_notification );
			}
			// required-fields notice — hidden until the user attempts to submit
			// with an empty required field (revealed by script.js on `invalid`).
			if ( $show_required && '' !== $required_label ) {
				echo '<p class="sp-real-tsf-required-notice" hidden>' . esc_html( $required_label ) . '</p>';
			}
			?>
			<div class="<?php echo esc_attr( 'sp-real-tsf-fields sp-d-flex sp-flex-wrap sp-real-tsf-input-' . $input_style ); ?>">
				<?php
				// form fields.
				foreach ( $input_fields as $field ) {
					if ( $field['showField'] ) {
						$this->render_field( $field, $attributes );
					}
				}
				// terms and conditions.
				if ( ! empty( $attributes['showTermsAndCondition'] ) ) {
					$this->render_terms( $attributes );
				}
				?>
			</div>
			<?php
			// submit button.
			$this->render_submit_button( $attributes );
			// nonce field for security.
			wp_nonce_field( 'sp_real_tsf_nonce', 'sp_real_tsf_nonce' );
			?>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $unique_id ); ?>" />
			<input type="hidden" name="post_id" value="<?php echo esc_attr( (string) get_the_ID() ); ?>" />
			<?php
			if ( ! $is_ajax_submission ) {
				echo '<input type="hidden" name="action" value="sp_real_tsf_submit_normal" />';
			}
			if ( 'bottom' === $message_position ) {
				$this->render_message_holder( $success_message, $error_message, 'bottom', $force_show_notification );
			}
			?>
		</form>
		<?php
	}

	/**
	 * Render the message placeholder for ajax success/error feedback.
	 *
	 * @param string $success_message Success message text.
	 * @param string $error_message   Error message text.
	 * @param string $position        Message position ('top'|'bottom').
	 * @param string $force_show      Show a message on load ('success'|'error'|'') — used by the non-AJAX redirect flow.
	 * @return void
	 */
	private function render_message_holder( $success_message, $error_message, $position = 'top', $force_show = '' ) {
		// Spacing between the message holder and the form: gap below when shown
		// above the fields, gap above when shown below the submit button.
		$spacing = 'bottom' === $position ? 'margin-top:20px' : 'margin-bottom:20px';
		?>
		<div class="sp-real-tsf-messages" style="<?php echo esc_attr( $spacing ); ?>" aria-live="polite">
			<div class="sp-real-tsf-message sp-real-tsf-message__success"<?php echo 'success' === $force_show ? '' : ' hidden'; ?>><?php echo esc_html( $success_message ); ?></div>
			<div class="sp-real-tsf-message sp-real-tsf-message__error"<?php echo 'error' === $force_show ? '' : ' hidden'; ?>><?php echo esc_html( $error_message ); ?></div>
		</div>
		<?php
	}

	/**
	 * Render a single configured field.
	 *
	 * @param array $field            Field definition.
	 * @param array $block_attributes Full block attributes (for text alignment, etc).
	 * @return void
	 */
	private function render_field( $field, $block_attributes = array() ) {
		// block attr.
		$unique_id      = $block_attributes['uniqueId'] ?? '';
		$text_alignment = $block_attributes['textAlignment'] ?? 'left';
		$fields_gap     = $block_attributes['fieldsGap'] ?? array();
		$field_border   = $block_attributes['fieldBorderWidth']['value'] ?? array();
		// form data.
		$field_name_key = $field['fieldName'] ?? '';
		$type           = self::field_type( $field_name_key );
		$input_name     = $field_name_key;
		$id             = $field['id'] ?? sanitize_title( $field_name_key );
		$label          = $field['label'] ?? '';
		$placeholder    = $field['placeholder'] ?? '';
		$help_text      = $field['helpText'] ?? '';
		$required       = ! empty( $field['required'] );
		// Scope the field/input id by the block uniqueId so multiple forms on
		// one page don't share DOM ids (label `for` would otherwise bind to the
		// first form).
		$field_id     = "$unique_id-tsf-$id";
		$length_mode  = $field['length'] ?? '';
		$limit        = isset( $field['limit'] ) && is_array( $field['limit'] ) ? $field['limit'] : array();
		$limit_value  = isset( $limit['value'] ) ? (int) $limit['value'] : 0;
		$limit_unit   = $limit['unit'] ?? 'words';
		$_field_width = $field['fieldWidth'] ?? array();
		$field_width  = self::field_width_css( $_field_width, $fields_gap, $field_border );

		$length_attrs = '';
		if ( 'limited' === $length_mode && $limit_value > 0 ) {
			$length_attrs = ' data-length-unit="' . esc_attr( $limit_unit ) . '" data-length-limit="' . esc_attr( (string) $limit_value ) . '"';
			if ( 'characters' === $limit_unit ) {
				$length_attrs .= ' maxlength="' . esc_attr( (string) $limit_value ) . '"';
			}
		}

		$is_rating = 'rating' === $type;
		?>
		<div class="sp-real-tsf-field sp-d-flex sp-flex-col sp-real-tsf-field--<?php echo esc_attr( $type ); ?>" data-field="<?php echo esc_attr( $field_name_key ); ?>" style="width:<?php echo esc_attr( $field_width ); ?>">
			<div class="sp-real-tsf-field-label-section sp-d-flex sp-align-center sp-justify-<?php echo 'left' === $text_alignment ? 'between' : esc_attr( $text_alignment ); ?> sp-gap-10px">
				<label class="sp-real-tsf-field__label sp-d-i-flex sp-gap-4px" for="<?php echo esc_attr( $field_id ); ?>">
					<?php
					echo esc_html( $label );
					if ( $required ) {
						echo '<span class="sp-real-tsf-required" aria-hidden="true">*</span>';
					}
					?>
				</label>
				<?php if ( 'limited' === $length_mode && $limit_value > 0 ) { ?>
					<span class="sp-real-tsf-field__length">
						0 <?php echo esc_html( $limit_unit ); ?> out of <?php echo esc_html( (string) $limit_value ); ?>
					</span>
					<?php } ?>
			</div>
			<?php
			// help text (rating shows its note above the stars).
			if ( $is_rating && '' !== $help_text ) {
				echo '<span class="sp-real-tsf-field__note sp-tsf-rating-note sp-d-block">' . esc_html( $help_text ) . '</span>';
			}
			// fields.
			switch ( $type ) {
				case 'textarea':
					$rows = isset( $field['rows'] ) ? (int) $field['rows'] : 5;
					?>
					<textarea
						id="<?php echo esc_attr( $field_id ); ?>"
						name="<?php echo esc_attr( $input_name ); ?>"
						class="sp-real-tsf-field__input"
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						rows="<?php echo esc_attr( (string) $rows ); ?>"
						<?php echo $required ? 'required' : ''; ?>
						<?php echo $length_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					></textarea>
					<?php
					break;
				case 'rating':
					?>
					<div class="sp-real-tsf-rating sp-d-flex sp-justify-start sp-row-reverse sp-gap-4px" role="radiogroup" aria-label="<?php echo esc_attr( $label ); ?>">
						<?php
						$rating_map = array(
							5 => 'five_star',
							4 => 'four_star',
							3 => 'three_star',
							2 => 'two_star',
							1 => 'one_star',
						);
						foreach ( $rating_map as $stars => $value ) {
							$rid = $field_id . '-' . $stars;
							echo '<span class="sp-real-tsf-rating__item">';
							echo '<input type="radio" id="' . esc_attr( $rid ) . '" name="' . esc_attr( $input_name ) . '" value="' . esc_attr( $value ) . '"' . ( 0 === $stars ? ' checked' : '' ) . ( $required ? ' required' : '' ) . ' />';
							echo '<label class="sp-cursor-pointer" for="' . esc_attr( $rid ) . '" title="' . esc_attr( sprintf( /* translators: %d: star number */ _n( '%d star', '%d stars', $stars, 'testimonial-free' ), $stars ) ) . '"><svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.7961 0.470057L13.4481 5.67684L19.2607 6.61696C19.5877 6.65311 19.842 6.87006 19.951 7.19549C20.0599 7.52091 19.9873 7.84634 19.733 8.06329L15.5915 12.2215L16.4997 18.0068C16.536 18.3322 16.4271 18.6576 16.1364 18.8384C15.8821 19.0192 15.5188 19.0554 15.2282 18.9108L9.99686 16.2712L4.7655 18.9108C4.47487 19.0554 4.11159 19.0192 3.85728 18.8384C3.56665 18.6576 3.45767 18.3322 3.494 18.0068L4.40222 12.2215L0.260729 8.06329C0.0427559 7.84634 -0.0662306 7.52091 0.0427559 7.19549C0.151742 6.87006 0.406044 6.65311 0.733004 6.61696L6.54562 5.67684L9.23395 0.470057C9.37927 0.180791 9.6699 0 9.99686 0C10.3238 0 10.6144 0.180791 10.7961 0.470057Z" fill="currentColor"/></svg></label>';
							echo '</span>';
						}
						?>
					</div>
					<?php
					break;
				case 'file':
					?>
					<input
						id="<?php echo esc_attr( $field_id ); ?>"
						type="file"
						name="<?php echo esc_attr( $input_name ); ?>"
						class="sp-real-tsf-field__input sp-real-tsf-field__file"
						accept="image/*"
						<?php echo $required ? 'required' : ''; ?>
					/>
					<?php
					break;
				default:
					?>
					<input
						id="<?php echo esc_attr( $field_id ); ?>"
						type="<?php echo esc_attr( $type ); ?>"
						name="<?php echo esc_attr( $input_name ); ?>"
						class="sp-real-tsf-field__input"
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						<?php echo $required ? 'required' : ''; ?>
						<?php echo $length_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					/>
					<?php
					break;
			}
			// help text for non-rating fields.
			if ( ! $is_rating && '' !== $help_text ) {
				echo '<span class="sp-real-tsf-field__note sp-d-block">' . esc_html( $help_text ) . '</span>';
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render the terms and conditions checkbox row.
	 *
	 * @param array $attributes Block attributes.
	 * @return void
	 */
	private function render_terms( $attributes ) {
		$label        = $attributes['termsAndConditionLabel'] ?? '';
		$anchor_label = $attributes['termsAndConditionAnchorLabel'] ?? '';
		$link         = $attributes['termsAndConditionLink'] ?? '#';
		?>
		<div class="sp-real-tsf-field sp-real-tsf-field--checkbox sp-d-flex sp-flex-col" data-field="tpro_terms">
			<span class="sp-real-tsf-field__checkbox sp-d-i-flex sp-align-center sp-gap-8px">
				<input type="checkbox" name="tpro_terms" value="1" required />
				<span class="sp-real-tsf-field__terms">
					<?php
					echo esc_html( $label ) . ' ';
					printf(
						'<a href="%1$s" target="_blank" rel="noreferrer">%2$s</a>',
						esc_url( $link ),
						esc_html( $anchor_label )
					);
					?>
				</span>
			</span>
		</div>
		<?php
	}

	/**
	 * Resolve the CSS width for a field from its { value, unit } attribute.
	 *
	 * At 100% the field owns the row. Below 100% it shares a flex row, so half
	 * the column gap and half the field's left+right border width are
	 * subtracted to keep two columns from overflowing. The gap uses the Desktop
	 * value (the inline width is a single value; responsive gaps are not
	 * reflowed on the frontend).
	 *
	 * @param array $field_width      Stored fieldWidth attribute { value, unit }.
	 * @param array $fields_gap is fields_gap.
	 * @param array $field_border is field_border.
	 * @return string CSS width value.
	 */
	private static function field_width_css( $field_width, $fields_gap, $field_border ) {
		$value = (float) ( $field_width['value'] ?? 100 );
		$unit  = $field_width['unit'] ?? '%';
		if ( $value >= 100 && '%' === $unit ) {
			return '100%';
		}

		$parts       = array( $value . $unit );
		$device_type = wp_is_mobile() ? 'Mobile' : 'Desktop';
		$gap_value   = (float) ( $fields_gap['device'][ $device_type ] ?? 0 );

		if ( $gap_value ) {
			$gap_unit = $fields_gap['unit'][ $device_type ] ?? 'px';
			$parts[]  = ( $gap_value / 2 ) . $gap_unit;
		}

		$border_sum = (float) ( $field_border['left'] ?? 0 ) + (float) ( $field_border['right'] ?? 0 );
		if ( $border_sum ) {
			$border_unit = $field_border['unit'] ?? 'px';
			$parts[]     = ( $border_sum / 2 ) . $border_unit;
		}

		return count( $parts ) > 1 ? 'calc(' . implode( ' - ', $parts ) . ')' : $parts[0];
	}

	/**
	 * Map a fieldName to its HTML control type.
	 *
	 * @param string $field_name Canonical fieldName.
	 * @return string
	 */
	public static function field_type( $field_name ) {
		$map = array(
			'tpro_client_name'        => 'text',
			'tpro_client_email'       => 'email',
			'tpro_client_designation' => 'text',
			'tpro_client_rating'      => 'rating',
			'tpro_testimonial_title'  => 'text',
			'tpro_client_testimonial' => 'textarea',
			'tpro_client_image'       => 'file',
		);
		return $map[ $field_name ] ?? 'text';
	}

	/**
	 * Render the submit button block.
	 *
	 * @param array $attributes Block attributes.
	 * @return void
	 */
	private function render_submit_button( $attributes ) {
		$label        = $attributes['submitButtonLabel'] ?? __( 'Submit Testimonial', 'testimonial-free' );
		$button_width = $attributes['submitButtonWidth'] ?? 'auto';
		$alignment    = $attributes['submitButtonAlignment'] ?? 'left';
		$show_icon    = ! empty( $attributes['showSubmitButtonIcon'] );
		$icon         = $attributes['submitButtonIcon'] ?? array();
		$icon_pos     = $attributes['submitButtonIconPosition'] ?? 'right';
		$icon_size    = $attributes['submitButtonIconSize'] ?? array();
		$size_value   = isset( $icon_size['value'] ) ? $icon_size['value'] : 16;
		$size_unit    = $icon_size['unit'] ?? 'px';
		$icon_dim     = esc_attr( $size_value . $size_unit );

		?>
		<div class="<?php echo esc_attr( BlocksHelper::class_list( array( 'sp-real-tsf-submit-wrap', 'auto' === $button_width ? ( 'sp-d-flex sp-justify-' . $alignment ) : 'sp-tsf-full-btn' ) ) ); ?>">
			<button type="submit" class="<?php echo esc_attr( BlocksHelper::class_list( array( 'sp-real-tsf-submit sp-d-flex sp-align-center sp-justify-center sp-gap-8px', ( $show_icon && 'left' === $icon_pos ) ? 'sp-row-reverse' : '' ) ) ); ?>">
				<span class="sp-real-tsf-submit__label"><?php echo esc_html( $label ); ?></span>
				<?php
				if ( $show_icon ) {
					echo '<span class="sp-real-tsf-submit__icon sp-d-i-flex" style="width:' . $icon_dim . ';height:' . $icon_dim . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$this->render_library_icon( $icon );
					echo '</span>';
				}
				?>
			</button>
		</div>
		<?php
	}

	/**
	 * Render an IconsLibrary value (library svg or custom image).
	 *
	 * @param array $icon Icon attribute object.
	 * @return void
	 */
	private function render_library_icon( $icon ) {
		if ( ! is_array( $icon ) ) {
			return;
		}

		if ( 'custom' === ( $icon['source'] ?? 'icon' ) ) {
			$url = $icon['image']['url'] ?? '';
			if ( $url ) {
				echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $icon['image']['alt'] ?? '' ) . '" style="width:100%;height:100%" />';
			}
			return;
		}

		$view_box = $icon['icon']['viewBox'] ?? '';
		$path     = $icon['icon']['path'] ?? '';
		if ( '' === $path ) {
			return;
		}
		echo '<span class="sp-real-library-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="' . esc_attr( $view_box ) . '" aria-hidden="true"><path d="' . esc_attr( $path ) . '" /></svg></span>';
	}
}
