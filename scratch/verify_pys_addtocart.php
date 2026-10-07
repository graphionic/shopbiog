<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

// Inspect PixelYourSite Facebook module options
$pys_fb = get_option('pys_facebook');

echo "=== PYS FACEBOOK MODULE SETTINGS ===" . PHP_EOL;
echo "Enabled: " . ($pys_fb['enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Pixel IDs: " . implode(', ', $pys_fb['pixel_id'] ?? []) . PHP_EOL;
echo "Woo AddToCart Enabled: " . ($pys_fb['woo_add_to_cart_enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Woo ViewContent Enabled: " . ($pys_fb['woo_view_content_enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Woo InitiateCheckout Enabled: " . ($pys_fb['woo_initiate_checkout_enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Woo Purchase Enabled: " . ($pys_fb['woo_purchase_enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Server API (CAPI) Enabled: " . ($pys_fb['use_server_api'] ? 'YES' : 'NO') . PHP_EOL;

// Check GA4 in PYS
$pys_ga = get_option('pys_ga');
echo PHP_EOL . "=== PYS GA4 MODULE SETTINGS ===" . PHP_EOL;
echo "Enabled: " . ($pys_ga['enabled'] ? 'YES' : 'NO') . PHP_EOL;
echo "Tracking IDs: " . (empty($pys_ga['tracking_id']) ? 'NONE (Empty array)' : implode(', ', $pys_ga['tracking_id'])) . PHP_EOL;
echo "Woo AddToCart Enabled: " . ($pys_ga['woo_add_to_cart_enabled'] ? 'YES' : 'NO') . PHP_EOL;

// Check Google for WooCommerce (GLA)
echo PHP_EOL . "=== GOOGLE FOR WOOCOMMERCE (GLA) ===" . PHP_EOL;
echo "Merchant ID: " . get_option('gla_merchant_id') . PHP_EOL;
echo "Ads ID: " . get_option('gla_ads_id') . PHP_EOL;

// Check TikTok for WooCommerce
echo PHP_EOL . "=== TIKTOK FOR BUSINESS ===" . PHP_EOL;
echo "Pixel Code: " . get_option('tt4b_pixel_code') . PHP_EOL;
