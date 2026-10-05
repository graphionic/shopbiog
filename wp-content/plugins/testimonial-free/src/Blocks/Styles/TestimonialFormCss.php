<?php
/**
 * Testimonial Submission Form Dynamic CSS Renderer.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Styles
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Styles;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * TestimonialFormCss - Dynamic CSS generation for the Testimonial Submission Form block.
 */
class TestimonialFormCss {
	/**
	 * Attributes.
	 *
	 * @var array
	 */
	public $attributes = array();

	/**
	 * Unique ID selector (with leading `#`).
	 *
	 * @var string
	 */
	public $unique_id = '';

	/**
	 * Constructor.
	 *
	 * @param array $attributes Block attributes.
	 */
	public function __construct( $attributes ) {
		$this->attributes = $attributes;
		$this->unique_id  = isset( $attributes['uniqueId'] ) ? '#' . $attributes['uniqueId'] : '';
	}

	/**
	 * Generate responsive CSS for a specific device type.
	 *
	 * @param string $device_type Device type (Desktop/Tablet/Mobile).
	 * @return array Responsive CSS array.
	 */
	public function submission_form_responsive_css( $device_type = 'Desktop' ) {
		$attr      = $this->attributes;
		$unique_id = $this->unique_id;

		$input_style           = $attr['inputStyle'] ?? 'style-one';
		$max_width             = $attr['maxWidth'] ?? array();
		$fields_gap            = $attr['fieldsGap'] ?? array();
		$field_padding         = $attr['fieldPadding'] ?? array();
		$submit_button_padding = $attr['submitButtonPadding'] ?? array();
		$submit_button_margin  = $attr['submitButtonMargin'] ?? array();
		$form_padding          = $attr['formPadding'] ?? array();
		$form_margin           = $attr['formMargin'] ?? array();
		$label_to_field_gap    = $attr['labelToFieldGap'] ?? array();
		$field_to_note_gap     = $attr['fieldGapToNoteGap'] ?? array();

		$responsive_css = array(
			// Outer wrap max width.
			array(
				'selector' => "{$unique_id}.sp-real-testimonial-submission-form",
				'styles'   => array(
					'max-width' => CssHelpers::single_responsive_css( $max_width, $device_type ),
				),
			),
			// Form padding / margin.
			array(
				'selector' => "$unique_id .sp-real-tsf-form",
				'styles'   => array(
					'padding' => CssHelpers::responsive_spacing_css( $form_padding, $device_type ),
					'margin'  => CssHelpers::responsive_spacing_css( $form_margin, $device_type ),
				),
			),
			// Fields container gap.
			array(
				'selector' => "$unique_id .sp-real-tsf-fields",
				'styles'   => array(
					'gap' => CssHelpers::single_responsive_css( $fields_gap, $device_type ),
				),
			),
			// Label.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__label",
				'styles'   => CssHelpers::generate_typo_responsive( $attr, $device_type, 'label' ),
			),
			array(
				'selector' => "$unique_id .sp-real-tsf-field-label-section",
				'styles'   => array(
					'style-two' === $input_style ? 'margin-right' : 'margin-bottom' => CssHelpers::single_responsive_css( $label_to_field_gap, $device_type ),
				),
			),
			// note text.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__note",
				'styles'   => array_merge(
					CssHelpers::generate_typo_responsive( $attr, $device_type, 'note' ),
					array( 'margin-top' => CssHelpers::single_responsive_css( $field_to_note_gap, $device_type ) )
				),
			),
			array(
				'selector' => "$unique_id .sp-real-tsf-field__note.sp-tsf-rating-note",
				'styles'   => array(
					'margin-top'    => 0,
					'margin-bottom' => CssHelpers::single_responsive_css( $field_to_note_gap, $device_type ),
				),
			),
			// Input typography + padding.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__input",
				'styles'   => array_merge(
					CssHelpers::generate_typo_responsive( $attr, $device_type, 'placeholder' ),
					array(
						'padding' => CssHelpers::responsive_spacing_css( $field_padding, $device_type ),
					)
				),
			),
			// Submit button typography + padding + margin.
			array(
				'selector' => "$unique_id .sp-real-tsf-submit",
				'styles'   => array_merge(
					CssHelpers::generate_typo_responsive( $attr, $device_type, 'submitButton' ),
					array(
						'padding' => CssHelpers::responsive_spacing_css( $submit_button_padding, $device_type ),
						'margin'  => CssHelpers::responsive_spacing_css( $submit_button_margin, $device_type ),
					)
				),
			),
		);

		return $responsive_css;
	}

	/**
	 * Generate desktop + tablet + mobile CSS for the submission form block.
	 *
	 * @return array CSS array with desktop_css, tablet_css, mobile_css keys.
	 */
	public function submission_form_block_css() {
		$attr      = $this->attributes;
		$unique_id = $this->unique_id;

		$label_typography       = $attr['labelTypography'] ?? array();
		$label_colors           = $attr['labelColors'] ?? array();
		$note_text_typography   = $attr['noteTypography'] ?? array();
		$note_text_colors       = $attr['noteColors'] ?? array();
		$required_color         = $attr['requiredColor'] ?? '';
		$placeholder_colors     = $attr['placeholderColors'] ?? array();
		$placeholder_typography = $attr['placeholderTypography'] ?? array();
		$field_bg_colors        = $attr['fieldBackgroundColors'] ?? array();
		$field_border           = $attr['fieldBorder'] ?? array();
		$field_border_width     = $attr['fieldBorderWidth'] ?? array();
		$field_border_radius    = $attr['fieldBorderRadius'] ?? array();

		$submit_typography    = $attr['submitButtonTypography'] ?? array();
		$submit_colors        = $attr['submitButtonColors'] ?? array();
		$submit_bg            = $attr['submitButtonBackground'] ?? array();
		$submit_border        = $attr['submitButtonBorder'] ?? array();
		$submit_border_width  = $attr['submitButtonBorderWidth'] ?? array();
		$submit_border_radius = $attr['submitButtonBorderRadius'] ?? array();

		$form_bg            = $attr['formBackground'] ?? array();
		$form_border        = $attr['formBorder'] ?? array();
		$form_border_width  = $attr['formBorderWidth'] ?? array();
		$form_border_radius = $attr['formBorderRadius'] ?? array();
		$form_box_shadow    = $attr['formBoxShadow'] ?? array();

		$success_color = $attr['successMessageColor'] ?? '';
		$error_color   = $attr['errorMessageColor'] ?? '';

		$desktop_css = array(
			// Form container.
			array(
				'selector' => "$unique_id .sp-real-tsf-form",
				'styles'   => array_merge(
					array(
						'background'    => CssHelpers::background_control( $form_bg ),
						'border-radius' => CssHelpers::spacing_css( $form_border_radius ),
					),
					CssHelpers::box_shadow_css( $form_box_shadow ),
					CssHelpers::get_border_styles( $form_border, $form_border_width )
				),
			),
			// Label color + typography.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__label",
				'styles'   => array_merge(
					array( 'color' => $label_colors['normal'] ?? '' ),
					CssHelpers::generate_typography_css( $label_typography )
				),
			),
			// Note Text color + typography.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__note",
				'styles'   => array_merge(
					array( 'color' => $note_text_colors['normal'] ?? '' ),
					CssHelpers::generate_typography_css( $note_text_typography )
				),
			),
			// Input base styles.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__input",
				'styles'   => array_merge(
					array(
						'color'            => $placeholder_colors['normal'] ?? '',
						'background-color' => $field_bg_colors['normal'] ?? '',
						'border-radius'    => CssHelpers::spacing_css( $field_border_radius ),
					),
					CssHelpers::generate_typography_css( $placeholder_typography ),
					CssHelpers::get_border_styles( $field_border, $field_border_width )
				),
			),
			// Placeholder color.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__input::placeholder",
				'styles'   => array(
					'color' => $placeholder_colors['normal'] ?? '',
				),
			),
			// Focus state.
			array(
				'selector' => "$unique_id .sp-real-tsf-field__input:focus",
				'styles'   => array(
					'color'            => $placeholder_colors['hover'] ?? '',
					'background-color' => $field_bg_colors['hover'] ?? '',
					'border-color'     => $field_border['hoverColor'] ?? '',
				),
			),
			// Required asterisk color.
			array(
				'selector' => "$unique_id .sp-real-tsf-required",
				'styles'   => array(
					'color' => $required_color,
				),
			),
			// Submit button base.
			array(
				'selector' => "$unique_id .sp-real-tsf-submit",
				'styles'   => array_merge(
					array(
						'color'         => $submit_colors['normal'] ?? '',
						'background'    => CssHelpers::background_control( $submit_bg['normal'] ?? array() ),
						'border-radius' => CssHelpers::spacing_css( $submit_border_radius ),
					),
					CssHelpers::generate_typography_css( $submit_typography ),
					CssHelpers::get_border_styles( $submit_border, $submit_border_width )
				),
			),
			// Submit button hover.
			array(
				'selector' => "$unique_id .sp-real-tsf-submit:hover",
				'styles'   => array(
					'color'        => $submit_colors['hover'] ?? '',
					'border-color' => $submit_border['hoverColor'] ?? '',
					'background'   => CssHelpers::background_control( $submit_bg['hover'] ?? array() ),
				),
			),
			// Message colors.
			array(
				'selector' => "$unique_id .sp-real-tsf-message__success",
				'styles'   => array(
					'color' => $success_color,
				),
			),
			array(
				'selector' => "$unique_id .sp-real-tsf-message__error",
				'styles'   => array(
					'color' => $error_color,
				),
			),
		);

		// Visibility + responsive merge.
		$visibility_css = CssHelpers::get_visibility_css( $attr );
		$desktop_css    = array_merge(
			$desktop_css,
			$visibility_css['Desktop'],
			$this->submission_form_responsive_css( 'Desktop' )
		);
		$tablet_css     = array_merge(
			$visibility_css['Tablet'],
			$this->submission_form_responsive_css( 'Tablet' )
		);
		$mobile_css     = array_merge(
			$visibility_css['Mobile'],
			$this->submission_form_responsive_css( 'Mobile' )
		);

		return array(
			'desktop_css' => $desktop_css,
			'tablet_css'  => $tablet_css,
			'mobile_css'  => $mobile_css,
		);
	}
}
