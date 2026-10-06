# Plugin Strategy & Decision Framework — BiO-G / ShopBiog

This document defines the evaluation framework for auditing, streamlining, replacing, and maintaining third-party plugins on the BiO-G website.

---

## Core Philosophy

> [!IMPORTANT]  
> **Dependency Understanding Rule:**  
> Plugins must **NEVER** be removed based solely on "plugin count." A plugin should only be deactivated or removed when its exact frontend usage, shortcode dependencies, database references, and business impact are thoroughly understood and verified.

---

## Classification Categories

Every plugin installed on `shopbiog` is classified into one of the following categories:

| Classification | Definition & Strategy |
| :--- | :--- |
| **KEEP** | Essential core plugin required for site operation (e.g., WooCommerce, Stripe, Rank Math, Elementor Pro). |
| **KEEP BUT OPTIMIZE** | Required plugin whose asset loading or queries must be tuned (e.g., PixelYourSite PRO, Contact Form 7). |
| **REPLACE WITH CUSTOM CODE** | Simple single-purpose plugin that can be replaced with custom logic in `shopbiog-core` (e.g., WP Last Modified Info). |
| **REPLACE WITH LIGHTER SOLUTION** | Heavy plugin whose feature set can be served by a lighter tool or native theme capability. |
| **REMOVE** | Legacy, redundant, or unused plugin safe for removal after QA verification (e.g., Slide Everything for Elementor). |
| **ADMIN / MAINTENANCE ONLY** | Tools required only during development or migration (e.g., All-in-One WP Migration). |
| **UNKNOWN / REQUIRES QA** | Plugin requiring staging environment verification before decision (e.g., WooCommerce PayPal Payments). |

---

## Evaluation Criteria

Before modifying any plugin's status, evaluate against these 10 criteria:

1. **Actual Frontend Usage:** Are shortcodes, widgets, or functions actively rendered on live pages?
2. **Performance Impact:** Does the plugin enqueue heavy CSS/JS bundles globally on non-relevant pages?
3. **Asset Loading Efficiency:** Can the plugin's scripts be conditionally deregistered where unneeded?
4. **Database Overhead:** Does it add large autoloaded options or custom database tables?
5. **Feature Complexity:** Is the feature complex (e.g., payment processing) or trivial (e.g., 5-line filter)?
6. **Update Reliability:** Is the plugin actively maintained by a reputable vendor?
7. **Code Quality:** Does the code follow WordPress standards without security vulnerabilities?
8. **Functional Overlap:** Does another active plugin or theme module already provide this exact capability?
9. **Replicability:** Can the feature be recreated cleanly in `shopbiog-core` with under 50 lines of maintainable code?
10. **Business Criticality:** Does the plugin directly support revenue generation, checkout, tracking, or SEO?
