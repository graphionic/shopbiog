<?php
/**
 * Admin Modules Bootstrapper for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-database-maintenance.php';

// Initialize Admin Maintenance sub-module
ShopBiOG_Database_Maintenance::get_instance();
