<?php

namespace PixelYourSite;

use URL;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if EDD Recurring plugin is active.
 * Uses static caching to prevent repeated checks.
 *
 * @return bool
 */
function isEddRecurringActive() {
    static $cache = null;

    if ( $cache !== null ) {
        return $cache;
    }

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $cache = is_plugin_active( 'edd-recurring/edd-recurring.php' );
    return $cache;
}

/**
 * Check if EDD Software Licensing plugin is active.
 * Uses static caching to prevent repeated checks.
 *
 * @return bool
 */
function isEddSoftwareLicensingActive() {
    static $cache = null;

    if ( $cache !== null ) {
        return $cache;
    }

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $cache = is_plugin_active( 'edd-software-licensing/edd-software-licenses.php' );
    return $cache;
}

/**
 * Check if WooCommerce Subscriptions plugin is active.
 * Uses static caching to prevent repeated checks.
 *
 * @return bool
 */
function isWooCommerceSubscriptionsActive() {
    static $cache = null;

    if ( $cache !== null ) {
        return $cache;
    }

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $cache = is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' );
    return $cache;
}

/**
 * Check if WPML plugin installed and activated.
 *
 * @return bool
 */
function isWPMLActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' );
}
/**
 * Check if Pixel Cost of goods plugin installed and activated.
 * Uses static caching to prevent repeated checks.
 *
 * @return bool
 */
function isPixelCogActive() {
    static $cache = null;
    if ( $cache !== null ) {
        return $cache;
    }
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $cache = is_plugin_active( 'pixel-cost-of-goods/pixel-cost-of-goods.php' );
    return $cache;
}

/**
 * Check if WooCommerce native Cost of Goods (COGS) is available.
 * Requires WooCommerce 10.3 or later.
 * Uses static caching to prevent repeated reflection calls.
 *
 * @return bool
 */
function isWcCogAvailable() {
    static $cache = null;
    if ( $cache !== null ) {
        return $cache;
    }
    $cache = method_exists( 'WC_Order', 'get_cogs_total_value' );
    return $cache;
}

// ─────────────────────────────────────────────────────────────────────────────
// Global COG (Cost of Goods) helpers
// All COG logic for events and ConversionProfit goes through these functions.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Check if the WooCommerce native COGS feature flag is enabled by the user.
 * Requires WooCommerce 8.3+ (FeaturesUtil) and COGS enabled in
 * WooCommerce → Settings → Advanced → Features.
 *
 * @return bool
 */
function isWcCogFeatureEnabled() {
    static $cache = null;
    if ( $cache !== null ) {
        return $cache;
    }
    if ( ! class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        $cache = false;
        return $cache;
    }
    $cache = \Automattic\WooCommerce\Utilities\FeaturesUtil::feature_is_enabled( 'cost_of_goods_sold' );
    return $cache;
}

/**
 * Return the active COG source selected in the global setting.
 *
 * @return string 'pixel_cog' | 'wc_cog'
 */
function pys_cog_get_source() {
    return PYS()->getOption( 'woo_cog_source' ) ?: 'pixel_cog';
}

/**
 * Check whether the currently selected COG source is fully available and ready.
 * - 'wc_cog'    → WooCommerce 10.3+ AND feature flag enabled in WC Settings.
 * - 'pixel_cog' → PixelYourSite Cost of Goods plugin active.
 *
 * @return bool
 */
function pys_cog_source_is_available() {
    $source = pys_cog_get_source();
    if ( $source === 'wc_cog' ) {
        return isWcCogAvailable() && isWcCogFeatureEnabled();
    }
    return isPixelCogActive();
}

/**
 * Calculate profit (price − COG) for a single product line.
 * Works for both 'wc_cog' and 'pixel_cog' sources.
 *
 * For 'wc_cog': amount = tax-adjusted price × qty, cog = $product->get_cogs_value() × qty.
 * For 'pixel_cog': delegates to pys_woo_get_cog_product_value() (PYS COG cascade).
 *
 * Returns '' (empty string) when no COG data is found, so callers can fall back to price.
 *
 * @param \WC_Product $product
 * @param int         $quantity
 * @param float|string $price   Unit price (before tax adjustments).
 * @return float|string Profit value, or '' if unavailable.
 */
function pys_cog_get_product_profit( $product, $quantity, $price ) {
    if ( ! pys_cog_source_is_available() ) {
        return '';
    }

    if ( pys_cog_get_source() === 'wc_cog' ) {
        $cogs_value = $product->get_cogs_value();
        if ( $cogs_value === null ) {
            return '';
        }
        $price_args = [ 'qty' => $quantity, 'price' => $price ];
        // For wc_cog: tax inclusion is controlled by our own option, not the PYS COG plugin setting
        if ( PYS()->getOption( 'woo_cog_wc_include_tax' ) ) {
            $amount = wc_get_price_including_tax( $product, $price_args );
        } else {
            $amount = wc_get_price_excluding_tax( $product, $price_args );
        }
        $profit = (float) $amount - ( (float) $cogs_value * (int) $quantity );
        return formatPriceTrimZeros( $profit );
    }

    // pixel_cog: delegate to existing PYS COG cascade function
    return pys_woo_get_cog_product_value( $product, $quantity, $price );
}

/**
 * Calculate total profit for a WooCommerce order.
 * Works for both 'wc_cog' and 'pixel_cog' sources.
 *
 * For 'wc_cog':
 *   profit = subtotal − cogs_total [+ tax] [+ shipping]
 *   tax/shipping inclusion controlled by woo_cog_wc_include_tax / woo_cog_wc_include_shipping.
 *
 * For 'pixel_cog':
 *   Uses pre-calculated '_pixel_cost_of_goods_order_profit' order meta (stored by COG plugin).
 *   Falls back to per-product cascade calculation if meta is absent.
 *
 * Returns 0.0 when profit cannot be determined.
 *
 * @param \WC_Order $order
 * @return float
 */
function pys_cog_get_order_profit( $order ) {
    if ( ! pys_cog_source_is_available() ) {
        return 0.0;
    }

    if ( pys_cog_get_source() === 'wc_cog' ) {
        $cogs_total = (float) $order->get_cogs_total_value();
        $subtotal   = (float) $order->get_subtotal();
        $profit     = $subtotal - $cogs_total;
        if ( PYS()->getOption( 'woo_cog_wc_include_tax' ) ) {
            $profit += (float) $order->get_total_tax();
        }
        if ( PYS()->getOption( 'woo_cog_wc_include_shipping' ) ) {
            $profit += (float) $order->get_shipping_total();
        }
        return max( 0.0, round( $profit, 2 ) );
    }

    // pixel_cog: prefer pre-calculated meta stored by the COG plugin on order save
    $profit = $order->get_meta( '_pixel_cost_of_goods_order_profit', true );
    if ( $profit !== '' && $profit !== false && is_numeric( $profit ) ) {
        return round( (float) $profit, 2 );
    }

    // Fallback: calculate from per-product cascade (used when meta not yet written)
    $items = [];
    foreach ( $order->get_items() as $item ) {
        if ( ! ( $item instanceof \WC_Order_Item_Product ) ) {
            continue;
        }
        $variation_id = $item->get_variation_id();
        $items[]      = [
            'product_id' => $variation_id ?: $item->get_product_id(),
            'quantity'   => $item->get_quantity(),
            'total'      => $item->get_total(),
            'total_tax'  => $item->get_total_tax(),
        ];
    }
    if ( empty( $items ) ) {
        return 0.0;
    }
    $calculated = getAvailableProductCogOrder( [ 'products' => $items ] );
    return is_numeric( $calculated ) ? round( (float) $calculated, 2 ) : 0.0;
}

function isPinterestActive( $checkCompatibility = true ) {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $active = is_plugin_active( 'pixelyoursite-pinterest/pixelyoursite-pinterest.php' );

    if ( $checkCompatibility ) {
        return $active && ! isPinterestVersionIncompatible()
            && function_exists( 'PixelYourSite\Pinterest' )
            && Pinterest() instanceof Plugin; // false for dummy
    } else {
        return $active;
    }

}

function isPinterestVersionIncompatible() {

    if ( ! function_exists( 'get_plugin_data' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $data = get_plugin_data( WP_PLUGIN_DIR . '/pixelyoursite-pinterest/pixelyoursite-pinterest.php', false, false );

    return ! version_compare( $data['Version'], PYS_PINTEREST_MIN_VERSION, '>=' );

}

function isSuperPackActive( $checkCompatibility = true  ) {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $active = is_plugin_active( 'pixelyoursite-super-pack/pixelyoursite-super-pack.php' );

    if ( $checkCompatibility ) {
        return $active && function_exists( 'PixelYourSite\SuperPack' ) && ! isSuperPackVersionIncompatible();
    } else {
        return $active;
    }

}

function isSuperPackVersionIncompatible() {

    if ( ! function_exists( 'get_plugin_data' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $data = get_plugin_data( WP_PLUGIN_DIR . '/pixelyoursite-super-pack/pixelyoursite-super-pack.php', false, false );

    return ! version_compare( $data['Version'], PYS_SUPER_PACK_MIN_VERSION, '>=' );

}

function isBingActive( $checkCompatibility = true ) {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $active = is_plugin_active( 'pixelyoursite-bing/pixelyoursite-bing.php' );

    if ( $checkCompatibility ) {
        return $active && ! isBingVersionIncompatible()
            && function_exists( 'PixelYourSite\Bing' )
            && Bing() instanceof Plugin; // false for dummy
    } else {
        return $active;
    }

}

function isBingVersionIncompatible() {

    if ( ! function_exists( 'get_plugin_data' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $data = get_plugin_data( WP_PLUGIN_DIR . '/pixelyoursite-bing/pixelyoursite-bing.php', false, false );

    return ! version_compare( $data['Version'], PYS_BING_MIN_VERSION, '>=' );

}

/**
 * Check if Reddit plugin installed and activated.
 * @param bool $checkCompatibility
 * @return bool
 */
function isRedditActive( bool $checkCompatibility = true ): bool {

	if ( !function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$active = is_plugin_active( 'pixelyoursite-reddit/pixelyoursite-reddit.php' );

	if ( $checkCompatibility ) {
		return $active && !isRedditVersionIncompatible()
		       && function_exists( 'PixelYourSite\Reddit' )
		       && Reddit() instanceof Plugin; // false for dummy
	} else {
		return $active;
	}
}

/**
 * Check if Reddit plugin version is compatible.
 * @return bool
 */
function isRedditVersionIncompatible(): bool {

	if ( !function_exists( 'get_plugin_data' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$data = get_plugin_data( WP_PLUGIN_DIR . '/pixelyoursite-reddit/pixelyoursite-reddit.php', false, false );

	return !version_compare( $data[ 'Version' ], PYS_REDDIT_MIN_VERSION, '>=' );
}

/**
 * Check if WooCommerce plugin installed and activated.
 *
 * @return bool
 */
function isWooCommerceActive() {
    return class_exists( 'woocommerce' );
}

/**
 * Check if Easy Digital Downloads plugin installed and activated.
 *
 * @return bool
 */
function isEddActive() {
    return function_exists( 'EDD' );
}

/**
 * Check if Product Catalog Feed Pro plugin installed and activated.
 *
 * @return bool
 */
function isProductCatalogFeedProActive() {
    return class_exists( 'wpwoof_product_catalog' );
}

/**
 * Check if EDD Products Feed Pro plugin installed and activated.
 *
 * @return bool
 */
function isEddProductsFeedProActive() {
    return class_exists( 'Wpeddpcf_Product_Catalog' );
}

function isBoostActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'boost/boost.php' );

}

/**
 * Check if Smart OpenGraph plugin installed and activated.
 *
 * @return bool
 */
function isSmartOpenGraphActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'smart-opengraph/catalog-plugin.php' );

}

function isVisualComposerActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'js_composer/js_composer.php' );

}

function isMagicRowActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'magic-row/magic-row.php' );

}


function isContactForm7Active() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'contact-form-7/wp-contact-form-7.php' );

}
function isWPFormsActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'wpforms-lite/wpforms.php' );

}
function isForminatorActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'forminator/forminator.php' );

}
function isFluentFormActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'fluentform/fluentform.php' );

}
function isFormidableActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'formidable/formidable.php' );

}

