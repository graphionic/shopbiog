# Performance Baseline & Bottleneck Map — BiO-G / ShopBiog

This document establishes the comprehensive performance baseline, asset inventory, Core Web Vitals bottleneck map, and prioritized optimization plan for `shopbiog.com`.

---

## 1. Executive Summary & Baseline Metrics

- **Current Active Plugins**: 18 active plugins (Phase 5 complete).
- **Payment Gateways**: Stripe (Primary) + PayPal Payments (Alternative Checkout - Retained per business decision).
- **Database Autoload Size**: **359.80 KB** across 933 options (Canonical `wp_load_alloptions()` measurement standard).
- **Action Scheduler Queue**: 4,870 completed, 617 canceled, 404 failed, 28 pending jobs.
- **Image Optimization Level**: High (Primary product images delivered in WebP).
- **Primary Optimization Goals**: Reduce Core Web Vitals (LCP, CLS, INP), eliminate redundant CSS/JS enqueues, optimize database autoload overhead, and tune caching without breaking checkout or tracking integrity.
- **WP Rocket Page Caching Baseline (Wave 6)**: Active with safe anonymous page caching (`cache_mobile: 1`), dynamic cookie bypass (`woocommerce_items_in_cart`, `wp_woocommerce_session_`), URI exclusions (`/cart/`, `/shopping-cart/`, `/checkout/`, `/my-account/`, `/checkouts/`, `/offer/`, `/wp-json/`, `/wc-api/`), and `ShopBiOG_Cache_Compatibility` layer.


---

## 2. Baseline Route Summary

| Route | URI | Enqueued CSS | Enqueued JS | HTML Size | Primary Bottleneck |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Homepage** | `/` | 28 handles | 22 handles | ~78 KB | `wc-cart-fragments`, Google Fonts render-blocking |
| **Shop Archive** | `/shop/` | 24 handles | 22 handles | ~44 KB | Elessi catalog JS, attribute swatch filtering |
| **Category Archive** | `/product-category/all/` | 24 handles | 22 handles | ~44 KB | Category header assets, AJAX pagination |
| **Simple Product** | `/product/sample/` | 24 handles | 22 handles | ~77 KB | Express payment buttons (Stripe & PayPal JS) |
| **Variable Product** | `/product/variable-test/` | 24 handles | 22 handles | ~77 KB | WooCommerce variation script, swatch calculations |
| **Cart** | `/cart/` | 24 handles | 22 handles | ~45 KB | Cart update AJAX, shipping calculator |
| **Checkout** | `/checkout/` | 24 handles | 22 handles | ~45 KB | Stripe Credit Card Elements, PayPal Smart Buttons |
| **My Account** | `/my-account/` | 24 handles | 22 handles | ~45 KB | Account tab navigation, form validation |
| **FAQ Page** | `/faq/` (or ID 4833) | 24 handles | 22 handles | ~40 KB | Accordion JS, Elementor container styles |
| **Contact Page** | `/contact/` | 24 handles | 22 handles | ~40 KB | `ShopBiOG_Forms` native styling |
| **Landing Page 5885** | `/?page_id=5885` | 24 handles | 22 handles | ~40 KB | Amazon Review widget CSS/JS |

---

## 3. Frontend Asset & Script Ownership Matrix

| Handle / Script | Owner Plugin / Theme | Scope Required | Current Loading Behavior | Optimization Opportunity |
| :--- | :--- | :--- | :--- | :--- |
| `wc-cart-fragments` | WooCommerce Core | Cart / Checkout / Header Cart | Enqueued site-wide | Disable on non-commerce pages or replace with local storage cart drawer count. |
| `stripe.js` | WooCommerce Stripe | Checkout / Product Express Pay | Single Product & Checkout | Retain express pay on product/checkout; prevent loading on non-ecommerce pages. |
| `paypal.js` / `ppcp-smart-button` | WooCommerce PayPal Payments | Checkout / Product Express Pay | Single Product & Checkout | Retain express pay on product/checkout; keep active per business directive. |
| `pys.js` | PixelYourSite PRO | Site-wide | Site-wide | Defer PYS helper JS non-critically. |
| `wprevpro_public.js` | Amazon Reviews | Page ID 5885 only | Conditionally loaded | Enforce strict conditional enqueue only when review widget shortcode is present. |
| `elementor-frontend` | Elementor | Pages built with Elementor | Enqueued on Elementor pages | Enable Elementor Improved Asset Loading & DOM Optimization experiments. |
| `elessi-theme-script` | Elessi Theme (Nasa Core) | Site-wide | Site-wide | Optimize theme mobile drawer & sticky header JS execution. |

---

## 4. Typography & Font Audit

