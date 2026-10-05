<?php
/**
 * Dynamic Css Renderer File.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Styles
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Styles;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * DynamicCssGenerator
 */
class DynamicCssGenerator {
	/**
	 * Attributes.
	 *
	 * @var array
	 */
	public $attributes = array();

	/**
	 * Method __construct
	 *
	 * @param array $attributes is block attributes.
	 *
	 * @return void
	 */
	public function __construct( $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * Method sp_real_generate_dynamic_css
	 *
	 * @return array
	 */
	public function sp_real_generate_dynamic_css() {
		$block_name = $this->attributes['blockName'] ?? '';

		$css_object      = array();
		$shared_css      = new SharedCss( $this->attributes );
		$child_block_css = new ChildBlocksCss( $this->attributes );

		switch ( $block_name ) {
			case 'carousel':
			case 'slider':
			case 'grid':
				$css_object = $shared_css->block_shared_css();
				break;
			case 'ajax-pagination':
				$css_object = $child_block_css->ajax_pagination_block_css();
				break;
			case 'testimonial-submission-form':
				$tsf_css    = new TestimonialFormCss( $this->attributes );
				$css_object = $tsf_css->submission_form_block_css();
				break;
			default:
				// code...
				break;
		}
		$css_str = CssHelpers::filter_responsive_dynamic_css( $css_object );
		return $css_str;
	}
}
