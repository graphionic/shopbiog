<?php
/**
 * Functions file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free.
 * @subpackage Testimonial_free/includes.
 */

namespace ShapedPlugin\TestimonialFree\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Functions
 */
class TFREE_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'sp_tfree_change_default_post_update_message' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
		add_filter( 'update_footer', array( $this, 'sprt_admin_footer_version' ), 11 );
		// Post thumbnails.
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'tf-client-image-size', 120, 120, true );
	}

	/**
	 * Post update messages for Shortcode Generator
	 *
	 * @param string $message post update message.
	 */
	public function sp_tfree_change_default_post_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'spt_shortcodes' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'View updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'View updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'View published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'View submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'View draft updated.', 'testimonial-free' );
		} elseif ( 'spt_testimonial' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Testimonial updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'Testimonial updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'Testimonial published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'Testimonial submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'Testimonial draft updated.', 'testimonial-free' );
		} elseif ( 'spt_testimonial_form' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Form updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'Form updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'Form published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'Form submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'Form draft updated.', 'testimonial-free' );
		}

		return $message;
	}

	/**
	 * Review Text - Footer with social links and team info.
	 *
	 * @param string $text text.
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'spt_testimonial' === get_post_type() || 'spt_shortcodes' === $screen->post_type || 'spt_testimonial_form' === $screen->post_type || 'spt_testimonial_page_rtp_dashboard' === $screen->base ) {
			$heart_icon = '<svg class="sp-real-footer-heart" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="#E25555"/>
			</svg>';

			$social_icons = array(
				'linkedin'  => array(
					'url'  => 'https://www.linkedin.com/company/shapedplugin',
					'icon' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 0c.563 0 1 .469 1 1v12c0 .563-.437 1-1 1H1c-.562 0-1-.437-1-1V1c0-.531.438-1 1-1zM4.219 12h.031V5.313H2.156V12zM3.187 2C2.532 2 2 2.531 2 3.219c0 .656.531 1.187 1.188 1.187s1.218-.531 1.218-1.187A1.22 1.22 0 0 0 3.188 2M12 12V8.344c0-1.813-.375-3.188-2.469-3.188-1.031 0-1.687.563-1.969 1.063h-.03v-.907h-2V12h2.093V8.688c0-.876.156-1.72 1.219-1.72 1.062 0 1.094 1 1.094 1.782V12z" fill="#757575"/></svg>',
				),
				'twitter'   => array(
					'url'  => 'https://www.x.com/shapedplugin/',
					'icon' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 0h10c1.094 0 2 .906 2 2v10c0 1.094-.906 2-2 2H2c-1.094 0-2-.906-2-2V2C0 .906.906 0 2 0m9.281 2.625H9.812L7.345 5.438 5.25 2.625H2.188l3.656 4.781-3.469 3.969h1.469L6.53 8.313l2.344 3.062h2.969L8.03 6.344zM10.094 10.5H9.28L3.906 3.469h.875z" fill="#757575"/></svg>',
				),
				'wordpress' => array(
					'url'  => 'https://profiles.wordpress.org/shapedplugin/#content-plugins',
					'icon' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.688 5.031a6.6 6.6 0 0 0-.594 2.719 6.62 6.62 0 0 0 3.75 5.969zM12.25 7.406c0 .594-.219 1.25-.5 2.157l-.687 2.218-2.376-7.156c.376 0 .75-.062.75-.062.344-.032.313-.563-.062-.532 0 0-1.062.063-1.75.063-.656 0-1.75-.063-1.75-.063-.375-.031-.406.532-.062.532 0 0 .343.062.718.062l1.032 2.844-1.47 4.375-2.405-7.219c.406 0 .75-.062.75-.062.343-.032.312-.563-.032-.532 0 0-1.093.063-1.781.063h-.437a6.65 6.65 0 0 1 5.562-3c1.719 0 3.313.656 4.5 1.75h-.094c-.656 0-1.125.562-1.125 1.187 0 .532.313 1 .656 1.563.25.437.563 1 .563 1.812m-4.375.938 2.031 5.594c.031.03.031.062.063.093a6.5 6.5 0 0 1-2.219.375 6.2 6.2 0 0 1-1.875-.281zm5.719-3.781c.5.937.812 2.03.812 3.187 0 2.469-1.344 4.594-3.312 5.75l2.031-5.875c.375-.937.5-1.719.5-2.375 0-.25 0-.469-.031-.687M0 7.75a7.75 7.75 0 0 0 7.75 7.75 7.75 7.75 0 0 0 7.75-7.75A7.75 7.75 0 0 0 7.75 0 7.75 7.75 0 0 0 0 7.75m15.156 0a7.4 7.4 0 0 1-7.406 7.406A7.4 7.4 0 0 1 .344 7.75 7.4 7.4 0 0 1 7.75.344a7.4 7.4 0 0 1 7.406 7.406" fill="#757575"/></svg>',
				),
				'facebook'  => array(
					'url'  => 'https://www.facebook.com/shapedplugin/',
					'icon' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 0h10c1.094 0 2 .906 2 2v10c0 1.094-.906 2-2 2H7.969V9.438h2.156L10.594 7H7.969v-.875c0-1.281.5-1.781 1.812-1.781.406 0 .75 0 .938.031V2.156c-.375-.094-1.25-.187-1.75-.187-2.656 0-3.906 1.25-3.906 3.968V7H3.406v2.438h1.656V14H2c-1.094 0-2-.906-2-2V2C0 .906.906 0 2 0" fill="#757575"/></svg>',
				),
				'youtube'   => array(
					'url'  => 'https://www.youtube.com/@shapedplugin',
					'icon' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m8.813 7-2.97 1.688V5.312zM12 0c1.094 0 2 .906 2 2v10c0 1.094-.906 2-2 2H2c-1.094 0-2-.906-2-2V2C0 .906.906 0 2 0zm.438 4.25c-.126-.5-.5-.875-1-1C10.562 3 7 3 7 3s-3.562 0-4.437.25c-.5.125-.876.5-1 1-.25.906-.25 2.75-.25 2.75s0 1.875.25 2.781c.125.469.5.844 1 .969C3.437 11 7 11 7 11s3.563 0 4.438-.25c.5-.125.874-.5 1-1 .25-.875.25-2.75.25-2.75s0-1.844-.25-2.75" fill="#757575"/></svg>',
				),
			);

			$social_html = '';
			foreach ( $social_icons as $platform => $data ) {
				$social_html .= sprintf(
					'<a href="%s" target="_blank" rel="noopener noreferrer" class="sp-real-footer-social-link" title="%s">%s</a>',
					esc_url( $data['url'] ),
					esc_attr( ucfirst( $platform ) ),
					$data['icon']
				);
			}

			$text = sprintf(
				'<div class="sp-real-footer-container">
					<div class="sp-real-footer-left">
						<span class="sp-real-footer-made-with">%s</span>
						%s
						<span class="sp-real-footer-by">%s</span>
						<a href="https://shapedplugin.com/about-us/" target="_blank" rel="noopener noreferrer" class="sp-real-footer-team-link">ShapedPlugin LLC Team</a>
					</div>
					<div class="sp-real-footer-social">%s %s</div>
				</div>',
				esc_html__( 'Made with', 'testimonial-free' ),
				$heart_icon,
				esc_html__( 'by the', 'testimonial-free' ),
				esc_html__( 'Get Connected with', 'testimonial-free' ),
				$social_html
			);
		}

		return $text;
	}
	/**
	 * Version Text
	 *
	 * @param string $text Footer version text.
	 *
	 * @return string
	 */
	public function sprt_admin_footer_version( $text ) {
		$screen = get_current_screen();
		if ( 'spt_testimonial' === $screen->post_type || 'spt_testimonial_form' === $screen->post_type ) {
			$text = 'Real Testimonials ' . SP_TFREE_VERSION;
		}
		return $text;
	}
}
