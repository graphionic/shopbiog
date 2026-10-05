<?php
/**
 * Real Testimonials block attributes File.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Includes
 */

use ShapedPlugin\TestimonialFree\Blocks\Includes\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require SP_TFREE_PATH . 'Blocks/Includes/attributes.php';

/**
 * Carousel block specific attributes.
 *
 * @var array
 */
$sp_real_carousel_attr = array(
	'blockName' => Utils::string( 'carousel' ),
	'padding'   => Utils::responsive_spacing( 10, 12, 10, 12, 'px' ),
	'width'     => Utils::single_responsive(),
);

// Merge all attributes for carousel block.
$sp_real_carousel_attributes = array_merge(
	$sp_real_required_attributes,
	$sp_real_nav_arrow_pagination_dots_attr,
	$sp_real_layouts_attributes,
	$sp_real_query_attributes,
	$sp_real_card_design_attributes,
	$sp_real_content_attributes,
	$sp_real_rating_attributes,
	$sp_real_reviewer_image_attributes,
	$sp_real_reviewer_details_attributes,
	$sp_real_advanced_attributes,
	$sp_real_carousel_attr
);

/**
 * Slider block specific attributes.
 *
 * Extended version of the carousel — shows testimonials one-by-one. Shares the
 * full carousel schema; only blockName and the single-column default differ.
 *
 * @var array
 */
$sp_real_slider_attr = array(
	'blockName'  => Utils::string( 'slider' ),
	'padding'    => Utils::responsive_spacing( 10, 12, 10, 12, 'px' ),
	'width'      => Utils::single_responsive(),
	'columns'    => Utils::single_responsive( 1, 1, 1, '' ),
	'cardBorder' => Utils::border( 'none', '#DDDDDD', '#DDDDDD' ),
);

// Merge all attributes for slider block.
$sp_real_slider_attributes = array_merge(
	$sp_real_required_attributes,
	$sp_real_nav_arrow_pagination_dots_attr,
	$sp_real_layouts_attributes,
	$sp_real_query_attributes,
	$sp_real_card_design_attributes,
	$sp_real_content_attributes,
	$sp_real_rating_attributes,
	$sp_real_reviewer_image_attributes,
	$sp_real_reviewer_details_attributes,
	$sp_real_advanced_attributes,
	$sp_real_slider_attr
);

/**
 * Grid block specific attributes.
 *
 * @var array
 */
$sp_real_grid_attr = array(
	'blockName'             => Utils::string( 'grid' ),
	'padding'               => Utils::responsive_spacing( 10, 12, 10, 12, 'px' ),
	'width'                 => Utils::single_responsive(),
	'enableAjaxPagination'  => Utils::boolean( true ),
	'enableNavigationArrow' => Utils::boolean(),
	'enablePaginationDots'  => Utils::boolean(),
);

// Merge all attributes for grid block.
$sp_real_grid_attributes = array_merge(
	$sp_real_required_attributes,
	$sp_real_layouts_attributes,
	$sp_real_query_attributes,
	$sp_real_card_design_attributes,
	$sp_real_content_attributes,
	$sp_real_rating_attributes,
	$sp_real_reviewer_image_attributes,
	$sp_real_reviewer_details_attributes,
	$sp_real_advanced_attributes,
	$sp_real_grid_attr
);

// AJAX PAGINATION BLOCK ATTRIBUTES.
$sp_real_ajax_pagination_override_attr = array(
	'blockName'               => Utils::string( 'ajax-pagination' ),
	'itemPerPage'             => array(
		'type'    => 'number',
		'default' => 4,
	),
	'paginationType'          => Utils::string( 'normal' ),
	'paginationButtonType'    => Utils::string( 'load-more' ),
	'loadMoreLabel'           => Utils::string( 'Load More' ),
	'endingMessage'           => Utils::string( 'No more testimonial' ),
	'paginationPrevLabel'     => Utils::string( 'Prev' ),
	'paginationNextLabel'     => Utils::string( 'Next' ),
	'paginationNumberType'    => Utils::string( 'number' ),
	'paginationShorten'       => Utils::boolean(),
	'paginationNumberGap'     => Utils::range_control( 4 ),
	'paginationAlignment'     => Utils::string( 'center' ),
	'paginationTypography'    => Utils::typography(),
	'paginationFontSize'      => Utils::single_responsive( 14 ),
	'paginationLineHeight'    => Utils::single_responsive( 1.3 ),
	'paginationLetterSpacing' => Utils::single_responsive( 0 ),
	'paginationColor'         => Utils::colors( '', '#fff' ),
	'paginationBgColor'       => Utils::colors( '', 'var(--sp-real-primary-color)' ),
	'paginationBorder'        => Utils::border( 'solid', '#3D3D3D', 'var(--sp-real-primary-color)' ),
	'paginationBorderWidth'   => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'paginationBorderRadius'  => Utils::spacing( 4, 4, 4, 4 ),
	'paginationPadding'       => Utils::responsive_spacing( 10, 20, 10, 20, 'px' ),
	'paginationMargin'        => Utils::responsive_spacing( 32, 0, 0, 0, 'px' ),
);

