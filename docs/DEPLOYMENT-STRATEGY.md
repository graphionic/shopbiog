# Deployment Strategy & Operations — BiO-G / ShopBiog

This document defines the deployment pipeline, quality assurance procedures, and production environment rules for `shopbiog`.

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
  │   - Controlled deployment to live host                    │
  │   - Database migration check & cache purge                  │
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
