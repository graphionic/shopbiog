<?php
/**
 * Helper class for css generation.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Styles
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Styles;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * CssHelpers
 */
class CssHelpers {
	/**
	 * Method object_to_css_string
	 *
	 * @param array $dynamic_css array.
	 *
	 * @return string
	 */
	public static function object_to_css_string( $dynamic_css ) {
		$css = '';
		if ( ! empty( $dynamic_css ) && is_array( $dynamic_css ) ) {
			foreach ( $dynamic_css as $item ) {
				if ( isset( $item['styles'] ) && is_array( $item['styles'] ) ) {
					$styles = '';
					foreach ( $item['styles'] as $property => $value ) {
						if ( null !== $value && '' !== $value && false !== $value ) {
							$styles .= "{$property}: {$value};";
						}
					}
					if ( $styles ) {
						$css .= "{$item['selector']} {{$styles}}";
					}
				}
			}
		}
		return $css;
	}

	/**
	 * Merge duplicate CSS selectors and their styles.
	 *
	 * @param array $css_array Array of CSS selector/style definitions.
	 *
	 * @return array Filtered array with merged selectors.
	 */
	public static function filter_duplicate_selector( $css_array ) {
		if ( empty( $css_array ) || ! is_array( $css_array ) ) {
			return array();
		}

		$selector_map = array();

		foreach ( $css_array as $css ) {
			if ( empty( $css ) || ! isset( $css['selector'], $css['styles'] ) || ! is_array( $css['styles'] ) ) {
				continue;
			}

			$selector = $css['selector'];
			$styles   = $css['styles'];

			if ( empty( $styles ) ) {
				continue;
			}

			if ( isset( $selector_map[ $selector ] ) ) {
				// Merge existing styles with new ones (new overrides old).
				$selector_map[ $selector ]['styles'] = array_merge(
					$selector_map[ $selector ]['styles'],
					$styles
				);
			} else {
				$selector_map[ $selector ] = array(
					'selector' => $selector,
					'styles'   => $styles,
				);
			}
		}

		return array_values( $selector_map );
	}

	/**
	 * Generate responsive CSS string after filtering duplicate selectors.
	 *
	 * @param array $css_obj Array containing desktopCss, tabletCss, and mobileCss.
	 *
	 * @return string Complete responsive CSS string.
	 */
	public static function filter_responsive_dynamic_css( $css_obj ) {
		if ( empty( $css_obj ) || ! is_array( $css_obj ) ) {
			return '';
		}

		$desktop_css = isset( $css_obj['desktop_css'] ) ? $css_obj['desktop_css'] : array();
		$tablet_css  = isset( $css_obj['tablet_css'] ) ? $css_obj['tablet_css'] : array();
		$mobile_css  = isset( $css_obj['mobile_css'] ) ? $css_obj['mobile_css'] : array();

		$filtered_desktop_css = self::filter_duplicate_selector( $desktop_css );
		$filtered_tablet_css  = self::filter_duplicate_selector( $tablet_css );
		$filtered_mobile_css  = self::filter_duplicate_selector( $mobile_css );

		// Build CSS string.
		$css  = self::object_to_css_string( $filtered_desktop_css );
		$css .= ' @media only screen and (min-width: 600px) and (max-width: 1023px) { ';
		$css .= self::object_to_css_string( $filtered_tablet_css );
		$css .= ' } @media only screen and (max-width: 599px) {';
		$css .= self::object_to_css_string( $filtered_mobile_css );
		$css .= ' }';

		return trim( $css );
	}

