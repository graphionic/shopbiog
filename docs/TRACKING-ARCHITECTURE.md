# Tracking & Catalog Architecture — BiO-G / ShopBiog

This document defines the verified analytics, event tracking, Conversions API (CAPI), and catalog synchronization architecture for `shopbiog.com`.

---

## 1. System Responsibility & Separation Matrix

To prevent event duplication and script bloat, systems are strictly separated by role:

| System / Plugin | Responsible Role | Scope & Description |
| :--- | :--- | :--- |
| **PixelYourSite PRO** | **TRACKING ROLE (Browser & CAPI)** | Unified engine for Meta Pixel + CAPI and GA4 enhanced ecommerce tracking. |
| **Meta for WooCommerce** | **CATALOG ROLE** | Syncs WooCommerce products to Meta Catalog (Facebook/Instagram Shop). Browser pixel disabled via `ShopBiOG Core`. |
| **Google for WooCommerce** | **CATALOG & ADS ROLE** | Syncs product feed to Google Merchant Center and connects Google Ads conversion tags. |
| **TikTok for Business** | **CATALOG & TRACKING ROLE** | Handles TikTok product catalog feed and TikTok Pixel / Event tracking. |
| **ShopBiOG Core** | **INTEGRATION BRIDGE** | Enforces deduplication filters (e.g. `facebook_for_woocommerce_integration_pixel_enabled` => `false`). |

---

## 2. Event Ownership Matrix

| Platform | Event | Responsible System | Browser? | Server (CAPI)? | Catalog Dependency? | Duplication Prevention Rule |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Meta** | `PageView` | PixelYourSite PRO | Yes | Yes (CAPI) | No | Meta for Woo pixel disabled via filter in `ShopBiOG Core`. |
| **Meta** | `ViewContent` | PixelYourSite PRO | Yes | Yes (CAPI) | Product IDs match | Native PYS handler. |
| **Meta** | `AddToCart` | PixelYourSite PRO | Yes | Yes (CAPI) | Content IDs match | Handled natively by PYS. Custom WPCode click listener #9182 removed to eliminate double-firing. |
| **Meta** | `InitiateCheckout` | PixelYourSite PRO | Yes | Yes (CAPI) | Content IDs match | Native PYS handler. |
| **Meta** | `Purchase` | PixelYourSite PRO | Yes | Yes (CAPI) | Order items match | Native PYS handler. |
| **GA4** | `page_view` | PixelYourSite PRO | Yes | No | No | Configured via GA4 Measurement ID in PYS. Raw IHAF script header removed. |
| **GA4** | `view_item` | PixelYourSite PRO | Yes | No | Item IDs match | Native PYS handler. |
| **GA4** | `add_to_cart` | PixelYourSite PRO | Yes | No | Item IDs match | Native PYS handler. |
| **GA4** | `begin_checkout` | PixelYourSite PRO | Yes | No | Item IDs match | Native PYS handler. |
| **GA4** | `purchase` | PixelYourSite PRO | Yes | No | Item IDs match | Native PYS handler. |
| **TikTok** | `PageView` | TikTok for Business | Yes | No | No | Managed via TikTok for Business plugin. |
| **TikTok** | `ViewContent` | TikTok for Business | Yes | No | Product IDs match | Managed via TikTok for Business plugin. |
| **TikTok** | `AddToCart` | TikTok for Business | Yes | No | Product IDs match | Managed via TikTok for Business plugin. |
| **TikTok** | `InitiateCheckout` | TikTok for Business | Yes | No | Product IDs match | Managed via TikTok for Business plugin. |
| **TikTok** | `Purchase` | TikTok for Business | Yes | Yes | Order items match | Managed via TikTok for Business plugin. |

---

## 3. WPCode Consolidation Summary

1. **WPCode Snippet #9180** (`FB4WC pixel OFF`):
   - Logic: `add_filter('facebook_for_woocommerce_integration_pixel_enabled', '__return_false');`
   - Migrated to: `wp-content/plugins/shopbiog-core/modules/integrations/class-meta-integration.php`.
   - Result: Meta for WooCommerce continues syncing product feeds to Meta Catalog without double-firing browser pixel events.

2. **WPCode Snippet #9182** (`Meta & GA4 AddToCart Click Bridge`):
   - Verified: PixelYourSite PRO natively captures single product AddToCart, variable products, and shop archive AJAX AddToCart events.
   - Result: Option A selected — redundant #9182 click bridge removed. Eliminates duplicate Meta and GA4 AddToCart events.

3. **WPCode IHAF Header Tag (`ihaf_insert_header`)**:
   - Consolidated GA4 Measurement ID into `PixelYourSite PRO` (`pys_ga` module).
   - Result: Clean, native GA4 enhanced ecommerce tracking without raw header script injections.

4. **WPCode Lite Removal**:
   - Plugin deactivated and removed (`wp-content/plugins/insert-headers-and-footers/`).
   - Active plugins reduced to **18**.
