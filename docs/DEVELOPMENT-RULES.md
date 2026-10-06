# Mandatory Development Rules — BiO-G / ShopBiog

All developers, automated assistants, and AI agents contributing to this project must adhere strictly to these 20 development rules.

---

## The 20 Mandatory Rules

* **RULE 1:** Never edit WordPress core files.
* **RULE 2:** Never edit WooCommerce core files.
* **RULE 3:** Never edit Elessi parent theme (`elessi-theme`) source files.
* **RULE 4:** Never edit third-party plugin source files.
* **RULE 5:** All custom functionality belongs in project-owned code directories.
* **RULE 6:** Visual and theme presentation customizations belong exclusively in `elessi-theme-child`.
* **RULE 7:** Functional, business logic, and backend customizations belong in `shopbiog-core`.
* **RULE 8:** Do not copy or override WooCommerce templates unless hooks and filters are strictly incapable of solving the requirement.
* **RULE 9:** Avoid unnecessary third-party software dependencies.
* **RULE 10:** Never install a new plugin for functionality that can be implemented cleanly with small, maintainable custom code.
* **RULE 11:** Do not remove or deactivate plugins until actual usage, shortcodes, and dependencies are verified.
* **RULE 12:** Performance optimizations must never break core functionality (Checkout, Tracking, Variations, Cart, AJAX Add-to-Cart, or Mobile Nav).
* **RULE 13:** Every meaningful code change must be developed and tested on branch `staging` first.
* **RULE 14:** The `main` branch must remain production-ready at all times.
* **RULE 15:** Never commit or expose passwords, API keys, Stripe secrets, salts, or credentials.
* **RULE 16:** Keep custom implementation modular, well-commented, and documented in `/docs`.
* **RULE 17:** Avoid large monolithic functions or bloated files; structure code into clean, single-responsibility modules.
* **RULE 18:** Prefer update-safe WordPress APIs and hooks over fragile DOM manipulation or client-side hacks.
* **RULE 19:** Avoid unnecessary `!important` flags in CSS; use proper CSS specificity and cascading rules.
* **RULE 20:** UX and performance decisions must be evidence-driven and empirically verified.
