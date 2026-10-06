<?php
/**
 * Child Theme Asset Loading Strategy
 *
 * @package Elessi_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles & scripts.
 */
function elessi_child_enqueue_assets() {
    $prefix = function_exists('elessi_prefix_theme') ? elessi_prefix_theme() : 'elessi';
    $theme_version = wp_get_theme()->get('Version');

    // Parent theme main stylesheet.
    wp_enqueue_style($prefix . '-style', get_template_directory_uri() . '/style.css', array(), $theme_version);

    // Child theme main stylesheet.
    wp_enqueue_style($prefix . '-child-style', get_stylesheet_uri(), array($prefix . '-style'), $theme_version);

    // Helper closure to obtain file version based on filemtime for cache busting.
    $get_asset_version = function($relative_path) {
        $file_path = get_stylesheet_directory() . '/' . ltrim($relative_path, '/');
        return file_exists($file_path) ? filemtime($file_path) : SHOPBIOG_CORE_VERSION;
    };

    // Registered child theme modular CSS files (loaded when populated).
    $css_assets = array(
        'shopbiog-child-base'        => '/assets/css/base.css',
        'shopbiog-child-components'  => '/assets/css/components.css',
        'shopbiog-child-woocommerce' => '/assets/css/woocommerce.css',
        'shopbiog-child-responsive'  => '/assets/css/responsive.css',
    );

    foreach ($css_assets as $handle => $rel_path) {
        $full_path = get_stylesheet_directory() . $rel_path;
        if (file_exists($full_path) && filesize($full_path) > 0) {
            wp_enqueue_style($handle, get_stylesheet_directory_uri() . $rel_path, array($prefix . '-child-style'), $get_asset_version($rel_path));
        }
    }

    // Child theme JS script.
    $js_path = '/assets/js/theme.js';
    $full_js_path = get_stylesheet_directory() . $js_path;
    if (file_exists($full_js_path) && filesize($full_js_path) > 0) {
        wp_enqueue_script('shopbiog-child-js', get_stylesheet_directory_uri() . $js_path, array('jquery'), $get_asset_version($js_path), true);
    }
}
add_action('wp_enqueue_scripts', 'elessi_child_enqueue_assets', 998);
