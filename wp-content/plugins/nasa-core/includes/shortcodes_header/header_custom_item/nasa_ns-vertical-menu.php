<?php
defined('ABSPATH') || exit;

$menu = isset($data['vertical_menu_selected']) ? $data['vertical_menu_selected'] : false;
$vfmenu_enable = isset($data['vertical_menu_float']) ? $data['vertical_menu_float'] : false;

if (!$menu) {
    $locations = get_theme_mod('nav_menu_locations');
    $menu = isset($locations['vetical-menu']) && $locations['vetical-menu'] ? $locations['vetical-menu'] : null;
}

if ($menu && $menu != '-1') {
    $vroot = isset($data['v_root']) && $data['v_root'] ? true : false;
    $show = isset($data['v_menu_visible']) && $data['v_menu_visible'] ? $data['v_menu_visible'] : false;
    $nasa_wrap = 'vertical-menu nasa-vertical-header';
    $nasa_class = $show ? ' nasa-allways-show nasa-active' : '';
    $nasa_wrap .= $show ? ' nasa-allways-show-warp' : '';
    $nasa_wrap .= NASA_RTL ? ' nasa-menu-ver-align-right' : ' nasa-menu-ver-align-left';
    $nasa_wrap .= isset($data['order_mobile_menus']) && $data['order_mobile_menus'] == 'v-focus' ? ' nasa-focus-menu' : '';

    $wrap_class = 'nasa-menu-vertical-header';
    $wrap_class .= $vroot ? ' vitems-root' : '';
    $wrap_class .= $vfmenu_enable ? ' vertical_float_menu_toggle' : '';
    //vertical_float_menu_toggle

    $browse_heading = '<h5 class="section-title nasa-title-vertical-menu nasa-flex">' .
        '<svg class="ns-v-icon" fill="currentColor" width="15" height="15" viewBox="0 0 512 512"><path d="M43 469c-23 0-43-19-43-42 0-24 20-44 43-44 24 0 42 20 42 44 0 23-18 42-42 42z m0-171c-23 0-43-19-43-42 0-23 20-43 43-43 24 0 42 20 42 43 0 23-18 42-42 42z m0-169c-23 0-43-20-43-44 0-23 20-42 43-42 24 0 42 19 42 42 0 24-18 44-42 44z m100 312l0-28 369 0 0 28z m0-199l369 0 0 28-369 0z m0-171l369 0 0 28-369 0z"/></svg>' .
        '<span>' . esc_html__('Browse Categories', 'elessi-theme') . '</span>' .
        '</h5>';
    $browse_heading = apply_filters('nasa_vertical_menu_heading', $browse_heading);

    $attributes = '';
    if ($vroot) {
        $limit = isset($data['v_root_limit']) && (int) $data['v_root_limit'] ? (int) $data['v_root_limit'] : 0;
        $attributes .= $limit ? ' data-limit="' . $limit . '" data-more="' . esc_attr__('Show more ...', 'elessi-theme') . '"' : '';
    }

    if ($vfmenu_enable) {
        add_action('nasa_static_content', array($this, 'nasa_get_vertical_menu_float'), 9);
        $GLOBALS['vfmenu_custom'] = $menu;
    }
?>
    <div id="nasa-menu-vertical-header" class="<?php echo esc_attr($wrap_class . ' ' . $ex_class); ?>" <?php echo $attributes; ?>>
        <div class="<?php echo esc_attr($nasa_wrap); ?>">

            <?php echo apply_filters('nasa_browse_categories_heading', $browse_heading); ?>
            <?php if (!$vfmenu_enable) :
                $mega = class_exists('Nasa_Nav_Menu');
                $class = $mega ? '' : ' nasa-wp-simple-nav-menu';
                $walker = $mega ? new Nasa_Nav_Menu() : new Walker_Nav_Menu();
                $depth = $vroot ? 1 : apply_filters('nasa_max_depth_vertical_menu', 3);
            ?>
                <div class="vertical-menu-container<?php echo esc_attr($nasa_class); ?>">
                    <ul class="vertical-menu-wrapper<?php echo esc_attr($class); ?>">
                        <?php
                        wp_nav_menu(array(
                            'menu' => $menu,
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'depth' => (int) $depth,
                            'walker' => $walker
                        ));
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}




?>