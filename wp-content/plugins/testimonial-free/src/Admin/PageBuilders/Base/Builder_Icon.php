<?php
/**
 * Shared addon icon for page builder integrations.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base;

use ShapedPlugin\TestimonialFree\Core\SPFileSystem;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Single source of the Real Testimonials addon icon shown in page builders.
 *
 * Every builder wants the same brand mark but accepts a different form of it:
 * Divi reads a filesystem path, Oxygen renders a URL as an `<img>`, Beaver takes
 * inline SVG markup, and Elementor/Bricks/WPBakery only accept a CSS class. This
 * class hands each one the form its API expects so no integration re-implements
 * the lookup.
 *
 * @since 4.2.5
 */
class Builder_Icon {

	/**
	 * Class name used by builders that only accept a CSS class.
	 *
	 * @var string
	 */
	const CSS_CLASS = 'rtp-builder-icon';

	/**
	 * Icon location relative to SP_TFREE_PATH / SP_TFREE_URL.
	 *
	 * Both constants already end in `src/`, so this must not repeat it.
	 *
	 * @var string
	 */
	const RELATIVE = 'Admin/PageBuilders/assets/icon.svg';

	/**
	 * Cached inline SVG markup.
	 *
	 * @var string|null
	 */
	private static $svg = null;

	/**
	 * Icon URL, for builders that render the icon as an image.
	 *
	 * @since 4.2.5
	 *
	 * @return string
	 */
	public static function url() {
		return SP_TFREE_URL . self::RELATIVE;
	}

	/**
	 * Icon filesystem path, for builders that read the file from disk.
	 *
	 * @since 4.2.5
	 *
	 * @return string
	 */
	public static function path() {
		return SP_TFREE_PATH . self::RELATIVE;
	}

	/**
	 * Inline SVG markup, for builders that embed the icon directly.
	 *
	 * @since 4.2.5
	 *
	 * @return string Empty string when the file cannot be read.
	 */
	public static function svg() {
		if ( null === self::$svg ) {
			$file_system = new SPFileSystem();
			self::$svg   = self::normalize_svg( trim( (string) $file_system->get_file_contents( self::path() ) ) );
		}

		return self::$svg;
	}

	/**
	 * Drop the intrinsic `width`/`height` off the root `<svg>` tag.
	 *
	 * Builders drop the icon into a small slot and size it from their own CSS,
	 * but they only set one axis — Divi's rule is
	 * `.et-fb-icon svg { display:block; width:100%; fill:inherit }`. The shipped
	 * file carries `width="511.999" height="416.032"`, so the untouched markup
	 * keeps its 416px height and the glyph renders far outside the slot. Without
	 * both attributes the `viewBox` supplies the aspect ratio and the builder's
	 * own sizing wins.
	 *
	 * @since 4.2.5
	 *
	 * @param string $svg SVG markup.
	 * @return string
	 */
	public static function normalize_svg( $svg ) {
		if ( ! preg_match( '/<svg\b[^>]*>/i', $svg, $matches, PREG_OFFSET_CAPTURE ) ) {
			return $svg;
		}

		$open_tag = $matches[0][0];
		$offset   = $matches[0][1];
		$stripped = preg_replace( '/\s+(?:width|height)\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $open_tag );

		return substr_replace( $svg, $stripped, $offset, strlen( $open_tag ) );
	}

	/**
	 * CSS painting the icon on builders that only accept a class name.
	 *
	 * Two flavours, because the builders hand the class to different kinds of element:
	 *
	 * - `$sized` true — a font-icon slot, `<i class="…">`, which is `display:inline` and
	 *   therefore has no box for a background to paint on. The image goes on a sized
	 *   `::before` and the host is made `inline-block`. Elementor
	 *   (`.elementor-panel .elementor-element .icon` only sets `font-size`) and Bricks
	 *   both use this shape.
	 * - `$sized` false — an element that already is a sized box with a background, so the
	 *   image goes straight on it. WPBakery's `.vc_element-icon` works this way.
	 *
	 * @since 4.2.5
	 *
	 * @param string|null $selector Selector to paint. Defaults to the shared class.
	 * @param bool        $sized    Whether the icon lands in a font-icon slot.
	 * @return string
	 */
	public static function css( $selector = null, $sized = true ) {
		if ( empty( $selector ) ) {
			$selector = '.' . self::CSS_CLASS;
		}

		$paint = sprintf(
			'background-image:url("%s");background-repeat:no-repeat;background-position:center;background-size:contain;',
			esc_url( self::url() )
		);

		if ( ! $sized ) {
			return $selector . '{' . $paint . '}';
		}

		return sprintf(
			'%1$s{display:inline-block;width:1em;height:1em;line-height:1;}%1$s::before{content:"";display:block;width:100%%;height:100%%;%2$s}',
			$selector,
			$paint
		);
	}

	/**
	 * `et_builder_module_icons` callback shared by the Divi 4 and Divi 5 modules.
	 *
	 * Divi resolves an `icon_path` entry by reading the file itself, so the markup
	 * never passes through normalize_svg() and the raw `width`/`height` survive.
	 * Handing Divi a ready `icon_svg` instead skips that branch of
	 * `ET_Builder_Element::get_module_icons()` and keeps both Divi versions on one
	 * code path. A module registering this must not also set `$icon_path`, or the
	 * file contents would overwrite the value below.
	 *
	 * @since 4.2.5
	 *
	 * @param array        $icons Registered module icons.
	 * @param string|array $slugs Module slug(s) to paint.
	 * @return array
	 */
	public static function divi_module_icons( $icons, $slugs ) {
		if ( ! is_array( $icons ) ) {
			return $icons;
		}

		$svg = self::svg();
		if ( empty( $svg ) ) {
			return $icons;
		}

		foreach ( (array) $slugs as $slug ) {
			$icons[ $slug ] = array( 'icon_svg' => $svg );
		}

		return $icons;
	}

	/**
	 * Register, fill and enqueue a src-less stylesheet carrying the icon CSS.
	 *
	 * Builders print their element list from their own editor bootstrap, so the
	 * rule has to ride on a handle of our own instead of an inline style added to
	 * a shared handle after `wp_head`.
	 *
	 * @since 4.2.5
	 *
	 * @param string      $handle   Stylesheet handle.
	 * @param string|null $selector Selector to paint. Defaults to the shared class.
	 * @param bool        $sized    Whether to emit the `::before` sizing box.
	 * @return void
	 */
	public static function enqueue_css( $handle, $selector = null, $sized = true ) {
		if ( ! wp_style_is( $handle, 'registered' ) ) {
			wp_register_style( $handle, false, array(), SP_TFREE_VERSION );
			wp_add_inline_style( $handle, self::css( $selector, $sized ) );
		}

		wp_enqueue_style( $handle );
	}
}
