<?php
/**
 * SavedTemplates Templates.
 *
 * @link http://shapedplugin.com
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 */

namespace ShapedPlugin\TestimonialFree\Admin\Dashboard;

use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Builder_Assets;
use ShapedPlugin\TestimonialFree\Admin\PageBuilders\Base\Template_Css;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SavedTemplates
 */
class SavedTemplates {

	/**
	 * Constructor.
	 *
	 * Initialize hooks for saved templates functionality including CPT registration,
	 * admin redirects, block editor enforcement, and shortcode handling.
	 *
	 * @return void
	 */
	public function __construct() {
		// Register the CPT early enough for REST requests (Gutenberg saves via REST).
		add_action( 'init', array( $this, 'sp_real_register_post_type' ) );
		add_action( 'admin_init', array( $this, 'sp_real_redirect_saved_template_edit_page' ) );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'sp_real_force_block_editor_for_save_templates' ), 100, 2 );
		add_shortcode( 'sp_real_template', array( $this, 'sp_real_saved_template_callback' ) );
	}

	/**
	 * Real Testimonials Saved Template post type.
	 */
	public function sp_real_register_post_type() {
		if ( post_type_exists( 'sp_real_template' ) ) {
			return;
		}
		$show_ui = current_user_can( 'manage_options' ) ? true : false;
		// Set the testimonial saved template post type labels.
		$labels = array(
			'name'                     => __( 'Saved Templates', 'testimonial-free' ),
			'singular_name'            => __( 'Saved Template', 'testimonial-free' ),
			'menu_name'                => __( 'Saved Templates', 'testimonial-free' ),
			'all_items'                => __( 'Saved Templates', 'testimonial-free' ),
			'add_new'                  => __( 'Add New Template', 'testimonial-free' ),
			'add_new_item'             => __( 'Add New Template', 'testimonial-free' ),
			'edit'                     => __( 'Edit', 'testimonial-free' ),
			'edit_item'                => __( 'Edit Template', 'testimonial-free' ),
			'view_item'                => __( 'View Template', 'testimonial-free' ),
			'new_item'                 => __( 'New Templates', 'testimonial-free' ),
			'search_items'             => __( 'Search Template', 'testimonial-free' ),
			'not_found'                => __( 'No Template found', 'testimonial-free' ),
			'not_found_in_trash'       => __( 'No Template found in Trash', 'testimonial-free' ),
			'item_published'           => __( 'Template Published', 'testimonial-free' ),
			'item_published_privately' => __( 'Template published privately.', 'testimonial-free' ),
			'item_reverted_to_draft'   => __( 'Template reverted to draft.', 'testimonial-free' ),
			'item_scheduled'           => __( 'Template scheduled.', 'testimonial-free' ),
			'item_updated'             => __( 'Template updated.', 'testimonial-free' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'supports'            => array( 'title', 'editor', 'revisions' ),
			'show_in_rest'        => true,
			'hierarchical'        => false,
			'rewrite'             => false,
			'show_ui'             => $show_ui,
			'show_in_menu'        => false,
			'show_in_nav_menu'    => true,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'sp_real_template', $args );
	}

	/**
	 * Redirect Saved Template edit page with proper sanitization.
	 *
	 * Sanitizes GET parameters and uses WordPress global $pagenow for robust
	 * admin page detection to prevent CSRF and URL manipulation attacks.
	 *
	 * @return void
	 */
	public function sp_real_redirect_saved_template_edit_page() {
		global $pagenow;

		// If we are on post.php and editing a post.
		if ( isset( $_GET['post'], $_GET['action'] ) && 'edit' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {

			$post_id   = absint( $_GET['post'] );
			$post_type = get_post_type( $post_id );

			// Check custom post type.
			if ( 'sp_real_template' === $post_type ) {
				return;
			}
		}

		// When creating a new post (post-new.php) - use $pagenow for robust check.
		if ( 'post-new.php' === $pagenow ) {
			return; // DO NOT redirect.
		}

		// Redirect default list table edit.php?post_type=sp_real_template.
		if ( isset( $_GET['post_type'] ) && 'sp_real_template' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) ) {

			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			if ( 'rtp_dashboard#saved_templates' !== $page ) {
				wp_safe_redirect( admin_url( 'admin.php?page=rtp_dashboard#saved_templates' ) );
				exit;
			}
		}
	}

	/**
	 * Force to save template via block editor.
	 *
	 * @param bool   $use_block_editor Whether block editor is enabled.
	 * @param string $post_type Post type slug.
	 * @return bool
	 */
	public function sp_real_force_block_editor_for_save_templates( $use_block_editor, $post_type ) {
		if ( 'sp_real_template' === $post_type ) {
			return true;
		}
		return $use_block_editor;
	}

	/**
	 * Ensure CSS file exists for a saved template.
	 *
	 * Uses the same CSS folder structure as DynamicStyle.php for consistency.
	 * Path: {uploads}/testimonial-pro/assets/sp-real-style-{id}.css
	 *
	 * @since 4.2.0
	 *
	 * @param int $template_id Template post ID.
	 * @return bool|string False if file doesn't exist, CSS URL if exists.
	 */
	private function get_template_css_url( $template_id ) {
		$upload_dir = wp_upload_dir();
		$css_file   = trailingslashit( $upload_dir['basedir'] ) . 'testimonial-pro/assets/sp-real-style-' . $template_id . '.css';

		if ( file_exists( $css_file ) ) {
			return trailingslashit( $upload_dir['baseurl'] ) . 'testimonial-pro/assets/sp-real-style-' . $template_id . '.css';
		}
		return false;
	}

	/**
	 * Saved template shortcode callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function sp_real_saved_template_callback( $attributes ) {
		$attributes = shortcode_atts( array( 'id' => '' ), $attributes );
		$id         = $attributes['id'];
		$id         = is_numeric( $id ) ? absint( $id ) : false;

		// Return if id is empty.
		if ( ! $id ) {
			return '';
		}

		// Return if post id or post type is not published.
		$post = get_post( $id );
		if ( ! $post || 'publish' !== $post->post_status ) {
			return '';
		}

		// Return if post content is empty.
		$content = $post->post_content;
		if ( empty( $content ) ) {
			return '';
		}

		$content = do_blocks( $content );
		$content = do_shortcode( $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace( '%<p>&nbsp;\s*</p>%', '', $content );
		$content = preg_replace( '/^(?:<br\s*\/?>\s*)+/', '', $content );
		$content = trim( $content );

		// Enqueue the block assets the template content needs.
		Builder_Assets::enqueue();

		// Page builder modules already print the template CSS next to their markup.
		$css_printed = Template_Css::is_printed( $id );

		// Get and enqueue CSS file.
		$css_url = $css_printed ? false : $this->get_template_css_url( $id );
		if ( $css_url ) {
			// Get unique version for cache busting.
			$sp_rand = get_post_meta( $id, '_sp_real_unique_version', true );
			$sp_rand = ! empty( $sp_rand ) ? $sp_rand : SP_TFREE_VERSION;
			wp_enqueue_style(
				"sp-real-css-{$id}",
				$css_url,
				array(),
				'sp-real-' . $sp_rand
			);

			// Enqueue Google Fonts from post meta.
			$font_lists = get_post_meta( $id, 'sp_real_dynamic_fonts', true );
			if ( ! empty( $font_lists ) && is_array( $font_lists ) ) {
				$font_lists = array_unique( $font_lists );
				if ( ! empty( $font_lists ) ) {
					wp_enqueue_style(
						"sp-tpro-google-fonts-{$id}",
						'https://fonts.googleapis.com/css?family=' . implode( '|', $font_lists ),
						array(),
						SP_TFREE_VERSION
					);
				}
			}
		} elseif ( ! $css_printed ) {
			// Fallback: Generate CSS from blocks when file doesn't exist.
			$this->enqueue_template_fallback_css( $id );
		}

		return $content;
	}

	/**
	 * Fallback: Enqueue CSS from blocks when CSS file doesn't exist.
	 *
	 * This is used when the CSS file hasn't been generated yet.
	 * Uses DynamicStyle class to generate CSS from blocks.
	 *
	 * @since 4.2.0
	 *
	 * @param int $template_id Template post ID.
	 * @return void
	 */
	private function enqueue_template_fallback_css( $template_id ) {
		if ( class_exists( '\ShapedPlugin\TestimonialFree\Blocks\Styles\DynamicStyle' ) ) {
			$dynamic_style  = \ShapedPlugin\TestimonialFree\Blocks\Styles\DynamicStyle::instance();
			$dynamic_assets = $dynamic_style->generate_post_css_file( $template_id );

			if ( false !== $dynamic_assets && is_array( $dynamic_assets ) ) {
				$css   = $dynamic_assets[0] ?? '';
				$fonts = $dynamic_assets[1] ?? array();

				// Enqueue Google Fonts.
				if ( ! empty( $fonts ) && is_array( $fonts ) ) {
					$fonts = array_unique( $fonts );
					wp_enqueue_style(
						"sp-tpro-google-fonts-{$template_id}",
						'https://fonts.googleapis.com/css?family=' . implode( '|', $fonts ) . '&display=swap',
						array(),
						SP_TFREE_VERSION
					);
				}

				// Output dynamic CSS inline on a virtual handle: the shortcode runs while the
				// body renders, and inline styles attached to an already printed handle are dropped.
				if ( ! empty( $css ) ) {
					$style_handle = "sp-real-template-{$template_id}";
					wp_register_style( $style_handle, false, array(), SP_TFREE_VERSION );
					wp_add_inline_style( $style_handle, $css );
					wp_enqueue_style( $style_handle );
				}
			}
		}
	}
}
