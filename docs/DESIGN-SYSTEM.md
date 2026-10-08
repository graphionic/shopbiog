# BiO-G Design System & Component Architecture (PROPOSED DRAFT)

> [!IMPORTANT]
> **STATUS: PROPOSED / DRAFT**  
> This document defines the proposed unified design tokens, component architecture, design ownership boundaries, and Phase 7 implementation roadmap for BiO-G. No runtime styling changes are applied during Phase 7A.

---

## 1. Target Design Ownership Matrix

To eliminate style duplication across Elessi, Elementor, and custom code, design ownership is strictly scoped:

```
  ┌─────────────────────────────────────────────────────────────┐
  │              GLOBAL DESIGN TOKENS & BASE STYLES             │
  │   - Location: wp-content/themes/elessi-theme-child/style.css │
  │   - Scope: CSS Custom Properties (:root variables)          │
  │   - Owns: Colors, Typography, Spacing, Radius, Buttons, Forms│
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 PAGE STRUCTURE & LAYOUTS                    │
  │   - Scope: Elementor Pro & Elessi theme templates            │
  │   - Owns: Section containers, grid layouts, page blocks    │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │              BUSINESS LOGIC & DYNAMIC INTERACTION           │
  │   - Scope: wp-content/plugins/shopbiog-core/                │
  │   - Owns: Forms, LCP image optimization, performance rules │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 CHECKOUT & FUNNEL ENGINE                    │
  │   - Scope: FunnelKit (WooFunnels)                           │
  │   - Owns: Checkout form steps, upsells, thank-you pages     │
  └─────────────────────────────────────────────────────────────┘
```

---

## 2. Proposed Design Tokens (CSS Custom Properties)

```css
:root {
  /* Brand Color Palette */
  --biog-color-primary: #22c18e;
  --biog-color-primary-hover: #1ba87a;
  --biog-color-primary-light: #e6f7f2;

  --biog-color-dark: #111827;
  --biog-color-text-main: #1f2937;
  --biog-color-text-muted: #6b7280;
  --biog-color-text-light: #9ca3af;

  --biog-color-bg-body: #ffffff;
  --biog-color-bg-surface: #f9fafb;
  --biog-color-bg-alt: #f3f4f6;

  --biog-color-border: #e5e7eb;
  --biog-color-border-dark: #d1d5db;

  --biog-color-sale: #ef4444;
  --biog-color-star: #f59e0b;

  /* Typography System (Poppins) */
  --biog-font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;

  --biog-font-weight-regular: 400;
  --biog-font-weight-medium: 500;
  --biog-font-weight-semibold: 600;
  --biog-font-weight-bold: 700;

  --biog-font-size-xs: 12px;
  --biog-font-size-sm: 13px;
  --biog-font-size-base: 15px;
  --biog-font-size-md: 17px;
  --biog-font-size-lg: 20px;
  --biog-font-size-xl: 24px;
  --biog-font-size-2xl: 30px;
  --biog-font-size-3xl: 38px;

  --biog-line-height-tight: 1.2;
  --biog-line-height-normal: 1.5;
  --biog-line-height-relaxed: 1.65;

  /* 8-Point Spacing Scale */
  --biog-space-1: 4px;
  --biog-space-2: 8px;
  --biog-space-3: 12px;
  --biog-space-4: 16px;
  --biog-space-6: 24px;
  --biog-space-8: 32px;
  --biog-space-12: 48px;
  --biog-space-16: 64px;
  --biog-space-24: 96px;

  /* Border Radius System */
  --biog-radius-sm: 4px;
  --biog-radius-md: 8px;
  --biog-radius-lg: 12px;
  --biog-radius-full: 9999px;

  /* Box Shadow System */
  --biog-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --biog-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --biog-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

  /* Touch & Interactive Element Heights */
  --biog-btn-height-lg: 48px;
  --biog-btn-height-md: 42px;
  --biog-input-height: 44px;
  --biog-container-max-width: 1200px;
}
```

---

## 3. Component Inventory & Classification

| Component Name | Description | Current System Owner | Action Classification |
| :--- | :--- | :--- | :--- |
| **Header (Desktop)** | Main navigation & brand header | Elessi Header Builder | **REFINE** (Reduce height & clutter) |
| **Mobile Drawer** | Slide-out navigation menu | Elessi Mobile Drawer | **REDESIGN** (Increase touch targets) |
| **Hero Section** | Homepage main brand introduction | Elementor Pro | **REDESIGN** (Highlight 24/7 Odor-Free tech) |
| **Section Headings** | Page title & section headers | Elessi / Elementor | **SYSTEMIZE** (Enforce Poppins scale) |
| **Button System** | Primary, secondary & outline CTAs | Theme / Elementor | **REDESIGN** (Unify into `--biog-btn-*` tokens) |
| **Product Card** | Grid item displaying sock products | Elessi WooCommerce Loop | **REDESIGN** (350:447 ratio, 32px swatches) |
| **Benefit Cards** | Odor-control & comfort feature grids | Elementor Widgets | **REFINE** (Standardize SVG icons & copy) |
| **Trust Strip** | Guarantee, shipping & review badges | Elementor / Theme | **REFINE** (Clean horizontal strip) |
| **Review Block / UGC**| Verified customer reviews & rating stars| Elementor / `wprevpro` | **REFINE** (Consistent card styling) |
| **Accordions** | Product FAQ, Size Guide, Returns | Elessi / Elementor | **RETAIN** (Clean, fast interaction) |
| **Form Fields** | Inputs, select dropdowns, textareas | `ShopBiOG_Forms` / Theme | **REFINE** (44px height, crisp borders) |
| **Quantity Selector** | `+` and `-` product quantity control | WooCommerce / Elessi | **REFINE** (Clean 40px touch targets) |
| **Product Purchase Panel**| Title, price, swatches, AddToCart | Elessi Single Product | **REFINE** (Mobile sticky AddToCart bar) |
| **Badges & Pills** | "Sale!", "Hot", "Top Seller" tags | Elessi Loop Badges | **SYSTEMIZE** (Compact pill design) |
| **Footer** | Site footer with links & newsletter | Elementor Block ID 3712 | **REFINE** (Streamline mobile accordions) |

---

## 4. Page Priority Ranking

1. **P0: Design System Foundation** (Global CSS Custom Properties in `elessi-theme-child`)
2. **P1: Header & Mobile Navigation Drawer** (Mobile touch targets & clean desktop height)
3. **P1: Single Product Page** (Above-the-fold conversion, swatches, size guide, trust badges)
4. **P1: Product Card System & Shop Grid** (Catalog scanability & swatch interaction)
5. **P1: Homepage Transformation** (Brand storytelling & value proposition)
6. **P2: Cart Drawer & Full Cart Page** (Frictionless subtotal & checkout CTA)
7. **P2: Checkout Polish** (FunnelKit visual alignment)
8. **P2: Supporting Pages** (FAQ, Contact, Landing 5885)
9. **P3: My Account Portal** (Clean tab navigation)

---

## 5. Phase 7 Implementation Roadmap

- **Phase 7A: Visual, UX & Conversion Audit** (**COMPLETE**)
- **Phase 7B: Global Design Tokens & Base Component System**
- **Phase 7C: Header & Mobile Navigation Modernization**
- **Phase 7D: Product Card System & Shop Grid Optimization**
- **Phase 7E: Product Page Experience Transformation**
- **Phase 7F: Homepage Experience Transformation**
- **Phase 7G: Cart & Checkout UX Polish**
- **Phase 7H: Supporting Pages & Footer Refinement**
- **Phase 7I: Final Responsive & Conversion QA**