- **Primary Font Family**: `Poppins` (Google Fonts).
- **Enqueued Font Weights**: `300, 300italic, 400, 400italic, 500, 500italic, 600, 600italic, 700, 700italic, 800, 800italic, 900, 900italic` (14 variants).
- **Delivery Source**: `fonts.googleapis.com/css?family=Poppins...`
- **Font Display Setting**: `display=swap` currently present in URL.
- **Bottlenecks Identified**: 14 distinct font variants requested externally in a single request.
- **Future Font Strategy**:
  1. Reduce Poppins variants to only actively used weights (`400, 500, 600, 700`).
  2. Preload primary font files (`woff2`) locally or via preconnect tags to eliminate render-blocking DNS/TLS latency.

---

## 5. Icon Framework Audit

- **Icon Libraries Detected**:
  - `FontAwesome` (Solid & Regular icon fonts enqueued by Elementor/Elessi).
  - `Elessi Native Icons` (Theme icon font).
  - SVG inline icons in child theme components.
- **Bottlenecks Identified**: Multiple icon font requests loading full icon sets for a few header/footer icons.
- **Future Icon Strategy**: Enable Elementor `e_font_icon_svg` experiment to load inline SVGs instead of heavy font files.

---

## 6. Image Delivery & LCP / CLS Bottlenecks

### Image Delivery
- Product grid & gallery images deliver natively in `.webp` format.
- A few homepage static banner fallback images remain in `.png` / `.jpg`.

### LCP (Largest Contentful Paint) Bottlenecks
- **Homepage**: Hero slider banner image. Needs `fetchpriority="high"` and lazy loading disabled on the initial image.
- **Product Page**: Product main feature image. Needs layout dimension reservation (`width` & `height`) and `fetchpriority="high"`.

### CLS (Cumulative Layout Shift) Bottlenecks
- **Dynamic Elements**: Swatch loaders, header cart drawer counts, and sticky navigation headers shifting page content during init.
- **Mitigation Strategy**: Reserve fixed aspect ratios and CSS placeholders for dynamic swatches and gallery images.

---

## 7. Database & Autoload Audit

- **Total Autoloaded Option Size**: **359.80 KB** across 933 options (Canonical `wp_load_alloptions()` measurement standard).
- **Top Autoload Offenders**:
  1. `rewrite_rules`: 60.48 KB
  2. `_transient_wp_core_block_css_files`: 21.74 KB
  3. `theme_mods_elessi-theme-child`: 21.27 KB
  4. `wp_user_roles`: 10.36 KB
  5. `cron`: 8.38 KB
  6. `bwf_gen_config`: 5.74 KB (FunnelKit)
  7. `woocommerce_stripe_settings`: 2.89 KB
  8. `rank-math-options-general`: 2.75 KB
  9. `_transient_ppcp-paypal-bearerppcp-bearer`: 1.55 KB
  10. `yith_woocompare_fields_attrs`: 0.20 KB (*Stale leftover option from uninstalled YITH plugin*)
- **Optimization Opportunities**:
  - Remove stale leftover option `yith_woocompare_fields_attrs`.
  - Flush expired transients in `wp_options`.

---

## 8. Action Scheduler & Background Cron Audit

- **Action Scheduler Status Breakdown**:
  - Complete: 4,870 jobs
  - Canceled: 617 jobs
  - Failed: 404 jobs
  - Pending: 28 jobs
- **Top Job Volume**:
  - `facebook_for_woocommerce_process_logs_batch`: 2,261 complete jobs
  - `woocommerce_cancel_unpaid_orders`: 686 complete jobs
  - `rank_math/analytics/get_inspections_data`: 611 canceled jobs
  - `gla/jobs/resubmit_expiring_products/process_item`: 300 failed jobs
- **Optimization Strategy**:
  - Purge completed and failed Action Scheduler logs.
  - Tune Meta for WooCommerce log processing interval.

---

## 9. Security & Firewall Audit (Wordfence)

- **Wordfence Status**: Active (`wordfence/wordfence.php`).
- **Configuration**: Standard Web Application Firewall (WAF) & malware scan.
- **Frontend Impact**: Minimal frontend asset overhead (0 heavy scripts enqueued on client).
- **Recommendation**: Maintain active WAF; optimize scan frequency to off-peak hours.

---

## 10. Elementor Experiments Audit

- **Container (Flexbox Layouts)**: `active`
- **DOM Optimization (`e_optimized_markup`)**: `active` (Activated in Phase 6B Wave 3; reduced 101 DOM nodes across core routes)
- **Inline Font Icons (`e_font_icon_svg`)**: `active` (Activated in Phase 6B Wave 2)
- **Element Cache (`e_element_cache`)**: `DEFERRED` (Deferred per business/performance rule to avoid caching dynamic WooCommerce price, stock, or cart data)
- **Improved Asset Loading (`e_optimized_assets`)**: `native core` (Standardized core behavior in Elementor 3.34.0)
- **CSS Print Method**: `external` (External CSS file delivery enabled)

