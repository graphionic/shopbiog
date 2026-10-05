<?php
/**
 * 
 * @param type $atts
 * @param string $content
 * @return string
 */
function nasa_sc_buttons($atts = array(), $content = null) {
    extract(shortcode_atts(array(
        'text' => '',
        'style' => '',
        'color' => '',
        'size' => '',
        'link' => '',
        'target' => ''
    ), $atts));

    $target = $target ? ' target="' . esc_attr($target) . '"' : '';
    $color = $color ? ' style="background-color: ' . esc_attr($color) . ' !important"' : '';
    $content = '<a href="' . ($link != '' ? esc_url($link) : 'javascript:void(0);') . '" class="button ' . esc_attr($size) . ' ' . esc_attr($style) . '"' . $color . $target . ' rel="nofollow">' . esc_html($text) . '</a>';
    
    return $content;
}
