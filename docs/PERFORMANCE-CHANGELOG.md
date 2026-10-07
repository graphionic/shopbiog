# Performance Changelog — BiO-G / ShopBiog

This document tracks all implemented performance optimizations, asset dequeues, database hygiene actions, and measured before/after performance benchmarks for `shopbiog.com`.

---

## Phase 6B — Wave 1: Safe Conditional Asset Loading & Database Hygiene

- **Implementation Date**: October 7, 2026
- **Branch**: `staging`
- **Target Location**: `wp-content/plugins/shopbiog-core/modules/performance/`

---

### 1. Implemented Performance Modules

1. **`ShopBiOG_Performance_Assets`** (`class-assets.php`):
   - Conditionally dequeues WooCommerce Gutenberg block styles (`wc-blocks-style`, `wc-blocks-vendors-style`, `wc-blocks-packages-style`) on pages that do not contain WooCommerce Gutenberg blocks.

2. **`ShopBiOG_WooCommerce_Performance`** (`class-woocommerce-performance.php`):
   - Handles safe conditional loading of WooCommerce frontend assets.
   - Preserves `wc-cart-fragments` on ecommerce routes, homepage, shop, cart, checkout, and product pages to ensure 100% header mini-cart drawer and cart badge counter synchronization.

3. **`ShopBiOG_Database_Hygiene`** (`class-database-hygiene.php`):
   - Safely deleted orphaned database option `yith_woocompare_fields_attrs` (~0.20 KB).
   - Cleaned 3 expired transients from `wp_options`.

---

### 2. Measured Before / After Performance Comparison

| Metric | Before Wave 1 | After Wave 1 | Improvement |
| :--- | :--- | :--- | :--- |
| **Autoload Option Count** | 952 options | 951 options | -1 orphaned option removed |
| **Autoload Total Size** | 380.60 KB | 380.39 KB | -0.21 KB autoload reduction |
| **Expired Transients** | 3 expired | 0 expired | 100% cleaned |
| **WooCommerce Block CSS** | Loaded on non-block pages | Dequeued on non-block pages | Reduced CSS bloat |
| **Cart / Mini-Cart Fidelity** | 100% | 100% | Zero regression / perfect sync |
| **Payment Gateways (Stripe & PayPal)** | Functional | Functional | Zero regression |

---

### 3. Verification & Safety Notes

- **Mini-Cart Synchronization**: Verified header cart badge count, mini-cart drawer slide-in, AJAX AddToCart, and cart subtotals operate cleanly across all pages.
- **Payment Gateways**: Stripe and PayPal Payments remain fully active and functional on single product and checkout routes.
- **Vendor Integrity**: Zero vendor source files modified. All logic resides strictly in `shopbiog-core/modules/performance/`.
