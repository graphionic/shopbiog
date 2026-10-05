<?php
/**
 * Saved template CSS emitter for page builders.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base;

use ShapedPlugin\TestimonialFree\Blocks\Styles\DynamicStyle;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Builds the saved template CSS markup for page builder modules.
 *
 * Page builder pages keep the template ID inside builder specific data
 * (Elementor/Bricks/Beaver/Oxygen meta or a builder shortcode), so the
 * `wp_enqueue_scripts` pass never sees it and a style enqueued while the
 * body renders is printed too late (or dropped). Every builder therefore
 * prints the template CSS right next to its markup, on the frontend as
 * well as inside the builder editor.
 *
 * @since 4.2.0
 */
class Template_Css {

	/**
	 * Template IDs already printed in the current request.
	 *
	 * @var array
	 */
	private static $printed = array();

	/**
	 * Whether the CSS of a template was already printed in this request.
	 *
	 * @since 4.2.0
	 *
	 * @param int $template_id Template post ID.
	 * @return bool
	 */
	public static function is_printed( $template_id ) {
		return isset( self::$printed[ absint( $template_id ) ] );
	}

	/**
	 * Get the CSS markup (stylesheet link or inline style + Google fonts) of a saved template.
	 *
	 * Returns an empty string when the template CSS was already printed
	 * in the current request.
	 *
	 * @since 4.2.0
	 *
	 * @param int $template_id Template post ID.
	 * @return string
	 */
	public static function get( $template_id ) {
		$template_id = absint( $template_id );
		if ( ! $template_id || self::is_printed( $template_id ) ) {
			return '';
		}

		self::$printed[ $template_id ] = true;

		// Already linked in the head by DynamicStyle::collect_blocks_and_generate_css().
		$handle = 'sp-real-css-' . $template_id;
		if ( wp_style_is( $handle, 'enqueued' ) || wp_style_is( $handle, 'done' ) ) {
			return '';
		}

		$output     = '';
		$font_lists = array();
		$upload_dir = wp_upload_dir();
		$css_file   = trailingslashit( $upload_dir['basedir'] ) . 'testimonial-pro/assets/sp-real-style-' . $template_id . '.css';
		$css_url    = trailingslashit( $upload_dir['baseurl'] ) . 'testimonial-pro/assets/sp-real-style-' . $template_id . '.css';

		if ( file_exists( $css_file ) ) {
			$sp_rand = get_post_meta( $template_id, '_sp_real_unique_version', true );
			$sp_rand = ! empty( $sp_rand ) ? $sp_rand : SP_TFREE_VERSION;

			$output    .= '<link rel="stylesheet" id="rtp-css-' . esc_attr( $template_id ) . '" href="' . esc_url( $css_url . '?v=sp-real-' . $sp_rand ) . '" media="all">'; // phpcs:ignore.
			$font_lists = get_post_meta( $template_id, 'sp_real_dynamic_fonts', true );
		} else {
			// The CSS file is not generated yet: build it now and print it inline.
			$dynamic_assets = DynamicStyle::instance()->generate_post_css_file( $template_id );

			if ( false !== $dynamic_assets && is_array( $dynamic_assets ) ) {
				$css        = $dynamic_assets[0] ?? '';
				$font_lists = $dynamic_assets[1] ?? array();

				if ( ! empty( $css ) ) {
					$output .= '<style id="rtp-css-' . esc_attr( $template_id ) . '">' . str_replace( '</style', '<\/style', $css ) . '</style>';

					$unique_id = wp_rand( 1000, 9999 );
					update_post_meta( $template_id, '_sp_real_unique_version', $unique_id );
				}
			}
		}

		if ( ! empty( $font_lists ) && is_array( $font_lists ) ) {
			$font_lists = array_unique( array_filter( $font_lists ) );
			if ( ! empty( $font_lists ) ) {
				$output .= '<link rel="stylesheet" id="rtp-google-fonts-' . esc_attr( $template_id ) . '" href="' . esc_url( 'https://fonts.googleapis.com/css?family=' . implode( '|', $font_lists ) ) . '" media="all">'; // phpcs:ignore.
			}
		}

		return $output;
	}
}
