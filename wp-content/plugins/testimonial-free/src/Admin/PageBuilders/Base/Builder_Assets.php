<?php
/**
 * Shared block asset loader for page builder integrations.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin/PageBuilders
 */

namespace ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base;

use ShapedPlugin\TestimonialFree\Admin\Dashboard\DashboardHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Single source of the block assets a saved template needs inside a page builder.
 *
 * A saved template is block content, so it needs the block frontend bundle. Builders
 * cannot rely on `BlocksInit::sp_real_register_block_assets()` for it: that runs on
 * `enqueue_block_assets`, and `wp_common_block_scripts_and_styles()` short-circuits
 * that hook in admin unless `wp_should_load_block_editor_scripts_and_styles()` — which
 * is false in the Elementor and WPBakery backend editors. Every integration therefore
 * registers the set itself, and does it through this class so the handle/source map
 * lives in exactly one place.
 *
 * The `[sp_real_template]` shortcode uses it for the same reason: it renders block
 * content from inside the body, where nothing has enqueued the block bundle for it.
 *
 * @since 4.2.5
 */
class Builder_Assets {

	/**
	 * Whether the frontend script was localized in this request.
	 *
	 * @var bool
	 */
	private static $localized = false;

	/**
	 * Whether the dashboard custom CSS/JS was attached in this request.
	 *
	 * @var bool
	 */
	private static $custom_attached = false;

	/**
	 * `.min` suffix unless SCRIPT_DEBUG is on.
	 *
	 * @since 4.2.5
	 *
	 * @return string
	 */
	private static function suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * The handle/source map, for assertions.
	 *
	 * @since 4.2.5
	 *
	 * @return array With `scripts` and `styles` keys.
	 */
	public static function manifest() {
		return array(
			'scripts' => self::scripts(),
			'styles'  => self::styles(),
		);
	}

	/**
	 * Scripts a saved template needs, in dependency order.
	 *
	 * Mirrors the `$scripts` declared by `RenderTestimonialBlock` and `Carousel`, and
	 * the registrations in `BlocksInit::sp_real_register_block_assets()`. Sources must
	 * stay identical to those, so a duplicate registration is a harmless no-op.
	 *
	 * @since 4.2.5
	 *
	 * @return array Handle => array( src, deps ).
	 */
	private static function scripts() {
		return array(
			'sp-real-swiper'          => array( SP_TFREE_URL . 'Frontend/assets/js/swiper.min.js', array() ),
			'sp-real-blocks-frontend' => array( SP_TFREE_URL . 'Blocks/assets/js/script.js', array( 'sp-real-swiper' ) ),
		);
	}

	/**
	 * Styles a saved template needs, in enqueue order.
	 *
	 * The first four mirror the `$styles` declared by the layout blocks. The last two
	 * cover the classic `shortcode` / `form` blocks and the submission form, which can
	 * also sit inside a saved template. The free plugin ships no magnific-popup
	 * stylesheet, so that handle is absent here (video/read-more popups are Pro-only).
	 *
	 * @since 4.2.5
	 *
	 * @return array Handle => src.
	 */
	private static function styles() {
		$suffix = self::suffix();

		return array(
			'sp-real-blocks-style' => SP_RT_PLUGIN_URL . 'dist/style-rtp-blocks.css',
			'sp-real-swiper'       => SP_TFREE_URL . 'Frontend/assets/css/swiper.min.css',
			'tfree-font-awesome'   => SP_TFREE_URL . 'Frontend/assets/css/font-awesome' . $suffix . '.css',
			'tpro-block-fontello'  => SP_TFREE_URL . 'Admin/assets/css/fontello.min.css',
			'tfree-style'          => SP_TFREE_URL . 'Frontend/assets/css/style' . $suffix . '.css',
			'tfree-form'           => SP_TFREE_URL . 'Frontend/assets/css/form' . $suffix . '.css',
		);
	}

	/**
	 * Register every handle that is not registered yet.
	 *
	 * Safe to call from any enqueue hook and any number of times.
	 *
	 * @since 4.2.5
	 *
	 * @return void
	 */
	public static function register() {
		foreach ( self::scripts() as $handle => $script ) {
			if ( ! wp_script_is( $handle, 'registered' ) ) {
				wp_register_script( $handle, $script[0], $script[1], SP_TFREE_VERSION, true );
			}
		}

		foreach ( self::styles() as $handle => $src ) {
			if ( ! wp_style_is( $handle, 'registered' ) ) {
				wp_register_style( $handle, $src, array(), SP_TFREE_VERSION, 'all' );
			}
		}

		self::localize();
		self::custom_assets();
	}

