<?php
/**
 * Global Helper Functions for ShopBiOG Core
 *
 * @package ShopBiOG\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get ShopBiOG Core Version.
 *
 * @return string
 */
function shopbiog_core_version() {
    return defined('SHOPBIOG_CORE_VERSION') ? SHOPBIOG_CORE_VERSION : '1.0.0';
}

/**
 * Get asset URL within shopbiog-core plugin.
 *
 * @param string $relative_path Relative path to asset.
 * @return string
 */
function shopbiog_core_asset_url($relative_path = '') {
    return SHOPBIOG_CORE_URL . 'assets/' . ltrim($relative_path, '/');
}

/**
 * Check if a specific core module is active.
 *
 * @param string $module_name Module directory name.
 * @return bool
 */
function shopbiog_is_module_active($module_name) {
    if (class_exists('ShopBiOG_Module_Loader')) {
        return ShopBiOG_Module_Loader::get_instance()->is_active($module_name);
    }
    return false;
}