function isWSFormsActive() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}

	return is_plugin_active( 'ws-form-pro/ws-form.php' ) || is_plugin_active( 'ws-form/ws-form.php' );
}

function isPhotoCartActive() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    return is_plugin_active( 'sunshine-photo-cart/sunshine-photo-cart.php' );
}
/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var
 *
 * @return string|array
 */
function deepSanitizeTextField( $var ) {

    if ( is_array( $var ) ) {
        return array_map( 'deepSanitizeTextField', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }

}

function getAvailableUserRoles() {

    $wp_roles   = new \WP_Roles();
    $user_roles = array();

    foreach ( $wp_roles->get_names() as $slug => $name ) {
        $user_roles[ $slug ] = $name;
    }

    return $user_roles;

}

/**
 * Resolve Cost of Goods for a single product with 4-level cascade fallback:
 *   1. Product meta  →  2. Parent variation meta  →  3. Category meta  →  4. Global option
 *
 * @param \WC_Product $product
 * @return array{type: string, val: string}
 */
function getAvailableProductCog( $product ) {
    $product_id   = $product->get_id();
    $cost_type    = get_post_meta( $product_id, '_pixel_cost_of_goods_cost_type', true );
    $product_cost = get_post_meta( $product_id, '_pixel_cost_of_goods_cost_val', true );

    // Level 2: parent variation
    if ( ! $product_cost && $product->is_type( 'variation' ) ) {
        $parent_id    = $product->get_parent_id();
        $cost_type    = get_post_meta( $parent_id, '_pixel_cost_of_goods_cost_type', true );
        $product_cost = get_post_meta( $parent_id, '_pixel_cost_of_goods_cost_val', true );
    }

    if ( $product_cost ) {
        return [ 'type' => $cost_type, 'val' => $product_cost ];
    }

    // Level 3: category — single query via get_product_cog_by_cat()
    $cat_cog = get_product_cog_by_cat( $product_id );
    if ( $cat_cog['val'] ) {
        return [ 'type' => $cat_cog['type'], 'val' => $cat_cog['val'] ];
    }

    // Level 4: global option
    return [
        'type' => get_option( '_pixel_cost_of_goods_cost_type' ),
        'val'  => get_option( '_pixel_cost_of_goods_cost_val' ),
    ];
}

function getAvailableProductCogOrder( $args ) {
    // ── WooCommerce native COGS path ──────────────────────────────────────────
    if ( pys_cog_get_source() === 'wc_cog' && isWcCogAvailable() && isWcCogFeatureEnabled() ) {
        // For wc_cog: tax inclusion controlled by our own option, not the PYS COG plugin setting
        $wc_include_tax = PYS()->getOption( 'woo_cog_wc_include_tax' );
        $order_total    = 0.0;
        $cost           = 0.0;
        foreach ( $args['products'] as $productData ) {
            $order_total += (float) $productData['total'];
            if ( $wc_include_tax ) {
                $order_total += (float) $productData['total_tax'];
            }
            $productObject = wc_get_product( $productData['product_id'] );
            if ( ! $productObject ) continue;
            $cogs_value = $productObject->get_cogs_value();
            if ( $cogs_value === null ) continue;
            $cost += (float) $cogs_value * (int) $productData['quantity'];
        }
        return $order_total - $cost;
    }

    // ── PYS COG plugin path ───────────────────────────────────────────────────
    // For pixel_cog: tax inclusion controlled by the PYS COG plugin's own setting
    $isWithoutTax = get_option( '_pixel_cog_tax_calculating' ) == 'no';
    $order_total  = 0.0;
    $cost         = 0.0;
    $custom_total = 0.0;
    $cat_isset    = 0;
    $notice       = '';
    // Read global fallback values once before the loop
    $global_cost  = get_option( '_pixel_cost_of_goods_cost_val' );
    $global_type  = get_option( '_pixel_cost_of_goods_cost_type' );

    foreach ( $args['products'] as $productData ) {
        $order_total += (float) $productData['total'];
        if ( ! $isWithoutTax ) {
            $order_total += (float) $productData['total_tax'];
        }

        $product_id    = $productData['product_id'];
        $productObject = wc_get_product( $product_id );
        if ( ! $productObject ) continue;

        $cost_type    = get_post_meta( $productObject->get_id(), '_pixel_cost_of_goods_cost_type', true );
        $product_cost = get_post_meta( $productObject->get_id(), '_pixel_cost_of_goods_cost_val', true );

        if ( ! $product_cost && $productObject->is_type( 'variation' ) ) {
            $cost_type    = get_post_meta( $productObject->get_parent_id(), '_pixel_cost_of_goods_cost_type', true );
            $product_cost = get_post_meta( $productObject->get_parent_id(), '_pixel_cost_of_goods_cost_val', true );
        }

        // Use a distinct variable name to avoid shadowing the outer $args parameter
        $price_args = [ 'qty' => 1, 'price' => $productObject->get_price() ];
        $qlt        = $productData['quantity'];
        $price      = $isWithoutTax
            ? wc_get_price_excluding_tax( $productObject, $price_args )
            : wc_get_price_including_tax( $productObject, $price_args );

        if ( $product_cost ) {
            $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
            $custom_total += $price * $qlt;
        } else {
            // Single query for both val + type via the combined category helper
            $cat_cog      = get_product_cog_by_cat( $product_id );
            $product_cost = $cat_cog['val'];
            $cost_type    = $cat_cog['type'];
            if ( $product_cost ) {
                $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
                $custom_total += $price * $qlt;
                $notice        = 'Category Cost of Goods was used for some products.';
                $cat_isset     = 1;
            } elseif ( $global_cost ) {
                $cost         += ( $global_type == 'percent' ) ? (float) $price * ( (float) $global_cost / 100 ) * $qlt : (float) $global_cost * $qlt;
                $custom_total += $price * $qlt;
                $notice        = $cat_isset ? 'Global and Category Cost of Goods was used for some products.' : 'Global Cost of Goods was used for some products.';
            } else {
                $notice = "Some products don't have Cost of Goods.";
            }
        }
    }

    return $order_total - $cost;
}
function getProductCogOrder( $args ) {
    if ( ! isPixelCogActive() ) {
        return [ 'cost' => 0, 'profit' => 0 ];
    }

    $order_total  = 0.0;
    $cost         = 0.0;
    $custom_total = 0.0;
    $isWithoutTax = get_option( '_pixel_cog_tax_calculating' ) == 'no';
    // Read global fallback values once before the loop
    $global_cost  = get_option( '_pixel_cost_of_goods_cost_val' );
    $global_type  = get_option( '_pixel_cost_of_goods_cost_type' );

    foreach ( $args['products'] as $productData ) {
        $order_total += (float) $productData['total'];
        if ( ! $isWithoutTax ) {
            $order_total += (float) $productData['total_tax'];
        }

        $product_id    = $productData['product_id'];
        $productObject = wc_get_product( $product_id );
        if ( ! $productObject ) continue;

        $cost_type    = get_post_meta( $productObject->get_id(), '_pixel_cost_of_goods_cost_type', true );
        $product_cost = get_post_meta( $productObject->get_id(), '_pixel_cost_of_goods_cost_val', true );

        if ( ! $product_cost && $productObject->is_type( 'variation' ) ) {
            $cost_type    = get_post_meta( $productObject->get_parent_id(), '_pixel_cost_of_goods_cost_type', true );
            $product_cost = get_post_meta( $productObject->get_parent_id(), '_pixel_cost_of_goods_cost_val', true );
        }

        // Use a distinct variable name to avoid shadowing the outer $args parameter
        $price_args = [ 'qty' => 1, 'price' => $productObject->get_price() ];
        $qlt        = $productData['quantity'];
        $price      = $isWithoutTax
            ? wc_get_price_excluding_tax( $productObject, $price_args )
            : wc_get_price_including_tax( $productObject, $price_args );

        if ( $product_cost ) {
            $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
            $custom_total += $price * $qlt;
        } else {
            // Single query for both val + type via the combined category helper
            $cat_cog      = get_product_cog_by_cat( $product_id );
            $product_cost = $cat_cog['val'];
            $cost_type    = $cat_cog['type'];
            if ( $product_cost ) {
                $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
                $custom_total += $price * $qlt;
            } elseif ( $global_cost ) {
                $cost         += ( $global_type == 'percent' ) ? (float) $price * ( (float) $global_cost / 100 ) * $qlt : (float) $global_cost * $qlt;
                $custom_total += $price * $qlt;
            }
        }
    }

    return [ 'cost' => $cost, 'profit' => $custom_total - $cost ];
}

/**
 * Check if every product in the order has a defined Cost of Goods.
 *
 * Uses the global woo_cog_source option (via pys_cog_get_source()):
 * - 'wc_cog':    WooCommerce native COGS (10.3+). Checks $product->get_cogs_value().
 * - 'pixel_cog': PYS COG plugin. Cascades: product → parent → category → global.
 *
 * Returns false if any single product lacks a cost value.
 *
 * @param \WC_Order $order
 * @return bool
 */
function poasAllProductsHaveCost( $order ) {
    $source = pys_cog_get_source();

    // WooCommerce native COGS: null means no cost defined for this product
    if ( $source === 'wc_cog' ) {
        if ( ! isWcCogAvailable() || ! isWcCogFeatureEnabled() ) {
            return false;
        }
        foreach ( $order->get_items() as $item ) {
            if ( ! ( $item instanceof \WC_Order_Item_Product ) ) {
                continue;
            }
            $product = $item->get_product();
            if ( ! $product ) {
                return false;
            }
            if ( $product->get_cogs_value() === null ) {
                return false;
            }
        }
        return true;
    }

    // PYS COG plugin: cascade through product → parent → category → global
    if ( ! isPixelCogActive() ) {
        return false;
    }

    $global_cost = get_option( '_pixel_cost_of_goods_cost_val' );

    foreach ( $order->get_items() as $item ) {
        if ( ! ( $item instanceof \WC_Order_Item_Product ) ) {
            continue;
        }

        $variation_id = $item->get_variation_id();
        $product_id   = $variation_id ? $variation_id : $item->get_product_id();

        // Level 1: product meta
        $product_cost = get_post_meta( $product_id, '_pixel_cost_of_goods_cost_val', true );
        if ( $product_cost !== '' && is_numeric( $product_cost ) ) {
            continue;
        }

        // Level 2: parent product meta (if variation)
        if ( $variation_id ) {
            $parent_id    = $item->get_product_id();
            $product_cost = get_post_meta( $parent_id, '_pixel_cost_of_goods_cost_val', true );
            if ( $product_cost !== '' && is_numeric( $product_cost ) ) {
                continue;
            }
        }

        // Level 3: category cost
        $cat_cost = get_product_cost_by_cat( $product_id );
        if ( $cat_cost !== '' && $cat_cost !== false && is_numeric( $cat_cost ) ) {
            continue;
        }

        // Level 4: global cost
        if ( $global_cost !== '' && $global_cost !== false && is_numeric( $global_cost ) ) {
            continue;
        }

        // No cost found at any level
        return false;
    }

    return true;
}

/**
 * Fire the ProfitConversion event (server-side only) for an order.
 *
 * Sends a server-side ProfitConversion event to Meta CAPI, GA4 Measurement Protocol,
 * and TikTok Events API. The event value equals the order profit.
 *
 * Called from two paths:
 * - Browser path: class-events-woo.php, inside the woo_purchase case (thank-you page load)
 * - APT path:     class-pys.php, woo_completed_purchase() (order status → completed)
 *
 * Has its own fire-once check (_pys_poas_event_fired) independent from the Purchase event.
 *
 * @param int $order_id The WooCommerce order ID.
 */
function firePoasForOrder( $order_id ) {
    $log = PYS()->getLog();

    if ( ! PYS()->getOption( 'woo_poas_enabled' ) ) {
        $log->debug( 'POAS: Skipped — woo_poas_enabled is off.' );
        return;
    }

    // Verify the selected COG provider is available via shared helper
    if ( ! pys_cog_source_is_available() ) {
        $source = pys_cog_get_source();
        if ( $source === 'wc_cog' ) {
            $log->debug( 'POAS: Skipped — WooCommerce COGS not available or feature not enabled.' );
        } else {
            $log->debug( 'POAS: Skipped — Cost of Goods plugin is not active.' );
        }
        return;
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        $log->debug( "POAS: Skipped — order #{$order_id} not found." );
        return;
    }

    // Fire-once: only block repeat sends when "Fire the event only once for each order" is ON
    if ( PYS()->getOption( 'woo_purchase_on_transaction' ) &&
         $order->get_meta( '_pys_poas_event_fired', true ) ) {
        $log->debug( "POAS: Skipped — already fired for order #{$order_id} (fire-once is ON)." );
        return;
    }

    // Abort if any product is missing a Cost of Goods value
    if ( ! poasAllProductsHaveCost( $order ) ) {
        $log->debug( "POAS: Skipped for order #{$order_id} — one or more products missing Cost of Goods." );
        return;
    }

    // Calculate profit via the shared helper (handles both wc_cog and pixel_cog)
    $profit = pys_cog_get_order_profit( $order );

    if ( $profit <= 0.0 ) {
        $log->debug( "POAS: Skipped for order #{$order_id} — profit is zero or negative." );
        return;
    }
    $profit          = round( $profit, 2 );
    $event_id        = EventIdGenerator::guidv4();
    $currency        = $order->get_currency();
    $mapped_order_id = wooMapOrderId( $order_id );

    $log->debug( "POAS: Firing for order #{$order_id}, profit={$profit}, currency={$currency}" );

    // Send ProfitConversion server-side only (never visible in browser console).
    // Each platform uses sendEventsNow() for synchronous delivery.

    // --- Facebook CAPI ---
    if ( Facebook()->configured() && Facebook()->isServerApiEnabled() ) {
        $fb_event = new SingleEvent( 'woo_poas', EventTypes::$STATIC, 'woo' );
        $fb_event->addPayload( array(
            'name'      => 'ProfitConversion',
            'eventID'   => $event_id,
            'woo_order' => $order_id,
        ) );
        $fb_event->addParams( array(
            'value'    => $profit,
            'currency' => $currency,
            'order_id' => $mapped_order_id,
        ) );
        $fb_event->addPayload( array( 'pixelIds' => Facebook()->getAllPixels( false ) ) );

        FacebookServer()->sendEventsNow( array( $fb_event ) );
        $log->debug( "POAS: Sent ProfitConversion to Facebook for order #{$order_id}" );
    }

    // --- GA4 Measurement Protocol ---
    if ( GA()->configured() && ! empty( GA()->getApiTokens() ) ) {
        $ga_event = new SingleEvent( 'woo_poas', EventTypes::$STATIC, 'woo' );
        $ga_event->addPayload( array(
            'name'       => 'ProfitConversion',
            'eventID'    => $event_id,
            'woo_order'  => $order_id,
        ) );

        $params = array(
            'profit'          => $profit,
            'currency'       => $currency,
            'order_id' => $mapped_order_id,
            'value'          => $profit
        );

        $params = array_merge( $params, getConsentModeParams( $ga_event ) );

        $ga_event->addParams( $params );

        $ga_event->addPayload( array( 'trackingIds' => GA()->getAllPixels( false ) ) );

        GaMeasurementProtocolAPI()->sendEventsNow( array( $ga_event ) );
        $log->debug( "POAS: Sent ProfitConversion to GA4 for order #{$order_id}" );
    }

    // --- TikTok Events API ---
    if ( Tiktok()->enabled() && Tiktok()->isServerApiEnabled() ) {
        $tt_event = new SingleEvent( 'woo_poas', EventTypes::$STATIC, 'woo' );
        $tt_event->addPayload( array(
            'name'      => 'ProfitConversion',
            'eventID'   => $event_id,
            'woo_order' => $order_id,
        ) );
        $tt_event->addParams( array(
            'value'    => $profit,
            'currency' => $currency,
            'order_id' => $mapped_order_id,
        ) );
        $tt_event->addPayload( array( 'pixelIds' => Tiktok()->getAllPixels( false ) ) );

        TikTokServer()->sendEventsNow( array( $tt_event ) );
        $log->debug( "POAS: Sent ProfitConversion to TikTok for order #{$order_id}" );
    }

    // Mark as fired
    $order->update_meta_data( '_pys_poas_event_fired', true );
    $order->save();

    $log->debug( "POAS: Completed for order #{$order_id}" );
}

function getAvailableProductCogCart() {
    $shipping        = WC()->cart->get_shipping_total();
    $cart_total_base = WC()->cart->get_total( 'edit' ) - $shipping;

    // ── WooCommerce native COGS path ──────────────────────────────────────────
    if ( pys_cog_get_source() === 'wc_cog' && isWcCogAvailable() && isWcCogFeatureEnabled() ) {
        // For wc_cog: tax inclusion controlled by our own option, not the PYS COG plugin setting
        $wc_include_tax = PYS()->getOption( 'woo_cog_wc_include_tax' );
        $cart_total     = $cart_total_base;
        if ( $wc_include_tax ) {
            $cart_total -= WC()->cart->get_shipping_tax(); // keep product tax, remove shipping tax
        } else {
            $cart_total -= WC()->cart->get_total_tax();    // remove all tax
        }
        $cost = 0.0;
        foreach ( WC()->cart->cart_contents as $item ) {
            $product_id = ( isset( $item['variation_id'] ) && 0 != $item['variation_id'] )
                ? $item['variation_id']
                : $item['product_id'];
            $product = wc_get_product( $product_id );
            if ( ! $product ) continue;
            $cogs_value = $product->get_cogs_value();
            if ( $cogs_value === null ) continue;
            $cost += (float) $cogs_value * (int) $item['quantity'];
        }
        return $cart_total - $cost;
    }

    // ── PYS COG plugin path ───────────────────────────────────────────────────
    // For pixel_cog: tax inclusion controlled by the PYS COG plugin's own setting
    $isWithoutTax = get_option( '_pixel_cog_tax_calculating' ) == 'no';
    $cart_total   = $cart_total_base;
    if ( $isWithoutTax ) {
        $cart_total -= WC()->cart->get_total_tax();
    } else {
        $cart_total -= WC()->cart->get_shipping_tax();
    }

    // Read global fallback values once before the loop
    $global_cost  = get_option( '_pixel_cost_of_goods_cost_val' );
    $global_type  = get_option( '_pixel_cost_of_goods_cost_type' );

    $cost         = 0.0;
    $custom_total = 0.0;
    $cat_isset    = 0;
    $notice       = '';

    foreach ( WC()->cart->cart_contents as $cart_item_key => $item ) {
        $product_id = ( isset( $item['variation_id'] ) && 0 != $item['variation_id'] )
            ? $item['variation_id']
            : $item['product_id'];

        $product = wc_get_product( $product_id );
        if ( ! $product ) continue;

        $cost_type    = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_type', true );
        $product_cost = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_val', true );

        if ( ! $product_cost && $product->is_type( 'variation' ) ) {
            $cost_type    = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_type', true );
            $product_cost = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_val', true );
        }

        $price_args = [ 'qty' => 1, 'price' => $product->get_price() ];
        $price      = $isWithoutTax
            ? wc_get_price_excluding_tax( $product, $price_args )
            : wc_get_price_including_tax( $product, $price_args );
        $qlt        = $item['quantity'];

        if ( $product_cost ) {
            $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
            $custom_total += $price * $qlt;
        } else {
            // Single query for both val + type via the combined category helper
            $cat_cog      = get_product_cog_by_cat( $product_id );
            $product_cost = $cat_cog['val'];
            $cost_type    = $cat_cog['type'];
            if ( $product_cost ) {
                $cost         += ( $cost_type == 'percent' ) ? $price * ( (float) $product_cost / 100 ) * $qlt : (float) $product_cost * $qlt;
                $custom_total += $price * $qlt;
                $notice        = 'Category Cost of Goods was used for some products.';
                $cat_isset     = 1;
            } elseif ( $global_cost ) {
                $cost         += ( $global_type == 'percent' ) ? (float) $price * ( (float) $global_cost / 100 ) * $qlt : (float) $global_cost * $qlt;
                $custom_total += $price * $qlt;
                $notice        = $cat_isset ? 'Global and Category Cost of Goods was used for some products.' : 'Global Cost of Goods was used for some products.';
            } else {
                $notice = "Some products don't have Cost of Goods.";
            }
        }
    }

    return $cart_total - $cost;
}

/**
 * get_product_cog_by_cat.
 *
 * Fetches COG value AND type for a product from its WooCommerce categories in a single
 * wp_get_post_terms() call. Results are cached per product_id for the duration of the request.
 * Selects the category with the numerically highest cost value (same behaviour as the
 * original asort/end approach, but only for categories that have a numeric value set).
 *
 * @param int $product_id
 * @return array{val: string, type: string}
 */
function get_product_cog_by_cat( $product_id ) {
    static $cache = [];
    if ( isset( $cache[ $product_id ] ) ) {
        return $cache[ $product_id ];
    }

    $empty     = [ 'val' => '', 'type' => '' ];
    $term_list = wp_get_post_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

    if ( empty( $term_list ) || is_wp_error( $term_list ) ) {
        $cache[ $product_id ] = $empty;
        return $empty;
    }

    $candidates = [];
    foreach ( $term_list as $term_id ) {
        $val = get_term_meta( $term_id, '_pixel_cost_of_goods_cost_val', true );
        if ( $val !== '' && is_numeric( $val ) ) {
            $candidates[ $term_id ] = [
                'val'  => $val,
                'type' => get_term_meta( $term_id, '_pixel_cost_of_goods_cost_type', true ),
            ];
        }
    }

    if ( empty( $candidates ) ) {
        $cache[ $product_id ] = $empty;
        return $empty;
    }

    // Use the category with the highest numeric cost value
    uasort( $candidates, function ( $a, $b ) {
        return (float) $a['val'] <=> (float) $b['val'];
    } );

    $max                  = end( $candidates );
    $cache[ $product_id ] = $max;
    return $max;
}

/**
 * get_product_type_by_cat.
 *
 * @deprecated Use get_product_cog_by_cat() which returns both val and type in one query.
 * @version 1.0.0
 * @since   1.0.0
 */
function get_product_type_by_cat( $product_id ) {
    return get_product_cog_by_cat( $product_id )['type'];
}

/**
 * get_product_cost_by_cat.
 *
 * @deprecated Use get_product_cog_by_cat() which returns both val and type in one query.
 * @version 1.0.0
 * @since   1.0.0
 */
function get_product_cost_by_cat( $product_id ) {
    return get_product_cog_by_cat( $product_id )['val'];
}

function isDisabledForCurrentRole() {

    $user = wp_get_current_user();

    if(isDisabledForUserRole($user)) {
        add_action( 'wp_head', function() {
            echo "<script type='application/javascript' id='pys-config-warning-user-role'>console.warn('PixelYourSite is disabled for current user role.');</script>\r\n";
        } );

        return true;
    }

    return false;

}

/**
 * @param \WP_User $user
 * @return bool
 */

function isDisabledForUserRole($user) {
    if($user instanceof \WP_User) {
        $disabled_for = PYS()->getOption( 'do_not_track_user_roles' );

        foreach ( (array) $user->roles as $role ) {

            if ( in_array( $role, $disabled_for ) ) {
                return true;
            }
        }
    }

    return false;
}

function getPageTitle()
{
    global $post;

    if ($post instanceof \WP_Post) {
        return $post->post_title;
    }

    if (is_singular('post')) {
        return $post->post_title;
    }
    if(is_singular('page') || is_home()) {
        return is_home() == true ? get_bloginfo('name') : $post->post_title;
    }

    if (isWooCommerceActive() && is_shop()) {
        $page_id = (int)wc_get_page_id('shop');
        return get_the_title($page_id);
    }

    if (is_category()) {
        $cat = get_query_var('cat');
        $term = get_category($cat);
        if ($term) {
            return $term->name;
        }
    }

    if (is_tag()) {
        $slug = get_query_var('tag');
        $term = get_term_by('slug', $slug, 'post_tag');
        if ($term) {
            return $term->name;
        }
    }

    if (is_tax()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        if ($term) {
            return $term->name;
        }
    }
    $cpt = get_post_type();

    if ((isWooCommerceActive() && $cpt == 'product') ||
        (isEddActive() && $cpt == 'download')) {
        return $post->post_title;
    }

    return "";
}

function getPageId() {
    global $post;

    if ($post) {
        return $post->ID;
    }

    if (is_category()) {
        $cat = get_query_var('cat');
        $term = get_category($cat);
    }

    if (is_tag()) {
        $slug = get_query_var('tag');
        $term = get_term_by('slug', $slug, 'post_tag');
    }

    if (is_tax()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    }

    if ($term) {
        return $term->term_id;
    }
    return 0;
}


function getStandardParams( $post_id = null ) {
    global $post;
    $cpt = get_post_type();
    $params = array(
        'page_title' => "",
        'post_type' => $cpt,
        'post_id' => "",
        'plugin' => "PixelYourSite"
    );

    if(PYS()->getOption("enable_event_url_param")) {
        $url = getCurrentPageUrl(true);
        $params['event_url'] = $url;
    }
    if(PYS()->getOption("enable_user_role_param")) {
        $params['user_role'] = getUserRoles();
    }



    // If a specific post_id is provided (e.g., during AJAX requests where
    // WordPress conditional tags are unavailable), resolve page context directly.
    if ( ! empty( $post_id ) && (int) $post_id > 0 ) {
        $queried_post = get_post( (int) $post_id );
        if ( $queried_post instanceof \WP_Post ) {
            $params['page_title'] = $queried_post->post_title;
            $params['post_type']  = $queried_post->post_type;
            $params['post_id']    = $queried_post->ID;
        }
    } elseif(is_singular( 'post' )) {
        $params['page_title'] = $post->post_title;
        $params['post_id']   = $post->ID;


    } elseif( is_singular( 'page' ) || is_home()) {
        $params['post_type']    = 'page';
        $params['post_id']      = is_home() ? null : $post->ID;
        $params['page_title']   = is_home() == true ? get_bloginfo( 'name' ) : $post->post_title;

    } elseif (isWooCommerceActive() && is_shop()) {
        $page_id = (int) wc_get_page_id( 'shop' );
        $params['post_type'] = 'page';
        $params['post_id']   = $page_id;
        $params['page_title'] = get_the_title( $page_id );

    } elseif ( is_category() ) {
        $cat  = get_query_var( 'cat' );
        $term = get_category( $cat );
        $params['post_type']    = 'category';
        $params['post_id']      = $cat;
        $params['page_title'] = $term->name;

    } elseif ( is_tag() ) {
        $slug = get_query_var( 'tag' );
        $term = get_term_by( 'slug', $slug, 'post_tag' );
        $params['post_type']    = 'tag';
        if($term) {
            $params['post_id']      = $term->term_id;
            $params['page_title']   = $term->name;
        }


    } elseif (is_tax()) {
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $params['post_type'] = get_query_var( 'taxonomy' );
        if ( $term ) {
            $params['post_id']      = $term->term_id;
            $params['page_title'] = $term->name;
        }

    }elseif(is_archive()){
	    $params['page_title'] = get_the_archive_title();
	    $params['post_type'] = 'archive';
	} elseif ((isWooCommerceActive() && $cpt == 'product') ||
        (isEddActive() && $cpt == 'download') ) {
        $params['page_title'] = $post->post_title;
        $params['post_id']   = $post->ID;

    } else if ($post instanceof \WP_Post) {
        $params['page_title'] = $post->post_title;
        $params['post_id']   = $post->ID;
    }


    if(!PYS()->getOption("enable_post_type_param")) {
        unset($params['post_type']);
    }
    if(!PYS()->getOption("enable_post_id_param")) {
        unset($params['post_id']);
    }

    if(isWcfStep()) {

        $step = getWcfCurrentStep();
        $flow_id = $step->get_flow_id();

        if(PYS()->getOption('wcf_global_cartflows_parameter_enabled'))
            $params["cartlows"] = "yes";

        if(PYS()->getOption('wcf_global_cartflows_flow_parameter_enabled'))
            $params["cartflows_flow"] = get_the_title($flow_id);

        if(PYS()->getOption('wcf_global_cartflows_step_parameter_enabled'))
            $params["cartflows_step"] = $step->get_step_type();
    }

    return $params;
}

function getWPMLProductId($product_id, $tag) {
    $tagOption = "woo_wpml_unified_id";
    $tagLanguageOption = "woo_wpml_language";

    if (isWPMLActive() && $tag->getOption($tagOption)) {
        $wpml_product_id = !empty($tag->getOption($tagLanguageOption))
            ? apply_filters('wpml_object_id', $product_id, 'product', false, $tag->getOption($tagLanguageOption))
            : apply_filters('wpml_original_element_id', NULL, $product_id);
        if ($wpml_product_id) {
            return $wpml_product_id;
        }
    }
    return $product_id;
}
function getObjectTerms($taxonomy, $post_id) {
    $terms = get_the_terms($post_id, $taxonomy);
    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    // Build a map of term_id => term object for quick parent lookups
    $terms_by_id = [];
    foreach ($terms as $term) {
        $terms_by_id[$term->term_id] = $term;
    }

    // Helper function to calculate the "depth" of a term in the hierarchy
    // (how many parent levels it has)
    $get_depth = function($term) use ($terms_by_id) {
        $depth = 0;
        while ($term->parent && isset($terms_by_id[$term->parent])) {
            $term = $terms_by_id[$term->parent];
            $depth++;
        }
        return $depth;
    };

    // Sort terms by their depth so that parent categories come before child categories
    usort($terms, function($a, $b) use ($get_depth) {
        return $get_depth($a) <=> $get_depth($b);
    });

    // Return an array of decoded term names
    $results = [];
    foreach ($terms as $term) {
        $results[] = html_entity_decode($term->name, ENT_QUOTES, 'UTF-8');
    }

    return $results;
}

/**
 * @param string $taxonomy Taxonomy name
 *
 * @return array Array of object term names and id
 */
function getObjectTermsWithId( $taxonomy, $post_id ) {

    $terms   = get_the_terms( $post_id, $taxonomy );
    $results = array();

    if ( is_wp_error( $terms ) || empty ( $terms ) ) {
        return array();
    }

    // decode special chars
    foreach ( $terms as $term ) {
        $results[] = [
            'name' => html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' ),
            'id'   => $term->term_id
        ];
    }

    return $results;

}

/**
 * @param array  $params
 * @param string $key
 *
 * @return mixed
 */
function safeGetArrayValue( $params, $key, $fallback = null ) {
    return isset( $params[ $key ] ) ? $params[ $key ] : $fallback;
}

/**
 * Sanitize event name. Only letters, numbers and underscores allowed.
 *
 * @param string $name
 *
 * @return string
 */
function sanitizeKey( $name ) {

    $name = str_replace( ' ', '_', $name );
    $name = preg_replace( '/[^0-9a-zA-z_]/', '', $name );

    return $name;

}

function removeProtocolFromUrl( $url ) {

    if ( extension_loaded( 'mbstring' ) ) {

        $un = new URL\Normalizer();
        $un->setUrl( $url );
        $url = $un->normalize();

    }

    // remove fragment component
    $url_parts = parse_url( $url );
    if( isset( $url_parts['fragment'] ) ) {
        $url = preg_replace( '/#'. $url_parts['fragment'] . '$/', '', $url );
    }

    // remove scheme and www and current host if any
    $url = str_replace( array( 'http://', 'https://', 'http://www.', 'https://www.', 'www.' ), '', $url );
    $url = trim( $url );
    $url = ltrim( $url, '/' );
    // $url = rtrim( $url, '/' );

    return $url;

}

function getCurrentPageUrl($removeQuery = false) {
	if(!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['REQUEST_URI'])) {
		return '';
	}
    if($removeQuery && isset($_SERVER['QUERY_STRING']) && isset($_SERVER['HTTP_HOST'])){
        return $_SERVER['HTTP_HOST'] . str_replace("?".$_SERVER['QUERY_STRING'],"",$_SERVER['REQUEST_URI']);
    }
    return  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;
}

