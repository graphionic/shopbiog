<?php
/**
 * Real Testimonials TestimonialQuery File.
 *
 * Handles testimonial queries and data fetching for blocks.
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
 * TestimonialQuery Class
 *
 * Provides reusable methods for querying and formatting testimonial data.
 * Used by both BlockBase and ManageAPI to avoid code duplication.
 *
 * @since 4.0.0
 */
class TestimonialQuery {

	/**
	 * Query testimonials based on parameters.
	 *
	 * Compatible with both block attributes and REST API request params.
	 *
	 * @param array $args Query arguments.
	 * @return \WP_Query Query object.
	 */
	public static function query( $args = array() ) {
		$defaults = array(
			'per_page'      => 10,
			'page'          => 1,
			'orderby'       => 'date',
			'order'         => 'desc',
			'offset'        => 0,
			'include'       => array(),
			'exclude'       => array(),
			'enable_random' => false,
			'rating'        => '',
			'search'        => '',
			'post_type'     => 'spt_testimonial',
			'post_status'   => 'publish',
		);

		$args = wp_parse_args( $args, $defaults );

		// Handle random order.
		if ( ! empty( $args['enable_random'] ) ) {
			$args['orderby'] = 'rand';
		}

		// Handle include as comma-separated string (from API).
		if ( ! empty( $args['include'] ) && is_string( $args['include'] ) ) {
			$args['include'] = array_map( 'intval', explode( ',', $args['include'] ) );
		}

		// Handle exclude as comma-separated string (from API).
		if ( ! empty( $args['exclude'] ) && is_string( $args['exclude'] ) ) {
			$args['exclude'] = array_map( 'intval', explode( ',', $args['exclude'] ) );
		}

		// Build WP_Query args.
		$query_args = array(
			'post_type'      => $args['post_type'],
			'posts_per_page' => $args['per_page'],
			'post_status'    => $args['post_status'],
			'paged'          => $args['page'],
			'orderby'        => $args['orderby'],
			'order'          => $args['order'],
			'offset'         => $args['offset'],
		);

		// Include specific posts.
		if ( ! empty( $args['include'] ) && is_array( $args['include'] ) ) {
			$query_args['post__in']       = $args['include'];
			$query_args['posts_per_page'] = count( $args['include'] );
		}

		// Exclude specific posts.
		if ( ! empty( $args['exclude'] ) && is_array( $args['exclude'] ) ) {
			$query_args['post__not_in'] = $args['exclude'];
		}

		// Handle keyword search.
		if ( ! empty( $args['search'] ) ) {
			$query_args['s']              = sanitize_text_field( $args['search'] );
			$query_args['sp_real_search'] = sanitize_text_field( $args['search'] );
		}

		// Handle rating filter - meta query. Accepts a single rating key or an
		// array of keys, matched against the serialized meta with an OR relation.
		if ( ! empty( $args['rating'] ) ) {
			$ratings = is_array( $args['rating'] ) ? $args['rating'] : array( $args['rating'] );
			$ratings = array_values( array_filter( array_map( 'sanitize_text_field', $ratings ) ) );

			if ( ! empty( $ratings ) ) {
				$rating_clause = array( 'relation' => 'OR' );
				foreach ( $ratings as $rating_key ) {
					$rating_clause[] = array(
						'key'     => 'sp_tpro_meta_options',
						'value'   => '"tpro_rating";s:' . strlen( $rating_key ) . ':"' . $rating_key . '"',
						'compare' => 'LIKE',
					);
				}
				$query_args['meta_query'][] = $rating_clause;
			}
		}

		return new \WP_Query( $query_args );
	}

	/**
	 * Build query arguments from block attributes.
	 *
	 * Maps block attribute names to query argument names.
	 *
	 * @param array $attributes Block attributes.
	 * @return array Query arguments.
	 */
	public static function build_args_from_attributes( $attributes ) {
		// Free plugin only supports the "Latest" source; groups / specific /
		// star-rating filtering and random order are Pro-only.
		return array(
			'per_page' => isset( $attributes['limit'] ) ? intval( $attributes['limit'] ) : 10,
			'page'     => isset( $attributes['page'] ) ? intval( $attributes['page'] ) : 1,
			'orderby'  => isset( $attributes['orderBy'] ) ? sanitize_text_field( $attributes['orderBy'] ) : 'date',
			'order'    => isset( $attributes['order'] ) ? sanitize_text_field( $attributes['order'] ) : 'desc',
			'offset'   => isset( $attributes['offset'] ) ? intval( $attributes['offset'] ) : 0,
			'exclude'  => isset( $attributes['excludesItems'] ) && is_array( $attributes['excludesItems'] ) ? self::normalize_option_ids( $attributes['excludesItems'] ) : array(),
		);
	}

	/**
	 * Normalize a MultipleSelect value (array of {value,id} option objects or
	 * raw ids) into a flat array of integer ids.
	 *
	 * @param array $items Option objects or ids.
	 * @return array Integer ids.
	 */
	public static function normalize_option_ids( $items ) {
		if ( ! is_array( $items ) ) {
			return array();
		}
		$ids = array();
		foreach ( $items as $item ) {
			if ( is_array( $item ) ) {
				$ids[] = intval( $item['value'] ?? ( $item['id'] ?? 0 ) );
			} else {
				$ids[] = intval( $item );
			}
		}
		return array_values( array_filter( $ids ) );
	}

