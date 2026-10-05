
<?php
defined('ABSPATH') || exit;

// if ($in_mobile) return;

?>

<div class="nasa-menus-wrapper nasa-menus-wrapper-reponsive nasa-loading <?php esc_attr_e($ex_class) ?>">
    <?php

    $mega = class_exists('Nasa_Nav_Menu');
    $menu_id = $data['menu-display'];
    $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
    $depth = apply_filters('nasa_max_depth_main_menu', 3);

    if (wp_get_nav_menu_object($menu_id)) {
        $nasa_main_menu = wp_nav_menu(array(
            'echo' => false,
            'menu' => $menu_id,
            'container' => false,
            'items_wrap' => '%3$s',
            'depth' => (int) $depth,
            'walker' => $walker
        ));
    } elseif (has_nav_menu('primary')) {
        $nasa_main_menu = wp_nav_menu(array(
            'echo' => false,
            'theme_location' => 'primary',
            'container' => false,
            'items_wrap' => '%3$s',
            'depth' => (int) $depth,
            'walker' => $walker
        ));
    } else { 
        $allowed_html = array(
            'li' => array(),
            'b' => array()
        );

        $nasa_main_menu = wp_kses(__('<li>Please Define main menu in <b>Header Builder > Header Main Menu </b> <b>Apperance > Menus</b></li>', 'nasa-core'), $allowed_html);
    }

    $class = 'header-nav nasa-to-menu-mobile nasa-main-menu';
    $class .= $mega ? '' : ' nasa-wp-simple-nav-menu';

    $result = '';

    $result .= '<div class="nav-wrapper main-menu-warpper">';
    $result .= '<ul id="site-navigation" class="' . $class . '">';
    $result .= $nasa_main_menu;
    $result .= '</ul>';
    $result .= '</div><!-- nav-wrapper -->';

    echo $result;
    ?>
</div>