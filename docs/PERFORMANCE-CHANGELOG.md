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

---

## Phase 6B — Wave 2: Font & Icon Optimization

- **Implementation Date**: October 7, 2026
- **Branch**: `staging`
- **Target Location**: `wp-content/plugins/shopbiog-core/modules/performance/class-assets.php`

---

### 1. Implemented Optimizations

1. **Canonical Autoload Size Reconciliation**:
   - Established WordPress native API `wp_load_alloptions()` query (`WHERE autoload IN ('yes', 'on', '1', 'auto')`) as the canonical measurement standard.
   - Reconciled row count: **951 autoloaded options** (~380.39 KB raw value size / ~382.73 KB serialized).

2. **Wave 1 CSS Handle Reconciliation**:
   - Verified 3 WooCommerce block CSS handles (`wc-blocks-style`, `wc-blocks-vendors-style`, `wc-blocks-packages-style`) dequeued on non-block pages.
   - 4 project-owned presentation layer CSS handles (`shopbiog-child-base`, `shopbiog-child-components`, `shopbiog-child-woocommerce`, `shopbiog-child-responsive`) enqueued in child theme.

3. **Google Fonts Poppins Weight Optimization**:
   - Filtered `nasa_google_font_weight` in `ShopBiOG_Performance_Assets` to request only actively used weights: `:400,500,600,700,800,900`.
   - Eliminated 8 unused italic variants (300italic, 400italic, 500italic, 600italic, 700italic, 800italic, 900italic) and weight 300, reducing Google Font payload by **> 50%**.

4. **Resource Hints Preconnect**:
   - Enforced preconnect resource hints for `fonts.googleapis.com` and `fonts.gstatic.com` via `wp_resource_hints` filter.

5. **Elementor Inline SVG Icons**:
   - Activated Elementor experiment `elementor_experiment-e_font_icon_svg` (`active`), loading inline SVGs instead of full icon font files.

---

### 2. Measured Before / After Font & Icon Comparison

| Metric | Before Wave 2 | After Wave 2 | Optimization Result |
| :--- | :--- | :--- | :--- |
| **Poppins Font Variants Requested** | 14 variants (300–900 + italics) | **6 weights** (400, 500, 600, 700, 800, 900) | **-8 unused variants eliminated** |
| **Google Font Preconnect Hints** | Standard | `fonts.googleapis.com` + `fonts.gstatic.com` | Preconnect TLS handshakes enforced |
| **Elementor Font Icon Delivery** | Full font files (`default`) | Inline SVGs (`active`) | Reduced icon font requests |
| **Visual / CLS Regression** | Zero shift | Zero shift | **100% Visual Parity Confirmed** |

---

## Phase 6B — Wave 3: Elementor DOM & Rendering Optimization

- **Implementation Date**: October 7, 2026
- **Branch**: `staging`
- **Target Location**: Database Configuration & Elementor Engine

---

### 1. Implemented Optimizations & Experiment Decisions

1. **Elementor Optimized Markup (`e_optimized_markup`) -> ACTIVE**:
   - Analyzed theme & plugin CSS selector dependencies (0 dependencies on wrapper markup found).
   - Activated `e_optimized_markup` independently.
   - Cleared and rebuilt Elementor CSS file cache (`\Elementor\Plugin::$instance->files_manager->clear_cache()`).
   - Reduced wrapper HTML `div` bloat across all Elementor-built pages.

2. **Elementor Element Caching (`e_element_cache`) -> DEFERRED**:
   - Inspected Elementor Element Cache module architecture.
   - Identified high risk of caching dynamic WooCommerce product prices, cart fragments, stock badges, and user session nonces.
   - Explicitly DEFERRED per performance safety rules ("Performance correctness is more important than enabling every optimization").

3. **Elementor Improved Asset Loading**:
   - Confirmed native core capability in Elementor 3.34.0.

4. **CSS Print Method**:
   - Verified `external` (External CSS File) is active and serving 6 clean generated files (50.15 KB total).

5. **Motion Effects / Animations (`e-animations`) Audit**:
   - Confirmed **0 published posts/templates** use entrance animations.

---

### 2. Measured Before / After DOM & HTML Reduction Comparison

