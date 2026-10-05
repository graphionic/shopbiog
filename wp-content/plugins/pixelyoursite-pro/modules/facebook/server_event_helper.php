<?php
namespace PixelYourSite;

use PixelYourSite;
use PYS_PRO_GLOBAL\FacebookAds\Object\ServerSide\Event;
use PYS_PRO_GLOBAL\FacebookAds\Object\ServerSide\UserData;
use PYS_PRO_GLOBAL\FacebookAds\Object\ServerSide\CustomData;
use PYS_PRO_GLOBAL\FacebookAds\Object\ServerSide\Content;
defined('ABSPATH') or die('Direct access not allowed');




class ServerEventHelper {

	private static $fbp;
	private static $fbc;
    /**
     * @param SingleEvent $event
     * @return Event | null
     */
    public static function mapEventToServerEvent($event) {

        $eventData = $event->getData();

        $eventData = EventsManager::filterEventParams($eventData,$event->getCategory(),[
            'event_id'=>$event->getId(),
            'pixel'=>Facebook()->getSlug()
        ]);

        $eventName = $eventData['name'];
        $eventParams = $eventData['params'];
        $wooOrder = isset($event->payload['woo_order']) ? $event->payload['woo_order'] : null;
        $eddOrder = isset($event->payload['edd_order']) ? $event->payload['edd_order'] : null;


        $user_data = ServerEventHelper::getUserData($wooOrder,$eddOrder)
            ->setClientIpAddress(self::getIpAddress())
            ->setClientUserAgent(self::getHttpUserAgent());

		if ( Consent()->checkConsent( 'facebook' ) ) {
			if ( !self::getFbp() && ( !isset( $eventParams[ '_fbp' ] ) || !$eventParams[ '_fbp' ] ) && !headers_sent() ) {
				self::setFbp( 'fb.1.' . time() . '.' . rand( 1000000000, 9999999999 ) );
				if ( !headers_sent() ) {
					setcookie( "_fbp", self::getFbp(), 2147483647, '/', PYS()->general_domain );
				}
			}

			if ( !self::getFbc() && self::getUrlParameter( 'fbclid' ) ) {
				$fbclid = self::getUrlParameter( 'fbclid' );
				if ( $fbclid ) {
					self::setFbc( 'fb.1.' . time() . '.' . $fbclid );
					if ( !headers_sent() ) {
						setcookie( "_fbc", self::$fbc, 2147483647, '/', PYS()->general_domain );
					}
				}
			}
		}

        $fbp = '';
        $fbc = '';

        // Check if WooCommerce is active before calling wc_get_order()
        if ($wooOrder && PixelYourSite\isWooCommerceActive()) {
            // Get order once and retrieve both fbp and fbc from it
            $order = \wc_get_order( $wooOrder );
            if ($order) {
                $fbCookie = $order->get_meta('pys_fb_cookie', true);
                if ($fbCookie) {
                    if (!empty($fbCookie['fbp'])) {
                        $fbp = $fbCookie['fbp'];
                    }
                    if (!empty($fbCookie['fbc'])) {
                        $fbc = $fbCookie['fbc'];
                    }
                }
            }
        }

// Checking that the values are not empty and setting alternative values if they are missing
        if(empty($fbp)) {
            $fbp = self::getFbp() ?? $eventParams['_fbp'] ?? '';
        }
        if (empty($fbc)) {
            $fbc = self::getFbc() ?? $eventParams['_fbc'] ?? '';
        }

        if(!empty($fbp)) { $user_data->setFbp($fbp); }
        if(!empty($fbc)) { $user_data->setFbc($fbc); }


        $customData = self::paramsToCustomData($eventParams, $event);
        $uri = self::getRequestUri(PYS()->getOption('enable_remove_source_url_params'));

        // set custom uri use in ajax request
        if(isset($_POST['url'])) {
            if(PYS()->getOption('enable_remove_source_url_params')) {
                $list = explode("?",esc_url_raw($_POST[ 'url' ]));
                if(is_array($list) && count($list) > 0) {
                    $uri = $list[0];
                } else {
                    $uri = esc_url_raw($_POST[ 'url' ]);
                }
            } else {
                $uri = esc_url_raw($_POST[ 'url' ]);
            }
        }

        $serverEvent = (new Event())
            ->setEventName($eventName)
            ->setEventTime(time())
            ->setEventSourceUrl($uri)
            ->setActionSource("website")
            ->setCustomData($customData)
            ->setUserData($user_data);

        if(isset($event->payload['eventID'])) {
            $serverEvent->setEventId($event->payload['eventID']);
        }

		if ( Facebook()->getLDUMode() ) {
			$serverEvent
				->setDataProcessingOptions( [ 'LDU' ] )
				->setDataProcessingOptionsCountry( 0 )
				->setDataProcessingOptionsState( 0 );
		}

        return $serverEvent;
    }