	/**
	 * Register and enqueue the whole set.
	 *
	 * @since 4.2.5
	 *
	 * @return void
	 */
	public static function enqueue() {
		self::register();

		foreach ( array_keys( self::scripts() ) as $handle ) {
			wp_enqueue_script( $handle );
		}

		foreach ( array_keys( self::styles() ) as $handle ) {
			wp_enqueue_style( $handle );
		}
	}

	/**
	 * Give the frontend script the ajax URL and nonce it needs.
	 *
	 * Without this ajax-pagination is dead inside a builder (the other ajax children —
	 * ajax-search, live-filter, filter-by-group, filter-by-rating — are Pro-only),
	 * because a builder
	 * that registers `sp-real-blocks-frontend` itself pre-empts the localization
	 * `BlocksInit` attaches to it.
	 *
	 * @since 4.2.5
	 *
	 * @return void
	 */
	private static function localize() {
		if ( self::$localized ) {
			return;
		}

		self::$localized = true;

		wp_localize_script(
			'sp-real-blocks-frontend',
			'sp_real_localize_data',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'sp_real_block_nonce' ),
			)
		);
	}

	/**
	 * Attach the dashboard Custom CSS & JS, as `BlocksInit` does outside builders.
	 *
	 * @since 4.2.5
	 *
	 * @return void
	 */
	private static function custom_assets() {
		if ( self::$custom_attached ) {
			return;
		}

		self::$custom_attached = true;

		if ( ! class_exists( DashboardHelper::class ) ) {
			return;
		}

		$settings = DashboardHelper::get_plugin_settings();

		$custom_js = isset( $settings['custom_js'] ) ? trim( html_entity_decode( $settings['custom_js'] ) ) : '';
		if ( ! empty( $custom_js ) ) {
			wp_add_inline_script( 'sp-real-blocks-frontend', $custom_js );
		}

		$custom_css = isset( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : '';
		if ( ! empty( $custom_css ) ) {
			wp_add_inline_style( 'sp-real-blocks-style', $custom_css );
		}
	}

	/**
	 * Inline bootstrap that re-runs the block frontend init after a builder re-render.
	 *
	 * Builders swap module markup in over AJAX without a page load, so swiper and the
	 * filters have to be re-initialised. Each builder passes its own wrapper class and
	 * event names; the MutationObserver is the fallback for builders that fire nothing
	 * useful. Written without jQuery because `sp-real-blocks-frontend` does not depend
	 * on it, and the builder editor is not guaranteed to load it.
	 *
	 * @since 4.2.5
	 *
	 * @param string $wrapper_class Wrapper class the builder renders around the template.
	 * @param array  $events        Document events that signal a re-render.
	 * @return void
	 */
	public static function reinit_script( $wrapper_class, array $events = array() ) {
		self::register();

		$script = sprintf(
			'(function(){
	var target = %1$s;
	var events = %2$s;
	function reinit(){
		if ( typeof window.spRealTestimonialInit === "function" ) {
			window.spRealTestimonialInit();
		}
	}
	events.forEach( function( name ){ document.addEventListener( name, reinit ); } );
	if ( window.jQuery ) {
		events.forEach( function( name ){ window.jQuery( document ).on( name, reinit ); } );
	}
	function matches( node ){
		if ( node.nodeType !== 1 ) { return false; }
		return node.classList.contains( target ) || !! node.querySelector( "." + target );
	}
	var observer = new MutationObserver( function( mutations ){
		for ( var m = 0; m < mutations.length; m++ ) {
			var nodes = mutations[ m ].addedNodes;
			for ( var i = 0; i < nodes.length; i++ ) {
				if ( matches( nodes[ i ] ) ) {
					reinit();
					return;
				}
			}
		}
	} );
	function observe(){
		if ( document.body ) {
			observer.observe( document.body, { childList: true, subtree: true } );
		}
	}
	if ( "loading" === document.readyState ) {
		document.addEventListener( "DOMContentLoaded", observe );
	} else {
		observe();
	}
})();',
			wp_json_encode( $wrapper_class ),
			wp_json_encode( array_values( $events ) )
		);

		wp_add_inline_script( 'sp-real-blocks-frontend', $script );
	}
}
