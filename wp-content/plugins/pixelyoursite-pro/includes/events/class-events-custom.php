<?php

namespace PixelYourSite;
class EventsCustom extends EventsFactory {
	private static $_instance;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	private function __construct() {
		add_filter( "pys_event_factory", [
			$this,
			"register"
		] );
	}
	function register( $list ) {
		$list[] = $this;
		return $list;
	}

	static function getSlug() {
		return "custom";
	}

	function getEvents() {
		return CustomEventFactory::get( 'active' );
	}

	function getCount() {
		if ( !$this->isEnabled() ) {
			return 0;
		}
		return count( $this->getEvents() );
	}

	function isEnabled() {
		return PYS()->getOption( 'custom_events_enabled' );
	}

	function getOptions() {
		return array();
	}

	/**
	 * @param CustomEvent $event
	 * @return bool
	 */
	function isReadyForFire( $event ) {

		$conditions_enabled = (bool) $event->__get( 'conditions_enabled' );
		$conditions_logic   = $event->__get( 'conditions_logic' );
		$isOrLogic          = $conditions_enabled && $conditions_logic !== 'AND';
		$conditions         = $event->checkConditions();

		// AND mode.
		if ( !$isOrLogic && !$conditions ) {
			return false;
		}

		$event_triggers = $event->getTriggers();
		$isReady = array();
		$visitTracked = false;

		if ( !empty( $event_triggers ) ) {
			foreach ( $event_triggers as $event_trigger ) {
				$trigger_type = $event_trigger->getTriggerType();
				switch ( $trigger_type ) {
					case 'post_type' :
					{
						$isTriggerReady = $event_trigger->getPostTypeValue() == get_post_type();
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}
					case 'number_page_visit' :
					{
						$triggers = $event_trigger->getNumberPageVisitTriggers();
						if ( !empty( $triggers ) && compareURLs( $triggers ) ) {
							$user = get_current_user_id() && get_current_user_id() !== 0 ? get_current_user_id() : null;
							$tracker = new PageVisitTracker( $user );
							if ( !$visitTracked ) {
								$tracker->update_page_visits( $event->getPostId() );
								$visitTracked = true;
							}
							$visitCount = $tracker->get_page_visit_count( $event->getPostId() );
                            if ( $this->isConditionalNumberVisit( $event_trigger->getConditionalNumberVisit(), $event_trigger->getNumberVisit(), $visitCount ) ) {
								$event_trigger->setTriggerStatus( true );
								$isReady[] = true;
							} else {
                                $isReady[] = false;
                            }
						}
						break;
					}
					case 'page_visit':
					{
						$triggers = $event_trigger->getPageVisitTriggers();
						$isTriggerReady = !empty( $triggers ) && compareURLs( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}
                    case 'home_page':
                    {
                        $isTriggerReady = is_front_page();
                        $event_trigger->setTriggerStatus( $isTriggerReady );
                        $isReady[] = $isTriggerReady;
                        break;
                    }
                    case 'purchase':
                    {
                        $isTriggerReady = isWooCommerceActive() && PYS()->woo_is_order_received_page() && wooIsRequestContainOrderId();
                        $event_trigger->setTriggerStatus($isTriggerReady);

                        if (!$isTriggerReady) {
                            $isReady[] = false;
                            break;
                        }

                        $order = EventsWoo()->getOrder();
                        $fire_event = true;

                        if ($order) {
                            $meta_key = '_pys_custom_purchase_event_fired_' . $event->getPostId();
                            if ($event_trigger->getOnlyTransactionsPurchase() && $order->get_meta($meta_key, true)) {
                                $fire_event = false;  // skip woo_purchase if this transaction was fired
                            } else {
                                $order->update_meta_data($meta_key, true);
                                $order->save();
                            }
                        }

                        $isReady[] = $isTriggerReady && $fire_event;
                        break;
                    }

                    case 'add_to_cart':
                    {
                        $isTriggerReady = isWooCommerceActive();
                        $event_trigger->setTriggerStatus( $isTriggerReady );
                        $isReady[] = $isTriggerReady;
                        break;
                    }

					case 'url_click':
					{
						$triggers = $event_trigger->getURLClickTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

					case 'css_click':
					{
						$triggers = $event_trigger->getCSSClickTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

					case 'css_mouseover':
					{
						$triggers = $event_trigger->getCSSMouseOverTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

					case 'scroll_pos':
					{
						$triggers = $event_trigger->getScrollPosTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

					case 'video_view':
					{
						$triggers = $event_trigger->getVideoViewTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

                    case 'form_field':
                    {
                        $triggers = array_map(function($formValue) {
                            return ['value' => $formValue, 'rule' => 'contains'];
                        }, $event_trigger->getAnyFormUrls());
                        $isTriggerReady = !empty( $event_trigger->getAnyForm() ) && !empty( $event_trigger->getAnyFormField() ) && compareURLs( $triggers ) ;
                        $event_trigger->setTriggerStatus( $isTriggerReady );
                        $isReady[] = $isTriggerReady;
                        break;
                    }

					case 'email_link':
					{
						$isTriggerReady = !empty( $event_trigger->getEmailLinkTriggers() );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}
					case 'copy_element':
					{
						$triggers = $event_trigger->getCopyElementTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}
					case 'video_speed':
					{
						$triggers = $event_trigger->getVideoSpeedTriggers();
						$isTriggerReady = !empty( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}

					case 'gads_phone_conversion':
					{
						$triggers = $event_trigger->getGadsPhoneConversionTriggers();
						$isTriggerReady = !empty( $triggers ) && compareURLs( $triggers );
						$event_trigger->setTriggerStatus( $isTriggerReady );
						$isReady[] = $isTriggerReady;
						break;
					}
				}
				if ( $event_trigger->isFormTriggerType( $trigger_type ) ) {
					$triggers = $event_trigger->getForms();
					$event_trigger->setTriggerStatus( !empty( $triggers ) );
					$isReady[] = !empty( $triggers );
				}
			}

			// OR mode
			if ( $isOrLogic && $conditions ) {
				foreach ( $event_triggers as $event_trigger ) {
					if ( $event_trigger->getTriggerType() === 'purchase' ) {
						continue;
					}
					if ( ! $event_trigger->getTriggerStatus() ) {
						$event_trigger->setTriggerStatus( true );
						$isReady[] = true;
					}
				}
			}
		}
		$trigger_logic = $event->__get( 'trigger_logic' ) ?: 'OR';
		if ( $trigger_logic === 'AND' ) {
			return !empty( $isReady ) && !in_array( false, $isReady );
		}
		return in_array( true, $isReady );
	}

	/**
	 * @param CustomEvent $event
	 * @return PYSEvent
	 */
	function getEvent( $event ) {
		$event_triggers = $event->getTriggers();
		$trigger_types = array();
		$eventObject = null;
		$eventId = $event->getPostId();
		$triggerEventTypes = array();

		if ( !empty( $event_triggers ) ) {
			foreach ( $event_triggers as $event_trigger ) {
				if ( $event_trigger->getTriggerStatus() ) {
					$trigger_type = $event_trigger->getTriggerType();
					switch ( $trigger_type ) {
						case 'post_type' :
						case 'page_visit':
						case 'number_page_visit':
                        case 'home_page':
                        case 'purchase':
							$trigger_types[] = EventTypes::$STATIC;
							break;
						case 'url_click':
						case 'css_click':
						case 'css_mouseover':
						case 'scroll_pos':
						case 'video_view':
						case 'email_link':
                        case 'add_to_cart':
                        case 'form_field':
						case 'copy_element':
						case 'video_speed':
                        case 'gads_phone_conversion':
							$trigger_types[] = EventTypes::$TRIGGER;
							break;
					}

					if ( $event_trigger->isFormTriggerType( $trigger_type ) ) {
						$trigger_types[] = EventTypes::$TRIGGER;
					}

					$trigger = $event_trigger->getEventTriggers( $event_trigger );

					// css_click stores selectors + click_count + click_time_limit as a keyed object for JS consumption.
					if ( $trigger[ 'trigger_type' ] === 'css_click' ) {
						$existing = isset( $triggerEventTypes[ 'css_click' ][ $eventId ] )
							? $triggerEventTypes[ 'css_click' ][ $eventId ]
							: array( 'selectors' => array(), 'click_count' => 1, 'click_time_limit' => 0 );
						$existing[ 'selectors' ]        = array_merge( $existing[ 'selectors' ], $trigger[ 'data' ] );
						$existing[ 'click_count' ]       = isset( $trigger[ 'click_count' ] ) ? $trigger[ 'click_count' ] : 1;
						$existing[ 'click_time_limit' ]  = isset( $trigger[ 'click_time_limit' ] ) ? max( 0, (int) $trigger[ 'click_time_limit' ] ) : 0;
						$triggerEventTypes[ 'css_click' ][ $eventId ] = $existing;
					} elseif ( $trigger[ 'trigger_type' ] === 'video_speed' ) {
						// video_speed stores {triggers, urls, speed_rate} as a keyed object for JS consumption.
						$existing = isset( $triggerEventTypes[ 'video_speed' ][ $eventId ] )
							? $triggerEventTypes[ 'video_speed' ][ $eventId ]
							: array( 'triggers' => array(), 'urls' => array(), 'speed_rate' => 'any' );
						$existing[ 'triggers' ]   = array_merge( $existing[ 'triggers' ], $trigger[ 'data' ] );
						$existing[ 'urls' ]       = array_merge( $existing[ 'urls' ], (array) ( $trigger[ 'urls' ] ?? array() ) );
						$existing[ 'speed_rate' ] = isset( $trigger[ 'speed_rate' ] ) ? $trigger[ 'speed_rate' ] : 'any';
						$triggerEventTypes[ 'video_speed' ][ $eventId ] = $existing;
					} elseif ( isset( $triggerEventTypes[ $trigger[ 'trigger_type' ] ][ $eventId ] ) ) {
						$triggerEventTypes[ $trigger[ 'trigger_type' ] ][ $eventId ] = array_merge( $triggerEventTypes[ $trigger[ 'trigger_type' ] ][ $eventId ], $trigger[ 'data' ] );
					} else {
						$triggerEventTypes[ $trigger[ 'trigger_type' ] ][ $eventId ] = $trigger[ 'data' ];
					}
				}
			}
		}

		$has_static    = in_array( EventTypes::$STATIC, $trigger_types );
		$has_trigger   = in_array( EventTypes::$TRIGGER, $trigger_types );
		$trigger_logic = $event->__get( 'trigger_logic' ) ?: 'OR';

		if ( $trigger_logic === 'AND' && $has_static && $has_trigger ) {
			// Mixed AND: the static conditions were already confirmed by isReadyForFire().
			// Emit as TRIGGER so the JS state machine waits only for the dynamic triggers.
			// Strip static trigger keys from the payload — JS has no handlers for them and
			// they would inflate the AND counter, preventing the event from ever firing.
			foreach ( array( 'page_visit', 'home_page', 'post_type', 'number_page_visit', 'purchase' ) as $static_key ) {
				unset( $triggerEventTypes[ $static_key ] );
			}
			$singleEvent = new SingleEvent( 'custom_event', EventTypes::$TRIGGER, self::getSlug() );
			$singleEvent->args = $event;
			$singleEvent->args->__set( 'triggerEventTypes', $triggerEventTypes );
			$eventObject = $singleEvent;
		} elseif ( $has_static ) {
			$singleEvent = new SingleEvent( 'custom_event', EventTypes::$STATIC, self::getSlug() );
			$singleEvent->args = $event;
			$eventObject = $singleEvent;
		} elseif ( $has_trigger ) {
			$singleEvent = new SingleEvent( 'custom_event', EventTypes::$TRIGGER, self::getSlug() );
			$singleEvent->args = $event;
			$singleEvent->args->__set( 'triggerEventTypes', $triggerEventTypes );
			$eventObject = $singleEvent;
		}

		if ( $eventObject ) {
			$eventObject->addPayload( [ "custom_event_post_id" => $event->__get( 'post_id' ) ] );
			if ( $event->hasTimeWindow() ) {
				$eventObject->addPayload( [ "hasTimeWindow" => $event->hasTimeWindow() ] );
				$eventObject->addPayload( [ "timeWindow" => $event->getTimeWindow() ] );
			}

			$delay = $event->getDelay();
			if ( $delay > 0 ) {
				$eventObject->addPayload( [ "delay" => $delay ] );
			}
		}

		return $eventObject;
	}

    public function hasTriggerAddToCart() {
        $flag = false;
        foreach ($this->getEvents() as $event) {
            if ($event->hasTriggerAddToCart()) {
                $flag = true;
                break;
            }
        }

        return $flag;
    }

	function isConditionalNumberVisit( $operator, $visitCount, $currentVisits ) {
		switch ( $operator ) {
			case 'equal':
				return $currentVisits == $visitCount;
			case 'equal_or_larger':
				return $currentVisits >= $visitCount;
			case 'equal_or_less':
				return $currentVisits <= $visitCount;
			case 'larger':
				return $currentVisits > $visitCount;
			case 'less':
				return $currentVisits < $visitCount;
			default:
				// Handle unexpected operator
				return false;
		}
	}

    function chechConditionals($event)
    {
        if($event->conditions_enabled) {
            //var_dump($event->getConditions());
        }
    }
}

/**
 * @return EventsCustom
 */
function EventsCustom() {
	return EventsCustom::instance();
}

EventsCustom();