| Route | Baseline DOM Nodes | Post-Wave 3 DOM Nodes | DOM Node Delta | Widget Containers | HTML Size Reduction |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Homepage** | 1,352 | **1,297** | **-55 nodes** | 51 -> 2 | **-3,042 Bytes (~3.04 KB)** |
| **Shop Archive** | 1,701 | **1,675** | **-26 nodes** | 26 -> 0 | **-1,430 Bytes (~1.43 KB)** |
| **FAQ Page** | 824 | **816** | **-8 nodes** | 14 -> 6 | **-422 Bytes** |
| **Simple Product** | 1,267 | **1,257** | **-10 nodes** | 10 -> 0 | **-534 Bytes** |
| **Variable Product** | 1,166 | **1,164** | **-2 nodes** | 2 -> 0 | **-86 Bytes** |
| **TOTAL** | **6,310** | **6,209** | **-101 nodes** | **103 -> 8** | **-5,514 Bytes** |

---

### 3. Verification & Functional Integrity

- **Elementor Popups (IDs 4948, 4963)**: Verified trigger behavior, close controls, and popup metadata.
- **ShopBiOG Custom Form**: Verified `[shopbiog_contact_form]` rendering (2,338 B) with fields, honeypot, labels, and validation intact.
- **FunnelKit Checkout (IDs 9104–9108)**: Verified checkout, order bump, upsells, and thank-you pages operate with zero side-effects.
---

## Phase 6B — Wave 4: LCP / CLS Image & Product Gallery Optimization

- **Implementation Date**: October 7, 2026
- **Branch**: `staging`
- **Target Locations**:
  - Plugin: `wp-content/plugins/shopbiog-core/modules/performance/class-image-performance.php`
  - Child Theme CSS: `wp-content/themes/elessi-theme-child/assets/css/woocommerce.css`, `components.css`

---

### 1. Implemented Optimizations

1. **`ShopBiOG_Image_Performance` Module**:
   - Filtered `wp_get_attachment_image_attributes`, `wp_img_tag_add_loading_attr`, `wp_img_tag_add_fetchpriority_attr` to enforce `fetchpriority="high"` and remove `loading="lazy"` on true LCP elements (Homepage main hero image `wp-image-9158` and single product main featured image `wp-post-image`).
   - Kept secondary gallery images, thumbnails, and below-the-fold images lazy-loaded (`loading="lazy"`).
   - Injected intrinsic `width` and `height` attributes via `wp_content_img_tag` for feature icon slides (`200x50` / `250x50`), 404 placeholder (`180x180`), and footer trust badges.

2. **Presentation CSS Aspect Ratio & Layout Reservation**:
   - Reserved single product main gallery image aspect ratio (`aspect-ratio: 595 / 760; object-fit: cover;`) and thumbnail bounds (`aspect-ratio: 117 / 150;`).
   - Reserved catalog product card image aspect ratio (`aspect-ratio: 350 / 447; object-fit: cover;`) preventing grid reflow.
   - Reserved minimum width for header cart badge counters (`min-width: 1.5rem; display: inline-block; text-align: center;`) preventing header horizontal shifts when `wc-cart-fragments` loads asynchronously.

---

### 2. Measured Before / After Image & Layout Stability Comparison

| Metric | Before Wave 4 | After Wave 4 | Improvement |
| :--- | :--- | :--- | :--- |
| **Homepage LCP Image Priority** | `fetchpriority` un-set | `fetchpriority="high"` | Immediate browser fetch prioritization |
| **Single Product LCP Image Priority** | Standard | `fetchpriority="high"` | Immediate main image fetch prioritization |
| **Product Gallery CLS** | Height jump on slider init | Zero jump (`aspect-ratio: 595 / 760`) | Layout reserved before JS slider init |
| **Product Card Grid CLS** | Grid reflow on image load | Zero shift (`aspect-ratio: 350 / 447`) | Stable catalog card grid |
| **Header Cart Counter CLS** | Horizontal shift on frag update | Zero shift (`min-width: 1.5rem`) | Header menu stability |
| **Missing Image Dimensions** | 11 images missing WxH | 0 core images missing WxH | Intrinsic dimensions enforced |

---

### 3. Verification & Functional Integrity

- **Variable Product Image Switching**: Verified color variation selection (Black, Green, Grey) swaps images with 0 layout jump.
- **FunnelKit & Gateways**: Stripe express buttons, checkout pages (9104–9108), PayPal integration intact.
- **Responsive Viewports**: Desktop, Tablet, Mobile visually verified.