$sp_real_ajax_pagination_block_attributes = array_merge(
	$sp_real_required_attributes,
	$sp_real_ajax_pagination_override_attr
);

/**
 * Testimonial Submission Form block attributes.
 *
 * @var array
 */
$sp_real_submission_form_overrides = array(
	// Form Templates.
	'blockName'                    => Utils::string( 'testimonial-submission-form' ),
	'formDisplayType'              => Utils::string( 'inline' ),
	'inputStyle'                   => Utils::string( 'style-one' ),
	// styles.
	'maxWidth'                     => Utils::single_responsive( 640, 640, 400, 'px' ),
	'formBackground'               => array(
		'type'    => 'object',
		'default' => array(
			'style'    => 'solid',
			'solid'    => '',
			'gradient' => 'var(--eab-gradient-color)',
		),
	),
	'formBorder'                   => Utils::border( 'none', '#CCCCCC' ),
	'formBorderWidth'              => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'formBorderRadius'             => Utils::spacing( 12, 12, 12, 12 ),
	'formBoxShadow'                => Utils::box_shadow( true, 0, 12, 17, 0, '#0000001A' ),
	'formPadding'                  => Utils::responsive_spacing( 32, 32, 32, 32 ),
	'formMargin'                   => Utils::responsive_spacing( 0, 0, 0, 0 ),
	// Form Builder.
	'formFields'                   => array(
		'type'    => 'array',
		'default' => array(
			array(
				'id'          => 1,
				'required'    => true,
				'showField'   => true,
				'fieldName'   => 'tpro_client_name',
				'label'       => 'Full Name',
				'placeholder' => '',
				'helpText'    => 'What is your full name?',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
			),
			array(
				'id'          => 2,
				'required'    => true,
				'showField'   => true,
				'fieldName'   => 'tpro_client_email',
				'label'       => 'E-mail Address',
				'placeholder' => '',
				'helpText'    => 'What is your e-mail address?',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
			),
			array(
				'id'          => 3,
				'required'    => false,
				'showField'   => true,
				'fieldName'   => 'tpro_client_designation',
				'label'       => 'Designation',
				'placeholder' => '',
				'helpText'    => 'What is your designation?',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
			),
			array(
				'id'          => 4,
				'required'    => true,
				'showField'   => true,
				'fieldName'   => 'tpro_client_rating',
				'label'       => 'Star Rating',
				'placeholder' => '',
				'helpText'    => 'Select a star rating from 1 to 5 stars.',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
			),
			array(
				'id'          => 5,
				'required'    => false,
				'showField'   => true,
				'fieldName'   => 'tpro_testimonial_title',
				'label'       => 'Testimonial Title',
				'placeholder' => '',
				'helpText'    => 'A headline or tagline for your testimonial.',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
				'length'      => 'unlimited',
				'limit'       => array(
					'value' => 10,
					'unit'  => 'words',
				),
			),
			array(
				'id'          => 6,
				'required'    => false,
				'showField'   => true,
				'fieldName'   => 'tpro_client_testimonial',
				'label'       => 'Testimonial Text',
				'placeholder' => '',
				'helpText'    => 'What do you think about us?',
				'fieldWidth'  => array(
					'value' => 100,
					'unit'  => '%',
				),
				'length'      => 'unlimited',
				'limit'       => array(
					'value' => 60,
					'unit'  => 'words',
				),
			),
		),
	),
	'showTermsAndCondition'        => Utils::boolean(),
	'termsAndConditionLabel'       => Utils::string( 'By submitting, you agree to our ' ),
	'termsAndConditionAnchorLabel' => Utils::string( 'Terms and Conditions.' ),
	'termsAndConditionLink'        => Utils::string( '#' ),
	'labelToFieldGap'              => Utils::single_responsive( 12, 12, 8, 'px' ),
	'fieldGapToNoteGap'            => Utils::single_responsive( 8, 8, 6, 'px' ),
	'fieldsGap'                    => Utils::single_responsive( 24, 18, 12, 'px' ),
	'textAlignment'                => Utils::string( 'left' ),
	// label.
	'labelTypography'              => Utils::typography(),
	'labelFontSize'                => Utils::single_responsive( 16 ),
	'labelLineHeight'              => Utils::single_responsive( 1.2 ),
	'labelLetterSpacing'           => Utils::single_responsive( 0 ),
	'labelColors'                  => Utils::colors(),
	// note.
	'noteTypography'               => Utils::typography( '400', 'italic' ),
	'noteFontSize'                 => Utils::single_responsive( 14 ),
	'noteLineHeight'               => Utils::single_responsive( 1.2 ),
	'noteLetterSpacing'            => Utils::single_responsive( 0 ),
	'noteColors'                   => Utils::colors(),
	// placeholder.
	'placeholderTypography'        => Utils::typography(),
	'placeholderFontSize'          => Utils::single_responsive( 14 ),
	'placeholderLineHeight'        => Utils::single_responsive( 1.2 ),
	'placeholderLetterSpacing'     => Utils::single_responsive( 0 ),
	'requiredColor'                => Utils::string(),
	'fieldBackgroundColors'        => Utils::colors(),
	'placeholderColors'            => Utils::colors(),
	'fieldBorder'                  => Utils::border( 'solid', '#E2E2E2', 'var(--sp-real-primary-color)' ),
	'fieldBorderWidth'             => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'fieldBorderRadius'            => Utils::spacing( 4, 4, 4, 4 ),
	'fieldPadding'                 => Utils::responsive_spacing( 15, 18, 15, 18, 'px' ),
	// Submit Button.
	'submitButtonLabel'            => Utils::string( 'Submit Testimonial' ),
	'submitButtonWidth'            => Utils::string( 'fullWidth' ),
	'submitButtonAlignment'        => Utils::string( 'left' ),
	'showSubmitButtonIcon'         => Utils::boolean(),
	'submitButtonIcon'             => array(
		'type'    => 'object',
		'default' => array(
			'source' => 'icon',
			'icon'   => array(
				'iconName' => '',
				'viewBox'  => '',
				'path'     => '',
			),
			'image'  => array(),
		),
	),
	'submitButtonIconPosition'     => Utils::string( 'right' ),
	'submitButtonIconSize'         => Utils::range_control( 16, 'px' ),
	'submitButtonTypography'       => Utils::typography( '500' ),
	'submitButtonFontSize'         => Utils::single_responsive( 16 ),
	'submitButtonLineHeight'       => Utils::single_responsive( 1.3 ),
	'submitButtonLetterSpacing'    => Utils::single_responsive( 0 ),
	'submitButtonColors'           => Utils::colors(),
	'submitButtonBackground'       => Utils::bg(),
	'submitButtonBorder'           => Utils::border( 'none', '', '' ),
	'submitButtonBorderWidth'      => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'submitButtonBorderRadius'     => Utils::spacing( 4, 4, 4, 4 ),
	'submitButtonPadding'          => Utils::responsive_spacing( 15, 18, 15, 18, 'px' ),
	'submitButtonMargin'           => Utils::responsive_spacing( 32, 0, 0, 0, 'px' ),
	// Status & Message Settings.
	'testimonialStatus'            => Utils::string( 'pending' ),
	'showRequiredNotice'           => Utils::boolean( true ),
	'requiredNoticeLabel'          => Utils::string( 'Red asterisk fields are required.' ),
	'ajaxFormSubmission'           => Utils::boolean( true ),
	'successMessage'               => Utils::string( 'Thank you! Your testimonial is currently waiting to be approved.' ),
	'errorMessage'                 => Utils::string( 'We encountered an issue while processing your testimonial.' ),
	'submissionMessagePosition'    => Utils::string( 'top' ),
	'successMessageColor'          => Utils::string(),
	'errorMessageColor'            => Utils::string(),
);

$sp_real_submission_form_attributes = array_merge(
	$sp_real_required_attributes,
	$sp_real_advanced_attributes,
	$sp_real_submission_form_overrides
);

return array(
	'carousel'                    => $sp_real_carousel_attributes,
	'slider'                      => $sp_real_slider_attributes,
	'grid'                        => $sp_real_grid_attributes,
	'ajax-pagination'             => $sp_real_ajax_pagination_block_attributes,
	'testimonial-submission-form' => $sp_real_submission_form_attributes,
);
