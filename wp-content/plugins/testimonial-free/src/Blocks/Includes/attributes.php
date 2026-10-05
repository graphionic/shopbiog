<?php
/**
 * Real Testimonials attributes File.
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

$sp_real_required_attributes = array(
	'uniqueId'        => Utils::string(),
	'parentId'        => Utils::string(),
	'template'        => Utils::string( 'template-one' ),
	'align'           => Utils::string( 'wide' ),
	'isPreview'       => Utils::boolean(),
	'fontLists'       => Utils::string(),
	// visibility attr.
	'hideOnDesktop'   => Utils::boolean(),
	'hideOnTablet'    => Utils::boolean(),
	'hideOnMobile'    => Utils::boolean(),
	'customClassName' => Utils::string(),
	'customIdName'    => Utils::string(),
	'preloader'       => Utils::boolean(),
);

/**
 * Layouts panel attributes.
 *
 * @var array
 */
$sp_real_layouts_attributes = array(
	'carouselStyle'               => Utils::string( 'default' ),
	'itemsAlignment'              => Utils::string( 'top' ),
	'enableLiveFrontendFilter'    => Utils::boolean(),
	'enableAjaxTestimonialSearch' => Utils::boolean(),
	'enableSEOSchemaMarkup'       => Utils::boolean(),
	// slider tab settings.
	'slidingEffect'               => Utils::string( 'slide' ),
	'columns'                     => Utils::single_responsive( 3, 2, 1, '' ),
	'columnGap'                   => Utils::single_responsive( 24, 16, 12, 'px' ),
	'rowGap'                      => Utils::single_responsive( 24, 16, 12, 'px' ),
	'sliderAutoPlay'              => Utils::boolean(),
	'carouselSpeed'               => Utils::range_control( 900, 'ms' ),
	'carouselAutoplayDelay'       => Utils::range_control( 900, 'ms' ),
	'slideToScroll'               => Utils::single_responsive( 1, 1, 1, '' ),
	'enableNavigationArrow'       => Utils::boolean( true ),
	'enablePaginationDots'        => Utils::boolean(),
	'carouselDirection'           => Utils::string( 'ltr' ),
	'pauseOnHover'                => Utils::boolean( true ),
	'infiniteLoop'                => Utils::boolean( true ),
	'tabAndKeyNavigation'         => Utils::boolean( true ),
	'mouseWheelControl'           => Utils::boolean(),
	'freeScrollMode'              => Utils::boolean(),
);

/**
 * Query Builder panel attributes.
 *
 * @var array
 */
$sp_real_query_attributes = array(
	'filterBy'      => Utils::string( 'latest' ),
	'excludesItems' => array(
		'type'    => 'array',
		'default' => array(),
	),
	'limit'         => Utils::string( '6' ),
	'orderBy'       => Utils::string( 'date' ),
	'order'         => Utils::string( 'DESC' ),
);

/**
 * Card Design panel attributes.
 *
 * @var array
 */
$sp_real_card_design_attributes = array(
	// General Tab Settings.
	'cardDesign'          => Utils::string( '' ),
	'cardContents'        => array(
		'type'    => 'array',
		'default' => array(
			array(
				'id'        => 1,
				'name'      => 'reviewer_image',
				'is_active' => true,
			),
			array(
				'id'        => 2,
				'name'      => 'testimonial_title',
				'is_active' => true,
			),
			array(
				'id'        => 3,
				'name'      => 'testimonial_text',
				'is_active' => true,
			),
			array(
				'id'        => 4,
				'name'      => 'rating',
				'is_active' => true,
			),
			array(
				'id'        => 5,
				'name'      => 'reviewer_name',
				'is_active' => true,
			),
			array(
				'id'        => 6,
				'name'      => 'designation',
				'is_active' => true,
			),
		),
	),
	'cardAlignment'       => Utils::string( 'center' ),
	'cardHoverEffect'     => Utils::string( 'none' ),
	'cardHoverTransition' => Utils::range_control( 600, 'ms' ),
	// Style Tab Settings.
	'cardBackground'      => Utils::bg( array( 'normal', 'hover' ) ),
	'cardBorder'          => Utils::border( 'solid', '#DDDDDD', '#DDDDDD' ),
	'cardBorderWidth'     => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'cardBorderRadius'    => Utils::spacing( 16, 16, 16, 16 ),
	'cardBoxShadow'       => Utils::box_shadow( false, 0, 4, 6, 0, '#0000001A' ),
	'cardBoxShadowHover'  => Utils::box_shadow( false, 0, 4, 6, 0, '#0000001A' ),
	'cardPadding'         => Utils::responsive_spacing( 32, 32, 32, 32 ),
);

