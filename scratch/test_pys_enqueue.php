<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

// Simulate frontend environment
do_action('wp_enqueue_scripts');

ob_start();
wp_print_scripts();
wp_print_styles();
$scripts = ob_get_clean();

echo "=== PRINTED SCRIPTS ===" . PHP_EOL;
preg_match_all('/var pysOptions = (\{.*?\});/s', $scripts, $m);
if (!empty($m[1])) {
    echo "pysOptions found!" . PHP_EOL;
    $json = json_decode($m[1][0], true);
    if ($json) {
        echo "woo options:" . PHP_EOL;
        print_r($json['woo'] ?? []);
        echo "dynamicEvents options:" . PHP_EOL;
        print_r($json['dynamicEvents'] ?? []);
    } else {
        echo substr($m[1][0], 0, 1000) . PHP_EOL;
    }
} else {
    echo "Searching for pys script tags..." . PHP_EOL;
    preg_match_all('/<script.*?>.*?<\/script>/s', $scripts, $sm);
    foreach ($sm[0] as $s) {
        if (strpos($s, 'pys') !== false) {
            echo "Script block: " . substr($s, 0, 300) . PHP_EOL;
        }
    }
}
