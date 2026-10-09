# UI Component Selection - Phase R1 Arena Direction

This document selects the final BiO-G visual direction by combining the strongest pieces across the five Arena references. It does not select one full project and it does not begin implementation.

## Final Component Matrix

| Component | Winner | Runner-Up | Borrow From | Future Owner |
|---|---|---|---|---|
| Header | Ref 5 | Ref 2 | Ref 2 safer desktop nav spacing; Ref 5 cart/size-guide actions | BiO-G Theme / Elementor Header Builder |
| Mobile Navigation | Ref 3 | Ref 2 | Ref 5 cart badge language, Ref 1 simple drawer structure | BiO-G Theme |
| Full-Width Hero | Ref 5 | Ref 3 | Ref 2 product sculpture callouts, Ref 3 reduced-motion handling | Custom BiO-G Elementor Widget |
| Features | Ref 5 | Ref 3 | Ref 4 sticky image narrative details | Custom BiO-G Elementor Widget |
| Facts / Proof | Ref 5 | Ref 3 | Ref 3 proof ledger discipline, Ref 4 fabric macro labeling | Custom BiO-G Elementor Widget |
| Product Card | Ref 3 | Ref 5 | Ref 5 live variant swap, Ref 2 high-conversion hierarchy | BiO-G Theme / WooCommerce Component + Elementor product widgets |
| Product Grid | Ref 3 | Ref 2 | Ref 5 catalog add-to-cart styling | BiO-G Theme / WooCommerce Component |
| PDP Gallery | Ref 3 | Ref 5 | Ref 4 mobile swipe gallery, Ref 5 thumbnail stage | BiO-G Theme / WooCommerce Component |
| PDP Purchase Panel | Ref 5 | Ref 3 | Ref 2 compact premium panel, Ref 4 bundle logic | BiO-G Theme / WooCommerce Component |
| Variant Selector | Ref 5 | Ref 3 | Ref 3 gliding segment / size guide reveal | BiO-G Theme / WooCommerce Component |
| PDP Mobile Sticky Buy Bar | Ref 5 | Ref 3 | Ref 1 simple sticky bar clarity | BiO-G Theme / WooCommerce Component |
| PDP Trust / Guarantee | Ref 5 | Ref 2 | Ref 3 thin trust bar | BiO-G Theme + Elementor Widget |
| PDP Benefits | Ref 3 | Ref 5 | Ref 4 sticky benefit crossfade | Custom BiO-G Elementor Widget |
| Product Story | Ref 5 | Ref 3 | Ref 4 macro statement sequence | Custom BiO-G Elementor Widget |
| Hero Product Showcase | Ref 5 | Ref 2 | Ref 3 cinematic product scale | Custom BiO-G Elementor Widget |
| UGC / Video | Ref 5 | Ref 3 | Ref 2 center-active carousel | Custom BiO-G Elementor Widget + shopbiog-core data |
| Reviews | Ref 5 | Ref 3 | Ref 2 large testimonial controls | Custom BiO-G Elementor Widget + shopbiog-core data |
| Product Comparison | Ref 5 | Ref 4 | Ref 3 proof ledger caution | Custom BiO-G Elementor Widget |
| Offer / Bundle | Ref 5 | Ref 4 | Ref 2 offer hierarchy | Custom BiO-G Elementor Widget + WooCommerce data |
| CTA | Ref 5 | Ref 2 | Ref 1 minimal single-line close | Custom BiO-G Elementor Widget |
| Newsletter | Ref 5 | Ref 2 | Ref 1 simple form fallback | Elementor Containers + CSS / shopbiog-core form integration |
| Footer | Ref 2 dark footer | Ref 5 brand close | Ref 1 utility footer hierarchy | BiO-G Theme / Elementor Footer Builder |
| Mini Cart | Ref 5 | Ref 3 | Ref 1 simpler quantity controls | BiO-G Theme / WooCommerce Component |
| Size Guide | Ref 5 | Ref 3 | Ref 4 size copy clarity | BiO-G Theme / WooCommerce Component |

## Priority Component Decisions

### Full-Width Hero

Winner: Ref 5.

Use a full-width lifestyle/product hero with clean white/cool-neutral ground, deep green accent, near-black type, simple CTA hierarchy, and a product support tile. It feels premium, modern, fresh, and immediately suitable for socks and underwear.

Use from other references:

- Ref 3: reduced-motion safeguards and restrained line reveal.
- Ref 2: product callouts and sculpture variant as an alternate preset.

Avoid:

- Oversized mobile lines that clip or overflow.
- Unsupported review counts or repeated-wash claims.

### Features

Winner: Ref 5.

