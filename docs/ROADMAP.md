# Project Roadmap — BiO-G / ShopBiog Modernization

This document maps the 11-phase execution roadmap for modernizing the BiO-G WooCommerce site.

---

## Master Execution Roadmap

### PHASE 1: Baseline Audit
* Full audit of WordPress, themes, 28 plugins, WooCommerce, database, and security.
* **STATUS:** **COMPLETE**

### PHASE 2: Git / Branching / GitHub / Security Setup
* Git initialized; `.gitignore` configured; `main` baseline commit established; `staging` branch created; GitHub remote `origin` configured; secret scanning investigation complete.
* **STATUS:** **COMPLETE**

### PHASE 3: Architecture & Dependency Verification
* Verified header/footer ownership, HFE 20-template audit, ElementsKit usages, Elementor Pro dependencies, Theme Mods diff, WooCommerce template compatibility (51 overrides), tracking matrix, WPCode snippets (#9180/#9182), and FunnelKit CPTs.
* **STATUS:** **COMPLETE**

### PHASE 4: Custom Architecture Foundation
* **Phase 4A — Custom Architecture Scaffold**: Created `wp-content/plugins/shopbiog-core/` and structured `wp-content/themes/elessi-theme-child/`. (**STATUS: COMPLETE**)
* **Phase 4B — Child Theme Activation**: Synchronized 169 theme mod keys and safely activated `elessi-theme-child` with 0 visual regressions. (**STATUS: COMPLETE**)
* **Phase 4C — Deployment State Documentation**: Recorded database state, deployment warnings, and production migration procedures. (**STATUS: COMPLETE**)
* **STATUS:** **COMPLETE**

### PHASE 5: Plugin Reduction & Feature Migration
* **Phase 5A — Plugin Reduction Blueprint**: Completed full inventory, deep-dive usage analysis of 30 plugins, replacement mapping, and 6-wave migration plan (`docs/PLUGIN-REDUCTION-PLAN.md`). (**STATUS: COMPLETE**)
* **Phase 5B — Wave 1 Zero-Risk Cleanup**: Removed `Hello Dolly`, `Akismet`, `Slide Everything`; deactivated `All-in-One WP Migration` plugins. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 2 Simple Utility Replacements**: Activated `ShopBiOG Core`; migrated last modified date logic to `shopbiog-core/modules/frontend/last-modified/`; removed `WP Last Modified Info`. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 3 Elementor Addon Consolidation**: Consolidated and removed `ElementsKit Lite` & `Header Footer Elementor (HFE)`. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 4 Forms / Testimonials / Review Components**: Replaced `Contact Form 7` with `ShopBiOG_Forms` and `Real Testimonials` with `ShopBiOG_Testimonials`. Active plugins reduced to 19. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 5 Custom Code & Snippet Consolidation**: Consolidate WPCode snippets (#9180 & #9182) into `shopbiog-core/modules/integrations/`; deactivate and delete `WPCode Lite`. Active plugins reduced to 18. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 6 Payment & Catalog Integration Review**: Reviewed PayPal, Meta, Google, TikTok, and WooCommerce Tax integrations. Catalog infrastructure retained; PayPal classified as ready for removal. (**STATUS: COMPLETE**)
* **STATUS:** **COMPLETE**

### PHASE 6: Performance Optimization
* **Phase 6A — Performance Baseline & Bottleneck Map**: Established complete route inventory, asset ownership, DB autoload analysis, font/icon audit, and priority matrix (`docs/PERFORMANCE-BASELINE.md`). (**STATUS: COMPLETE**)
* **Phase 6B — Wave 1 Safe Conditional Assets & DB Hygiene**: Created `shopbiog-core/modules/performance/`; conditionally dequeued unused WooCommerce block CSS; cleaned expired transients & orphaned option `yith_woocompare_fields_attrs`. (**STATUS: COMPLETE**)
* **Phase 6B — Wave 2 Font & Icon Optimization**: Filtered Google Fonts Poppins weights to `:400,500,600,700,800,900` (eliminating 8 unused italic variants); added Google Font preconnect hints; activated Elementor inline SVG icons. (**STATUS: COMPLETE**)
* **Phase 6B — Wave 3 Elementor DOM & Rendering Optimization**: Activated `e_optimized_markup` (-101 DOM nodes); evaluated `e_element_cache` -> DEFERRED (safeguarding dynamic ecommerce data); verified popups, forms, FunnelKit, and responsive layouts. (**STATUS: COMPLETE**)
* **Phase 6B — Wave 4 LCP / CLS Image & Gallery Optimization**: Created `ShopBiOG_Image_Performance` module enforcing `fetchpriority="high"` on LCP images, lazy-load exclusions, intrinsic dimension enforcement, and CSS layout aspect-ratio reservation for product gallery (`595/760`) and catalog cards (`350/447`). (**STATUS: COMPLETE**)
* **Phase 6B — Wave 5 Action Scheduler / Background Task & Database Maintenance**: Created `ShopBiOG_Database_Maintenance` admin module; purged 183 completed, 24 canceled, and 295 resolved GLA failed actions older than 30 days via Action Scheduler API; unscheduled 14 orphaned WP-Cron hooks; created `docs/DATABASE-MAINTENANCE.md`. (**STATUS: COMPLETE**)
* **Phase 6B — Wave 5.1 Cron Safety Reconciliation**: Created 99.94 MB SQL backup; reconciled Wordfence, Core, and Rank Math crons; established canonical DB measurement (`128.69 MB`). (**STATUS: COMPLETE**)
* **Phase 6B — Wave 6 Controlled WP Rocket Activation & Cache Configuration**: Activated WP Rocket (`v3.19.2.1`); created `ShopBiOG_Cache_Compatibility` module enforcing cart/checkout/FunnelKit/REST URI exclusions and cookie bypass; disabled aggressive JS/CSS/LazyLoad overrides; documented settings in `docs/WP-ROCKET-CONFIGURATION.md`. (**STATUS: COMPLETE**)
* **STATUS:** **COMPLETE (Phase 6A & 6B Waves 1–6 Complete)**

### PHASE 6C: Final Performance Validation & Core Web Vitals Review
* **Phase 6C — Final Performance Validation**: Comprehensive audit of LCP, CLS, INP, TTFB, and route stability across desktop and mobile form factors. (**STATUS: NEXT UP**)




### PHASE 7: Design System & Styling Framework
* Establish unified design tokens (typography, color palettes, spacing, button styles, card borders, mobile breakpoints).
* **STATUS:** **PENDING**

### PHASE 8: UX / UI Redesign
* Modernize header, mobile navigation drawer, homepage hero, shop catalog grid, single product page layout, cart drawer, checkout, and footer.
* **STATUS:** **PENDING**

### PHASE 9: Conversion Rate Optimization (CRO)
* Integrate social proof badges, clear size guides, odor-resistant benefit highlights, money-back guarantees, and friction-free checkout enhancements.
* **STATUS:** **PENDING**

### PHASE 10: SEO, Technical Quality & Final QA
* Validate Rank Math schema output, XML sitemaps, cross-browser compatibility, and pixel tracking event accuracy.
* **STATUS:** **PENDING**

### PHASE 11: Production Deployment & Monitoring
* Final code review, merge `staging` into `main`, full database backup, controlled production host deployment, and post-launch monitoring.
* **STATUS:** **PENDING**

---

## Current Verified Architecture Summary Notes

> [!NOTE]  
> **Verified Current State (as of Phase 5 Completion):**  
> * **Active Theme State:** Child theme `elessi-theme-child` is active (`Template: elessi-theme`, `Stylesheet: elessi-theme-child`). Theme mods synchronized 1:1 with 0 visual regressions.
> * **Active Plugin Stack:** Total active plugins: **18**. Removed 7 third-party plugins (`Hello Dolly`, `Akismet`, `Slide Everything`, `WP Last Modified Info`, `ElementsKit Lite`, `HFE`, `Contact Form 7`, `Real Testimonials`, `WPCode Lite`). Reduced from 26 to 18 active plugins.
> * **Project Code Architecture:** Functionality layer active in `wp-content/plugins/shopbiog-core/modules/` (`frontend/`, `integrations/`); Presentation layer active in `wp-content/themes/elessi-theme-child/`.
> * **Working Branch:** `staging` (Clean working tree, up to date with `origin/staging`).


