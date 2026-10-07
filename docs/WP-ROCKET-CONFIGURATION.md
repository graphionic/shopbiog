# WP Rocket Configuration & Cache Exclusions (ShopBiOG)

## Overview
This document specifies the official production caching policy and WP Rocket configuration matrix for the ShopBiOG WooCommerce store. The configuration enforces **Correctness First, Cache Safety Second, Performance Third** to safeguard ecommerce sessions, checkout flows, tracking scripts, and dynamic pricing.

---

## 1. Retained Setting Matrix

| Setting Category | Setting Key | Value | Status | Rationale / Decision |
| :--- | :--- | :--- | :--- | :--- |
| **Page Cache** | `cache_mobile` | `1` | **ENABLED** | Mobile page caching enabled. |
| | `do_caching_mobile_files` | `1` | **ENABLED** | Separate cache files for mobile devices. |
| | `cache_logged_user` | `0` | **DISABLED** | Prevent cross-contamination of logged-in user sessions. |
| | `cache_ssl` | `1` | **ENABLED** | HTTPS caching active. |
| | `purge_cron_interval` | `10` | **ENABLED** | 10-hour cache purge interval. |
| **File Optimization** | `minify_css` | `0` | **DISABLED** | Elessi theme and Elementor already ship minified assets. |
| | `optimize_css_delivery` | `0` | **DISABLED** | Avoid layout shifts and CSS parsing overhead. |
| | `remove_unused_css` | `0` | **DEFERRED** | SaaS API dependency unavailable on localhost; dynamic classes used by Woo/swatches/popups. |
| | `minify_js` | `0` | **DISABLED** | Core JS files already pre-minified. |
| | `defer_all_js` | `0` | **DISABLED** | Avoid inline jQuery execution order issues. |
| | `delay_js` | `0` | **DEFERRED** | High-risk option deferred to future Core Web Vitals review. |
| **Media & LazyLoad** | `lazyload` | `0` | **DISABLED** | Native browser lazyloading + `ShopBiOG_Image_Performance` LCP priority handles images cleanly. |
| | `lazyload_iframes` | `0` | **DISABLED** | No iframe embeds on core routes. |
| | `image_dimensions` | `0` | **DISABLED** | Handled natively by ShopBiOG Core gallery aspect ratios. |
| **Preload & CDN** | `manual_preload` | `0` | **DISABLED** | Prevents WAMP loopback server load. |
| | `preload_links` | `0` | **DISABLED** | Avoid unnecessary speculative background fetches. |
| | `cdn` | `0` | **DISABLED** | No CDN active on local environment. |
| **Heartbeat** | `control_heartbeat` | `1` | **ENABLED** | Reduces admin/editor/frontend Heartbeat frequency to 120s. |
| **Database Cleanup**| `database_*` | `0` | **DISABLED** | Controlled database hygiene handled via `ShopBiOG_Database_Maintenance`. |

---

## 2. Dynamic Cache Exclusions & Safeguards

### URL Exclusions (`rocket_cache_reject_uri`)
- `/cart/(.*)` & `/shopping-cart/(.*)`: WooCommerce Cart page
- `/checkout/(.*)`: WooCommerce Checkout page
- `/my-account/(.*)`: WooCommerce Customer Portal
- `/checkouts/(.*)`: FunnelKit Express & Custom Checkout Funnels (ID 9104)
- `/offer/(.*)` & `/upsell/(.*)` & `/thank-you/(.*)`: FunnelKit Order Bumps, Upsells, and Thank You Pages (IDs 9106, 9107, 9108)
- `/wp-json/(.*)`: WordPress REST API, PixelYourSite CAPI, WooCommerce REST
- `/wc-api/(.*)`: WooCommerce Payment Webhooks (Stripe IPN, PayPal IPN)

### Cookie Exclusions (`rocket_cache_reject_cookies`)
- `woocommerce_items_in_cart`: Triggers dynamic rendering when items are added to cart
- `woocommerce_cart_hash`: Bypasses page cache when cart state modifies
- `wp_woocommerce_session_`: Bypasses page cache during active WooCommerce customer sessions

### Code Safety Module
Compatibility filters are enforced in project code:
`wp-content/plugins/shopbiog-core/modules/performance/class-cache-compatibility.php`

---

## 3. Production Deployment Differences

1. **Preload Bot**: Can be safely enabled in production if server resources permit.
2. **CDN Integration**: Enable CDN CNAME rewrites in production if a CDN (e.g. Cloudflare, CloudFront) is attached.
3. **RUCSS (Remove Unused CSS)**: Test carefully on staging with public HTTPS URL before enabling in production.

---

## 4. Rollback Procedure
If caching issues occur in production:
1. Deactivate WP Rocket: `wp plugin deactivate wp-rocket` via CLI or Admin.
2. Delete cache directory: `rm -rf wp-content/cache/wp-rocket/*`.
3. `ShopBiOG_Cache_Compatibility` module remains active without side effects.
