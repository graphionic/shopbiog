<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../wp-load.php';

$wpcode_plugin = 'insert-headers-and-footers/ihaf.php';
$active_plugins = get_option('active_plugins', array());

if (in_array($wpcode_plugin, $active_plugins)) {
    deactivate_plugins($wpcode_plugin);
    echo "WPCode Lite ($wpcode_plugin) has been DEACTIVATED." . PHP_EOL;
} else {
    echo "WPCode Lite ($wpcode_plugin) is already inactive." . PHP_EOL;
}

$updated_active = get_option('active_plugins', array());
echo "Remaining Active Plugins Count: " . count($updated_active) . PHP_EOL;
foreach ($updated_active as $p) {
    echo " - " . $p . PHP_EOL;
}
