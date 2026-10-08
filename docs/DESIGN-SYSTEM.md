# BiO-G Design System & Component Architecture

> [!IMPORTANT]
> **STATUS: DRAFT — AWAITING VISUAL APPROVAL**  
> This document defines the design tokens, visual guidelines, component specifications, and preview system for BiO-G under the approved **BIO-G PREMIUM EVERYDAY** brand direction. All tokens and components are implemented inside the isolated UI Lab preview system (`/ui-lab/`) and have not been applied globally to production pages yet.

---

## 1. Approved Brand Direction: BIO-G PREMIUM EVERYDAY

BiO-G is positioned as a **premium consumer apparel/lifestyle brand first**. The antibacterial and odor-control technology serves as quiet **supporting proof**, not the primary visual theme.

### Brand Identity Principles:
- **Look & Feel**: Premium, refined, elegant, modern, clean, minimal, sophisticated, comfortable, lifestyle-oriented, conversion-focused.
- **Customer Journey Arc**: **BRAND → PRODUCT → LIFESTYLE → BENEFITS → PROOF** (NOT Technology → Technology → Product).
- **Technology Role**: Technology proof appears quietly in benefit sections, "Why BiO-G Works", freshness technology cards, proof/testing badges, product detail accordions, and FAQ. It does NOT dominate the hero, main navigation, overall color palette, typography, or page structure.
- **Visual Avoidances**: No cyber/tech aesthetics, neon greens, laboratory visuals, excessive science diagrams, monospace typography, technical grid motifs, glowing effects, futuristic UI, or oversized pill buttons.

---

## 2. Target Design Ownership Matrix

To eliminate style duplication across Elessi, Elementor, and custom code, design ownership is strictly scoped:

```
  ┌─────────────────────────────────────────────────────────────┐
  │              GLOBAL DESIGN TOKENS & BASE STYLES             │
  │   - Location: wp-content/themes/elessi-theme-child/assets/css/│
  │               design-tokens.css                             │
  │   - Scope: CSS Custom Properties (--biog-* variables)       │
  │   - Owns: Colors, Typography, Spacing, Radius, Buttons, Forms│
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 PAGE STRUCTURE & LAYOUTS                    │
  │   - Scope: Elementor Pro & Elessi theme templates            │
  │   - Owns: Section containers, grid layouts, page blocks    │
  │   - UI Preview: page-ui-lab.php & assets/css/ui-lab.css     │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │              BUSINESS LOGIC & DYNAMIC INTERACTION           │
  │   - Scope: wp-content/plugins/shopbiog-core/                │
  │   - Owns: Forms, LCP image performance, cache rules         │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                 CHECKOUT & FUNNEL ENGINE                    │
  │   - Scope: FunnelKit (WooFunnels)                           │
  │   - Owns: Checkout form steps, upsells, thank-you pages     │
  └──────────────────────────────┴──────────────────────────────┘
```

---

## 3. Global Design Tokens (`design-tokens.css`)

Tokens are namespaced with `--biog-*` and defined in `wp-content/themes/elessi-theme-child/assets/css/design-tokens.css`.

### 3.1 Color Palette System (3 Options in UI Lab)

The system baseline defaults to **Option B (Premium Forest)** with dynamic switcher classes in UI Lab (`.biog-theme-a`, `.biog-theme-b`, `.biog-theme-c`).

| Token Name | Option A: Deep Botanical | Option B: Premium Forest (Default) | Option C: Modern Heritage |
| :--- | :--- | :--- | :--- |
| `--biog-color-primary` | `#2D4A3E` (Deep Botanical) | `#1B4D3E` (Forest Green) | `#343F35` (Dark Olive) |
| `--biog-color-primary-hover` | `#233B31` | `#143B2F` | `#273028` |
| `--biog-color-dark` | `#1C1F1D` (Charcoal) | `#111613` (Near Black) | `#181B18` (Graphite) |
| `--biog-color-bg-body` | `#FAFAFA` (Warm White) | `#FDFBF7` (Cream) | `#F9F8F6` (Ivory) |
| `--biog-color-bg-surface` | `#F2F4F2` (Soft Stone) | `#F4F0EA` (Light Taupe) | `#EFECE6` (Warm Gray) |
| `--biog-color-accent` | `#668574` (Muted Green) | `#88A090` (Gentle Sage) | `#7A8B7B` (Refined Fresh) |
| `--biog-color-text-main` | `#2B2F2C` | `#232724` | `#262926` |
| `--biog-color-text-muted` | `#636B66` | `#5E6660` | `#606862` |
| `--biog-color-border` | `#E1E5E2` | `#E3DFD7` | `#E2DDD5` |
| `--biog-color-success` | `#2E7D32` | `#2E7D32` | `#2E7D32` |
| `--biog-color-sale` | `#B91C1C` | `#B91C1C` | `#B91C1C` |

