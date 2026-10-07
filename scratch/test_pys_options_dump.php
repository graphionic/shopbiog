<?php
define('WP_USE_THEMES', true);
require_once __DIR__ . '/../wp-load.php';

// Simulate single product page load
$_SERVER['REQUEST_URI'] = '/product/test/';
global $wp_query;
$posts = get_posts(['post_type' => 'product', 'posts_per_page' => 1]);
if (!empty($posts)) {
    $wp_query->posts = $posts;
    $wp_query->post = $posts[0];
    $wp_query->is_single = true;
    $wp_query->is_singular = true;
    $wp_query->is_product = true;
}

ob_start();
wp_head();
$head = ob_get_clean();

preg_match('/var pysOptions = (\{.*?\});/s', $head, $m);
if (!empty($m[1])) {
    echo "=== PYS OPTIONS JSON ===" . PHP_EOL;
    $json = json_decode($m[1], true);
    if ($json) {
        echo "woo options:" . PHP_EOL;
        print_r($json['woo'] ?? []);
        echo "dynamicEvents options:" . PHP_EOL;
        print_r($json['dynamicEvents'] ?? []);
    } else {
        echo substr($m[1], 0, 1000);
    }
} else {
    echo "pysOptions not found in head!" . PHP_EOL;
}
