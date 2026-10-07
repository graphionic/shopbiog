<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

echo "=== PIXELYOURSITE DETAILED OPTIONS ===" . PHP_EOL;

$options_to_check = ['pixelyoursite_common', 'pixelyoursite_facebook', 'pixelyoursite_ga', 'pixelyoursite_tiktok', 'pixelyoursite_bing', 'pixelyoursite_pinterest'];

foreach ($options_to_check as $opt_name) {
    $val = get_option($opt_name);
    echo "--- Option: $opt_name ---" . PHP_EOL;
    if (is_array($val)) {
        foreach ($val as $k => $v) {
            if (is_array($v)) {
                echo "  $k => array(" . count($v) . ")" . PHP_EOL;
                foreach ($v as $k2 => $v2) {
                    if (!is_array($v2)) {
                        $str = (string)$v2;
                        if (strlen($str) > 15 && (strpos($k2, 'token') !== false || strpos($k2, 'key') !== false || strpos($k2, 'secret') !== false)) {
                            $str = substr($str, 0, 4) . '...' . substr($str, -4);
                        }
                        echo "     $k2 => $str" . PHP_EOL;
                    }
                }
            } else {
                $str = (string)$v;
                if (strlen($str) > 15 && (strpos($k, 'token') !== false || strpos($k, 'key') !== false || strpos($k, 'secret') !== false)) {
                    $str = substr($str, 0, 4) . '...' . substr($str, -4);
                }
                echo "  $k => $str" . PHP_EOL;
            }
        }
    } else {
        var_dump($val);
    }
}