function startsWith( $haystack, $needle ) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos( $haystack, $needle, -strlen( $haystack ) ) !== false;
}

/**
 * Compare single URL or array of URLs with base URL. If base URL is not set, current page URL will be used.
 *
 * @param string|array $url
 * @param string       $base
 * @param string       $rule
 *
 * @return bool
 */
function compareURLs( $url, $base = '', $rule = 'match' ) {

    // use current page url if not set
    if ( empty( $base ) ) {
        $base = getCurrentPageUrl();
    }

    // For param_* rules, extract query params from base URL before protocol is stripped
    $base_params = null;
    if ( $rule === 'param_match' || $rule === 'param_contains' ) {
        $base_query = parse_url( $base, PHP_URL_QUERY );
        $base_params = array();
        if ( $base_query ) {
            // Normalize &amp; to & before parsing (handles HTML-encoded separators)
            $base_query = html_entity_decode( $base_query, ENT_QUOTES | ENT_HTML5 );
            parse_str( $base_query, $base_params );
        }
    }

    $base = removeProtocolFromUrl( $base );

    if ( is_string( $url ) ) {
        if ( empty( $url ) || '*' === $url ) {
            return true;
        }

        $url = rtrim( $url, '*' );  // for backward capability
        $url = removeProtocolFromUrl( $url );
        if($rule == 'param_match')
        {
            $urlParamsWithQuestionMark = $url;
            parse_str($urlParamsWithQuestionMark, $params);

            foreach ($params as $key => $value)
            {
                if(urlParamsMatch($key, $value, $base_params))
                {
                    return true;
                }
            }
            return false;
        }
        if($rule == 'param_contains')
        {
            return urlParamsContains($url, $base_params);
        }
        if ( ! $rule || $rule == 'match' ) {
            return $base == $url;
        }

        if ( $rule == 'contains' ) {

            if ( $base == $url ) {
                return true;
            }

            if(empty($base) || empty($url)) {
                return false;
            }

            if ( strpos( $base, $url ) !== false ) {
                return true;
            }

            return false;

        }

        return false;

    } else {

        // recursively compare each url
        foreach ( $url as $single_url ) {
            if ( $single_url['rule'] === 'any' || compareURLs( $single_url['value'], $base, $single_url['rule'] )) {
                return true;
            }

        }

        return false;

    }

}
function urlParamsMatch($param, $value, $params = null) {
    $source = $params !== null ? $params : $_GET;
    return isset($source[$param]) && $source[$param] === $value;
}