	/**
	 * Build query arguments from REST API request.
	 *
	 * Maps REST API request params to query argument names.
	 *
	 * @param array $request REST API request params.
	 * @return array Query arguments.
	 */
	public static function build_args_from_request( $request ) {
		// Free plugin only supports the "Latest" source; groups / specific /
		// star-rating filtering and random order are Pro-only.
		return array(
			'per_page' => isset( $request['per_page'] ) ? intval( $request['per_page'] ) : 10,
			'page'     => isset( $request['page'] ) ? intval( $request['page'] ) : 1,
			'orderby'  => isset( $request['orderby'] ) ? sanitize_text_field( $request['orderby'] ) : 'date',
			'order'    => isset( $request['order'] ) ? sanitize_text_field( $request['order'] ) : 'desc',
			'offset'   => 0,
			'exclude'  => isset( $request['exclude'] ) ? self::csv_to_ids( $request['exclude'] ) : array(),
		);
	}

	/**
	 * Convert a comma-separated id list (or array) to integer ids.
	 *
	 * @param string|array $value CSV string or array.
	 * @return array Integer ids.
	 */
	public static function csv_to_ids( $value ) {
		if ( is_array( $value ) ) {
			return self::normalize_option_ids( $value );
		}
		if ( '' === (string) $value ) {
			return array();
		}
		return array_values( array_filter( array_map( 'intval', explode( ',', (string) $value ) ) ) );
	}

	/**
	 * Get testimonial data for a post.
	 *
	 * Maps post meta to the format expected by the card template.
	 * Matches the structure returned by the REST API.
	 *
	 * @param int|\WP_Post $post Post ID or post object.
	 * @return array Testimonial data.
	 */
	public static function get_testimonial_data( $post ) {
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		if ( ! $post || 'spt_testimonial' !== $post->post_type ) {
			return array();
		}

		$post_id = $post->ID;

		// Date is kept for schema markup + the review modal; use the site format.
		$formatted_date = get_the_date( get_option( 'date_format' ), $post_id );
		$meta_data      = get_post_meta( $post_id, 'sp_tpro_meta_options', true );
		$meta_data      = is_array( $meta_data ) ? $meta_data : array();

		// Get thumbnail.
		$thumbnail_id = get_post_thumbnail_id( $post_id );

		// Get all image sizes.
		$image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large', 'full', '1536x1536', '2048x2048' );
		$thumbnails  = array();
		foreach ( $image_sizes as $size ) {
			$thumbnails[ $size ] = wp_get_attachment_image_url( $thumbnail_id, $size );
		}

		return array(
			// Standard WordPress post fields.
			'id'              => $post_id,
			'title'           => get_the_title( $post ),
			'content'         => apply_filters( 'the_content', $post->post_content ),
			'excerpt'         => get_the_excerpt( $post ),
			'slug'            => $post->post_name,
			'status'          => $post->post_status,
			'date'            => $formatted_date,
			'modified'        => get_post_modified_time( 'Y-m-d H:i:s', false, $post_id ),
			'modified_gmt'    => get_post_modified_time( 'Y-m-d H:i:s', true, $post_id ),
			'author'          => get_post_field( 'post_author', $post_id ),
			'author_name'     => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			'parent'          => $post->post_parent,
			'menu_order'      => $post->menu_order,
			'comment_status'  => $post->comment_status,
			'ping_status'     => $post->ping_status,
			// Images.
			'thumbnail_id'    => $thumbnail_id,
			'thumbnails'      => $thumbnails,
			'thumbnail'       => $thumbnails['full'] ?? '',
			// Client info from meta.
			'name'            => isset( $meta_data['tpro_name'] ) ? $meta_data['tpro_name'] : '',
			'email'           => isset( $meta_data['tpro_email'] ) ? $meta_data['tpro_email'] : '',
			'position'        => isset( $meta_data['tpro_designation'] ) ? $meta_data['tpro_designation'] : '',
			'company'         => isset( $meta_data['tpro_company_name'] ) ? $meta_data['tpro_company_name'] : '',
			'phone'           => isset( $meta_data['tpro_phone'] ) ? $meta_data['tpro_phone'] : '',
			'website'         => isset( $meta_data['tpro_website'] ) ? $meta_data['tpro_website'] : '',
			'video_url'       => isset( $meta_data['tpro_video_url'] ) ? $meta_data['tpro_video_url'] : '',
			'rating'          => self::get_rating_value( $meta_data['tpro_rating'] ?? '' ),
			'rating_raw'      => isset( $meta_data['tpro_rating'] ) ? $meta_data['tpro_rating'] : '',
			'client_checkbox' => isset( $meta_data['tpro_client_checkbox'] ) ? $meta_data['tpro_client_checkbox'] : '',
			'form_id'         => isset( $meta_data['tpro_form_id'] ) ? $meta_data['tpro_form_id'] : '',
			// Legacy meta keys.
			'rating_legacy'   => get_post_meta( $post_id, 'sp_testimonial_rating', true ),
			'position_legacy' => get_post_meta( $post_id, 'sp_testimonial_position', true ),
			'company_legacy'  => get_post_meta( $post_id, 'sp_testimonial_company', true ),
			'email_legacy'    => get_post_meta( $post_id, 'sp_testimonial_email', true ),
			'website_legacy'  => get_post_meta( $post_id, 'sp_testimonial_website', true ),
			// All raw meta data.
			'meta'            => $meta_data,
		);
	}

	/**
	 * Convert rating string to numeric value.
	 *
	 * @param string $rating_string Rating string (e.g., 'five_star').
	 * @return float Numeric rating value.
	 */
	public static function get_rating_value( $rating_string ) {
		$rating_map = array(
			'five_star'  => 5,
			'four_star'  => 4,
			'three_star' => 3,
			'two_star'   => 2,
			'one_star'   => 1,
		);

		return isset( $rating_map[ $rating_string ] ) ? $rating_map[ $rating_string ] : 5;
	}
}
