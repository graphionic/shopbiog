<?php
/**
 * Elessi Child Theme Functions & Bootstrap
 *
 * @package Elessi_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define Child Theme Constants.
define('ELESSI_CHILD_DIR', get_stylesheet_directory());
define('ELESSI_CHILD_URI', get_stylesheet_directory_uri());

// Load modular child theme components from /inc/
require_once ELESSI_CHILD_DIR . '/inc/setup.php';
require_once ELESSI_CHILD_DIR . '/inc/assets.php';