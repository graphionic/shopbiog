<?php
/**
 * Abstract Block Base Class
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Abstracts
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Abstracts;

use ShapedPlugin\TestimonialFree\Blocks\Includes\TestimonialQuery;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;
use ShapedPlugin\TestimonialFree\Blocks\Templates\CardTemplate;
use ShapedPlugin\TestimonialFree\Blocks\Includes\SwiperConfig;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Abstract Block Base Class
 *
 * Base class for all Gutenberg blocks. Provides common functionality
 * and enforces consistent block structure across all block types.
 *
 * @since 4.0.0
 */
abstract class BlockBase {

	/**
	 * Block name (slug) without namespace, e.g. "carousel".
	 *
	 * @var string
	 */
	protected $block_name;

	/**
	 * Frontend script handles.
	 *
	 * Stays in PHP because it must be suppressed in the editor, and because
	 * Slider/Carousel append `sp-real-swiper` — neither is expressible in
	 * block.json.
	 *
	 * @var array
	 */
	protected $scripts = array( 'sp-real-blocks-frontend' );

	/**
	 * Frontend style handles.
	 *
	 * Stays in PHP because subclasses extend the list per block (Swiper CSS,
	 * Font Awesome, Fontello). block.json names only the shared base handle.
	 *
	 * @var array
	 */
	protected $styles = array( 'sp-real-blocks-style' );

	/**
	 * Block attributes schema.
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Constructor.
	 *
	 * Initializes the block with attributes and sets up editor context.
	 *
	 * @param array $attributes Optional block attributes to initialize with.
	 */
	public function __construct( $attributes = array() ) {
		$this->attributes = $attributes;
		$this->initialize();
	}

	/**
	 * Initialize and register the block.
	 *
	 * @return void
	 */
	protected function initialize() {
		$this->sp_real_register_block_type();
	}

	/**
	 * Get render callback for the block.
	 *
	 * Can be overridden in child classes to provide custom render logic.
	 *
	 * @return callable|null Render callback function or null.
	 */
	protected function get_render_callback() {
		return array( $this, 'render_callback' );
	}

	/**
	 * Block render callback.
	 *
	 * Should be overridden in child classes for dynamic blocks.
	 * Default behavior returns inner content unchanged.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content Inner block content.
	 * @param array  $blocks Block instances.
	 * @return string Rendered HTML output.
	 */
	public function render_callback( $attributes, $content = '', $blocks = array() ) {
		return $content;
	}

	/**
	 * Register the block with WordPress using metadata.
	 *
	 * Reads block.json from dist directory and registers the block type
	 * with appropriate assets and render callback.
	 *
	 * @return void
	 */
	protected function sp_real_register_block_type() {
		$metadata_path = $this->get_block_metadata_path();

		if ( ! $metadata_path || ! file_exists( $metadata_path . '/block.json' ) || ! function_exists( 'register_block_type_from_metadata' ) ) {
			return;
		}

		// The editor script/style come from block.json, which names the same handle.
		// `script` and `style` stay here: the frontend bundle must be suppressed in
		// the editor, and subclasses extend both lists per block (Swiper, Font
		// Awesome, Fontello) — neither is expressible in metadata.
		$args = array(
			'script'     => BlocksHelper::is_editor_page() ? '' : $this->scripts,
			'style'      => $this->styles,
			'attributes' => $this->attributes,
		);

		$render_callback = $this->get_render_callback();
		if ( is_callable( $render_callback ) ) {
			$args['render_callback'] = $render_callback;
		}

		register_block_type_from_metadata( $metadata_path, $args );
	}

	/**
	 * Get path to folder containing block.json.
	 *
	 * Uses the block name to construct path to dist directory.
	 *
	 * @return string Path to block metadata directory.
	 */
	protected function get_block_metadata_path() {
		return SP_RT_PLUGIN_PATH . 'dist/blocks/' . $this->block_name;
	}

	/**
	 * Query testimonials based on block attributes.
	 *
	 * Uses TestimonialQuery class for shared query logic.
	 *
	 * @param array $attributes Block attributes.
	 * @return \WP_Query Query object.
	 */
	protected function query_testimonials( $attributes ) {
		$args = TestimonialQuery::build_args_from_attributes( $attributes );
		return TestimonialQuery::query( $args );
	}

	/**
	 * Get testimonial data for a post.
	 *
	 * Uses TestimonialQuery class for shared data formatting.
	 *
	 * @param int $post_id Post ID.
	 * @return array Testimonial data.
	 */
	protected function get_testimonial_data( $post_id ) {
		return TestimonialQuery::get_testimonial_data( $post_id );
	}

	/**
	 * Render rows in the static grid layout.
	 *
	 * Mirrors the editor's GridRender — iterate the already-resolved rows and emit
	 * one CardTemplate each. `$items` is an array of testimonial data rows (the
	 * shape returned by get_testimonial_data()).
	 *
	 * @param array $attributes Block attributes.
	 * @param array $items      Resolved testimonial data rows.
	 * @return void
	 */
	protected function render_grid_blocks( $attributes, $items ) {
		?>
		<div class="sp-real-grid-wrapper sp-real-cards-wrapper">
			<?php
			foreach ( (array) $items as $item ) {
				new CardTemplate( $attributes, $item );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render rows in the swiper carousel/slider layout.
	 *
	 * Mirrors the editor's CarouselRender.
	 *
	 * @param array $attributes Block attributes.
	 * @param array $items      Resolved testimonial data rows.
	 * @return void
	 */
	protected function render_carousel_blocks( $attributes, $items ) {
		// Layout attributes needed by the template wrapper.
		$carousel_style    = $attributes['carouselStyle'] ?? 'default';
		$enable_navigation = $attributes['enableNavigationArrow'] ?? true;
		$enable_pagination = $attributes['enablePaginationDots'] ?? true;
		// Build conflict-resolved swiper settings (mirrors editor-side helper).
		$swiper_settings = SwiperConfig::make( $attributes );

		?>
		<div class="sp-testimonial-swiper swiper sp-real-carousel-style-<?php echo esc_attr( $carousel_style ); ?>" data-swiper-settings="<?php echo esc_attr( wp_json_encode( $swiper_settings ) ); ?>">
			<div class="sp-real-swiper-wrapper swiper-wrapper sp-real-cards-wrapper">
				<?php
				foreach ( (array) $items as $item ) {
					new CardTemplate( $attributes, $item );
				}
				?>
			</div>
		</div>
		<?php
		// Navigation Arrow.
		if ( $enable_navigation ) {
			require BlocksHelper::get_template_parts( 'navigation-arrow' );
		}
		// Pagination (scrollbar + all other styles render here, outside .swiper).
		if ( $enable_pagination ) {
			require BlocksHelper::get_template_parts( 'swiper-pagination' );
		}
		?>
		<?php
	}

}