/**
 * Testimonial Content panel attributes.
 *
 * @var array
 */
$sp_real_content_attributes = array(
	// General Tab Settings.
	'titleTag'             => Utils::string( 'h3' ),
	'titleLength'          => Utils::string( 'full' ),
	'excerptLength'        => Utils::string( 'full' ),
	'stripAllHTMLTags'     => Utils::boolean( true ),
	// Style Tab Settings.
	'titleTypography'      => Utils::typography( '500' ),
	'titleFontSize'        => Utils::single_responsive( 18, 18, 16, 'px' ),
	'titleLineHeight'      => Utils::single_responsive( 1.4 ),
	'titleLetterSpacing'   => Utils::single_responsive( 0 ),
	'titleColors'          => Utils::colors(),
	'titleMargin'          => Utils::responsive_spacing( 8, 0, 8, 0, 'px' ),
	'excerptTypography'    => Utils::typography( '400' ),
	'excerptFontSize'      => Utils::single_responsive( 16, 15, 14, 'px' ),
	'excerptLineHeight'    => Utils::single_responsive( 1.6 ),
	'excerptLetterSpacing' => Utils::single_responsive( 0 ),
	'excerptColors'        => Utils::colors(),
	'excerptMargin'        => Utils::responsive_spacing( 8, 0, 8, 0, 'px' ),
);

/**
 * Star Rating panel attributes.
 *
 * @var array
 */
$sp_real_rating_attributes = array(
	// General Tab Settings.
	// `active`/`inactive` are rating icon keys resolved by
	// BlocksHelper::get_rating_svg_icon(). The style picker sets both keys together.
	'ratingIconSet'        => array(
		'type'    => 'object',
		'default' => array(
			'active'   => 'star-fill',
			'inactive' => 'star-stroke',
		),
	),
	'ratingIconSize'       => Utils::single_responsive( 16, 14, 12, 'px' ),
	'ratingIconGap'        => Utils::single_responsive( 2, 2, 2, 'px' ),
	// Style Tab Settings.
	'ratingIconColor'      => Utils::string( '#FFC107' ),
	'ratingIconEmptyColor' => Utils::string( '#E0E0E0' ),
	'ratingIconMargin'     => Utils::responsive_spacing( 12, 0, 12, 0, 'px' ),
);

/**
 * Reviewer Image panel attributes.
 *
 * @var array
 */
$sp_real_reviewer_image_attributes = array(
	// General Tab Settings.
	'imageResolution'        => Utils::string( 'original' ),
	'aspectRatio'            => Utils::string( '1:1' ),
	'aspectRatioCustom'      => Utils::string( '' ),
	'imageWidth'             => Utils::single_responsive( 70, 70, 70, 'px' ),
	'imageHeight'            => Utils::single_responsive( 70, 70, 70, 'px' ),
	'load2xInRetinaDisplay'  => Utils::boolean(),
	// Reviewer image fallback. Free supports 'none' only (renders nothing);
	// other modes are Pro upsell options.
	'reviewerFallbackImages' => Utils::string( 'none' ),
	// Style Tab Settings.
	'imageBorder'            => Utils::border( 'none' ),
	'imageBorderRadius'      => Utils::spacing( 50, 50, 50, 50, '%' ),
	'imageBorderWidth'       => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'imageBoxShadow'         => Utils::box_shadow( false, 0, 4, 6, 0, '#0000001A' ),
	'imageMargin'            => Utils::responsive_spacing( 0, 0, 12, 0, 'px' ),
);

