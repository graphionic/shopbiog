<?php
/**
 * Card Template Renderer.
 *
 * Renders the testimonial card markup by orchestrating the per-slot
 * template parts. Mirrors the editor's React TestimonialCard component
 * so the frontend and editor produce identical markup.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Templates
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Templates;

use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;
use ShapedPlugin\TestimonialFree\Blocks\Includes\SchemaMarkup;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Renders a single testimonial card from block attributes + testimonial data.
 */
class CardTemplate {

	/**
	 * Block attributes.
	 *
	 * @var array
	 */
	private $attributes;

	/**
	 * Resolved testimonial data passed to template parts.
	 *
	 * @var array
	 */
	private $testimonial_data;

	/**
	 * Block name (carousel, slider or grid).
	 *
	 * @var string
	 */
	private $block_name;

	/**
	 * Card design slug.
	 *
	 * @var string
	 */
	private $card_design;

	/**
	 * Ordered list of card content slot configs.
	 *
	 * @var array
	 */
	private $card_contents;

	/**
	 * Card content alignment.
	 *
	 * @var string
	 */
	private $card_alignment;

	/**
	 * Hover effect slug.
	 *
	 * @var string
	 */
	private $card_hover_effect;

	/**
	 * Constructor.
	 *
	 * @param array $attributes       Block attributes.
	 * @param array $testimonial_data Testimonial data resolved by TestimonialQuery.
	 */
	public function __construct( array $attributes, array $testimonial_data ) {
		$this->attributes       = $attributes;
		$this->testimonial_data = $testimonial_data;
		$this->block_name       = $attributes['blockName'] ?? '';

		$this->card_design       = $attributes['cardDesign'] ?? 'design-one';
		$this->card_contents     = $attributes['cardContents'] ?? array();
		$this->card_alignment    = $attributes['cardAlignment'] ?? 'center';
		$this->card_hover_effect = $attributes['cardHoverEffect'] ?? 'none';

		// Collect this testimonial for the page's JSON-LD schema when enabled.
		if ( ! empty( $attributes['enableSEOSchemaMarkup'] ) ) {
			SchemaMarkup::collect( $testimonial_data );
		}

		// call card renderer.
		$this->render();
	}

	/**
	 * Render the card markup to the output buffer.
	 */
	public function render() {
		$wrapper_class = $this->wrapper_class();
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div class="sp-real-card-inner <?php echo esc_attr( ( ( 'sp-align-' . $this->card_alignment ) . ( ' sp-text-' . $this->card_alignment ) ) ); ?>">
				<?php $this->render_slots( $this->card_contents ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Iterate slots, dispatching each to its renderer.
	 *
	 * @param array $items Slot config list.
	 */
	private function render_slots( $items ) {
		foreach ( (array) $items as $item ) {
			$name = $item['name'] ?? '';
			if ( empty( $item['is_active'] ) || '' === $name ) {
				continue;
			}

			$this->render_slot( $name );
		}
	}

	/**
	 * Render a single slot if its data prerequisites are met.
	 *
	 * @param string $name Slot identifier.
	 */
	private function render_slot( $name ) {
		$d = $this->testimonial_data;

		switch ( $name ) {
			case 'reviewer_image':
				if ( ! empty( $d['thumbnail'] ) || $this->reviewer_fallback_active() ) {
					$this->include_part( 'client-image' );
				}
				break;

			case 'testimonial_title':
				if ( ! empty( $d['title'] ) ) {
					$this->include_part( 'client-title' );
				}
				break;

			case 'testimonial_text':
				if ( ! empty( $d['content'] ) ) {
					$this->include_part( 'testimonial-content' );
				}
				break;

			case 'rating':
				$this->include_part( 'client-rating' );
				break;

			case 'reviewer_name':
				if ( ! empty( $d['name'] ) ) {
					$this->include_part( 'client-name' );
				}
				break;

			case 'designation':
				if ( ! empty( $d['position'] ) || ! empty( $d['company'] ) ) {
					$this->include_part( 'client-designation' );
				}
				break;
		}
	}

	/**
	 * Compose the outer wrapper class string.
	 *
	 * @return string
	 */
	private function wrapper_class() {
		// check is layout is carousel or not.
		$is_carousel_block = in_array( $this->block_name, array( 'carousel', 'slider' ), true );
		// build class.
		$classes = array(
			'sp-real-testimonial-card',
			'sp-real-card-' . $this->card_design,
			'none' !== $this->card_hover_effect ? 'sp-real-card-effect-' . $this->card_hover_effect : '',
			$is_carousel_block ? 'sp-real-swiper-slide swiper-slide' : '',
		);

		return BlocksHelper::class_list( $classes );
	}

	/**
	 * Whether a reviewer-image fallback should render when no thumbnail exists.
	 *
	 * @return bool
	 */
	private function reviewer_fallback_active() {
		// Reviewer image fallbacks (mystery/smart-avatar/gravatar/custom) are Pro;
		// free only supports "No Fallback Image", so a fallback never renders.
		return false;
	}

	/**
	 * Include a template-parts file, exposing the variables it needs.
	 *
	 * @param string $name Template part slug.
	 */
	private function include_part( $name ) {
		$file = BlocksHelper::get_template_parts( $name );
		if ( false === $file ) {
			return;
		}

		// Variables in scope for the included template.
		$attributes       = $this->attributes;
		$testimonial_data = $this->testimonial_data;
		$card_design      = $this->card_design;

		require $file; // phpcs:ignore WordPress.PHP.IncludingFile.UsingVariable -- Path returned by trusted helper.
	}
}
