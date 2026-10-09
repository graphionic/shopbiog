# UI Reference Audit - Phase R1 Arena Design Selection

Branch: `staging`  
Repository: `C:\wamp64\www\shopbiog`  
Reference folders: `docs/ui references/1` through `docs/ui references/5`

## Scope And Method

This phase inspected the five Arena-generated reference projects for visual direction, conversion patterns, mobile behavior, motion language, and WordPress/Elementor/WooCommerce translation value.

This was an inspection and documentation phase only. No `biog` theme was created. No WooCommerce templates, WordPress settings, plugins, database records, or active site files were modified.

## Rendering Notes

All five projects are React/Vite/Tailwind reference boards with local assets under each project's `public` folder.

Each project installed from its existing lockfile and completed `npm run build` successfully. Vite emitted a local environment warning because the machine is running Node `22.11.0` while Vite requests Node `20.19+` or `22.12+`. Builds still succeeded, so Node was not upgraded.

Headless Chrome evidence was captured for all five references at:

- 1440 desktop
- 1024 laptop
- 768 tablet
- 390 mobile

Capture limitation: the screenshots land at each board's opening state. They verify visual rendering and responsive wrapper behavior, but deeper subsections were audited by combining rendered evidence with component/source inspection. Ref 4's opening screenshot captured during its entrance animation and shows a clipped headline; this is treated as a capture/wrapper limitation, not as proof that every Ref 4 section is unusable.

## Inventory Summary

| Reference | Structure | Entry | Dependencies | Motion / Interaction Libraries | Assets |
|---|---|---|---|---|---|
| Ref 1 | Single broad `App.tsx` section library | `src/main.tsx`, `src/App.tsx` | React, Tailwind, clsx | CSS transitions, keyframes, IntersectionObserver | `public/images` |
| Ref 2 | Modular `refs/*` section system | `src/App.tsx` plus `refs` | React, GSAP, Lenis, Framer Motion, lucide, Tailwind | GSAP, Lenis, parallax, sliders, stateful controls | `public/images` |
| Ref 3 | Componentized board with dedicated section files | `src/sections/*` | React, GSAP, Lenis, SplitType, Swiper | GSAP ScrollTrigger, Lenis, SplitType, Swiper | placeholder assets in `public/bio-g` path |
| Ref 4 | Compact board grouped by domain sections | `src/sections/*` | React, GSAP, Lenis, SplitType, Swiper | GSAP ScrollTrigger, Lenis, magnetic buttons, Swiper | `public/img` |
| Ref 5 | Cohesive commerce prototype with shared shop state | `src/sections/*`, `state/ShopContext.tsx` | React, GSAP, Lenis, Swiper, Tailwind | GSAP ScrollTrigger, Lenis, Swiper, global overlays, sticky buy bar | `public/img` plus stock data |

## Overall Scores

Scoring weights: Visual Direction 20, BiO-G Suitability 15, Conversion Potential 15, Product Presentation 10, PDP Quality 10, Motion / Interaction 10, Mobile 10, Performance Feasibility 5, WordPress/Elementor Translation 5.

| Rank | Reference | Score | Summary |
|---:|---|---:|---|
| 1 | Ref 5 | 90 | Strongest cohesive BiO-G direction. Best full-width hero, features, facts, PDP, UGC, reviews, CTA, cart, and mobile commerce concepts. Some board chrome clips on mobile and several claims must be replaced. |
| 2 | Ref 3 | 84 | Strongest reusable component library depth. Excellent product cards, PDP gallery variants, benefit storytelling, UGC, reviews, and mobile patterns. More fragmented than Ref 5, with heavier animation complexity. |
| 3 | Ref 2 | 79 | Clean, premium, component-rich board with strong product cards and PDP purchase panels. Very good Elementor translation value. Less emotionally distinctive than Ref 5. |
| 4 | Ref 4 | 72 | Strong proof, sticky benefits, and PDP ideas. More editorial and animation-dependent. Capture showed entrance-state clipping and several concepts need simplification for production. |
| 5 | Ref 1 | 68 | Broadest inventory and lowest implementation risk. Useful as fallback patterns and section coverage, but visual language is less refined and less differentiated. |

