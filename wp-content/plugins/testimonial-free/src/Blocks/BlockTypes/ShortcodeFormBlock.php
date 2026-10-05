<?php
/**
 * Form Block.
 *
 * Inserts a saved testimonial submission form via the
 * [sp_testimonial_form id="x"] shortcode.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/BlockTypes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\BlockTypes;

use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * ShortcodeFormBlock.
 */
class ShortcodeFormBlock {

	/**
	 * Block name.
	 *
	 * @var string
	 */
	private $block_name = 'sp-testimonial-pro/form';

	/**
	 * Single class instance.
	 *
	 * @var ShortcodeFormBlock
	 */
	private static $instance;

	/**
	 * Get main instance.
	 *
	 * Ensures only one instance exists in memory at any time.
	 *
	 * @static
	 * @return ShortcodeFormBlock The one true instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Initialize hooks.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->is_visible() ) {
			return;
		}
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_assets', array( $this, 'localize_editor_data' ), 20 );
	}

	/**
	 * Check whether this block is enabled in the dashboard visibility settings.
	 *
	 * @return bool
	 */
	private function is_visible() {
		return in_array( $this->block_name, BlocksHelper::sp_real_get_active_block_list(), true );
	}

	/**
	 * Localize the saved form list onto the shared editor bundle.
	 *
	 * @return void
	 */
	public function localize_editor_data() {
		wp_localize_script(
			'sp-real-blocks-editor',
			'sp_testimonial_form',
			array(
				'link'          => esc_url( admin_url( 'edit.php?post_type=spt_testimonial_form' ) ),
				'shortCodeList' => $this->post_list(),
			)
		);
	}

	/**
	 * Get the list of saved testimonial forms.
	 *
	 * @return array
	 */
	public function post_list() {
		$form_shortcodes = get_posts(
			array(
				'post_type'      => 'spt_testimonial_form',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);

		if ( count( $form_shortcodes ) < 1 ) {
			return array();
		}

		return array_map(
			function ( $shortcode ) {
				return (object) array(
					'id'    => absint( $shortcode->ID ),
					'title' => esc_html( $shortcode->post_title ),
				);
			},
			$form_shortcodes
		);
	}

	/**
	 * Register the form block.
	 *
	 * @return void
	 */
	public function register_block() {
		register_block_type(
			$this->block_name,
			array(
				'attributes'      => array(
					'shortcode'          => array(
						'type'    => 'string',
						'default' => '',
					),
					'showInputShortcode' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'preview'            => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'is_admin'           => array(
						'type'    => 'boolean',
						'default' => is_admin(),
					),
					'isAvailableId'      => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'example'         => array(
					'attributes' => array(
						'preview' => true,
					),
				),
				'editor_script'   => array( 'sp-real-blocks-editor' ),
				'editor_style'    => array( 'sp-real-blocks-editor' ),
				'render_callback' => array( $this, 'render_form_shortcode' ),
			)
		);
	}

	/**
	 * Render callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render_form_shortcode( $attributes ) {
		if ( is_admin() ) {
			return;
		}
		$class_name = ! empty( $attributes['className'] ) ? $attributes['className'] : '';
		return '<div class="' . esc_attr( $class_name ) . '">' . do_shortcode( '[sp_testimonial_form id="' . sanitize_text_field( esc_attr( $attributes['shortcode'] ) ) . '"]' ) . '</div>';
	}
}
