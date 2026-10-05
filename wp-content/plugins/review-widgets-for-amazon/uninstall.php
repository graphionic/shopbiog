<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
die;
}
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
$trustindex_pm_amazon = new TrustindexPlugin_amazon("amazon", __FILE__, "14.1.1", "Widgets for Amazon Reviews", "Amazon");
$trustindex_pm_amazon->uninstall();
?>