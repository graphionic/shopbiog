<?php
/**
 * Real Testimonials Schema Markup.
 *
 * Collects testimonials rendered by blocks that have "SEO Schema Markup"
 * enabled and prints a single JSON-LD graph in the footer (one Organization
 * node with aggregateRating + nested Review items).
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/Includes
 */

namespace ShapedPlugin\TestimonialFree\Blocks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * SchemaMarkup Class.
 *
 * @since 4.0.0
 */
class SchemaMarkup {

	/**
	 * Collected reviews, keyed by testimonial id to de-duplicate across blocks.
	 *
	 * @var array
	 */
	private static $reviews = array();

	/**
	 * Whether the footer output hook has been registered.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Collect one testimonial for the page schema.
	 *
	 * Called from CardTemplate when the block has SEO schema markup enabled.
	 *
	 * @param array $testimonial_data Testimonial data resolved by TestimonialQuery.
	 * @return void
	 */
	public static function collect( array $testimonial_data ) {
		// Gated by the "SEO Schema Markup" dashboard module.
		if ( ! BlocksHelper::is_active_module( 'seo_schema_markup' ) ) {
			return;
		}

		$body = isset( $testimonial_data['content'] ) ? trim( wp_strip_all_tags( $testimonial_data['content'] ) ) : '';
		if ( '' === $body ) {
			return;
		}

		$id  = (string) ( $testimonial_data['id'] ?? '' );
		$key = '' !== $id ? $id : md5( $body );

		self::$reviews[ $key ] = array(
			'name'   => isset( $testimonial_data['name'] ) ? wp_strip_all_tags( (string) $testimonial_data['name'] ) : '',
			'rating' => (int) ( $testimonial_data['rating'] ?? 0 ),
			'body'   => $body,
			'date'   => (string) ( $testimonial_data['date_raw'] ?? ( $testimonial_data['date'] ?? '' ) ),
		);

		if ( ! self::$hooked ) {
			add_action( 'wp_footer', array( __CLASS__, 'render' ), 99 );
			self::$hooked = true;
		}
	}

	/**
	 * Print the JSON-LD schema in the footer.
	 *
	 * @return void
	 */
	public static function render() {
		$reviews = array_values( self::$reviews );
		if ( empty( $reviews ) ) {
			return;
		}

		$review_nodes = array();
		$rating_sum   = 0;
		$rated_count  = 0;

		foreach ( $reviews as $review ) {
			$node = array(
				'@type'      => 'Review',
				'author'     => array(
					'@type' => 'Person',
					'name'  => '' !== $review['name'] ? $review['name'] : __( 'Anonymous', 'testimonial-free' ),
				),
				'reviewBody' => $review['body'],
			);

			if ( $review['rating'] > 0 ) {
				$node['reviewRating'] = array(
					'@type'       => 'Rating',
					'ratingValue' => $review['rating'],
					'bestRating'  => 5,
					'worstRating' => 1,
				);
				$rating_sum          += $review['rating'];
				++$rated_count;
			}

			if ( '' !== $review['date'] ) {
				$timestamp = strtotime( $review['date'] );
				if ( $timestamp ) {
					$node['datePublished'] = gmdate( 'Y-m-d', $timestamp );
				}
			}

			$review_nodes[] = $node;
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
			'review'   => $review_nodes,
		);

		if ( $rated_count > 0 ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => round( $rating_sum / $rated_count, 1 ),
				'reviewCount' => $rated_count,
				'bestRating'  => 5,
				'worstRating' => 1,
			);
		}

		// JSON_HEX_TAG/AMP/APOS/QUOT keep the payload safe inside <script>.
		$json = wp_json_encode(
			$schema,
			JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
		);

		echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded, tag-safe flags applied.
	}
}
