# ShopBiOG Clean-Slate Rebuild Dependency & Architecture Audit

> [!IMPORTANT]
> **AUDIT ONLY — READ-ONLY ASSESSMENT**  
> This document presents an empirical, evidence-based dependency audit of the ShopBiOG WordPress/WooCommerce installation. No themes or plugins have been deactivated or modified during this audit. It defines the blueprint for transitioning ShopBiOG from a heavy, theme-dependent site (Elessi + nasa-core + Elementor Pro + FunnelKit) to a lean, custom-architected e-commerce storefront centered around a standalone **`biog` custom theme** and **`shopbiog-core` functionality plugin**.

---

## 1. Executive Summary

- **Current Active Theme**: `elessi-theme-child` (Parent: `elessi-theme` v6.2.8)
- **Current Active Plugins**: **18** active plugins (Down from 26 in Phase 5)
- **Target Active Plugins**: **10** clean-slate plugins (64% reduction in software overhead)
- **Key Architectural Shifts**:
  1. **Elessi Theme & `nasa-core`**: Completely retired. Replaced by a lightweight, standalone, custom-built `biog` theme that owns all header, footer, product card, shop catalog, single product, cart drawer, and checkout templates natively in PHP.
  2. **FunnelKit (Builder & Pro)**: **100% REMOVABLE**. Audit proves FunnelKit global checkout override is disabled (`wffn_global_checkout` is `false`). Native WooCommerce checkout page (ID 10) is already active. Stripe and PayPal operate natively. Bumps/upsells are unused.
  3. **Elementor Pro**: **100% REMOVABLE**. Header, footer, single product, and catalog templates will be owned by the native `biog` theme. Popups and dynamic features will be handled cleanly by `biog` / `shopbiog-core`.
  4. **Elementor (Free)**: Retained strictly as an optional visual page builder for static marketing pages (e.g., About Us, Privacy Policy, Terms, Landing 5885 content blocks). Elementor will NOT own e-commerce structural templates.
  5. **Core Business Data & Tracking**: 100% preserved. All products, variations, orders, customers, reviews, Rank Math SEO data, PixelYourSite tracking tags, Google Listings & Ads catalog feeds, Stripe, and PayPal settings remain untouched and fully compatible.

---

## 2. KEEP (Target Core Stack: 10 Plugins)

The following plugins represent essential business infrastructure and will be retained in the clean-slate architecture:

| Plugin Slug | Plugin Name | Version | Purpose & Justification |
| :--- | :--- | :--- | :--- |
| `woocommerce` | WooCommerce | 9.3.3 | Core e-commerce engine (products, orders, checkout, customer management). |
| `woocommerce-gateway-stripe` | WooCommerce Stripe Gateway | 8.8.0 | Primary credit card gateway, Apple Pay, Google Pay express checkout. |
| `woocommerce-paypal-payments` | WooCommerce PayPal Payments | 2.9.2 | Primary PayPal gateway, Pay Later, Venmo integration. |
| `google-listings-and-ads` | Google Listings & Ads | 2.8.6 | Syncs product catalog feed to Google Merchant Center & manages Shopping ads. |
| `pixelyoursite` | PixelYourSite | 9.6.6 | Handles Meta Pixel, Meta CAPI, GA4, and TikTok event tracking natively via WC hooks. |
| `seo-by-rank-math` | Rank Math SEO | 1.0.231 | Controls SEO meta titles, descriptions, Product Schema (`@type: Product`), sitemaps, 301 redirects. |
| `wp-rocket` | WP Rocket | 3.19.2.1 | Page caching, browser caching, and static asset delivery optimization. |
| `wordfence` | Wordfence Security | 7.11.7 | Web Application Firewall (WAF), malware scanner, login security. |
| `shopbiog-core` | ShopBiOG Core | 1.0.0 | Project-owned custom plugin for forms, testimonials, image performance, cache safety, maintenance. |
| `elementor` | Elementor (Free) | 3.24.5 | Retained exclusively for simple visual composition on static marketing pages. |

---

## 3. REPLACE (3 Items)

The following themes and plugins will be replaced by native code in the new `biog` custom theme or `shopbiog-core`:

