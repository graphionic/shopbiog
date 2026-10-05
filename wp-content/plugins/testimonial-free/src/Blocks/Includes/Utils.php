<?php
/**
 * CSS utils functions.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Includes
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Utils
 *
 * Provides reusable helper methods for generating
 * Gutenberg block attribute definitions.
 */
class Utils {

	/**
	 * Generate a colors attribute definition.
	 *
	 * @param string $normal Default normal color.
	 * @param string $hover  Default hover color.
	 * @param string $active Default active color.
	 *
	 * @return array Colors attribute configuration.
	 */
	public static function colors( $normal = '', $hover = '', $active = null ) {
		$_colors = array(
			'type'    => 'object',
			'default' => array(
				'normal' => $normal,
				'hover'  => $hover,
			),
		);

		if ( null !== $active ) {
			$_colors['active'] = $active;
		}

		return $_colors;
	}

	/**
	 * Generate a typography attribute definition.
	 *
	 * @param string $font_weight Font weight value.
	 * @param string $style       Font style.
	 * @param string $transform   Text transform value.
	 * @param string $decoration  Text decoration value.
	 * @param string $family      Font family.
	 *
	 * @return array Typography attribute configuration.
	 */
	public static function typography( $font_weight = '400', $style = 'normal', $transform = 'none', $decoration = 'none', $family = '' ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'family'     => $family,
				'fontWeight' => $font_weight,
				'style'      => $style,
				'transform'  => $transform,
				'decoration' => $decoration,
			),
		);
	}

	/**
	 * Generate a single responsive value attribute definition.
	 *
	 * @param string $desktop Desktop value.
	 * @param string $tablet  Tablet value.
	 * @param string $mobile  Mobile value.
	 * @param string $unit    CSS unit.
	 *
	 * @return array Responsive attribute configuration.
	 */
	public static function single_responsive( $desktop = '', $tablet = '', $mobile = '', $unit = 'px' ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'device' => array(
					'Desktop' => $desktop,
					'Tablet'  => $tablet,
					'Mobile'  => $mobile,
				),
				'unit'   => array(
					'Desktop' => $unit,
					'Tablet'  => $unit,
					'Mobile'  => $unit,
				),
			),
		);
	}

	/**
	 * Generate a spacing attribute definition.
	 *
	 * @param string $top    Top spacing.
	 * @param string $right  Right spacing.
	 * @param string $bottom Bottom spacing.
	 * @param string $left   Left spacing.
	 * @param string $unit   CSS unit.
	 * @param string $all_change   change alll.
	 *
	 * @return array Spacing attribute configuration.
	 */
	public static function spacing( $top = '', $right = '', $bottom = '', $left = '', $unit = 'px', $all_change = true ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'value'     => array(
					'top'    => $top,
					'right'  => $right,
					'bottom' => $bottom,
					'left'   => $left,
				),
				'unit'      => $unit,
				'allChange' => $all_change,
			),
		);
	}

	/**
	 * Generate a responsive spacing attribute definition.
	 *
	 * @param string $top top spacing values.
	 * @param string $right  right spacing values.
	 * @param string $bottom  bottom spacing values.
	 * @param string $left  left spacing values.
	 * @param string $unit    CSS unit.
	 * @param bolean $all_change    CSS unit.
	 *
	 * @return array Responsive spacing attribute configuration.
	 */
	public static function responsive_spacing( $top = '', $right = '', $bottom = '', $left = '', $unit = 'px', $all_change = true ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'device'    => array(
					'Desktop' => array(
						'top'    => $top,
						'right'  => $right,
						'bottom' => $bottom,
						'left'   => $left,
					),
					'Tablet'  => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'Mobile'  => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
				),
				'unit'      => array(
					'Desktop' => $unit,
					'Tablet'  => $unit,
					'Mobile'  => $unit,
				),
				'allChange' => $all_change,
			),
		);
	}

	/**
	 * Usually used for fields which has four values (top, right, bottom, left).
	 *
	 * @param string $is_active spacing top value.
	 * @param string $top spacing top value.
	 * @param string $right spacing right value.
	 * @param string $bottom bottom value.
	 * @param string $left left value.
	 * @param string $color color.
	 * @param string $unit unit value.
	 *
	 * @return $spacing
	 */
	public static function box_shadow( $is_active = false, $top = '', $right = '', $bottom = '', $left = '', $color = '', $unit = 'Outset' ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'value'    => array(
					'top'    => $top,
					'right'  => $right,
					'bottom' => $bottom,
					'left'   => $left,
				),
				'unit'     => $unit,
				'color'    => $color,
				'isActive' => $is_active,
			),
		);
	}

	/**
	 * Usually used for boolean.
	 *
	 * @param boolean $value default value.
	 *
	 * @return $spacing
	 */
	public static function boolean( $value = false ) {
		return array(
			'type'    => 'boolean',
			'default' => $value,
		);
	}
	/**
	 * Usually used for boolean.
	 *
	 * @param mixed $value default value.
	 *
	 * @return $spacing
	 */
	public static function string( $value = '' ) {
		return array(
			'type'    => 'string',
			'default' => $value,
		);
	}
	/**
	 * Usually used for border object.
	 *
	 * @param string $default_style default style.
	 * @param string $normal_color default color.
	 * @param string $hover_color default hover color.
	 *
	 * @return $border
	 */
	public static function border( $default_style = 'none', $normal_color = '', $hover_color = null ) {
		$default = array(
			'style' => $default_style,
			'color' => $normal_color,
		);

		if ( null !== $hover_color ) {
			$default['hoverColor'] = $hover_color;
		}

		return array(
			'type'    => 'object',
			'default' => $default,
		);
	}

	/**
	 * Usually used for ranger control object.
	 *
	 * @param  mixed  $default_value default value.
	 * @param  string $unit default unit.
	 * @return object
	 */
	public static function single_value_unit( $default_value, $unit = 'px' ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'value' => $default_value,
				'unit'  => $unit,
			),
		);
	}

	/**
	 * Range control object.
	 *
	 * @param  mixed  $default_value default value.
	 * @param  string $unit default unit.
	 * @return object
	 */
	public static function range_control( $default_value, $unit = 'px' ) {
		return array(
			'type'    => 'object',
			'default' => array(
				'value' => $default_value,
				'unit'  => $unit,
			),
		);
	}

	/**
	 * Background object alias.
	 *
	 * @param  mixed $states states.
	 * @param  mixed $defaults default value.
	 * @return array
	 */
	public static function bg( $states = array( 'normal', 'hover', 'active' ), $defaults = array() ) {
		return self::background( $states, $defaults );
	}

	/**
	 * Background object.
	 *
	 * @param  mixed $states states.
	 * @param  mixed $defaults default value.
	 * @return array
	 */
	public static function background( $states = array( 'normal', 'hover', 'active' ), $defaults = array() ) {
		$base = array(
			'style'    => $defaults['style'] ?? 'solid',
			'solid'    => $defaults['solid'] ?? '',
			'gradient' => $defaults['gradient'] ?? 'var(--sp-real-gradient-color)',
		);

		$data = array();

		foreach ( $states as $state ) {
			$data[ $state ] = $base;
		}

		return array(
			'type'    => 'object',
			'default' => $data,
		);
	}
}