Use a sticky desktop feature story: image on one side, five feature beats on the other: Freshness, Comfort, Breathability, Fit, Durability. On mobile, use stacked blocks instead of pinned scroll.

Use from other references:

- Ref 3: sticky image plus changing copy structure.
- Ref 4: progress bars and clear numbered feature rhythm.

### Facts / Proof

Winner: Ref 5, with Ref 3 discipline.

Use a near-black "spec sheet, not lab report" wall: big statements, clean ledger rows, fabric imagery, and only approved metrics. Where real numbers are unavailable, show plain-language proof rows instead of invented counters.

Reject:

- Fake percentages.
- "99 washes" claims.
- Medical/lab styling.
- Molecule/glow diagrams.

### Product Card

Base: Ref 3.

The core product card should include:

- Stable product image ratio.
- Alternate image on hover.
- Product title.
- Price and sale state.
- Rating if real review data exists.
- Color swatches that update image/name/price.
- Size chips where appropriate.
- Add-to-cart state.
- Touch-safe controls visible on mobile.

Controlled variants:

- Standard: Ref 3 high-conversion card.
- Compact: Ref 2 compact/minimal card.
- Feature/Dark: Ref 5 or Ref 3 editorial card for homepage/campaign modules.

### PDP Gallery

Winner: Ref 3.

Use the large image plus vertical thumbnails as the standard desktop PDP gallery. Add touch swipe/fraction counter on mobile. Keep the editorial grid and sticky gallery as presets for hero products or content-rich products, not the default.

Borrow:

- Ref 5: large crossfade stage and curated customer frame.
- Ref 4: mobile swipe deck.

### PDP Purchase Panel

Winner: Ref 5.

The purchase panel should use:

- Sticky desktop buy panel.
- Product name, reviews, price, short value line.
- Live color variant switching.
- Size selector with stock states and size-guide drawer.
- Quantity.
- Add-to-cart with loading/done state.
- Express checkout area.
- Shipping/free-shipping progress where appropriate.
- Guarantee/shipping/returns rows.
- Mini-cart handoff.

Borrow:

- Ref 3: segmented size selector and accordion quality.
- Ref 4: bundle/pack logic for bundle PDPs.
- Ref 2: compact premium version for quick-view or drawer contexts.

### UGC

Winner: Ref 5.

Use a curated vertical video deck with center-weighted cards, poster-first loading, hover/tap play, dimmed inactive cards, and customer quote metadata. It should feel brand-curated, not like a generic social feed.

Borrow:

- Ref 3: active clip carousel and quote/image collage options.
- Ref 2: center-active creator controls.

### Reviews

Winner: Ref 5.

Use a sticky review summary plus draggable review cards. Keep it brand-owned and avoid Amazon references. Use real verified review data only.

Borrow:

- Ref 3: editorial quote as an alternate homepage proof preset.
- Ref 2: compact review carousel.

### CTA

Winner: Ref 5.

Use a full-width near-black section with oversized white type, green accent, one primary action, optional secondary link, and quiet motion. Avoid small generic newsletter-card CTAs as the main close.

### Header And Footer

Header: Ref 5 for concept, Ref 2 for implementation discipline.

The final header should support:

- Desktop logo, shop navigation, search, account, cart.
- Transparent or light mode over hero only if contrast is reliable.
- Sticky compact mode.
- Mobile drawer.
- Cart badge.

Footer: Ref 2 dark footer as base, with Ref 5 brand-close energy.

The final footer should support:

- Strong BiO-G wordmark / brand close.
- Shop, Help, About columns.
- Newsletter.
- Social links.
- Privacy/terms/accessibility links.
- Enough whitespace to feel premium.

## Elementor / Theme / Core Ownership

| Concept | Ownership |
|---|---|
| Hero widget | Custom BiO-G Elementor Widget |
| Sticky feature story | Custom BiO-G Elementor Widget |
| Facts / proof wall | Custom BiO-G Elementor Widget |
| Product showcase | Custom BiO-G Elementor Widget |
| UGC deck | Custom BiO-G Elementor Widget + shopbiog-core data |
| Review wall | Custom BiO-G Elementor Widget + shopbiog-core data |
| Offer / bundle module | Custom BiO-G Elementor Widget + WooCommerce data |
| CTA / newsletter visual shell | Elementor Containers + CSS, with optional custom widget |
| Header / footer templates | BiO-G Theme + Elementor Header/Footer templates |
| Product cards | BiO-G Theme / WooCommerce loop component |
| PDP gallery | BiO-G Theme / WooCommerce single-product component |
| PDP purchase panel | BiO-G Theme / WooCommerce single-product component |
| Variants / swatches | BiO-G Theme / WooCommerce component |
| Mini cart | BiO-G Theme / WooCommerce component |
| Size guide drawer | BiO-G Theme / WooCommerce component + shopbiog-core data |
| Mobile sticky buy bar | BiO-G Theme / WooCommerce component |
| Global design tokens | BiO-G Theme options / design tokens |

