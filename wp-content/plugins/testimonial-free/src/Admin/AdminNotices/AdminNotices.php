<?php
/**
 * The admin notices file.
 *
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 * @author ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\AdminNotices;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This class is responsible for all kinds of admin facing notices.
 */
class AdminNotices {

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'render_all_admin_notices' ) );
		add_action( 'wp_ajax_sp_real_dismiss_blocks_promo_notice', array( $this, 'dismiss_blocks_promo_notice' ) );
	}

	/**
	 * Display all admin notice for backend.
	 *
	 * @return void
	 */
	public function render_all_admin_notices() {
		$this->render_blocks_promo_notice();
	}

	/**
	 * Render the "Meet the new Real Testimonials" promo banner that surfaces
	 * the block editor + ready patterns on the classic spt_shortcodes edit
	 * screens. Dismissed per-user via user_meta.
	 *
	 * @return void
	 */
	public function render_blocks_promo_notice() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( get_option( 'sp_real_blocks_promo_notice_dismissed' ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( isset( $screen->id ) && ( 'edit-spt_shortcodes' !== $screen->id && 'edit-spt_testimonial_form' !== $screen->id ) ) {
			return;
		}

		$block_editor_url = admin_url( 'post-new.php?post_type=page&rtpblock_inserter=true' );
		$patterns_url     = admin_url( 'post-new.php?post_type=page&realpatterns=true' );
		$nonce            = wp_create_nonce( 'sp_real_blocks_promo_notice' );
		?>
		<div class="notice sp-real-blocks-promo-notice" data-sp-real-nonce="<?php echo esc_attr( $nonce ); ?>">
			<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/assets/images/admin-notices.svg' ); ?>" alt="notice icon"/>
			<div class="sp-real-blocks-promo-notice__text">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %1$s and %2$s are the opening and closing HTML anchor tags for the promo link. */
						__( 'Good news! We\'ve introduced %1$s16 extended Gutenberg blocks%2$s — use Real Testimonial layouts and features directly in the block editor.', 'testimonial-free' ),
						'<a class="sp-real-blocks-promo-notice__highlight" href="' . esc_url( $patterns_url ) . '" target="_blank" rel="noopener">',
						'</a>'
					),
					array(
						'strong' => array(),
						'a'      => array(
							'class'  => array(),
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				);
				?>
			</div>
			<div class="sp-real-blocks-promo-notice__actions">
				<a class="sp-real-blocks-promo-notice__cta" href="<?php echo esc_url( $block_editor_url ); ?>">
					<?php esc_html_e( 'Try Block Editor', 'testimonial-free' ); ?>
					<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<path d="M2.25 6h7.5M6.75 2.25 10.5 6 6.75 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
				<button type="button" class="sp-real-blocks-promo-notice__dismiss" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'testimonial-free' ); ?>">
					<span class="dashicons dashicons-no-alt"></span>
				</button>
			</div>
		</div>
		<style>
			.sp-real-blocks-promo-notice {
				display: flex;
				align-items: center;
				gap: 14px;
				margin: 16px 0 !important;
				padding: 16px 24px;
				color: #2F2F2F;
				background-color: #EAECFD;
				border: 1px solid #C9CEF9;
				border-radius: 8px;
				box-shadow: none;
			}
			.sp-real-blocks-promo-notice__text {
				flex: 1 1 auto;
				color: #2f2f2f;
				font-size: 18px;
				line-height: 1.5;
			}
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__highlight{
				color: #1E67D8;
				font-weight: 700;
				text-decoration: none;
			}
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__highlight:hover,
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__highlight:focus {
				color: #1E67D8;
				box-shadow: none;
				outline: none;
			}
			.sp-real-blocks-promo-notice__actions {
				display: flex;
				align-items: center;
				gap: 48px;
				flex-shrink: 0;
			}
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__cta {
				display: inline-flex;
				align-items: center;
				gap: 5px;
				padding: 8px 12px;
				background: #1E67D8;
				border: 1px solid #1E67D8;
				border-radius: 5px;
				color: #fff;
				font: 600 13px/18px Roboto, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
				text-decoration: none;
				box-shadow: none;
			}
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__cta:hover,
			.sp-real-blocks-promo-notice .sp-real-blocks-promo-notice__cta:focus {
				background: #0066ff;
				border-color: #0066ff;
				color: #fff;
				outline: none;
			}
			.sp-real-blocks-promo-notice__cta svg {
				flex-shrink: 0;
			}
			.sp-real-blocks-promo-notice__dismiss {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: 24px;
				height: 24px;
				padding: 0;
				color: #8C8F94;
				background-color: transparent;
				border: 0;
				cursor: pointer;
			}
			.sp-real-blocks-promo-notice__dismiss:hover,
			.sp-real-blocks-promo-notice__dismiss:focus {
				color: #000;
				outline: none;
			}
			.sp-real-blocks-promo-notice__dismiss .dashicons {
				font-size: 20px;
				width: 20px;
				height: 20px;
			}
		</style>
		<script>
			( function() {
				document.addEventListener( 'click', function( event ) {
					var dismiss = event.target.closest( '.sp-real-blocks-promo-notice__dismiss' );
					if ( ! dismiss ) {
						return;
					}
					var notice = dismiss.closest( '.sp-real-blocks-promo-notice' );
					if ( ! notice ) {
						return;
					}
					notice.style.display = 'none';
					var data = new FormData();
					data.append( 'action', 'sp_real_dismiss_blocks_promo_notice' );
					data.append( 'nonce', notice.getAttribute( 'data-sp-real-nonce' ) || '' );
					if ( window.fetch && window.ajaxurl ) {
						fetch( window.ajaxurl, { method: 'POST', credentials: 'same-origin', body: data } );
					}
				} );
			} )();
		</script>
		<?php
	}

	/**
	 * Persist dismissal of the blocks promo notice site-wide.
	 *
	 * @return void
	 */
	public function dismiss_blocks_promo_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sp_real_blocks_promo_notice' ) ) {
			wp_send_json_error();
		}
		update_option( 'sp_real_blocks_promo_notice_dismissed', 1, false );
		wp_send_json_success();
	}
}
