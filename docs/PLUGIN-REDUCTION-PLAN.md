# Plugin Reduction & Feature Migration Blueprint — BiO-G / ShopBiog

This document defines the comprehensive plugin audit, feature migration strategies, risk classifications, and phased reduction waves for modernizing `shopbiog.com`.

---

## Executive Summary & Target Architecture

The goal of Phase 5 is to eliminate unnecessary plugin overhead, reduce frontend CSS/JS bloat, eliminate redundant code execution, and establish long-term architectural maintainability without breaking existing website functionality.

> [!IMPORTANT]
> **Core Principle: Plugin Reduction is NOT a Numbers Game.**  
> Plugins providing complex, well-maintained, business-critical capabilities (e.g., WooCommerce, Stripe, Elementor Pro, FunnelKit, Rank Math, PixelYourSite PRO) remain untouched. Plugin reduction focuses strictly on eliminating unused plugins, lightweight single-feature plugins that can be replaced by `< 50` lines of project code, and third-party Elementor addon bundles that duplicate native theme capabilities.

### Overall Target Impact
- **Initial Active Plugins (Phase 5A Baseline)**: 26 active (30 installed)
- **Current Active Plugins (Post Wave 5)**: **18 active** (21 installed - `WPCode Lite` removed)
- **Immediate Safe-Remove Candidates**: 4 plugins (3 removed in Wave 1)
- **Custom-Code / Lighter Replacements**: 7 plugins (6 removed: `CF7`, `Real Testimonials`, `ElementsKit Lite`, `HFE`, `WP Last Modified Info`, `WPCode Lite`)
- **Admin/Maintenance Deactivations**: 2 plugins (`All-in-One WP Migration` & `Unlimited Extension` kept installed but inactive)
- **Target Active Plugin Count**: **18 active plugins** (~31% overall reduction in active plugin count).

---

## 1. Complete Plugin Inventory & Classification Matrix

