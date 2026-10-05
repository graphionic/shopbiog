<?php
namespace PixelYourSite;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TriggerEvent {

	private $trigger_type                   = 'page_visit';
	private $index                          = 0;
	private $ready                          = false;
	private $delay                          = null;
	private $triggers                       = array();
	private $number_visit                   = null;
	private $conditional_number_visit       = null;
	private $forms                          = array();
	private $video_view_data                = array();
	private $video_view_urls                = array();
	private $video_view_triggers            = array();
	private $video_view_play_trigger        = '0%';
	private $video_view_disable_watch_video = false;
	private $disabled_form_action           = false;
	private $url_filters                    = array();
	private $post_type_value                = null;
	private $elementor_form_urls            = array();
	private $elementor_form_data            = array();

    private $form_field_data                = array();

    private $form_field_urls                = array();

    private $form_field_forms = '';

    private $form_field_fields = '';
    
    private $form_field_type = '';
    
    private $created_via_est = false;
	private $email_link_disable_email_event = false;
    private $purchase_transaction_only = false;
    private $track_value_and_currency = false;
    private $track_transaction_ID = false;
    private $form_submit_mode = 'both';
    private $video_speed_urls     = array();
    private $video_speed_rate     = 'any';
    private $video_speed_data     = array();
    private $video_speed_triggers = array();
    private $click_count          = 1;
    private $click_time_limit  = 0;
    private $gads_phone_numbers = array();

	public static $allowedTriggers = array(
		'page_visit',
        'home_page',
        'add_to_cart',
        'purchase',
		'number_page_visit',
		'url_click',
		'css_click',
		'css_mouseover',
		'scroll_pos',
		'post_type',
		'video_view',
		'email_link',
		'copy_element',
		'video_speed',
		'email_link',
		'gads_phone_conversion'
	);


	public function __construct( $trigger_type = 'page_visit', $index = null ) {
		$this->trigger_type = $trigger_type;

		if ( $index === null ) {
			$this->index = rand( 100, 200 );
		} else {
			$this->index = $index;
		}

		$eventsFormFactory = apply_filters( "pys_form_event_factory", array() );
		foreach ( $eventsFormFactory as $activeFormPlugin ) {
			self::$allowedTriggers[] = $activeFormPlugin->getSlug();
		}
	}

	public function getEventTriggers() {
		$payload = array(
			'trigger_type' => $this->getTriggerType(),
			'data'         => array()
		);
		switch ( $this->getTriggerType() ) {
			case 'url_click':
				{
					foreach ( $this->getURLClickTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger;
					}
				}
				break;
			case 'css_click':
				{
					foreach ( $this->getCSSClickTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger[ 'value' ];
					}
					$payload[ 'click_count' ]      = $this->getClickCount();
					$payload[ 'click_time_limit' ] = $this->getClickTimeLimit();
				}
				break;
			case 'css_mouseover':
				{
					foreach ( $this->getCSSMouseOverTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger[ 'value' ];
					}
				}
				break;
			case 'scroll_pos':
				{
					foreach ( $this->getScrollPosTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger[ 'value' ];
					}
				}
				break;
			case 'video_view':
				{
					foreach ( $this->getVideoViewPlayTrigger() as $trigger ) {
						$triggerData = $trigger;
						$triggerData['disable_watch_video'] = $this->getVideoViewDisableWatchVideo();
						$payload['data'][] = $triggerData;
					}
				}
				break;
			case 'email_link':
				{
					$payload[ 'data' ][] = array(
						'disabled_email_link' => $this->getEmailLinkDisableEmailEvent(),
						'rules'                => $this->getEmailLinkTriggers(),
					);
				}
				break;
			case 'copy_element':
				{
					foreach ( $this->getCopyElementTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger[ 'value' ];
					}
				}
				break;
			case 'video_speed':
				{
					foreach ( $this->getVideoSpeedVideoTriggers() as $trigger ) {
						$payload[ 'data' ][] = $trigger;
					}
					$payload[ 'speed_rate' ] = $this->getVideoSpeedRate();
					$payload[ 'urls' ]       = $this->getVideoSpeedUrls();
				}
				break;
            case 'form_field':
                {
                    $field = $this->getAnyFormField();
                    $form = $this->getAnyForm();
                    $fieldType = $this->getFormFieldType();

                    // For EST events, we already have the correct form and field selectors
                    // No need to filter through getAllFieldsAnyForms() as it may be empty
                    $payload['data'][] = array(
                        'form' => $form,
                        'field' => $field,
                        'type' => $fieldType ?: 'text', // Use actual field type or default to 'text'
                    );
                }
                break;
			case 'gads_phone_conversion':
				$payload[ 'data' ][ 'enabled' ]  = true;
				$payload[ 'data' ][ 'triggers' ] = $this->getGadsPhoneConversionTriggers();
				$payload[ 'data' ][ 'numbers' ]  = $this->getGadsPhoneConversionNumbers();

			default:
				break;
		}

	if ( $this->isFormTriggerType( $this->getTriggerType() ) ) {
		$payload[ 'data' ][] = array(
			'disabled_form_action' => $this->getParam( 'disabled_form_action' ),
			'forms'                => $this->getForms(),
			'form_submit_mode'     => $this->getParam( 'form_submit_mode' ),
		);
	}

	return $payload;
	}

