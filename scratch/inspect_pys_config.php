<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

$options = [
    'pys_core',
    'pys_facebook',
    'pys_ga',
    'pys_gtm',
    'pys_head_footer',
    'wc_facebook_pixel_id',
    'tt4b_pixel_code'
];

foreach ($options as $opt_name) {
    $val = get_option($opt_name);
    echo "==========================================" . PHP_EOL;
    echo "OPTION: $opt_name" . PHP_EOL;
    if (is_array($val)) {
        foreach ($val as $k => $v) {
            if (is_array($v)) {
                echo "  $k => array(" . count($v) . ")" . PHP_EOL;
                foreach ($v as $subk => $subv) {
                    $s = (string)$subv;
                    if (strlen($s) > 20 && (strpos($k, 'token') !== false || strpos($k, 'key') !== false || strpos($subk, 'token') !== false || strpos($subk, 'secret') !== false || strpos($subk, 'api') !== false)) {
                        $s = substr($s, 0, 4) . '***' . substr($s, -4);
                    }
                    echo "     $subk => $s" . PHP_EOL;
                }
            } else {
                $s = (string)$v;
                if (strlen($s) > 20 && (strpos($k, 'token') !== false || strpos($k, 'key') !== false || strpos($k, 'secret') !== false || strpos($k, 'api') !== false)) {
                    $s = substr($s, 0, 4) . '***' . substr($s, -4);
                }
                echo "  $k => $s" . PHP_EOL;
            }
        }
    } else {
        $s = (string)$val;
        if (strlen($s) > 20 && (strpos($opt_name, 'pixel') !== false || strpos($opt_name, 'token') !== false)) {
            $s = substr($s, 0, 4) . '***' . substr($s, -4);
        }
        echo "  Value: $s" . PHP_EOL;
    }
}
