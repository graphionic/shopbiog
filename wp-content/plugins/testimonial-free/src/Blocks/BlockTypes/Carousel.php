<?php
/**
 * Carousel Block
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
 * Carousel Block Class
 *
 * Registers and renders the testimonial carousel block.
 * Extends BlockBase for automatic registration and common functionality.
 *
 * @since 4.0.0
 */
class Carousel extends RenderTestimonialBlock {

	/**
	 * Block name (slug) without namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'carousel';

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
