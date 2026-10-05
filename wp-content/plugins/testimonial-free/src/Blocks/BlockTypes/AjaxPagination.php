<?php
/**
 * Ajax Pagination Block
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/BlockTypes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\BlockTypes;

use ShapedPlugin\TestimonialFree\Blocks\Abstracts\BlockBase;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax Pagination Block Class
 *
 * Child block of testimonial-group. Renders pagination controls (load-more or
 * numbered) for testimonials with optional prev/next arrows.
 *
 * @since 4.0.0
 */
class AjaxPagination extends BlockBase {

	/**
	 * Block name (slug) without namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'ajax-pagination';

	/**
	 * Render Ajax Pagination block on the frontend.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Inner block content.
	 * @param array  $blocks     Inner blocks.
	 * @return string Rendered HTML output.
	 */
	public function render_callback( $attributes, $content = '', $blocks = array() ) {
		if ( BlocksHelper::is_editor_page() ) {
			return $content;
		}

		$unique_id              = $attributes['uniqueId'] ?? '';
		$custom_id_name         = $attributes['customIdName'] ?? '';
		$custom_class_name      = $attributes['customClassName'] ?? '';
		$pagination_type        = $attributes['paginationType'] ?? 'normal';
		$pagination_button_type = $attributes['paginationButtonType'] ?? 'load-more';
		$load_more_label        = $attributes['loadMoreLabel'] ?? __( 'Load More', 'testimonial-free' );
		$pagination_alignment   = $attributes['paginationAlignment'] ?? 'center';
		$pagination_number_type = $attributes['paginationNumberType'] ?? 'number';
		$pagination_prev_label  = $attributes['paginationPrevLabel'] ?? __( 'Prev', 'testimonial-free' );
		$pagination_next_label  = $attributes['paginationNextLabel'] ?? __( 'Next', 'testimonial-free' );
		$pagination_shorten     = ! empty( $attributes['paginationShorten'] );
		$items_per_page         = $attributes['itemPerPage'] ?? 4;
		$ending_message         = $attributes['endingMessage'] ?? __( 'No more testimonial', 'testimonial-free' );

		$has_arrows  = in_array( $pagination_number_type, array( 'number-arrow', 'number-prev-next-arrow', 'prev-next' ), true );
		$show_labels = in_array( $pagination_number_type, array( 'number-prev-next-arrow', 'prev-next' ), true );

		$pagination_settings_data = array(
			'itemsPerPage'         => $items_per_page,
			'paginationType'       => $pagination_type,
			'paginationButtonType' => $pagination_button_type,
			'paginationNumberType' => $pagination_number_type,
		);

		ob_start();
		?>
		<div id="<?php echo esc_attr( $unique_id ); ?>" class="<?php echo esc_attr( trim( 'sp-real-ajax-pagination ' . ( $custom_class_name ? ' ' . $custom_class_name : '' ) ) ); ?>">
			<div class="sp-real-pagination-wrapper sp-d-flex sp-justify-<?php echo esc_attr( $pagination_alignment ); ?>" data-pagination="<?php echo esc_attr( wp_json_encode( $pagination_settings_data ) ); ?>">
				<?php if ( 'load-more' === $pagination_button_type ) { ?>
					<div class="sp-real-load-more-button">
						<a href="#" class="sp-real-pagination-item" data-page="1">
							<span class="sp-real-load-more-spinner" aria-hidden="true"></span>
							<span class="sp-real-pagination-text"><?php echo esc_html( $load_more_label ); ?></span>
						</a>
						<span class='sp-real-pagination-ending sp-d-hidden'>
							<?php echo esc_html( $ending_message ); ?>
						</span>
					</div>
				<?php } ?>
				<?php if ( 'number' === $pagination_button_type ) { ?>
					<div class="sp-real-pagination-buttons sp-d-flex" data-page="1">
						<?php
						if ( $has_arrows ) {
							?>
								<!-- prev button -->
								<a href="#" class="sp-real-pagination-item next-prev-button prev-button sp-d-flex sp-align-center sp-gap-2px sp-d-disabled">
									<span class="sp-d-block sp-real-pagination-prev-icon">
										<svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
											<path d="M3.7 1 2.3 2.4 4.9 5 2.3 7.6 3.7 9l4-4z"/>
										</svg>
									</span>
									<?php
									if ( $show_labels ) {
										echo esc_html( $pagination_prev_label );
									}
									?>
								</a>
								<!-- next button -->
								<a href="#" class="sp-real-pagination-item next-prev-button next-button sp-d-flex sp-align-center sp-gap-2px">
									<?php
									if ( $show_labels ) {
										echo esc_html( $pagination_next_label );
									}
									?>
									<span class="sp-d-block sp-real-pagination-next-icon">
										<svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
											<path d="M3.7 1 2.3 2.4 4.9 5 2.3 7.6 3.7 9l4-4z"/>
										</svg>
									</span>
								</a>
							<?php
						}
						?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
