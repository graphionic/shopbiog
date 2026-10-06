<?php
/**
 * Child Theme Setup and Filters
 *
 * @package Elessi_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom main menu depth configuration.
 *
 * @param int $depth Default menu depth.
 * @return int Modified menu depth.
 */
function custom_max_depth_menu($depth) {
    return 5; // Return max depth menu - Default is 3.
}
add_filter('nasa_max_depth_main_menu', 'custom_max_depth_menu');

/**
 * Child theme setup hooks.
 */
function elessi_child_setup() {
    // Theme support additions if needed in future phases.
}
add_action('after_setup_theme', 'elessi_child_setup');