	/**
	 * Get background value based on background attributes.
	 *
	 * @param array $background_attr Background attribute array containing style, solid, and gradient.
	 *
	 * @return string|null Background value (solid, gradient, or transparent) or null if not found.
	 */
	public static function background_control( $background_attr ) {
		if ( empty( $background_attr ) || ! is_array( $background_attr ) ) {
			return null;
		}

		$style     = isset( $background_attr['style'] ) ? $background_attr['style'] : 'solid';
		$solid     = isset( $background_attr['solid'] ) ? $background_attr['solid'] : '';
		$gradient  = isset( $background_attr['gradient'] ) ? $background_attr['gradient'] : '';
		$image_url = isset( $background_attr['image']['url'] ) ? $background_attr['image']['url'] : '';

		$bg_options = array(
			'transparent' => 'transparent',
			'solid'       => $solid,
			'gradient'    => $gradient,
			'image'       => "url($image_url)",
		);

		return isset( $bg_options[ $style ] ) ? $bg_options[ $style ] : null;
	}

	/**
	 * Method bg_image_css_settings.
	 *
	 * @param  array $background bg .
	 * @return array
	 */
	public static function bg_image_css_settings( $background ) {
		$image_settings = array();
		$bg_style       = $background['style'] ?? 'solid';
		if ( 'image' !== $bg_style ) {
			return $image_settings;
		}
		$settings       = $background['imageSettings'] ?? array();
		$image_settings = array(
			'background-position'   => $settings['bgImagePosition'] ?? '',
			'background-attachment' => $settings['bgImageAttachment'] ?? '',
			'background-repeat'     => $settings['bgImageRepeat'] ?? '',
			'background-size'       => $settings['bgImageSize'] ?? '',
		);
		return $image_settings;
	}

	/**
	 * Format a `range_control` attribute (non-responsive scalar + unit) into a CSS value.
	 *
	 * Attribute shape (from `Utils::range_control`):
	 *   array( 'value' => int|string, 'unit' => string )
	 *
	 * Use case: single numeric controls such as `cardHoverTransition` (600 + "ms")
	 * or `paginationNumberGap`, e.g.
	 *   'transition-duration' => CssHelpers::range_control_css( $card_hover_trans ).
	 *
	 * @param array $attribute The range_control attribute array.
	 *
	 * @return string Formatted CSS value (e.g. "600ms"), or empty string when value is empty.
	 */
	public static function range_control_css( $attribute ) {
		if ( ! is_array( $attribute ) || ! isset( $attribute['value'] ) || '' === trim( (string) $attribute['value'] ) ) {
			return '';
		}

		return $attribute['value'] . ( $attribute['unit'] ?? '' );
	}

	/**
	 * Format a `single_value_unit` attribute (non-responsive scalar + unit) into a CSS value.
	 *
	 * Attribute shape (from `Utils::single_value_unit`):
	 *   array( 'value' => int|string, 'unit' => string )
	 *
	 * Same data shape as range_control; kept separate for intent/readability.
	 * Use case: standalone value+unit controls (e.g. an opacity of 50 + "%"),
	 * used as 'opacity' => CssHelpers::single_value_unit_css( $some_attr ).
	 *
	 * @param array $attribute The single_value_unit attribute array.
	 *
	 * @return string Formatted CSS value (e.g. "50%"), or empty string when value is empty.
	 */
	public static function single_value_unit_css( $attribute ) {
		if ( ! is_array( $attribute ) || ! isset( $attribute['value'] ) || '' === trim( (string) $attribute['value'] ) ) {
			return '';
		}

		return $attribute['value'] . ( $attribute['unit'] ?? '' );
	}

	/**
	 * Shared box formatter for spacing / responsive_spacing.
	 *
	 * Emits each filled side in top/right/bottom/left order. `allChange` is not
	 * honored here — the spacing control already writes the same value to every
	 * side when linked, so linked simply yields four equal values (parity with the
	 * JS `formatBoxValue`). Internal helper — callers pass the per-device or
	 * non-responsive side map already resolved.
	 *
	 * @param array  $sides      Side value map: array( 'top', 'right', 'bottom', 'left' ).
	 * @param string $unit       Unit appended to each value (e.g. "px", "%").
	 *
	 * @return string e.g. "10px 10px 10px 10px" (linked) or "10px 12px 10px 5px" (unlinked).
	 */
	private static function format_box_value( $sides, $unit ) {
		if ( ! is_array( $sides ) ) {
			return '';
		}

		$is_filled = static function ( $val ) {
			return null !== $val && '' !== trim( (string) $val );
		};

		$parts = array();
		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			if ( isset( $sides[ $side ] ) && $is_filled( $sides[ $side ] ) ) {
				$parts[] = $sides[ $side ] . $unit;
			}
		}

