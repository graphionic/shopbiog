# System Architecture — BiO-G / ShopBiog

This document defines the structural architecture, code ownership boundaries, and extension hierarchy for the BiO-G website.

---

## Architectural Layers

```
┌──────────────────────────────────────────────────────────────────────────┐
│                          SYSTEM ARCHITECTURE                             │
├──────────────────────────────────────────────────────────────────────────┤
│ VENDOR-OWNED LAYER (Untouched Core & Upstream Code)                     │
│  - WordPress Core                                                        │
│  - WooCommerce Core                                                      │
│  - Elessi Parent Theme (elessi-theme)                                    │
│  - Nasa Core (nasa-core)                                                 │
│  - Elementor & Elementor Pro                                             │
│  - FunnelKit Builder Pro                                                 │
│  - Rank Math SEO & PRO                                                   │
│  - PixelYourSite PRO & Stripe Gateway                                    │
├──────────────────────────────────────────────────────────────────────────┤
│ PROJECT-OWNED LAYER (Custom Code & Overrides)                            │
│                                                                          │
│ 1. Presentation Layer:                                                   │
│    wp-content/themes/elessi-theme-child/                                 │
│    - Visual presentation & CSS styling                                   │
│    - Typography & responsive design tokens                               │
│    - WooCommerce layout-level presentation                               │
│    - Template overrides (when strictly required)                         │
│                                                                          │
│ 2. Functional & Business Logic Layer:                                   │
│    wp-content/plugins/shopbiog-core/                                     │
│    - Custom business logic & WooCommerce hooks                           │
│    - Performance optimizations & conditional asset loading                │
│    - Custom integrations, AJAX handlers, & tracking bridges              │
│    - Replacement modules for lightweight plugin functions                │
└──────────────────────────────────────────────────────────────────────────┘
```

---

## Core Architectural Principles

### 1. Vendor Code Rule
> [!CAUTION]  
> **Vendor code is consumed, never edited.**  
> Third-party plugins, themes, and WordPress core files must remain 100% untouched. Updating upstream packages must never risk overwriting custom project code.

### 2. Modification Hierarchy
When implementing any feature or modification, solutions must be chosen in the following strict order of preference:

1. **WordPress / WooCommerce Action Hooks**
2. **WordPress / WooCommerce Filters**
3. **Child Theme Presentation (`elessi-theme-child`)**
4. **Custom Plugin Modules (`shopbiog-core`)**
5. **WooCommerce Template Overrides** *(Only when hooks and filters cannot achieve the requirement)*
