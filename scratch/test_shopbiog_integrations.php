<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

echo "=== SHOPBIOG CORE INTEGRATIONS MODULE VERIFICATION ===" . PHP_EOL;

$loader = ShopBiOG_Module_Loader::get_instance();
echo "Loaded modules: " . implode(', ', $loader->get_loaded_modules()) . PHP_EOL;
echo "Integrations module active: " . ($loader->is_active('integrations') ? 'YES' : 'NO') . PHP_EOL;

// Verify filter facebook_for_woocommerce_integration_pixel_enabled
$pixel_enabled = apply_filters('facebook_for_woocommerce_integration_pixel_enabled', true);
echo "facebook_for_woocommerce_integration_pixel_enabled filter result: " . ($pixel_enabled ? 'TRUE (Pixel Active)' : 'FALSE (Pixel Disabled - Catalog Only)') . PHP_EOL;

if ($pixel_enabled === false) {
    echo "SUCCESS: Filter correctly returns false! Meta browser pixel disabled while catalog remains active." . PHP_EOL;
} else {
    echo "FAILURE: Filter did not return false!" . PHP_EOL;
}
