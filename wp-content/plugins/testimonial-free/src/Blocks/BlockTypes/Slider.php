<?php
/**
 * Slider Block
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/BlockTypes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\BlockTypes;

use ShapedPlugin\TestimonialFree\Blocks\Includes\RenderTestimonialBlock;

defined( 'ABSPATH' ) || exit;

/**
 * Slider Block Class
 *
 * Registers and renders the testimonial slider block. Extended version of the
 * carousel that shows testimonials one-by-one with additional sliding effects
 * (slide, flip, cube). Reuses the carousel swiper
 * pipeline and CardTemplate.
 * Extends BlockBase for automatic registration and common functionality.
 *
 * @since 4.0.0
 */
class Slider extends RenderTestimonialBlock {

	/**
	 * Block name (slug) without namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'slider';

	/**
	 * Frontend style handles.
	 *
	 * @var array
	 */
	protected $styles = array( 'sp-real-blocks-style', 'sp-real-swiper', 'tfree-font-awesome', 'tpro-block-fontello' );

	/**
	 * Frontend script handles.
	 *
	 * @var array
	 */
	protected $scripts = array( 'sp-real-swiper', 'sp-real-blocks-frontend' );
}
