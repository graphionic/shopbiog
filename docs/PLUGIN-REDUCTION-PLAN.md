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
- **Current Active Plugins (Post Wave 2)**: **23 active** (26 installed - `ShopBiOG Core` activated; `WP Last Modified Info` removed)
- **Immediate Safe-Remove Candidates**: 4 plugins (3 removed in Wave 1)
- **Custom-Code / Lighter Replacements**: 6 plugins (1 replaced in Wave 2)
- **Admin/Maintenance Deactivations**: 2 plugins (`All-in-One WP Migration` & `Unlimited Extension` kept installed but inactive)
- **Target Active Plugin Count**: **16–18 active plugins** (~35% reduction in plugin overhead).

---

## 1. Complete Plugin Inventory & Classification Matrix

| Plugin Name | Version | Active? | Primary Purpose | Asset Cost | DB / Background Cost | Business Critical | Classification Recommendation | Wave Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Akismet Anti-spam** | 5.6 | No | Spam protection | None | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **All-in-One WP Migration** | 7.87 | **No** | Site migration tool | Low | Low | No | `ADMIN / MAINTENANCE ONLY` | **INSTALLED / INACTIVE** |
| **All-in-One WP Migration Unlimited** | 2.63 | **No** | Migration extension | None | None | No | `ADMIN / MAINTENANCE ONLY` | **INSTALLED / INACTIVE** |
| **Contact Form 7** | 6.6.4 | Yes | Form submission | Medium | Low | Yes | `REPLACE WITH LIGHTER SOLUTION` | Pending Wave 4 |
| **Elementor** | 3.34.0 | Yes | Page Builder core | High | Medium | Yes | `KEEP` | Active Core |
| **Elementor Pro** | 3.33.2 | Yes | Page Builder Pro & Forms | Medium | Medium | Yes | `KEEP` | Active Core |
| **ElementsKit Lite** | 4.0.5 | Yes | Elementor Addon Bundle | High | Medium | No | `REPLACE WITH LIGHTER SOLUTION` | Pending Wave 3 |
| **Elessi Core (Nasa Core)** | 6.5.2 | Yes | Theme engine & swatches | High | High | Yes | `KEEP` | Active Core |
| **FunnelKit Funnel Builder** | 3.15.0.5 | Yes | Sales Funnels core | Medium | Medium | Yes | `KEEP` | Active Core |
| **FunnelKit Funnel Builder Pro** | 3.15.0.6 | Yes | Checkout & Order Bumps | High | High | Yes | `KEEP` | Active Core |
| **Google for WooCommerce** | 3.9.3 | Yes | Merchant Center Sync | Low | High (Sync Jobs) | Yes | `KEEP` | Active Catalog Sync |
| **Hello Dolly** | 1.7.2 | No | Sample plugin | None | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **Meta for WooCommerce** | 3.7.6 | Yes | FB Catalog Sync | Low | High (Sync Jobs) | Yes | `KEEP BUT OPTIMIZE` | Active Catalog Sync |
| **PixelYourSite PRO** | 12.5.3 | Yes | CAPI & Pixel Tracking | Medium | Medium | Yes | `KEEP` | Active Tracking |
| **Rank Math SEO** | 1.0.271.1 | Yes | SEO Engine core | Medium | Medium | Yes | `KEEP` | Active Core |
| **Rank Math SEO PRO** | 3.0.102 | Yes | SEO Schema & Analytics | Low | Medium | Yes | `KEEP` | Active Core |
| **Real Testimonials** | 4.0.0 | Yes | Testimonial Sliders | Medium | Low | No | `REPLACE WITH SHOPBIOG CORE` | Pending Wave 4 |
| **ShopBiOG Core** | 1.0.0 | **Yes** | Project Functionality | Low | Low | Yes | `KEEP` | **ACTIVE (Wave 2)** |
| **Slide Everything for Elementor** | 1.7.0 | No | Elementor Swiper Slider | Medium | None | No | `REMOVE` | **REMOVED (Wave 1)** |
| **TikTok for Business** | 1.4.2 | Yes | TikTok Catalog Sync | Low | High (Sync Jobs) | Yes | `KEEP` | Active Catalog Sync |
| **Ultimate Addons (HFE)** | 2.9.4 | Yes | Header/Footer Builder | Medium | Medium | No | `REPLACE WITH LIGHTER SOLUTION` | Pending Wave 3 |
| **Widgets for Amazon Reviews** | 14.1.1 | Yes | Amazon Review Embeds | High | Medium | No | `KEEP BUT OPTIMIZE` | Pending Wave 4 |
| **WooCommerce** | 11.1.1 | Yes | E-commerce Core | High | High | Yes | `KEEP` | Active Core |
| **WooCommerce PayPal Payments** | 4.1.3 | Yes | PayPal Gateway | Medium | Low | No | `REMOVE` *(Business Approval Req)* | Pending Wave 6 |
| **WooCommerce Stripe Gateway** | 11.0.0 | Yes | Stripe Payment Gateway | Medium | Low | Yes | `KEEP` | Active Core |
| **WooCommerce Tax (Services)** | 3.6.3 | Yes | Automated Tax Sync | Low | Medium | Yes | `KEEP` | Active Core |
| **Wordfence Security** | 9.0.1 | Yes | Security & Firewall | Low | High (DB Logs) | Yes | `KEEP BUT OPTIMIZE` | Active Core |
| **WPCode Lite** | 2.3.9 | Yes | Custom Code Snippets | Low | Low | No | `REPLACE WITH SHOPBIOG CORE` | Pending Wave 5 |
| **WP Last Modified Info** | 1.9.6 | **No** | Post Modification Dates | Low | Low | No | `REPLACE WITH SHOPBIOG CORE` | **REPLACED & REMOVED (Wave 2)** |
| **WP Rocket** | 3.19.2.1 | No | Caching & Performance | High | Low | Yes | `KEEP BUT OPTIMIZE` | Pending Phase 6 |

