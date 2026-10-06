# ShopBiOG Core Plugin

## Purpose & Responsibility Boundaries

**FUNCTIONALITY LIVES HERE.**

This plugin serves as the project-owned functionality layer for shopbiog.com. All custom PHP business logic, WooCommerce backend/checkout hooks, performance optimizations, third-party integration bridges, and custom administrative features MUST reside within this plugin module.

### Included Responsibilities:
- WooCommerce custom business logic and data manipulation
- Performance optimization hooks (asset unloading, script/style optimization, cache hooks)
- Third-party tracking and analytics bridges
- Custom frontend functionality and lightweight widget/shortcode logic
- Administrative tools, feature flags, and diagnostic controls
- AJAX endpoints and REST API extensions

### Strict Boundary Rules:
1. **No Business Logic in Child Theme**: Business logic must NEVER be placed in `elessi-theme-child`.
2. **No Vendor Modifications**: Never modify WordPress core, WooCommerce, parent theme, or 3rd-party plugins. All extensions are added via hooks and filters here.
3. **Modular Architecture**: Features inside `modules/` must remain decoupled and selectable.