| Legacy Item | Current Role | Future Owner | Replacement Implementation Plan |
| :--- | :--- | :--- | :--- |
| **`elessi-theme` & `elessi-theme-child`** | Parent & child theme owning headers, styling, & template overrides | **BiO-G Custom Theme (`biog`)** | Standalone custom WP theme built from scratch with clean, high-performance HTML5/CSS3 and native PHP WooCommerce templates. |
| **`nasa-core`** | Theme companion plugin (swatches, mini-cart, search, static blocks) | **BiO-G Custom Theme + `shopbiog-core`** | Header/footer, catalog grid, variation swatches, and mini-cart drawer built natively into `biog`. AJAX search and trust modules in `shopbiog-core`. |
| **`yith-woocommerce-frequently-bought-together`** | Frequently bought together product bundle widget | **BiO-G Custom Theme** | Native WooCommerce Cross-sells / Up-sells displayed as a refined D2C bundle box directly inside the product purchase panel. |

---

## 4. REMOVE (4 Plugins)

The following plugins are non-essential, redundant, or unused, and will be deleted during the clean-slate migration:

| Plugin Slug | Plugin Name | Current Status | Removal Feasibility & Rationale |
| :--- | :--- | :--- | :--- |
| `funnelkit-checkout-builder` | FunnelKit Checkout Builder | ACTIVE (Unused Override) | **SAFE**. Global checkout override option `wffn_global_checkout` is `false`. Native WC checkout (page 10) is active. |
| `funnelkit-checkout-builder-pro` | FunnelKit Builder Pro | ACTIVE (Unused Bumps) | **SAFE**. Order bump 9105 and upsell funnel 9106 have 0 order items attached in WooCommerce history. |
| `elementor-pro` | Elementor Pro | ACTIVE (Legacy Templates) | **SAFE AFTER REPLACEMENT**. All Pro dependencies (Theme Builder, Popups) replaced by native `biog` theme templates. |
| `yith-woocommerce-compare` | YITH WooCommerce Compare | ACTIVE (Unused Feature) | **SAFE**. Product comparison tables are irrelevant for sock/lifestyle D2C conversion. |

---

## 5. TEMPORARY / REQUIRES DECISION (1 Plugin)

| Plugin Slug | Plugin Name | Current Status | Decision Criteria |
| :--- | :--- | :--- | :--- |
| `wp-reviews-plugin-for-google` | Google Reviews Plugin | ACTIVE | **TEMPORARY**. Retain if live Google Business reviews badge on Homepage/Landing 5885 is desired, or replace with a static/cached `shopbiog-core` review block. |

---

## 6. FunnelKit — Deep Usage Audit Findings

### Empirical Database Audit Evidence:
- **Global Checkout Setting**: `wffn_checkout_page_id` = `false`, `wffn_global_checkout` = `false`.
- **Active Checkout Page**: Native WooCommerce Checkout Page ID `10` (`Checkout`). FunnelKit is **NOT** handling live checkout routing.
- **FunnelKit Objects**:
  - `wfacp_checkout` ID 9104 ('Checkout Page'): Isolated template post.
  - `wfob_bump` ID 9105 ('Order Bump'): Status `publish`, but attached to 9104 (which is bypassed).
  - `wfocu_funnel` ID 9106 ('Upsells'): Configured step list, no active funnel routing.
  - `wfocu_offer` ID 9107 ('Offer'): Isolated offer post.
  - `wffn_ty` ID 9108 ('Thank you Page'): Template post. Native WC Thank You page handles live orders.
- **Order Evidence**:
  - WooCommerce order items containing bump metadata: **0**.
  - Historical WooCommerce orders processed via FunnelKit checkout: **0**.
- **Payment Gateways**: Stripe and PayPal operate 100% independently through standard WooCommerce hooks (`woocommerce_checkout_order_processed`, `woocommerce_payment_complete`).
- **REST Endpoints & External Integrations**: None required by tracking or payment systems.

> [!NOTE]  
> **FUNNELKIT REMOVAL FEASIBILITY: SAFE**  
> FunnelKit plugins (`funnelkit-checkout-builder` & `funnelkit-checkout-builder-pro`) can be completely deactivated and uninstalled without affecting checkout functionality, payment processing, or order tracking.

---

## 7. Elementor Pro — Deep Usage Audit Findings

### Empirical Database Audit Evidence:
- **`elementor_library` Templates**: 8 total items.
  - ID 14: Default Kit
  - ID 4833: FAQ section block
  - ID 4948: Popup 1 (Exit intent / promo popup)
  - ID 4963: Popup 2 (Draft popup)
  - ID 5392, 5396, 5401, 5473: Static section/page blocks
- **Pro Widgets in Use**: `nav-menu`, `form`, `popup`, `woocommerce-cart`, `woocommerce-checkout`.
- **Custom Code Snippets (`elementor_snippet`)**: **0**.
- **Custom CSS Entries**: 6 minor styling snippets.

