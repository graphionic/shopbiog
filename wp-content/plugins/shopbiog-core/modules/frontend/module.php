<?php
/**
 * Frontend Module Bootstrapper for ShopBiOG Core
 *
 * @package ShopBiOG\Core\Modules\Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/class-last-modified.php';

// Boot Last Modified Sub-module.
ShopBiOG_Last_Modified::get_instance();