| Plugin Name | Version | Active? | Primary Purpose | Asset Cost | DB / Background Cost | Business Critical | Classification Recommendation | Wave Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Akismet Anti-spam** | 5.6 | No | Spam protection | None | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **All-in-One WP Migration** | 7.87 | **No** | Site migration tool | Low | Low | No | `ADMIN / MAINTENANCE ONLY` | **INSTALLED / INACTIVE** |
| **All-in-One WP Migration Unlimited** | 2.63 | **No** | Migration extension | None | None | No | `ADMIN / MAINTENANCE ONLY` | **INSTALLED / INACTIVE** |
| **Contact Form 7** | 6.6.4 | **No** | Form submission | Medium | Low | Yes | `REPLACE WITH LIGHTER SOLUTION` | **REPLACED & REMOVED (Wave 4)** |
| **Elementor** | 3.34.0 | Yes | Page Builder core | High | Medium | Yes | `KEEP` | Active Core |
| **Elementor Pro** | 3.33.2 | Yes | Page Builder Pro & Forms | Medium | Medium | Yes | `KEEP` | Active Core |
| **ElementsKit Lite** | 4.0.5 | **No** | Elementor Addon Bundle | High | Medium | No | `REPLACE WITH LIGHTER SOLUTION` | **REMOVED (Wave 3)** |
| **Elessi Core (Nasa Core)** | 6.5.2 | Yes | Theme engine & swatches | High | High | Yes | `KEEP` | Active Core |
| **FunnelKit Funnel Builder** | 3.15.0.5 | Yes | Sales Funnels core | Medium | Medium | Yes | `KEEP` | Active Core |
| **FunnelKit Funnel Builder Pro** | 3.15.0.6 | Yes | Checkout & Order Bumps | High | High | Yes | `KEEP` | Active Core |
| **Google for WooCommerce** | 3.9.3 | Yes | Merchant Center Sync | Low | High (Sync Jobs) | Yes | `KEEP` | Active Catalog Sync |
| **Hello Dolly** | 1.7.2 | No | Sample plugin | None | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **Meta for WooCommerce** | 3.7.6 | Yes | FB Catalog Sync | Low | High (Sync Jobs) | Yes | `KEEP BUT OPTIMIZE` | Active Catalog Sync |
| **PixelYourSite PRO** | 12.5.3 | Yes | CAPI & Pixel Tracking | Medium | Medium | Yes | `KEEP` | Active Tracking |
| **Rank Math SEO** | 1.0.271.1 | Yes | SEO Engine core | Medium | Medium | Yes | `KEEP` | Active Core |
| **Rank Math SEO PRO** | 3.0.102 | Yes | SEO Schema & Analytics | Low | Medium | Yes | `KEEP` | Active Core |
| **Real Testimonials** | 4.0.0 | **No** | Testimonial Sliders | Medium | Low | No | `REPLACE WITH SHOPBIOG CORE` | **REPLACED & REMOVED (Wave 4)** |
| **ShopBiOG Core** | 1.0.0 | **Yes** | Project Functionality | Low | Low | Yes | `KEEP` | **ACTIVE (Wave 2)** |
| **Slide Everything for Elementor** | 1.7.0 | No | Elementor Swiper Slider | Medium | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **TikTok for Business** | 1.4.2 | Yes | TikTok Catalog Sync | Low | High (Sync Jobs) | Yes | `KEEP` | Active Catalog Sync |
| **Ultimate Addons (HFE)** | 2.9.4 | **No** | Header/Footer Builder | Medium | Medium | No | `REPLACE WITH LIGHTER SOLUTION` | **REMOVED (Wave 3)** |
| **Widgets for Amazon Reviews** | 14.1.1 | Yes | Amazon Review Embeds | High | Medium | No | `KEEP BUT OPTIMIZE` | Pending Phase 6 |
| **WooCommerce** | 11.1.1 | Yes | E-commerce Core | High | High | Yes | `KEEP` | Active Core |
| **WooCommerce PayPal Payments** | 4.1.3 | Yes | PayPal Gateway | Medium | Low | No | `REMOVE` *(Business Approval Req)* | Pending Wave 6 |
| **WooCommerce Stripe Gateway** | 11.0.0 | Yes | Stripe Payment Gateway | Medium | Low | Yes | `KEEP` | Active Core |
| **WooCommerce Tax (Services)** | 3.6.3 | Yes | Automated Tax Sync | Low | Medium | Yes | `KEEP` | Active Core |
| **Wordfence Security** | 9.0.1 | Yes | Security & Firewall | Low | High (DB Logs) | Yes | `KEEP BUT OPTIMIZE` | Active Core |
| **WPCode Lite** | 2.3.9 | **No** | Custom Code Snippets | Low | Low | No | `REPLACE WITH SHOPBIOG CORE` | **REPLACED & REMOVED (Wave 5)** |
| **WP Last Modified Info** | 1.9.6 | **No** | Post Modification Dates | Low | Low | No | `REPLACE WITH SHOPBIOG CORE` | **REPLACED & REMOVED (Wave 2)** |
| **WP Rocket** | 3.19.2.1 | No | Caching & Performance | High | Low | Yes | `KEEP BUT OPTIMIZE` | Pending Phase 6 |

---

## 2. Priority Plugin Deep-Dive Analyses & Execution Results

### Wave 1 Verification & Deletion Results
- **Hello Dolly**: Removed completely (`wp-content/plugins/hello.php`).
- **Akismet Anti-spam**: Removed completely (`wp-content/plugins/akismet/`).
- **Slide Everything for Elementor**: Deactivated and removed completely (`wp-content/plugins/slide-everything-for-elementor/`).
- **All-in-One WP Migration & Unlimited Extension**: Deactivated locally. Kept installed as `MAINTENANCE-ONLY` tools.

### Wave 2 Execution Results
- **ShopBiOG Core Activation**: Activated project-owned plugin `shopbiog-core/shopbiog-core.php`.
- **WP Last Modified Info Migration**: Migrated to `shopbiog-core/modules/frontend/last-modified/`. Registered native shortcode `[shopbiog_last_modified]` and compatibility alias `[lmt-post-modified-info]`.
- **Third-Party Removal**: `WP Last Modified Info` plugin deactivated and deleted (`wp-content/plugins/wp-last-modified-info/`).

