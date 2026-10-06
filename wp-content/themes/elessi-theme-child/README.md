# Elessi Child Theme (ShopBiOG Presentation Layer)

## Purpose & Responsibility Boundaries

**PRESENTATION LIVES HERE.**

This child theme serves as the presentation layer for shopbiog.com. All visual styling, typography, color definitions, design system layout adjustments, responsive media queries, and component presentation MUST reside within this child theme.

### Included Responsibilities:
- Design system CSS tokens (colors, typography, spacing, shadows)
- Component visual styling (buttons, cards, banners, badges)
- WooCommerce visual presentation overrides via CSS
- Viewport and responsive layout adjustments
- Client-side presentation micro-interactions (UI JS in `assets/js/theme.js`)

### Strict Boundary Rules:
1. **No Business Logic**: PHP business logic, database queries, and integrations must NEVER be placed in `functions.php` or theme files. Use `shopbiog-core` plugin instead.
2. **Modular Organization**: Styles are strictly separated in `assets/css/` by layer (`base.css`, `components.css`, `woocommerce.css`, `responsive.css`).
3. **No Direct Parent File Mutations**: Never edit the parent Elessi theme files directly.
4. **No Template Overrides Unless Unavoidable**: Prefer hooks and CSS for presentation styling before creating template overrides.
