# Deployment Strategy & Operations — BiO-G / ShopBiog

This document defines the deployment pipeline, quality assurance procedures, database state synchronization, and production environment rules for `shopbiog`.

---

## Deployment Pipeline

```
  ┌─────────────────────────────────────────────────────────────┐
  │                   LOCAL DEVELOPMENT (WAMP)                  │
  │   - Feature development & custom code writing               │
  │   - Local testing on branch: staging                        │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                   GITHUB STAGING (origin/staging)           │
  │   - Code integration & automated checks                     │
  │   - Cross-browser QA & tracking verification                │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                   MAIN BRANCH MERGE (origin/main)           │
  │   - Final code review & pull request approval               │
  │   - Production-ready tagged commit                          │
  └──────────────────────────────┬──────────────────────────────┘
                                 │
                                 ▼
  ┌─────────────────────────────────────────────────────────────┐
  │                   PRODUCTION HOSTING DEPLOYMENT             │
  │   - Controlled deployment to live host                      │
  │   - Theme mod database synchronization & cache purge        │
  └─────────────────────────────────────────────────────────────┘
```

---

## Deployment Safety Rules

1. **Mandatory Full Backup:** Create a full database and `wp-content` file backup prior to any production deployment.
2. **Staging Testing First:** Never push or deploy untested code directly to the production environment.
3. **Rollback Capability:** Ensure Git baseline tags allow instant rollback if unexpected errors occur.
4. **Database Migration Caution:** Database structure updates must be executed via tested SQL scripts or plugin migration routines.
5. **No Secrets in Repository:** Confirm credentials, API keys, and database passwords are set via environment variables or host configuration, **never** in Git.
6. **Preserve User Media & Data:** Production deployments must never overwrite or delete `wp-content/uploads/` or live order/customer database tables.

---

## Database State & Child Theme Activation Deployment Rules

> [!CAUTION]
> **CRITICAL DEPLOYMENT WARNING: Child-Theme Activation is NOT Code-Only!**  
> WordPress stores Theme Customizer options in the database `wp_options` table keyed by theme stylesheet name (e.g. `theme_mods_elessi-theme` vs `theme_mods_elessi-theme-child`).  
> Pushing child-theme code files to production alone will **NOT** reproduce the current visual layout, menus, logo, colors, or header/footer configurations. The production deployment process **must** verify that required child-theme `theme_mod` values exist before switching the active production theme from `elessi-theme` to `elessi-theme-child`.

### Theme Mod Synchronization Summary (Phase 4B)
During local/staging child theme activation, `theme_mods_elessi-theme` (528 options) was used as the source state and approximately 169 required option keys were synchronized into `theme_mods_elessi-theme-child`.

Categorized Synchronized Settings:
- **Navigation Menu Locations**: `nav_menu_locations` (Main menu, topbar menu, mobile drawer menu)
- **Logo Settings**: `custom_logo`, `site_logo`, `site_logo_retina`, logo width/height dimensions
- **Header Configurations**: `nasa_header_custom`, `nasa_header_custom_mobile`, topbar toggles
- **Footer Configurations**: `footer_elm`, `footer_elm_mobile`
- **Color Palettes**: `color_primary`, `color_hot_label`
- **Typography Tokens**: `type_headings`, `type_texts`, `type_nav`, `type_banner`, `type_price`
- **WooCommerce UX & Swatches**: `enable_nasa_variations_ux`, `nasa_variations_ux_item`, `gallery_images_variation`
- **Shop Catalog & Grid Layouts**: `products_per_row`, `products_pr_page`, `products_layout_style`, sidebar layouts
- **Product Badges & Discount Labels**: `sale_badge`, `loop_discount_slide`

---

## Production Migration Procedure

When deploying child-theme activation and custom architecture to the production environment, follow this strict 8-step sequence:

1. **Full Production Backup**: Perform a complete backup of the production database and `wp-content/` directory.
2. **Deploy Project Code**: Deploy project-owned code (`wp-content/themes/elessi-theme-child/` and `wp-content/plugins/shopbiog-core/`) to the production web server.
3. **Inspect Production Theme Mods**: Compare production database options:
   - `theme_mods_elessi-theme`
   - `theme_mods_elessi-theme-child`
4. **Synchronize Theme Mods**: Programmatically copy/synchronize missing or updated required theme mod keys from `theme_mods_elessi-theme` into `theme_mods_elessi-theme-child`.
5. **Verify Configuration Settings**: Confirm menu locations (`nav_menu_locations`), logo IDs, custom header builders, and footer block IDs.
6. **Activate Child Theme**: Execute theme switch to `elessi-theme-child` via WP-CLI (`wp theme activate elessi-theme-child`) or WordPress admin.
7. **Execute Visual Regression & Smoke Checks**: Verify key frontend pages (Homepage, Shop, Product, Cart, Checkout, My Account) for 1:1 visual match and zero errors.
8. **Emergency Rollback Procedure**: If any visual or functional discrepancies appear, execute `wp theme activate elessi-theme` immediately to restore parent theme state.
