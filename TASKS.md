# SeaHivez Development Tasks

## Phase 0 — Project preparation

- [x] Create project documentation structure
- [x] Review existing theme code
- [x] Review UI/UX reference
- [x] Identify reusable existing code
- [x] Confirm final architecture
- [x] Resolve nested theme directory structure (Git root vs WP theme root)
- [ ] Add `.gitignore` (node_modules, logs)

## Phase 1 — Theme foundation

- [x] Flatten repo so theme files live at Git root (`wp-content/themes/seahivez-theme/`)
- [ ] Update `style.css` theme metadata (name, description, author, version)
- [ ] Configure Tailwind CSS (`tailwind.config.js`, color/font tokens)
- [ ] Configure PostCSS (`postcss.config.js`)
- [ ] Create `assets/css/src/main.css` entry file
- [ ] Create `assets/js/src/main.js` entry file
- [ ] Configure production frontend build (`npm run dev`, `npm run build`)
- [ ] Replace legacy `package.json` node-sass scripts with Tailwind + esbuild
- [ ] Configure WordPress theme setup (`inc/setup.php`)
- [ ] Refactor `functions.php` into `/inc` loader pattern
- [ ] Configure asset enqueueing (`inc/enqueue.php`) — load `assets/dist/`, not legacy `style.css` body
- [ ] Register primary menu (`primary`)
- [ ] Register footer menus (`footer-quick`, `footer-yacht`)
- [ ] Implement `template-parts/header/site-header.php`
- [ ] Implement `template-parts/header/primary-nav.php`
- [ ] Implement `template-parts/header/mobile-nav.php`
- [ ] Refactor `header.php` to use template parts
- [ ] Implement `template-parts/footer/site-footer.php`
- [ ] Refactor `footer.php` to use template parts
- [ ] Migrate `js/navigation.js` → `assets/js/src/navigation.js` (adapt for new markup)
- [ ] Implement responsive mobile navigation
- [ ] Create base typography/layout system (Tailwind components layer)
- [ ] Register custom image sizes (`seahivez-hero`, `seahivez-card`, `seahivez-gallery`)
- [ ] Set up font loading (Inter + Cormorant Garamond)
- [ ] Set up SVG icon approach (`assets/images/icons/`)
- [ ] Remove sidebar calls from templates (`get_sidebar()`)
- [ ] Deprecate `inc/custom-header.php` (remove require)
- [ ] Evaluate/remove `inc/customizer.php` dependency

## Phase 2 — Homepage

- [ ] Create `front-page.php`
- [ ] Hero section (`template-parts/home/hero.php`)
- [ ] Yacht information bar (`template-parts/home/specs-bar.php`)
- [ ] Yacht introduction (`template-parts/home/about-yacht.php`)
- [ ] Specifications (`template-parts/home/specifications.php`)
- [ ] Experiences/packages (`template-parts/home/experiences.php`)
- [ ] Toys and extras (`template-parts/home/toys-extras.php`)
- [ ] Gallery (`template-parts/home/gallery.php`)
- [ ] Location + booking CTA (`template-parts/home/location-cta.php`)
- [ ] Reusable card partials (`template-parts/cards/*`)
- [ ] Reusable spec-item partial (`template-parts/cards/spec-item.php`)
- [ ] Homepage responsive audit

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
