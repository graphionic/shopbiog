<?php
/**
 * Template Name: BiO-G UI Lab
 * Description: Dedicated internal visual preview page for BiO-G Design System components.
 * Location: wp-content/themes/elessi-theme-child/page-ui-lab.php
 *
 * @package ShopBiOG\ChildTheme
 */

// Load WordPress Header
get_header();

// Enqueue dedicated Design System CSS
wp_enqueue_style('biog-design-tokens', get_stylesheet_directory_uri() . '/assets/css/design-tokens.css', [], '1.0.0');
wp_enqueue_style('biog-ui-lab', get_stylesheet_directory_uri() . '/assets/css/ui-lab.css', ['biog-design-tokens'], '1.0.0');
?>

<div class="biog-ui-lab">

  <!-- LAB HEADER -->
  <div class="biog-lab-header">
    <div class="biog-lab-title">
      <h1>BiO-G UI Lab & Style Guide</h1>
      <p>Brand Direction: <strong>BiO-G Premium Everyday</strong> | Internal Preview & Review Page</p>
    </div>
    <div class="biog-lab-status-tag">Status: Draft / Pending Approval</div>
  </div>

  <div class="biog-lab-container">

    <!-- 1. COLOR SYSTEM -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>1. Color System Palette Options</h2>
        <p>Three proposed premium apparel color palettes. Bright mint green is replaced by deep forest/botanical tones.</p>
      </div>

      <div class="biog-palette-grid">

        <!-- Option A -->
        <div class="biog-palette-card">
          <div class="biog-palette-card-head">
            <h3>Option A — Deep Botanical</h3>
          </div>
          <div class="biog-swatch-list">
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#2A5A44;"></div>Primary (#2A5A44)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#1A201C;"></div>Dark (#1A201C)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#FAFAF8;"></div>Background (#FAFAF8)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#438B6A;"></div>Accent (#438B6A)</div>
          </div>
        </div>

        <!-- Option B (Baseline) -->
        <div class="biog-palette-card" style="border: 2px solid var(--biog-color-primary);">
          <div class="biog-palette-card-head" style="background: #E6F0EB;">
            <h3>Option B — Premium Forest (Recommended)</h3>
          </div>
          <div class="biog-swatch-list">
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#1B4D3E;"></div>Primary Forest (#1B4D3E)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#111827;"></div>Near Black (#111827)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#FDFBF7;"></div>Cream BG (#FDFBF7)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#3B7A66;"></div>Gentle Sage (#3B7A66)</div>
          </div>
        </div>

        <!-- Option C -->
        <div class="biog-palette-card">
          <div class="biog-palette-card-head">
            <h3>Option C — Modern Heritage</h3>
          </div>
          <div class="biog-swatch-list">
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#2D4F3A;"></div>Dark Olive (#2D4F3A)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#1C2421;"></div>Graphite (#1C2421)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#FAF9F5;"></div>Ivory BG (#FAF9F5)</div>
            <div class="biog-swatch-item"><div class="biog-swatch-color" style="background:#48775A;"></div>Fresh Accent (#48775A)</div>
          </div>
        </div>

      </div>
    </div>


    <!-- 2. TYPOGRAPHY SYSTEM -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>2. Typography System Directions</h2>
        <p>Comparing Premium Sans-Only vs Editorial Heading + Sans Body options.</p>
      </div>

      <div class="biog-typo-grid">

        <!-- Option 1 -->
        <div class="biog-typo-card">
          <h3>Option 1 — Premium Sans-Only (Poppins)</h3>
          <h1 style="font-family:'Poppins'; font-size:32px; font-weight:700; margin:12px 0;">All-Day Freshness. Unmatched Comfort.</h1>
          <p style="font-family:'Poppins'; font-size:15px; color:#4B5563;">Engineered with odor-resistant zinc technology to keep your feet fresh from morning workout to evening commute.</p>
        </div>

        <!-- Option 2 -->
        <div class="biog-typo-card">
          <h3>Option 2 — Editorial Heading (Playfair) + Sans Body</h3>
          <h1 style="font-family:'Playfair Display', Georgia, serif; font-size:34px; font-weight:600; margin:12px 0; font-style:italic;">Thoughtfully Crafted Essentials.</h1>
          <p style="font-family:'Poppins'; font-size:15px; color:#4B5563;">Refined everyday apparel built with invisible freshness technology for quiet confidence.</p>
        </div>

      </div>
    </div>


    <!-- 3. BUTTON SYSTEM -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>3. Button System</h2>
        <p>44px–48px target heights, soft modern 8px corners, confident contrast.</p>
      </div>

      <div class="biog-btn-group">
        <a class="biog-btn biog-btn-primary" href="#">Shop Odor-Free Socks</a>
        <a class="biog-btn biog-btn-secondary" href="#">Explore Collection</a>
        <a class="biog-btn biog-btn-outline" href="#">View Size Guide</a>
        <a class="biog-btn biog-btn-tertiary" href="#">Learn how it works &rarr;</a>
      </div>

      <div style="margin-top:16px;">
        <span class="biog-btn biog-btn-primary" style="opacity:0.5; cursor:not-allowed;">Disabled Primary</span>
      </div>
    </div>


    <!-- 4. PRODUCT CARD PREVIEWS -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>4. Product Card System</h2>
        <p>Standardized 350:447 aspect ratios, 2-line title clamps, 32px swatch touch targets.</p>
      </div>

      <div class="biog-card-grid">

        <!-- Card 1 -->
        <div class="biog-product-card">
          <div class="biog-card-media">
            <span class="biog-card-badge">Best Seller</span>
            <img src="<?php echo site_url('/wp-content/uploads/2024/05/socks-1.jpg'); ?>" alt="Performance Socks" onerror="this.src='https://via.placeholder.com/350x447/f4f1ea/1b4d3e?text=BiO-G+Socks';">
          </div>
          <div class="biog-card-content">
            <div class="biog-card-title">Performance Unisex Crew Socks (3-Pack)</div>
            <div style="display:flex; gap:6px; margin-bottom:8px;">
              <span style="width:20px; height:20px; border-radius:50%; background:#111827; border:2px solid #1B4D3E;"></span>
              <span style="width:20px; height:20px; border-radius:50%; background:#FFFFFF; border:1px solid #D1D5DB;"></span>
              <span style="width:20px; height:20px; border-radius:50%; background:#6B7280; border:1px solid #D1D5DB;"></span>
            </div>
            <div class="biog-card-price"><del>$34.99</del> $24.99</div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="biog-product-card">
          <div class="biog-card-media">
            <span class="biog-card-badge" style="background:#C93B3B;">Save 20%</span>
            <img src="<?php echo site_url('/wp-content/uploads/2024/05/boxers-1.jpg'); ?>" alt="Everyday Boxers" onerror="this.src='https://via.placeholder.com/350x447/f4f1ea/1b4d3e?text=BiO-G+Boxers';">
          </div>
          <div class="biog-card-content">
            <div class="biog-card-title">Everyday Odor-Resistant Boxers</div>
            <div class="biog-card-price">$29.99</div>
          </div>
        </div>

      </div>
    </div>


    <!-- 5. PRODUCT PURCHASE PANEL MOCK -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>5. Product Purchase Panel Preview</h2>
        <p>Refined purchase hierarchy with high-contrast size selection and guarantee badges.</p>
      </div>

      <div class="biog-purchase-panel">
        <div>
          <img src="https://via.placeholder.com/500x600/f4f1ea/1b4d3e?text=BiO-G+Product+Hero" alt="Product Hero" style="width:100%; border-radius:var(--biog-radius-md);">
        </div>
        <div>
          <div style="font-size:13px; color:var(--biog-color-primary); font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">24/7 Odor Control</div>
          <h2 style="font-size:26px; font-weight:700; margin:0 0 8px 0;">Performance Unisex Crew Socks</h2>
          <div style="font-size:22px; font-weight:700; color:var(--biog-color-dark); margin-bottom:16px;">$24.99 <span style="font-size:14px; color:#6B7280; font-weight:400;">(Set of 3)</span></div>

          <p style="font-size:14px; color:#4B5563; line-height:1.6; margin-bottom:20px;">Lightweight, breathable crew socks engineered with zinc antibacterial technology to keep feet fresh and comfortable all day long.</p>

          <label style="font-size:14px; font-weight:600;">Select Size:</label>
          <div class="biog-size-picker">
            <div class="biog-size-pill">S</div>
            <div class="biog-size-pill active">M</div>
            <div class="biog-size-pill">L</div>
            <div class="biog-size-pill">XL</div>
          </div>

          <button class="biog-btn biog-btn-primary" style="width:100%; height:48px; font-size:16px;">Add to Cart — $24.99</button>

          <div style="margin-top:20px; padding:16px; background:#F9FAFB; border-radius:var(--biog-radius-md); font-size:13px; color:#4B5563;">
            ✓ 30-Day Freshness Guarantee &bull; Free USA Shipping over $40
          </div>
        </div>
      </div>
    </div>


    <!-- 6. TRUST STRIP & SCIENCE PROOF -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>6. Trust Strip & Quiet Technical Proof</h2>
        <p>Science proof displayed quietly as supporting evidence rather than dominant tech marketing.</p>
      </div>

      <div class="biog-trust-strip" style="margin-bottom:24px;">
        <div class="biog-trust-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          30-Day Freshness Guarantee
        </div>
        <div class="biog-trust-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          Lab-Tested Odor Control
        </div>
        <div class="biog-trust-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          Free USA Delivery Over $40
        </div>
      </div>

      <div class="biog-proof-box">
        <h4>Why BiO-G Technology Works</h4>
        <p>Unlike surface spray treatments that wash out after a few laundry cycles, BiO-G's zinc antibacterial compound is embedded directly into fabric microfibers. It continuously neutralizes odor-causing bacteria without harsh chemicals or environmental pollution.</p>
      </div>
    </div>


    <!-- 7. HERO MOCKUPS -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>7. Hero Direction Mockup</h2>
        <p>Lifestyle-led hero banner with clear value proposition and primary CTA.</p>
      </div>

      <div class="biog-hero-mock">
        <span style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#3B7A66; margin-bottom:12px;">Premium Odor-Resistant Apparel</span>
        <h2>Fresh Comfort, Made for Every Day.</h2>
        <p>Engineered with zinc antibacterial technology so your socks and boxers stay fresh from morning till night.</p>
        <div style="display:flex; gap:16px;">
          <a class="biog-btn biog-btn-primary" style="height:48px;" href="#">Shop Odor-Free Socks</a>
          <a class="biog-btn biog-btn-outline" style="border-color:#FFFFFF; color:#FFFFFF;" href="#">Explore Boxers</a>
        </div>
      </div>
    </div>


    <!-- 8. FORM SYSTEM PREVIEW -->
    <div class="biog-lab-section">
      <div class="biog-lab-section-head">
        <h2>8. Form System Components</h2>
        <p>44px touch targets, clear labels, and explicit focus rings.</p>
      </div>

      <div style="max-width:500px;">
        <div class="biog-form-field">
          <label>Email Address</label>
          <input class="biog-input" type="email" placeholder="you@example.com">
        </div>
        <div class="biog-form-field">
          <label>Select Size Category</label>
          <select class="biog-input">
            <option>Men's Socks</option>
            <option>Women's Socks</option>
            <option>Everyday Boxers</option>
          </select>
        </div>
        <button class="biog-btn biog-btn-primary">Subscribe for 15% Off</button>
      </div>
    </div>

  </div>
</div>

<?php
// Load WordPress Footer
get_footer();
