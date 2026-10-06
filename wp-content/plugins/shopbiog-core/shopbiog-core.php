<?php
/**
 * Plugin Name: ShopBiOG Core
 * Plugin URI:  https://shopbiog.com
 * Description: Core functionality and custom architecture layer for BiO-G / shopbiog.com.
 * Version:     1.0.0
 * Author:      BiO-G Development Team
 * Text Domain: shopbiog-core
 * Domain Path: /languages
 *
 * @package ShopBiOG\Core
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Plugin Constants.
define('SHOPBIOG_CORE_VERSION', '1.0.0');
define('SHOPBIOG_CORE_FILE', __FILE__);
define('SHOPBIOG_CORE_PATH', plugin_dir_path(__FILE__));
define('SHOPBIOG_CORE_URL', plugin_dir_url(__FILE__));

// Require Bootstrap Loader.
require_once SHOPBIOG_CORE_PATH . 'includes/bootstrap.php';

/**
 * Initialize the ShopBiOG Core Plugin.
 *
 * @return ShopBiOG_Bootstrap
 */
function shopbiog_core() {
    return ShopBiOG_Bootstrap::get_instance();
}

// Boot plugin.
shopbiog_core();