## Reference 1 Audit

### Rendered Character

Ref 1 is a large single-file section library. It is clean, readable, and comprehensive, but it feels more like a controlled exploration board than a final premium storefront. The whitespace and typography are strong, but many sections are safe rather than memorable.

### Strengths

- Best low-risk inventory for common ecommerce sections.
- Useful fallback product card structures: minimal, conversion-first, image-forward.
- Clear PDP purchase panel variants with color, size, quantity, add-to-cart states, payment note, and trust rows.
- Good mobile interaction concepts: drawer menu, sticky add-to-cart, filter sheet, mobile cart.
- Easy to translate into Elementor containers, CSS, and WooCommerce theme components.

### Weaknesses

- Hero concepts are competent but less ownable than Ref 5 and Ref 3.
- Motion is mostly CSS and IntersectionObserver; polished enough, but not premium enough for flagship moments.
- Some placeholder claims, especially "99 wash-tested performance", must be removed.
- Product imagery and layout often feel like a reference catalog rather than a refined brand system.

### Section Scores

| Section | Visual | Premium | Suitability | Clarity | Conversion | Interaction | Motion | Mobile | Implementation | Perf Risk |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Hero | 7 | 7 | 8 | 8 | 7 | 6 | 6 | 7 | 9 | 9 |
| Features / Benefits | 7 | 7 | 8 | 8 | 7 | 7 | 6 | 8 | 9 | 9 |
| Facts / Proof | 6 | 6 | 6 | 7 | 5 | 5 | 5 | 7 | 8 | 8 |
| Product Cards | 8 | 7 | 8 | 8 | 8 | 7 | 6 | 8 | 9 | 9 |
| PDP | 8 | 7 | 8 | 9 | 8 | 8 | 6 | 8 | 9 | 9 |
| UGC / Reviews | 7 | 7 | 7 | 8 | 7 | 7 | 6 | 7 | 8 | 8 |
| CTA / Footer | 7 | 7 | 7 | 8 | 7 | 6 | 6 | 8 | 9 | 9 |

### Motion Findings

- CSS transitions: KEEP for product hover, buttons, accordions.
- IntersectionObserver reveals: KEEP for lower-priority sections.
- Sticky mobile buy bar: KEEP.
- "99 washes" proof counter: REJECT as content, not as layout.

## Reference 2 Audit

### Rendered Character

Ref 2 is a polished modular reference board with Poppins-like modern typography, cool neutrals, strong green accents, and a very implementation-friendly structure. It has 41 independent explorations and a clean section index. It feels more mature than Ref 1 but less brand-defining than Ref 5.

### Strengths

- Strong product card base, especially the high-conversion card with visible rating, swatches, size chips, and add states.
- PDP purchase panel variants are highly useful: minimal, conversion-first, compact premium.
- Large image plus vertical thumbnails and mobile swipe gallery are solid PDP gallery patterns.
- Good UGC options: offset wall and centered creator carousel.
- Strong header/footer reference coverage.
- Very good future Elementor/WooCommerce translation value because each concept is already modular.

### Weaknesses

- Some hero content uses unsupported review counts and wash claims.
- Several advanced motion ideas, such as horizontal pinned story and cursor-follow images, are better as optional campaign presets than default ecommerce behavior.
- Slightly cooler and more systemized than ideal for an emotional D2C apparel brand.

### Section Scores

| Section | Visual | Premium | Suitability | Clarity | Conversion | Interaction | Motion | Mobile | Implementation | Perf Risk |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Hero | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 7 |
| Features / Benefits | 8 | 8 | 8 | 8 | 7 | 8 | 8 | 7 | 8 | 6 |
| Facts / Proof | 8 | 8 | 8 | 8 | 7 | 6 | 7 | 8 | 8 | 8 |
| Product Cards | 9 | 8 | 9 | 9 | 9 | 9 | 8 | 8 | 9 | 8 |
| PDP | 9 | 8 | 9 | 9 | 9 | 9 | 8 | 8 | 9 | 8 |
| UGC / Reviews | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 8 | 7 |
| CTA / Footer | 8 | 8 | 8 | 8 | 7 | 7 | 7 | 8 | 9 | 8 |

