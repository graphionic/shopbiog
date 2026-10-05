<?php
/**
 * The admin dashboard page for admin.
 *
 * @link https://shapedplugin.com/
 * @since 4.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 * @author ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\Dashboard;

use ShapedPlugin\TestimonialFree\Admin\Dashboard\ManageAPI;
use ShapedPlugin\TestimonialFree\Admin\Dashboard\DashboardHelper;
use ShapedPlugin\TestimonialFree\Admin\Dashboard\SavedTemplates;
use ShapedPlugin\TestimonialFree\Blocks\Includes\BlocksHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dashboard Class.
 */
class Dashboard {

	/**
	 * ManageAPI instance.
	 *
	 * @var ManageAPI
	 */
	private $api_manager;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'easy-accordion-free'            => 'easy-accordion-free.php',
		'easy-accordion-pro'             => 'easy-accordion-pro.php',
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
	);

	/**
	 * Block constructor.
	 */
	public function __construct() {
		// admin menu customization.
		add_action( 'admin_menu', array( $this, 'sp_real_admin_menu_customize' ) );
		add_action( 'admin_menu', array( $this, 'sp_real_reorder_submenus' ), 999 );
		// remove admin notices.
		add_action( 'current_screen', array( $this, 'remove_admin_notices' ) );
		// manage admin dashboard assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'sp_real_enqueue_dashboard_assets' ) );
		// admin notices.
		add_action( 'init', array( $this, 'sp_real_submit_user_consent' ) );
		add_action( 'admin_notices', array( $this, 'sp_real_maybe_show_user_consent_notice' ) );
		add_action( 'testimonial_pro_weekly_scheduled_events', array( $this, 'init_real_data_storing' ) );

		// Initialize API manager.
		$this->api_manager = new ManageAPI();
		// Saved templates file init.
		if ( BlocksHelper::is_active_module( 'saved_templates' ) ) {
			new SavedTemplates();
		}
	}

	/**
	 * Add submenu page for blocks.
	 */
	public function sp_real_admin_menu_customize() {
		// Getting Started.
		add_submenu_page(
			'edit.php?post_type=spt_testimonial',
			__( 'Real Testimonials Dashboard', 'testimonial-free' ),
			__( 'Getting Started', 'testimonial-free' ),
			apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' ),
			'rtp_dashboard',
			array( $this, 'sp_real_dashboard_wrapper_callback' )
		);
		// Settings - same dashboard content with settings hash for JS routing.
		add_submenu_page(
			'edit.php?post_type=spt_testimonial',
			__( 'Real Testimonials Settings', 'testimonial-free' ),
			__( 'Settings', 'testimonial-free' ),
			apply_filters( 'sp_real_testimonial_ui_permission', 'manage_options' ),
			'edit.php?post_type=spt_testimonial&page=rtp_dashboard#settings'
		);
	}

	/**
	 * Method sp_real_reorder_submenus.
	 *
	 * @return void
	 */
	public function sp_real_reorder_submenus() {
		global $submenu;

		$menu_key = 'edit.php?post_type=spt_testimonial';

		if ( empty( $submenu[ $menu_key ] ) ) {
			return;
		}
		$items = $submenu[ $menu_key ];

		// Index submenu items by slug.
		$indexed = array();
		foreach ( $items as $item ) {
			$_slug             = $item[2];
			$indexed[ $_slug ] = $item;
		}

		// Define priority order (these will appear first).
		$priority_items = array( 'rtp_dashboard' );

		$new_menu = array();

		// Add priority items first.
		foreach ( $priority_items as $slug ) {
			if ( isset( $indexed[ $slug ] ) ) {
				$new_menu[] = $indexed[ $slug ];
				unset( $indexed[ $slug ] );
			}
		}

		// Testimonial Form always sits at the bottom — hold it out of the
		// automatic append and add it last.
		$form_slug = 'edit.php?post_type=spt_testimonial_form';
		$form_item = isset( $indexed[ $form_slug ] ) ? $indexed[ $form_slug ] : null;
		unset( $indexed[ $form_slug ] );

		$settings_slug = 'edit.php?post_type=spt_testimonial&page=rtp_dashboard#settings';
		$settings_item = isset( $indexed[ $settings_slug ] ) ? $indexed[ $settings_slug ] : null;
		unset( $indexed[ $settings_slug ] );

		// Append remaining submenu items automatically.
		foreach ( $indexed as $item ) {
			$new_menu[] = $item;
		}

		// Force the Testimonial Form submenu to the end.
		if ( $form_item ) {
			$new_menu[] = $form_item;
		}

		// Force the Settings submenu to the very end.
		if ( $settings_item ) {
			$new_menu[] = $settings_item;
		}

		// Replace submenu.
		// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
		$submenu[ $menu_key ] = $new_menu;
		// phpcs:enable
	}

	/**
	 * Callback function for the blocks page.
	 */
	public function sp_real_dashboard_wrapper_callback() {
		// Handle activation/deactivation requests that come back via the action links.
		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && 'activate' === $action && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			if ( current_user_can( 'activate_plugins' ) ) {
				activate_plugin( $plugin, '', false, true );
			}
		}
		?>
			<div id="sp-real-admin-dashboard-wrapper" class="sp-real-admin-dashboard-wrapper"></div>
			<div class="sp-real-recommended-plugins-wrapper" style="display:none">
				<h2 class="sp-real-section-title"><?php esc_html_e( 'Supercharge Your Website with Our Free Plugins — Trusted by 360,050+ Users', 'testimonial-free' ); ?></h2>
				<div class="sp-real-wp-list-table plugin-install-php sp-d-flex sp-flex-wrap sp-gap-20px">
					<div class="sp-real-recommended-plugins sp-d-flex sp-flex-wrap sp-gap-20px sp-w-full" id="the-list">
						<?php $this->sp_real_plugins_info_api_help_page(); ?>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @return void
	 */
	public function sp_real_enqueue_dashboard_assets() {
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		if ( 'rtp_dashboard' !== $page ) {
			return;
		}
		// load dependencies.
		$dependencies = array( 'wp-element', 'wp-i18n', 'wp-components' );
		$asset_file   = SP_RT_PLUGIN_PATH . 'dist/rtp-dashboard.asset.php';
		if ( file_exists( $asset_file ) ) {
			$asset = require $asset_file;
			if ( ! empty( $asset['dependencies'] ) && is_array( $asset['dependencies'] ) ) {
				$dependencies = $asset['dependencies'];
			}
		}
		/**
		 * WP core CodeMirror for the Custom CSS & JS settings tab. Core ships CSS
		 * with linting off, so ask for it explicitly — the short mode name is what
		 * code-editor.js matches when it merges the CSSLint rule set, and requesting
		 * lint is also what makes wp_enqueue_code_editor() load csslint.
		 */
		$css_editor_settings = wp_enqueue_code_editor(
			array(
				'type'       => 'text/css',
				'codemirror' => array(
					'mode' => 'css',
					'lint' => true,
				),
			)
		);
		$js_editor_settings  = wp_enqueue_code_editor( array( 'type' => 'text/javascript' ) );
		/**
		 * Both return false when the user has switched syntax highlighting off in
		 * their profile. Only claim the dependency when the editor is actually
		 * loading, so that preference keeps the plain-textarea fallback.
		 */
		if ( false !== $css_editor_settings || false !== $js_editor_settings ) {
			$dependencies = array_merge( $dependencies, array( 'wp-codemirror', 'code-editor' ) );
		}

		/**
		 * Enqueue block admin dashboard main script.
		 */
		wp_enqueue_script( 'sp_real_dashboard_script', SP_RT_PLUGIN_URL . 'dist/rtp-dashboard.js', $dependencies, SP_TFREE_VERSION, true );
		/**
		 * Enqueue block admin dashboard main style.
		 */
		wp_enqueue_style( 'sp_real_dashboard_style', SP_RT_PLUGIN_URL . 'dist/rtp-dashboard.css', array(), SP_TFREE_VERSION, 'all' );

		/**
		 * Admin Settings page localization.
		 *
		 * Only the values that must be available before the first REST
		 * request (AJAX endpoint + nonces for admin-ajax fallbacks) are
		 * exposed here. Environment data (plugin URL/version, home URL,
		 * current user) is delivered via the REST
		 * `sp_real_api_response_data()` payload so the dashboard reads
		 * everything from a single source of truth. The code editor settings
		 * belong here too: they are produced by an enqueue-time core function
		 * and must exist synchronously when the editor field mounts.
		 */
		wp_localize_script(
			'sp_real_dashboard_script',
			'sp_real_admin_dashboard_localize',
			array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'nonce'               => wp_create_nonce( DashboardHelper::$sp_real_dashboard_nonce_key ),
				'import_export_nonce' => wp_create_nonce( 'spftestimonial_options_nonce' ),
				'code_editor'         => array(
					'css' => false !== $css_editor_settings ? $css_editor_settings : null,
					'js'  => false !== $js_editor_settings ? $js_editor_settings : null,
				),
			)
		);
	}

	/**
	 * Conditionally display the anonymous data consent notice.
	 *
	 * Ensures the current user has sufficient permissions, the notice
	 * has not been ignored, and the delay period has passed before
	 * showing the notice.
	 *
	 * @return void
	 */
	public function sp_real_maybe_show_user_consent_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$ignored_consent_notice = DashboardHelper::get_plugin_settings( 'rtp_ignored_consent_notice', false );
		$allow_anonymous_data   = DashboardHelper::get_plugin_settings( 'rtp_allow_anonymous_data', false );

		// Do not show if already allowed or ignored.
		if ( $allow_anonymous_data || $ignored_consent_notice ) {
			return;
		}

		// delay logic (7 days).
		DashboardHelper::sp_real_maybe_set_notice_start_time();

		if ( ! DashboardHelper::sp_real_has_notice_delay_passed( 7 ) ) {
			return;
		}

		// Finally show notice.
		$this->sp_real_notice_for_user_consent();
	}

	/**
	 * Handle user consent submission for anonymous data collection.
	 *
	 * Processes the admin notice form submission, verifies the nonce,
	 * stores the user's consent choice, and prevents form resubmission
	 * on page refresh.
	 *
	 * @return void
	 */
	public function sp_real_submit_user_consent() {
		// Handle POST action for the notice buttons.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$form_action = isset( $_POST['rtp_anonymous_data_action'] ) ? sanitize_text_field( wp_unslash( $_POST['rtp_anonymous_data_action'] ) ) : '';
		if ( ! $form_action || ! check_admin_referer( 'rtp_anonymous_data_action', 'rtp_anonymous_data_nonce' ) ) {
			return;
		}
		if ( 'allow' === $form_action ) {
			DashboardHelper::update_plugin_settings( 'rtp_allow_anonymous_data', true );
		} elseif ( 'deny' === $form_action ) {
			DashboardHelper::update_plugin_settings( 'rtp_ignored_consent_notice', true );
		}
		// Avoid resubmission on refresh.
		wp_safe_redirect( admin_url() );
		exit;
	}
	/**
	 * Render the anonymous data consent admin notice.
	 *
	 * Displays an admin notice prompting the user to allow or deny
	 * anonymous data collection if consent has not yet been given.
	 *
	 * @return void
	 */
	public function sp_real_notice_for_user_consent() {
		?>
				<style>
					.sp-real-anonymous-data-notice {
						background-color: #ffffff;
						border: 1px solid rgba(204, 204, 204, 1);
						border-left: 4px solid #0076ec;
						margin-bottom: 20px;
						display: flex;
						align-items: flex-start;
						padding: 14px;
						gap: 16px;
						box-shadow: 0 16px 32px -4px rgba(12, 12, 13, 0.05), 0 4px 4px -4px rgba(12, 12, 13, 0.02);
						position: relative;
						border-radius: 4px;
					}

					button.sp_real_anonymous_data_cross {
						border: none;
						background: transparent;
						position: absolute;
						top: 0;
						right: 7px;
						cursor: pointer;
						color: #b6b6b6;
						font-size: 16px;
					}

					.sp-real-anonymous-data-notice-wrapper {
						display: flex;
						gap: 26px;
					}
					.sp-real-anonymous-data-notice h3 {
						font-weight: 600;
						font-size: 18px;
						color: #2C2D2F;
						margin: 0 0 8px 0;
					}
					.sp-real-anonymous-data-notice p, .sp-real-anonymous-data-notice a {
						color: #6E6F72;
						font-size: 14px;
						margin: 0 0 2px 0;
					}
					.sp-real-anonymous-data-notice a {
						text-decoration: underline;
					}
					.sp-real-anonymous-data-notice .button {
						font-size: 14px;
						font-weight: 500;
					}
					.sp-real-anonymous-data-notice .button {
						font-size: 14px;
						font-weight: 500;
						background-color: #ffffff;
						color: #6E6F72;
						border: 1px solid #ECEDF0;
						transition: all 0.2s ease;
						margin-top: 8px;
					}
					.sp-real-anonymous-data-notice .button:hover {
						border: 1px solid #b7b8bb;
						color: #6E6F72;
						background-color: #ffffff;
					}
					.sp-real-anonymous-data-notice .sp_real_anonymous_data_connect {
						background-color: rgba(30, 30, 30, 1);
						color: #ffffff;
						line-height: 14px;
						border-radius: 4px;
						font-size: 13px;
					}
					.sp-real-anonymous-data-notice .sp_real_anonymous_data_connect:hover {
						background-color: rgb(46, 46, 46);
						color: #ffffff;
					}
					.sp-real-anonymous-data-notice .sp_real_anonymous_data_connect:focus {
						border: none;
						box-shadow: none;
						out-line: none;
					}
				</style>
				<div class="notice notice-info sp-real-anonymous-data-notice">
					<div class="sp-real-anonymous-data-notice-wrapper">
						<div>
							<h3>
							<?php esc_html_e( 'Help us make Real Testimonials even more awesome?', 'testimonial-free' ); ?>
							</h3>
							<p>
								<?php
								esc_html_e(
									'Allow us to collect non-sensitive diagnostic data to resolve problems faster and improve performance.',
									'testimonial-free'
								);
								?>
								<a href="https://realtestimonials.io/information-we-collect/" target="_blank"><?php esc_html_e( 'Learn More', 'testimonial-free' ); ?></a>
							</p>
						</div>
						<div style="display:flex; gap:10px;">
							<form method="post" style="display:inline;">
								<?php wp_nonce_field( 'rtp_anonymous_data_action', 'rtp_anonymous_data_nonce' ); ?>
								<input type="hidden" name="rtp_anonymous_data_action" value="allow" />
								<button type="submit" class="sp_real_anonymous_data_connect button">
								<?php esc_html_e( 'Accept & Close', 'testimonial-free' ); ?>
								</button>
							</form>
							<form method="post" style="display:inline;">
								<?php wp_nonce_field( 'rtp_anonymous_data_action', 'rtp_anonymous_data_nonce' ); ?>
								<input type="hidden" name="rtp_anonymous_data_action" value="deny" />
								<button type="submit" class="sp_real_anonymous_data_cross dashicons dashicons-dismiss">
								
								</button>
							</form>
						</div>
					</div>
				</div>
			<?php
	}
	/**
	 * Method init_rtp_data_storing.
	 *
	 * @return void
	 */
	public function init_real_data_storing() {
		$is_user_allow = DashboardHelper::get_plugin_settings( 'rtp_allow_anonymous_data', false );
		if ( $is_user_allow ) {
			$data = DashboardHelper::collect_anonymous_data();
			if ( ! empty( $data ) ) {
				DashboardHelper::send_data_to_remote( $data );
			}
		}
	}
	/**
	 * Method remove_admin_notices.
	 *
	 * @param object $screen is current screen.
	 * @return void
	 */
	public function remove_admin_notices( $screen ) {
		if ( $screen && 'spt_testimonial_page_rtp_dashboard' === $screen->id ) {
			// Disable all other admin notices on this screen.
			add_action(
				'admin_head',
				function () {
					remove_all_actions( 'admin_notices' );
					remove_all_actions( 'all_admin_notices' );
					remove_all_actions( 'network_admin_notices' );
					remove_action( 'admin_notices', 'update_nag', 3 );
				},
				1
			);
		}
	}

	/**
	 * Method sp_real_plugins_info_api_help_page function.
	 *
	 * @return void
	 */
	public function sp_real_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sp_rtf_plugins_data' );

		if ( false === $plugins_arr ) {
			$args = array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
					'icons',
				),
			);

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$plugin_info = plugins_api( 'query_plugins', $args );

			if ( ! is_wp_error( $plugin_info ) ) {

				$plugins_arr = array();
				if ( isset( $plugin_info->plugins ) && ( count( $plugin_info->plugins ) > 0 ) ) {
					foreach ( $plugin_info->plugins as $pl ) {
						$plugins_arr[] = array(
							'slug'              => $pl['slug'],
							'name'              => $pl['name'],
							'version'           => $pl['version'],
							'downloaded'        => $pl['downloaded'],
							'active_installs'   => $pl['active_installs'],
							'last_updated'      => strtotime( $pl['last_updated'] ),
							'rating'            => $pl['rating'],
							'num_ratings'       => $pl['num_ratings'],
							'short_description' => $pl['short_description'],
							'icons'             => $pl['icons']['2x'],
						);
					}
				}

				set_transient( 'sp_rtf_plugins_data', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			$no_of_active_installs = array_column( $plugins_arr, 'active_installs' );
			array_multisort( $no_of_active_installs, SORT_DESC, $plugins_arr );
			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$plugin_icon = $plugin['icons'];
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}
				// Skip the plugin if it is the current plugin.
				if ( 'testimonial-free' === $plugin_slug ) {
					continue;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=745&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
									<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( $plugin_icon ); ?>" class="plugin-icon" />
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
									<?php
									if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
										if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
											?>
											<button type="button" class="button button-disabled" disabled="disabled"><?php esc_html_e( 'Active', 'testimonial-free' ); ?></button>
											<?php
										} else {
											?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
												<?php esc_html_e( 'Activate', 'testimonial-free' ); ?>
											</a>
											<?php
										}
									} else {
										?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
										<?php esc_html_e( 'Install Now', 'testimonial-free' ); ?>
										</a>
									<?php } ?>
								</li>
								<li>
									<?php /* translators: %s: plugin name */ ?>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( sprintf( esc_html__( 'More information about %s', 'testimonial-free' ), $plugin['name'] ) ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
										<?php esc_html_e( 'More Details', 'testimonial-free' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
						</div>
					</div>
						<?php
						echo '<div class="plugin-card-bottom">';
						if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
							?>
							<div class="vers column-rating">
								<?php
								wp_star_rating(
									array(
										'rating' => $plugin['rating'],
										'type'   => 'percent',
										'number' => $plugin['num_ratings'],
									)
								);
								?>
								<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
							</div>
							<?php
						}
						if ( isset( $plugin['version'] ) ) {
							?>
							<div class="column-updated">
								<strong><?php esc_html_e( 'Version:', 'testimonial-free' ); ?></strong>
								<span><?php echo esc_html( $plugin['version'] ); ?></span>
							</div>
							<?php
						}

						if ( isset( $plugin['active_installs'] ) ) {
							?>
							<div class="column-downloaded">
								<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'testimonial-free' ); ?>
							</div>
							<?php
						}

						if ( isset( $plugin['last_updated'] ) ) {
							?>
							<div class="column-compatibility">
								<strong><?php esc_html_e( 'Last Updated:', 'testimonial-free' ); ?></strong>
								<span>
									<?php
									printf(
										/* translators: %s: time ago */
										esc_html__( '%s ago', 'testimonial-free' ),
										esc_html( human_time_diff( $plugin['last_updated'] ) )
									);
									?>
								</span>
							</div>
							<?php
						}
						echo '</div>';
						?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=spt_testimonial&page=rtp_dashboard&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#our_plugins' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}
}