### Wave 3 Execution Results
- **ElementsKit Lite & HFE Removals**: Confirmed 0 active published dependencies. Deactivated and removed `ElementsKit Lite` (`wp-content/plugins/elementskit-lite/`) and `Header Footer Elementor` (`wp-content/plugins/header-footer-elementor/`).

### Wave 4 Execution Results
- **Contact Form 7 Migration**: Replaced CF7 shortcodes `[contact-form-7]` and `[nasa_cf7]` with native lightweight form component in `shopbiog-core/modules/frontend/forms/class-forms.php`. Deactivated and removed `Contact Form 7` (`wp-content/plugins/contact-form-7/`).
- **Real Testimonials Migration**: Exported 32 testimonial entries to JSON. Registered native testimonial component in `shopbiog-core/modules/frontend/testimonials/class-testimonials.php`. Deactivated and removed `Real Testimonials` (`wp-content/plugins/testimonial-free/`).
- **Widgets for Amazon Reviews**: Retained plugin active for live Amazon review content; marked for asset optimization in Phase 6 Performance Optimization.
- **Asset Overhead Impact**: Saved ~7KB of unused CSS/JS framework asset enqueues per page render. Active plugins reduced to **19**.

---

## 3. Tracking Architecture & Ownership Note

> [!NOTE]  
> **Tracking Ownership Re-Verification Notice (Required Before Wave 5):**  
> Phase 3 previously identified `TikTok for WooCommerce` as handling TikTok browser events, while Phase 5A noted `PixelYourSite PRO` as the unified tracking manager.  
> **Tracking ownership must be re-verified before Wave 5 execution** to ensure zero pixel or Conversion API (CAPI) events are broken when consolidating custom code snippets.

---

## 4. Replacement Architecture Mapping

Custom feature replacements are placed according to strict responsibility boundaries:

```
FUNCTIONALITY LAYER (wp-content/plugins/shopbiog-core/modules/)
├── frontend/
│   ├── module.php                   # Frontend module loader (Wave 2 - ACTIVE)
│   ├── class-last-modified.php      # Replaces WP Last Modified Info (Wave 2 - ACTIVE)
│   ├── forms/
│   │   └── class-forms.php          # Replaces Contact Form 7 (Wave 4 - ACTIVE)
│   └── testimonials/
│       └── class-testimonials.php   # Replaces Real Testimonials (Wave 4 - ACTIVE)
└── integrations/
    ├── module.php                   # Integrations module loader (Wave 5 - ACTIVE)
    └── class-meta-integration.php   # Replaces WPCode snippet #9180 (Wave 5 - ACTIVE)

PRESENTATION LAYER (wp-content/themes/elessi-theme-child/)
├── assets/css/
│   ├── components.css               # Replaces ElementsKit & HFE widget styles
│   └── woocommerce.css              # Custom WooCommerce tab presentation
└── inc/
    └── setup.php                    # Form & block hooks
```

---

## 5. Phase 5B Execution Waves Status

- **WAVE 1: Zero-Risk Unused & Maintenance Plugins** — **COMPLETE** (Active plugins: 23)
- **WAVE 2: Simple Utility Replacements** — **COMPLETE** (Active plugins: 23)
- **WAVE 3: Elementor Addon Consolidation (ElementsKit & HFE)** — **COMPLETE** (Active plugins: 21)
- **WAVE 4: Forms, Testimonials & Amazon Reviews** — **COMPLETE** (Active plugins: 19)
- **WAVE 5: Custom Code & WPCode Consolidation** — **COMPLETE** (Active plugins: 18)
  - Migrated Meta pixel deduplication filter to `shopbiog-core/modules/integrations/class-meta-integration.php`.
  - Consolidated GA4 Measurement ID `G-VP3TGE9JK1` into `PixelYourSite PRO`.
  - Deactivated and deleted `WPCode Lite`.
  - Active plugins reduced to **18**.
- **WAVE 6: Payment & Catalog Integration Review** — Pending

