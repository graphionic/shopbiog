# BiO-G / ShopBiog Modernization Documentation

Welcome to the official documentation foundation for the **BiO-G** (`shopbiog`) WooCommerce website modernization project.

## Overview
This documentation serves as the **single source of truth** for all architectural decisions, development standards, design principles, performance targets, and Git workflows for `shopbiog`. 

The objective of this project is to transform a restored production backup into a high-performance, update-safe, premium eCommerce store while maintaining robust security, data integrity, and seamless checkout operations.

## Governing Principle
> [!IMPORTANT]  
> **Source of Truth Rule:**  
> If any proposed or current code implementation conflicts with the rules and architecture defined in these documentation files, **implementation must immediately stop**, and the architecture/rules must be reviewed and aligned first.

---

## Documentation Structure

| Document | Scope & Purpose |
| :--- | :--- |
| [`PROJECT-GOALS.md`](./PROJECT-GOALS.md) | Defines the 16 core business & technical objectives and out-of-scope boundaries. |
| [`SYSTEM-ARCHITECTURE.md`](./SYSTEM-ARCHITECTURE.md) | Maps vendor-owned vs project-owned code boundaries and extension hierarchies. |
| [`DEVELOPMENT-RULES.md`](./DEVELOPMENT-RULES.md) | Establishes the 20 mandatory development rules for code quality and maintainability. |
| [`GIT-WORKFLOW.md`](./GIT-WORKFLOW.md) | Defines branch management, commit rules, and release flow (`staging` -> `main`). |
| [`PLUGIN-STRATEGY.md`](./PLUGIN-STRATEGY.md) | Framework for evaluating, optimizing, replacing, or removing third-party plugins. |
| [`PERFORMANCE-STRATEGY.md`](./PERFORMANCE-STRATEGY.md) | Targets Core Web Vitals (LCP, INP, CLS, TTFB) and asset optimization rules. |
| [`UX-DESIGN-PRINCIPLES.md`](./UX-DESIGN-PRINCIPLES.md) | Mobile-first, premium design guidelines, typography, and product page clarity. |
| [`DEPLOYMENT-STRATEGY.md`](./DEPLOYMENT-STRATEGY.md) | Safe deployment pipeline from local development to production. |
| [`ROADMAP.md`](./ROADMAP.md) | High-level 11-phase project execution roadmap. |

---

## Governance & Team Alignment
This documentation applies to all contributors, including **Antigravity**, **Arena**, **ChatGPT**, and future human developers. All pull requests and code reviews on branch `staging` must comply with these guidelines.
