<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * @link https://wordpress.org/plugins/ginger/
 */
function isGingerPluginActivated() {
    
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    
    return is_plugin_active( 'ginger/ginger-eu-cookie-law.php' );
    
}

/**
 * @link https://wordpress.org/plugins/cookiebot/
 * @link https://www.cookiebot.com/en/developer/
 */
function isCookiebotPluginActivated() {
    
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    
    return is_plugin_active( 'cookiebot/cookiebot.php' );
    
}

/**
 * @link https://wordpress.org/plugins/cookie-notice/
 */
function isCookieNoticePluginActivated() {
    
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    
    return is_plugin_active( 'cookie-notice/cookie-notice.php' );
    
}

/**
 * GDPR Cookie Consent
 *
 * @link https://wordpress.org/plugins/cookie-law-info/
 */
function isCookieLawInfoPluginActivated() {
    
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    
    return is_plugin_active( 'cookie-law-info/cookie-law-info.php' )
           || is_plugin_active( 'webtoffee-cookie-consent/webtoffee-cookie-consent.php' );
    
}

/**
 * ConsentMagic
 */
function isConsentMagicPluginActivated() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return (is_plugin_active( 'consent-magic-pro/consent-magic-pro.php' ) || is_plugin_active( 'consent-magic/consent-magic.php' )) ;

}
function isConsentMagicPluginInstalled() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $installed_plugins = get_plugins();
    $plugin_slug = 'consent-magic/consent-magic.php';
    $plugin_slug_pro = "consent-magic-pro/consent-magic-pro.php";

    return
        array_key_exists( $plugin_slug, $installed_plugins ) ||
        in_array( $plugin_slug, $installed_plugins, true ) ||
        array_key_exists( $plugin_slug_pro, $installed_plugins ) ||
        in_array( $plugin_slug_pro, $installed_plugins, true );

}

function isConsentMagicPluginLicenceActivated() {
    $id = get_option('cs_product_id');
    if($id && get_option('wc_am_client_'.$id.'_activated') == 'Activated' || is_plugin_active( 'consent-magic/consent-magic.php')) {
        return true;
    }
    return false;
}

/**
 * GDPR Real Cookie Banner
 *
 * @link https://wordpress.org/plugins/real-cookie-banner/
 */
function isRealCookieBannerPluginActivated() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'real-cookie-banner-pro/index.php' )
        || is_plugin_active( 'real-cookie-banner/index.php' ) ;

}

function adminGdprAjaxNotEnabledNotice() {
    $user_id = get_current_user_id();
    $url = buildAdminUrl( 'pixelyoursite', 'gdpr', false, array(
        '_wpnonce' => wp_create_nonce( 'pys_enable_gdpr_ajax' ),
        'pys' => array(
            'enable_gdpr_ajax' => true,
        ),
    ) );
    $meta_key = 'pys_core_gdpr_ajax_notice_dismissed_at';
    $dismissed_at = get_user_meta( $user_id, $meta_key );
    if(!$dismissed_at){
        ?>

        <div class="notice notice-error is-dismissible pys_core_gdpr_ajax_notice pys-notice">
            <p>You use the <strong>GDPR Cookie Consent</strong> and <strong>PixelYourSite PRO</strong> plugins. You
                must turn on "Enable AJAX filter values update" option to avoid problems with cache plugins.
                <a href="<?php echo esc_url( $url ); ?>" class="notice-link"><strong>CLICK HERE TO
                        ENABLE</strong></a>.</p>
        </div>
        <script type="application/javascript">
            jQuery(document).on('click', '.pys_core_gdpr_ajax_notice .notice-dismiss', function () {

                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'pys_notice_dismiss',
                        nonce: '<?php echo esc_attr( wp_create_nonce( 'pys_notice_dismiss' ) ); ?>',
                        user_id: '<?php echo esc_attr( $user_id ); ?>',
                        addon_slug: 'core',
                        meta_key: 'gdpr_ajax_notice'
                    }
                })

            })
        </script>
        <?php
    }
}

function adminGdprAjaxEnabledNotice() {
    ?>

    <div class="notice notice-success">
        <p>All good :)</p>
    </div>

    <?php
}

/**
 * Resolve Google Consent Mode params for purchase/subscription/license events.
 *
 * Priority for each parameter:
 *   1. Order/payment meta (_cm_analytics_storage, _cm_ad_storage, _cm_ad_user_data, _cm_ad_personalization)
 *   2. $_REQUEST (cm_analytics_storage, cm_ad_storage, cm_ad_user_data, cm_ad_personalization)
 *   3. 'granted' fallback when the google_consent_mode option is enabled
 *
 * The SingleEvent payload is used to derive the order id:
 *   - woo_order -> WooCommerce order meta
 *   - edd_order -> EDD payment meta
 *
 * @param SingleEvent|null $event
 * @return array
 */
function getConsentModeParams( $event = null ) {
    $params = array();
    $google_consent_mode = PYS()->getOption( 'google_consent_mode' );

    $keys = array(
        'analytics_storage'  => 'cm_analytics_storage',
        'ad_storage'         => 'cm_ad_storage',
        'ad_user_data'       => 'cm_ad_user_data',
        'ad_personalization' => 'cm_ad_personalization',
    );

    $meta_values = array();

    if ( $event instanceof SingleEvent ) {
        $woo_order_id = $event->getPayloadValue( 'woo_order' );
        $edd_order_id = $event->getPayloadValue( 'edd_order' );

        if ( ! empty( $woo_order_id ) && function_exists( 'wc_get_order' ) ) {
            $order = wc_get_order( $woo_order_id );
            if ( $order ) {
                foreach ( $keys as $param_key => $request_key ) {
                    $val = $order->get_meta( '_' . $request_key );
                    if ( ! empty( $val ) ) {
                        $meta_values[ $param_key ] = $val;
                    }
                }
            }
        } elseif ( ! empty( $edd_order_id ) && function_exists( 'edd_get_payment_meta' ) ) {
            foreach ( $keys as $param_key => $request_key ) {
                $val = edd_get_payment_meta( $edd_order_id, '_' . $request_key, true );
                if ( ! empty( $val ) ) {
                    $meta_values[ $param_key ] = $val;
                }
            }
        }
    }

    foreach ( $keys as $param_key => $request_key ) {
        if ( ! empty( $meta_values[ $param_key ] ) ) {
            $params[ $param_key ] = $meta_values[ $param_key ];
        } elseif ( isset( $_REQUEST[ $request_key ] ) ) {
            $params[ $param_key ] = sanitize_text_field( $_REQUEST[ $request_key ] );
        } elseif ( $google_consent_mode ) {
            $params[ $param_key ] = 'granted';
        }
    }

    return $params;
}