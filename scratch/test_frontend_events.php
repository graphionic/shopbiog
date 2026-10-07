<?php
define('WP_USE_THEMES', true);
require_once __DIR__ . '/../wp-load.php';

// Simulate a single product page request
$_SERVER['REQUEST_URI'] = '/shop/'; // or a product page
$query = new WP_Query(['post_type' => 'product', 'posts_per_page' => 1]);
if ($query->have_posts()) {
    $query->the_post();
    $product_id = get_the_ID();
    echo "Testing product ID: $product_id" . PHP_EOL;
}

// Inspect PYS output in wp_head / wp_footer
ob_start();
wp_head();
$head_output = ob_get_clean();

ob_start();
wp_footer();
$footer_output = ob_get_clean();

echo "=== HEAD OUTPUT HIGHLIGHTS ===" . PHP_EOL;
if (strpos($head_output, 'pys') !== false) {
    echo "PYS script found in head!" . PHP_EOL;
    // Extract PYS js options object if printed inline
    preg_match_all('/var pysOptions = (\{.*?\});/s', $head_output, $matches);
    if (!empty($matches[1])) {
        echo "pysOptions found in head: " . substr($matches[1][0], 0, 500) . PHP_EOL;
    }
}
if (strpos($head_output, 'fbq(') !== false) {
    echo "fbq calls found in head!" . PHP_EOL;
}
if (strpos($head_output, 'gtag(') !== false) {
    echo "gtag calls found in head!" . PHP_EOL;
}
if (strpos($head_output, 'ttq') !== false) {
    echo "ttq (TikTok) calls found in head!" . PHP_EOL;
}

echo PHP_EOL . "=== FOOTER OUTPUT HIGHLIGHTS ===" . PHP_EOL;
if (strpos($footer_output, 'pys') !== false) {
    echo "PYS script found in footer!" . PHP_EOL;
}
if (strpos($footer_output, '9182') !== false || strpos($footer_output, 'AddToCart Click Bridge') !== false) {
    echo "Snippet #9182 script found in footer!" . PHP_EOL;
}
