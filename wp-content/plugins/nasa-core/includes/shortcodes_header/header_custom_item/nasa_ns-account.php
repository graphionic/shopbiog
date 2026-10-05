<?php

defined('ABSPATH') || exit;

$title_acc = !NASA_CORE_USER_LOGGED ? esc_attr__('Login / Register', 'nasa-core') : esc_attr__('My Account', 'nasa-core');

$login_ajax = !NASA_CORE_USER_LOGGED && (!isset($nasa_opt['login_ajax']) || $nasa_opt['login_ajax'] == 1) ? '1' : '0';

$links = false;
$submenu = '';

if (NASA_WOO_ACTIVED) {
    $myaccount_page_id = get_option('woocommerce_myaccount_page_id');

    if ($myaccount_page_id) {
        $links = get_permalink($myaccount_page_id);
    }
} else {
    $links = !NASA_CORE_USER_LOGGED ? wp_login_url() : admin_url('profile.php');
}

$links ? $links : home_url('/');

if (NASA_CORE_USER_LOGGED) {
    $submenu = '<ul class="sub-menu hide-for-mobile">';

    /**
     * Hello Account
     */
    $current_user = wp_get_current_user();
    $submenu .= '<li class="nasa-subitem-acc nasa-hello-acc"><a href="' . esc_url($links) . '">' . sprintf(esc_html__('Hello, %s!', 'nasa-core'), $current_user->display_name) . '</a></li>';

    $menu_items = NASA_WOO_ACTIVED ? wc_get_account_menu_items() : false;
    if ($menu_items) {
        foreach ($menu_items as $endpoint => $label) {
            $submenu .= '<li class="nasa-subitem-acc ' . wc_get_account_menu_item_classes($endpoint) . '"><a href="' . esc_url(wc_get_account_endpoint_url($endpoint)) . '">';

            /**
             * Logout - menu
             */
            $submenu .= $endpoint == 'customer-logout' ? '<svg width="20" height="28" viewBox="0 0 32 32" fill="currentColor"><path d="M14.389 7.956v4.374l1.056 0.010c7.335 0.071 11.466 3.333 12.543 9.944-4.029-4.661-8.675-4.663-12.532-4.664h-1.067v4.337l-9.884-7.001 9.884-7zM15.456 5.893l-12.795 9.063 12.795 9.063v-5.332c5.121 0.002 9.869 0.26 13.884 7.42 0-4.547-0.751-14.706-13.884-14.833v-5.381z" /></svg>&nbsp;' : '';

            $submenu .= esc_html($label);

            $submenu .= '</a></li>';
        }
    }

    $submenu .= '</ul>';
}


$icon = apply_filters('nasa_mini_icon_account', '<svg width="24" height="24" viewBox="0 0 32 32" fill="currentColor"><path d="M16 3.205c-7.067 0-12.795 5.728-12.795 12.795s5.728 12.795 12.795 12.795 12.795-5.728 12.795-12.795c0-7.067-5.728-12.795-12.795-12.795zM16 4.271c6.467 0 11.729 5.261 11.729 11.729 0 2.845-1.019 5.457-2.711 7.49-1.169-0.488-3.93-1.446-5.638-1.951-0.146-0.046-0.169-0.053-0.169-0.66 0-0.501 0.206-1.005 0.407-1.432 0.218-0.464 0.476-1.244 0.569-1.944 0.259-0.301 0.612-0.895 0.839-2.026 0.199-0.997 0.106-1.36-0.026-1.7-0.014-0.036-0.028-0.071-0.039-0.107-0.050-0.234 0.019-1.448 0.189-2.391 0.118-0.647-0.030-2.022-0.921-3.159-0.562-0.719-1.638-1.601-3.603-1.724l-1.078 0.001c-1.932 0.122-3.008 1.004-3.57 1.723-0.89 1.137-1.038 2.513-0.92 3.159 0.172 0.943 0.239 2.157 0.191 2.387-0.010 0.040-0.025 0.075-0.040 0.111-0.131 0.341-0.225 0.703-0.025 1.7 0.226 1.131 0.579 1.725 0.839 2.026 0.092 0.7 0.35 1.48 0.569 1.944 0.159 0.339 0.234 0.801 0.234 1.454 0 0.607-0.023 0.614-0.159 0.657-1.767 0.522-4.579 1.538-5.628 1.997-1.725-2.042-2.768-4.679-2.768-7.555 0-6.467 5.261-11.729 11.729-11.729zM7.811 24.386c1.201-0.49 3.594-1.344 5.167-1.808 0.914-0.288 0.914-1.058 0.914-1.677 0-0.513-0.035-1.269-0.335-1.908-0.206-0.438-0.442-1.189-0.494-1.776-0.011-0.137-0.076-0.265-0.18-0.355-0.151-0.132-0.458-0.616-0.654-1.593-0.155-0.773-0.089-0.942-0.026-1.106 0.027-0.070 0.053-0.139 0.074-0.216 0.128-0.468-0.015-2.005-0.17-2.858-0.068-0.371 0.018-1.424 0.711-2.311 0.622-0.795 1.563-1.238 2.764-1.315l1.011-0.001c1.233 0.078 2.174 0.521 2.797 1.316 0.694 0.887 0.778 1.94 0.71 2.312-0.154 0.852-0.298 2.39-0.17 2.857 0.022 0.078 0.047 0.147 0.074 0.217 0.064 0.163 0.129 0.333-0.025 1.106-0.196 0.977-0.504 1.461-0.655 1.593-0.103 0.091-0.168 0.218-0.18 0.355-0.051 0.588-0.286 1.338-0.492 1.776-0.236 0.502-0.508 1.171-0.508 1.886 0 0.619 0 1.389 0.924 1.68 1.505 0.445 3.91 1.271 5.18 1.77-2.121 2.1-5.035 3.4-8.248 3.4-3.183 0-6.073-1.277-8.188-3.342z"/></svg>');

$icon = isset($data['svg_account']) && $data['svg_account'] != '' ? $data['svg_account'] : $icon;
$ex_class .= ' svg_size_mode_' . (isset($data['svg_size_mode']) && $data['svg_size_mode'] != '' ? $data['svg_size_mode'] : 'default');
$display_text = isset($data['display_text']) && !$data['display_text'] ? $data['display_text'] : true;


?>

<div class="nasa_ns-account <?php esc_attr_e($ex_class) ?>">
    <a class="nasa-login-register-ajax nasa-flex" data-enable="<?php echo esc_attr($login_ajax) ?>" href="<?php echo esc_url($links) ?>" title="<?php echo esc_attr($title_acc) ?>">
        <?php echo $icon ?>

        <?php if ($display_text): ?>
            <span class="icon-text"><?php echo esc_html($title_acc) ?></span>
        <?php endif; ?>
    </a>

    <?php echo $submenu ?>
</div>