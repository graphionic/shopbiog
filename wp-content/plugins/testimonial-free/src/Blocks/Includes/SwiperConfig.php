<?php
/**
 * Swiper Config Builder
 *
 * Resolves conflicts between carouselStyle, slidingEffect, columns and
 * carouselDirection. Mirrors the editor useSwiperConfig hook so editor and
 * frontend stay in sync.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Includes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Includes;

defined( 'ABSPATH' ) || exit;

/**
 * SwiperConfig builder.
 */
class SwiperConfig {

	/**
	 * Block attributes.
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Resolved sliding effect, coerced against carouselStyle.
	 *
	 * @var string
	 */
	protected $effect;

	/**
	 * Constructor.
	 *
	 * @param array $attributes Block attributes.
	 */
	public function __construct( array $attributes ) {
		$this->attributes = $attributes;
		$this->effect     = self::resolve_effect(
			$attributes['carouselStyle'] ?? 'default',
			$attributes['slidingEffect'] ?? 'slide'
		);
	}

	/**
	 * Static factory — build and return the swiper config array in one call.
	 *
	 * @param array $attributes Block attributes.
	 * @return array
	 */
	public static function make( array $attributes ) {
		return ( new self( $attributes ) )->build();
	}

	/**
	 * Coerce sliding effect by carousel style. Default style allows any supported
	 * effect (center/coverflow are Pro-only and never reach the free frontend).
	 *
	 * @param string $carousel_style Carousel style.
	 * @param string $sliding_effect Stored sliding effect.
	 * @return string
	 */
	public static function resolve_effect( $carousel_style, $sliding_effect ) {
		return $sliding_effect ? $sliding_effect : 'slide';
	}

	/**
	 * Per-device slidesPerView.
	 *
	 * @param string $device  Device key (Mobile|Tablet|Desktop).
	 * @param array  $columns Responsive columns attribute.
	 * @return int|float
	 */
	protected static function slides_per_view( $device, $columns ) {
		$value = $columns['device'][ $device ] ?? 1;
		$count = is_numeric( $value ) ? (float) $value : 1;
		return ( floor( $count ) === $count ) ? (int) $count : $count;
	}

	/**
	 * Per-device slidesPerGroup, clamped to 1 for grouped effects.
	 *
	 * @param string $device          Device key.
	 * @param array  $slide_to_scroll Responsive slideToScroll attribute.
	 * @return int
	 */
	protected static function slides_per_group( $device, $slide_to_scroll ) {
		$value = $slide_to_scroll['device'][ $device ] ?? 1;
		return is_numeric( $value ) ? (int) $value : 1;
	}

	/**
	 * Per-device spaceBetween, zeroed for grouped effects.
	 *
	 * @param string $device     Device key.
	 * @param array  $column_gap Responsive columnGap attribute.
	 * @return int
	 */
	protected static function space_between( $device, $column_gap ) {
		$value = $column_gap['device'][ $device ] ?? 0;
		return is_numeric( $value ) ? (int) $value : 0;
	}

	/**
	 * Build conflict-resolved swiper config suitable for JSON encoding into
	 * the data-swiper-settings attribute.
	 *
	 * @return array
	 */
	public function build() {
		$attributes          = $this->attributes;
		$effect              = $this->effect;
		$carousel_direction  = $attributes['carouselDirection'] ?? 'ltr';
		$carousel_speed      = $attributes['carouselSpeed']['value'] ?? 600;
		$infinite_loop       = ! empty( $attributes['infiniteLoop'] );
		$slider_autoplay     = ! empty( $attributes['sliderAutoPlay'] );
		$carousel_delay      = $attributes['carouselAutoplayDelay']['value'] ?? 600;
		$pause_on_hover      = ! empty( $attributes['pauseOnHover'] );
		$tab_key_navigation  = ! empty( $attributes['tabAndKeyNavigation'] );
		$mouse_wheel_control = ! empty( $attributes['mouseWheelControl'] );
		$free_scroll_mode    = ! empty( $attributes['freeScrollMode'] );
		$enable_navigation   = $attributes['enableNavigationArrow'] ?? true;
		$enable_pagination   = $attributes['enablePaginationDots'] ?? true;
		$pagination_style    = $attributes['paginationStyle'] ?? '';

		$columns         = $attributes['columns'] ?? array();
		$slide_to_scroll = $attributes['slideToScroll'] ?? array();
		$column_gap      = $attributes['columnGap'] ?? array();
		// Effective swiper direction — carouselDirection applies only under autoplay,
		// so RTL never re-orders/re-aligns a static carousel.
		$swiper_dir = ( $slider_autoplay && 'rtl' === $carousel_direction ) ? 'rtl' : 'ltr';

		return array(
			'effect'              => $effect,
			'direction'           => 'horizontal',
			'rtl'                 => ( 'rtl' === $swiper_dir ),
			'swiperDir'           => $swiper_dir,
			'loop'                => $infinite_loop,
			'grabCursor'          => true,
			'followFinger'        => true,
			'watchOverflow'       => true,
			'a11y'                => array( 'scrollOnFocus' => false ),
			'centeredSlides'      => false,
			'watchSlidesProgress' => 'slide' !== $effect,
			'allowTouchMove'      => true,
			'simulateTouch'       => true,
			'resistance'          => true,
			'resistanceRatio'     => 0.85,
			'speed'               => (int) $carousel_speed,
			'autoplay'            => $slider_autoplay ? array(
				'delay'                => (int) $carousel_delay,
				'pauseOnMouseEnter'    => $pause_on_hover,
				'disableOnInteraction' => false,
				'stopOnLastSlide'      => ! $infinite_loop,
				'reverseDirection'     => ( 'rtl' === $carousel_direction ),
			) : false,
			'keyboard'            => $tab_key_navigation ? array(
				'enabled'        => true,
				'onlyInViewport' => true,
			) : false,
			'mousewheel'          => $mouse_wheel_control ? array(
				// forceToAxis:false so a normal vertical wheel (deltaY) drives a horizontal carousel too.
				'forceToAxis'    => false,
				'sensitivity'    => 1,
				'releaseOnEdges' => false,
			) : false,
			'freeMode'            => $free_scroll_mode ? array(
				'enabled'        => true,
				'sticky'         => false,
				'momentum'       => true,
				'momentumBounce' => true,
			) : false,
			'slidesPerView'       => self::slides_per_view( 'Desktop', $columns ),
			'slidesPerGroup'      => self::slides_per_group( 'Desktop', $slide_to_scroll ),
			'spaceBetween'        => self::space_between( 'Desktop', $column_gap ),
			'breakpoints'         => array(
				0    => array(
					'slidesPerView'  => self::slides_per_view( 'Mobile', $columns ),
					'slidesPerGroup' => self::slides_per_group( 'Mobile', $slide_to_scroll ),
					'spaceBetween'   => self::space_between( 'Mobile', $column_gap ),
				),
				768  => array(
					'slidesPerView'  => self::slides_per_view( 'Tablet', $columns ),
					'slidesPerGroup' => self::slides_per_group( 'Tablet', $slide_to_scroll ),
					'spaceBetween'   => self::space_between( 'Tablet', $column_gap ),
				),
				1024 => array(
					'slidesPerView'  => self::slides_per_view( 'Desktop', $columns ),
					'slidesPerGroup' => self::slides_per_group( 'Desktop', $slide_to_scroll ),
					'spaceBetween'   => self::space_between( 'Desktop', $column_gap ),
				),
			),
			'navigation'          => $enable_navigation,
			'pagination'          => $enable_pagination,
			'paginationStyle'     => $pagination_style,
		);
	}
}
