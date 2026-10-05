<?php
namespace PixelYourSite;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Event\RefundEvent;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Event\ViewItemEvent;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Event\PurchaseEvent;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\ItemParameter;
use PixelYourSite\GoogleAnalytics\Server\CustomEvent;

// Include our custom event class
require_once __DIR__ . '/CustomEvent.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class GaServerEventHelper
{
    static $uaMap = [
        'cn' => 'traffic_source',
        'ec' => 'event_category',
        'tt' => 'tax',
        'tr' => 'value',
        'ti' => 'transaction_id',
        'cu' => 'currency',
        'dr' => 'traffic_source'
    ];
    /**
     * @param SingleEvent $singleEvent
     * @return array|null
     */
    static public function mapSingleEventToServerData($singleEvent)
    {
        switch ($singleEvent->payload['name']) {
            case 'purchase': {
                    return self::mapPurchaseToServerData($singleEvent);
                }
            case 'refund': {
                    return self::mapRefundToServerData($singleEvent);
                }
        }

        return null;
    }

    /**
     * @param SingleEvent $singleEvent
     */
    static public function mapSingleEventToServerDataGA4($singleEvent)
    {
        switch ($singleEvent->payload['name']) {
            case 'purchase': {
                    return self::mapPurchaseToServerDataGA4($singleEvent);
                }
            case 'refund': {
                    return self::mapRefundToServerDataGA4($singleEvent);
                }
            case 'StartTrial':
            case 'Subscribe':
            case 'SubscriptionRenewal':
            case 'SubscriptionExpired':
            case 'SubscriptionCanceled':
            case 'LicenseCreated':
            case 'LicenseUpgrade':
            case 'LicenseRenewal':
            case 'LicenseExpired': {
                    return self::mapSubsLicenseToServerDataGA4($singleEvent);
                }
            case 'ProfitConversion': {
                    return self::mapProfitConversionToServerDataGA4($singleEvent);
                }
        }

        return null;
    }

    /**
     * @param SingleEvent $singleEvent
     * @return array
     */
    static private function mapPurchaseToServerData($singleEvent)
    {
        $data = $singleEvent->getData();
        $params = $data['params'];

        $serverParams = [
            't' => 'event',
            'pa' => 'purchase',
            'ea' => 'purchase',
            'el' => "Server Purchase",
            'cid' => EventIdGenerator::guidv4(),

            'ti' => $params['transaction_id'], // transaction ID, required
            'tr' => $params['value'], // revenue
            'tt' => $params['tax'], // tax
            'cu' => $params['currency'], // order currency
        ];
        if (isset($params['coupon'])) {
            $serverParams['tcc'] = $params['coupon']; // coupon code
        }

        if (isset($params['shipping'])) {
            $serverParams['ts'] = $params['shipping'];
        }

        foreach (self::$uaMap as $key => $val) {
            if (isset($params[$val])) {
                $serverParams[$key] = $params[$val];
            }
        }

        for ($i = 1; $i <= count($params['items']); $i++) {
            $item = $params['items'][$i - 1];
            $serverParams["pr{$i}id"] = $item['item_id'] ?? '';
            $serverParams["pr{$i}nm"] = $item['item_name'] ?? '';
            $serverParams["pr{$i}ca"] = $item['item_category'] ?? '';
            $serverParams["pr{$i}pr"] = $item['price'] ?? '';
            $serverParams["pr{$i}qt"] = $item['quantity'] ?? '';
        }

        return $serverParams;
    }
    static private function mapRefundToServerData($singleEvent)
    {
        $data = $singleEvent->getData();
        $params = $data['params'];

        $serverParams = [
            't' => 'event',
            'ec' => 'Ecommerce',
            'ea' => 'Refund',
            'el' => "Server Refund",
            'cid' => EventIdGenerator::guidv4(),

            'ti' => $params['transaction_id'], // transaction ID, required
            'tr' => -$params['value'], // revenue
            'cu' => $params['currency'], // order currency
        ];

        return $serverParams;
    }

    /**
     * Map purchase event to GA4 server data
     * 
     * @param SingleEvent $singleEvent
     * @return PurchaseEvent
     */
    static private function mapPurchaseToServerDataGA4($singleEvent)
    {
        // Get and filter event data
        $data = $singleEvent->getData();
        $data = EventsManager::filterEventParams($data, $singleEvent->getCategory(), [
            'event_id' => $singleEvent->getId(),
            'pixel' => GA()->getSlug()
        ]);

        $params = $data['params'];

        // Create PurchaseEvent
        $purchaseEventData = new PurchaseEvent();

        // Set base event parameters using helper function
        self::setEventBaseParams($purchaseEventData, $params, $singleEvent);

        // Add items if available
        if (isset($params['items']) && is_array($params['items'])) {
            $currency = $params['currency'] ?? 'USD';

            foreach ($params['items'] as $item) {
                $itemParameter = self::createItemParameter($item, $currency);
                $purchaseEventData->addItem($itemParameter);
            }
        }

        // Process additional parameters using helper function
        self::processAdditionalEventParams($purchaseEventData, $params, $singleEvent);

        return $purchaseEventData;
    }

    /**
     * Create ItemParameter from item data array
     * 
     * @param array $item Item data
     * @param string $currency Currency code
     * @return ItemParameter
     */
    static private function createItemParameter($item, $currency)
    {
        $itemParameter = new ItemParameter();

        // Set required parameters
        $itemParameter
            ->setItemId($item['item_id'] ?? '')
            ->setItemName($item['item_name'] ?? '')
            ->setCurrency($currency)
            ->setPrice($item['price'] ?? 0)
            ->setQuantity($item['quantity'] ?? 1);

        // Set optional parameters with mapping
        $optionalParams = [
            'item_category' => 'setItemCategory',
            'item_category2' => 'setItemCategory2',
            'item_category3' => 'setItemCategory3',
            'item_category4' => 'setItemCategory4',
            'item_category5' => 'setItemCategory5',
            'variant' => 'setItemVariant',
            'item_list_name' => 'setItemListName',
            'item_list_id' => 'setItemListId',
            'affiliation' => 'setAffiliation',
            'item_brand' => 'setItemBrand',
        ];

        foreach ($optionalParams as $key => $method) {
            if (isset($item[$key]) && !empty($item[$key])) {
                $itemParameter->$method($item[$key]);
            }
        }

        return $itemParameter;
    }

    /**
     * Set base event parameters (value, currency, tax, shipping, etc.)
     * 
     * @param object $event Event object (PurchaseEvent, CustomEvent, etc.)
     * @param array $params Event parameters
     * @param SingleEvent $singleEvent Original single event
     * @return void
     */
    static private function setEventBaseParams($event, $params, $singleEvent)
    {
        // Set main parameters if available
        if (isset($params['value'])) {
            $event->setValue($params['value']);
        }
        if (isset($params['currency'])) {
            $event->setCurrency($params['currency']);
        }
        if (isset($params['transaction_id'])) {
            $event->setTransactionId($params['transaction_id']);
        }
        if (isset($params['tax'])) {
            $event->setTax($params['tax']);
        }
        if (isset($params['shipping'])) {
            $event->setShipping($params['shipping']);
        }

        // Set UA mapped parameters
        foreach (self::$uaMap as $val) {
            if (isset($params[$val])) {
                $event->setParamValue($val, $params[$val]);
            }
        }

        // Set event_id
        if (isset($params['event_id']) && !empty($params['event_id'])) {
            $event->setParamValue('event_id', $params['event_id']);
        }
        elseif (!empty($singleEvent->getPayloadValue('eventID'))) {
            $event->setParamValue('event_id', $singleEvent->getPayloadValue('eventID'));
        }

        // Set additional tracking parameters
        if (isset($params['advanced_purchase_tracking']) && !empty($params['advanced_purchase_tracking'])) {
            $event->setParamValue('advanced_purchase_tracking', $params['advanced_purchase_tracking']);
        }
        if (isset($params['new_customer']) && !empty($params['new_customer'])) {
            $event->setParamValue('new_customer', $params['new_customer']);
        }
    }

    /**
     * Process and add additional event parameters that were not already processed
     * 
     * @param object $event Event object
     * @param array $params Event parameters
     * @param SingleEvent $singleEvent Original single event
     * @return void
     */
    static private function processAdditionalEventParams($event, $params, $singleEvent)
    {
        // Define already processed parameters
        $processed_params = [
            'value', 'currency', 'transaction_id', 'tax', 'shipping',
            'items', 'event_id', 'eventID', 'advanced_purchase_tracking', 'new_customer'
        ];

        // Add uaMap keys to processed list
        $processed_params = array_merge($processed_params, self::$uaMap);

        // Add remaining parameters
        foreach ($params as $key => $value) {
            if (in_array($key, $processed_params, true)) {
                continue;
            }

            // Convert array values to comma-separated string
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $event->setParamValue($key, $value);
        }

        // Apply custom filter for additional parameters
        $custom_filter_data_event = apply_filters('pys_event_data', array(), $singleEvent->getCategory(), [
            'event_id' => $singleEvent->getId(),
            'pixel' => GA()->getSlug()
        ]);

        if (isset($custom_filter_data_event['params']) && !empty($custom_filter_data_event['params'])) {
            foreach ($custom_filter_data_event['params'] as $key => $value) {
                // Convert array values to comma-separated string
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $event->setParamValue($key, $value);
            }
        }
    }

    /**
     * Map subscription event to GA4 server data
     * 
     * @param SingleEvent $singleEvent
     * @return CustomEvent
     */
    static private function mapSubsLicenseToServerDataGA4($singleEvent)
    {
        // Get and filter event data
        $data = $singleEvent->getData();
        $data = EventsManager::filterEventParams($data, $singleEvent->getCategory(), [
            'event_id' => $singleEvent->getId(),
            'pixel' => GA()->getSlug()
        ]);

        $params = $data['params'];

        // Get event name from the SingleEvent
        $eventName = $singleEvent->payload['name'] ?? 'custom_event';

        // Create CustomEvent with the event name
        $customEventData = new CustomEvent($eventName);

        // Set base event parameters using helper function
        self::setEventBaseParams($customEventData, $params, $singleEvent);

        // Add items if available
        if (isset($params['items']) && is_array($params['items'])) {
            $currency = $params['currency'] ?? 'USD';

            foreach ($params['items'] as $item) {
                $itemParameter = self::createItemParameter($item, $currency);
                $customEventData->addItem($itemParameter);
            }
        }

        // Process additional parameters using helper function
        self::processAdditionalEventParams($customEventData, $params, $singleEvent);

        return $customEventData;
    }
    /**
     * Map POAS ProfitConversion event for GA4 Measurement Protocol.
     *
     * @param SingleEvent $singleEvent
     * @return BaseEvent
     */
    static private function mapProfitConversionToServerDataGA4($singleEvent)
    {
        $data = $singleEvent->getData();
        $params = $data['params'];

        // Create CustomEvent with the event name
        $customEventData = new CustomEvent('ProfitConversion');

        // Set base event parameters using helper function
        self::setEventBaseParams($customEventData, $params, $singleEvent);

        // Process additional parameters using helper function
        self::processAdditionalEventParams($customEventData, $params, $singleEvent);

        return $customEventData;
    }
    static private function mapRefundToServerDataGA4($singleEvent)
    {
        $data = $singleEvent->getData();
        $params = $data['params'];
        $refundEventData = new RefundEvent();
        $refundEventData->setValue($params['value'])
            ->setCurrency($params['currency'])
            ->setTransactionId($params['transaction_id']);

        foreach (self::$uaMap as $val) {
            if (isset($params[$val])) {
                $refundEventData->setParamValue($val, $params[$val]);
            }
        }

        return $refundEventData;
    }

    public static function getClientId()
    {
        $clientID = null;

        if (isset($_COOKIE['_ga']) && !empty($_COOKIE['_ga'])) {
            $cookieValue = sanitize_text_field($_COOKIE['_ga']);
            $cookieParts = explode('.', $cookieValue);
            $clientID = $cookieParts[2] . '.' . $cookieParts[3];
        }
        return $clientID;
    }

    /**
     * Parse GA4 cookies: _ga and _ga_<MEASUREMENT_ID>
     *
     * @return array|null
     */
    public static function parseGaCookies()
    {
        $result = [
            'clientId' => null,
            'sessions' => []
        ];

        // 1. Parse _ga (clientId)
        if (!empty($_COOKIE['_ga'])) {
            // Example: GA1.2.1234567890.1694000000
            $parts = explode('.', $_COOKIE['_ga']);
            if (count($parts) === 4) {
                $cid1 = $parts[2]; // 1234567890
                $cid2 = $parts[3]; // 1694000000
                $result['clientId'] = $cid1 . '.' . $cid2;
            }
        }

        // 2. Parse all _ga_<MEASUREMENT_ID>
        foreach ($_COOKIE as $name => $value) {
            if (preg_match('/^_ga_(.+)$/', $name, $matches)) {
                $measurementId = $matches[1]; // e.g. "7J57J7JPK6"

                $sessionData = [
                    'session_id' => null,
                    'session_number' => null,
                    'last_event' => null,
                ];

                // Split by $
                $parts = explode('$', $value);

                foreach ($parts as $part) {
                    // Format: GS2.1.s1758214452 or s1758214452 (session_id)
                    if (preg_match('/s(\d+)/', $part, $m)) {
                        $sessionData['session_id'] = (int)$m[1];
                    }
                    // Format: o1 (session_number)
                    if (preg_match('/^o(\d+)$/', $part, $m)) {
                        $sessionData['session_number'] = (int)$m[1];
                    }
                    // Format: t1758217068 (last_event timestamp)
                    if (preg_match('/^t(\d+)$/', $part, $m)) {
                        $sessionData['last_event'] = (int)$m[1];
                    }
                }

                $result['sessions'][$measurementId] = $sessionData;
            }
        }

        return $result;
    }

    /**
     * Get GA data from order
     * 
     * Supports both new GA4 data structure and old structure for backward compatibility
     * 
     * Usage examples:
     * - getGAStatFromOrder('clientId', $order_id, 'woo') - get client_id
     * - getGAStatFromOrder('session_id', $order_id, 'woo', 'G-XXXXXXXXXX') - get session_id for specific measurement_id
     * 
     * @param string $key Data key ('clientId', 'session_id', etc.)
     * @param string $order_id Order ID
     * @param string $type Order type ('woo' or 'edd')
     * @param string|null $measurement_id GA4 Measurement ID (required for session_id)
     * @return string
     */
    public static function getGAStatFromOrder($key, $order_id, $type, $measurement_id = null)
    {

        $cleanMeasurementId = $measurement_id ? str_replace('G-', '', $measurement_id) : null;

        if(!is_admin()){
            $gaCookie = GaServerEventHelper::parseGaCookies() ?: [];
        }
        else{
            $gaCookie = self::getOrderMeta($type, $order_id, 'pys_ga_cookie') ?: [];
        }
        // --- CLIENT ID -----------------------------------------------------------
        if ($key === 'clientId') {

            // 1. Return existing clientId from current order
            if (!empty($gaCookie['clientId'])) {
                return (string)$gaCookie['clientId'];
            }

            // 2. Try parent order
            $parentClientId = self::getParentClientId($type, $order_id);
            if (is_string($parentClientId) && $parentClientId !== '') {
                return $parentClientId;
            }

            // 3. Generate new clientId and save it
            $clientID = EventIdGenerator::generate_ga4_client_id();
            $gaCookie['clientId'] = $clientID;
            self::updateOrderMeta($type, $order_id, 'pys_ga_cookie', $gaCookie);

            return $clientID;
        }

        // --- SESSION VALUES ------------------------------------------------------
        if (
        $cleanMeasurementId &&
        in_array($key, ['session_id', 'session_number'], true) &&
        isset($gaCookie['sessions'][$cleanMeasurementId][$key])
        ) {
            return (string)$gaCookie['sessions'][$cleanMeasurementId][$key];
        }

        // --- OLD FORMAT (FALLBACK) ----------------------------------------------
        if (!empty($gaCookie[$key])) {
            return (string)$gaCookie[$key];
        }

        return '';
    }

    private static function getOrderMeta($type, $order_id, $key)
    {
        if ($type === 'woo') {
            $order = wc_get_order($order_id);
            return $order ? $order->get_meta($key, true) : null;
        }
        if ($type === 'edd') {
            return edd_get_order_meta($order_id, $key, true);
        }
        return null;
    }

    /**
     * Get clientId from parent order for renewals/refunds
     * 
     * @param string $type Order type ('woo' or 'edd')
     * @param string $order_id Order ID
     * @return string|null
     */
    private static function getParentClientId($type, $order_id)
    {
        if ($type === 'woo') {
            $order = wc_get_order($order_id);
            if (!$order) {
                return null;
            }

            $parent_id = $order->get_parent_id();
            if ($parent_id) {
                $parent_order = wc_get_order($parent_id);
                if ($parent_order) {
                    $parentGaCookie = $parent_order->get_meta('pys_ga_cookie', true);
                    if (!empty($parentGaCookie['clientId'])) {
                        return $parentGaCookie['clientId'];
                    }
                }
            }
        }

        if ($type === 'edd') {
            $order = edd_get_order($order_id);
            if ($order && ($order->status === 'edd_subscription' || $order->type === 'refund')) {
                $parentGaCookie = edd_get_order_meta($order->parent, 'pys_ga_cookie', true);
                if (!empty($parentGaCookie['clientId'])) {
                    return $parentGaCookie['clientId'];
                }
            }
        }

        return null;
    }

    private static function updateOrderMeta($type, $order_id, $key, $value)
    {
        if ($type === 'woo') {
            $order = wc_get_order($order_id);
            if ($order) {
                $order->update_meta_data($key, $value);
                $order->save();
            }
        }
        if ($type === 'edd') {
            edd_update_payment_meta($order_id, $key, $value);
        }
    }
}