/**
 * Reviewer Details panel attributes.
 *
 * @var array
 */
$sp_real_reviewer_details_attributes = array(
	'nameHtmlTag'              => Utils::string( 'h6' ),
	// name.
	'nameTypography'           => Utils::typography( '500', 'normal', 'none' ),
	'nameFontSize'             => Utils::single_responsive( 16, 16, 14, 'px' ),
	'nameLineHeight'           => Utils::single_responsive( 1.4 ),
	'nameLetterSpacing'        => Utils::single_responsive( 0 ),
	'nameColors'               => Utils::colors(),
	'nameMargin'               => Utils::responsive_spacing( 2, 0, 2, 0, 'px' ),
	// designation.
	'designationTypography'    => Utils::typography( '400' ),
	'designationFontSize'      => Utils::single_responsive( 14, 13, 12, 'px' ),
	'designationLineHeight'    => Utils::single_responsive( 1.4 ),
	'designationLetterSpacing' => Utils::single_responsive( 0 ),
	'designationColors'        => Utils::colors(),
	'designationMargin'        => Utils::responsive_spacing( 2, 0, 2, 0, 'px' ),
);

/**
 * Swiper Navigation and Pagination dots attr.
 *
 * @var array
 */
$sp_real_nav_arrow_pagination_dots_attr = array(
	'navIconVisibleOnHover'      => Utils::boolean(),
	'navIconName'                => Utils::string( 'chevron-outline' ),
	'navIconPosition'            => Utils::string( 'vertical_center' ),
	'navIconSize'                => Utils::single_responsive( 16 ),
	'navIconGap'                 => Utils::single_responsive( 10 ),
	'navOffsetX'                 => Utils::single_responsive( -22, -22, -22, 'px' ),
	'navOffsetY'                 => Utils::single_responsive( 50, 50, 50, '%' ),
	'navIconColors'              => Utils::colors( '', '#fff' ),
	'navIconBackground'          => Utils::colors( '', '#1E67D8' ),
	'navIconBorder'              => Utils::border( 'none', '#DDDDDD', '' ),
	'navIconBorderWidth'         => Utils::spacing( 1, 1, 1, 1, 'px' ),
	'navIconBoxShadow'           => Utils::box_shadow( true, 0, 1, 4, 0, '#0D0D0E33' ),
	'navIconBoxShadowHover'      => Utils::box_shadow( false, 0, 1, 4, 0, '#0000001A' ),
	'navIconBorderRadius'        => Utils::spacing( 50, 50, 50, 50, '%' ),
	'navIconPadding'             => Utils::responsive_spacing( 12, 12, 12, 12 ),
	// pagination dots.
	'paginationStyle'            => Utils::string( 'dots' ),
	'paginationDotsWidth'        => Utils::single_responsive( 12 ),
	'paginationDotsHeight'       => Utils::single_responsive( 12 ),
	'paginationDotsSpaceBetween' => Utils::single_responsive( 8 ),
	'paginationDotsColors'       => Utils::colors(),
	'paginationDotsMargin'       => Utils::responsive_spacing( 24, 0, 0, 0, 'px' ),
	'paginationDotsPosition'     => Utils::string( 'center' ),
);

/**
 * Advanced Settings panel attributes.
 *
 * @var array
 */
$sp_real_advanced_attributes = array(
	'customCSS'      => Utils::string(),
	'customJS'       => Utils::string(),
	'realBackground' => Utils::bg(),
	'realPadding'    => Utils::responsive_spacing( 0, 0, 0, 0, 'px' ),
	'realMargin'     => Utils::responsive_spacing( 0, 0, 0, 0, 'px' ),
);
