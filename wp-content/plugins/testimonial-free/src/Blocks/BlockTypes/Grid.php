<?php
/**
 * Grid Block
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
 * Grid Block Class
 *
 * Registers and renders the testimonial grid block.
 * Extends BlockBase for automatic registration and common functionality.
 *
 * @since 4.0.0
 */
class Grid extends RenderTestimonialBlock {

	/**
	 * Block name (slug) without namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'grid';
}
