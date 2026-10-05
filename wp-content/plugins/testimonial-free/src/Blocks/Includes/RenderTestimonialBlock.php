<?php
/**
 * Render testimonial block file.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Includes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Includes;

use ShapedPlugin\TestimonialFree\Blocks\Abstracts\BlockBase;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * RenderTestimonialBlock.
 */
class RenderTestimonialBlock extends BlockBase {
	/**
	 * Frontend style handles.
	 *
	 * @var array
	 */
	protected $styles = array( 'sp-real-blocks-style', 'tfree-font-awesome', 'tpro-block-fontello' );

	/**
	 * Frontend script handles.
	 *
	 * @var array
	 */
	protected $scripts = array( 'sp-real-blocks-frontend' );

	/**
	 * Render testimonial grid block on frontend.
	 *
	 * Returns placeholder content in editor, renders actual grid on frontend.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content Inner block content.
	 * @param array  $blocks Inner block instances.
	 * @return string Rendered HTML output.
	 */
	public function render_callback( $attributes, $content = '', $blocks = array() ) {
		if ( BlocksHelper::is_editor_page() ) {
			return $content;
		}
		// return if card design is empty.
		$card_design = $attributes['cardDesign'] ?? '';
		if ( empty( $card_design ) || ! $card_design ) {
			return;
		}
		// return if testimonial not found.
		$query = $this->query_testimonials( $attributes );
		if ( ! $query->have_posts() ) {
			return '<p>' . esc_html__( 'No testimonials found.', 'testimonial-free' ) . '</p>';
		}

		$total_posts = (int) $query->found_posts;

		// Resolve every post into the shared row shape once, then hand off to the
		// layout helpers in BlockBase (same array contract review blocks use).
		$items = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = $this->get_testimonial_data( get_the_ID() );
		}
		wp_reset_postdata();

		// Extract attributes.
		$align             = $attributes['align'] ?? 'wide';
		$block_name        = $attributes['blockName'] ?? 'grid';
		$template          = $attributes['template'] ?? 'template-one';
		$unique_id         = $attributes['uniqueId'] ?? '';
		$post_limit        = $attributes['limit'] ?? 6;
		$custom_class_name = $attributes['customClassName'] ?? '';
		$custom_id_name    = $attributes['customIdName'] ?? '';

		ob_start();
		?>
		<div
			class="sp-real-testimonial-block align<?php echo esc_attr( $align . ( $custom_class_name ? " $custom_class_name" : '' ) ); ?>"
			<?php
			if ( $custom_id_name ) {
				echo 'id="' . esc_attr( $custom_id_name ) . '"';
			}
			?>
		>
			<div id="<?php echo esc_attr( $unique_id ); ?>" class="sp-real-<?php echo esc_attr( $block_name ); ?> sp-real-block-frontend" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>" data-total-posts="<?php echo esc_attr( $total_posts ); ?>" data-limit="<?php echo esc_attr( $post_limit ); ?>">
				<div class="sp-real-<?php echo esc_attr( $block_name . '-' . $template ); ?> sp-real-template-wrapper">
				<?php
				if ( in_array( $block_name, array( 'carousel', 'slider' ), true ) ) {
					$this->render_carousel_blocks( $attributes, $items );
				} else {
					$this->render_grid_blocks( $attributes, $items );
				}
				?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