### Motion Findings

- GSAP reveal / line stagger: KEEP for hero and campaign sections.
- Lenis smooth scroll: SIMPLIFY. Use cautiously and disable on mobile/reduced motion.
- Cursor-follow category images: SIMPLIFY or make desktop-only.
- Pinned horizontal story: SIMPLIFY. High impact, but not default ecommerce behavior.
- Swiper and stateful controls: KEEP.

## Reference 3 Audit

### Rendered Character

Ref 3 is refined and designerly, with strong typography, disciplined section structure, and high-quality motion. It is a deeper component library than Ref 5, but less cohesive as a single storefront direction. It contains some of the best product cards, PDP gallery thinking, UGC, reviews, and benefits.

### Strengths

- Best candidate for product card base: high-conversion card has swatches, size selection, hover alternate image, add states, and mobile-safe critical controls.
- Strongest PDP gallery system overall: large thumbnail gallery, editorial grid, sticky gallery, and mobile swipe gallery.
- Benefit storytelling is excellent: sticky image plus changing copy is ideal for Freshness, Comfort, Breathability, Fit, Durability.
- UGC and reviews are curated, premium, and less plugin-like.
- Reduced-motion handling exists in the motion utility.

### Weaknesses

- More animation-heavy than necessary for a WooCommerce build.
- Board chrome has evidence of clipping/overflow at 1440 in captured screenshot; storefront components should not inherit this wrapper.
- Some placeholder review counts and claims need replacement.

### Section Scores

| Section | Visual | Premium | Suitability | Clarity | Conversion | Interaction | Motion | Mobile | Implementation | Perf Risk |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Hero | 8 | 9 | 8 | 8 | 8 | 8 | 9 | 8 | 8 | 7 |
| Features / Benefits | 9 | 9 | 9 | 8 | 8 | 9 | 9 | 8 | 8 | 6 |
| Facts / Proof | 9 | 9 | 9 | 9 | 7 | 7 | 8 | 8 | 8 | 7 |
| Product Cards | 9 | 9 | 9 | 9 | 9 | 9 | 8 | 9 | 9 | 8 |
| PDP Gallery | 10 | 9 | 9 | 9 | 8 | 9 | 9 | 9 | 8 | 7 |
| PDP Panel | 8 | 8 | 9 | 9 | 9 | 9 | 8 | 8 | 9 | 8 |
| UGC / Reviews | 9 | 9 | 8 | 8 | 8 | 9 | 9 | 9 | 8 | 7 |
| CTA / Footer | 8 | 8 | 8 | 8 | 7 | 7 | 7 | 8 | 8 | 8 |

### Motion Findings

- GSAP ScrollTrigger reveals and SplitType: KEEP for hero, benefits, reviews, and story sections with reduced-motion support.
- Lenis: SIMPLIFY. Avoid global scroll smoothing unless performance remains excellent.
- Swiper mobile gallery and review carousel: KEEP.
- Sticky gallery and benefits: KEEP as premium presets, not always-on defaults.
- Offset UGC video wall: KEEP, but lazy-load video and use poster on mobile.

## Reference 4 Audit

### Rendered Character

Ref 4 is modern, minimal, and high-contrast. It contains strong PDP purchase panel, gallery, sticky benefits, and proof concepts. It is more dramatic and animation-reliant than Ref 2. The capture showed the opening board headline in an animated/clipped state, so rendered evidence for the opening wrapper is limited.

### Strengths

- Strong sticky benefit image narrative with clear feature progression.
- Strong "quiet proof" section: macro fabric plus labels and plain-language specs.
- Good mobile PDP gallery and sticky add-to-cart reference.
- Conversion-first PDP panel has bundle logic, proof up top, sticky purchase behavior, and staged add-to-cart.
- Palette is largely compatible: white, cool neutral, charcoal, green.

