<?php
/**
 * Performance Module Bootstrapper for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Performance
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-assets.php';
require_once __DIR__ . '/class-woocommerce-performance.php';
require_once __DIR__ . '/class-database-hygiene.php';
require_once __DIR__ . '/class-image-performance.php';
require_once __DIR__ . '/class-cache-compatibility.php';

// Initialize performance sub-modules
ShopBiOG_Performance_Assets::get_instance();
ShopBiOG_WooCommerce_Performance::get_instance();
ShopBiOG_Database_Hygiene::get_instance();
ShopBiOG_Image_Performance::get_instance();
ShopBiOG_Cache_Compatibility::get_instance();


