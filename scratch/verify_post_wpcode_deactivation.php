<?php
define('WP_USE_THEMES', true);
require_once __DIR__ . '/../wp-load.php';

$pages = [
    'Homepage' => '/',
    'Shop' => '/shop/',
    'Product' => '/product/test/',
    'Cart' => '/cart/',
    'Checkout' => '/checkout/'
];

$results = [];

foreach ($pages as $label => $uri) {
    $_SERVER['REQUEST_URI'] = $uri;
    
    // Simulate query environment if needed
    if ($label === 'Product') {
        global $wp_query;
        $posts = get_posts(['post_type' => 'product', 'posts_per_page' => 1]);
        if (!empty($posts)) {
            $wp_query->posts = $posts;
            $wp_query->post = $posts[0];
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            $wp_query->is_product = true;
        }
    }

    ob_start();
    wp_head();
    $head = ob_get_clean();

    ob_start();
    wp_footer();
    $footer = ob_get_clean();

    $output = $head . $footer;

    $has_pys = strpos($output, 'pys') !== false;
    $has_fbq = strpos($output, 'fbq(') !== false || strpos($output, '1202704947165676') !== false;
    $has_gtag = strpos($output, 'gtag') !== false || strpos($output, 'G-VP3TGE9JK1') !== false;
    $has_tiktok = strpos($output, 'ttq') !== false || strpos($output, 'DA36MDJC77UBD9OLRLP0') !== false;
    $has_wpcode_snippet = strpos($output, '9182') !== false || strpos($output, 'AddToCart Click Bridge') !== false;
    
    $results[$label] = [
        'length' => strlen($output),
        'has_pys' => $has_pys,
        'has_meta' => $has_fbq,
        'has_ga4' => $has_gtag,
        'has_tiktok' => $has_tiktok,
        'wpcode_snippet_present' => $has_wpcode_snippet,
    ];
}

echo "=== POST-DEACTIVATION TRACKING VERIFICATION ===" . PHP_EOL;
print_r($results);

// Check Meta filter result
$filter_result = apply_filters('facebook_for_woocommerce_integration_pixel_enabled', true);
echo "Meta Pixel Filter Result: " . ($filter_result ? 'ACTIVE (Error!)' : 'DISABLED (Correct - Catalog Only)') . PHP_EOL;
