# SeaHivez Development Tasks

## Phase 0 — Project preparation

- [x] Create project documentation structure
- [x] Review existing theme code
- [x] Review UI/UX reference
- [x] Identify reusable existing code
- [x] Confirm final architecture
- [x] Resolve nested theme directory structure (Git root vs WP theme root)
- [x] Add `.gitignore` (node_modules, logs)

## Phase 1 — Theme foundation

- [x] Flatten repo so theme files live at Git root (`wp-content/themes/seahivez-theme/`)
- [x] Update `style.css` theme metadata (name, description, author, version)
- [x] Configure Tailwind CSS (`tailwind.config.js`, color/font tokens)
- [x] Configure PostCSS (`postcss.config.js`)
- [x] Create `assets/css/src/main.css` entry file
- [x] Create `assets/js/src/main.js` entry file
- [x] Configure production frontend build (`npm run dev`, `npm run build`)
- [x] Replace legacy `package.json` node-sass scripts with Tailwind + esbuild
- [x] Configure WordPress theme setup (`inc/setup.php`)
- [x] Refactor `functions.php` into `/inc` loader pattern
- [x] Configure asset enqueueing (`inc/enqueue.php`) — load `assets/dist/`, not legacy `style.css` body
- [x] Register primary menu (`primary`)
- [x] Register footer menu (`footer`)
- [x] Register footer yacht menu (`footer-yacht`)
- [x] Implement `template-parts/header/site-header.php`
- [x] Implement `template-parts/header/primary-nav.php`
- [x] Implement `template-parts/header/mobile-nav.php`
- [x] Refactor `header.php` to use template parts
- [x] Implement `template-parts/footer/site-footer.php`
- [x] Refactor `footer.php` to use template parts
- [x] Migrate `js/navigation.js` → `assets/js/src/navigation.js` (adapt for new markup)
- [x] Implement responsive mobile navigation
- [x] Create base typography/layout system (Tailwind components layer)
- [x] Register custom image sizes (`seahivez-hero`, `seahivez-card`, `seahivez-gallery`)
- [x] Deprecate `inc/custom-header.php` (remove require)
- [x] Evaluate `inc/customizer.php` dependency (kept for site title/description preview)
- [x] Set up SVG icon approach (`assets/images/icons/toys/` + `assets/images/icons/specs/` + `inc/icons.php`)
- [ ] Remove sidebar calls from templates (`get_sidebar()`)
- [ ] Self-hosted or external font loading (deferred — using system fonts in Phase 1B)

## Phase 2 — Homepage

- [x] Create `front-page.php`
- [x] Hero section (`template-parts/home/hero.php`)
- [x] Yacht information bar (`template-parts/home/specs-bar.php`)
- [x] Yacht introduction (`template-parts/home/about-yacht.php`)
- [x] Specifications (`template-parts/home/specifications.php`)
- [x] Experiences/packages (`template-parts/home/experiences.php`)
- [x] Toys and extras (`template-parts/home/toys-extras.php`)
- [x] Gallery (`template-parts/home/gallery.php`)
- [x] Location + booking CTA (`template-parts/home/location-cta.php`)
- [x] Reusable card partials (`template-parts/cards/spec-item.php`, `experience-card.php`, `gallery-item.php`, `extra-item.php`)
- [x] Homepage data layer (`inc/homepage-data.php`) for future ACF migration
- [x] Homepage header overlay + scroll behavior
- [x] Section reveal animations (IntersectionObserver + reduced motion)
- [ ] Homepage responsive audit (manual verification at target breakpoints)

## Phase 3 — WordPress templates

- [ ] Restyle `page.php` (full-width, prose content, optional page hero)
- [ ] Restyle `single.php` (post layout, meta, navigation)
- [ ] Restyle `archive.php` (post list/cards)
- [ ] Restyle `index.php` (fallback, match archive)
- [ ] Restyle `search.php` (results list)
- [ ] Restyle `404.php` (simplified, on-brand)
- [ ] Migrate content template parts to `template-parts/content/` subdirectory
- [ ] Add `@tailwindcss/typography` prose styles for post/page content
- [ ] Post typography
- [ ] Pagination/navigation styling

## Phase 4 — Dynamic content

- [ ] Install/configure ACF
- [ ] Create `inc/acf.php` with JSON save/load paths
- [ ] Add ACF JSON support (`acf-json/`)
- [ ] Create Site Settings options page (contact, social, booking URL)
- [ ] Create front page field group (all homepage sections)
- [ ] Make homepage content editable via ACF
- [ ] Register `rental_package` CPT
- [ ] Register `extra` CPT
- [ ] Register `faq` CPT
- [ ] Register `review` CPT
- [ ] Rental packages structure + ACF fields
- [ ] Extras structure + ACF fields
- [ ] FAQ structure + ACF fields
- [ ] Reviews structure + ACF fields
- [ ] Connect homepage experiences section to CPT/query

## Phase 5 — Booking

- [ ] Add booking CTA configuration (ACF options → SuperSaaS URL)
- [ ] Integrate SuperSaaS booking system
- [ ] Verify responsive booking flow
- [ ] Verify external calendar workflow

## Phase 6 — Polish

- [ ] Responsive audit 360px
- [ ] Responsive audit 390px
- [ ] Responsive audit 430px
- [ ] Responsive audit 768px
- [ ] Responsive audit 1024px
- [ ] Responsive audit 1280px
- [ ] Responsive audit 1440px
- [ ] Responsive audit 1920px
- [ ] Accessibility audit
- [ ] Performance optimization
- [ ] Image optimization (WebP, srcset, lazy load)
- [ ] Animations
- [ ] prefers-reduced-motion support
- [ ] SEO basics (meta, OG tags, JSON-LD)
- [ ] Language switcher architecture (WPML/Polylang prep)