### Replacement Blueprint:
1. **Header & Footer**: Replaced by native PHP templates in `biog` theme (`header.php`, `footer.php`).
2. **Single Product Page**: Replaced by native `single-product.php` template in `biog` theme.
3. **Shop & Catalog Grid**: Replaced by native `archive-product.php` template in `biog` theme.
4. **Cart Drawer & Checkout**: Replaced by native WC templates in `biog` theme.
5. **Popups / Modal Dialogs**: Replaced by a 20-line lightweight native JS/CSS modal module in `shopbiog-core`.

> [!NOTE]  
> **ELEMENTOR PRO REMOVAL FEASIBILITY: SAFE AFTER REPLACEMENT**  
> Elementor Pro can be completely uninstalled once the native `biog` theme templates are activated. Elementor Free remains to edit standard static text/image content on marketing pages.

---

## 8. Elessi / Nasa-Core — Deep Usage Audit Findings

| Elessi / Nasa Feature | Current Implementation | Active Usage | Clean-Slate Owner | Rebuild Action |
| :--- | :--- | :--- | :--- | :--- |
| **Desktop Header** | Elessi Header Builder / `nasa-core` | YES | **BiO-G Theme** | Rebuild as clean native `header.php`. |
| **Mobile Drawer** | Elessi Mobile Drawer JS/CSS | YES | **BiO-G Theme** | Rebuild as responsive CSS/JS slide-out drawer. |
| **Mega Menu** | `nasa-core` custom menu walker | YES | **BiO-G Theme** | Rebuild using standard WP `wp_nav_menu()`. |
| **AJAX Product Search**| `nasa-core` AJAX search modal | YES | **ShopBiOG Core** | Rebuild via lightweight REST API endpoint + JS dropdown. |
| **Shop Catalog Grid** | Elessi WC loop overrides | YES | **BiO-G Theme** | Rebuild as clean flex/grid in `archive-product.php`. |
| **Variation Swatches** | `nasa-core` swatches engine | YES | **BiO-G Theme** | Rebuild as native CSS color/size swatch selector. |
| **Mini Cart Drawer** | `nasa-core` mini-cart drawer | YES | **BiO-G Theme** | Rebuild using WooCommerce AJAX cart fragments. |
| **Wishlist & Compare** | `nasa-core` & YITH Compare | NO | **None** | **REMOVE WITHOUT REPLACEMENT**. |
| **Quick View** | `nasa-core` quickview modal | NO | **None** | **REMOVE WITHOUT REPLACEMENT**. |
| **Static Blocks** | CPT `nasa_block` (13 posts) | YES | **BiO-G Theme / WP Editor**| Convert active blocks into theme template parts or Gutenberg reusable blocks. |

---

## 9. Conversion Feature Findings

- **Viewing-Now Counter**: Currently disabled in `nasa_opt`. If desired, can be implemented in `shopbiog-core` as an authentic inventory/activity indicator.
- **Recent Purchase Popups**: Synthetic popups disabled. Will rely on authentic Google Reviews (`wp-reviews-plugin-for-google`) and verified buyer badges.
- **Free Shipping Threshold Bar**: High-converting D2C feature. Will be built directly into the `biog` theme mini-cart drawer ($X away from Free USA Shipping).
- **Product Bundles / Frequently Bought Together**: Replaced by native WooCommerce Cross-sells cleanly styled inside `biog` product page templates.

---

## 10. Payment Findings

- **Stripe (`woocommerce-gateway-stripe` v8.8.0)**:
  - Status: **ACTIVE & FULLY FUNCTIONAL**.
  - Apple Pay / Google Pay express buttons enabled.
  - Integrates natively with WooCommerce checkout hooks (`woocommerce_checkout_order_processed`).
  - Zero dependency on FunnelKit or Elessi.
- **PayPal (`woocommerce-paypal-payments` v2.9.2)**:
  - Status: **ACTIVE & FULLY FUNCTIONAL**.
  - Supports PayPal Smart Buttons & Pay Later financing.
  - Integrates natively with WooCommerce checkout hooks.
  - Zero dependency on FunnelKit or Elessi.

---

## 11. Tracking Findings

- **PixelYourSite (`pixelyoursite` v9.6.6)**:
  - Manages Meta Pixel, Meta Conversions API (CAPI), GA4, and TikTok Pixel.
  - Fires standard e-commerce events (`ViewContent`, `AddToCart`, `InitiateCheckout`, `Purchase`) by hooking into core WooCommerce actions (`woocommerce_add_to_cart`, `woocommerce_thankyou`).
  - Fully decoupled from theme markup; zero impact from theme replacement.
