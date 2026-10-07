<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';
global $wpdb;

$opts = $wpdb->get_results("SELECT option_name, CHAR_LENGTH(option_value) as len FROM {$wpdb->options} WHERE option_name LIKE '%pys%' OR option_name LIKE '%pixel%' OR option_name LIKE '%gtag%' OR option_name LIKE '%ga%' OR option_name LIKE '%woo%'", ARRAY_A);

foreach ($opts as $o) {
    if (strpos($o['option_name'], 'pys') !== false || strpos($o['option_name'], 'pixel') !== false || strpos($o['option_name'], 'gtag') !== false) {
        echo $o['option_name'] . " (len: " . $o['len'] . ")" . PHP_EOL;
    }
}
