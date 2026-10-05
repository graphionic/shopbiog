<?php
/**
 * Real Testimonials ChildBlocksCss File.
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
 * ChildBlocksCss - Dynamic CSS generation for testimonial blocks.
 */
class ChildBlocksCss {
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
	 * Generate responsive CSS for Ajax Pagination block.
	 *
	 * @param string $device_type Device type (Desktop/Tablet/Mobile).
	 * @return array
	 */
	public function ajax_pagination_responsive_css( $device_type = 'Desktop' ) {
		$attributes         = $this->attributes;
		$unique_id          = $this->unique_id;
		$pagination_padding = $attributes['paginationPadding'] ?? array();
		$pagination_margin  = $attributes['paginationMargin'] ?? array();

		return array(
			array(
				'selector' => "$unique_id .sp-real-pagination-item",
				'styles'   => array_merge(
					CssHelpers::generate_typo_responsive( $attributes, $device_type, 'pagination' ),
					array(
						'padding' => CssHelpers::responsive_spacing_css( $pagination_padding, $device_type ),
					)
				),
			),
			array(
				'selector' => "$unique_id .sp-real-pagination-wrapper",
				'styles'   => array(
					'margin' => CssHelpers::responsive_spacing_css( $pagination_margin, $device_type ),
				),
			),
		);
	}

	/**
	 * Assemble the full Ajax Pagination block CSS object.
	 *
	 * @return array Array with desktop_css, tablet_css and mobile_css keys.
	 */
	public function ajax_pagination_block_css() {
		$attributes               = $this->attributes;
		$unique_id                = $this->unique_id;
		$pagination_button_type   = $attributes['paginationButtonType'] ?? 'load-more';
		$pagination_color         = $attributes['paginationColor'] ?? array();
		$pagination_bg_color      = $attributes['paginationBgColor'] ?? array();
		$pagination_border        = $attributes['paginationBorder'] ?? array();
		$pagination_border_width  = $attributes['paginationBorderWidth'] ?? array();
		$pagination_border_radius = $attributes['paginationBorderRadius'] ?? array();
		$pagination_number_gap    = $attributes['paginationNumberGap'] ?? array();
		$pagination_typography    = $attributes['paginationTypography'] ?? array();

		$desktop_css = array(
			array(
				'selector' => "$unique_id .sp-real-pagination-item",
				'styles'   => array_merge(
					CssHelpers::generate_typography_css( $pagination_typography ),
					CssHelpers::get_border_styles( $pagination_border, $pagination_border_width ),
					array(
						'color'            => $pagination_color['normal'] ?? '',
						'background-color' => $pagination_bg_color['normal'] ?? '',
						'border-radius'    => CssHelpers::spacing_css( $pagination_border_radius ),
					)
				),
			),
			array(
				'selector' => "$unique_id .sp-real-pagination-item:where(:hover, .current)",
				'styles'   => array(
					'color'            => $pagination_color['hover'] ?? '',
					'background-color' => $pagination_bg_color['hover'] ?? '',
					'border-color'     => $pagination_border['hoverColor'] ?? '',
				),
			),
			( 'number' === $pagination_button_type ) ? array(
				'selector' => "$unique_id .sp-real-pagination-buttons",
				'styles'   => array(
					'gap' => CssHelpers::range_control_css( $pagination_number_gap ),
				),
			) : array(),
		);

		$visibility_css = CssHelpers::get_visibility_css( $attributes );
		$desktop_css    = array_merge(
			$desktop_css,
			$visibility_css['Desktop'],
			$this->ajax_pagination_responsive_css( 'Desktop' )
		);
		$tablet_css     = array_merge(
			$visibility_css['Tablet'],
			$this->ajax_pagination_responsive_css( 'Tablet' )
		);
		$mobile_css     = array_merge(
			$visibility_css['Mobile'],
			$this->ajax_pagination_responsive_css( 'Mobile' )
		);

		return array(
			'desktop_css' => $desktop_css,
			'tablet_css'  => $tablet_css,
			'mobile_css'  => $mobile_css,
		);
	}
}