### Weaknesses

- More editorial and animation-led than the final BiO-G commerce system should be.
- Opening board capture indicates a risk of text clipping during animation.
- Some phrases imply claims that need validation, such as wash-after-wash durability and odor-control specifics.
- Rounded card-heavy wrappers should be restrained in final implementation.

### Section Scores

| Section | Visual | Premium | Suitability | Clarity | Conversion | Interaction | Motion | Mobile | Implementation | Perf Risk |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Hero | 8 | 8 | 8 | 7 | 7 | 7 | 8 | 7 | 8 | 7 |
| Features / Benefits | 9 | 8 | 9 | 8 | 8 | 8 | 9 | 8 | 8 | 6 |
| Facts / Proof | 9 | 8 | 9 | 9 | 7 | 7 | 8 | 8 | 8 | 7 |
| Product Cards | 8 | 8 | 8 | 8 | 8 | 8 | 7 | 8 | 8 | 8 |
| PDP | 9 | 8 | 9 | 8 | 9 | 9 | 8 | 9 | 8 | 7 |
| UGC / Reviews | 7 | 7 | 7 | 7 | 7 | 7 | 7 | 8 | 8 | 7 |
| CTA / Footer | 7 | 8 | 7 | 8 | 7 | 7 | 7 | 8 | 8 | 8 |

### Motion Findings

- Mask reveals and line reveals: KEEP in hero/story if tested against clipping.
- Sticky benefit image crossfade: KEEP.
- Magnetic buttons: SIMPLIFY to CSS hover or limited JS.
- Cursor zoom: SIMPLIFY for desktop only.
- Heavy ScrollTrigger entrance states: SIMPLIFY to avoid blank/clipped capture states.

## Reference 5 Audit

### Rendered Character

Ref 5 is the strongest complete BiO-G direction. It feels like a premium modern D2C apparel commerce experience rather than a generic reference board. It uses clean white, near-black, green, deep green, high-impact product/lifestyle imagery, and strong conversion components. It is the most suitable foundation for the final design brief.

### Strengths

- Strongest full-width hero: image-led, product-supported, clear CTAs, premium typography, strong green/charcoal identity.
- Strongest features: pinned split feature story with five beats and mobile stacked fallback.
- Strongest facts/proof wall: near-black, bold but not lab-like, explicit warning not to invent numbers.
- Strongest PDP purchase panel: sticky buy panel, live variant switching, size guide, quantity, express checkout, assurance rows, cart progress, and mini-cart integration.
- Strongest global commerce state: mini cart, size guide drawer, mobile sticky buy bar.
- Strongest UGC, reviews, and final CTA system.
- Best final visual personality for BiO-G.

### Weaknesses

- Board/navigation chrome clips on 390px mobile screenshot; final storefront header must not copy that wrapper.
- Some placeholder copy still references repeated washes and review counts; content must be replaced with approved claims.
- Motion stack is heavy and should be simplified for production: no unnecessary Lenis on mobile, lazy-load video, preserve INP.
- Hero uses very large display type; final mobile needs tighter line control to avoid clipping.

### Section Scores

| Section | Visual | Premium | Suitability | Clarity | Conversion | Interaction | Motion | Mobile | Implementation | Perf Risk |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Hero | 10 | 10 | 10 | 9 | 9 | 9 | 9 | 8 | 8 | 7 |
| Features | 10 | 9 | 10 | 9 | 8 | 9 | 9 | 9 | 8 | 6 |
| Facts / Proof | 10 | 9 | 10 | 9 | 8 | 8 | 8 | 8 | 8 | 7 |
| Product Cards | 9 | 9 | 9 | 9 | 9 | 9 | 8 | 9 | 8 | 8 |
| PDP | 10 | 10 | 10 | 9 | 10 | 10 | 9 | 9 | 8 | 7 |
| UGC / Reviews | 10 | 9 | 9 | 9 | 9 | 9 | 9 | 9 | 8 | 7 |
| CTA / Footer | 10 | 9 | 10 | 9 | 9 | 8 | 9 | 8 | 8 | 7 |

