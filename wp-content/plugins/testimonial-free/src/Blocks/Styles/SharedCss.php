<?php
/**
 * Real Testimonials SharedCss File.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Styles
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Styles;

use ShapedPlugin\TestimonialFree\Blocks\Styles\CssHelpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * SharedCss - Dynamic CSS generation for testimonial blocks.
 */
class SharedCss {
	/**
	 * Attributes.
	 *
	 * @var array
	 */
	public $attributes = array();

	/**
	 * Unique ID.
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
	 * Generate responsive CSS for specific device type.
	 *
	 * @param string $device_type Device type (Desktop/Tablet/Mobile).
	 * @return array Responsive CSS array.
	 */
	public function shared_responsive_css( $device_type = 'Desktop' ) {
		$attr      = $this->attributes;
		$unique_id = $this->unique_id;
		// Layout attributes.
		$block_name        = $attr['blockName'] ?? 'carousel';
		$card_design       = $attr['cardDesign'] ?? 'design-one';
		$card_contents     = $attr['cardContents'] ?? array();
		$gap_between_items = $attr['columnGap'] ?? array();
		$card_padding      = $attr['cardPadding'] ?? array();
		$image_width       = $attr['imageWidth'] ?? array();
		$image_height      = $attr['imageHeight'] ?? array();
		$image_margin      = $attr['imageMargin'] ?? array();
		$aspect_ratio_attr = $attr['aspectRatio'] ?? 'original';
		// "custom" pulls the free-text value; accepts "16:9" or "16/9".
		$aspect_ratio     = 'custom' === $aspect_ratio_attr ? ( $attr['aspectRatioCustom'] ?? '' ) : $aspect_ratio_attr;
		$has_aspect_ratio = '' !== $aspect_ratio && 'original' !== $aspect_ratio;
		// Content attributes.
		$title_margin   = $attr['titleMargin'] ?? array();
		$excerpt_margin = $attr['excerptMargin'] ?? array();
		// Reviewer details.
		$name_margin        = $attr['nameMargin'] ?? array();
		$designation_margin = $attr['designationMargin'] ?? array();
		$rating_icon_size   = $attr['ratingIconSize'] ?? array();
		$rating_icon_gap    = $attr['ratingIconGap'] ?? array();
		$rating_icon_margin = $attr['ratingIconMargin'] ?? array();
		// Navigation arrows and Pagination dots responsive.
		$enable_navigation_arrow = $attr['enableNavigationArrow'] ?? false;
		$enable_pagination_dots  = $attr['enablePaginationDots'] ?? false;
		$pagination_style        = $attr['paginationStyle'] ?? 'dots';
		$sp_real_padding         = $attr['realPadding'] ?? array();
		$sp_real_margin          = $attr['realMargin'] ?? array();

		$responsive_css = array();
		// Gap between items (columns gap) — flex gap for the swiper case.
		if ( 'carousel' === $block_name ) {
			$responsive_css[]      = array(
				'selector' => "$unique_id .sp-swiper-wrapper",
				'styles'   => array(
					'gap' => CssHelpers::single_responsive_css( $gap_between_items, $device_type ),
				),
			);
			$carousel_columns_attr = $attr['columns'] ?? array();
			$cols_value            = (int) ( $carousel_columns_attr['device'][ $device_type ] ?? 1 );
			$cols_value            = max( 1, $cols_value );
			$responsive_css[]      = array(
				'selector' => "$unique_id .sp-testimonial-swiper:is(.swiper-fade, .swiper-cube, .swiper-flip) .swiper-slide",
				'styles'   => array(
					'display'               => 'grid',
					'grid-template-columns' => 'repeat(' . $cols_value . ', minmax(0, 1fr))',
					'gap'                   => CssHelpers::single_responsive_css( $gap_between_items, $device_type ),
				),
			);
		}

		// Grid layout: columns + column/row gap as native CSS grid.
		if ( 'grid' === $block_name ) {
			$grid_columns_attr = $attr['columns'] ?? array();
			$vertical_gap_attr = $attr['rowGap'] ?? array();
			$cols_value        = (int) ( $grid_columns_attr['device'][ $device_type ] ?? 3 );
			$cols_value        = max( 1, $cols_value );
			$responsive_css[]  = array(
				'selector' => "$unique_id .sp-real-grid-wrapper",
				'styles'   => array(
					'display'               => 'grid',
					'grid-template-columns' => 'repeat(' . $cols_value . ', minmax(0, 1fr))',
					'column-gap'            => CssHelpers::single_responsive_css( $gap_between_items, $device_type ),
					'row-gap'               => CssHelpers::single_responsive_css( $vertical_gap_attr, $device_type ),
				),
			);
		}

		$responsive_css = array_merge(
			$responsive_css,
			array(
				// main wrapper css.
				array(
					'selector' => "$unique_id .sp-real-template-wrapper",
					'styles'   => array(
						'padding' => CssHelpers::responsive_spacing_css( $sp_real_padding, $device_type ),
						'margin'  => CssHelpers::responsive_spacing_css( $sp_real_margin, $device_type ),
					),
				),
				// Card padding.
				array(
					'selector' => "$unique_id .sp-real-card-inner",
					'styles'   => array(
						'padding' => CssHelpers::responsive_spacing_css( $card_padding, $device_type ),
					),
				),
			)
		);

		// Title Css.
		if ( $this->is_active_card_content( $card_contents, 'testimonial_title' ) ) {
			$responsive_css = array_merge(
				$responsive_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-client-title",
						'styles'   => CssHelpers::generate_typo_responsive( $attr, $device_type, 'title' ),
					),
					array(
						'selector' => "$unique_id .sp-real-testimonial-client-title",
						'styles'   => array(
							'margin' => CssHelpers::responsive_spacing_css( $title_margin, $device_type ),
						),
					),
				)
			);
		}

		// Excerpt.
		if ( $this->is_active_card_content( $card_contents, 'testimonial_text' ) ) {
			$responsive_css = array_merge(
				$responsive_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-testimonial-text",
						'styles'   => CssHelpers::generate_typo_responsive( $attr, $device_type, 'excerpt' ),
					),
					array(
						'selector' => "$unique_id .sp-real-testimonial-content",
						'styles'   => array(
							'margin' => CssHelpers::responsive_spacing_css( $excerpt_margin, $device_type ),
						),
					),
				)
			);
		}

		// Rating margin.
		if ( $this->is_active_card_content( $card_contents, 'rating' ) ) {
			$responsive_css[] = array(
				'selector' => "$unique_id .sp-real-client-rating",
				'styles'   => array(
					'font-size' => CssHelpers::single_responsive_css( $rating_icon_size, $device_type ),
					'gap'       => CssHelpers::single_responsive_css( $rating_icon_gap, $device_type ),
					'margin'    => CssHelpers::responsive_spacing_css( $rating_icon_margin, $device_type ),
				),
			);
		}

		// Reviewer image css.
		if ( $this->is_active_card_content( $card_contents, 'reviewer_image' ) ) {
			$responsive_css = array_merge(
				$responsive_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-img-tag",
						'styles'   => array(
							'width'        => CssHelpers::single_responsive_css( $image_width, $device_type ),
							'height'       => $has_aspect_ratio
								? 'auto'
								: CssHelpers::single_responsive_css( $image_height, $device_type ),
							'aspect-ratio' => $has_aspect_ratio ? str_replace( ':', ' / ', $aspect_ratio ) : '',
							'object-fit'   => $has_aspect_ratio ? 'cover' : '',
						),
					),
					array(
						'selector' => "$unique_id .sp-real-client-image",
						'styles'   => array(
							'margin' => CssHelpers::responsive_spacing_css( $image_margin, $device_type ),
						),
					),
				)
			);
			if ( 'design-two' === $card_design ) {
				$image_half_height = ( ( (int) $image_height['device'][ $device_type ] ) / 2 ) . CssHelpers::get_unit( $image_height, $device_type );

				$responsive_css = array_merge(
					$responsive_css,
					array(
						array(
							'selector' => "$unique_id .sp-real-client-image",
							'styles'   => array(
								'top' => '-' . $image_half_height,
							),
						),
						array(
							'selector' => "$unique_id .sp-real-card-design-two",
							'styles'   => array(
								'margin-top' => $image_half_height,
							),
						),
						array(
							'selector' => "$unique_id .sp-real-card-design-two .sp-real-card-inner>div:nth-child(2)",
							'styles'   => array(
								'padding-top' => $image_half_height,
							),
						),
					)
				);
			}
		}

		// Name.
		if ( $this->is_active_card_content( $card_contents, 'reviewer_name' ) ) {
			$responsive_css = array_merge(
				$responsive_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-client-name",
						'styles'   => CssHelpers::generate_typo_responsive( $attr, $device_type, 'name' ),
					),
					array(
						'selector' => "$unique_id .sp-real-testimonial-client-name",
						'styles'   => array(
							'margin' => CssHelpers::responsive_spacing_css( $name_margin, $device_type ),
						),
					),
				)
			);
		}

		// Designation typography + margin.
		if ( $this->is_active_card_content( $card_contents, 'designation' ) ) {
			$responsive_css[] = array(
				'selector' => "$unique_id .sp-real-client-designation",
				'styles'   => array_merge(
					CssHelpers::generate_typo_responsive( $attr, $device_type, 'designation' ),
					array(
						'margin' => CssHelpers::responsive_spacing_css( $designation_margin, $device_type ),
					)
				),
			);
		}

		// Navigation Arrows Responsive Css.
		if ( $enable_navigation_arrow ) {
			$nav_icon_size     = $attr['navIconSize'] ?? array();
			$nav_icon_gap      = $attr['navIconGap'] ?? array();
			$nav_icon_padding  = $attr['navIconPadding'] ?? array();
			$nav_icon_position = $attr['navIconPosition'] ?? 'vertical_center';
			$nav_offset_x      = $attr['navOffsetX'] ?? array();
			$nav_offset_y      = $attr['navOffsetY'] ?? array();
			// css.
			$responsive_css = array_merge(
				$responsive_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-swiper-nav-arrows",
						'styles'   => array(
							'gap'   => CssHelpers::single_responsive_css( $nav_icon_gap, $device_type ),
							'right' => CssHelpers::single_responsive_css( $nav_offset_x, $device_type ),
							'left'  => CssHelpers::single_responsive_css( $nav_offset_x, $device_type ),
							in_array( $nav_icon_position, array( 'bottom_left', 'bottom_center', 'bottom_right' ), true ) ? 'bottom' : 'top'   => CssHelpers::single_responsive_css( $nav_offset_y, $device_type ),
						),
					),
					array(
						'selector' => "$unique_id .sp-real-swiper-navigation",
						'styles'   => array(
							'padding' => CssHelpers::responsive_spacing_css( $nav_icon_padding, $device_type ),
						),
					),
					array(
						'selector' => "$unique_id .sp-real-swiper-navigation svg",
						'styles'   => array(
							'width'  => CssHelpers::single_responsive_css( $nav_icon_size, $device_type ),
							'height' => CssHelpers::single_responsive_css( $nav_icon_size, $device_type ),
						),
					),
				)
			);
		}

		// Pagination responsive: margin/gap on container.
		if ( $enable_pagination_dots ) {
			$pagination_dots_width  = $attr['paginationDotsWidth'] ?? array();
			$pagination_dots_height = $attr['paginationDotsHeight'] ?? array();
			$pagination_dots_margin = $attr['paginationDotsMargin'] ?? array();
			$pagination_dots_gap    = $attr['paginationDotsSpaceBetween'] ?? array();

			$responsive_css = array_merge(
				$responsive_css,
				array(
					// gaps.
					array(
						'selector' => "$unique_id .sp-real-swiper-pagination",
						'styles'   => array(
							'margin' => CssHelpers::responsive_spacing_css( $pagination_dots_margin, $device_type ),
							'gap'    => CssHelpers::single_responsive_css( $pagination_dots_gap, $device_type ),
						),
					),
					// dot width.
					array(
						'selector' => "$unique_id .swiper-pagination-bullet",
						'styles'   => array(
							'width'  => CssHelpers::single_responsive_css( $pagination_dots_width, $device_type ) . ' !important',
							'height' => CssHelpers::single_responsive_css( $pagination_dots_height, $device_type ),
						),
					),
				)
			);
		}

		return $responsive_css;
	}

	/**
	 * Generate block shared CSS (desktop, tablet, mobile).
	 *
	 * @return array CSS array with desktop_css, tablet_css, mobile_css keys.
	 */
	public function block_shared_css() {
		$attr      = $this->attributes;
		$unique_id = $this->unique_id;

		// Card design attributes.
		$card_design        = $attr['cardDesign'] ?? 'design-one';
		$card_background    = $attr['cardBackground'] ?? array();
		$card_border        = $attr['cardBorder'] ?? array();
		$card_border_width  = $attr['cardBorderWidth'] ?? array();
		$card_border_radius = $attr['cardBorderRadius'] ?? array();
		$card_box_shadow    = $attr['cardBoxShadow'] ?? array();
		$card_hover_shadow  = $attr['cardBoxShadowHover'] ?? array();
		$card_hover_effect  = $attr['cardHoverEffect'] ?? 'none';
		$card_hover_trans   = $attr['cardHoverTransition'] ?? array();

		// Content attributes.
		$title_colors       = $attr['titleColors'] ?? array();
		$title_typography   = $attr['titleTypography'] ?? array();
		$excerpt_colors     = $attr['excerptColors'] ?? array();
		$excerpt_typography = $attr['excerptTypography'] ?? array();

		// Rating attributes.
		$rating_icon_color       = $attr['ratingIconColor'] ?? '';
		$rating_icon_empty_color = $attr['ratingIconEmptyColor'] ?? '';

		// Image attributes.
		$image_border        = $attr['imageBorder'] ?? array();
		$image_border_width  = $attr['imageBorderWidth'] ?? array();
		$image_border_radius = $attr['imageBorderRadius'] ?? array();
		$image_box_shadow    = $attr['imageBoxShadow'] ?? array();

		// Reviewer details.
		$name_colors            = $attr['nameColors'] ?? array();
		$name_typography        = $attr['nameTypography'] ?? array();
		$designation_colors     = $attr['designationColors'] ?? array();
		$designation_typography = $attr['designationTypography'] ?? array();
		$real_background        = $attr['realBackground'] ?? array();

		// Navigation arrows and Pagination dots attributes.
		$enable_navigation_arrow = $attr['enableNavigationArrow'] ?? false;
		$enable_pagination_dots  = $attr['enablePaginationDots'] ?? false;

		// Card contents for conditional CSS.
		$card_contents = $attr['cardContents'] ?? array();

		// Base card CSS (always present).
		$desktop_css = array(
			array(
				'selector' => "$unique_id .sp-real-template-wrapper",
				'styles'   => array(
					'background' => CssHelpers::background_control( $real_background['normal'] ),
				),
			),
			array(
				'selector' => "$unique_id .sp-real-card-inner",
				'styles'   => array_merge(
					array(
						'background'          => CssHelpers::background_control( $card_background['normal'] ),
						'border-radius'       => CssHelpers::spacing_css( $card_border_radius ),
						'transition-duration' => 'none' !== $card_hover_effect
							? CssHelpers::range_control_css( $card_hover_trans )
							: '',
					),
					CssHelpers::box_shadow_css( $card_box_shadow ),
					CssHelpers::get_border_styles( $card_border, $card_border_width )
				),
			),
			array(
				'selector' => "$unique_id .sp-real-card-inner:hover",
				'styles'   => array_merge(
					array(
						'border-color' => $card_border['hoverColor'] ?? '',
						'background'   => CssHelpers::background_control( $card_background['hover'] ),
					),
					CssHelpers::box_shadow_css( $card_hover_shadow ),
				),
			),
		);

		// Title.
		if ( $this->is_active_card_content( $card_contents, 'testimonial_title' ) ) {
			$desktop_css[] = array(
				'selector' => "$unique_id .sp-real-client-title",
				'styles'   => array_merge(
					CssHelpers::generate_typography_css( $title_typography ),
					array( 'color' => $title_colors['normal'] ?? '' )
				),
			);
		}

		// Excerpt.
		if ( $this->is_active_card_content( $card_contents, 'testimonial_text' ) ) {
			$desktop_css[] = array(
				'selector' => "$unique_id .sp-real-testimonial-text",
				'styles'   => array_merge(
					CssHelpers::generate_typography_css( $excerpt_typography ),
					array( 'color' => $excerpt_colors['normal'] ?? '' )
				),
			);
		}

		// Rating.
		if ( $this->is_active_card_content( $card_contents, 'rating' ) ) {
			$desktop_css = array_merge(
				$desktop_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-rating-full, $unique_id .sp-real-rating-half .sp-real-rating-fill",
						'styles'   => array(
							'color' => $rating_icon_color,
						),
					),
					array(
						'selector' => "$unique_id .sp-real-rating-empty, $unique_id .sp-real-rating-half .sp-real-rating-base",
						'styles'   => array(
							'color' => $rating_icon_empty_color,
						),
					),
				)
			);
		}

		// Image.
		if ( $this->is_active_card_content( $card_contents, 'reviewer_image' ) ) {
			$desktop_css = array_merge(
				$desktop_css,
				array(
					array(
						'selector' => "$unique_id .sp-real-img-tag",
						'styles'   => array_merge(
							CssHelpers::get_border_styles( $image_border, $image_border_width ),
							CssHelpers::box_shadow_css( $image_box_shadow ),
							array(
								'border-radius' => CssHelpers::spacing_css( $image_border_radius ),
							)
						),
					),
					array(
						'selector' => "$unique_id .sp-real-client-image:hover .sp-real-img-tag",
						'styles'   => array(
							'border-color' => $image_border['hoverColor'] ?? '',
						),
					),
				)
			);
		}

		// Name.
		if ( $this->is_active_card_content( $card_contents, 'reviewer_name' ) ) {
			$desktop_css[] = array(
				'selector' => "$unique_id .sp-real-client-name",
				'styles'   => array_merge(
					CssHelpers::generate_typography_css( $name_typography ),
					array( 'color' => $name_colors['normal'] ?? '' )
				),
			);
		}

		// Designation.
		if ( $this->is_active_card_content( $card_contents, 'designation' ) ) {
			$desktop_css[] = array(
				'selector' => "$unique_id .sp-real-client-designation",
				'styles'   => array_merge(
					CssHelpers::generate_typography_css( $designation_typography ),
					array( 'color' => $designation_colors['normal'] ?? '' )
				),
			);
		}

		// Navigation Arrows Css.
		if ( $enable_navigation_arrow ) {
			$nav_visible_on_hover    = $attr['navIconVisibleOnHover'] ?? false;
			$nav_icon_border_width   = $attr['navIconBorderWidth'] ?? array();
			$nav_icon_colors         = $attr['navIconColors'] ?? array();
			$nav_icon_background     = $attr['navIconBackground'] ?? array();
			$nav_icon_border         = $attr['navIconBorder'] ?? array();
			$nav_icon_border_radius  = $attr['navIconBorderRadius'] ?? array();
			$nav_icon_box_shadow     = $attr['navIconBoxShadow'] ?? array();
			$nav_icon_box_shadow_hvr = $attr['navIconBoxShadowHover'] ?? array();

			$desktop_css = array_merge(
				$desktop_css,
				array(
					// Nav buttons base styles.
					array(
						'selector' => "$unique_id .sp-real-swiper-navigation",
						'styles'   => array_merge(
							array(
								'color'         => $nav_icon_colors['normal'] ?? '',
								'background'    => $nav_icon_background['normal'] ?? '',
								'border-radius' => CssHelpers::spacing_css( $nav_icon_border_radius ),
							),
							CssHelpers::get_border_styles( $nav_icon_border, $nav_icon_border_width ),
							CssHelpers::box_shadow_css( $nav_icon_box_shadow ),
							$nav_visible_on_hover ? array(
								'opacity'    => '0',
								'transition' => 'opacity 0.3s ease',
							) : array()
						),
					),
					array(
						'selector' => "$unique_id .sp-real-swiper-navigation:hover",
						'styles'   => array_merge(
							array(
								'color'        => $nav_icon_colors['hover'] ?? '',
								'background'   => $nav_icon_background['hover'] ?? '',
								'border-color' => $nav_icon_border['hoverColor'] ?? '',
							),
							CssHelpers::box_shadow_css( $nav_icon_box_shadow_hvr )
						),
					),
				)
			);

			// Hover visibility styles.
			if ( $nav_visible_on_hover ) {
				$desktop_css[] = array(
					'selector' => "$unique_id:hover .sp-real-swiper-navigation",
					'styles'   => array(
						'opacity' => '1',
					),
				);
			}
		}

		// Pagination: attribute-driven colors for dots/stepper.
		if ( $enable_pagination_dots ) {
			$pagination_dots_colors = $attr['paginationDotsColors'] ?? array();

			$desktop_css = array_merge(
				$desktop_css,
				array(
					array(
						'selector' => "$unique_id .swiper-pagination-bullet",
						'styles'   => array(
							'background' => $pagination_dots_colors['normal'] ?? '',
						),
					),
					array(
						'selector' => "$unique_id :is(.swiper-pagination-bullet:hover, .swiper-pagination-bullet-active)",
						'styles'   => array(
							'background' => $pagination_dots_colors['active'] ?? '',
						),
					),
				)
			);
		}

		// Visibility CSS.
		$visibility_css = CssHelpers::get_visibility_css( $attr );

		// Merge with responsive and visibility CSS.
		$desktop_css = array_merge(
			$desktop_css,
			$visibility_css['Desktop'],
			$this->shared_responsive_css( 'Desktop' )
		);

		$tablet_css = array_merge(
			$visibility_css['Tablet'],
			$this->shared_responsive_css( 'Tablet' )
		);

		$mobile_css = array_merge(
			$visibility_css['Mobile'],
			$this->shared_responsive_css( 'Mobile' )
		);

		$css_array = array(
			'desktop_css' => $desktop_css,
			'tablet_css'  => $tablet_css,
			'mobile_css'  => $mobile_css,
		);

		return $css_array;
	}

	/**
	 * Check if card content element is active.
	 *
	 * @param array  $options Card contents array.
	 * @param string $key_name Content element name to check.
	 * @return bool True if content is active, false otherwise.
	 */
	private function is_active_card_content( $options, $key_name ) {
		if ( empty( $options ) || ! is_array( $options ) ) {
			return false;
		}
		return in_array( $key_name, array_column( $options, 'name' ), true );
	}
}