## Recommended Widget Presets

Keep presets focused. Do not recreate Elessi-style option bloat.

### BiO-G Hero

- Preset 01: Ref 5 full-width lifestyle/product hero.
- Preset 02: Ref 3 product-led sculpture hero.
- Preset 03: Ref 2 creative split hero.

### BiO-G Features

- Preset 01: Ref 5 sticky five-beat feature story.
- Preset 02: Ref 3 sticky image plus changing copy.
- Preset 03: Ref 4 statement/number system for shorter pages.

### BiO-G Facts / Proof

- Preset 01: Ref 5 near-black proof wall with real metrics or ledger rows.
- Preset 02: Ref 3 proof ledger.
- Preset 03: Ref 4 fabric macro with labels.

### BiO-G Product Showcase

- Preset 01: Ref 5 hero product / campaign showcase.
- Preset 02: Ref 2 oversized product crossing.
- Preset 03: Ref 3 cinematic product scroll moment.

### BiO-G UGC

- Preset 01: Ref 5 center-weighted video deck.
- Preset 02: Ref 3 offset vertical video wall.

### BiO-G Reviews

- Preset 01: Ref 5 review summary plus draggable review wall.
- Preset 02: Ref 3 large editorial quote.
- Preset 03: Ref 2 compact review carousel.

### BiO-G CTA

- Preset 01: Ref 5 full-width near-black final CTA.
- Preset 02: Ref 2 large type plus minimal form.

## Rejected Ideas

- Do not use any design whose persuasive power depends on "99 washes", fake lab percentages, fake review volume, fake customer counts, or Amazon references.
- Do not use warm beige/cream/vintage paper as the dominant BiO-G palette.
- Do not make the brand feel medical, clinical, SaaS-like, or lab-dashboard-like.
- Do not hide purchase-critical actions behind hover on mobile.
- Do not ship horizontal scroll hijacking as a default ecommerce pattern.
- Do not copy reference-board chrome that clips horizontally on mobile.
- Do not use heavy global smooth-scroll if it harms INP, accessibility, or native scroll behavior.

## Final BiO-G Visual Direction

Typography:

- Clean modern sans-serif: Montserrat/Poppins-adjacent.
- Heavy uppercase display for hero and campaign moments.
- Smaller, restrained labels for commerce metadata.
- Avoid script, decorative, cursive, and serif-heavy magazine styling.

Color:

- Clean white and cool light gray as the primary surface.
- Near-black / charcoal for high-impact sections.
- BiO-G green and deeper green as conversion and freshness signals.
- Avoid dominant beige, cream, warm paper, and vintage luxury palettes.

Image Treatment:

- Real product and lifestyle imagery should lead.
- Use macro/fabric shots as supporting proof, not as a clinical science identity.
- Product images need stable aspect ratios and high-quality crops.

Product Card:

- Ref 3 high-conversion card is the base.
- Swatches update image, label, and price.
- Mobile add-to-cart remains visible.
- Feature/dark cards can be used for homepage storytelling.

Hero:

- Ref 5 sets the main direction: full-width, high-impact, lifestyle/product-led, clean CTA hierarchy, motion-aware.
- Mobile type must be tuned so no words clip or overflow.

Features:

- Five-beat sticky/stacked system for Freshness, Comfort, Breathability, Fit, Durability.
- Animation should clarify, not obstruct.

PDP:

- Ref 5 purchase panel plus Ref 3 gallery.
- Buying hierarchy: image, product, reviews, price, value, color, size, add-to-cart, payment, guarantee, shipping, benefits, proof, reviews.
- Mobile gets swipe gallery and sticky add-to-cart.

Motion:

- Premium but restrained.
- Keep line reveals, soft parallax, gallery crossfades, active slide scaling, and add-to-cart states.
- Simplify Lenis/ScrollTrigger on mobile; respect reduced motion.

UGC:

- Curated vertical video deck, not a generic social wall.
- Poster-first, lazy video, tap/hover play, customer metadata.

Mobile:

- Mobile is a first-class buying surface.
- Stacked features replace pinned scroll.
- Sticky buy bar, swipe gallery, visible size/color choices, and bottom-sheet mini-cart are mandatory.

Overall Personality:

- Premium everyday performance.
- Fresh, confident, clean, and modern.
- Apparel-first, not lab-first.
- High-conversion without feeling like a cheap Shopify template.

