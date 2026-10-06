# Git Workflow & Branching Strategy — BiO-G / ShopBiog

This document defines the Git repository management standards, branch roles, and pull request procedures for `shopbiog`.

---

## Branch Structure

```
                  ┌──────────────────────────────┐
                  │             main             │
                  │ (Production-Ready Baseline)  │
                  └──────────────▲───────────────┘
                                 │ Merge (after QA)
                  ┌──────────────┴───────────────┐
                  │           staging            │
                  │ (Active Development Branch)  │
                  └──────────────▲───────────────┘
                                 │ Merge
        ┌────────────────────────┼────────────────────────┐
        │                        │                        │
┌───────┴──────┐         ┌───────┴──────┐         ┌───────┴──────┐
│  feature/*   │         │ performance/*│         │   design/*   │
└──────────────┘         └──────────────┘         └──────────────┘
```

### Branch Roles

1. **`main` (Production Branch):**
   * Contains only tested, stable, production-ready code.
   * No direct day-to-day development or experimental work is permitted on `main`.
   * Serves as the release baseline for live production deployments.

2. **`staging` (Primary Integration Branch):**
   * Active default working branch for all cleanup, optimization, and feature development.
   * All pull requests and feature merges target `staging`.
   * Local QA and integration testing occur on `staging` before merging to `main`.

3. **Optional Feature Branches (`feature/*`, `fix/*`, `performance/*`, `design/*`):**
   * Used for isolating large or multi-step tasks.
   * Created from `staging` and merged back into `staging` upon completion.

---

## Commit & Workflow Rules

* **No Force Push Rule:** Never use `git push --force` on `main` or `staging` unless explicitly approved.
* **Working Tree Cleanliness:** Always inspect `git status` and `git diff` before committing.
* **Commit Granularity:** One logical task per commit where practical.
* **Commit Messages:** Follow conventional commit formatting (e.g., `docs: ...`, `feat: ...`, `fix: ...`, `refactor: ...`, `chore: ...`).
* **Secret Prevention:** Confirm `.gitignore` exclusions prior to staging files.