	public function updateParam( $params, $value = '' ) {
		if ( !is_array( $params ) ) {
			$params = array( $params => $value );
		}
		foreach ( $params as $key => $param ) {
			if ( $param !== null && property_exists( $this, $key ) ) {
				$this->{$key} = $param;
			}
		}
	}

	public function getParam( $param ) {
		return $this->{$param} ?? null;
	}

	public function getTriggerStatus() {
		return $this->ready;
	}

	public function setTriggerStatus( $status ) {
		$this->ready = $status;
	}

	public function getTriggers() {
		return $this->triggers;
	}

	public function getTriggerType() {
		return $this->trigger_type;
	}

	public function getForms() {
		return $this->forms;
	}

	public function getDelay() {
		return $this->delay;
	}

	public function isFormTriggerType( $trigger_type ) {
		$form_trigger_type = array();
		$eventsFormFactory = apply_filters( "pys_form_event_factory", [] );
		foreach ( $eventsFormFactory as $activeFormPlugin ) :
			$form_trigger_type[] = $activeFormPlugin->getSlug();
		endforeach;

		if ( in_array( $trigger_type, $form_trigger_type ) ) {
			return true;
		}
		return false;
	}

	public function getTriggerIndex() {
		return $this->index;
	}

	/**
	 * @return array
	 */
	public function getPageVisitTriggers() {
		return $this->trigger_type == 'page_visit' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getGadsPhoneConversionTriggers(): array {
		return $this->trigger_type == 'gads_phone_conversion' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getGadsPhoneConversionNumbers(): array {
		return $this->trigger_type == 'gads_phone_conversion' ? $this->gads_phone_numbers : array();
	}

	/**
	 * @return array
	 */
	public function getNumberPageVisitTriggers() {
		return $this->trigger_type == 'number_page_visit' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getURLClickTriggers() {
		return $this->trigger_type == 'url_click' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getCSSClickTriggers() {
		return $this->trigger_type == 'css_click' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getCSSMouseOverTriggers() {
		return $this->trigger_type == 'css_mouseover' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getScrollPosTriggers() {
		return $this->trigger_type == 'scroll_pos' ? $this->triggers : array();
	}

	/**
	 * @return array
	 */
	public function getURLFilters() {
		return in_array( $this->trigger_type, array(
			'url_click',
			'css_click',
			'css_mouseover',
			'scroll_pos'
		) ) ? $this->url_filters : array();
	}

	public function getVideoViewTriggers() {
		return $this->trigger_type == 'video_view' ? $this->video_view_triggers : array();
	}

	public function getVideoViewData() {
		return $this->trigger_type == 'video_view' ? $this->video_view_data : array();
	}

	public function getVideoViewUrls() {
		return $this->trigger_type == 'video_view' ? $this->video_view_urls : array();
	}

	public function getVideoViewPlayTrigger() {
		return $this->trigger_type == 'video_view' ? $this->triggers : array();
	}

	public function getVideoViewDisableWatchVideo() {
		return $this->trigger_type == 'video_view' ? $this->video_view_disable_watch_video : true;
	}

	public function getNumberVisit() {
		return $this->number_visit;
	}

	public function getConditionalNumberVisit() {
		return $this->conditional_number_visit;
	}

	public function getPostTypeValue() {
		return $this->post_type_value;
	}

	public function getElementorFormData() {
		return $this->trigger_type == 'elementor_form' ? $this->elementor_form_data : array();
	}

	public function getElementorFormUrls() {
		return $this->trigger_type == 'elementor_form' ? $this->elementor_form_urls : array();
	}

    public function getAnyFormData() {
        return $this->trigger_type == 'form_field' ? $this->form_field_data : array();
    }

    public function getAnyFormUrls() {
        return $this->trigger_type == 'form_field' ? $this->form_field_urls : array();
    }

    public function getAllFormsAnyForms()
    {
        $forms = [];
        if($this->form_field_data && is_array($this->form_field_data)) {
            foreach ($this->form_field_data as $form_field_datum) {
                foreach ($form_field_datum['forms'] as $form) {
                    $forms[] = $form;
                }
            }
        }

        return $forms;
    }

    public function getAllFieldsAnyForms()
    {
        $fields = [];
        if($this->form_field_data && is_array($this->form_field_data)) {
            foreach ($this->form_field_data as $form_field_datum) {
                foreach ($form_field_datum['forms'] as $form) {
                    if (isset($form['selector']) && $form['selector'] == $this->form_field_forms) {
                        return $form['fields'];
                    }
                }
            }
        }

        return $fields;
    }

    public function getAnyForm()
    {
        return $this->trigger_type == 'form_field' ? $this->form_field_forms : array();
    }

    public function getAnyFormField()
    {
        return $this->trigger_type == 'form_field' ? $this->form_field_fields : array();
    }
    
    public function getFormFieldType()
    {
        return $this->trigger_type == 'form_field' ? $this->form_field_type : '';
    }
    
    public function isCreatedViaEst()
    {
        return $this->created_via_est;
    }

	public function getEmailLinkTriggers() {
		return $this->trigger_type == 'email_link' ? $this->triggers : array();
	}

	public function getCopyElementTriggers() {
		return $this->trigger_type == 'copy_element' ? $this->triggers : array();
	}

	public function getVideoSpeedUrls() {
		return $this->trigger_type == 'video_speed' ? $this->video_speed_urls : array();
	}

	public function getVideoSpeedRate() {
		return $this->trigger_type == 'video_speed' ? $this->video_speed_rate : 'any';
	}

	public function getVideoSpeedData() {
		return $this->trigger_type == 'video_speed' ? $this->video_speed_data : array();
	}

	public function getVideoSpeedTriggers() {
		return $this->trigger_type == 'video_speed' ? $this->video_speed_triggers : array();
	}

	public function getVideoSpeedVideoTriggers() {
		return $this->trigger_type == 'video_speed' ? $this->triggers : array();
	}

	public function getClickCount() {
		return $this->trigger_type == 'css_click' ? max( 1, (int) $this->click_count ) : 1;
	}

	public function getClickTimeLimit() {
		return $this->trigger_type == 'css_click' ? max( 0, (int) $this->click_time_limit ) : 0;
	}

	public function getEmailLinkDisableEmailEvent() {
		return $this->trigger_type == 'email_link' ? $this->email_link_disable_email_event : true;
	}

    public function getOnlyTransactionsPurchase(){
        return $this->trigger_type == 'purchase' ? $this->purchase_transaction_only : false;
    }
    public function getTrackTransactionID(){
        return $this->trigger_type == 'purchase' ? $this->track_transaction_ID : false;
    }

    public function getTrackValueAndCurrency()
    {
        return $this->trigger_type == 'add_to_cart' || $this->trigger_type == 'purchase' ? $this->track_value_and_currency : false;
    }

	public function migrateTriggerData( $trigger_type, $data ) {
		switch ( $trigger_type ) {
			case 'number_page_visit':
				$this->updateParam( array(
					'conditional_number_visit' => $data[ 'conditional_number_visit' ] ?? '',
					'number_visit'             => $data[ 'number_visit' ] ?? '',
					'triggers'                 => $data[ 'triggers' ] ?? '',
				) );
				break;

			case 'page_visit':
            case 'home_page':
            case 'add_to_cart':
            case 'purchase':
			case 'url_click':
			case 'css_mouseover':
			case 'scroll_pos':
				$this->updateParam( array(
					'triggers' => $data[ 'triggers' ] ?? '',
				) );
				break;

			case 'css_click':
				$this->updateParam( array(
					'triggers'         => $data[ 'triggers' ] ?? '',
					'click_count'      => isset( $data[ 'click_count' ] ) ? max( 1, (int) $data[ 'click_count' ] ) : 1,
					'click_time_limit' => isset( $data[ 'click_time_limit' ] ) ? max( 0, (int) $data[ 'click_time_limit' ] ) : 0,
				) );
				break;

			case 'copy_element':
				$this->updateParam( array(
					'triggers' => $data[ 'triggers' ] ?? '',
				) );
				break;

			case 'video_speed':
				$this->updateParam( array(
					'video_speed_urls'     => $data[ 'video_speed_urls' ] ?? array(),
					'video_speed_rate'     => $data[ 'video_speed_rate' ] ?? 'any',
					'video_speed_data'     => $data[ 'video_speed_data' ] ?? array(),
					'video_speed_triggers' => $data[ 'video_speed_triggers' ] ?? array(),
					'triggers'             => $data[ 'triggers' ] ?? array(),
				) );
				break;

			case 'post_type':
				$this->updateParam( array(
					'post_type_value' => $data[ 'post_type_value' ] ?? '',
				) );
				break;

			case 'video_view':
				$this->updateParam( array(
					'video_view_data'                => $data[ 'video_view_data' ] ?? '',
					'video_view_urls'                => $data[ 'video_view_urls' ] ?? '',
					'video_view_triggers'            => $data[ 'video_view_triggers' ] ?? '',
					'video_view_play_trigger'        => $data[ 'video_view_play_trigger' ] ?? '',
					'video_view_disable_watch_video' => $data[ 'video_view_disable_watch_video' ] ?? '',
					'triggers'                       => $data[ 'triggers' ] ?? '',
				) );
				break;
			case 'elementor_form':
				$this->updateParam( array(
					'elementor_form_urls' => $data[ 'elementor_form_urls' ] ?? '',
					'elementor_form_data' => $data[ 'elementor_form_data' ] ?? '',
				) );
				break;
            case 'form_field':
                $this->updateParam( array(
                    'form_field_urls' => $data[ 'form_field_urls' ] ?? '',
                    'form_field_data' => $data[ 'form_field_data' ] ?? '',
                    'form_field_forms' => $data[ 'form_field_forms' ] ?? '',
                    'form_field_fields' => $data[ 'form_field_fields' ] ?? '',
                    'form_field_type' => $data[ 'form_field_type' ] ?? '',
                    'created_via_est' => $data[ 'created_via_est' ] ?? false,
                ) );
                break;
			case 'email_link':
				$this->updateParam( array(
					'email_link_disable_email_event' => $data[ 'email_link_disable_email_event' ] ?? '',
					'triggers'                       => $data[ 'triggers' ] ?? '',
				) );
				break;

            case 'purchase':
                $this->updateParam( array(
                    'purchase_transaction_only' => $data[ 'purchase_transaction_only' ] ?? false,
                    'track_transaction_ID' => $data['track_transaction_ID'] ?? false,
                    'track_value_and_currency' => $data['track_value_and_currency'] ?? false,
                ) );
                break;
            case 'add_to_cart':
                $this->updateParam(array(
                    'track_value_and_currency' => $data['track_value_and_currency'] ?? false,
                ));
			default:
				break;
		}

	if ( $this->isFormTriggerType( $this->getTriggerType() ) ) {
		$this->updateParam( array(
			'disabled_form_action' => $data[ 'disabled_form_action' ] ?? '',
			'forms'                => $data[ 'forms' ] ?? '',
			'form_submit_mode'     => $data[ 'form_submit_mode' ] ?? 'both',
		) );
	}

	$this->updateParam( array(
		'delay'       => $data[ 'delay' ] ?? '',
		'url_filters' => $data[ 'url_filters' ] ?? '',
	) );
}

}