---

## 11. WP Rocket Readiness & Caching Plan

- **Plugin State**: Installed but inactive (`wp-content/plugins/wp-rocket/`).
- **Pre-Activation Exclusion Rules**:
  - Exclude WooCommerce Cart (`/cart/`), Checkout (`/checkout/`), My Account (`/my-account/`).
  - Exclude FunnelKit checkout endpoints.
  - Exclude Stripe & PayPal webhook endpoints.
  - Exclude PYS CAPI REST endpoints (`/wp-json/pys-facebook/v1/event`).
- **Activation Roadmap**: Reserve WP Rocket activation for Wave 6 after asset and database optimizations are completed.

---

## 12. Performance Priority Matrix

| Optimization | Priority | Expected Benefit | Risk | Complexity | Routes Affected | Implementation Location |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Purge Expired Transients & Stale Autoload Options** | **P0** | Reduces DB autoload size by ~25KB | Low | Low | All Routes | Database / `ShopBiOG Core` |
| **Optimize Google Fonts Variants** | **P0** | Reduces font payload by ~60% | Low | Low | All Routes | `elessi-theme-child` / `ShopBiOG Core` |
| **Disable `wc-cart-fragments` on Non-Commerce Pages** | **P0** | Prevents AJAX polling on static pages | Low | Low | Non-Commerce Pages | `shopbiog-core/modules/performance/` |
| **Enable Elementor DOM & SVG Experiments** | **P1** | Reduced HTML DOM depth by 101 nodes | Low | Medium | Page Builder Pages | Elementor Settings |
| **Purge Failed/Completed Action Scheduler Logs** | **P1** | Reduces DB table size & query latency | Low | Low | Admin / Background | Database |
| **LCP Image Priority & Dimension Hints** | **P1** | Improves LCP score by ~300ms | Medium | Medium | Homepage / Single Product | `elessi-theme-child` |
| **Configure & Activate WP Rocket Caching** | **P2** | Dramatically improves TTFB & page load speed | Medium | High | All Public Routes | WP Rocket Settings |

---

## 13. Proposed Phase 6 Implementation Waves

- **Wave 1: Safe Conditional Asset Loading & DB Autoload Cleanup** (**COMPLETE**)
  - Purge stale autoload options (`yith_woocompare_fields_attrs`) and expired transients.
  - Conditionally unload non-commerce scripts (`wc-cart-fragments`) on static pages.
- **Wave 2: Font & Icon Optimization** (**COMPLETE**)
  - Reduce Poppins font weights to required variants (`400, 500, 600, 700, 800, 900`).
  - Enable Elementor Inline Font Icons (`e_font_icon_svg`).
- **Wave 3: Elementor Experiment Tuning & DOM Optimization** (**COMPLETE**)
  - Activated `e_optimized_markup` alone; eliminated wrapper div bloat (-101 total DOM nodes).
  - Evaluated `e_element_cache` -> DEFERRED (safeguarding dynamic WooCommerce price/stock/cart data).
- **Wave 4: LCP / CLS Image & Gallery Optimization** (**COMPLETE**)
  - Created `ShopBiOG_Image_Performance` module enforcing `fetchpriority="high"` and lazy-loading exclusion on true LCP hero & product images.
  - Enforced intrinsic width/height attributes for icon images, certificates, and 404 placeholder.
  - Added CSS layout aspect-ratio reservation for product gallery (`595 / 760`), thumbnails (`117 / 150`), product cards (`350 / 447`), and header cart badge counters (`min-width: 1.5rem`), eliminating CLS during JS initialization.
- **Wave 5: Action Scheduler Log Cleanup & Background Task Tuning** (**COMPLETE**)
  - Created `ShopBiOG_Database_Maintenance` module in `shopbiog-core/modules/admin/`.
  - Safely purged 183 historical completed actions, 24 canceled actions, 295 resolved GLA failed actions, and associated scheduler logs older than 30 days via Action Scheduler API.
  - Unscheduled 15 orphaned WP-Cron events (including `wplmi/fetch_plugin_data`, `wpcode_usage_tracking_cron`, `ai1wm_storage_cleanup`).
  - Preserved 25 active pending background actions for Meta, Google, TikTok, and WooCommerce order processing.
- **Wave 6: Controlled WP Rocket Activation & Exclusion Setup** (**NEXT UP**)
  - Configure safe caching rules with cart, checkout, CAPI, and payment exclusions.



