# Project Goals — BiO-G / ShopBiog Modernization

This document outlines the core objectives and scope boundaries for the BiO-G website modernization project.

---

## Primary Objectives

1. **Improve Overall Website Architecture:** Establish clear separation of concerns between vendor code, custom business logic, and presentation layers.
2. **Remove Technical Debt:** Eliminate legacy demo templates, unused scripts, and redundant plugin overrides.
3. **Reduce Heavy Plugin Dependency:** Audit and streamline the 28 installed plugins to decrease server processing and asset bloat.
4. **Custom Code Replacement:** Replace simple single-purpose plugin functionality with lightweight, maintainable custom code where justified.
5. **Improve Site Speed & Core Web Vitals:** Optimize LCP, INP, CLS, and TTFB across desktop and mobile devices.
6. **Mobile Performance Focus:** Deliver an instant, responsive mobile experience optimized for handheld shoppers.
7. **Prioritize User Experience (UX):** Ensure seamless navigation, visual clarity, and intuitive interaction across every page.
8. **Elevate Look & Feel:** Transform visual design into a sleek, state-of-the-art storefront using curated typography and modern styling.
9. **Create a Premium Storefront:** Align visual branding with BiO-G's high-quality odor-resistant product proposition.
10. **Enhance WooCommerce Shopping Experience:** Streamline product selection, attribute swatches, cart drawers, and product discovery.
11. **Product Discovery & Page Clarity:** Highlight key material benefits, sizing guides, and customer reviews clearly on single product pages.
12. **Optimize Cart & Checkout:** Protect and refine FunnelKit checkout flows and AJAX mini-cart drawers to reduce cart abandonment.
13. **Increase Conversion Rate:** Build shopper trust with clear CTAs, benefit badges, social proof, and friction-free purchase paths.
14. **Improve SEO & Technical Quality:** Maintain clean Rank Math metadata, structured data schemas, and clean HTML semantics.
15. **Maintainable & Update-Safe Code:** Ensure all customizations survive future WordPress, WooCommerce, and theme updates.
16. **Prevent Overwrite Regression:** Isolate project code in `elessi-theme-child` and `shopbiog-core` to ensure third-party updates never overwrite custom work.

---

## Scope Boundaries

> [!IMPORTANT]  
> **OUT OF SCOPE RULE:**  
> This project is **NOT** a narrow tracking fix or single-feature AddToCart script project. Tracking and pixel event handling represent only one small component of a comprehensive, full-scale website modernization.
