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
* Safely deactivate unused/legacy plugins (`All-in-One WP Migration`, `Slide Everything for Elementor`, `WooCommerce PayPal Payments`).
* Clean up 12 unreferenced legacy HFE templates (IDs 3706–3719).
* Consolidate custom CSS into `elessi-theme-child/assets/css/`.
* **STATUS:** **NEXT UP**

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
> **Verified Current State (as of Phase 4 Completion):**  
> * **Active Theme State:** Child theme `elessi-theme-child` is active (`Template: elessi-theme`, `Stylesheet: elessi-theme-child`). Theme mods synchronized 1:1 with 0 visual regressions.
> * **Project Code Architecture:** Functionality scaffold established in `wp-content/plugins/shopbiog-core/`; Presentation scaffold established in `wp-content/themes/elessi-theme-child/`.
> * **Header & Footer:** Controlled by Elessi Theme (`nasa-core`), with NASA Static Blocks used for footer. HFE header/footer templates exist in DB but have empty display rules.
> * **Page Builder:** Elementor Pro is critical and provides forms, popups, and WooCommerce dynamic tag widgets.
> * **Checkout:** Overridden by FunnelKit Builder Pro (`wfacp_checkout` CPT ID 9104).
> * **Analytics & Tracking:** PixelYourSite PRO handles primary tracking. WPCode snippet #9180 disables Meta for Woo pixel; WPCode snippet #9182 acts as AddToCart click bridge.
> * **Working Branch:** `staging` (Clean working tree, up to date with `origin/staging`).
