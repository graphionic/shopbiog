<?php

namespace PixelYourSite;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( isset( $_REQUEST[ 'id' ] ) ) {
	$id = sanitize_key( $_REQUEST[ 'id' ] );
	$event = CustomEventFactory::getById( $id );
} else {
	$event = new CustomEvent();
}
    $registry = PYS_Events_Registry();
    $enabled_platform = $registry->get_supported_platforms();
    $events = $registry->get_events_for_platform('facebook');
?>

<?php wp_nonce_field( 'pys_update_event' ); ?>
<input type="hidden" name="action" value="update">
<?php Events\renderHiddenInput( $event, 'post_id' ); ?>

<div class="cards-wrapper cards-wrapper-style1 events-page-wrapper gap-24">

    <div class="card card-style4 card-static">
        <div class="card-header card-header-style3">
            <p class="secondary_heading_type2">
                General
            </p>
        </div>
        <div class="card-body card-body-general">
            <div class="events-general-content-wrap">
                <div class="d-flex align-items-center mb-24">
                    <?php Events\renderSwitcherInput( $event, 'enabled' ); ?>
                    <h4 class="switcher-label secondary_heading">Enable Event</h4>
                </div>

                <div class="mb-24">
                    <h4 class="primary_heading mb-4">Event Name</h4>
					<?php Events\renderTextInput( $event, 'title', 'Enter event title' ); ?>
                    <input type="hidden" id="get_transform_title_wpnonce"
                           value="<?= wp_create_nonce( "get_transform_title_wpnonce" ) ?>"/>
                    <p class="text-gray mt-8">This name will be used in the GTM data layer for the custom parameters
                        object.</p>
                </div>

                <div class="d-flex align-items-center" id="fire_event_once">
					<?php Events\renderSwitcherInput( $event, 'enable_time_window' ); ?>

                    <label class="switcher-label primary_heading mr-16">Fire this event only once in</label>

                    <span>
                        <?php Events\renderNumberInput( $event, 'time_window', '24' ); ?>
                    </span>

                    <span class="ml-24">
                        hours
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-style4 card-static">
        <div class="card-header card-header-style3">
            <p class="secondary_heading_type2">
                Event Triggers
            </p>
        </div>
        <div class="card-body pys_triggers_wrapper">
            <div class="trigger_logic d-flex flex-column mb-24">
                <h4 class="primary_heading mb-16">Trigger Logic</h4>
                <div class="d-flex">
                    <?php Events\render_radio_input( $event, 'trigger_logic', 'OR', 'OR' ); ?>
                    <?php Events\render_radio_input( $event, 'trigger_logic', 'AND', 'AND' ); ?>
                </div>
            </div>
            <div class="fire_frequency d-flex flex-column mb-24">
                <h4 class="primary_heading mb-16">Fire Frequency</h4>
                <div class="d-flex">
                    <?php Events\render_radio_input( $event, 'fire_frequency', 'every_time', 'Every time' ); ?>
                    <?php Events\render_radio_input( $event, 'fire_frequency', 'once', 'Once per page' ); ?>
                </div>
            </div>
			<?php
			$event_triggers = $event->getTriggers();

			if ( !empty( $event_triggers ) ) :
				foreach ( $event_triggers as $event_trigger ) :
					$i = $event_trigger->getTriggerIndex();
					$trigger_type = $event_trigger->getTriggerType()
					?>
                    <div class="trigger_group" data-trigger_id="<?php echo esc_attr( $i ); ?>">
                        <div class="pys_remove_trigger">
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>

                        <div class="trigger_group_head trigger_group_<?php echo esc_attr( $trigger_type ); ?>">
                            <div class="fire_event_when d-flex align-items-center">
                                <div>
                                    <p class="font-semibold">Fire event when</p>
                                </div>

                                <div class="ml-8">
									<?php Events\renderTriggerTypeInput( $event_trigger, 'trigger_type' ); ?>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
								<?php if ( $trigger_type == "post_type" ) : ?>
                                    <div class="event_triggers_panel post_type_panel">
                                        <div class="trigger_post_type">
											<?php Events\renderPostTypeSelect( $event_trigger, 'post_type_value' ); ?>
                                        </div>
                                    </div>
								<?php endif; ?>

                                <div class="insert-marker-trigger post_type_marker"></div>

                                <div class="event-delay ml-24">
                                    <label class="mr-24">with delay</label>
									<?php Events\renderTriggerNumberInput( $event_trigger, 'delay', '0' ); ?>
                                    <label class="ml-16">seconds</label>
                                </div>

								<?php if ( $trigger_type == "number_page_visit" ) : ?>
                                    <div class="event_triggers_panel number_page_visit_panel number_page_visit_conditional_panel"
                                         data-trigger_type="number_page_visit"
                                         style="display: none;">
                                        <div class="d-flex">
                                            <div class="trigger_number_page_visit conditional_number_visit ml-24">
												<?php Events\renderTriggerConditionalNumberPage( $event_trigger, 'conditional_number_visit' ); ?>
                                            </div>

                                            <div class="trigger_number_page_visit number_visit ml-24">
												<?php Events\renderTriggerNumberInput( $event_trigger, 'number_visit', '0', 3 ); ?>
                                                <label class="ml-24">visited page</label>
                                            </div>
                                        </div>
                                    </div>
								<?php endif; ?>

                                <div class="insert-marker-trigger number_page_visit_conditional_marker"></div>
                            </div>

							<?php
							if ( $trigger_type == "post_type" ) :
								$selectedPostType = $event_trigger->getPostTypeValue();
								$errorMessage = "Post type " . $selectedPostType . " not found: the post type that triggers this event is not found on the website. This event can't fire.";
								$types = get_post_types( null, "objects " );
								foreach ( $types as $type ) {
									if ( $type->name == $selectedPostType ) {
										$errorMessage = "";
										break;
									}
								}

								if ( $errorMessage != "" ) :?>
                                    <div class="post_type_error mt-16">
                                        <div class="event_error critical_message"><?= $errorMessage ?>  </div>
                                    </div>
								<?php endif;
							endif; ?>

                        </div>

						<?php if ( $trigger_type == "page_visit" ) : ?>
                            <div class="event_triggers_panel page_visit_panel" data-trigger_type="page_visit"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div class="select-standard-wrap">
                                            <select class="select-standard" name="" autocomplete="off"
                                                    style="width: 100%;">
                                                <option value="contains">URL Contains</option>
                                                <option value="match">URL Match</option>
                                                <option value="param_contains">URL Parameters Contains</option>
                                                <option value="param_match">URL Parameters Match</option>
                                            </select>
                                        </div>

                                        <div class="ml-8">
                                            <input name="" placeholder="Enter URL" class="input-standard"
                                                   type="text">
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getPageVisitTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key ); ?>">

                                        <div class="event_trigger_wrapper">
                                            <div class="select-standard-wrap">
                                                <select class="select-standard"
                                                        name='<?php echo esc_attr( "pys[event][triggers][$i][page_visit_triggers][$key][rule]" ); ?>'
                                                        autocomplete="off" style="width: 100%;">
                                                    <option value="contains" <?php selected( $trigger[ 'rule' ], 'contains' ); ?>>
                                                        URL Contains
                                                    </option>
                                                    <option value="match" <?php selected( $trigger[ 'rule' ], 'match' ); ?>>
                                                        URL Match
                                                    </option>
                                                    <option value="param_contains" <?php selected( $trigger[ 'rule' ], 'param_contains' ); ?>>
                                                        URL Parameters Contains
                                                    </option>
                                                    <option value="param_match" <?php selected( $trigger[ 'rule' ], 'param_match' ); ?>>
                                                        URL Parameters Match
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="ml-8">
                                                <input type="text" placeholder="Enter URL" class="input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][page_visit_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div class="mb-24">
                                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all
                                        pages</p>
                                </div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another URL
                                    </button>
                                </div>

                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger page_visit_marker"></div>

						<?php if ( $trigger_type == "number_page_visit" ) : ?>
                            <div class="event_triggers_panel number_page_visit_panel number_page_visit_url_panel"
                                 data-trigger_type="number_page_visit"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div class="select-standard-wrap">
                                            <select class="select-standard pys_number_page_visit_triggers"
                                                    name=""
                                                    autocomplete="off" style="width: 100%;">
                                                <option value="any">Any URL`s</option>
                                                <option value="contains">URL Contains</option>
                                                <option value="match">URL Match</option>
                                                <option value="param_contains">URL Parameters Contains</option>
                                                <option value="param_match">URL Parameters Match</option>
                                            </select>
                                        </div>

                                        <div class="trigger_url ml-24" style="display: none">
                                            <input name="" placeholder="Enter URL"
                                                   class="pys_number_page_visit_triggers input-standard"
                                                   type="text">
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getNumberPageVisitTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key ); ?>">
                                        <div class="event_trigger_wrapper">
                                            <div class="select-standard-wrap">
                                                <select class="select-standard pys_number_page_visit_triggers"
                                                        name='<?php echo esc_attr( "pys[event][triggers][$i][number_page_visit_triggers][$key][rule]" ); ?>'
                                                        autocomplete="off" style="width: 100%;">
                                                    <option value="any" <?php selected( $trigger[ 'rule' ], 'any' ); ?>>
                                                        Any
                                                        URL`s
                                                    </option>
                                                    <option value="contains" <?php selected( $trigger[ 'rule' ], 'contains' ); ?>>
                                                        URL Contains
                                                    </option>
                                                    <option value="match" <?php selected( $trigger[ 'rule' ], 'match' ); ?>>
                                                        URL Match
                                                    </option>
                                                    <option value="param_contains" <?php selected( $trigger[ 'rule' ], 'param_contains' ); ?>>
                                                        URL Parameters Contains
                                                    </option>
                                                    <option value="param_match" <?php selected( $trigger[ 'rule' ], 'param_match' ); ?>>
                                                        URL Parameters Match
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="trigger_url ml-24" <?php if ( $trigger[ 'rule' ] === "any" ) : ?> style="display:none;"  <?php endif; ?>>
                                                <input type="text" placeholder="Enter URL"
                                                       class="pys_number_page_visit_triggers input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][number_page_visit_triggers][$key][value]" ); ?>'
                                                       value="<?php if ( $trigger[ 'rule' ] !== "any" ) : echo esc_attr( $trigger[ 'value' ] ); endif; ?>">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div class="mb-24">
                                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all
                                        pages</p>
                                </div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another URL
                                    </button>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger number_page_visit_url_marker"></div>

						<?php if ( $trigger_type == "url_click" ) : ?>
                            <div class="event_triggers_panel url_click_panel mb-16" data-trigger_type="url_click"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">

                                        <div class="select-standard-wrap">
                                            <select name="" autocomplete="off"
                                                    style="width: 100%;"
                                                    class="select-standard">
                                                <option value="contains">URL Contains</option>
                                                <option value="match">URL Match</option>
                                            </select>
                                        </div>
                                        <div class="ml-24">
                                            <input name="" placeholder="Enter URL"
                                                   type="text"
                                                   class="input-standard">
                                        </div>
                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>

                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getURLClickTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key );; ?>">
                                        <div class="event_trigger_wrapper">
                                            <div class="select-standard-wrap">
                                                <select title=""
                                                        name='<?php echo esc_attr( "pys[event][triggers][$i][url_click_triggers][$key][rule]" ); ?>'
                                                        autocomplete="off" style="width: 100%;"
                                                        class="select-standard">
                                                    <option value="contains" <?php selected( $trigger[ 'rule' ], 'contains' ); ?>>
                                                        URL Contains
                                                    </option>
                                                    <option value="match" <?php selected( $trigger[ 'rule' ], 'match' ); ?>>
                                                        URL Match
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="ml-24">
                                                <input type="text" placeholder="Enter URL"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][url_click_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>"
                                                       class="input-standard">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another URL
                                    </button>
                                </div>
                            </div>
						<?php endif; ?>

                        <div class="insert-marker-trigger url_click_marker"></div>

						<?php if ( $trigger_type == "css_click" ) : ?>
                            <div class="event_triggers_panel css_click_panel mb-16" data-trigger_type="css_click"
                                 style="display: none;">

                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div>
                                            <input name="" placeholder="Enter CSS selector"
                                                   class="input-standard"
                                                   type="text">
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getCSSClickTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key );; ?>">
                                        <div class="event_trigger_wrapper">
                                            <div>
                                                <input type="text" placeholder="Enter CSS selector"
                                                       class="input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][css_click_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another selector
                                    </button>
                                </div>

                                <div class="d-flex align-items-center mt-16">
                                    <label class="primary_heading mr-16">Number of clicks required</label>
									<?php Events\renderTriggerNumberInput( $event_trigger, 'click_count', '1', 2 ); ?>
                                </div>

                                <div class="d-flex align-items-center mt-16">
									<?php
									$i_ctl    = $event_trigger->getTriggerIndex();
									$ctl_val  = $event_trigger->getClickTimeLimit();
									?>
                                    <label class="primary_heading mr-16">Time limit between clicks (ms, 0&nbsp;=&nbsp;no limit)</label>
                                    <?php Events\renderTriggerNumberInput( $event_trigger, 'click_time_limit', '3000', 0 ); ?>
                                </div>
                            </div>
						<?php endif; ?>

                        <div class="insert-marker-trigger css_click_marker"></div>

						<?php if ( $trigger_type == "copy_element" ) : ?>
                            <div class="event_triggers_panel copy_element_panel mb-16" data-trigger_type="copy_element"
                                 style="display: none;">

                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div>
                                            <input name="" placeholder="Enter CSS selector (e.g. .article-content)"
                                                   class="input-standard" type="text">
                                        </div>
                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getCopyElementTriggers() as $key => $trigger ) : ?>
                                    <div class="event_trigger mb-16" data-trigger_id="<?php echo esc_attr( $key ); ?>">
                                        <div class="event_trigger_wrapper">
                                            <div>
                                                <input type="text" placeholder="Enter CSS selector"
                                                       class="input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][copy_element_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>">
                                            </div>
                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; ?>

                                <div class="insert-marker"></div>
                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another selector</button>
                                </div>
                                <p class="form-text text-small mt-8">Fires when a visitor copies text from an element matching the selector.</p>
                            </div>
						<?php endif; ?>

                        <div class="insert-marker-trigger copy_element_marker"></div>

						<?php if ( $trigger_type == "video_speed" ) : ?>
                            <div class="event_triggers_panel embedded_video_speed video_speed_panel" data-trigger_type="video_speed"
                                 style="display: none;">
								<?php
								$speed_data            = $event_trigger->getVideoSpeedData();
								$speed_urls            = $event_trigger->getVideoSpeedUrls();
								$speed_urls_opts       = !empty( $speed_urls ) ? array_combine( $speed_urls, $speed_urls ) : array();
								$selected_spd_triggers = $event_trigger->getVideoSpeedTriggers();
								$speed_trigger_opts    = !empty( $speed_data ) ? array_combine( array_column( $speed_data, 'id' ), array_column( $speed_data, 'title' ) ) : array();
								$speed_rate_options    = array(
									'increase' => 'Increase speed',
									'decrease' => 'Decrease speed',
									'any'      => 'Any speed change',
								);
								?>
                                <div class="event_trigger" data-trigger_id="0">
                                    <div class="event_trigger_wrapper">
                                        <div class="w-100">
                                            <p class="form-text text-small mb-4">Enter video pages URL</p>
                                            <div class="d-flex align-items-center w-100">
                                                <div class="flex-1">
                                                    <input type="hidden" class="pys_event_video_speed_data"
                                                           name="<?php echo esc_attr( "pys[event][triggers][$i][video_speed_data]" ); ?>"
                                                           value="<?php echo htmlspecialchars( json_encode( $speed_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
													<?php Events\render_multi_select_trigger_input( $event_trigger, 'video_speed_urls', $speed_urls_opts, $speed_urls, false, '', 'pys-tags-pysselect2 pys_video_speed_urls_event' ); ?>
                                                </div>
                                                <div class="ml-24">
                                                    <button class="btn btn-primary btn-primary-type2 pys-scan-video"
                                                            type="button"
                                                            value="Scan videos">Scan videos
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pys_video_speed_triggers" data-trigger_id="0"
                                     style="<?php echo empty( $speed_data ) ? 'display: none;' : ''; ?>">
                                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                                        <div class="event_trigger_wrapper">
                                            <div class="w-100">
                                                <p class="form-text text-small mb-4">Select videos</p>
												<?php Events\render_multi_select_trigger_input( $event_trigger, 'video_speed_triggers', $speed_trigger_opts, $selected_spd_triggers, false, '', 'pys-pysselect2 pys_video_speed_triggers_event' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="event_trigger mb-16" data-trigger_id="0">
                                        <div class="d-flex align-items-center">
                                            <label class="primary_heading mr-16">Speed change type</label>
											<?php Events\renderTriggerSelectInput( $event_trigger, 'video_speed_rate', $speed_rate_options, false, 'pys_video_speed_rate' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="video_speed_error mt-16" style="display: none">
                                    <div class="event_error critical_message"></div>
                                </div>
                            </div>
						<?php endif; ?>

                        <div class="insert-marker-trigger video_speed_marker"></div>

						<?php if ( $trigger_type == "css_mouseover" ) : ?>
                            <div class="event_triggers_panel css_mouseover_panel mb-16"
                                 data-trigger_type="css_mouseover"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div>
                                            <input name="" placeholder="Enter CSS selector"
                                                   class="input-standard"
                                                   type="text">
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getCSSMouseOverTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key );; ?>">
                                        <div class="event_trigger_wrapper">
                                            <div>
                                                <input type="text" placeholder="Enter CSS selector"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][css_mouseover_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>"
                                                       class="input-standard">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another selector
                                    </button>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger css_mouseover_marker"></div>

						<?php if ( $trigger_type == "scroll_pos" ) : ?>
                            <div class="event_triggers_panel scroll_pos_panel mb-16" data-trigger_type="scroll_pos"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div class="input-number-wrapper input-number-wrapper-percent">
                                            <button class="decrease"><i class="icon-minus"></i></button>
                                            <input type="number" name=""
                                                   value="30"
                                                   min="0"
                                                   max="100"
                                                   step="1"
                                            >
                                            <button class="increase"><i class="icon-plus"></i></button>
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getScrollPosTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key );; ?>">
                                        <div class="event_trigger_wrapper">

											<?php Events\renderTriggerNumberInputPercent( $event_trigger, 'scroll_pos_triggers', $key, $trigger[ 'value' ] ); ?>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another threshold
                                    </button>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger scroll_pos_marker"></div>

						<?php $eventsFormFactory = apply_filters( "pys_form_event_factory", [] );
						foreach ( $eventsFormFactory as $activeFormPlugin ) : ?>
							<?php if ( $trigger_type == $activeFormPlugin->getSlug() ) : ?>
								<?php if ( $activeFormPlugin->getSlug() == "elementor_form" ) : ?>
                                    <div class="event_triggers_panel <?php echo $activeFormPlugin->getSlug(); ?>_panel elementor_form"
                                         data-trigger_type="<?php echo $activeFormPlugin->getSlug(); ?>"
                                         style="display: none;">

										<?php
                                            $data = $event_trigger->getElementorFormData();
                                            $urls = $event_trigger->getElementorFormUrls();
                                            $urls = array_combine( $urls, $urls );
										?>
                                        <div class="event_trigger" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="w-100">
                                                    <p class="form-text text-small mb-4">Enter Elementor form pages
                                                        URL</p>

                                                    <div class="d-flex align-items-center w-100">
                                                        <div class="flex-1">
                                                            <input type="hidden" class="pys_event_elementor_form_data"
                                                                   name="<?php echo esc_attr( "pys[event][triggers][$i][elementor_form_data]" ); ?>"
                                                                   value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
															<?php Events\render_multi_select_trigger_input( $event_trigger, 'elementor_form_urls', $urls, $event_trigger->getElementorFormUrls(), false, '', 'pys-tags-pysselect2 pys_elementor_form_urls_event' ); ?>
                                                        </div>

                                                        <div class="ml-24">
                                                            <button class="btn btn-primary btn-primary-type2 pys-scan-elementor-form"
                                                                    type="button"
                                                                    value="Scan forms">Scan forms
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pys_elementor_form_triggers" data-trigger_id="0"
                                             style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                                            <div class="event_trigger mb-16" data-trigger_id="0">
                                                <div class="event_trigger_wrapper">
                                                    <div class="w-100">
                                                        <p class="form-text text-small mb-4">Select forms</p>
														<?php Events\render_multi_select_trigger_form_input( $event_trigger, $activeFormPlugin, false, '', true, 'pys_elementor_form_triggers_event' ); ?>
                                                    </div>
                                                </div>
                                            </div>
                            <div class="event_trigger mb-16" data-trigger_id="0">
                                <div class="form_submit_mode_wrapper">
                                    <h4 class="primary_heading mb-8">Trigger Form Submit Mode</h4>
                                    <div class="d-flex flex-column radio-inputs-wrap">
                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'both', 'Send after page reload + AJAX' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'reload_only', 'Only after page reload' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'ajax_only', 'Only AJAX' ); ?>
                                    </div>
                                    <p class="form-text text-small mt-8">Change this option if the trigger doesn't work, or if two events are fired.</p>
                                </div>
                            </div>
                            <div class="event_trigger" data-trigger_id="0">
                                <div class="switcher_event_disabled_form_action d-flex align-items-center">
													<?php Events\renderSwitcherTriggerFormInput( $event_trigger, $activeFormPlugin ); ?>
                                    <h4 class="switcher-label secondary_heading">Disable the Form event
                                        for the same forms</h4>
                                </div>
                            </div>
                        </div>

                        <div class="elementor_form_error mt-16" style="display: none">
                            <div class="event_error critical_message"></div>
                        </div>
                    </div>

								<?php else : ?>
                                    <div class="event_triggers_panel <?php echo $activeFormPlugin->getSlug(); ?>_panel"
                                         data-trigger_type="<?php echo $activeFormPlugin->getSlug(); ?>"
                                         style="display: none;">
                                         
                                        <div class="event_trigger mb-16" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="form_submit_mode_wrapper">
                                                    <h4 class="primary_heading mb-8">Trigger Form Submit Mode</h4>
                                                    <div class="d-flex flex-column radio-inputs-wrap">
                                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'both', 'Send after page reload + AJAX' ); ?>
                                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'reload_only', 'Only after page reload' ); ?>
                                                        <?php Events\renderTriggerRadioFormInput( $event_trigger, $activeFormPlugin, 'ajax_only', 'Only AJAX' ); ?>
                                                    </div>
                                                    <p class="form-text text-small mt-8">Change this option if the trigger doesn't work, or if two events are fired.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="event_trigger mb-16" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="w-100">
                                                    <p class="form-text text-small mb-4">Select Forms to Trigger the
                                                        Event</p>

                                                    <div class="select_event_trigger_form_wrapper">
														<?php Events\render_multi_select_trigger_form_input( $event_trigger, $activeFormPlugin ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="event_trigger" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="switcher_event_form_disable_event d-flex align-items-center">
													<?php Events\renderSwitcherTriggerFormInput( $event_trigger, $activeFormPlugin ); ?>
                                                    <h4 class="switcher-label secondary_heading">Disable the Form event
                                                        for the same
                                                        forms</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php endif; ?>
							<?php endif; ?>

                            <div class="insert-marker-trigger <?php echo $activeFormPlugin->getSlug(); ?>_marker"></div>

						<?php endforeach; ?>

                        <?php  if ( $trigger_type == 'form_field' ) : ?>
                            <div class="event_triggers_panel form_field_panel" data-trigger_type="form_field" style="display: none;">
                                <?php if ( $event_trigger->isCreatedViaEst() ) : ?>
                                    <!-- EST form field display with editable fields -->
                                    <div class="event_trigger" data-trigger_id="0">
                                        <div class="event_trigger_wrapper">
                                            <div class="w-100">
                                                <div class="d-flex align-items-center mb-16">
                                                    <div class="est-badge mr-16">
                                                        <span class="badge badge-info">EST</span>
                                                    </div>
                                                    <p class="form-text text-small mb-0">Created via Event Setup Tool</p>
                                                </div>
                                                
                                                <?php 
                                                $form_field = $event_trigger->getAnyForm();
                                                $form_field_field = $event_trigger->getAnyFormField();
                                                $form_field_type = $event_trigger->getFormFieldType();
                                                $form_field_urls = $event_trigger->getAnyFormUrls();
                                                ?>
                                                
                                                <!-- Form URLs -->
                                                <div class="mb-16">
                                                    <p class="form-text text-small mb-4">Form pages URL</p>
                                                    <div class="select-standard-wrap">
                                                        <select class="pys-pysselect2" name="<?php echo esc_attr( "pys[event][triggers][$i][form_field_urls]" ); ?>[]" multiple style="width: 100%;">
                                                            <?php if ( !empty( $form_field_urls ) ) : ?>
                                                                <?php foreach ( $form_field_urls as $url ) : ?>
                                                                    <option value="<?php echo esc_attr( $url ); ?>" selected><?php echo esc_html( $url ); ?></option>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <option value="<?php echo esc_attr( home_url() ); ?>" selected><?php echo esc_html( home_url() ); ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Form Selector -->
                                                <div class="mb-16">
                                                    <p class="form-text text-small mb-4">Form selector</p>
                                                    <div>
                                                        <input type="text" 
                                                               class="input-standard" 
                                                               name="<?php echo esc_attr( "pys[event][triggers][$i][form_field][form]" ); ?>"
                                                               value="<?php echo esc_attr( $form_field ); ?>"
                                                               placeholder="e.g., form.contact-form">
                                                    </div>
                                                </div>

                                                <!-- Field Selector -->
                                                <div class="mb-16">
                                                    <p class="form-text text-small mb-4">Field selector</p>
                                                    <div>
                                                        <input type="text" 
                                                               class="input-standard" 
                                                               name="<?php echo esc_attr( "pys[event][triggers][$i][form_field][field]" ); ?>"
                                                               value="<?php echo esc_attr( $form_field_field ); ?>"
                                                               placeholder="e.g., input[name='email']">
                                                    </div>
                                                </div>

                                                <!-- Field Type -->
                                                <div class="mb-16">
                                                    <p class="form-text text-small mb-4">Field type</p>
                                                    <div class="select-standard-wrap">
                                                        <select class="select-standard" name="<?php echo esc_attr( "pys[event][triggers][$i][form_field][type]" ); ?>" style="width: 100%;">
                                                            <option value="text" <?php selected( $form_field_type, 'text' ); ?>>Text</option>
                                                            <option value="email" <?php selected( $form_field_type, 'email' ); ?>>Email</option>
                                                            <option value="tel" <?php selected( $form_field_type, 'tel' ); ?>>Telephone</option>
                                                            <option value="password" <?php selected( $form_field_type, 'password' ); ?>>Password</option>
                                                            <option value="number" <?php selected( $form_field_type, 'number' ); ?>>Number</option>
                                                            <option value="url" <?php selected( $form_field_type, 'url' ); ?>>URL</option>
                                                            <option value="search" <?php selected( $form_field_type, 'search' ); ?>>Search</option>
                                                            <option value="date" <?php selected( $form_field_type, 'date' ); ?>>Date</option>
                                                            <option value="checkbox" <?php selected( $form_field_type, 'checkbox' ); ?>>Checkbox</option>
                                                            <option value="radio" <?php selected( $form_field_type, 'radio' ); ?>>Radio</option>
                                                            <option value="select" <?php selected( $form_field_type, 'select' ); ?>>Select</option>
                                                            <option value="select-multiple" <?php selected( $form_field_type, 'select-multiple' ); ?>>Select Multiple</option>
                                                            <option value="textarea" <?php selected( $form_field_type, 'textarea' ); ?>>Textarea</option>
                                                            <option value="file" <?php selected( $form_field_type, 'file' ); ?>>File</option>
                                                            <option value="range" <?php selected( $form_field_type, 'range' ); ?>>Range</option>
                                                            <option value="color" <?php selected( $form_field_type, 'color' ); ?>>Color</option>
                                                            <option value="hidden" <?php selected( $form_field_type, 'hidden' ); ?>>Hidden</option>
                                                            <option value="button" <?php selected( $form_field_type, 'button' ); ?>>Button</option>
                                                            <option value="submit" <?php selected( $form_field_type, 'submit' ); ?>>Submit</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Hidden fields for compatibility -->
                                                <input type="hidden" name="<?php echo esc_attr( "pys[event][triggers][$i][form_field_data]" ); ?>" value="[]">
                                                <input type="hidden" name="<?php echo esc_attr( "pys[event][triggers][$i][created_via_est]" ); ?>" value="1">
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <!-- Legacy form field display -->
                                    <?php
                                    $data = $event_trigger->getAnyFormData();
                                    $urls = $event_trigger->getAnyFormUrls();
                                    $urls = array_combine( $urls, $urls );

                                    ?>
                                    <div class="event_trigger" data-trigger_id="0">
                                        <div class="event_trigger_wrapper">
                                            <div class="w-100">
                                                <p class="form-text text-small mb-4">Enter form pages URL</p>

                                                <div class="d-flex align-items-center w-100">
                                                    <div class="flex-1">
                                                        <input type="hidden" class="pys_event_form_field_data"
                                                               name="<?php echo esc_attr( "pys[event][triggers][$i][form_field_data]" ); ?>"
                                                               value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
                                                        <?php Events\render_multi_select_trigger_input( $event_trigger, 'form_field_urls', $urls, $event_trigger->getAnyFormUrls(), false, '', 'pys_form_field_urls_event', false ); ?>
                                                    </div>

                                                    <div class="ml-24">
                                                        <button class="btn btn-primary btn-primary-type2 pys-scan-forms"
                                                                type="button"
                                                                value="Scan forms">Scan forms
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pys_form_field_triggers" data-trigger_id="0"
                                         style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                                        <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="w-100">
                                                    <p class="form-text text-small mb-4">Select form</p>
                                                    <?php Events\render_select_trigger_any_form_input( $event_trigger, false, '', false, 'pys_form_field_triggers_event' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pys_form_fields" data-trigger_id="0"
                                         style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                                        <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                                            <div class="event_trigger_wrapper">
                                                <div class="w-100">
                                                    <p class="form-text text-small mb-4">Select field</p>
                                                    <?php Events\render_select_trigger_field_input( $event_trigger, false, '', false, 'pys_form_field_event' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="elementor_form_error mt-16" style="display: none">
                                        <div class="event_error critical_message"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="insert-marker-trigger form_field_marker"></div>


						<?php if ( $trigger_type == 'video_view' ) : ?>
                            <div class="event_triggers_panel embedded_video_view video_view_panel"
                                 data-trigger_type="video_view"
                                 style="display: none;">
								<?php $data = $event_trigger->getVideoViewData();
								$urls = $event_trigger->getVideoViewUrls();
								$urls = array_combine( $urls, $urls );
								$selected = $event_trigger->getVideoViewTriggers();
								$triggers = !empty( $data ) ? array_combine( array_column( $data, 'id' ), array_column( $data, 'title' ) ) : array();
								$play_options = array(
									'0%'   => 'Play',
									'10%'  => '10%',
									'50%'  => '50%',
									'90%'  => '90%',
									'100%' => '100%',
								);
								?>
                                <div class="event_trigger" data-trigger_id="0">
                                    <div class="event_trigger_wrapper">
                                        <div class="w-100">
                                            <p class="form-text text-small mb-4">Enter video pages URL</p>

                                            <div class="d-flex align-items-center w-100">
                                                <div class="flex-1">
                                                    <input type="hidden" class="pys_event_video_view_data"
                                                           name="<?php echo esc_attr( "pys[event][triggers][$i][video_view_data]" ); ?>"
                                                           value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
													<?php Events\render_multi_select_trigger_input( $event_trigger, 'video_view_urls', $urls, $event_trigger->getVideoViewUrls(), false, '', 'pys-tags-pysselect2 pys_video_view_urls_event' ); ?>
                                                </div>

                                                <div class="ml-24">
                                                    <button class="btn btn-primary btn-primary-type2 pys-scan-video"
                                                            type="button"
                                                            value="Scan videos">Scan videos
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pys_video_view_triggers" data-trigger_id="0"
                                     style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                                        <div class="event_trigger_wrapper">
                                            <div class="w-100">
                                                <p class="form-text text-small mb-4">Select videos</p>
												<?php Events\render_multi_select_trigger_input( $event_trigger, 'video_view_triggers', $triggers, $selected, false, '', 'pys-pysselect2 pys_video_view_triggers_event' ); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="event_trigger mb-16" data-trigger_id="0">
                                        <div class="d-flex align-items-center">
                                            <label class="primary_heading mr-16">Select trigger</label>
											<?php Events\renderTriggerSelectInput( $event_trigger, 'video_view_play_trigger', $play_options, false, 'pys_video_view_play_trigger' ); ?>
                                        </div>
                                    </div>

                                    <div class="event_trigger" data-trigger_id="0">
                                        <div class="event_trigger_wrapper">
                                            <div class="switcher_event_disable_watch_video d-flex align-items-center">
												<?php Events\renderSwitcherTriggerInput( $event_trigger, 'video_view_disable_watch_video' ); ?>
                                                <h4 class="switcher-label secondary_heading">Disable the automatic
                                                    WatchVideo
                                                    events for the video</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="video_view_error mt-16" style="display: none">
                                    <div class="event_error critical_message"></div>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger video_view_marker"></div>

						<?php if ( $trigger_type == 'email_link' ) : ?>
                            <div class="event_triggers_panel email_link_panel" data-trigger_type="email_link"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div class="select-standard-wrap">
                                            <select class="select-standard pys_email_link_triggers"
                                                    name=""
                                                    autocomplete="off" style="width: 100%;">
                                                <option value="any">All links</option>
                                                <option value="match">Link Match</option>
                                                <option value="contains">Link Include</option>
                                            </select>
                                        </div>

                                        <div class="trigger_url ml-24" style="display: none">
                                            <input name="" placeholder="Enter Link"
                                                   class="input-standard"
                                                   type="text">
                                        </div>

                                        <div>
											<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

								<?php foreach ( $event_trigger->getEmailLinkTriggers() as $key => $trigger ) : ?>
                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key ); ?>">
                                        <div class="event_trigger_wrapper">
                                            <div class="select-standard-wrap">
                                                <select class="select-standard pys_email_link_triggers"
                                                        name='<?php echo esc_attr( "pys[event][triggers][$i][email_link_triggers][$key][rule]" ); ?>'
                                                        autocomplete="off" style="width: 100%;">
                                                    <option value="any" <?php selected( $trigger[ 'rule' ], 'any' ); ?>>
                                                        All links
                                                    </option>
                                                    <option value="match" <?php selected( $trigger[ 'rule' ], 'match' ); ?>>
                                                        Link Match
                                                    </option>
                                                    <option value="contains" <?php selected( $trigger[ 'rule' ], 'contains' ); ?>>
                                                        Link Include
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="trigger_url ml-24" <?php if ( $trigger[ 'rule' ] === "any" ) : ?> style="display:none;"  <?php endif; ?>>
                                                <input type="text" placeholder="Enter Link"
                                                       class="pys_number_page_visit_triggers input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][email_link_triggers][$key][value]" ); ?>'
                                                       value="<?php if ( $trigger[ 'rule' ] !== "any" ) : echo esc_attr( $trigger[ 'value' ] ); endif; ?>">
                                            </div>

                                            <div>
												<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger" type="button">
                                        Add
                                        another Link
                                    </button>
                                </div>

                                <div class="mt-16" data-trigger_id="0">
                                    <div class="event_trigger_wrapper">
                                        <div class="switcher_event_email_link_event d-flex align-items-center">
											<?php Events\renderSwitcherTriggerInput( $event_trigger, 'email_link_disable_email_event' ); ?>
                                            <h4 class="switcher-label secondary_heading">Disable the default Email event
                                                for the same action</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="insert-marker-trigger email_link_marker"></div>
                        <div class="insert-marker-trigger add_to_cart_marker"></div>
                        <?php if ( $trigger_type == 'add_to_cart' ) : ?>
                            <div class="event_triggers_panel add_to_cart_panel" data-trigger_type="add_to_cart" style="display: none;">
                                <div class="event_trigger mb-16">
                                    <div class="d-flex align-items-center switcher_event_track_value_and_currency">
                                        <?php Events\renderSwitcherTriggerInput( $event_trigger, 'track_value_and_currency' ); ?>
                                        <h4 class="switcher-label">Track value and currency</h4>
                                    </div>

                                    <p class="mt-8"><?php _e('We will add value and currency parameters, similar to the default WooCommerce add to cart event. If you use this option, don\'t manually add value or currency parameters to this event.', 'pys');?></p>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <h4><b><?php _e('Warning:', 'pys');?></b> <?php _e('Use it only if you must replace the default Add To Cart event with a custom event. Don\'t configure an add to cart event with this trigger, the plugin fires such an event automatically.', 'pys');?></h4>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="insert-marker-trigger purchase_marker"></div>
                        <?php if ( $trigger_type == 'purchase' ) : ?>
                            <div class="event_triggers_panel purchase_panel" data-trigger_type="purchase" style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="0">
                                    <div class="d-flex align-items-center switcher_event_transaction_only_action">
                                        <?php Events\renderSwitcherTriggerInput( $event_trigger, 'purchase_transaction_only' ); ?>
                                        <h4 class="switcher-label">Fire this event for transaction only</h4>
                                    </div>
                                </div>
                                <div class="event_trigger mb-16" data-trigger_id="0">
                                    <div class="d-flex align-items-center switcher_event_track_transaction_ID">
                                        <?php Events\renderSwitcherTriggerInput( $event_trigger, 'track_transaction_ID' ); ?>
                                        <h4 class="switcher-label">Track transaction ID</h4>
                                    </div>
                                </div>
                                <div class="event_trigger mb-16" data-trigger_id="0">
                                    <div class="d-flex align-items-center switcher_event_track_value_and_currency">
                                        <?php Events\renderSwitcherTriggerInput( $event_trigger, 'track_value_and_currency' ); ?>
                                        <h4 class="switcher-label">Track value and currency</h4>
                                    </div>
                                    <p class="mt-8"><?php _e('We will add value and currency parameters, similar to the default WooCommerce Purchase event. If you use this option, don\'t manually add value or currency parameters to this event.', 'pys');?></p>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <h4><b><?php _e('Warning:', 'pys');?></b> <?php _e('Use it only if you must replace the default Purchase event with a custom event. Don\'t configure a Purchase event with this trigger, the plugin fires such an event automatically. ', 'pys');?></h4>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


	                    <?php if ( $trigger_type == "gads_phone_conversion" ) : ?>
                            <div class="event_triggers_panel gads_phone_conversion_panel" data-trigger_type="gads_phone_conversion"
                                 style="display: none;">
                                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                                    <div class="event_trigger_wrapper">
                                        <div class="select-standard-wrap">
                                            <select class="select-standard" name="" autocomplete="off"
                                                    style="width: 100%;">
                                                <option value="contains">URL Contains</option>
                                                <option value="match">URL Match</option>
                                                <option value="param_contains">URL Parameters Contains</option>
                                                <option value="param_match">URL Parameters Match</option>
                                            </select>
                                        </div>

                                        <div class="ml-8">
                                            <input name="" placeholder="Enter URL" class="input-standard"
                                                   type="text">
                                        </div>

                                        <div>
						                    <?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>
                                </div>

			                    <?php foreach ( $event_trigger->getGadsPhoneConversionTriggers() as $key => $trigger ) : ?>

                                    <div class="event_trigger mb-16"
                                         data-trigger_id="<?php echo esc_attr( $key ); ?>">

                                        <div class="event_trigger_wrapper">
                                            <div class="select-standard-wrap">
                                                <select class="select-standard"
                                                        name='<?php echo esc_attr( "pys[event][triggers][$i][gads_phone_conversion_triggers][$key][rule]" ); ?>'
                                                        autocomplete="off" style="width: 100%;">
                                                    <option value="contains" <?php selected( $trigger[ 'rule' ], 'contains' ); ?>>
                                                        URL Contains
                                                    </option>
                                                    <option value="match" <?php selected( $trigger[ 'rule' ], 'match' ); ?>>
                                                        URL Match
                                                    </option>
                                                    <option value="param_contains" <?php selected( $trigger[ 'rule' ], 'param_contains' ); ?>>
                                                        URL Parameters Contains
                                                    </option>
                                                    <option value="param_match" <?php selected( $trigger[ 'rule' ], 'param_match' ); ?>>
                                                        URL Parameters Match
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="ml-8">
                                                <input type="text" placeholder="Enter URL" class="input-standard"
                                                       name='<?php echo esc_attr( "pys[event][triggers][$i][gads_phone_conversion_triggers][$key][value]" ); ?>'
                                                       value="<?php echo esc_attr( $trigger[ 'value' ] ); ?>">
                                            </div>

                                            <div>
							                    <?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                            </div>
                                        </div>
                                    </div>

			                    <?php endforeach; ?>

                                <div class="insert-marker"></div>

                                <div class="mb-24">
                                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all
                                        pages</p>
                                </div>

                                <div>
                                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                                            type="button">Add another URL
                                    </button>
                                </div>

                            </div>
	                    <?php endif; ?>
                        <div class="insert-marker-trigger gads_phone_conversion_marker"></div>
                    </div>
				<?php endforeach; ?>
			<?php endif; ?>

            <div class="insert-marker-add-trigger"></div>

			<?php // Add new event trigger
			?>
            <div id="pys-add-trigger">
                <button class="btn btn-primary btn-primary-type2 add-trigger" type="button">Add trigger
                </button>
            </div>
        </div>


        <div id="pys_add_event_trigger" style="display: none;">

			<?php $new_trigger = new TriggerEvent();
			$new_index = $new_trigger->getTriggerIndex();
			?>

            <input type="hidden" name="<?php echo esc_attr( "pys[event][triggers][$new_index][cloned_event]" ); ?>"
                   value="1">

            <div class="trigger_group" data-trigger_id="0"
                 data-new_trigger_index="<?php echo esc_attr( $new_index ); ?>" style="display: none;">
                <div class="pys_remove_trigger">
					<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                </div>

                <div class="trigger_group_head">
                    <div class="fire_event_when d-flex align-items-center">
                        <div>
                            <p class="font-semibold">Fire event when</p>
                        </div>

                        <div class="ml-8">
							<?php Events\renderTriggerTypeInput( $new_trigger, 'trigger_type' ); ?>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="insert-marker-trigger post_type_marker"></div>

                        <div class="event-delay ml-24">
                            <label class="mr-24">with delay</label>
							<?php Events\renderTriggerNumberInput( $new_trigger, 'delay', '0' ); ?>
                            <label class="ml-16">seconds</label>
                        </div>

                        <div class="insert-marker-trigger number_page_visit_conditional_marker"></div>
                    </div>
                </div>

            <div class="insert-marker-trigger page_visit_marker"></div>
            <div class="insert-marker-trigger number_page_visit_url_marker"></div>
            <div class="insert-marker-trigger url_click_marker"></div>
            <div class="insert-marker-trigger css_click_marker"></div>
            <div class="insert-marker-trigger copy_element_marker"></div>
            <div class="insert-marker-trigger video_speed_marker"></div>
            <div class="insert-marker-trigger css_mouseover_marker"></div>
            <div class="insert-marker-trigger scroll_pos_marker"></div>
            <div class="insert-marker-trigger email_link_marker"></div>
            <div class="insert-marker-trigger form_field_marker"></div>
            <div class="insert-marker-trigger add_to_cart_marker"></div>
            <div class="insert-marker-trigger purchase_marker"></div>

            <?php $eventsFormFactory = apply_filters( "pys_form_event_factory", [] );
            foreach ( $eventsFormFactory as $activeFormPlugin ) : ?>
                <div class="insert-marker-trigger <?php echo $activeFormPlugin->getSlug(); ?>_marker"></div>
            <?php endforeach; ?>

            <div class="insert-marker-trigger video_view_marker"></div>
            <div class="insert-marker-trigger gads_phone_conversion_marker"></div>
            </div>

            <div class="event_triggers_panel page_visit_panel" data-trigger_type="page_visit"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div class="select-standard-wrap">
                            <select class="select-standard" name="" autocomplete="off" style="width: 100%;">
                                <option value="contains">URL Contains</option>
                                <option value="match">URL Match</option>
                                <option value="param_contains">URL Parameters Contains</option>
                                <option value="param_match">URL Parameters Match</option>
                            </select>
                        </div>

                        <div class="ml-8">
                            <input name="" placeholder="Enter URL" class="input-standard"
                                   type="text">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div class="mb-24">
                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all pages</p>
                </div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                            type="button">Add another URL
                    </button>
                </div>
            </div>

            <div class="event_triggers_panel number_page_visit_panel number_page_visit_url_panel"
                 data-trigger_type="number_page_visit"
                 style="display: none;">

                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div class="select-standard-wrap">
                            <select class="select-standard pys_number_page_visit_triggers"
                                    name=""
                                    autocomplete="off" style="width: 100%;">
                                <option value="any">Any URL`s</option>
                                <option value="contains">URL Contains</option>
                                <option value="match">URL Match</option>
                                <option value="param_contains">URL Parameters Contains</option>
                                <option value="param_match">URL Parameters Match</option>
                            </select>
                        </div>

                        <div class="trigger_url ml-24" style="display: none">
                            <input name="" placeholder="Enter URL"
                                   class="pys_number_page_visit_triggers input-standard"
                                   type="text">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div class="mb-24">
                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all pages</p>
                </div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                            type="button">Add another URL
                    </button>
                </div>
            </div>

            <div class="event_triggers_panel post_type_panel" data-trigger_type="post_type"
                 style="display: none;">
                <div class="trigger_post_type">
					<?php Events\renderPostTypeSelect( $new_trigger, 'post_type_value' ); ?>
                </div>
            </div>

            <div class="event_triggers_panel number_page_visit_panel number_page_visit_conditional_panel d-flex"
                 data-trigger_type="number_page_visit"
                 style="display: none;">
                <div class="d-flex">
                    <div class="trigger_number_page_visit conditional_number_visit ml-24">
						<?php Events\renderTriggerConditionalNumberPage( $new_trigger, 'conditional_number_visit' ); ?>
                    </div>
                    <div class="trigger_number_page_visit number_visit ml-24">
						<?php Events\renderTriggerNumberInput( $new_trigger, 'number_visit', '0', 3 ); ?>
                        <label class="ml-24">visited page</label>
                    </div>
                </div>
            </div>

            <div class="event_triggers_panel url_click_panel mb-16" data-trigger_type="url_click"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div class="select-standard-wrap">
                            <select name="" autocomplete="off" style="width: 100%;"
                                    class="select-standard">>
                                <option value="contains">URL Contains</option>
                                <option value="match">URL Match</option>
                            </select>
                        </div>

                        <div class="ml-24">
                            <input name="" placeholder="Enter URL" type="text" class="input-standard">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>

                </div>

                <div class="insert-marker"></div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                            type="button">Add another URL
                    </button>
                </div>
            </div>

            <div class="event_triggers_panel css_click_panel mb-16" data-trigger_type="css_click"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div>
                            <input name="" placeholder="Enter CSS selector"
                                   class="input-standard"
                                   type="text">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger" type="button">Add another
                        selector
                    </button>
                </div>

                <div class="d-flex align-items-center mt-16">
                    <label class="primary_heading mr-16">Number of clicks required</label>
					<?php Events\renderTriggerNumberInput( $new_trigger, 'click_count', '1', 2 ); ?>
                </div>
                <div class="d-flex align-items-center mt-16">
                    <label class="primary_heading mr-16">Time limit between clicks (ms, 0&nbsp;=&nbsp;no limit)</label>
                    <?php Events\renderTriggerNumberInput( $new_trigger, 'click_time_limit', '3000', 0 ); ?>
                </div>
            </div>

            <div class="event_triggers_panel copy_element_panel mb-16" data-trigger_type="copy_element"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div>
                            <input name="" placeholder="Enter CSS selector (e.g. .article-content)"
                                   class="input-standard" type="text">
                        </div>
                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="insert-marker"></div>
                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger" type="button">Add another selector</button>
                </div>
                <p class="form-text text-small mt-8">Fires when a visitor copies text from an element matching the selector.</p>
            </div>

            <div class="event_triggers_panel embedded_video_speed video_speed_panel" data-trigger_type="video_speed"
                 style="display: none;">
                <div class="event_trigger" data-trigger_id="0">
                    <div class="event_trigger_wrapper">
                        <div class="w-100">
                            <p class="form-text text-small mb-4">Enter video pages URL</p>
                            <div class="d-flex align-items-center w-100">
                                <div class="flex-1">
                                    <input type="hidden" class="pys_event_video_speed_data"
                                           name="<?php echo esc_attr( "pys[event][triggers][$new_index][video_speed_data]" ); ?>"
                                           value="">
									<?php Events\render_multi_select_trigger_input( $new_trigger, 'video_speed_urls', array(), array(), false, '', 'pys_video_speed_urls_event', false ); ?>
                                </div>
                                <div class="ml-24">
                                    <button class="btn btn-primary btn-primary-type2 pys-scan-video"
                                            type="button"
                                            value="Scan videos">Scan videos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pys_video_speed_triggers" data-trigger_id="0" style="display: none;">
                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                        <div class="event_trigger_wrapper">
                            <div class="w-100">
                                <p class="form-text text-small mb-4">Select videos</p>
								<?php Events\render_multi_select_trigger_input( $new_trigger, 'video_speed_triggers', array(), array(), false, '', 'pys_video_speed_triggers_event', false ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="event_trigger mb-16" data-trigger_id="0">
                        <div class="d-flex align-items-center">
                            <label class="primary_heading mr-16">Speed change type</label>
							<?php Events\renderTriggerSelectInput( $new_trigger, 'video_speed_rate', array(
								'increase' => 'Increase speed',
								'decrease' => 'Decrease speed',
								'any'      => 'Any speed change',
							), false, 'pys_video_speed_rate' ); ?>
                        </div>
                    </div>
                </div>
                <div class="video_speed_error mt-16" style="display: none">
                    <div class="event_error critical_message"></div>
                </div>
            </div>

            <div class="event_triggers_panel css_mouseover_panel mb-16" data-trigger_type="css_mouseover"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div>
                            <input name="" placeholder="Enter CSS selector"
                                   class="input-standard"
                                   type="text">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger" type="button">Add another
                        selector
                    </button>
                </div>
            </div>

            <div class="event_triggers_panel scroll_pos_panel mb-16" data-trigger_type="scroll_pos"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div>
							<?php Events\renderTriggerNumberInputPercent( $new_trigger, 'scroll_pos_triggers', $new_index, 30 ); ?>
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="insert-marker"></div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                            type="button">Add another threshold
                    </button>
                </div>
            </div>

			<?php $eventsFormFactory = apply_filters( "pys_form_event_factory", [] );
			foreach ( $eventsFormFactory as $activeFormPlugin ) : ?>
				<?php if ( $activeFormPlugin->getSlug() == "elementor_form" ) : ?>
                    <div class="event_triggers_panel <?php echo $activeFormPlugin->getSlug(); ?>_panel elementor_form"
                         data-trigger_type="<?php echo $activeFormPlugin->getSlug(); ?>"
                         style="display: none;">

						<?php
                            $data = $new_trigger->getElementorFormData();
                            $urls = $new_trigger->getElementorFormUrls();
                            $urls = array_combine( $urls, $urls );
						?>
                        <div class="event_trigger" data-trigger_id="0">
                            <div class="event_trigger_wrapper">
                                <div class="w-100">
                                    <p class="form-text text-small mb-4">Enter Elementor form pages URL</p>

                                    <div class="d-flex align-items-center w-100">
                                        <div class="flex-1">
                                            <input type="hidden" class="pys_event_elementor_form_data"
                                                   name="<?php echo esc_attr( "pys[event][triggers][$new_index][elementor_form_data]" ); ?>"
                                                   value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
											<?php Events\render_multi_select_trigger_input( $new_trigger, 'elementor_form_urls', $urls, $new_trigger->getElementorFormUrls(), false, '', 'pys_elementor_form_urls_event', false ); ?>
                                        </div>

                                        <div class="ml-24">
                                            <button class="btn btn-primary btn-primary-type2 pys-scan-elementor-form"
                                                    type="button"
                                                    value="Scan forms">Scan forms
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pys_elementor_form_triggers" data-trigger_id="0"
                             style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                            <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                                <div class="event_trigger_wrapper">
                                    <div class="w-100">
                                        <p class="form-text text-small mb-4">Select forms</p>
										<?php Events\render_multi_select_trigger_form_input( $new_trigger, $activeFormPlugin, false, '', false, 'pys_elementor_form_triggers_event' ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="event_trigger mb-16" data-trigger_id="0">
                                <div class="form_submit_mode_wrapper">
                                    <h4 class="primary_heading mb-8">Trigger Form Submit Mode</h4>
                                    <div class="d-flex flex-column radio-inputs-wrap">
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'both', 'Send after page reload + AJAX' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'reload_only', 'Only after page reload' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'ajax_only', 'Only AJAX' ); ?>
                                    </div>
                                    <p class="form-text text-small mt-8">Change this option if the trigger doesn't work, or if two events are fired.</p>
                                </div>
                            </div>
                            <div class="event_trigger" data-trigger_id="0">
                                <div class="switcher_event_disabled_form_action d-flex align-items-center">
									<?php Events\renderSwitcherTriggerFormInput( $new_trigger, $activeFormPlugin ); ?>
                                    <h4 class="switcher-label secondary_heading">Disable the Form event for the same
                                        forms</h4>
                                </div>
                            </div>
                        </div>

                        <div class="elementor_form_error mt-16" style="display: none">
                            <div class="event_error critical_message"></div>
                        </div>
                    </div>
				<?php else: ?>
                    <div class="event_triggers_panel <?php echo $activeFormPlugin->getSlug(); ?>_panel"
                         data-trigger_type="<?php echo $activeFormPlugin->getSlug(); ?>"
                         style="display: none;">
                        <div class="event_trigger mb-16" data-trigger_id="0">
                            <div class="event_trigger_wrapper">
                                <div class="w-100">
                                    <p class="form-text text-small mb-4">Select Forms to Trigger the Event</p>

                                    <div class="select_event_trigger_form_wrapper">
										<?php Events\render_multi_select_trigger_form_input( $new_trigger, $activeFormPlugin, false, '', false ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="event_trigger mb-16" data-trigger_id="0">
                            <div class="event_trigger_wrapper">
                                <div class="form_submit_mode_wrapper">
                                    <h4 class="primary_heading mb-8">Trigger Form Submit Mode</h4>
                                    <div class="d-flex flex-column radio-inputs-wrap">
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'both', 'Send after page reload + AJAX' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'reload_only', 'Only after page reload' ); ?>
                                        <?php Events\renderTriggerRadioFormInput( $new_trigger, $activeFormPlugin, 'ajax_only', 'Only AJAX' ); ?>
                                    </div>
                                    <p class="form-text text-small mt-8">Change this option if the trigger doesn't work, or if two events are fired.</p>
                                </div>
                            </div>
                        </div>
                        <div class="event_trigger" data-trigger_id="0">
                            <div class="event_trigger_wrapper">
                                <div class="switcher_event_form_disable_event d-flex align-items-center">
									<?php Events\renderSwitcherTriggerFormInput( $new_trigger, $activeFormPlugin ); ?>
                                    <h4 class="switcher-label secondary_heading">Disable the Form event for the same
                                        forms</h4>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>

            <div class="event_triggers_panel form_field_panel" data-trigger_type="form_field" style="display: none;">
                <?php
                    $data = $new_trigger->getAnyFormData();
                    $urls = $new_trigger->getAnyFormUrls();
                    $urls = array_combine( $urls, $urls );
                ?>
                <div class="event_trigger" data-trigger_id="0">
                    <div class="event_trigger_wrapper">
                        <div class="w-100">
                            <p class="form-text text-small mb-4">Enter form pages URL</p>

                            <div class="d-flex align-items-center w-100">
                                <div class="flex-1">
                                    <input type="hidden" class="pys_event_form_field_data"
                                           name="<?php echo esc_attr( "pys[event][triggers][$new_index][form_field_data]" ); ?>"
                                           value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
                                    <?php Events\render_multi_select_trigger_input( $new_trigger, 'form_field_urls', $urls, $new_trigger->getAnyFormUrls(), false, '', 'pys_form_field_urls_event', false ); ?>
                                </div>

                                <div class="ml-24">
                                    <button class="btn btn-primary btn-primary-type2 pys-scan-forms"
                                            type="button"
                                            value="Scan forms">Scan forms
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pys_form_field_triggers" data-trigger_id="0"
                     style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                        <div class="event_trigger_wrapper">
                            <div class="w-100">
                                <p class="form-text text-small mb-4">Select form</p>
                                <?php Events\render_select_trigger_any_form_input( $new_trigger, false, '', false, 'pys_form_field_triggers_event' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pys_form_fields" data-trigger_id="0"
                     style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                        <div class="event_trigger_wrapper">
                            <div class="w-100">
                                <p class="form-text text-small mb-4">Select field</p>
                                <?php Events\render_select_trigger_field_input( $new_trigger, false, '', false, 'pys_form_field_event' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="elementor_form_error mt-16" style="display: none">
                    <div class="event_error critical_message"></div>
                </div>
            </div>

            <div class="event_triggers_panel embedded_video_view video_view_panel" data-trigger_type="video_view"
                 style="display: none;">
				<?php $data = $new_trigger->getVideoViewData();
				$urls = $new_trigger->getVideoViewUrls();
				$urls = array_combine( $urls, $urls );
				$selected = $new_trigger->getVideoViewTriggers();
				$triggers = !empty( $data ) ? array_combine( array_column( $data, 'id' ), array_column( $data, 'title' ) ) : array();
				$play_options = array(
					'0%'   => 'Play',
					'10%'  => '10%',
					'50%'  => '50%',
					'90%'  => '90%',
					'100%' => '100%',
				);
				?>
                <div class="event_trigger" data-trigger_id="0">
                    <div class="event_trigger_wrapper">
                        <div class="w-100">
                            <p class="form-text text-small mb-4">Enter video pages URL</p>

                            <div class="d-flex align-items-center w-100">
                                <div class="flex-1">
                                    <input type="hidden" class="pys_event_video_view_data"
                                           name="<?php echo esc_attr( "pys[event][triggers][$new_index][video_view_data]" ); ?>"
                                           value="<?php echo htmlspecialchars( json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ), ENT_QUOTES, 'UTF-8' ) ?>">
									<?php Events\render_multi_select_trigger_input( $new_trigger, 'video_view_urls', $urls, $new_trigger->getVideoViewUrls(), false, '', 'pys_video_view_urls_event', false ); ?>
                                </div>

                                <div class="ml-24">
                                    <button class="btn btn-primary btn-primary-type2 pys-scan-video"
                                            type="button"
                                            value="Scan videos">Scan videos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pys_video_view_triggers" data-trigger_id="0"
                     style="<?php echo empty( $data ) ? 'display: none;' : ''; ?>">
                    <div class="event_trigger mb-16 mt-16" data-trigger_id="0">
                        <div class="event_trigger_wrapper">
                            <div class="w-100">
                                <p class="form-text text-small mb-4">Select videos</p>
								<?php Events\render_multi_select_trigger_input( $new_trigger, 'video_view_triggers', $triggers, $selected, false, '', 'pys_video_view_triggers_event', false ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="event_trigger mb-16" data-trigger_id="0">
                        <div class="d-flex align-items-center">
                            <label class="primary_heading mr-16">Select trigger</label>
							<?php Events\renderTriggerSelectInput( $new_trigger, 'video_view_play_trigger', $play_options, false, 'pys_video_view_play_trigger' ); ?>
                        </div>
                    </div>

                    <div class="event_trigger" data-trigger_id="0">
                        <div class="event_trigger_wrapper">
                            <div class="switcher_event_disable_watch_video d-flex align-items-center">
								<?php Events\renderSwitcherTriggerInput( $new_trigger, 'video_view_disable_watch_video' ); ?>
                                <h4 class="switcher-label secondary_heading">Disable the automatic WatchVideo
                                    events for the video</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="video_view_error mt-16" style="display: none">
                    <div class="event_error critical_message"></div>
                </div>
            </div>

            <div class="event_triggers_panel email_link_panel" data-trigger_type="email_link" style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div class="select-standard-wrap">
                            <select class="select-standard pys_email_link_triggers"
                                    name=""
                                    autocomplete="off" style="width: 100%;">
                                <option value="any">All links</option>
                                <option value="match">Link Match</option>
                                <option value="contains">Link Include</option>
                            </select>
                        </div>

                        <div class="trigger_url ml-24" style="display: none">
                            <input name="" placeholder="Enter Link"
                                   class="input-standard"
                                   type="text">
                        </div>

                        <div>
							<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger" type="button">Add another Link
                    </button>
                </div>

                <div class="mt-16" data-trigger_id="0">
                    <div class="event_trigger_wrapper">
                        <div class="switcher_event_email_link_event d-flex align-items-center">
							<?php Events\renderSwitcherTriggerInput( $new_trigger, 'email_link_disable_email_event' ); ?>
                            <h4 class="switcher-label secondary_heading">Disable the default Email event for the same
                                action</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="event_triggers_panel add_to_cart_panel" data-trigger_type="add_to_cart">
                <div class="event_trigger mb-16" data-trigger_id="0">
                    <div class="d-flex align-items-center switcher_event_track_value_and_currency">
                        <?php Events\renderSwitcherTriggerInput( $new_trigger, 'track_value_and_currency' ); ?>
                        <h4 class="switcher-label">Track value and currency</h4>
                    </div>
                    <p class="mt-8"><?php _e('We will add value and currency parameters, similar to the default WooCommerce add to cart event. If you use this option, don\'t manually add value or currency parameters to this event.', 'pys');?></p>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <h4><b><?php _e('Warning:', 'pys');?></b> <?php _e('Use it only if you must replace the default Add To Cart event with a custom event. Don\'t configure an add to cart event with this trigger, the plugin fires such an event automatically.', 'pys');?></h4>
                    </div>
                </div>
            </div>

            <div class="event_triggers_panel purchase_panel" data-trigger_type="purchase">
                <div class="event_trigger mb-16" data-trigger_id="0">
                    <div class="d-flex align-items-center switcher_event_transaction_only_action">
                        <?php Events\renderSwitcherTriggerInput( $new_trigger, 'purchase_transaction_only' ); ?>
                        <h4 class="switcher-label">Fire this event for transaction only</h4>
                    </div>
                </div>
                <div class="event_trigger mb-16" data-trigger_id="0">
                    <div class="d-flex align-items-center switcher_event_track_transaction_ID">
                        <?php Events\renderSwitcherTriggerInput( $new_trigger, 'track_transaction_ID' ); ?>
                        <h4 class="switcher-label">Track transaction ID</h4>
                    </div>
                </div>
                <div class="event_trigger mb-16" data-trigger_id="0">
                    <div class="d-flex align-items-center switcher_event_track_value_and_currency">
                        <?php Events\renderSwitcherTriggerInput( $new_trigger, 'track_value_and_currency' ); ?>
                        <h4 class="switcher-label">Track value and currency</h4>
                    </div>
                    <p class="mt-8"><?php _e('We will add value and currency parameters, similar to the default WooCommerce Purchase event. If you use this option, don\'t manually add value or currency parameters to this event.', 'pys');?></p>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <h4><b><?php _e('Warning:', 'pys');?></b> <?php _e('Use it only if you must replace the default Purchase event with a custom event. Don\'t configure a Purchase event with this trigger, the plugin fires such an event automatically. ', 'pys');?></h4>
                    </div>
                </div>
            </div>

            <div class="event_triggers_panel gads_phone_conversion_panel" data-trigger_type="gads_phone_conversion"
                 style="display: none;">
                <div class="event_trigger mb-16" data-trigger_id="-1" style="display: none;">
                    <div class="event_trigger_wrapper">
                        <div class="select-standard-wrap">
                            <select class="select-standard" name="" autocomplete="off" style="width: 100%;">
                                <option value="contains">URL Contains</option>
                                <option value="match">URL Match</option>
                                <option value="param_contains">URL Parameters Contains</option>
                                <option value="param_match">URL Parameters Match</option>
                            </select>
                        </div>

                        <div class="ml-8">
                            <input name="" placeholder="Enter URL" class="input-standard"
                                   type="text">
                        </div>

                        <div>
					        <?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="insert-marker"></div>

                <div class="mb-24">
                    <p class="form-text text-small">You can also use <b>*</b> as the trigger URL on all pages</p>
                </div>

                <div>
                    <button class="btn btn-primary btn-primary-type2 add-event-trigger"
                            type="button">Add another URL
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-style4 card-static">
        <div class="card-header card-header-style3">
            <p class="secondary_heading_type2">
                Conditions
            </p>
        </div>
        <div class="card-body pys_condition_wrapper">
            <div class="d-flex align-items-center mb-24">
                <?php Events\renderSwitcherInput( $event, 'conditions_enabled' ); ?>
                <h4 class="switcher-label secondary_heading">Enable Conditions</h4>
            </div>
            <div class="conditions_logic d-flex flex-column mb-24">
                <h4 class="primary_heading mb-16">Logic</h4>
                <div class="d-flex">
                    <?php Events\render_radio_input( $event, 'conditions_logic', 'AND', 'AND' ); ?>
                    <?php Events\render_radio_input( $event, 'conditions_logic', 'OR', 'OR' ); ?>
                </div>
            </div>
            <div class="pys_conditions_wrapper">
                <?php
                $event_conditions = $event->getConditions();
                if ( !empty( $event_conditions ) ) :
                    foreach ( $event_conditions as $event_condition ) :
                        $i = $event_condition->getConditionIndex();
                        $trigger_type = $event_condition->getConditionType();
                        $event_condition->renderConditionalBlock(true);
                    endforeach;
                endif;
                ?>
            </div>
            <div id="pys-add-condition">
                <button class="btn btn-primary btn-primary-type2 add-condition" type="button"><?php _e('Add another Condition', 'pys'); ?></button>
            </div>
            <div id="pys_add_event_condition" style="display: none;">

                <?php
                $new_condition = new ConditionalEvent('url_filters');
                $new_condition_index = $new_condition->getConditionIndex();
                ?>

                <input type="hidden" name="<?php echo esc_attr( "pys[event][conditions][$new_condition_index][cloned_event]" ); ?>"
                       value="1">
                <?php $new_condition->renderConditionalBlock();?>
                <?php $new_condition->renderConditionalsPanel();?>

            </div>
        </div>
    </div>
	<?php if ( Facebook()->enabled() ) :
		$facebook_configured = Facebook()->enabled() && !empty( Facebook()->getPixelIDs() );
		?>
        <div class="card card-style4" data-configured="<?php echo $facebook_configured ? '1' : '0' ?>">
            <input type="checkbox" class="event-settings-checkbox" id="meta_custom_event_switch" style="display: none">

            <div class="card-header card-header-style3 d-flex justify-content-between align-items-center">
                <div class="custom-event-pixel-header">
                    <img src="<?php echo PYS_URL . '/dist/images/meta-logo.svg' ?>" alt="meta-logo"
                         class="pixel-logo">
                    <h4 class="font-semibold main-switcher">Meta</h4>
                </div>

                <div class="custom-event-pixel-status">
					<?php
					$enabled = $event->facebook_enabled && $facebook_configured;
					?>
                    <div class="pixel-status">
                        <p class="status pixel-enabled" style="<?php echo $enabled ? '' : 'display: none' ?>">
                            Active
                        </p>

                        <p class="status pixel-disabled" style="<?php echo $enabled ? 'display: none' : '' ?>">
                            Inactive
                        </p>
                    </div>

                    <label class="card-header-label" for="meta_custom_event_switch">
						<?php include PYS_VIEW_PATH . '/UI/properties-button-off.php'; ?>
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="facebook-not-configured-error mb-24">
                    <div class="event_error critical_message">Error: Meta pixel is not configured</div>
                </div>

                <div class="d-flex align-items-center mb-24">
					<?php Events\renderSwitcherInput( $event, 'facebook_enabled' ); ?>
                    <h4 class="switcher-label secondary_heading">Enable on Facebook</h4>
                </div>

                <div id="facebook_panel" class="pixel_panel">
                    <div class="mb-24">
                        <div class="mb-8">
                            <label class="custom-event-label">Fire for:</label>
                        </div>

						<?php Events\renderFacebookEventId( $event, 'facebook_pixel_id' ); ?>
                    </div>

                    <div class="mb-24">
                        <div class="mb-8">
                            <label class="custom-event-label">Event type:</label>
                        </div>

						<?php Events\renderFacebookEventTypeInput( $event, 'facebook_event_type' ); ?>

                        <div class="facebook-custom-event-type mt-16">
							<?php Events\renderTextInput( $event, 'facebook_custom_event_type', 'Enter name' ); ?>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
						<?php Events\renderSwitcherInput( $event, 'facebook_params_enabled' ); ?>
                        <h4 class="switcher-label secondary_heading">Add Parameters</h4>
                    </div>

                    <div id="facebook_params_panel">
						<?php
						$facebook_events = PYS_Event_Definitions::get_facebook_events();
						$fields = $facebook_events[ $event->facebook_event_type ];
						foreach ( $fields as $field ) :
							$param_key = $field[ 'label' ];
							$param_data = isset( $event->facebook_params[ $param_key ] ) ? $event->facebook_params[ $param_key ] : null;

							// Support both old format (string) and new format (array with value, selector, dynamic)
							$param_value = '';
							$param_selector = '';
							$param_dynamic = false;

							if ( is_array( $param_data ) && isset( $param_data['value'] ) ) {
								// New format
								$param_value = $param_data['value'];
								$param_selector = isset( $param_data['selector'] ) ? $param_data['selector'] : '';
								$param_dynamic = isset( $param_data['dynamic'] ) ? (bool) $param_data['dynamic'] : false;
							} elseif ( !empty( $param_data ) ) {
								// Old format (backward compatibility)
								$param_value = $param_data;
							}
						?>
                        <input type="hidden" name="pys[event][facebook_params][<?= $param_key ?>][input_type]" value="<?= esc_attr( $field['input_type'] ?? 'string' ) ?>">
                            <div class="mt-24 param-field-wrapper" data-param-key="<?= esc_attr( $param_key ) ?>">
                                <div class="mb-8">
                                    <label class="custom-event-label"><?= $field[ 'label' ] ?></label>
                                </div>

                                <!-- Value input (shown when dynamic mode is OFF) -->
                                <input type="text"
                                       name="pys[event][facebook_params][<?= $param_key ?>][value]"
                                       value="<?= esc_attr( $param_value ) ?>"
                                       class="input-standard param-value-input"
                                       placeholder=""
                                       style="<?= $param_dynamic ? 'display: none;' : '' ?>"
                                />

                                <!-- Selector input (shown when dynamic mode is ON) -->
                                <input type="text"
                                       name="pys[event][facebook_params][<?= $param_key ?>][selector]"
                                       value="<?= esc_attr( $param_selector ) ?>"
                                       class="input-standard param-selector-input"
                                       placeholder="CSS Selector (e.g., .price)"
                                       style="<?= $param_dynamic ? '' : 'display: none;' ?>"
                                />

                                <!-- Dynamic checkbox -->
                                <div class="mt-8 d-flex align-items-center">
                                    <input type="checkbox"
                                           name="pys[event][facebook_params][<?= $param_key ?>][dynamic]"
                                           value="1"
                                           class="param-dynamic-checkbox"
                                           <?= $param_dynamic ? 'checked' : '' ?>
                                    />
                                    <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                </div>
                            </div>
						<?php endforeach; ?>

                        <!-- Custom Facebook Params Template -->
                        <div class="facebook-custom-param custom-param-template" data-param_id="0"
                             style="display: none;">
                            <div class="mt-24">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <input name="" placeholder="Enter name"
                                               class="custom-param-name input-standard"
                                               type="text">
                                    </div>

                                    <div class="ml-16" style="flex: 1;">
                                        <input name="" placeholder="Enter value"
                                               class="custom-param-value input-standard"
                                               type="text"
                                               style="width: 100%;">
                                    </div>

                                    <div class="ml-16" style="flex: 1; display: none;">
                                        <input name="" placeholder="CSS Selector (e.g., .price)"
                                               class="custom-param-selector input-standard"
                                               type="text"
                                               style="width: 100%;">
                                    </div>

                                    <div>
									<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                    </div>
                                </div>

                                <!-- Dynamic checkbox for custom params -->
                                <div class="mt-8 d-flex align-items-center">
                                    <input type="checkbox"
                                           name=""
                                           value="1"
                                           class="custom-param-dynamic-checkbox"
                                    />
                                    <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                </div>
                            </div>
                        </div>

						<?php foreach ( $event->getFacebookCustomParams() as $key => $custom_param ) :
							$param_id = $key + 1;
							$param_value = isset( $custom_param['value'] ) ? $custom_param['value'] : '';
							$param_selector = isset( $custom_param['selector'] ) ? $custom_param['selector'] : '';
							$param_dynamic = isset( $custom_param['dynamic'] ) ? (bool) $custom_param['dynamic'] : false;
						?>

                            <div class="facebook-custom-param"
                                 data-param_id="<?php echo $param_id; ?>">
                                <div class="mt-24">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input type="text" placeholder="Enter name"
                                                   class="custom-param-name input-standard"
                                                   name="pys[event][facebook_custom_params][<?php echo $param_id; ?>][name]"
                                                   value="<?php echo esc_attr( $custom_param[ 'name' ] ); ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1;<?= $param_dynamic ? 'display: none;' : '' ?>">
                                            <input type="text" placeholder="Enter value"
                                                   class="custom-param-value input-standard"
                                                   name="pys[event][facebook_custom_params][<?php echo $param_id; ?>][value]"
                                                   value="<?php echo esc_attr( $param_value ); ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1; <?= $param_dynamic ? '' : 'display: none;' ?>">
                                            <input type="text" placeholder="CSS Selector (e.g., .price)"
                                                   class="custom-param-selector input-standard"
                                                   name="pys[event][facebook_custom_params][<?php echo $param_id; ?>][selector]"
                                                   value="<?php echo esc_attr( $param_selector ); ?>">
                                        </div>

                                        <div>
										<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>

                                    <!-- Dynamic checkbox for custom params -->
                                    <div class="mt-8 d-flex align-items-center">
                                        <input type="checkbox"
                                               name="pys[event][facebook_custom_params][<?php echo $param_id; ?>][dynamic]"
                                               value="1"
                                               class="custom-param-dynamic-checkbox"
                                               <?= $param_dynamic ? 'checked' : '' ?>
                                        />
                                        <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                    </div>
                                </div>
                            </div>

						<?php endforeach; ?>

                        <div class="insert-marker"></div>

                        <div class="mt-24">
							<?php renderAddCustomParameterButton( 'facebook' ); ?>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mt-24 mb-24">
                        <?php Events\renderSwitcherInput( $event, 'facebook_track_single_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce product data on single product pages</h4>
                    </div>
                    <div class="d-flex align-items-center mb-24">
                        <?php Events\renderSwitcherInput( $event, 'facebook_track_cart_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce cart data when possible</h4>
                    </div>

                    <p class="primary-heading-color mt-24">
                        <span class="primary-text-color primary_heading">Important: </span>
                        verify your custom events inside your Ads Manager:
                        <a
                                href="https://www.youtube.com/watch?v=Iyu-pSbqcFI" target="_blank"
                                class="link">watch this video to learn how
                        </a>
                    </p>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php
	$ga_tags_configured = ( Ads()->enabled() && !empty( Ads()->getPixelIDs() ) ) || ( GA()->enabled() && !empty( GA()->getPixelIDs() ) );
	?>

    <div class="card card-style4" data-configured="<?php echo $ga_tags_configured ? '1' : '0' ?>">
        <input type="checkbox" class="event-settings-checkbox" id="google_tags_custom_event_switch"
               style="display: none">

        <div class="card-header card-header-style3 d-flex justify-content-between align-items-center">
            <div class="custom-event-pixel-header">
                <img src="<?php echo PYS_URL . '/dist/images/google-tags-logo.svg' ?>" alt="google-tags-logo"
                     class="pixel-logo">
                <h4 class="font-semibold main-switcher">Google Tags</h4>
            </div>

            <div class="custom-event-pixel-status">
				<?php
				$enabled = $event->ga_ads_enabled && $ga_tags_configured;
				?>
                <div class="pixel-status">
                    <p class="status pixel-enabled" style="<?php echo $enabled ? '' : 'display: none' ?>">
                        Active
                    </p>

                    <p class="status pixel-disabled" style="<?php echo $enabled ? 'display: none' : '' ?>">
                        Inactive
                    </p>
                </div>

                <label class="card-header-label" for="google_tags_custom_event_switch">
					<?php include PYS_VIEW_PATH . '/UI/properties-button-off.php'; ?>
                </label>
            </div>
        </div>

        <div class="card-body">
            <div class="gatags-not-configured-error mb-24">
                <div class="event_error critical_message">Error: Google tags are not configured</div>
            </div>

            <div class="d-flex align-items-center mb-24">
				<?php Events\renderSwitcherInput( $event, 'ga_ads_enabled' ); ?>
                <h4 class="switcher-label secondary_heading">Enable on Google Tags</h4>
            </div>

            <div id="merged_analytics_panel" class="pixel_panel">
                <div class="mb-24">
                    <div class="mb-8">
                        <label class="custom-event-label">Fire for:</label>
                    </div>

					<?php Events\renderMergedGaEventId( $event, 'ga_ads_pixel_id' ); ?>
                </div>

                <?php
                $has_gads_phone_trigger = false;
                foreach ( $event->getTriggers() as $event_trigger ) {
                    if ( $event_trigger->getTriggerType() === 'gads_phone_conversion' ) {
                        $has_gads_phone_trigger = true;
                        break;
                    }
                }

                $ga_ads_phone_numbers = $event->google_ads_phone_numbers;
                if ( empty( $ga_ads_phone_numbers ) ) {
	               $ga_ads_phone_numbers = array( 0 => array( 'number' => '', 'label' => '' ) );
                }
                ?>

                <div class="mb-24 ga_ads_conversion_label_wrap"<?php if ( $has_gads_phone_trigger ) : ?> style="display: none;"<?php endif; ?>>
                    <div class="mb-8">
                        <label class="custom-event-label">Conversion Label</label>
                    </div>

                    <div class="ga_conversion_label">
						<?php Events\renderTextInput( $event, 'ga_ads_conversion_label' ); ?>

                        <p class="form-text text-small mt-6">Optional</p>
                    </div>
                </div>

                <div class="mb-24 ga_ads_phone_numbers_wrap"<?php if ( ! $has_gads_phone_trigger ) : ?> style="display: none;"<?php endif; ?>>
                    <div class="mb-8">
                        <label class="custom-event-label">The phone number you want to track:</label>
                    </div>

                    <div class="event_trigger pys_gads_phone_number_row pys_gads_phone_number_template mb-16"
                         data-trigger_id="-1" style="display: none;">
                        <div class="event_trigger_wrapper d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <input data-field="number" name="" placeholder="Enter phone number"
                                       class="input-standard" type="text">
                            </div>

                            <div class="ml-8">
                                <input data-field="label" name="" placeholder="Enter conversion label"
                                       class="input-standard" type="text">
                            </div>

                            <div>
								<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    foreach ( $ga_ads_phone_numbers as $key => $number ) :
                        $number_value = is_array( $number ) && isset( $number['number'] ) ? $number['number'] : '';
                        $label_value  = is_array( $number ) && isset( $number['label'] ) ? $number['label'] : '';
                    ?>
                        <div class="event_trigger pys_gads_phone_number_row mb-16"
                             data-trigger_id="<?php echo esc_attr( $key ); ?>">
                            <div class="event_trigger_wrapper d-flex align-items-center">
                                <div>
                                    <input type="text" placeholder="Enter phone number" class="input-standard"
                                           name='<?php echo esc_attr( "pys[event][ga_ads_phone_numbers][$key][number]" ); ?>'
                                           value="<?php echo esc_attr( $number_value ); ?>">
                                </div>

                                <div class="ml-8">
                                    <input type="text" placeholder="Enter conversion label" class="input-standard"
                                           name='<?php echo esc_attr( "pys[event][ga_ads_phone_numbers][$key][label]" ); ?>'
                                           value="<?php echo esc_attr( $label_value ); ?>">
                                </div>

                                <div>
									<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pys_gads_phone_number_insert_marker"></div>

                    <div class="mt-8">
                        <button class="btn btn-primary btn-primary-type2 pys_gads_phone_add_number"
                                type="button">Add another phone number
                        </button>
                    </div>
                </div>

                <!-- v4 Google params  -->
                <script>
					<?php
					$fields = array();
					$ga_events = PYS_Event_Definitions::get_ga_events();
					foreach ( $ga_events as $group => $items ) {
						foreach ( $items as $name => $elements ) {
							$fields[] = array(
								"name"   => $name,
								'fields' => $elements
							);
						}
					}

					?>
                    const ga_fields = <?=json_encode( $fields )?>
                </script>

                <div class="mb-24">
                    <div class="mb-8">
                        <label class="custom-event-label">Event</label>
                    </div>

					<?php Events\renderGoogleAnalyticsMergedActionInput( $event, 'ga_ads_event_action' ); ?>
                </div>

                <div class="mb-24" id="ga-ads-custom-action_g4">
                    <div class="mb-8">
                        <label class="custom-event-label">Enter name</label>
                    </div>

                    <div class="ga_ads_custom_event_action">
						<?php Events\renderTextInput( $event, 'ga_ads_custom_event_action' ); ?>
                    </div>
                </div>

                <div class="ga-ads-param-list">
					<?php
					foreach ( $event->getMergedGaParams() as $key => $val ) : ?>
                        <div class="mb-24 ga_ads_param param-field-wrapper" data-param-key="<?= esc_attr( $key ) ?>">
                            <div class="mb-8">
                                <label class="custom-event-label"><?= $key ?></label>
                            </div>

							<?php Events\renderMergedGAParamInput( $key, $val ); ?>
                        </div>
					<?php endforeach; ?>
                </div>

                <div class="ga-ads-custom-param-list">
					<?php
					foreach ( $event->getGAMergedCustomParams() as $key => $custom_param ) :
						$param_id = $key + 1;
						$param_value = isset( $custom_param['value'] ) ? $custom_param['value'] : '';
						$param_selector = isset( $custom_param['selector'] ) ? $custom_param['selector'] : '';
						$param_dynamic = isset( $custom_param['dynamic'] ) ? (bool) $custom_param['dynamic'] : false;
					?>

                        <div class="ga-ads-custom-param"
                             data-param_id="<?php echo $param_id; ?>">
                            <div class="mt-24">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <input type="text" placeholder="Enter name"
                                               class="custom-param-name input-standard"
                                               name="pys[event][ga_ads_custom_params][<?php echo $param_id; ?>][name]"
                                               value="<?php echo esc_attr( $custom_param[ 'name' ] ); ?>">
                                    </div>

                                    <div class="ml-16" style="flex: 1;">
                                        <input type="text" placeholder="Enter value"
                                               class="custom-param-value input-standard"
                                               name="pys[event][ga_ads_custom_params][<?php echo $param_id; ?>][value]"
                                               value="<?php echo esc_attr( $param_value ); ?>"
                                               style="width: 100%; <?= $param_dynamic ? 'display: none;' : '' ?>">
                                    </div>

                                    <div class="ml-16" style="flex: 1; <?= $param_dynamic ? '' : 'display: none;' ?>">
                                        <input type="text" placeholder="CSS Selector (e.g., .price)"
                                               class="custom-param-selector input-standard"
                                               name="pys[event][ga_ads_custom_params][<?php echo $param_id; ?>][selector]"
                                               value="<?php echo esc_attr( $param_selector ); ?>"
                                               style="width: 100%;">
                                    </div>

                                    <div>
									<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                    </div>
                                </div>

                                <!-- Dynamic checkbox for custom params -->
                                <div class="mt-8 d-flex align-items-center">
                                    <input type="checkbox"
                                           name="pys[event][ga_ads_custom_params][<?php echo $param_id; ?>][dynamic]"
                                           value="1"
                                           class="custom-param-dynamic-checkbox"
                                           <?= $param_dynamic ? 'checked' : '' ?>
                                    />
                                    <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>

                <div class="mt-24">
					<?php renderAddCustomParameterButton( 'ga-ads' ); ?>
                </div>

                <div id="pys_ga_ads_track_woo_data">
                    <div class="d-flex align-items-center mt-24 mb-24">
		                <?php Events\renderSwitcherInput( $event, 'ga_ads_track_single_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce product data on single product pages</h4>
                    </div>
                    <div class="d-flex align-items-center mb-24">
		                <?php Events\renderSwitcherInput( $event, 'ga_ads_track_cart_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce cart data when possible</h4>
                    </div>
                </div>

                <p class="primary-heading-color mt-24">
                    The following parameters are automatically tracked: <span class="event-parameter-list">content_name, event_url,
                    post_id, post_type</span>.
                </p>

                <p class="primary-heading-color">
                    The paid version tracks the <span class="event-parameter-list">event_hour, event_month</span>, and
                    <span class="event-parameter-list">event_day</span>.
                </p>

                <p class="primary-heading-color mt-24 ga_woo_info" style="display: none">
                    <span class="primary-text-color primary_heading">ATTENTION: </span>
                    ​ the plugin automatically tracks ecommerce specific
                    events for WooCommerce and Easy Digital Downloads. Make sure you really need this event.
                </p>
            </div>
        </div>
    </div>

	<?php if ( Tiktok()->enabled() ) :
		$tiktok_configured = Tiktok()->enabled() && !empty( Tiktok()->getPixelIDs() );
		?>
        <div class="card card-style4" data-configured="<?php echo $tiktok_configured ? '1' : '0' ?>">
            <input type="checkbox" class="event-settings-checkbox" id="tiktok_custom_event_switch"
                   style="display: none">

            <div class="card-header card-header-style3 d-flex justify-content-between align-items-center">
                <div class="custom-event-pixel-header">
                    <img src="<?php echo PYS_URL . '/dist/images/tiktok-logo.svg' ?>" alt="tiktok-logo"
                         class="pixel-logo">
                    <h4 class="font-semibold main-switcher">TikTok</h4>
                </div>

                <div class="custom-event-pixel-status">
					<?php
					$enabled = $event->tiktok_enabled && $tiktok_configured;
					?>
                    <div class="pixel-status">
                        <p class="status pixel-enabled" style="<?php echo $enabled ? '' : 'display: none' ?>">
                            Active
                        </p>

                        <p class="status pixel-disabled" style="<?php echo $enabled ? 'display: none' : '' ?>">
                            Inactive
                        </p>
                    </div>

                    <label class="card-header-label" for="tiktok_custom_event_switch">
						<?php include PYS_VIEW_PATH . '/UI/properties-button-off.php'; ?>
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="tiktok-not-configured-error mb-24">
                    <div class="event_error critical_message">Error: TikTok is not configured</div>
                </div>

                <div class="d-flex align-items-center mb-24">
					<?php Events\renderSwitcherInput( $event, 'tiktok_enabled' ); ?>
                    <h4 class="switcher-label secondary_heading">Enable on TikTok</h4>
                </div>

                <div id="tiktok_panel" class="pixel_panel">
                    <div class="mb-24">
                        <div class="mb-8">
                            <label class="custom-event-label">Fire for:</label>
                        </div>

						<?php Events\renderTikTokEventId( $event, 'tiktok_pixel_id' ); ?>
                    </div>

                    <div>
                        <div class="mb-8">
                            <label class="custom-event-label">Event type:</label>
                        </div>

						<?php Events\renderTikTokEventTypeInput( $event, 'tiktok_event_type' ); ?>

                        <div class="tiktok-custom-event-type mt-16">
							<?php Events\renderTextInput( $event, 'tiktok_custom_event_type', 'Enter name' ); ?>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mt-24">
						<?php Events\renderSwitcherInput( $event, 'tiktok_params_enabled' ); ?>
                        <h4 class="switcher-label secondary_heading">Add Parameters</h4>
                    </div>

                    <div id="tiktok_params_panel">
						<?php
						$tiktok_events = PYS_Event_Definitions::get_tiktok_events();
						$fields = $tiktok_events[ $event->tiktok_event_type ];
						foreach ( $fields as $field ) :
							$param_key = $field[ 'label' ];
							$param_data = isset( $event->tiktok_params[ $param_key ] ) ? $event->tiktok_params[ $param_key ] : null;

							// Support both old format (string) and new format (array with value, selector, dynamic)
							$param_value = '';
							$param_selector = '';
							$param_dynamic = false;

							if ( is_array( $param_data ) && isset( $param_data['value'] ) ) {
								// New format
								$param_value = $param_data['value'];
								$param_selector = isset( $param_data['selector'] ) ? $param_data['selector'] : '';
								$param_dynamic = isset( $param_data['dynamic'] ) ? (bool) $param_data['dynamic'] : false;
							} elseif ( !empty( $param_data ) ) {
								// Old format (backward compatibility)
								$param_value = $param_data;
							}
						?>
                            <input type="hidden" name="pys[event][tiktok_params][<?php echo esc_attr( $param_key ); ?>][input_type]" value="<?php echo esc_attr( $field['input_type'] ?? 'string' ); ?>">
                            <div class="mt-24 param-field-wrapper" data-param-key="<?= esc_attr( $param_key ) ?>">
                                <div class="mb-8">
                                    <label class="custom-event-label"><?= $field[ 'label' ] ?></label>
                                </div>

                                <!-- Value input (shown when dynamic mode is OFF) -->
                                <input type="text"
                                       name="pys[event][tiktok_params][<?= $param_key ?>][value]"
                                       value="<?= esc_attr( $param_value ) ?>"
                                       class="input-standard param-value-input"
                                       placeholder=""
                                       style="<?= $param_dynamic ? 'display: none;' : '' ?>"
                                />

                                <!-- Selector input (shown when dynamic mode is ON) -->
                                <input type="text"
                                       name="pys[event][tiktok_params][<?= $param_key ?>][selector]"
                                       value="<?= esc_attr( $param_selector ) ?>"
                                       class="input-standard param-selector-input"
                                       placeholder="CSS Selector (e.g., .price)"
                                       style="<?= $param_dynamic ? '' : 'display: none;' ?>"
                                />

                                <!-- Dynamic checkbox -->
                                <div class="mt-8 d-flex align-items-center">
                                    <input type="checkbox"
                                           name="pys[event][tiktok_params][<?= $param_key ?>][dynamic]"
                                           value="1"
                                           class="param-dynamic-checkbox"
                                           <?= $param_dynamic ? 'checked' : '' ?>
                                    />
                                    <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                </div>
                            </div>
						<?php endforeach; ?>

                        <!-- Custom TikTok Params Template -->
                        <div class="tiktok-custom-param custom-param-template" data-param_id="0"
                             style="display: none;">
                            <div class="mt-24">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <input name="" placeholder="Enter name"
                                               class="custom-param-name input-standard"
                                               type="text">
                                    </div>

                                    <div class="ml-16" style="flex: 1;">
                                        <input name="" placeholder="Enter value"
                                               class="custom-param-value input-standard"
                                               type="text"
                                               style="width: 100%;">
                                    </div>

                                    <div class="ml-16" style="flex: 1; display: none;">
                                        <input name="" placeholder="CSS Selector (e.g., .price)"
                                               class="custom-param-selector input-standard"
                                               type="text"
                                               style="width: 100%;">
                                    </div>

                                    <div>
									<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                    </div>
                                </div>

                                <!-- Dynamic checkbox for custom params -->
                                <div class="mt-8 d-flex align-items-center">
                                    <input type="checkbox"
                                           name=""
                                           value="1"
                                           class="custom-param-dynamic-checkbox"
                                    />
                                    <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                </div>
                            </div>
                        </div>

						<?php foreach ( $event->getTikTokCustomParams() as $key => $custom_param ) :
							$param_id = $key + 1;
							$param_value = isset( $custom_param['value'] ) ? $custom_param['value'] : '';
							$param_selector = isset( $custom_param['selector'] ) ? $custom_param['selector'] : '';
							$param_dynamic = isset( $custom_param['dynamic'] ) ? (bool) $custom_param['dynamic'] : false;
						?>

                            <div class="tiktok-custom-param"
                                 data-param_id="<?php echo $param_id; ?>">
                                <div class="mt-24">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input type="text" placeholder="Enter name"
                                                   class="custom-param-name input-standard"
                                                   name="pys[event][tiktok_custom_params][<?php echo $param_id; ?>][name]"
                                                   value="<?php echo esc_attr( $custom_param[ 'name' ] ); ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1;">
                                            <input type="text" placeholder="Enter value"
                                                   class="custom-param-value input-standard"
                                                   name="pys[event][tiktok_custom_params][<?php echo $param_id; ?>][value]"
                                                   value="<?php echo esc_attr( $param_value ); ?>"
                                                   style="width: 100%; <?= $param_dynamic ? 'display: none;' : '' ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1; <?= $param_dynamic ? '' : 'display: none;' ?>">
                                            <input type="text" placeholder="CSS Selector (e.g., .price)"
                                                   class="custom-param-selector input-standard"
                                                   name="pys[event][tiktok_custom_params][<?php echo $param_id; ?>][selector]"
                                                   value="<?php echo esc_attr( $param_selector ); ?>"
                                                   style="width: 100%;">
                                        </div>

                                        <div>
										<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>

                                    <!-- Dynamic checkbox for custom params -->
                                    <div class="mt-8 d-flex align-items-center">
                                        <input type="checkbox"
                                               name="pys[event][tiktok_custom_params][<?php echo $param_id; ?>][dynamic]"
                                               value="1"
                                               class="custom-param-dynamic-checkbox"
                                               <?= $param_dynamic ? 'checked' : '' ?>
                                        />
                                        <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                    </div>
                                </div>
                            </div>

						<?php endforeach; ?>

                        <div class="insert-marker"></div>

                        <div class="mt-24">
							<?php renderAddCustomParameterButton( 'tiktok' ); ?>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mt-24 mb-24">
                        <?php Events\renderSwitcherInput( $event, 'tiktok_track_single_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce product data on single product pages</h4>
                    </div>
                    <div class="d-flex align-items-center mb-24">
                        <?php Events\renderSwitcherInput( $event, 'tiktok_track_cart_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce cart data when possible</h4>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( isPinterestActive() && Pinterest()->enabled() ) : ?>
		<?php Pinterest()->renderCustomEventOptions( $event ); ?>
	<?php endif; ?>

	<?php if ( isBingActive() && Bing()->enabled() ) : ?>
		<?php Bing()->renderCustomEventOptions( $event ); ?>
	<?php endif; ?>

	<?php if ( isRedditActive() && Reddit()->enabled() ) : ?>
		<?php Reddit()->renderCustomEventOptions( $event ); ?>
	<?php endif; ?>

	<?php if ( GTM()->enabled() ) :
		$gtm_configured = GTM()->enabled() && !empty( GTM()->getPixelIDs() );
		?>
        <div class="card card-style4" data-configured="<?php echo $gtm_configured ? '1' : '0' ?>">
            <input type="checkbox" class="event-settings-checkbox" id="gtm_custom_event_switch"
                   style="display: none">

            <div class="card-header card-header-style3 d-flex justify-content-between align-items-center">
                <div class="custom-event-pixel-header">
                    <img src="<?php echo PYS_URL . '/dist/images/gtm-logo.svg' ?>" alt="gtm-logo"
                         class="pixel-logo">
                    <h4 class="font-semibold main-switcher">GTM DataLayer</h4>
                </div>

                <div class="custom-event-pixel-status">
					<?php

					$enabled = $event->gtm_enabled && $gtm_configured;
					?>
                    <div class="pixel-status">
                        <p class="status pixel-enabled" style="<?php echo $enabled ? '' : 'display: none' ?>">
                            Active
                        </p>

                        <p class="status pixel-disabled" style="<?php echo $enabled ? 'display: none' : '' ?>">
                            Inactive
                        </p>
                    </div>

                    <label class="card-header-label" for="gtm_custom_event_switch">
						<?php include PYS_VIEW_PATH . '/UI/properties-button-off.php'; ?>
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="gtm-not-configured-error mb-24">
                    <div class="event_error critical_message">Error: GTM is not configured</div>
                </div>

                <div class="d-flex align-items-center mb-24">
					<?php Events\renderSwitcherInput( $event, 'gtm_enabled' ); ?>
                    <h4 class="switcher-label secondary_heading">Enable on GTM</h4>
                </div>

                <div id="gtm_panel" class="pixel_panel">
                    <div class="line mb-24"></div>

                    <div class="d-flex align-items-center mb-24">
						<?php Events\renderSwitcherInput( $event, 'gtm_automated_param' ); ?>
                        <h4 class="switcher-label secondary_heading">Add the automated parameters in the dataLayer</h4>
                    </div>

                    <div class="d-flex align-items-center mb-24">
						<?php Events\renderSwitcherInput( $event, 'gtm_remove_customTrigger' ); ?>
                        <h4 class="switcher-label secondary_heading">Remove the customTrigger object</h4>
                    </div>

                    <div class="line mb-24"></div>

                    <div class="mb-24">
                        <div class="mb-8">
                            <label class="custom-event-label">Fire for:</label>
                        </div>

						<?php
						$mainPixels = GTM()->getPixelIDs();
						if ( !empty( $mainPixels ) && strpos( $mainPixels[ 0 ], 'GTM-' ) === 0 && strpos( $mainPixels[ 0 ], 'GTM-' ) !== false ) {
							echo '<div class="disabled-input">' . $mainPixels[ 0 ] . '</div>';
							echo '<input type="hidden" name="pys[event][gtm_pixel_id][]" value="' . $mainPixels[ 0 ] . '"/>';
						} else {
							echo '<div class="disabled-input form-text">' . __( 'No container ID is configured', 'pys' ) . '</div>';
						}
						?>
                    </div>

                    <script>
						<?php
						$fields = array();
						$ga_events = PYS_Event_Definitions::get_ga_events();
						foreach ( $ga_events as $group => $items ) {
							foreach ( $items as $name => $elements ) {
								$fields[] = array(
									"name"   => $name,
									'fields' => $elements
								);
							}
						}

						?>
                        const gtm_fields = <?=json_encode( $fields )?>
                    </script>

                    <div class="mb-24">
                        <div class="mb-8">
                            <label class="custom-event-label">Event</label>
                        </div>

						<?php Events\renderGTMActionInput( $event, 'gtm_event_action' ); ?>
                    </div>

                    <div class="mb-24" id="gtm-custom-action_g4">
                        <div class="mb-8">
                            <label class="custom-event-label">Enter name</label>
                        </div>

						<?php Events\renderTextInput( $event, 'gtm_custom_event_action' ); ?>
                    </div>

                    <div class="gtm-param-list">
						<?php
						foreach ( $event->getGTMParams() as $key => $val ) : ?>
                            <div class="mb-24 gtm_param param-field-wrapper" data-param-key="<?= esc_attr( $key ) ?>">
                                <div class="mb-8">
                                    <label class="custom-event-label"><?= $key ?></label>
                                </div>

								<?php Events\renderGTMParamInput( $key, $val ); ?>
                            </div>
						<?php endforeach; ?>
                    </div>

                    <div class="gtm-custom-param-list">
						<?php
						foreach ( $event->getGTMCustomParamsAdmin() as $key => $custom_param ) :
							$param_id = $key + 1;
							$param_value = isset( $custom_param['value'] ) ? $custom_param['value'] : '';
							$param_selector = isset( $custom_param['selector'] ) ? $custom_param['selector'] : '';
							$param_dynamic = isset( $custom_param['dynamic'] ) ? (bool) $custom_param['dynamic'] : false;
						?>

                            <div class="gtm-custom-param" data-param_id="<?php echo $param_id; ?>">
                                <div class="mt-24">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input type="text" placeholder="Enter name"
                                                   class="custom-param-name input-standard"
                                                   name="pys[event][gtm_custom_params][<?php echo $param_id; ?>][name]"
                                                   value="<?php echo esc_attr( $custom_param[ 'name' ] ); ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1;">
                                            <input type="text" placeholder="Enter value"
                                                   class="custom-param-value input-standard"
                                                   name="pys[event][gtm_custom_params][<?php echo $param_id; ?>][value]"
                                                   value="<?php echo esc_attr( $param_value ); ?>"
                                                   style="width: 100%; <?= $param_dynamic ? 'display: none;' : '' ?>">
                                        </div>

                                        <div class="ml-16" style="flex: 1; <?= $param_dynamic ? '' : 'display: none;' ?>">
                                            <input type="text" placeholder="CSS Selector (e.g., .price)"
                                                   class="custom-param-selector input-standard"
                                                   name="pys[event][gtm_custom_params][<?php echo $param_id; ?>][selector]"
                                                   value="<?php echo esc_attr( $param_selector ); ?>"
                                                   style="width: 100%;">
                                        </div>

                                        <div>
										<?php include PYS_VIEW_PATH . '/UI/button-remove-row.php'; ?>
                                        </div>
                                    </div>

                                    <!-- Dynamic checkbox for custom params -->
                                    <div class="mt-8 d-flex align-items-center">
                                        <input type="checkbox"
                                               name="pys[event][gtm_custom_params][<?php echo $param_id; ?>][dynamic]"
                                               value="1"
                                               class="custom-param-dynamic-checkbox"
                                               <?= $param_dynamic ? 'checked' : '' ?>
                                        />
                                        <label class="ml-8" style="font-size: 13px; color: #666;">Use dynamic value from page</label>
                                    </div>
                                </div>
                            </div>
						<?php endforeach; ?>
                    </div>

                    <div id="custom-param-message" class="critical_message mt-24"></div>

                    <div class="mt-24 mb-24">
						<?php renderAddCustomParameterButton( 'gtm' ); ?>
                    </div>

                    <div class="d-flex align-items-center mb-24">
                        <?php Events\renderSwitcherInput( $event, 'gtm_track_single_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce product data on single product pages</h4>
                    </div>

                    <div class="d-flex align-items-center mb-24">
                        <?php Events\renderSwitcherInput( $event, 'gtm_track_cart_woo_data' ); ?>
                        <h4 class="switcher-label secondary_heading">Track WooCommerce cart data when possible</h4>
                    </div>

                    <div class="line mb-24"></div>

                    <div class="gtm_use_custom_object_name d-flex align-items-center mb-24">
						<?php Events\renderSwitcherInput( $event, 'gtm_use_custom_object_name' ); ?>
                        <h4 class="switcher-label secondary_heading">Use a custom value for the custom parameters
                            object</h4>
                    </div>

                    <div class="gtm_custom_object_name d-flex align-items-center mb-24">
						<?php Events\renderTextInput( $event, 'gtm_custom_object_name', $event->getManualCustomObjectName() ); ?>
                    </div>

                    <p class="primary-heading-color mt-24">
                        When configuring GTM variables for these parameters, use this key: <span
                                class="event-parameter-list"
                                id="manual_custom_object_name"><?= $event->getManualCustomObjectName(); ?></span>
                    </p>

                    <p class="gtm_woo_info primary-heading-color mt-24">
                        <span class="primary-text-color primary_heading">ATTENTION: </span>
                        ​ the plugin automatically tracks ecommerce specific events for WooCommerce and Easy Digital
                        Downloads. Make sure you really need this event.
                    </p>

                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php do_action( 'pys_superpack_dynamic_params_help' ); ?>

</div>