		return implode( ' ', $parts );
	}

	/**
	 * Format a `single_responsive` attribute (one value per device) into a CSS value.
	 *
	 * Attribute shape (from `Utils::single_responsive`):
	 *   array(
	 *     'device' => array( 'Desktop' => v, 'Tablet' => v, 'Mobile' => v ),
	 *     'unit'   => array( 'Desktop' => u, 'Tablet' => u, 'Mobile' => u ),
	 *   )
	 *
	 * Use case: per-device single values such as `columnGap`, `imageWidth`,
	 * `ratingIconSize`, e.g.
	 *   'width' => CssHelpers::single_responsive_css( $image_width, $device_type ).
	 *
	 * @param array  $attribute   The single_responsive attribute array.
	 * @param string $device_type Device type key ('Desktop', 'Tablet', 'Mobile').
	 *
	 * @return string Formatted CSS value (e.g. "70px"), or empty string when empty.
	 */
	public static function single_responsive_css( $attribute, $device_type = 'Desktop' ) {
		if ( ! is_array( $attribute ) ) {
			return '';
		}

		$value = $attribute['device'][ $device_type ] ?? '';
		if ( '' === trim( (string) $value ) ) {
			return '';
		}

		return $value . self::get_unit( $attribute, $device_type );
	}

	/**
	 * Format a non-responsive `spacing` attribute (4-side box, no device) into a CSS value.
	 *
	 * Attribute shape (from `Utils::spacing`):
	 *   array(
	 *     'value'     => array( 'top', 'right', 'bottom', 'left' ),
	 *     'unit'      => string,
	 *     'allChange' => bool,
	 *   )
	 *
	 * Use case: non-responsive boxes such as `cardBorderRadius`, `imageBorderWidth`,
	 * e.g. 'border-radius' => CssHelpers::spacing_css( $card_border_radius ).
	 *
	 * @param array $attribute The spacing attribute array.
	 *
	 * @return string "16px 16px 16px 16px" (linked, equal sides) or "16px 8px 4px 2px" (unlinked), or empty string.
	 */
	public static function spacing_css( $attribute ) {
		if ( ! is_array( $attribute ) ) {
			return '';
		}

		return self::format_box_value( $attribute['value'] ?? array(), $attribute['unit'] ?? '' );
	}

	/**
	 * Format a `responsive_spacing` attribute (4-side box per device) into a CSS value.
	 *
	 * Attribute shape (from `Utils::responsive_spacing`):
	 *   array(
	 *     'device'    => array(
	 *       'Desktop' => array( 'top', 'right', 'bottom', 'left' ),
	 *       'Tablet'  => array( ... ),
	 *       'Mobile'  => array( ... ),
	 *     ),
	 *     'unit'      => array( 'Desktop' => u, 'Tablet' => u, 'Mobile' => u ),
	 *     'allChange' => bool,
	 *   )
	 *
	 * Use case: per-device boxes such as `cardPadding`, `titleMargin`, `formPadding`,
	 * e.g. 'padding' => CssHelpers::responsive_spacing_css( $card_padding, $device_type ).
	 *
	 * @param array  $attribute   The responsive_spacing attribute array.
	 * @param string $device_type Device type key ('Desktop', 'Tablet', 'Mobile').
	 *
	 * @return string "12px 12px 12px 12px" (linked) or "12px 0px 12px 0px" (unlinked), or empty string.
	 */
	public static function responsive_spacing_css( $attribute, $device_type = 'Desktop' ) {
		if ( ! is_array( $attribute ) ) {
			return '';
		}
		return self::format_box_value( $attribute['device'][ $device_type ] ?? array(), self::get_unit( $attribute, $device_type ) );
	}

	/**
	 * Get the unit value based on device type.
	 *
	 * @param array  $attributes Attributes array containing unit information.
	 * @param string $device_type Device type key (e.g., 'Desktop', 'Tablet', 'Mobile').
	 *
	 * @return string|null Unit string or null if not found.
	 */
	public static function get_unit( $attributes, $device_type = 'Desktop' ) {
		$unit = $attributes['unit'] ?? 'px';
		// If unit is not an array (single value).
		$current_unit = '';
		if ( is_array( $unit ) ) {
			$current_unit = isset( $unit[ $device_type ] ) ? $unit[ $device_type ] : 'px';
		} else {
			$current_unit = $unit;
		}
		return $current_unit;
	}

	/**
	 * Generate the box-shadow CSS declaration from shadow settings.
	 *
	 * Mirrors get_border_styles(): returns a property map to be spread into a
	 * styles array via array_merge(). When the shadow is disabled (or empty)
	 * nothing is returned, so no `box-shadow` declaration is emitted at all.
	 *
	 * @param array $shadow Shadow settings array.
	 *
	 * @return array CSS declaration map: array( 'box-shadow' => value ) or array().
	 */
	public static function box_shadow_css( $shadow ) {
		if ( empty( $shadow ) || ! is_array( $shadow ) ) {
			return array();
		}

		$is_active = isset( $shadow['isActive'] ) ? (bool) $shadow['isActive'] : false;

		if ( ! $is_active ) {
			return array();
		}

		$unit   = isset( $shadow['unit'] ) ? $shadow['unit'] : '';
		$values = isset( $shadow['value'] ) ? $shadow['value'] : array();
		$color  = isset( $shadow['color'] ) ? $shadow['color'] : '';

		$top    = isset( $values['top'] ) ? $values['top'] : 0;
		$right  = isset( $values['right'] ) ? $values['right'] : 0;
		$bottom = isset( $values['bottom'] ) ? $values['bottom'] : 0;
		$left   = isset( $values['left'] ) ? $values['left'] : 0;

		$inset = ( 'inset' === $unit ) ? 'inset ' : '';

		return array(
			'box-shadow' => sprintf(
				'%s%dpx %dpx %dpx %dpx %s',
				$inset,
				$top,
				$right,
				$bottom,
				$left,
				$color
			),
		);
	}

	/**
	 * Generate typography CSS array from typography settings.
	 *
	 * @param array $typography Typography attributes array.
	 *
	 * @return array CSS properties array.
	 */
	public static function generate_typography_css( $typography ) {
		if ( empty( $typography ) || ! is_array( $typography ) ) {
			return array();
		}

		$family      = isset( $typography['family'] ) ? $typography['family'] : '';
		$font_weight = isset( $typography['fontWeight'] ) ? $typography['fontWeight'] : '';
		$style       = isset( $typography['style'] ) ? $typography['style'] : '';
		$transform   = isset( $typography['transform'] ) ? $typography['transform'] : '';
		$decoration  = isset( $typography['decoration'] ) ? $typography['decoration'] : '';

		$styles = array();

		if ( $family ) {
			$styles['font-family'] = $family;
		}

		if ( $font_weight ) {
			$styles['font-weight'] = $font_weight;
		}

		if ( $style && 'normal' !== $style ) {
			$styles['font-style'] = $style;
		}

		if ( $transform && 'none' !== $transform ) {
			$styles['text-transform'] = $transform;
		}

		if ( $decoration && 'none' !== $decoration ) {
			$styles['text-decoration'] = $decoration;
		}

		return $styles;
	}

	/**
	 * Generate responsive typography CSS for a specific device.
	 *
	 * @param array  $attributes Typography attributes array.
	 * @param string $device     Device type ('Desktop', 'Tablet', 'Mobile').
	 * @param string $key        Base key for typography (e.g., 'title', 'content').
	 *
	 * @return array CSS properties array.
	 */
	public static function generate_typo_responsive( $attributes, $device, $key ) {
		$font_size_attr   = isset( $attributes[ $key . 'FontSize' ] ) ? $attributes[ $key . 'FontSize' ] : array();
		$line_height_attr = isset( $attributes[ $key . 'LineHeight' ] ) ? $attributes[ $key . 'LineHeight' ] : array();
		$spacing_attr     = isset( $attributes[ $key . 'LetterSpacing' ] ) ? $attributes[ $key . 'LetterSpacing' ] : array();

		$font_size      = isset( $font_size_attr['device'][ $device ] ) ? $font_size_attr['device'][ $device ] : '';
		$line_height    = isset( $line_height_attr['device'][ $device ] ) ? $line_height_attr['device'][ $device ] : '';
		$letter_spacing = isset( $spacing_attr['device'][ $device ] ) ? $spacing_attr['device'][ $device ] : 0;

		$unit_font_size = isset( $font_size_attr['unit'][ $device ] ) ? $font_size_attr['unit'][ $device ] : 'px';
		$unit_spacing   = isset( $spacing_attr['unit'][ $device ] ) ? $spacing_attr['unit'][ $device ] : 'px';

		$styles = array();

		if ( $font_size ) {
			$styles['font-size'] = $font_size . $unit_font_size;
		}

		if ( $line_height ) {
			$styles['line-height'] = $line_height;
		}

		if ( null !== $letter_spacing && '' !== $letter_spacing ) {
			$styles['letter-spacing'] = $letter_spacing . $unit_spacing;
		}

		return $styles;
	}

	/**
	 * Method get_border_styles
	 *
	 * @param array $border border.
	 * @param array $border_width border width.
	 *
	 * @return array
	 */
	public static function get_border_styles( $border, $border_width ) {
		$border_style = $border['style'] ?? 'solid';
		if ( 'none' === $border_style ) {
			return array( 'border' => 'none' );
		}
		$border_array = array(
			'border-style' => $border_style,
			'border-color' => $border['color'],
			'border-width' => self::spacing_css( $border_width ),
		);
		return $border_array;
	}

	/**
	 * Method get_visibility_css
	 *
	 * Hidden devices get `display:none`; visible lower breakpoints re-assert the
	 * wrapper's natural display so a higher-breakpoint hide does not cascade down.
	 * Pass `$visible_display` ('flex' etc.) when the block wrapper (`#uniqueId`)
	 * is not a plain block — otherwise the reset would clobber its layout.
	 *
	 * @param array  $attributes      block attributes.
	 * @param string $visible_display Natural display value for the visible state.
	 * @return array
	 */
	public static function get_visibility_css( $attributes, $visible_display = 'block' ) {
		$unique_id    = $attributes['uniqueId'] ?? '';
		$hide_desktop = $attributes['hideOnDesktop'] ?? false;
		$hide_tablet  = $attributes['hideOnTablet'] ?? false;
		$hide_mobile  = $attributes['hideOnMobile'] ?? false;
		// All blocks render uniqueId as the element id, so target it by id (not class).
		$selector = "#$unique_id";
		// css array.
		$desktop_css = array();
		$tablet_css  = array();
		$mobile_css  = array();

		if ( $hide_desktop ) {
			$desktop_css[] = array(
				'selector' => $selector,
				'styles'   => array( 'display' => 'none' ),
			);
		}
		if ( $hide_tablet ) {
			$tablet_css[] = array(
				'selector' => $selector,
				'styles'   => array( 'display' => 'none' ),
			);
		} else {
			$tablet_css[] = array(
				'selector' => $selector,
				'styles'   => array( 'display' => $visible_display ),
			);
		}

		if ( $hide_mobile ) {
			$mobile_css[] = array(
				'selector' => $selector,
				'styles'   => array( 'display' => 'none' ),
			);
		} else {
			$mobile_css[] = array(
				'selector' => $selector,
				'styles'   => array( 'display' => 'block' ),
			);
		}
		$css_array = array(
			'Desktop' => $desktop_css,
			'Tablet'  => $tablet_css,
			'Mobile'  => $mobile_css,
		);
		return $css_array;
	}
}