### Motion Findings

- Full-bleed hero slow zoom, line reveal, product parallax: KEEP, with reduced-motion fallback.
- Sticky feature story: KEEP on desktop, stacked static blocks on mobile.
- Facts counters and ledger stagger: KEEP only for real approved numbers; otherwise show ledger rows.
- Swiper gallery/UGC/reviews: KEEP with lazy-loading and touch-safe controls.
- Lenis: SIMPLIFY. Avoid on mobile and test INP before release.
- Mobile sticky buy bar: KEEP.
- Mini-cart drawer, size-guide drawer: KEEP as WooCommerce/theme components.

## Cross-Reference Section Map

| Category | Ref 1 | Ref 2 | Ref 3 | Ref 4 | Ref 5 |
|---|---|---|---|---|---|
| Header / Navigation | Present | Present | Present | Present | Present |
| Full-width Hero | Present | Present | Present | Present | Strongest |
| Features | Present | Present | Strong | Strong | Strongest |
| Facts / Proof | Present but claim risk | Strong | Strong | Strong | Strongest |
| Product Card | Present | Strong | Strongest | Strong | Strong |
| Product Grid | Present | Present | Present | Present | Present |
| Hero Product Showcase | Present | Strong | Strong | Strong | Strongest |
| PDP Gallery | Present | Strong | Strongest | Strong | Strong |
| PDP Purchase Panel | Present | Strong | Strong | Strong | Strongest |
| Variants / Swatches | Present | Strong | Strong | Strong | Strongest |
| PDP Benefits | Present | Strong | Strongest | Strong | Strong |
| Product Story | Present | Strong | Strong | Strong | Strong |
| Trust / Guarantee | Present | Strong | Strong | Strong | Strongest |
| UGC / Video | Present | Strong | Strong | Present | Strongest |
| Reviews | Present | Strong | Strong | Present | Strongest |
| Product Comparison | Limited | Limited | Proof-led | Present | Strong |
| Offer / Bundle | Present | Strong | Present | Strong | Strong |
| CTA | Present | Strong | Present | Present | Strongest |
| Newsletter | Present | Strong | Present | Present | Strong |
| Footer | Present | Strong | Strong | Present | Strong |
| Mobile Navigation | Present | Strong | Strong | Strong | Needs wrapper fix, concepts strong |
| Mobile Sticky Add to Cart | Present | Strong | Strong | Strong | Strongest |
| Mini Cart | Present | Strong | Strong | Present | Strongest |
| Motion / Scroll | Basic | Strong | Strong | Strong | Strongest |

## Rejected Or Penalized Concepts

- Any content that relies on "99 washes", "99 wash", invented lab percentages, invented review counts, fake FOMO, or Amazon references.
- Dominant beige, cream, vintage paper, or editorial perfume styling. None should become the main BiO-G direction.
- Lab UI, molecule visuals, glowing science dashboards, or overly clinical proof.
- Scroll hijacking, always-on smooth scrolling on mobile, or pinned horizontal scroll as default shopping behavior.
- Critical commerce actions hidden behind hover on touch devices.
- Board chrome clipping from Ref 3/Ref 5 and animated headline clipping from Ref 4.

## Best-Of Winners

- Strongest hero: Ref 5
- Strongest features: Ref 5
- Strongest facts/proof: Ref 5, with proof discipline from Ref 3
- Strongest product cards: Ref 3
- Strongest PDP gallery: Ref 3
- Strongest PDP purchase panel: Ref 5
- Strongest variant selector: Ref 5, with Ref 3's size control ideas
- Strongest product showcase: Ref 5
- Strongest UGC: Ref 5
- Strongest reviews: Ref 5
- Strongest CTA: Ref 5
- Strongest header: Ref 5 conceptually, Ref 2 for safer implementation
- Strongest footer: Ref 2 dark footer / Ref 5 brand close
- Best mobile commerce: Ref 5 concepts, Ref 3 mobile gallery as fallback
- Best animation language: Ref 5, simplified with Ref 3's reduced-motion discipline