---

## 2. Priority Plugin Deep-Dive Analyses & Wave 1 & 2 Execution Results

### Wave 1 Verification & Deletion Results
- **Hello Dolly**: Removed completely from codebase (`wp-content/plugins/hello.php`). Zero dependencies.
- **Akismet Anti-spam**: Removed completely from codebase (`wp-content/plugins/akismet/`). Zero form/comment dependencies.
- **Slide Everything for Elementor**: Deactivated and removed completely (`wp-content/plugins/slide-everything-for-elementor/`). Verified 0 active widget usages across published content.
- **All-in-One WP Migration & Unlimited Extension**: Deactivated locally. Kept installed as `MAINTENANCE-ONLY` tools for temporary local migration and backup recovery.

### Wave 2 Execution Results
- **ShopBiOG Core Activation**: Activated project-owned plugin `shopbiog-core/shopbiog-core.php` cleanly in WordPress.
- **WP Last Modified Info Migration**: Functionality migrated to `wp-content/plugins/shopbiog-core/modules/frontend/last-modified/` (`class-last-modified.php` & `module.php`). Registered native shortcode `[shopbiog_last_modified]` and compatibility alias shortcode `[lmt-post-modified-info]`.
- **Rank Math Schema Check**: Confirmed Rank Math SEO natively handles structured JSON-LD `dateModified` schema without duplication.
- **Third-Party Removal**: `WP Last Modified Info` plugin deactivated and deleted from codebase (`wp-content/plugins/wp-last-modified-info/`).

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
│   ├── module.php                   # Frontend module loader (Wave 2)
│   ├── class-last-modified.php      # Replaces WP Last Modified Info (Wave 2 - ACTIVE)
│   └── testimonials/                # Replaces Real Testimonials slider (Wave 4)
└── integrations/
    └── class-tracking-bridge.php    # Replaces WPCode snippets #9180 & #9182 (Wave 5)

PRESENTATION LAYER (wp-content/themes/elessi-theme-child/)
├── assets/css/
│   ├── components.css               # Replaces ElementsKit & HFE widget styles
│   └── woocommerce.css              # Custom WooCommerce tab presentation
└── inc/
    └── setup.php                    # Form & block hooks
```

---

## 5. Phase 5B Execution Waves Status

- **WAVE 1: Zero-Risk Unused & Maintenance Plugins** — **COMPLETE**
  - Removed `Hello Dolly`, `Akismet Anti-spam`, `Slide Everything for Elementor`.
  - Deactivated `All-in-One WP Migration` and `Unlimited Extension`. Active plugins: 23.
- **WAVE 2: Simple Utility Replacements** — **COMPLETE**
  - Activated `ShopBiOG Core`.
  - Migrated last modified functionality to `shopbiog-core/modules/frontend/last-modified/`.
  - Deactivated & removed `WP Last Modified Info`. Total active plugins remain: **23** (Core activated, 1 third-party removed).
- **WAVE 3: Elementor Addon Consolidation (ElementsKit & HFE)** — **NEXT UP**
- **WAVE 4: Forms, Testimonials & Amazon Reviews** — Pending
- **WAVE 5: Custom Code & Snippet Consolidation** — Pending *(Requires Tracking Re-verification)*
- **WAVE 6: Payment & Catalog Integration Review** — Pending