> **Primary Color Rule**: We do NOT lock legacy bright mint `#22c18e` or Elementor green `#5EC291` as the dominant brand color. Bright greens are restricted to tiny restrained badges/accents if visually required.

### 3.2 Typography System

The typography scale supports two family pairing modes tested in UI Lab:
- **Option 1 (Premium Sans-Only)**: `Poppins` for both headings and body text.
- **Option 2 (Premium Editorial Heading + Clean Sans Body)**: `Playfair Display` serif for hero & display headings; `Poppins` for H2, H3, body, navigation, and UI text.

| Token | Desktop Size | Weight | Line Height | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `--biog-font-display` | 48px / 3rem | 600 / SemiBold | 1.15 | Hero / Display titles |
| `--biog-font-h1` | 36px / 2.25rem | 600 / SemiBold | 1.2 | Page main title / H1 |
| `--biog-font-h2` | 28px / 1.75rem | 600 / SemiBold | 1.25 | Section headings |
| `--biog-font-h3` | 20px / 1.25rem | 600 / SemiBold | 1.3 | Card titles / Subsections |
| `--biog-font-body` | 15px / 0.9375rem | 400 / Regular | 1.6 | Paragraphs & standard copy |
| `--biog-font-small` | 13px / 0.8125rem | 400 / Regular | 1.5 | Supporting / Meta copy |
| `--biog-font-nav` | 14px / 0.875rem | 500 / Medium | 1.0 | Navigation items |
| `--biog-font-btn` | 14px / 0.875rem | 600 / SemiBold | 1.0 | Button label |
| `--biog-font-price` | 18px / 1.125rem | 600 / SemiBold | 1.0 | Product price display |
| `--biog-font-label` | 12px / 0.75rem | 600 / SemiBold | 1.0 | Form labels / Badges |
| `--biog-font-micro` | 11px / 0.6875rem | 500 / Medium | 1.4 | Microcopy / Trust notes |

### 3.3 Spacing Scale (8-Point System)

| Token | Value | Common Usage |
| :--- | :--- | :--- |
| `--biog-space-1` | 4px | Micro padding / icon gaps |
| `--biog-space-2` | 8px | Tight inline element gaps |
| `--biog-space-3` | 12px | Badge padding / small container gap |
| `--biog-space-4` | 16px | Standard padding / body spacing |
| `--biog-space-6` | 24px | Card padding / component spacing |
| `--biog-space-8` | 32px | Section inner gaps / grid gutters |
| `--biog-space-12` | 48px | Large block padding |
| `--biog-space-16` | 64px | Section vertical spacing (desktop) |
| `--biog-space-24` | 96px | Major page section breathing room |

### 3.4 Container & Layout System

- **Global Max Width**: `--biog-container-max: 1240px`
- **Content Max Width**: `--biog-content-max: 960px`
- **Narrow Editorial Width**: `--biog-narrow-max: 720px`
- **Desktop Gutters**: 32px
- **Tablet Gutters**: 24px
- **Mobile Gutters**: 16px

### 3.5 Shape & Radius System (Soft Modern)

- `--biog-radius-sm`: `4px` (Small badges, tooltips)
- `--biog-radius-md`: `8px` (Buttons, inputs, micro cards)
- `--biog-radius-lg`: `12px` (Product cards, purchase panel, modals)
- `--biog-radius-xl`: `16px` (Large hero blocks, featured images)
- `--biog-radius-full`: `9999px` (Restrained pill badges / swatches)

### 3.6 Shadow System (Subtle & High-Contrast)

- `--biog-shadow-sm`: `0 2px 4px rgba(0, 0, 0, 0.03)`
- `--biog-shadow-md`: `0 6px 16px rgba(0, 0, 0, 0.06)`

---

## 4. Scoped UI Preview Components (`page-ui-lab.php`)

