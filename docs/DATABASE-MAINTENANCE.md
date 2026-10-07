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
