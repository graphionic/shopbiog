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
* **Phase 5B — Wave 3 Elementor Addon Consolidation**: Consolidated and removed `ElementsKit Lite` & `Header Footer Elementor (HFE)`. Active plugins reduced to 21. (**STATUS: COMPLETE**)
* **Phase 5B — Wave 4 Forms / Testimonials / Review Components**: Recreate CF7 forms in Elementor Pro; migrate Real Testimonials; optimize Amazon Reviews. (**STATUS: NEXT UP**)
* **STATUS:** **IN PROGRESS (5A, 5B Wave 1, Wave 2 & Wave 3 Complete)**

### PHASE 6: Performance Optimization
* Dequeue redundant CSS/JS assets; optimize Google Fonts & FontAwesome calls; optimize WebP images; tune database autoload options; configure WP Rocket caching.
* **STATUS:** **PENDING**

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
> **Verified Current State (as of Phase 5B Wave 3 Completion):**  
> * **Active Theme State:** Child theme `elessi-theme-child` is active (`Template: elessi-theme`, `Stylesheet: elessi-theme-child`). Theme mods synchronized 1:1 with 0 visual regressions.
> * **Active Plugin Stack:** Total active plugins: 21. Removed `ElementsKit Lite` and `Header Footer Elementor (HFE)`.
> * **Project Code Architecture:** Functionality layer active in `wp-content/plugins/shopbiog-core/modules/frontend/last-modified/`; Presentation layer active in `wp-content/themes/elessi-theme-child/`.
> * **Working Branch:** `staging` (Clean working tree, up to date with `origin/staging`).
