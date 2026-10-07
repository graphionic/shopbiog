<?php
define('WP_USE_THEMES', true);
require_once __DIR__ . '/../wp-load.php';

$test_routes = [
    'Home' => '/',
    'Shop' => '/shop/',
    'Product' => '/product/sample/',
    'Cart' => '/cart/',
    'Checkout' => '/checkout/',
    'My Account' => '/my-account/'
];

echo "=== FULL SITE FUNCTIONAL & HEALTH CHECK ===" . PHP_EOL;

foreach ($test_routes as $name => $uri) {
    $_SERVER['REQUEST_URI'] = $uri;
    ob_start();
    try {
        wp_head();
        wp_footer();
        $out = ob_get_clean();
        echo " - $name ($uri): OK (Length: " . strlen($out) . " bytes)" . PHP_EOL;
    } catch (Throwable $e) {
        ob_end_clean();
        echo " - $name ($uri): ERROR -> " . $e->getMessage() . PHP_EOL;
    }
}

// Check active plugins count
$active = get_option('active_plugins', []);
echo PHP_EOL . "Active Plugins Count: " . count($active) . PHP_EOL;
foreach ($active as $p) {
    echo "  - $p" . PHP_EOL;
}