    private static function getIpAddress() {
        if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            // Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
            // Make sure we always only send through the first IP in the list which should always be the client IP.
            $value = trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) );
            // Account for the '<IPv4 address>:<port>', '[<IPv6>]' and '[<IPv6>]:<port>' cases, removing the port.
            // The regular expression is oversimplified on purpose, later 'rest_is_ip_address' will do the actual IP address validation.
            $value = preg_replace( '/([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)\:.*|\[([^]]+)\].*/', '$1$2', $value );
            return (string) rest_is_ip_address( $value );
        } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            // Make sure we always only send through the first IP in the list which should always be the client IP.
            $value = trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) ) );
            return (string) rest_is_ip_address( $value );
        }

        return "127.0.0.1";
    }

    private static function isValidIpAddress($ip_address) {
        return filter_var($ip_address,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4
            | FILTER_FLAG_IPV6
            | FILTER_FLAG_NO_PRIV_RANGE
            | FILTER_FLAG_NO_RES_RANGE);
    }

    private static function getHttpUserAgent() {
        $user_agent = null;

        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        return $user_agent;
    }

    private static function getRequestUri($removeQuery = false) {
        $request_uri = null;

        if (!empty($_SERVER['REQUEST_URI'])) {
            $start = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";
            $host = $_SERVER['HTTP_HOST'] ?? parse_url(get_site_url(), PHP_URL_HOST);
            $request_uri = $start . $host . $_SERVER['REQUEST_URI'];
        }
        if($removeQuery && isset($_SERVER['QUERY_STRING'])) {
            $request_uri = str_replace("?".$_SERVER['QUERY_STRING'],"",$request_uri);
        }


        return $request_uri;
    }
	static function getUrlParameter($sParam) {
		$sPageURL = $_SERVER['QUERY_STRING'];
		$sURLVariables = explode('&', $sPageURL);

		foreach ($sURLVariables as $sURLVariable) {
			$sParameterName = explode('=', $sURLVariable);

			if ($sParameterName[0] === $sParam) {
				return isset($sParameterName[1]) ? urldecode($sParameterName[1]) : true;
			}
		}

		return false;
	}

	public static function setFbp($fbp) {
		self::$fbp = $fbp;
	}

	public static function setFbc($fbc) {
		self::$fbc = $fbc;
	}
    static function getFbp() {
        $fbp = null;

        if (!empty($_COOKIE['_fbp'])) {
            $fbp = sanitize_text_field($_COOKIE['_fbp']);
        }
		elseif (!empty(self::$fbp)){
			$fbp = self::$fbp;
		}

        return $fbp;
    }

    static function getFbc() {
        $fbc = null;

        if (!empty($_COOKIE['_fbc'])) {
            $fbc = sanitize_text_field($_COOKIE['_fbc']);
        }
        elseif (!empty(self::$fbc)){
	        $fbc = self::$fbc;
        }

        return $fbc;
    }

	private static function getUserData( $wooOrder = null, $eddOrder = null ) {
		$userData = new UserData();

		/**
		 * Add purchase WooCommerce Advanced Matching params
		 */
		if ( PixelYourSite\isWooCommerceActive() && isEventEnabled( 'woo_purchase_enabled' ) && ( $wooOrder || ( PixelYourSite\PYS()->woo_is_order_received_page() && wooIsRequestContainOrderId() ) ) ) {
			if ( wooIsRequestContainOrderId() ) {
				$order_id = wooGetOrderIdFromRequest();
			} else {
				$order_id = $wooOrder;
			}

			$order = \wc_get_order( $order_id );

			if ( $order ) {
				if ( PixelYourSite\isWooCommerceVersionGte( '3.0.0' ) ) {

					$user_firstname = $order->get_billing_first_name();
					$user_lastname = $order->get_billing_last_name();
					$user_phone = $order->get_billing_phone();
					$user_email = $order->get_billing_email();

					if ( $order->get_billing_postcode() ) {
						$userData->setZipCode( $order->get_billing_postcode() );
					}
					if ( $order->get_billing_country() ) {
						$userData->setCountryCode( strtolower( $order->get_billing_country() ) );
					}

					if ( $order->get_billing_city() ) {
						$userData->setCity( $order->get_billing_city() );
					}

					if ( $order->get_billing_state() ) {
						$userData->setState( $order->get_billing_state() );
					}
					if ( $order->get_meta( 'external_id' ) ) {
						$external_id = $order->get_meta( 'external_id' );
						if ( !empty( $external_id ) ) {
							$userData->setExternalId( $external_id );
						}
					}
				} else {
					$user_firstname = $order->billing_first_name;
					$user_lastname = $order->billing_last_name;
					$user_phone = $order->billing_phone;
					$user_email = $order->billing_email;

					if ( $order->billing_postcode ) {
						$userData->setZipCode( $order->billing_postcode );
					}
					$userData->setCountryCode( strtolower( $order->billing_country ) );
					$userData->setCity( $order->billing_city );
					$userData->setState( $order->billing_state );
					if ( get_post_meta( $order_id, 'external_id', true ) ) {
						$external_id = get_post_meta( $order_id, 'external_id', true );
						if ( !empty( $external_id ) ) {
							$userData->setExternalId( $external_id );
						}
					}
				}

				$user_persistence_data = get_persistence_user_data( $user_email, $user_firstname, $user_lastname, $user_phone );
				if ( !empty( $user_persistence_data[ 'fn' ] ) ) $userData->setFirstName( $user_persistence_data[ 'fn' ] );
				if ( !empty( $user_persistence_data[ 'ln' ] ) ) $userData->setLastName( $user_persistence_data[ 'ln' ] );
				if ( !empty( $user_persistence_data[ 'em' ] ) ) $userData->setEmail( $user_persistence_data[ 'em' ] );
				if ( !empty( $user_persistence_data[ 'tel' ] ) ) $userData->setPhone( $user_persistence_data[ 'tel' ] );

				$user_id = $order->get_user_id();
				if ( $user_id && apply_filters( 'pys_send_meta_id', true ) ) {
					$login_id = get_user_meta( $user_id, '_socplug_social_id_Facebook', true );
					if ( !empty( $login_id ) ) {
						$userData->setFbLoginId( $login_id );
					}
				}

			} else {
				return ServerEventHelper::getRegularUserData();
			}

		} else {

			if ( PixelYourSite\isEddActive() && isEventEnabled( 'edd_purchase_enabled' ) && ( $eddOrder || edd_is_success_page() ) ) {

				if ( $eddOrder ) $payment_id = $eddOrder; else {
					$payment_key = getEddPaymentKey();
					$payment_id = (int) edd_get_purchase_id_by_key( $payment_key );
				}
				
				// Check if payment_id is valid before proceeding
				if ( $payment_id > 0 ) {
					$user_info = edd_get_payment_meta_user_info( $payment_id );
					$email = edd_get_payment_user_email( $payment_id );

					$user_firstname = $user_lastname = $user_email = '';

					if ( isset($user_info[ 'first_name' ]) && $user_info[ 'first_name' ] ) {
						$user_firstname = $user_info[ 'first_name' ];
					}
					if ( isset($user_info[ 'last_name' ]) && $user_info[ 'last_name' ] ) {
						$user_lastname = $user_info[ 'last_name' ];
					}
					if ( $email ) {
						$user_email = $email;
					}

					$user_persistence_data = get_persistence_user_data( $user_email, $user_firstname, $user_lastname, '' );
					if ( !empty( $user_persistence_data[ 'fn' ] ) ) $userData->setFirstName( $user_persistence_data[ 'fn' ] );
					if ( !empty( $user_persistence_data[ 'ln' ] ) ) $userData->setLastName( $user_persistence_data[ 'ln' ] );
					if ( !empty( $user_persistence_data[ 'em' ] ) ) $userData->setEmail( $user_persistence_data[ 'em' ] );
					if ( !empty( $user_persistence_data[ 'tel' ] ) ) $userData->setPhone( $user_persistence_data[ 'tel' ] );

					// Get external_id from EDD payment meta
					$external_id = edd_get_payment_meta( $payment_id, 'external_id', true );
					if ( !empty( $external_id ) ) {
						$userData->setExternalId( $external_id );
					}

					// Check if user_info is valid and contains 'id' key
					if ( isset($user_info[ 'id' ]) && $user_info[ 'id' ] && apply_filters( 'pys_send_meta_id', true ) ) {
						$login_id = get_user_meta( $user_info[ 'id' ], '_socplug_social_id_Facebook', true );
						if ( !empty( $login_id ) ) {
							$userData->setFbLoginId( $login_id );
						}
					}
				} else {
					// If payment_id is invalid, fall back to regular user data
					return ServerEventHelper::getRegularUserData();
				}

			} else {
				return ServerEventHelper::getRegularUserData();
			}
		}

		return apply_filters( "pys_fb_server_user_data", $userData );
	}

	private static function getRegularUserData() {

		$user = wp_get_current_user();
		$userData = new UserData();
		if ( $user->ID ) {

			$user_firstname = $user->get( 'user_firstname' );
			$user_lastname = $user->get( 'user_lastname' );
			$user_phone = $user->get( 'billing_phone' );

			/**
			 * Add common WooCommerce Advanced Matching params
			 */
			if ( PixelYourSite\isWooCommerceActive() ) {
				// if first name is not set in regular wp user meta
				if ( empty( $user_firstname ) ) {
					$user_firstname = $user->get( 'billing_first_name' );
				}

				// if last name is not set in regular wp user meta
				if ( empty( $user_lastname ) ) {
					$user_lastname = $user->get( 'billing_last_name' );
				}

				if ( $user_phone ) $user_phone = $user->get( 'billing_phone' );
				if ( $user->get( 'billing_city' ) ) $userData->setCity( $user->get( 'billing_city' ) );
				if ( $user->get( 'billing_state' ) ) $userData->setState( $user->get( 'billing_state' ) );
				if ( $user->get( 'shipping_country' ) ) $userData->setCountryCode( strtolower( $user->get( 'shipping_country' ) ) );
				if ( $user->get( 'billing_postcode' ) ) {
					$userData->setZipCode( $user->get( 'billing_postcode' ) );
				}
			}
			$user_persistence_data = get_persistence_user_data( $user->get( 'user_email' ), $user_firstname, $user_lastname, $user_phone );

			$login_id = get_user_meta( $user->ID, '_socplug_social_id_Facebook', true );
			if ( !empty( $login_id ) && apply_filters( 'pys_send_meta_id', true ) ) {
				$userData->setFbLoginId( $login_id );
			}
		} else {
			$user_persistence_data = get_persistence_user_data( '', '', '', '' );
		}

		if ( !empty( $user_persistence_data[ 'fn' ] ) ) $userData->setFirstName( $user_persistence_data[ 'fn' ] );
		if ( !empty( $user_persistence_data[ 'ln' ] ) ) $userData->setLastName( $user_persistence_data[ 'ln' ] );
		if ( !empty( $user_persistence_data[ 'em' ] ) ) $userData->setEmail( $user_persistence_data[ 'em' ] );
		if ( !empty( $user_persistence_data[ 'tel' ] ) ) $userData->setPhone( $user_persistence_data[ 'tel' ] );

		if ( PixelYourSite\EventsManager::isTrackExternalId() && !empty( PixelYourSite\PYS()->get_pbid() ) ) {
			$userData->setExternalId( PixelYourSite\PYS()->get_pbid() );
		}

		return apply_filters( "pys_fb_server_user_data", $userData );
	}

    static function paramsToCustomData($data, $singleEvent) {
        $wooOrder = isset($singleEvent->payload['woo_order']) ? $singleEvent->payload['woo_order'] : null;
        $eddOrder = isset($singleEvent->payload['edd_order']) ? $singleEvent->payload['edd_order'] : null;

        if(isset($data['contents']) && is_array($data['contents'])) {
            $contents = array();
            if(isset($data['contents']) && is_array($data['contents'])) {
                foreach ($data['contents'] as $c) {
                    if(isset($c['id']) && isset($c['quantity'])) {
                        $contents[] = new Content([
                            'product_id' => $c['id'],
                            'quantity' => $c['quantity']
                        ]);
                    }
                }
            }
            $data['contents'] = $contents;
        } else {
            $data['contents'] = array();
        }
        $customData = new CustomData($data);
        $customProperties = array();

        $isParentOrder = false;

        $getEnrichDataEvents = ['woo_start_trial',
            'woo_subscription_created',
            'woo_subscription_renewal',
            'woo_subscription_expired',
            'woo_subscription_canceled',
            'edd_start_trial',
            'edd_subscription_created',
            'edd_subscription_renewal',
            'edd_subscription_expired',
            'edd_subscription_canceled',
            'edd_license_created',
            'edd_license_upgrade',
            'edd_license_renewal',
            'edd_license_expired'];


        if(in_array($singleEvent->getId(), $getEnrichDataEvents) || (isset($data['advanced_purchase_tracking']) && !empty($data['advanced_purchase_tracking']))) {

            if(isset($data['advanced_purchase_tracking'])){
                $customProperties['advanced_purchase_tracking'] = $data['advanced_purchase_tracking'];
            }


            $enrichData = array();

            if ($wooOrder && PixelYourSite\isWooCommerceActive()) {
                $order = wc_get_order( $wooOrder );
                if(isWooUseHPStorage()) {
                    // WooCommerce >= 3.0
                    if($order) {
                        $enrichData = $order->get_meta( 'pys_enrich_data', true );
                    }
                } else {
                    // WooCommerce < 3.0
                    if(get_post_meta($wooOrder, "pys_enrich_data", true))
                    {
                        $enrichData = get_post_meta($wooOrder, "pys_enrich_data",true);
                    }
                }
            }
            if ($eddOrder && PixelYourSite\isEddActive()) {
                $payment      = new \EDD_Payment( $eddOrder );
                if(isset($payment) && $payment->get_meta()) {
                    $meta = $payment->get_meta();
                    if(isset($meta['pys_enrich_data'])) {
                        $enrichData = $meta['pys_enrich_data'];
                    }
                }
            }

            if (!empty($enrichData)) {
                $is_last_visit = PYS()->getOption('visit_data_model') === 'last_visit';

                if(PYS()->getOption( 'track_traffic_source' )) {
                    // Traffic source
                    if ($is_last_visit && isset($enrichData['last_pys_source'])) {
                        $customProperties['traffic_source'] = $enrichData['last_pys_source'];
                    } elseif (!$is_last_visit && isset($enrichData['pys_source'])) {
                        $customProperties['traffic_source'] = $enrichData['pys_source'];
                    }
                }
                if(PYS()->getOption( 'track_utms' )) {
                    // UTM parameters
                    if ($is_last_visit && isset($enrichData['last_pys_utm'])) {
                        $customProperties = array_merge($customProperties, parseUtmString($enrichData['last_pys_utm']));
                    } elseif (!$is_last_visit && isset($enrichData['pys_utm'])) {
                        $customProperties = array_merge($customProperties, parseUtmString($enrichData['pys_utm']));
                    }
                }
                if(PYS()->getOption( 'track_landing_page' )) {
                    // Landing page
                    if ($is_last_visit && isset($enrichData['last_pys_landing'])) {
                        $customProperties['landing_page'] = $enrichData['last_pys_landing'];
                    } elseif (!$is_last_visit && isset($enrichData['pys_landing'])) {
                        $customProperties['landing_page'] = $enrichData['pys_landing'];
                    }
                }

                // Browser time (same for both)
                if (isset($enrichData['pys_browser_time']) && !$isParentOrder) {
                    $customProperties = array_merge($customProperties, getUserTime($enrichData['pys_browser_time']));
                }
            }
        }
        else {
            if(PYS()->getOption( 'track_traffic_source' ))
                $customProperties['traffic_source'] = getTrafficSource(PYS()->getOption('visit_data_model') === "last_visit");

            if(PYS()->getOption( 'track_utms' )) {
                $customProperties = array_merge($customProperties,getUtms(false, PYS()->getOption('visit_data_model') === "last_visit"));
            }
        }

        if(isset($data['category_name'])) {
            $customData->setContentCategory($data['category_name']);
        }

        $custom_values = ['event_action','download_type','download_name','download_url','target_url','text','trigger','traffic_source','plugin','user_role','event_url','event_time', 'event_hour','event_day','event_month','page_title',"post_type",'post_id','categories','tags','video_type',
            'video_id','video_title','event_trigger','link_type','tag_text',"URL",
            'form_id','form_class','form_submit_label','transactions_count','average_order',
            'shipping_cost','tax','total','shipping','coupon_used','post_category','landing_page',
        'number_of_transactions', 'subscription_value', 'subscription_name', 'license_name',
            'subscription_id', 'license_id'];


        $adding_custom_field = array();
        $eventsCustom = EventsCustom()->getEvents();
        foreach ($eventsCustom as $event)
        {
            $fbCustomEvents = $event->getFacebookCustomParams();

            foreach ($fbCustomEvents as $paramKey => $params)
            {
                if(!in_array($params['name'], $custom_values) && !in_array($params['name'], $customData->attributeMap()))
                {
                    $adding_custom_field[] = $params['name'];
                }
            }
        }


        $result_custom_values = array_merge($custom_values, $adding_custom_field);

        foreach ($result_custom_values as $val) {
            if(isset($data[$val])){
                $customProperties[$val] = $data[$val];
            }
        }
        //get filter value
        $custom_filter_data_event = apply_filters('pys_event_data',array(),$singleEvent->getCategory(),[
            'event_id'=>$singleEvent->getId(),
            'pixel'=>Facebook()->getSlug()
        ]);

        if(isset($custom_filter_data_event['params']) && !empty($custom_filter_data_event['params'])) {
            foreach ($custom_filter_data_event['params'] as $key => $value) {
                $customProperties[$key] = $value;
            }
        }

        $customData->setCustomProperties($customProperties);
        return $customData;
    }

}