- **Google Listings & Ads (`google-listings-and-ads` v2.8.6)**:
  - Manages Google Merchant Center product catalog XML/JSON sync.
  - Reads directly from WooCommerce database (`wp_posts` type `product`). Fully decoupled from presentation layer.

---

## 12. Database Cleanup Candidates

After deploying the clean-slate `biog` theme and deactivating legacy plugins, the following database tables, options, and metadata can be safely purged in a dedicated database maintenance phase:

### 12.1 Custom Database Tables to Drop:
- `wp_bwf_ab_experiments`
- `wp_bwf_contact`
- `wp_bwf_contact_meta`
- `wp_bwf_conversion_tracking`
- `wp_bwf_funnelmeta`
- `wp_bwf_funnels`
- `wp_bwf_optin_entries`
- `wp_bwf_wc_customers`

### 12.2 Obsolete Options to Delete:
- `nasa_opt`
- `theme_mods_elessi-theme`
- `theme_mods_elessi-theme-child`
- `wffn_*`, `bwf_*`, `woofunnels_*`
- `yith_woocompare_*`

### 12.3 Obsolete Post Types & Posts to Purge:
- `elementor-hf` (Legacy Header/Footer templates)
- `nasa_block` (Legacy static blocks)
- `wffn_landing`, `wffn_checkout`, `wffn_bump`, `wffn_funnel`, `wffn_offer`, `wffn_ty`, `wfacp_checkout`, `wfob_bump`, `wfocu_funnel`, `wfocu_offer`

---

## 13. Target Architecture

```
  ┌─────────────────────────────────────────────────────────────┐
  │                 BIO-G CUSTOM THEME (`biog`)                 │
  │   - Standalone parent theme (Zero Elessi / parent dependency)│
  │   - Owns: header.php, footer.php, single-product.php,       │
  │           archive-product.php, cart.php, checkout.php       │
  │   - CSS: BEM methodology, CSS Custom Properties             │
  │   - JS: Native ES6 modules, zero jQuery dependencies        │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 SHOPBIOG CORE FUNCTIONALITY                 │
  │   - Location: wp-content/plugins/shopbiog-core/             │
  │   - Owns: ShopBiOG_Forms, ShopBiOG_Testimonials, AJAX Search,│
  │           Image Performance, Cache Compatibility, Modal UI   │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 WOOCOMMERCE CORE & PAYMENT ENGINE           │
  │   - Core: WooCommerce v9.3.3                                │
  │   - Payments: Stripe Gateway + PayPal Complete Payments     │
  │   - Tracking: PixelYourSite + Google Listings & Ads         │
  │   - SEO: Rank Math SEO                                      │
  └─────────────────────────────────────────────────────────────┘
```

---

## 14. Recommended Build Sequence

1. **Phase 7C: Standalone `biog` Theme Scaffold**: Create `wp-content/themes/biog/` with proper `style.css`, `functions.php`, asset pipeline, and design token integration (`--biog-*`).
2. **Phase 7D: Native Header & Navigation**: Build responsive desktop header, mobile drawer navigation, and announcement bar in `biog/header.php`.
3. **Phase 7E: Single Product Page Template**: Build high-converting native `biog/single-product.php` with swatches, gallery, trust badges, accordion details, and sticky mobile Add to Cart bar.
4. **Phase 7F: Shop Catalog & Category Templates**: Build native `biog/archive-product.php` catalog grid (`350:447` ratio), filter sidebar, and sorting controls.
5. **Phase 7G: Native Cart Drawer & Checkout Experience**: Build native `biog/cart/mini-cart.php` drawer and clean `biog/page-checkout.php` template for Stripe & PayPal.
6. **Phase 7H: Homepage & Static Page Migration**: Rebuild homepage using clean Elementor Free sections + native theme hero.
7. **Phase 7I: Legacy Deactivation & Plugin Cleanup**: Deactivate `elessi-theme`, `nasa-core`, `elementor-pro`, `funnelkit-checkout-builder`, `funnelkit-checkout-builder-pro`, `yith-woocommerce-compare`.
8. **Phase 7J: Final Performance & Database Purge**: Execute DB cleanup of legacy options, tables, and CPTs; run full Web Vitals validation.

---

> [!NOTE]  
> **AUDIT COMPLETE**: All findings are grounded in verified runtime database checks. Ready for Phase 7C implementation upon approval.
