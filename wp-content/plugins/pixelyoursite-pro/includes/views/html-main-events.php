<?php

namespace PixelYourSite;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$new_event_url = buildAdminUrl( 'pixelyoursite', 'events', 'edit' );
$export_url = buildAdminUrl( 'pixelyoursite', 'events', 'export' ) . "&_wpnonce=" . wp_create_nonce( "export_events_file_nonce" )
?>

    <div class="cards-wrapper cards-wrapper-style1 events-page-wrapper gap-24">
        <input type="hidden" name="pys[bulk_event_action_nonce]"
               value="<?php echo wp_create_nonce( 'bulk_event_action' ); ?>">

        <h2 class="font-semibold primary-heading-color fz-18 pt-4">User Defined Events</h2>

        <div class="card card-style4 card-static">
            <div class="card-header card-header-style3">
                <p class="secondary_heading_type2">
                    General
                </p>
            </div>
            <div class="card-body card-body-general">
                <div class="events-general-content-wrap">
                    <div class="d-flex align-items-center mb-24">
						<?php PYS()->render_switcher_input( 'custom_events_enabled' ); ?>
                        <h4 class="switcher-label secondary_heading">Enable Events</h4>
                    </div>

					<?php include PYS_VIEW_PATH . '/UI/buttons-import-export-events.php'; ?>
                </div>
            </div>
        </div>

		<?php
		$videos = array(
			array(
				'url'   => 'https://www.youtube.com/watch?v=UOzC8T0WGBw',
				'title' => 'How to use the Event Setup Tool to track any action',
				'time'  => '9:40',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=UBwGNlm5ILE',
				'title' => 'Track Form Progress with Field Events – Includes GA4 add_shipping_info for WooCommerce',
				'time'  => '6:10',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=wUsqwomsYMo',
				'title' => 'Improved Event Tracking - CONDITIONS - Meta, Google, TikTok, GTM',
				'time'  => '5:09',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=PcXYYGOvahc',
				'title' => 'Track URL tags as event parameters',
				'time'  => '8:15',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=ehi66agv1zo',
				'title' => 'Track YouTube or Vimeo Embedded Videos With Your Own Events',
				'time'  => '5:25',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=c4Hrb8WK5bw',
				'title' => 'Fire a LEAD event on form submit - WordPress & PixelYourSite',
				'time'  => '5:58',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=0IFHqI6itx8',
				'title' => 'Improve Meta EMQ score with when tracking WordPress forms',
				'time'  => '4:52',
			),
			array(
				'url'   => 'https://www.youtube.com/watch?v=Iyu-pSbqcFI',
				'title' => 'Mandatory: Verify your Meta CUSTOM Events (Pixel & CAPI)',
				'time'  => '5:10',
			),
		);

		renderRecommendedVideo( $videos );
		?>

		<!-- Event Setup Tool launcher -->
		<?php
		$est_nonce   = wp_create_nonce( 'pys_event_setup_tool' );
		$site_base   = trailingslashit( home_url() );
		$admin_return_url = esc_url( buildAdminUrl( 'pixelyoursite', 'events' ) );
		?>
		<div class="card card-style5 card-static" id="pys-event-setup-tool-launcher">
			<div class="card-header card-header-style3">
				<p class="secondary_heading_type2"><?php _e('Start Event Setup Tool', 'pys'); ?></p>
			</div>
			<div class="card-body">
				<div class="events-general-content-wrap">
					<p class="mb-12"><?php _e('Enter a URL on this site to open the visual tool on that page.', 'pys'); ?></p>
					<div class="d-flex align-items-center gap-12" style="gap:12px;">
                        <div class="flex-1">
                            <input type="text" id="pys-est-url" class="control-input input-standard" placeholder="<?php echo esc_attr( $site_base ); ?>sample-page/">
                        </div>
						<div class="ml-24">
                            <button type="button" class="btn btn-primary btn-primary-type2" id="pys-est-open"><?php _e('Open Tool', 'pys'); ?></button>
                        </div>
					</div>
                    <small class="form-text text-muted" style="display: block; margin-top: 4px; font-size: 12px; color: #6c757d;">
                        <?php _e('Supports both http:// and https:// URLs, or enter a relative path like /sample-page/', 'pys'); ?>
                    </small>
					<p class="mt-8 small muted"><?php _e('Only pages from this site are allowed:', 'pys'); ?> <strong><?php echo esc_html( $site_base ); ?></strong></p>
				</div>
			</div>
		</div>

		<script>
		(function(){
			var base = <?php echo wp_json_encode( $site_base ); ?>;
			var nonce = <?php echo wp_json_encode( $est_nonce ); ?>;
			var adminReturn = <?php echo wp_json_encode( $admin_return_url ); ?>;

			// Normalize base URL to support both http and https
			// Extract hostname and path from base, protocol will be ignored in validation
			var baseUrl = new URL(base);
			var baseHostname = baseUrl.hostname;
			var basePort = baseUrl.port;

			function normalizeUrl(input){
				if(!input){ return ''; }
				input = input.trim();

				try {
					// Try to parse as absolute URL (supports both http:// and https://)
					var u = new URL(input);
					return u.href;
				} catch(e) {
					// If not absolute, try as relative path with base
					try {
						var u = new URL(input, base);
						return u.href;
					} catch(e2){
						return '';
					}
				}
			}

			function isSameSite(fullUrl){
				try{
					var u = new URL(fullUrl);

					// Compare hostname and port (ignore protocol differences between http/https)
					// This allows both http:// and https:// URLs for the same domain
					var sameHost = u.hostname === baseHostname;
					var samePort = u.port === basePort;

					// If both ports are default (empty string), they're considered the same
					if (u.port === '' && basePort === '') {
						samePort = true;
					}

					// Also handle cases where one has default port and other is explicit
					// http default port is 80, https default port is 443
					if (u.port === '' && basePort === '') {
						samePort = true;
					} else if (u.port === '' && (basePort === '80' || basePort === '443')) {
						samePort = true;
					} else if ((u.port === '80' || u.port === '443') && basePort === '') {
						samePort = true;
					}

					return sameHost && samePort;
				}catch(e){
					return false;
				}
			}

			document.getElementById('pys-est-open').addEventListener('click', function(){
				var raw = document.getElementById('pys-est-url').value;
				var url = normalizeUrl(raw);

				if(!url){
					alert('Please enter a valid URL.');
					return;
				}

				if(!isSameSite(url)){
					alert('The URL must belong to this site.');
					return;
				}

				var target = new URL(url);
				target.searchParams.set('pys_est', '1');
				target.searchParams.set('pys_est_nonce', nonce);

				window.open(target.toString(), '_blank');
			});
		})();
		</script>

        <div class="card card-style4 card-static card-custom-events">
            <div class="card-header card-header-style3">
                <p class="font-semibold main-switcher">
                    Events List
                </p>
            </div>
            <div class="card-body">
                <div class="events-general-content-wrap">
					<?php include PYS_VIEW_PATH . '/UI/buttons-action-events.php'; ?>

                    <div class="line mb-24"></div>

                    <div>
                        <table class="table" id="table-custom-events">
                            <thead>
                            <tr>
                                <th>
                                    <div class="small-checkbox">
                                        <input type="checkbox" id="pys_select_all_events" value="1"
                                               class="small-control-input">
                                        <label class="small-control small-checkbox-label" for="pys_select_all_events">
                                            <span class="small-control-indicator"><i class="icon-check"></i></span>
                                        </label>
                                    </div>
                                </th>
                                <th class="column-title font-semibold">Name</th>
                                <th class="column-title font-semibold">Triggers</th>
                                <th class="column-title font-semibold">Networks</th>
                            </tr>
                            </thead>
                            <tbody>

							<?php foreach ( CustomEventFactory::get() as $event ) : ?>

								<?php
								$errorMessage = "";
								/** @var CustomEvent $event */

								$event_edit_url = buildAdminUrl( 'pixelyoursite', 'events', 'edit', array(
									'id' => $event->getPostId()
								) );

								$event_enable_url = buildAdminUrl( 'pixelyoursite', 'events', 'enable', array(
									'pys'      => array(
										'event' => array(
											'post_id' => $event->getPostId(),
										)
									),
									'_wpnonce' => wp_create_nonce( 'pys_enable_event' ),
								) );

								$event_disable_url = buildAdminUrl( 'pixelyoursite', 'events', 'disable', array(
									'pys'      => array(
										'event' => array(
											'post_id' => $event->getPostId(),
										)
									),
									'_wpnonce' => wp_create_nonce( 'pys_disable_event' ),
								) );

								$event_remove_url = buildAdminUrl( 'pixelyoursite', 'events', 'remove', array(
									'pys'      => array(
										'event' => array(
											'post_id' => $event->getPostId(),
										)
									),
									'_wpnonce' => wp_create_nonce( 'pys_remove_event' ),
								) );

								$triggers = $event->getTriggers();
								$event_types = array();

								if ( !empty( $triggers ) ) {
									foreach ( $triggers as $trigger ) {
										$trigger_type = $trigger->getTriggerType();
										switch ( $trigger_type ) {
											case 'number_page_visit':
												$event_types[] = 'Number of Page Visits';
												break;
											case 'post_type':
												{
													$event_types[] = 'Post Type';
													$selectedPostType = $trigger->getPostTypeValue();
													$errorMessage = "Post type not found";
													$types = get_post_types( null, "objects " );
													foreach ( $types as $type ) {
														if ( $type->name == $selectedPostType ) {
															$errorMessage = "";
															break;
														}
													}

												}
												break;

											case 'url_click':
												$event_types[] = 'Link Click';
												break;

											case 'css_click':
												$event_types[] = 'Element Click';
												break;

											case 'css_mouseover':
												$event_types[] = 'Element Mouseover';
												break;

											case 'scroll_pos':
												$event_types[] = 'Scroll Position';
												break;

                                            case 'copy_element':
                                                $event_types[] = 'Copy Element';
                                                break;

											case 'video_view':
												$event_types[] = 'Embedded Video View';
												break;

                                            case 'video_speed':
                                                $event_types[] = 'Video speed';
                                                break;

											case 'email_link':
												$event_types[] = 'Email Link';
												break;

											case 'page_visit':
												$event_types[] = 'Page Visit';
												break;

                                            case 'form_field':
                                                $event_types[] = 'Filling out a form field';
                                                break;

                                            case 'home_page':
                                                $event_types[] = 'Home Page Visit';
                                                break;

                                            case 'add_to_cart':
                                                $event_types[] = 'WooCommerce add to cart';
                                                break;

                                            case 'purchase':
                                                $event_types[] = 'WooCommerce purchase';
                                                break;

											case 'gads_phone_conversion':
												$event_types[] = 'Google Ads Phone Conversion';
												break;
										}
										if ( $trigger->isFormTriggerType( $trigger_type ) ) {
											$eventsFormFactory = apply_filters( "pys_form_event_factory", [] );
											foreach ( $eventsFormFactory as $activeFormPlugin ) :
												if ( $activeFormPlugin->getSlug() == $trigger_type ) {
													$event_types[] = $activeFormPlugin->getName();
												}
											endforeach;
										}
									}
								}

								if ( empty( $event_types ) ) {
									$event_types[] = 'No triggers';
								}
								?>

                                <tr data-post_id="<?php echo esc_attr( $event->getPostId() ); ?>"
                                    class="event-row <?php echo $event->isEnabled() ? '' : 'disabled'; ?>">
                                    <td class="event-select">
                                        <div class="small-checkbox">
                                            <input type="checkbox" name="pys[selected_events][]"
                                                   value="<?php echo esc_attr( $event->getPostId() ); ?>"
                                                   class="small-control-input pys-select-event"
                                                   id="pys-event-<?php echo esc_attr( $event->getPostId() ); ?>"
                                            >
                                            <label class="small-control small-checkbox-label"
                                                   for="pys-event-<?php echo esc_attr( $event->getPostId() ); ?>">
                                                <span class="small-control-indicator"><i class="icon-check"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="event-title-cell">
                                        <div class="event-title-wrapper">
                                            <div class="event-title">
                                                <a href="<?php echo esc_url( $event_edit_url ); ?>"
                                                   class="font-medium"><?php esc_html_e( $event->getTitle() ); ?></a>
                                            </div>
                                            <div class="event-actions">
												<?php if ( $event->isEnabled() ) : ?>
                                                    <a href="<?php echo esc_url( $event_disable_url ); ?>"
                                                       class="event-toggle">Disable</a>
												<?php else : ?>
                                                    <a href="<?php echo esc_url( $event_enable_url ); ?>"
                                                       class="event-toggle">Enable</a>
												<?php endif; ?>
                                                <div class="row-separator"></div>

                                                <a href="<?php echo esc_url( $event_remove_url ); ?>" class="
                                        remove-custom-event">Remove</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="event-types">
										<?php
										if ( !empty( $event_types ) ) :
											foreach ( $event_types as $event_type ) : ?>
                                                <p class="event-type font-medium">
													<?php echo wp_kses_post( $event_type ); ?>
                                                </p>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php

										if ( $errorMessage != "" ) : ?>
                                            <div class="event_error font-medium">
												<?= $errorMessage ?>
                                            </div>
										<?php endif; ?>
                                    </td>
                                    <td class="event-networks-col">
                                        <div class="event-networks">
											<?php
											$disabled = Facebook()->enabled() && !empty( Facebook()->getPixelIDs() ) && $event->isFacebookEnabled(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/meta-logo.svg" alt="meta logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$ga_tags = ( ( Ads()->enabled() && !empty( Ads()->getPixelIDs() ) ) || ( GA()->enabled() && !empty( GA()->getPixelIDs() ) ) ) && $event->isUnifyAnalyticsEnabled();
											$disabled = $ga_tags && $event->isGoogleAnalyticsPresent(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/google-tags-logo.svg"
                                                 alt="google tags logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$disabled = $ga_tags && $event->isGoogleAdsPresent(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/google-ads-logo.svg"
                                                 alt="google ads logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$disabled = GTM()->enabled() && !empty( GTM()->getPixelIDs() ) && $event->isGTMEnabled() && $event->isGTMPresent(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/gtm-logo.svg" alt="gtm logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$disabled = Tiktok()->enabled() && !empty( Tiktok()->getPixelIDs() ) && $event->isTikTokEnabled(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/tiktok-logo.svg"
                                                 alt="tiktok logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$disabled = Bing()->enabled() && !empty( Bing()->getPixelIDs() ) && $event->isBingEnabled(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/bing-logo.svg" alt="bing logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">

											<?php
											$disabled = Pinterest()->enabled() && !empty( Pinterest()->getPixelIDs() ) && $event->isPinterestEnabled(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/pinterest-logo.svg"
                                                 alt="pinterest logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">
	                                        <?php
	                                        $disabled = Reddit()->enabled() && !empty( Reddit()->getPixelIDs() ) && $event->isRedditEnabled(); ?>
                                            <img src="<?php echo PYS_URL; ?>/dist/images/reddit-logo.svg"
                                                 alt="reddit logo"
                                                 class="event-network <?php echo !$disabled ? 'disabled' : ''; ?>">
                                        </div>
                                    </td>
                                </tr>

							<?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- About params -->
        <div class="card about-params card-style3">
            <div class="card-header card-header-style2">
                <div class="d-flex align-items-center">
                    <i class="icon-Info"></i>
                    <h4 class="heading-with-icon bold-heading">About Parameters:</h4>
                </div>
            </div>

            <div class="card-body" style="display: block;">
                <p class="mb-20">All the events you configure here will automatically get the following parameters for
                    all
                    the tags:
                    <span class="parameters-list">page_title, post_type, post_id, landing_page, event_URL, user_role, plugin, event_time (pro), event_day (pro), event_month (pro), traffic_source (pro), UTMs (pro).</span>
                </p>
                <p>You can add other parameters when you configure the events.</p>
            </div>
        </div>
    </div>
