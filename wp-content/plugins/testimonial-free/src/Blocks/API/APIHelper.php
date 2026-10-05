<?php
/**
 * Real Testimonials API Helper.
 *
 * Internal helpers used by ManageAPI — not registered as REST/AJAX callbacks.
 * Houses the Testimonial Submission Form processing pipeline.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Blocks/API
 */

namespace ShapedPlugin\TestimonialFree\Blocks\API;

use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * APIHelper Class.
 */
class APIHelper {

	/**
	 * Process and persist a TSF submission.
	 *
	 * @param array $form_data Raw POST data.
	 * @return array{success:bool,message:string}
	 */
	public static function process_tsf_submission( $form_data ) {
		$form_id          = isset( $form_data['form_id'] ) ? sanitize_text_field( wp_unslash( $form_data['form_id'] ) ) : '';
		$post_id          = isset( $form_data['post_id'] ) ? absint( $form_data['post_id'] ) : 0;
		$block_attributes = BlocksHelper::find_block_attributes_by_id( $post_id, $form_id ) ?? array();

		$post_data = self::collect_tsf_post_data( $form_data, $block_attributes, $form_id );

		$post_id_new = wp_insert_post( $post_data, true );
		if ( is_wp_error( $post_id_new ) ) {
			return array(
				'success' => false,
				'message' => $post_id_new->get_error_message(),
			);
		}

		// Featured image upload → post thumbnail.
		$image_id = self::handle_tsf_upload(
			'tpro_client_image',
			$post_id_new,
			array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' ),
			5 * MB_IN_BYTES
		);
		if ( $image_id ) {
			set_post_thumbnail( $post_id_new, $image_id );
		}

		return array(
			'success' => true,
			'message' => $block_attributes['successMessage'] ?? __( 'Submitted successfully.', 'testimonial-free' ),
		);
	}

	/**
	 * Build the wp_insert_post payload from form data and block settings.
	 *
	 * @param array  $form_data        Raw POST data.
	 * @param array  $block_attributes Resolved block attributes.
	 * @param string $form_id          Form unique id.
	 * @return array
	 */
	public static function collect_tsf_post_data( $form_data, $block_attributes, $form_id ) {
		$name    = isset( $form_data['tpro_client_name'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_name'] ) ) : '';
		$email   = isset( $form_data['tpro_client_email'] ) ? sanitize_email( wp_unslash( $form_data['tpro_client_email'] ) ) : '';
		$role    = isset( $form_data['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_designation'] ) ) : '';
		$title   = isset( $form_data['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_testimonial_title'] ) ) : '';
		$content = isset( $form_data['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $form_data['tpro_client_testimonial'] ) ) : '';
		$rating  = isset( $form_data['tpro_client_rating'] ) ? sanitize_key( $form_data['tpro_client_rating'] ) : '';

		$status = self::resolve_tsf_status( $block_attributes );

		return array(
			'post_title'   => $title ? $title : $name,
			'post_content' => $content,
			'post_status'  => $status,
			'post_type'    => 'spt_testimonial',
			'meta_input'   => array(
				// Structure mirrors the classic form so submitted testimonials
				// display identically (see TestimonialQuery::get_testimonial_data).
				// Company name, phone, website, video, location, groups, and custom
				// fields are Pro-only and not collected in free.
				'sp_tpro_meta_options' => array(
					'tpro_name'        => $name,
					'tpro_email'       => $email,
					'tpro_designation' => $role,
					'tpro_rating'      => $rating,
					'tpro_form_id'     => $form_id,
				),
				'tpro_rating'          => $rating,
			),
		);
	}

	/**
	 * Validate and store a single uploaded file, returning its attachment id.
	 *
	 * Runs only after the caller has verified the form nonce. Enforces an
	 * allow-list of MIME types and a max byte size before handing off to
	 * media_handle_upload (which itself moves + attaches the file).
	 *
	 * @param string $file_key      Key in $_FILES.
	 * @param int    $post_id       Post to attach the media to.
	 * @param array  $allowed_mimes Allowed MIME types.
	 * @param int    $max_bytes     Max allowed size in bytes.
	 * @return int Attachment id, or 0 on failure/absence.
	 */
	public static function handle_tsf_upload( $file_key, $post_id, $allowed_mimes, $max_bytes ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce verified upstream in ManageAPI before this runs.
		if ( empty( $_FILES[ $file_key ]['name'] ) ) {
			return 0;
		}
		$error = isset( $_FILES[ $file_key ]['error'] ) ? (int) $_FILES[ $file_key ]['error'] : UPLOAD_ERR_NO_FILE;
		if ( UPLOAD_ERR_OK !== $error ) {
			return 0;
		}
		$size = isset( $_FILES[ $file_key ]['size'] ) ? (int) $_FILES[ $file_key ]['size'] : 0;
		if ( $size <= 0 || $size > (int) $max_bytes ) {
			return 0;
		}
		$tmp_name  = isset( $_FILES[ $file_key ]['tmp_name'] ) ? sanitize_text_field( $_FILES[ $file_key ]['tmp_name'] ) : '';
		$file_name = isset( $_FILES[ $file_key ]['name'] ) ? sanitize_file_name( wp_unslash( $_FILES[ $file_key ]['name'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		if ( '' === $tmp_name || '' === $file_name ) {
			return 0;
		}

		$check = wp_check_filetype_and_ext( $tmp_name, $file_name );
		$mime  = ! empty( $check['type'] ) ? $check['type'] : '';
		if ( '' === $mime || ! in_array( $mime, (array) $allowed_mimes, true ) ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attachment_id = media_handle_upload( $file_key, $post_id, array(), array( 'test_form' => false ) );
		if ( is_wp_error( $attachment_id ) ) {
			return 0;
		}
		return (int) $attachment_id;
	}

	/**
	 * Resolve the post_status to assign based on the configured moderation status.
	 *
	 * @param array $block_attributes Block attributes.
	 * @return string
	 */
	public static function resolve_tsf_status( $block_attributes ) {
		$status  = $block_attributes['testimonialStatus'] ?? 'pending';
		$allowed = array( 'pending', 'private', 'draft' );
		return in_array( $status, $allowed, true ) ? $status : 'pending';
	}
}
