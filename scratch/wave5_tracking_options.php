<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';
global $wpdb;

echo "=== PIXELYOURSITE OPTIONS ===" . PHP_EOL;
$pys_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%pixelyoursite%' OR option_name LIKE '%pys_%'", ARRAY_A);
foreach ($pys_options as $opt) {
    echo "Option: " . $opt['option_name'] . PHP_EOL;
    $val = @unserialize($opt['option_value']);
    if ($val === false && $opt['option_value'] !== 'b:0;') {
        $val = json_decode($opt['option_value'], true) ?? $opt['option_value'];
    }
    if (is_array($val)) {
        foreach ($val as $k => $v) {
            if (is_array($v)) {
                echo "   $k => [array length " . count($v) . "]" . PHP_EOL;
                foreach ($v as $subk => $subv) {
                    if (is_string($subv) || is_numeric($subv) || is_bool($subv)) {
                        // Mask tokens/keys if they look secret
                        $display = (string)$subv;
                        if (strlen($display) > 20 && (strpos($k, 'token') !== false || strpos($k, 'key') !== false || strpos($subk, 'token') !== false || strpos($subk, 'secret') !== false)) {
                            $display = substr($display, 0, 4) . '***' . substr($display, -4);
                        }
                        echo "      $subk => $display" . PHP_EOL;
                    }
                }
            } else {
                $display = (string)$v;
                if (strlen($display) > 20 && (strpos($k, 'token') !== false || strpos($k, 'key') !== false || strpos($k, 'secret') !== false)) {
                    $display = substr($display, 0, 4) . '***' . substr($display, -4);
                }
                echo "   $k => $display" . PHP_EOL;
            }
        }
    } else {
        echo "   Value: " . substr((string)$val, 0, 150) . PHP_EOL;
    }
    echo "----------------------------------------------" . PHP_EOL;
}

echo PHP_EOL . "=== META FOR WOOCOMMERCE OPTIONS ===" . PHP_EOL;
$fb_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%facebook%' OR option_name LIKE '%wc_facebook%'", ARRAY_A);
foreach ($fb_options as $opt) {
    echo "Option: " . $opt['option_name'] . " => " . substr($opt['option_value'], 0, 100) . PHP_EOL;
}

echo PHP_EOL . "=== GOOGLE FOR WOOCOMMERCE OPTIONS ===" . PHP_EOL;
$g_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%gla_%' OR option_name LIKE '%google_listings%'", ARRAY_A);
foreach ($g_options as $opt) {
    echo "Option: " . $opt['option_name'] . " => " . substr($opt['option_value'], 0, 100) . PHP_EOL;
}

echo PHP_EOL . "=== TIKTOK FOR BUSINESS OPTIONS ===" . PHP_EOL;
$tt_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%tiktok%'", ARRAY_A);
foreach ($tt_options as $opt) {
    echo "Option: " . $opt['option_name'] . " => " . substr($opt['option_value'], 0, 100) . PHP_EOL;
}

echo PHP_EOL . "=== FUNNELKIT TRACKING OPTIONS ===" . PHP_EOL;
$fk_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%wffn%' OR option_name LIKE '%funnel%'", ARRAY_A);
foreach ($fk_options as $opt) {
    if (strpos($opt['option_name'], 'analytics') !== false || strpos($opt['option_name'], 'pixel') !== false || strpos($opt['option_name'], 'track') !== false || strpos($opt['option_name'], 'settings') !== false) {
        echo "Option: " . $opt['option_name'] . " => " . substr($opt['option_value'], 0, 150) . PHP_EOL;
    }
}
