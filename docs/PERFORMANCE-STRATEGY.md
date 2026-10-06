# Performance Strategy & Core Web Vitals — BiO-G / ShopBiog

This document outlines the performance optimization goals, asset management rules, and Core Web Vitals targets for `shopbiog`.

---

## Governing Performance Rule

> [!CAUTION]  
> **Functionality Over Benchmarks:**  
> Performance optimizations must **NEVER** compromise core eCommerce functionality (Checkout, Payment Processing, Event Tracking, Product Swatches, Cart Drawers, or Mobile Navigation) simply to achieve a higher synthetic benchmark score.

---

## Core Web Vitals Target Metrics

| Metric | Full Name | Target Goal | Description |
| :--- | :--- | :--- | :--- |
| **LCP** | Largest Contentful Paint | `< 2.5s` | Time to render main product image or hero element. |
| **INP** | Interaction to Next Paint | `< 200ms` | Responsiveness to user taps, clicks, and swatch selections. |
| **CLS** | Cumulative Layout Shift | `< 0.1` | Visual stability; zero layout jumps during image/font load. |
| **TTFB** | Time to First Byte | `< 600ms` | Server response time from WAMP/Apache and database queries. |

*Note: Target metrics represent operational goals; synthetic test scores vary by network and hardware.*

---

## Key Optimization Focus Areas

1. **Reduce Frontend HTTP Requests:** Combine and dequeue redundant CSS/JS assets.
2. **Reduce JavaScript Execution Time:** Defer non-critical scripts and eliminate unused Elementor addon JS.
3. **Eliminate Unused CSS:** Dequeue global plugin styles (e.g. Contact Form 7, Elementor addons) on pages where they are not used.
4. **Conditional Asset Loading:** Load WooCommerce scripts, swatches, and cart fragments only on eCommerce pages via `shopbiog-core`.
5. **Elementor Addon Overhead:** Consolidate redundant Elementor addon libraries.
6. **Font & Icon Optimization:** Preload primary web fonts; eliminate duplicate FontAwesome font calls between theme and Elementor.
7. **Tracking Script Streamlining:** Centralize pixel tracking via PixelYourSite PRO; eliminate redundant click event bridge scripts.
8. **Next-Gen Image Optimization:** Serve product images in WebP/AVIF formats with explicit `width`/`height` attributes to prevent CLS.
9. **Lazy Loading:** Apply native `loading="lazy"` to below-the-fold product images while eager-loading hero LCP images.
10. **Database Autoload Tuning:** Keep autoloaded `wp_options` under 800 KB; clean up orphan transients.
11. **Page & Object Caching:** Configure WP Rocket caching rules post-cleanup.
