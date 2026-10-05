<?php
defined('ABSPATH') or die();

$meta_data = get_post_meta($post->ID, 'nasa_header_custom_editor_data', true);

if (empty($meta_data) || $meta_data === '') {
    $meta_data = array(
        'ns-header-topbar-left' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-topbar-middle' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-topbar-right' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-main-left' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-main-middle' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-main-right' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-bottom-left' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-bottom-middle' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-bottom-right' => array(
            'items' => '',
            'settings' => array()
        ),
        'ns-header-topbar' => array(
            'settings' => array()
        ),
        'ns-header-main' => array(
            'settings' => array()
        ),
        'ns-header-bottom' => array(
            'settings' => array()
        ),
    );
}

$edit_wrap = '<a href="javascript:void(0);" class="ns-edit-header-wrap" data-name="%s" data-id-dialog="%s">
                <svg viewBox="0 0 24 24" fill="none" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_iconCarrier">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"></circle>
                        <path d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="currentColor" stroke-width="1.5"></path>
                    </g>
                </svg>
            </a>';

$section_wrap = '<div class="ns-section-header-actions">
                    <a href="javascript:void(0);" class="ns-add-item-header">
                        <svg class="ns-df-cart-svg" width="30" height="30" stroke-width="2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 6V18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M6 12H18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>

                    <a href="javascript:void(0);" class="ns-edit-section-header" data-name="%s" data-id-dialog="%s">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_iconCarrier">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"></circle>
                        <path d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="currentColor" stroke-width="1.5"></path>
                    </g>
                </svg>
                    </a>
                </div>';

$dragable_tmp = '<div class="ns-draggable ns-item-header" data-json="{{data-json}}" id="{{id}}">{{name}} 
<div class="ns-item-header-actions">
<a href="javascript:void(0);" class="edit-item-header" data-id-dialog="{{data-id-dialog}}">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_iconCarrier">
            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"></circle>
            <path d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="currentColor" stroke-width="1.5"></path>
        </g>
    </svg>
</a>
<a href="javascript:void(0);" class="remove-item-header" data-confirm="' . __('Are you sure you want to delete this item?', 'nasa-core') . '">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_iconCarrier">
            <path d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M14 10V17M10 10V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
    </svg>
</a>
</div>
    </div>';

echo ('<script type="text/template" id="tmpl-nasa-item-header">' . $dragable_tmp . '</script>');
?>

<input id="nasa_header_custom_editor_data" type="hidden" name="nasa_header_custom_editor_data" value="<?php echo esc_attr(json_encode($meta_data)); ?>" />

<div class="nasa-header-editor loading" data-ask-close="<?php esc_html_e('You have unsaved changes. Are you sure you want to close?', 'nasa-core') ?>">
    <div class="spinner"></div>
    <div class="nasa-header-topbar-editor-wrap nasa-header-editor-wrap" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-topbar']['settings'])); ?>">
        <h2 class="wrap-title nasa-flex">
            <?php
            esc_html_e('Top bar', 'nasa-core');
            echo sprintf(
                $edit_wrap,
                esc_html__('Top bar wrap', 'nasa-core'),
                isset($meta_data['ns-header-topbar']['settings']['id']) ? $meta_data['ns-header-topbar']['settings']['id'] : ''
            );
            ?>
        </h2>
        <div class="nasa-header-topbar nasa-header-section">
            <div class="ns-droppable" data-pos="ns-header-topbar-left" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-topbar-left']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Top bar left wrap', 'nasa-core'),
                    isset($meta_data['ns-header-topbar-left']['settings']['id']) ? $meta_data['ns-header-topbar-left']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-topbar-left']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-topbar-middle" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-topbar-middle']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Top bar middle wrap', 'nasa-core'),
                    isset($meta_data['ns-header-topbar-middle']['settings']['id']) ? $meta_data['ns-header-topbar-middle']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-topbar-middle']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-topbar-right" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-topbar-right']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Top bar right wrap', 'nasa-core'),
                    isset($meta_data['ns-header-topbar-right']['settings']['id']) ? $meta_data['ns-header-topbar-right']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-topbar-right']['items'], $dragable_tmp);
                ?>
            </div>
        </div>
    </div>

    <div class="nasa-header-main-editor-wrap nasa-header-editor-wrap" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-main']['settings'])); ?>">
        <h2 class="wrap-title nasa-flex">
            <?php
            esc_html_e('Main header', 'nasa-core');
            echo sprintf(
                $edit_wrap,
                esc_html__('Main header wrap', 'nasa-core'),
                isset($meta_data['ns-header-main']['settings']['id']) ? $meta_data['ns-header-main']['settings']['id'] : ''
            );
            ?>
        </h2>
        <div class="nasa-header-main nasa-header-section">
            <div class="ns-droppable" data-pos="ns-header-main-left" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-main-left']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Main header left wrap', 'nasa-core'),
                    isset($meta_data['ns-header-main-left']['settings']['id']) ? $meta_data['ns-header-main-left']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-main-left']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-main-middle" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-main-middle']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Main header middle wrap', 'nasa-core'),
                    isset($meta_data['ns-header-main-middle']['settings']['id']) ? $meta_data['ns-header-main-middle']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-main-middle']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-main-right" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-main-right']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Main header right wrap', 'nasa-core'),
                    isset($meta_data['ns-header-main-right']['settings']['id']) ? $meta_data['ns-header-main-right']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-main-right']['items'], $dragable_tmp);
                ?>
            </div>
        </div>
    </div>

    <div class="nasa-header-bottom-editor-wrap nasa-header-editor-wrap" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-bottom']['settings'])); ?>">
        <h2 class="wrap-title nasa-flex">
            <?php
            esc_html_e('Header bottom', 'nasa-core');
            echo sprintf(
                $edit_wrap,
                esc_html__('Header bottom wrap', 'nasa-core'),
                isset($meta_data['ns-header-bottom']['settings']['id']) ? $meta_data['ns-header-bottom']['settings']['id'] : ''
            );
            ?>
        </h2>
        <div class="nasa-header-bottom nasa-header-section">
            <div class="ns-droppable" data-pos="ns-header-bottom-left" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-bottom-left']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Header bottom left wrap', 'nasa-core'),
                    isset($meta_data['ns-header-bottom-left']['settings']['id']) ? $meta_data['ns-header-bottom-left']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-bottom-left']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-bottom-middle" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-bottom-middle']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Header bottom middle wrap', 'nasa-core'),
                    isset($meta_data['ns-header-bottom-middle']['settings']['id']) ? $meta_data['ns-header-bottom-middle']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-bottom-middle']['items'], $dragable_tmp);
                ?>
            </div>
            <div class="ns-droppable" data-pos="ns-header-bottom-right" data-json="<?php echo esc_attr(json_encode($meta_data['ns-header-bottom-right']['settings'])); ?>">
                <?php
                echo sprintf(
                    $section_wrap,
                    esc_html__('Header bottom right wrap', 'nasa-core'),
                    isset($meta_data['ns-header-bottom-right']['settings']['id']) ? $meta_data['ns-header-bottom-right']['settings']['id'] : ''
                );
                $this->nasa_header_custom_get_data($meta_data['ns-header-bottom-right']['items'], $dragable_tmp);
                ?>
            </div>
        </div>
    </div>
</div>