function urlParamsContains($search, $params = null) {
    $source = $params !== null ? $params : $_GET;
    foreach ($source as $key => $val) {
        if ((is_string($key) && stripos($key, $search) !== false) || (is_string($val) && stripos($val, $search) !== false)) {
            return true;
        }
    }
    return false;
}
/**
 * Add attribute with value to a HTML tag.
 *
 * @param string $attr_name  Attribute name, eg. "class"
 * @param string $attr_value Attribute value
 * @param string $content    HTML content where attribute should be inserted
 * @param bool   $overwrite  Override existing value of attribute or append it
 * @param string $tag        Selector name, eg. "button". Default "a"
 *
 * @return string Modified HTML content
 */
function insertTagAttribute( $attr_name, $attr_value, $content, $overwrite = false, $tag = 'a' ) {

    // do not modify js attributes
    if ( $attr_name == 'on' ) {
        return $content;
    }

    $attr_value = trim( $attr_value );

    try {

        $doc = new \DOMDocument();

        // old libxml does not support options parameter
        if ( defined( 'LIBXML_DOTTED_VERSION' ) && version_compare( LIBXML_DOTTED_VERSION, '2.6.0', '>=' ) &&
            version_compare( phpversion(), '5.4.0', '>=' )
        ) {
            @$doc->loadHTML( '<?xml encoding="UTF-8">' . $content, LIBXML_NOEMPTYTAG );
        } else {
            @$doc->loadHTML( '<?xml encoding="UTF-8">' . $content );
        }

        // select top-level tag if it is not specified in args
        if ( $tag == 'any' ) {

            /** @var \DOMNodeList $node */
            $node = $doc->getElementsByTagName( 'body' );

            if ( $node->length == 0 ) {
                throw new \Exception( 'Empty or wrong tag passed to filter.' );
            }

            $node = $node->item( 0 )->childNodes->item( 0 );

        } else {
            $node = $doc->getElementsByTagName( $tag )->item( 0 );
        }

        if ( is_null( $node ) ) {
            return $content;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $attribute = $node->getAttribute( $attr_name );

        // add attribute or override old one
        if ( empty( $attribute ) || $overwrite ) {

            /** @noinspection PhpUndefinedMethodInspection */
            $node->setAttribute( $attr_name, $attr_value );

            return str_replace( array( '<?xml encoding="UTF-8">', '<html>', '</html>', '<body>', '</body>' ), null, $doc->saveHTML() );

        }

        // append value to exist attribute
        if ( $overwrite == false ) {
            if(strpos($attribute,$attr_value) !== false) {
                return $content;
            }
            $value = $attribute . ',' . $attr_value;
            /** @noinspection PhpUndefinedMethodInspection */
            $node->setAttribute( $attr_name, $value );

            return str_replace( array( '<?xml encoding="UTF-8">', '<html>', '</html>', '<body>', '</body>' ), null, $doc->saveHTML() );

        }

    } catch ( \Exception $e ) {
        error_log( $e );
    }

    return $content;

}
function getUserRoles() {
    $user = wp_get_current_user();

    if ( $user->ID !== 0 ) {
        $user_roles = implode( ',', $user->roles );
    } else {
        $user_roles = 'guest';
    }
    return $user_roles;
}

/**
 * Get UTM parameters
 *
 * @param bool $seed_undefined Whether to set undefined for missing values
 * @param bool $use_last If true, use last_pys_* cookies, otherwise use pys_* cookies
 * @return array
 */
function getUtms( $seed_undefined = false, $use_last = false ) {
    $utm = array();
    $utmTerms = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    $cookie_prefix = $use_last ? 'last_pys_' : 'pys_';

    foreach ( $utmTerms as $utmTerm ) {
        if ( isset( $_GET[ $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_GET[ $utmTerm ] );
        } elseif ( isset( $_COOKIE[ $cookie_prefix . $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_COOKIE[ $cookie_prefix . $utmTerm ] );
        } elseif ( isset( $_SESSION['TrafficUtms'] ) && isset( $_SESSION['TrafficUtms'][ $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_SESSION['TrafficUtms'][ $utmTerm ] );
        } elseif ( $seed_undefined ) {
            $utm[ $utmTerm ] = 'undefined';
        }
    }

    return $utm;
}

/**
 * Get UTM ID parameters
 *
 * @param bool $seed_undefined Whether to set undefined for missing values
 * @param bool $use_last If true, use last_pys_* cookies, otherwise use pys_* cookies
 * @return array
 */
function getUtmsId( $seed_undefined = false, $use_last = false ) {
    $utm = array();
    $utmTerms = ['fbadid', 'gadid', 'padid', 'bingid'];
    $cookie_prefix = $use_last ? 'last_pys_' : 'pys_';

    foreach ( $utmTerms as $utmTerm ) {
        if ( isset( $_GET[ $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_GET[ $utmTerm ] );
        } elseif ( isset( $_COOKIE[ $cookie_prefix . $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_COOKIE[ $cookie_prefix . $utmTerm ] );
        } elseif ( isset( $_SESSION['TrafficUtmsId'] ) && isset( $_SESSION['TrafficUtmsId'][ $utmTerm ] ) ) {
            $utm[ $utmTerm ] = filterEmails( $_SESSION['TrafficUtmsId'][ $utmTerm ] );
        } elseif ( $seed_undefined ) {
            $utm[ $utmTerm ] = 'undefined';
        }
    }

    return $utm;
}

/**
 * Parse UTM string in format "key:value|key:value" into array
 *
 * @param string $utmString UTM string to parse
 * @return array Parsed UTM parameters
 */
function parseUtmString( $utmString ) {
    if ( empty( $utmString ) ) {
        return array();
    }

    $result = array();
    $utmPairs = explode( '|', $utmString );

    foreach ( $utmPairs as $pair ) {
        $parts = explode( ':', $pair, 2 );
        $key = $parts[0];
        $value = isset( $parts[1] ) && $parts[1] !== 'undefined' ? $parts[1] : '';

        if ( ! empty( $key ) && ! empty( $value ) ) {
            $result[ $key ] = $value;
        }
    }

    return $result;
}

function getBrowserTime(){
    $dateTime = array();
    $date = new \DateTime();

    $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $months = array('January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December');
    $hours = array('00-01', '01-02', '02-03', '03-04', '04-05', '05-06', '06-07', '07-08',
        '08-09', '09-10', '10-11', '11-12', '12-13', '13-14', '14-15', '15-16', '16-17',
        '17-18', '18-19', '19-20', '20-21', '21-22', '22-23', '23-24');

    $dateTime[] = $hours[$date->format('G')];
    $dateTime[] = $days[$date->format('w')];
    $dateTime[] = $months[$date->format('n') - 1];

    $dateTimeString = implode("|", $dateTime);
    return $dateTimeString;
}

/**
 * Parse browser time string into array with event_time, event_day, event_month
 *
 * @param string $browserTimeString Browser time string in format "hour|day|month" (e.g., "08-09|Monday|January")
 * @return array Parsed time data with keys: event_time, event_day, event_month
 */
function getUserTime( $browserTimeString ) {
    if ( empty( $browserTimeString ) ) {
        return array();
    }

    $parts = explode( '|', $browserTimeString );
    $result = array();

    if ( isset( $parts[0] ) && ! empty( $parts[0] ) && PYS()->getOption( 'enable_event_time_param' ) ) {
        $result['event_hour'] = $parts[0];
    }
    if ( isset( $parts[1] ) && ! empty( $parts[1] ) && PYS()->getOption( 'enable_event_day_param' ) ) {
        $result['event_day'] = $parts[1];
    }
    if ( isset( $parts[2] ) && ! empty( $parts[2] ) && PYS()->getOption( 'enable_event_month_param' ) ) {
        $result['event_month'] = $parts[2];
    }

    return $result;
}

function filterEmails($value) {
    return validateEmail($value) ? "undefined" : $value;
}

function validateEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Returns true when the current request is a PYS pixel-tracking REST API call
 * (e.g. pys-facebook/v1/event, pys-tiktok/v1/event, pys-pinterest/v1/event).
 * These are server-side mirror requests triggered by the browser, so they must
 * NOT be treated as "REST API" traffic for source attribution.
 */
function pys_is_tracking_rest_request() {
    if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
        return false;
    }
    $uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $pys_namespaces = [ 'pys-facebook/', 'pys-tiktok/', 'pys-pinterest/' ];
    foreach ( $pys_namespaces as $ns ) {
        if ( strpos( $uri, $ns ) !== false ) {
            return true;
        }
    }
    return false;
}

function getTrafficSource ($use_last = false) {
    $referrer = "";
    $source = "";
    $cookie_prefix = $use_last ? 'last_pys' : 'pys';
    try {
        if ( function_exists( 'wc_get_raw_referer' ) ) {
            $referrer = wc_get_raw_referer();
        } else if (isset($_SERVER['HTTP_REFERER'])) {
            $referrer = $_SERVER['HTTP_REFERER'];
        }

        $referrer     = empty( $referrer ) ? '' : (string) $referrer;
        $has_referrer = $referrer !== '';
        $internal     = $has_referrer && ( substr( $referrer, 0, strlen( site_url() ) ) === site_url() );
        $external     = $has_referrer && ! $internal;
        $cookie       = ( ! isset( $_COOKIE[ $cookie_prefix . 'TrafficSource' ] ) || $_COOKIE[ $cookie_prefix . 'TrafficSource' ] === 'direct' ) ? false : $_COOKIE[ $cookie_prefix . 'TrafficSource' ];
        $session      = ( ! isset( $_SESSION['TrafficSource'] ) || $_SESSION['TrafficSource'] === 'direct' ) ? false : $_SESSION['TrafficSource'];

        // Resolve best known source; prefer session over cookie, use !== false so empty-string values are preserved.
        $known = $session !== false ? $session : ( $cookie !== false ? $cookie : false );

        if ( ! $external ) {
            // Direct or internal traffic: keep previously stored source if available.
            $source = $known !== false ? $known : 'direct';
        } else {
            // External referrer: use stored source only when it matches the current referrer, otherwise use referrer.
            $source = ( ( $cookie !== false && $cookie === $referrer ) || ( $session !== false && $session === $referrer ) )
                ? $known
                : $referrer;
        }

        $is_rest = defined( 'REST_REQUEST' ) && REST_REQUEST && ! pys_is_tracking_rest_request();

        // Final safety-net: should never be empty/false at this point, but guard anyway.
        if ( empty( $source ) ) {
            return $is_rest ? 'REST API' : 'direct';
        }

        if ( $source !== 'direct' ) {
            $parse = parse_url( $source );
            if ( isset( $parse['host'] ) ) {
                return $parse['host']; // leave only domain (Issue #70)
            } elseif ( $source === $cookie || $source === $session ) {
                return $source;
            } else {
                return $is_rest ? 'REST API' : 'direct';
            }
        } else {
            return $is_rest ? 'REST API' : $source;
        }
    } catch (\Exception $e) {
        return "direct";
    }
}

function sanitizeParams( $params ) {

    $sanitized = array();

    foreach ( $params as $key => $value ) {

        // skip empty (but not zero)
        if ( ! isset( $value )  ||
            (is_string($value) && $value == "") ||
            (is_array($value) && count($value) == 0)
        ) {
            continue;
        }

        $key = sanitizeKey( $key );

        if ( is_array( $value ) ) {
            $sanitized[ $key ] = sanitizeParams( $value );
        } elseif ( is_bool( $value ) ) {
            $sanitized[ $key ] = (bool) $value;
        } elseif (is_numeric($value)) {
            $sanitized[ $key ] = $value;
        } else {
            // Do NOT html_entity_decode() here: reversing HTML encoding would
            // re-introduce XSS-capable characters (<, >, ", ') that may have
            // been previously encoded by upstream sanitizers/templating.
            $sanitized[ $key ] = sanitize_text_field( stripslashes( $value ) );
        }



    }

    return $sanitized;

}

function formatPriceTrimZeros($number, $decimals = 2) {
    $formatted = number_format($number, $decimals, '.', '');
    // Remove extra zeros on the right and a period if necessary
    return rtrim(rtrim($formatted, '0'), '.');
}

/**
 * Checks if specified event enabled at least for one configured pixel
 *
 * @param string $eventName
 *
 * @return bool
 */
function isEventEnabled( $eventName ) {

    foreach ( PYS()->getRegisteredPixels() as $pixel ) {
        /** @var Pixel|Settings $pixel */

        if ( $pixel->configured() && $pixel->getOption( $eventName ) ) {
            return true;
        }

    }

    return false;

}

function pys_round( $val, $precision = 2, $mode = PHP_ROUND_HALF_UP )  {
    if ( ! is_numeric( $val ) ) {
        $val = floatval( $val );
    }
    return round( $val, $precision, $mode );
}

/**
 * Currency symbols
 *
 * @return array
 * */

function getPysCurrencySymbols() {
    return array(
        'AED' => '&#x62f;.&#x625;',
        'AFN' => '&#x60b;',
        'ALL' => 'L',
        'AMD' => 'AMD',
        'ANG' => '&fnof;',
        'AOA' => 'Kz',
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => 'Afl.',
        'AZN' => 'AZN',
        'BAM' => 'KM',
        'BBD' => '&#36;',
        'BDT' => '&#2547;&nbsp;',
        'BGN' => '&#1083;&#1074;.',
        'BHD' => '.&#x62f;.&#x628;',
        'BIF' => 'Fr',
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => 'Bs.',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTC' => '&#3647;',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYR' => 'Br',
        'BYN' => 'Br',
        'BZD' => '&#36;',
        'CAD' => '&#36;',
        'CDF' => 'Fr',
        'CHF' => '&#67;&#72;&#70;',
        'CLP' => '&#36;',
        'CNY' => '&yen;',
        'COP' => '&#36;',
        'CRC' => '&#x20a1;',
        'CUC' => '&#36;',
        'CUP' => '&#36;',
        'CVE' => '&#36;',
        'CZK' => '&#75;&#269;',
        'DJF' => 'Fr',
        'DKK' => 'DKK',
        'DOP' => 'RD&#36;',
        'DZD' => '&#x62f;.&#x62c;',
        'EGP' => 'EGP',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '&euro;',
        'FJD' => '&#36;',
        'FKP' => '&pound;',
        'GBP' => '&pound;',
        'GEL' => '&#x20be;',
        'GGP' => '&pound;',
        'GHS' => '&#x20b5;',
        'GIP' => '&pound;',
        'GMD' => 'D',
        'GNF' => 'Fr',
        'GTQ' => 'Q',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => '&#70;&#116;',
        'IDR' => 'Rp',
        'ILS' => '&#8362;',
        'IMP' => '&pound;',
        'INR' => '&#8377;',
        'IQD' => '&#x639;.&#x62f;',
        'IRR' => '&#xfdfc;',
        'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
        'ISK' => 'kr.',
        'JEP' => '&pound;',
        'JMD' => '&#36;',
        'JOD' => '&#x62f;.&#x627;',
        'JPY' => '&yen;',
        'KES' => 'KSh',
        'KGS' => '&#x441;&#x43e;&#x43c;',
        'KHR' => '&#x17db;',
        'KMF' => 'Fr',
        'KPW' => '&#x20a9;',
        'KRW' => '&#8361;',
        'KWD' => '&#x62f;.&#x643;',
        'KYD' => '&#36;',
        'KZT' => 'KZT',
        'LAK' => '&#8365;',
        'LBP' => '&#x644;.&#x644;',
        'LKR' => '&#xdbb;&#xdd4;',
        'LRD' => '&#36;',
        'LSL' => 'L',
        'LYD' => '&#x644;.&#x62f;',
        'MAD' => '&#x62f;.&#x645;.',
        'MDL' => 'MDL',
        'MGA' => 'Ar',
        'MKD' => '&#x434;&#x435;&#x43d;',
        'MMK' => 'Ks',
        'MNT' => '&#x20ae;',
        'MOP' => 'P',
        'MRU' => 'UM',
        'MUR' => '&#x20a8;',
        'MVR' => '.&#x783;',
        'MWK' => 'MK',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => 'MT',
        'NAD' => 'N&#36;',
        'NGN' => '&#8358;',
        'NIO' => 'C&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#x631;.&#x639;.',
        'PAB' => 'B/.',
        'PEN' => 'S/',
        'PGK' => 'K',
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PRB' => '&#x440;.',
        'PYG' => '&#8370;',
        'QAR' => '&#x631;.&#x642;',
        'RMB' => '&yen;',
        'RON' => 'lei',
        'RSD' => '&#x434;&#x438;&#x43d;.',
        'RUB' => '&#8381;',
        'RWF' => 'Fr',
        'SAR' => '&#x631;.&#x633;',
        'SBD' => '&#36;',
        'SCR' => '&#x20a8;',
        'SDG' => '&#x62c;.&#x633;.',
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&pound;',
        'SLL' => 'Le',
        'SOS' => 'Sh',
        'SRD' => '&#36;',
        'SSP' => '&pound;',
        'STN' => 'Db',
        'SYP' => '&#x644;.&#x633;',
        'SZL' => 'L',
        'THB' => '&#3647;',
        'TJS' => '&#x405;&#x41c;',
        'TMT' => 'm',
        'TND' => '&#x62f;.&#x62a;',
        'TOP' => 'T&#36;',
        'TRY' => '&#8378;',
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => 'Sh',
        'UAH' => '&#8372;',
        'UGX' => 'UGX',
        'USD' => '&#36;',
        'UYU' => '&#36;',
        'UZS' => 'UZS',
        'VEF' => 'Bs F',
        'VES' => 'Bs.S',
        'VND' => '&#8363;',
        'VUV' => 'Vt',
        'WST' => 'T',
        'XAF' => 'CFA',
        'XCD' => '&#36;',
        'XOF' => 'CFA',
        'XPF' => 'Fr',
        'YER' => '&#xfdfc;',
        'ZAR' => '&#82;',
        'ZMW' => 'ZK',
    );
}

function pys_generate_token( $length = 36 ) {
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$charactersLength = strlen( $characters );
	$randomString = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$randomString .= $characters[ random_int( 0, $charactersLength - 1 ) ];
	}
	return $randomString;
}

/**
 * Remove url params [url_*]
 * @param $params
 * @return mixed
 */
function remove_url_params( $params ) {
	if ( !empty( $params ) ) {
		foreach ( $params as &$param ) {
			if ( preg_match( '/^\[?url_([^\]]*)\]?$/i', $param, $matches ) ) {
				$param = $matches[ 1 ];
			}
		}
	}

	return $params;
}

/**
 * Get persistence user data
 * @param $em
 * @param $fn
 * @param $ln
 * @param $tel
 * @return array
 */
function get_persistence_user_data( $em, $fn, $ln, $tel ) {
	if ( !apply_filters( 'pys_disable_advanced_form_data_cookie', false ) && !apply_filters( 'pys_disable_advance_data_cookie', false ) && (!is_admin() || wp_doing_ajax())) {
        if ( isset( $_COOKIE[ "pys_advanced_form_data" ] ) ) {
			$userData = json_decode( stripslashes( $_COOKIE[ "pys_advanced_form_data" ] ), true );
			$data_persistence = PYS()->getOption( 'data_persistency' );

			if ( isset( $userData[ "email" ] ) && $userData[ "email" ] != "" && ( $data_persistence == 'keep_data' || empty( $em ) ) ) {
				$em = $userData[ "email" ];
			}
			if ( isset( $userData[ "phone" ] ) && $userData[ "phone" ] != "" && ( $data_persistence == 'keep_data' || empty( $tel ) ) ) {
				$tel = $userData[ "phone" ];
			}
			if ( isset( $userData[ "first_name" ] ) && $userData[ "first_name" ] != "" && ( $data_persistence == 'keep_data' || empty( $fn ) ) ) {
				$fn = $userData[ "first_name" ];
			}
			if ( isset( $userData[ "last_name" ] ) && $userData[ "last_name" ] != "" && ( $data_persistence == 'keep_data' || empty( $ln ) ) ) {
				$ln = $userData[ "last_name" ];
			}
		}
	}

	return array(
		'em'  => $em,
		'fn'  => $fn,
		'ln'  => $ln,
		'tel' => $tel
	);
}

/**
 * Recursively sanitize enrich data array
 *
 * @param mixed $data Data to sanitize (can be array, string, or other types)
 * @param string $key Current key being processed (for context-aware sanitization)
 * @return mixed Sanitized data
 */
function pys_sanitize_enrich_data($data, $key = '') {
    // Handle null values
    if (is_null($data)) {
        return '';
    }

    // Handle arrays recursively
    if (is_array($data)) {
        // Handle empty arrays
        if (empty($data)) {
            return array();
        }

        $sanitized = array();
        foreach ($data as $array_key => $value) {
            // Sanitize both key and value
            $clean_key = sanitize_text_field($array_key);
            $sanitized[$clean_key] = pys_sanitize_enrich_data($value, $clean_key);
        }
        return $sanitized;
    }

    // Handle non-scalar values (objects, resources, etc.)
    if (!is_scalar($data)) {
        return '';
    }

    // Convert to string for sanitization
    $data = (string) $data;

    // Context-aware sanitization based on field name
    // URLs should use esc_url_raw()
    if (stripos($key, 'landing') !== false || stripos($key, 'url') !== false) {
        return esc_url_raw($data);
    }

    // Multi-line text fields
    if (stripos($key, 'description') !== false || stripos($key, 'content') !== false) {
        return sanitize_textarea_field($data);
    }

    // Default: use sanitize_text_field for single-line text
    return sanitize_text_field($data);
}

/**
 * Safely extract and validate a field from enrichData array
 *
 * @param mixed $enrichData The enrich data array
 * @param string $key The key to extract
 * @param mixed $default Default value if key doesn't exist
 * @return mixed The sanitized value or default
 */
function pys_get_validated_enrich_field($enrichData, $key, $default = '') {
    if (!is_array($enrichData) || !isset($enrichData[$key])) {
        return $default;
    }

    return pys_sanitize_enrich_data($enrichData[$key], $key);
}

/**
 * Sanitize UTM values string and parse into array
 *
 * @param string $utms UTM string in format "key:value|key:value"
 * @return array Sanitized UTM parameters array
 */
function pys_sanitize_utm_values($utms) {
    // Validate input
    if (!is_string($utms) || empty($utms)) {
        return array();
    }

    // Sanitize the input string
    $utms = sanitize_text_field($utms);

    // Parse UTM string
    $utmArray = parseUtmString($utms);

    // Whitelist of allowed UTM parameters
    $allowedUtmParams = array(
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content'
    );

    // Filter and sanitize
    $sanitizedUtms = array();
    foreach ($utmArray as $key => $value) {
        // Only allow whitelisted UTM parameters
        if (in_array($key, $allowedUtmParams, true)) {
            $sanitizedUtms[$key] = sanitize_text_field($value);
        }
    }

    return $sanitizedUtms;
}


function update_advance_matching_user_data( $first_name, $last_name, $email, $phone ) {
	if ( !apply_filters( 'pys_disable_advanced_form_data_cookie', false ) && !apply_filters( 'pys_disable_advance_data_cookie', false ) ) {
		$user_persistence_data = get_persistence_user_data( $email, $first_name, $last_name, $phone );
		$userData = array(
			'first_name' => $user_persistence_data[ 'fn' ],
			'last_name'  => $user_persistence_data[ 'ln' ],
			'email'      => $user_persistence_data[ 'em' ],
			'phone'      => $user_persistence_data[ 'tel' ]
		);

		if ( isset( $_COOKIE[ "pys_advanced_form_data" ] ) ) {
			$userData_cookie = json_decode( stripslashes( $_COOKIE[ "pys_advanced_form_data" ] ), true );
			$data_persistence = PYS()->getOption( 'data_persistency' );

			if ( isset( $userData_cookie[ 'emails' ] ) && is_array( $userData_cookie[ 'emails' ] ) ) {
				if ( $data_persistence == 'keep_data' ) {
					$userData[ 'emails' ] = array_merge( $userData_cookie[ 'emails' ], array( $email ) );
				} else {
					$userData[ 'emails' ] = array_merge( array( $email ), $userData_cookie[ 'emails' ] );
				}
				$userData[ 'emails' ] = array_unique( $userData[ 'emails' ] );
				$userData[ 'emails' ] = array_slice( $userData[ 'emails' ], 0, 3 );
			} else {
				$userData[ 'emails' ] = array( $email );
			}

			if ( isset( $userData_cookie[ 'fns' ] ) && is_array( $userData_cookie[ 'fns' ] ) ) {
				if ( $data_persistence == 'keep_data' ) {
					$userData[ 'fns' ] = array_merge( $userData_cookie[ 'fns' ], array( $first_name ) );
				} else {
					$userData[ 'fns' ] = array_merge( array( $first_name ), $userData_cookie[ 'fns' ] );
				}
				$userData[ 'fns' ] = array_unique( $userData[ 'fns' ] );
				$userData[ 'fns' ] = array_slice( $userData[ 'fns' ], 0, 2 );
			} else {
				$userData[ 'fns' ] = array( $first_name );
			}

			if ( isset( $userData_cookie[ 'lns' ] ) && is_array( $userData_cookie[ 'lns' ] ) ) {
				if ( $data_persistence == 'keep_data' ) {
					$userData[ 'lns' ] = array_merge( $userData_cookie[ 'lns' ], array( $last_name ) );
				} else {
					$userData[ 'lns' ] = array_merge( array( $last_name ), $userData_cookie[ 'lns' ] );
				}
				$userData[ 'lns' ] = array_unique( $userData[ 'lns' ] );
				$userData[ 'lns' ] = array_slice( $userData[ 'lns' ], 0, 2 );
			} else {
				$userData[ 'lns' ] = array( $last_name );
			}

			if ( !empty( $phone ) ) {
				if ( isset( $userData_cookie[ 'phones' ] ) && is_array( $userData_cookie[ 'phones' ] ) ) {
					if ( $data_persistence == 'keep_data' ) {
						$userData[ 'phones' ] = array_merge( $userData_cookie[ 'phones' ], array( $phone ) );
					} else {
						$userData[ 'phones' ] = array_merge( array( $phone ), $userData_cookie[ 'phones' ] );
					}
					$userData[ 'phones' ] = array_unique( $userData[ 'phones' ] );
					$userData[ 'phones' ] = array_slice( $userData[ 'phones' ], 0, 2 );
				} else {
					$userData[ 'phones' ] = array( $phone );
				}
			} else {
				$userData[ 'phones' ] = $userData_cookie[ 'phones' ] ?? array();
			}

		} else {
			$userData[ 'emails' ] = array( $email );
			$userData[ 'fns' ] = array( $first_name );
			$userData[ 'lns' ] = array( $last_name );
			$userData[ 'phones' ] = array( $phone );
		}

		setcookie( 'pys_advanced_form_data', json_encode( $userData ), 2147483647, '/', PYS()->general_domain );
	}
}

function getUserData() {
    $userData = array();
    $emails = $addresses = $phones = array();

    $user = wp_get_current_user();
    if ( $user != null && $user->ID != 0 ) {
        $user_meta = get_user_meta( $user->ID, '', true );

        $emails[] = $user->user_email;
        if ( !empty( $user_meta[ 'billing_phone' ] ) && !empty( $user_meta[ 'billing_phone' ][ 0 ] ) ) $phones[] = $user_meta[ 'billing_phone' ][ 0 ];

        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $city = !empty( $user_meta[ 'billing_city' ] ) ? $user_meta[ 'billing_city' ][ 0 ] : '';
        $country = !empty( $user_meta[ 'billing_country' ] ) ? $user_meta[ 'billing_country' ][ 0 ] : '';

        if ( $first_name && $last_name && $country && $city ) {

            $address = array(
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'city'       => $city,
                'country'    => $country
            );

            $street = !empty( $user_meta[ 'billing_address_1' ] ) ? $user_meta[ 'billing_address_1' ][ 0 ] : '';
            $region = !empty( $user_meta[ 'billing_state' ] ) ? $user_meta[ 'billing_state' ][ 0 ] : '';
            $zip = !empty( $user_meta[ 'billing_postcode' ] ) ? $user_meta[ 'billing_postcode' ][ 0 ] : '';

            if ( $zip ) $address[ 'postal_code' ] = $zip; // additional
            if ( $street ) $address[ 'street' ] = $street; // additional
            if ( $region ) $address[ 'region' ] = $region; // additional

            $addresses[] = $address;
        }
    }
    if ( isWooCommerceActive() ) {
        $wooOrder = EventsWoo()->getOrder();
        if ( $wooOrder ) {

            $emails[] = $wooOrder->get_billing_email();
            $phones[] = $wooOrder->get_billing_phone();

            $city = $wooOrder->get_billing_city();
            $country = $wooOrder->get_billing_country();
            $first_name = $wooOrder->get_billing_first_name();
            $last_name = $wooOrder->get_billing_last_name();

            if ( $first_name && $last_name && $country && $city ) {
                $address = array(
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'city'       => $city,
                    'country'    => $country
                );

                $zip = $wooOrder->get_billing_postcode();
                $street = $wooOrder->get_billing_address_1();

                if ( $zip ) $address[ 'postal_code' ] = $zip; // additional
                if ( $street ) $address[ 'street' ] = $street; // additional

                $addresses[] = $address;
            }
        }
    }
    if ( isEddActive() ) {
        $eddOrderId = EventsEdd()->getEddOrderId();
        if ( $eddOrderId ) {
            $payment = new \EDD_Payment( $eddOrderId );
            if ( $payment ) {
                $meta = $payment->get_meta();
                if ( isset( $meta[ 'user_info' ] ) ) {

                    $first_name = $last_name = '';

                    if ( isset( $meta[ 'user_info' ][ 'email' ] ) ) {
                        $emails[] = $meta[ 'user_info' ][ 'email' ];
                    }
                    if ( isset( $meta[ 'user_info' ][ 'first_name' ] ) ) {
                        $first_name = $meta[ 'user_info' ][ 'first_name' ];
                    }
                    if ( isset( $meta[ 'user_info' ][ 'last_name' ] ) ) {
                        $last_name = $meta[ 'user_info' ][ 'last_name' ];
                    }
                    if ( isset( $meta[ 'user_info' ][ 'address' ] ) ) {
                        $addresses[] = $meta[ 'user_info' ][ 'address' ];
                    }

                    if ( $first_name && $last_name && !empty( $addresses ) ) {
                        $address = array(
                            'first_name' => $first_name,
                            'last_name'  => $last_name,
                            'city'       => $addresses[ 0 ][ 'city' ],
                            'country'    => $addresses[ 0 ][ 'country' ]
                        );
                        $addresses[] = $address;
                    }
                }
            }
        }
    }

    if ( GA()->getOption( 'use_multiple_provided_data' ) || GTM()->getOption( 'use_multiple_provided_data' )) {
        $userData[ 'emails' ] = array_slice( $emails, 0, 3 );
        $userData[ 'phones' ] = array_slice( $phones, 0, 3 );
        $userData[ 'addresses' ] = array_slice( $addresses, 0, 2 );
    } else {
        $userData[ 'emails' ] = array_slice( $emails, 0, 1 );
        $userData[ 'phones' ] = array_slice( $phones, 0, 1 );
        $userData[ 'addresses' ] = array_slice( $addresses, 0, 1 );
    }

    return $userData;
}

/**
 * Check if Elementor plugin installed and activated.
 * @return bool
 */
function isElementorActive() {

	if ( !function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active( 'elementor-pro/elementor-pro.php' );
}

function getAllMetaEventParamName(){
    $metaEventParamName = array(
        'event_url'=>'Event URL',
        'landing_page'=>'Landing Page URL',
        'post_id'=>'Post ID',
        'post_title'=>'Post Title',
        'post_type'=>'Post Type',
        'page_title' => 'Page Title',
        'content_name'=>'Content Name',
        'content_type'=>'Content Type',
        'categories'=>'Categories',
        'category_name'=>'Category Name',
        'tags'=>'Tags',
        'user_role'=>'User Role',
        'plugin'=>'Plugin',
    );

    return $metaEventParamName;
}

function get_aw_feed_country_codes() {
    $country = [
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BR' => 'Brazil',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo - Brazzaville',
        'CD' => 'Congo - Kinshasa',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GR' => 'Greece',
        'GD' => 'Grenada',
        'GT' => 'Guatemala',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HN' => 'Honduras',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar (Burma)',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'KP' => 'North Korea',
        'MK' => 'North Macedonia',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican City',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    ];

    $result = array_map(
        function($name, $code) {
            return $name . " ($code)";
        },
        $country,
        array_keys($country)
    );
    return array_combine(array_keys($country), $result);
}

function get_aw_feed_language_codes() {
    $language = [
        'AB' => 'Abkhaz',
        'AA' => 'Afar',
        'AF' => 'Afrikaans',
        'AK' => 'Akan',
        'SQ' => 'Albanian',
        'AM' => 'Amharic',
        'AR' => 'Arabic',
        'AN' => 'Aragonese',
        'HY' => 'Armenian',
        'AS' => 'Assamese',
        'AV' => 'Avaric',
        'AE' => 'Avestan',
        'AY' => 'Aymara',
        'AZ' => 'Azerbaijani',
        'BM' => 'Bambara',
        'BA' => 'Bashkir',
        'EU' => 'Basque',
        'BE' => 'Belarusian',
        'BN' => 'Bengali',
        'BH' => 'Bihari',
        'BI' => 'Bislama',
        'BS' => 'Bosnian',
        'BR' => 'Breton',
        'BG' => 'Bulgarian',
        'MY' => 'Burmese',
        'CA' => 'Catalan',
        'CH' => 'Chamorro',
        'CE' => 'Chechen',
        'NY' => 'Chichewa',
        'ZH' => 'Chinese',
        'CV' => 'Chuvash',
        'KW' => 'Cornish',
        'CO' => 'Corsican',
        'CR' => 'Cree',
        'HR' => 'Croatian',
        'CS' => 'Czech',
        'DA' => 'Danish',
        'DV' => 'Divehi',
        'NL' => 'Dutch',
        'DZ' => 'Dzongkha',
        'EN' => 'English',
        'EO' => 'Esperanto',
        'ET' => 'Estonian',
        'EE' => 'Ewe',
        'FO' => 'Faroese',
        'FJ' => 'Fijian',
        'FI' => 'Finnish',
        'FR' => 'French',
        'FF' => 'Fula',
        'GL' => 'Galician',
        'KA' => 'Georgian',
        'DE' => 'German',
        'EL' => 'Greek',
        'GN' => 'Guarani',
        'GU' => 'Gujarati',
        'HT' => 'Haitian',
        'HA' => 'Hausa',
        'HE' => 'Hebrew',
        'HZ' => 'Herero',
        'HI' => 'Hindi',
        'HO' => 'Hiri Motu',
        'HU' => 'Hungarian',
        'IA' => 'Interlingua',
        'ID' => 'Indonesian',
        'IE' => 'Interlingue',
        'GA' => 'Irish',
        'IG' => 'Igbo',
        'IK' => 'Inupiaq',
        'IO' => 'Ido',
        'IS' => 'Icelandic',
        'IT' => 'Italian',
        'IU' => 'Inuktitut',
        'JA' => 'Japanese',
        'JV' => 'Javanese',
        'KL' => 'Kalaallisut',
        'KN' => 'Kannada',
        'KR' => 'Kanuri',
        'KS' => 'Kashmiri',
        'KK' => 'Kazakh',
        'KM' => 'Khmer',
        'KI' => 'Kikuyu',
        'RW' => 'Kinyarwanda',
        'KY' => 'Kyrgyz',
        'KV' => 'Komi',
        'KG' => 'Kongo',
        'KO' => 'Korean',
        'KU' => 'Kurdish',
        'KJ' => 'Kwanyama',
        'LA' => 'Latin',
        'LB' => 'Luxembourgish',
        'LG' => 'Luganda',
        'LI' => 'Limburgish',
        'LN' => 'Lingala',
        'LO' => 'Lao',
        'LT' => 'Lithuanian',
        'LU' => 'Luba-Katanga',
        'LV' => 'Latvian',
        'GV' => 'Manx',
        'MK' => 'Macedonian',
        'MG' => 'Malagasy',
        'MS' => 'Malay',
        'ML' => 'Malayalam',
        'MT' => 'Maltese',
        'MI' => 'Maori',
        'MR' => 'Marathi',
        'MH' => 'Marshallese',
        'MN' => 'Mongolian',
        'NA' => 'Nauru',
        'NV' => 'Navajo',
        'ND' => 'North Ndebele',
        'NE' => 'Nepali',
        'NG' => 'Ndonga',
        'NB' => 'Norwegian Bokmål',
        'NN' => 'Norwegian Nynorsk',
        'NO' => 'Norwegian',
        'II' => 'Nuosu',
        'NR' => 'South Ndebele',
        'OC' => 'Occitan',
        'OJ' => 'Ojibwe',
        'CU' => 'Old Church Slavonic',
        'OM' => 'Oromo',
        'OR' => 'Oriya',
        'OS' => 'Ossetian',
        'PA' => 'Punjabi',
        'PI' => 'Pali',
        'FA' => 'Persian',
        'PL' => 'Polish',
        'PS' => 'Pashto',
        'PT' => 'Portuguese',
        'QU' => 'Quechua',
        'RM' => 'Romansh',
        'RN' => 'Kirundi',
        'RO' => 'Romanian',
        'RU' => 'Russian',
        'SA' => 'Sanskrit',
        'SC' => 'Sardinian',
        'SD' => 'Sindhi',
        'SE' => 'Northern Sami',
        'SM' => 'Samoan',
        'SG' => 'Sango',
        'SR' => 'Serbian',
        'GD' => 'Scottish Gaelic',
        'SN' => 'Shona',
        'SI' => 'Sinhala',
        'SK' => 'Slovak',
        'SL' => 'Slovenian',
        'SO' => 'Somali',
        'ST' => 'Southern Sotho',
        'ES' => 'Spanish',
        'SU' => 'Sundanese',
        'SW' => 'Swahili',
        'SS' => 'Swati',
        'SV' => 'Swedish',
        'TA' => 'Tamil',
        'TE' => 'Telugu',
        'TG' => 'Tajik',
        'TH' => 'Thai',
        'TI' => 'Tigrinya',
        'BO' => 'Tibetan',
        'TK' => 'Turkmen',
        'TL' => 'Tagalog',
        'TN' => 'Tswana',
        'TO' => 'Tongan',
        'TR' => 'Turkish',
        'TS' => 'Tsonga',
        'TT' => 'Tatar',
        'TW' => 'Twi',
        'TY' => 'Tahitian',
        'UG' => 'Uyghur',
        'UK' => 'Ukrainian',
        'UR' => 'Urdu',
        'UZ' => 'Uzbek',
        'VE' => 'Venda',
        'VI' => 'Vietnamese',
        'VO' => 'Volapük',
        'WA' => 'Walloon',
        'CY' => 'Welsh',
        'WO' => 'Wolof',
        'XH' => 'Xhosa',
        'YI' => 'Yiddish',
        'YO' => 'Yoruba',
        'ZA' => 'Zhuang',
        'ZU' => 'Zulu'
    ];

    $result = array_map(
        function($name, $code) {
            return $name . " ($code)";
        },
        $language,
        array_keys($language)
    );
    return array_combine(array_keys($language), $result);
}

function pys_get_option( $option, $default = false ) {
    global $wpdb;

    $table = $wpdb->prefix . 'pys_options';

    // We are trying to get from our table
    $value = $wpdb->get_var( $wpdb->prepare(
        "SELECT option_value FROM {$table} WHERE option_name = %s LIMIT 1",
        $option
    ) );

    if ( $value !== null ) {
        return maybe_unserialize( $value );
    }

    // If it is not in your table, try wp_options
    $value = get_option( $option, $default );

    return $value;
}
function pys_update_option( $option, $value ) {
    global $wpdb;

    $table = $wpdb->prefix . 'pys_options';

    $data = [
        'option_name'  => $option,
        'option_value' => maybe_serialize( $value ),
    ];
    $formats = [ '%s', '%s' ];

    // Update or insert
    $existing = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} WHERE option_name = %s",
        $option
    ) );

    if ( $existing ) {
        $wpdb->update( $table, $data, [ 'option_name' => $option ], $formats, [ '%s' ] );
    } else {
        $wpdb->insert( $table, $data, $formats );
    }

    return true;
}
