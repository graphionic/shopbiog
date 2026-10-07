<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';
global $wpdb;

echo "=== ALL WPCODE & IHAF OPTIONS (FULL UNTRUNCATED) ===" . PHP_EOL;
$options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%wpcode%' OR option_name LIKE '%ihaf%'", ARRAY_A);

foreach ($options as $opt) {
    echo "==========================================" . PHP_EOL;
    echo "OPTION NAME: " . $opt['option_name'] . PHP_EOL;
    $unserialized = @unserialize($opt['option_value']);
    if ($unserialized !== false || $opt['option_value'] === 'b:0;') {
        echo "VAL (Unserialized):" . PHP_EOL;
        print_r($unserialized);
    } else {
        echo "VAL (Raw):" . PHP_EOL;
        echo $opt['option_value'] . PHP_EOL;
    }
}
