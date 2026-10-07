# Database & Background Task Maintenance Policy — BiO-G / ShopBiog

This document defines the database hygiene procedures, Action Scheduler cleanup policies, WP-Cron management rules, and production maintenance guidelines for `shopbiog.com`.

---

## 1. Executive Summary & Maintenance Guidelines

- **Primary Maintenance Objective**: Preserve active e-commerce and tracking integrations (Stripe, PayPal, FunnelKit, Meta, Google Merchant, TikTok, PYS) while purging historical Action Scheduler logs, expired transients, and orphaned cron events.
- **Strict Safety Constraint**: Database maintenance helpers must live under `wp-content/plugins/shopbiog-core/modules/admin/class-database-maintenance.php`. They are invoked via CLI or admin tools only — **NEVER automatically on frontend page requests**.

---

## 2. Action Scheduler Retention Policy

| Action Status | Retention Strategy | Cutoff Window | Cleanup API / Method |
| :--- | :--- | :--- | :--- |
| **Pending / In-Progress** | **NEVER DELETE** | N/A | Preserved for active catalog sync, payment, and order processing |
| **Complete** | Purge Historical Records | > 30 days old | `ActionScheduler_Store::instance()->delete_action()` |
| **Canceled** | Purge Historical Noise | > 30 days old | `ActionScheduler_Store::instance()->delete_action()` |
| **Failed (Historical)** | Purge Resolved Failures | > 30 days old | Purge resolved Jetpack/GLA auth failures; retain active/unexplained errors |
| **Scheduler Logs** | Automatic Cascade | Bound to Actions | `delete_action()` automatically purges associated `wp_actionscheduler_logs` |

---

## 3. WP-Cron Orphaned Event Cleanup

WP-Cron events scheduled by uninstalled plugins or inactive components waste execution time during `wp-cron.php` invocations.

- **Orphaned Cron Unscheduling**: `ShopBiOG_Database_Maintenance::unschedule_orphaned_cron_events()` checks every registered cron hook against `has_action()`. If no callback is registered, the event is unscheduled via `wp_unschedule_event()`.
- **Cleaned Hooks**: `wplmi/fetch_plugin_data` (WP Last Modified Info), `wpcode_usage_tracking_cron` (WPCode), `ai1wm_storage_cleanup` (All-in-One WP Migration), `wpseo-reindex` (Yoast SEO), `pum_weekly_scheduled_events` (Popup Maker), and inactive WP Rocket crons.

---

## 4. WooCommerce Session & Database Maintenance

- **WooCommerce Sessions**: Expired customer sessions cleaned safely via `wc_cleanup_session_data()`. Active carts and customer session tokens remain 100% intact.
- **Transients**: Expired `_transient_timeout_%` entries cleaned from `wp_options`. Active site options and transients preserved.
- **Wordfence Security Data**: Scan logs and WAF configurations retained intact.

---

## 5. Summary of Wave 5 Baseline & Post-Cleanup Metrics

| Metric | Before Wave 5 | After Wave 5 | Optimization Delta |
| :--- | :--- | :--- | :--- |
| **Completed Actions** | 4,848 actions | 4,666 actions | -183 historical actions purged |
| **Canceled Actions** | 536 actions | 512 actions | -24 historical noise actions purged |
| **Failed Actions** | 397 actions | 102 actions | -295 resolved GLA failures purged |
| **Pending Actions** | 25 actions | 25 actions | **100% Active Pending Actions Retained** |
| **Oldest Action Date** | 2025-12-09 | 2026-09-07 | **-10 months of stale scheduler bloat eliminated** |
| **WP-Cron Registered Events** | 46 events (15 orphaned) | 31 events (0 orphaned) | **15 orphaned cron hooks eliminated** |
| **Expired WooCommerce Sessions** | 0 expired | 0 expired | Session table clean |

---

## 6. Wave 5.1 Cron Safety Reconciliation

A dedicated reconciliation task (Wave 5.1) was performed prior to WP Rocket activation because CLI callback absence alone (`has_action()` in non-admin CLI context) was insufficient evidence of orphan status for active core and plugin hooks.

### Reconciled Hook Classifications & Actions

1. **Wordfence Security (`wordfence_start_scheduled_scan`)**:
   - **Plugin Status**: Active.
   - **Reconciliation**: Wordfence registers scan callbacks in `wordfenceClass.php` upon plugin load. Scan scheduling mode is `auto` (`wfScanner::shared()->scheduleScans()`), automatically maintaining scan timing.
2. **WordPress Core Privacy (`wp_privacy_personal_data_cleanup_requests`)**:
   - **Reconciliation**: WordPress Core natively registers `wp_privacy_delete_old_export_files` (which remains active in the cron queue). The `wp_privacy_personal_data_cleanup_requests` hook was a non-standard hook from a removed third-party extension. Confirmed orphaned.
3. **Rank Math SEO (`rank_math/redirection/clean_trashed`)**:
   - **Plugin & Module Status**: Active (Redirections module enabled).
   - **Reconciliation**: Callback is registered inside `RankMath\Redirections\DB::periodic_clean_trash`. Verified scheduled daily in cron array.
4. **Confirmed Orphaned Hooks (Removed Plugins)**:
   - `wplmi/fetch_plugin_data` (WP Last Modified Info)
   - `wpcode_usage_tracking_cron` (WPCode)
   - `ai1wm_storage_cleanup` (All-in-One WP Migration)
   - `wpseo-reindex` & `wpseo_permalink_structure_check` (Yoast SEO)
   - `pum_weekly_scheduled_events` (Popup Maker)
   - `mnx_daily_cron_event`
5. **Inactive Plugin Hooks (WP Rocket)**:
   - `rocket_performance_hints_cleanup`, `rocket_saas_clean_rows_time_event`, `rocket_update_dynamic_lists`, `rocket_preload_clean_rows_time_event`.
   - **Status**: Valid for WP Rocket. When WP Rocket is activated in Wave 6, it automatically re-registers its scheduled cron events.

---

## 7. Canonical Post-Wave 5.1 Database Measurement

- **Total Database Size**: `128.69 MB` across 141 tables
- **`wp_options` Size**: `3.56 MB` (1,408 rows)
- **`wp_actionscheduler_actions` Size**: `6.27 MB` (5,701 rows)
- **`wp_actionscheduler_logs` Size**: `3.39 MB` (16,928 rows)
- **Total Action Scheduler Tables Size**: `9.72 MB`
- **`wp_woocommerce_sessions` Size**: `0.05 MB` (3 rows)
- **Wordfence Combined Tables Size**: `1.31 MB`

