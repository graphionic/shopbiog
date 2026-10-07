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
require_once __DIR__ . '/forms/class-forms.php';
require_once __DIR__ . '/testimonials/class-testimonials.php';

// Boot Sub-modules.
ShopBiOG_Last_Modified::get_instance();
ShopBiOG_Forms::get_instance();
ShopBiOG_Testimonials::get_instance();
