<?php
/**
 * Integrations Module Bootstrapper for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Integrations
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-meta-integration.php';

// Initialize Meta catalog integration & pixel deduplication filter.
ShopBiOG_Meta_Integration::get_instance();
