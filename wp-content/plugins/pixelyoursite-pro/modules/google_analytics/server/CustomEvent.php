<?php
/**
 * Custom Event class for GA4 Measurement Protocol
 * Compatible with Br33f\Ga4\MeasurementProtocol library
 * 
 * This class extends ItemBaseEvent to support custom events like subscriptions
 * that need item support but don't fit into standard GA4 event types.
 * 
 * IMPORTANT: This class is in the PixelYourSite\GoogleAnalytics\Server namespace
 * to avoid conflicts with the main CustomEvent class from includes/class-custom-event.php
 * 
 * Main CustomEvent (PixelYourSite\CustomEvent) - for custom events in WP admin
 * This CustomEvent - for server-side GA4 events via Measurement Protocol API
 * 
 * @see \PixelYourSite\CustomEvent - main class for custom events
 */

namespace PixelYourSite\GoogleAnalytics\Server;

use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Event\ItemBaseEvent;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\AbstractParameter;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Enum\ErrorCode;
use PYS_PRO_GLOBAL\Br33f\Ga4\MeasurementProtocol\Exception\ValidationException;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class CustomEvent
 * 
 * Extends ItemBaseEvent to provide a flexible event type for custom GA4 events.
 * Supports all standard GA4 parameters plus custom parameters.
 * 
 * @package PixelYourSite\GoogleAnalytics\Server
 * 
 * Usage example:
 * ```php
 * use PixelYourSite\GoogleAnalytics\Server\CustomEvent;
 * 
 * $event = new CustomEvent('subscribe');
 * $event->setValue(29.99)
 *       ->setCurrency('USD')
 *       ->setTransactionId('sub_12345');
 * ```
 * 
 * Standard methods (via magic __call):
 * @method string getCurrency()
 * @method CustomEvent setCurrency(string $currency)
 * @method string getTransactionId()
 * @method CustomEvent setTransactionId(string $transactionId)
 * @method float getValue()
 * @method CustomEvent setValue(float $value)
 * @method string getAffiliation()
 * @method CustomEvent setAffiliation(string $affiliation)
 * @method string getCoupon()
 * @method CustomEvent setCoupon(string $coupon)
 * @method float getShipping()
 * @method CustomEvent setShipping(float $shipping)
 * @method float getTax()
 * @method CustomEvent setTax(float $tax)
 */
class CustomEvent extends ItemBaseEvent
{
    /**
     * @var string The event name (e.g., 'subscribe', 'cancel_subscription', etc.)
     */
    private $eventName;

    /**
     * CustomEvent constructor.
     * 
     * @param string|null $eventName The name of the custom event
     * @param AbstractParameter[] $paramList Optional list of parameters
     */
    public function __construct(?string $eventName = null, array $paramList = [])
    {
        parent::__construct($eventName, $paramList);
        $this->eventName = $eventName;
    }

    /**
     * Get the event name
     * 
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->eventName;
    }

    /**
     * Set the event name
     * 
     * @param string|null $eventName
     * @return CustomEvent
     */
    public function setEventName(?string $eventName): self
    {
        $this->eventName = $eventName;
        return $this;
    }

    /**
     * Validate the event
     * 
     * @return bool
     * @throws ValidationException
     */
    public function validate()
    {
        // Validate items (inherited from ItemBaseEvent)
        parent::validate();

        // Validate that event name is set
        if (empty($this->eventName)) {
            throw new ValidationException(
                'Event name is required',
                ErrorCode::VALIDATION_NAME_EMPTY
            );
        }

        // Validate all parameters
        foreach ($this->getParamList() as $parameter) {
            if (method_exists($parameter, 'validate')) {
                $parameter->validate();
            }
        }

        return true;
    }

    /**
     * Export event data for API request
     * 
     * @return array
     */
    public function export(): array
    {
        $preparedParams = [];
        
        foreach ($this->getParamList() as $parameterName => $parameter) {
            $parameterExportedValue = $parameter->export();
            if (!is_null($parameterExportedValue)) {
                $preparedParams[$parameterName] = $parameterExportedValue;
            }
        }

        return [
            'name' => $this->getName(),
            // Use ArrayObject to ensure proper JSON encoding as object, not array
            'params' => new \ArrayObject($preparedParams),
        ];
    }
}

