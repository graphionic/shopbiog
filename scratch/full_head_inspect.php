<?php
define('WP_USE_THEMES', true);
require_once __DIR__ . '/../wp-load.php';

// Set up globals as if viewing a page
global $wp, $wp_query, $wp_the_query;
$wp->main();

ob_start();
wp_head();
$head = ob_get_clean();

echo "=== HEAD LENGTH: " . strlen($head) . " ===" . PHP_EOL;

preg_match_all('/<script.*?>.*?<\/script>/s', $head, $scripts);
foreach ($scripts[0] as $s) {
    if (strpos($s, 'pys') !== false || strpos($s, 'fbq') !== false || strpos($s, 'gtag') !== false || strpos($s, 'ttq') !== false || strpos($s, 'pysOptions') !== false) {
        echo "--------------------------------------------------------" . PHP_EOL;
        echo substr($s, 0, 800) . PHP_EOL;
    }
}