All components exist within the isolated UI Lab preview environment at `http://localhost/shopbiog/ui-lab/` (ID 9224):

1. **Palette Variations Switcher**: Dynamic live switching across Option A, B, and C.
2. **Typography Comparison Grid**: Live side-by-side comparison of Sans-Only vs Serif Editorial Heading + Sans Body.
3. **Spacing & Container System**: Visual representation of the 8-point scale and max-width containers.
4. **Button System**: Primary (`48px` height), Secondary (Outlined), Tertiary (Text link), and Icon buttons with default, hover, focus, and disabled states.
5. **Product Card Previews**:
   - **Direction A (Minimal Editorial)**: Clean aspect ratio (`350:447`), clean typography, swatches, no clutter.
   - **Direction B (Premium Commerce)**: Direct Add to Cart CTA button, star ratings, subtle badges.
6. **Product Purchase Panel**: Complete PDP purchase box mock with title, pricing, color swatches, size selector, quantity stepper, primary Add to Cart CTA, 30-Day Freshness Guarantee, and payment icons.
7. **Trust Strip Component**: Horizontal trust bar featuring 30-Day Freshness Guarantee, USA Shipping, Secure Checkout, and All-Day Odor Control.
8. **Benefit Card Grid**: 4-column lifestyle feature grid (24/7 Freshness, Breathable Cotton, Ergonomic Fit, Durable Quality).
9. **Science & Proof Supporting Component**: Clean, editorial proof section showing "How BiO-G Silver-Ion Tech Works" as quiet supporting evidence.
10. **Form System**: Standard inputs, search inputs, dropdown selects, textareas, checkboxes, radio buttons, focus rings, error states, and success states (44px min touch height).
11. **Badges & Labels**: Subtle, non-loud badges (`BEST SELLER`, `NEW`, `SAVE 20%`, `ODOR-FREE TECH`).
12. **Iconography System**: Crisp inline SVG outline icon set with consistent 1.75px stroke width.
13. **Image Treatment Directions**: Studio white card, warm neutral editorial background, and full-width lifestyle feature treatment.
14. **Section Headings**: Elegant eyebrow + main heading + description + link hierarchy.
15. **Hero Section Previews**:
    - **Hero A (Premium Editorial)**: Sophisticated lifestyle background with refined messaging and primary CTA.
    - **Hero B (Product-Led Split)**: Split container pairing high-resolution product photography with lifestyle copy.
16. **Responsive Layout Review**: Multi-column breakpoint verification across 1240px, 768px, and 390px.
17. **Accessibility & Touch Target Verification**: High-contrast ratios (`>4.5:1`), explicit `:focus-visible` outline rings (`2px solid --biog-color-primary`), and `44px+` touch targets.

---

## 5. Accessibility & Touch Standards

- **Text Contrast**: All body text (`#232724` on `#FDFBF7`) exceeds AAA standard (14.2:1). Muted copy (`#5E6660`) exceeds AA standard (5.8:1).
- **Focus States**: High-contrast 2px solid primary color focus ring with 2px offset on all interactive components.
- **Touch Heights**: All buttons, inputs, select fields, swatches, and quantity steppers meet or exceed `44px`.

---

## 6. Implementation Scoping & Non-Interference Rule

> [!CAUTION]
> **PREVIEW ONLY**: To protect production performance and avoid visual regressions prior to owner approval:
> - All styles are strictly scoped under the `.biog-ui-lab` wrapper class in `wp-content/themes/elessi-theme-child/assets/css/ui-lab.css`.
> - Global production selectors (`button {}`, `h1 {}`, `input {}`, `.woocommerce button {}`) are **NEVER** modified in Phase 7B.
> - Live homepage, catalog, single product, cart, checkout, header, footer, and landing pages remain **100% UNTOUCHED**.

---

## 7. Roadmap & Next Steps

- **Phase 7A: Visual, UX & Conversion Audit** (**COMPLETE**)
- **Phase 7A.1: Rendered Visual Review & Design Direction** (**COMPLETE**)
- **Phase 7B: Global Design Tokens & Base Components** (**IMPLEMENTED FOR VISUAL REVIEW — DRAFT**)
- **Phase 7B.1: Design System Lock** (**NEXT UP - Awaiting Owner Approval**)
- **Phase 7C: Header & Mobile Navigation Modernization** (**PENDING**)
touch targets & clean desktop height)
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
