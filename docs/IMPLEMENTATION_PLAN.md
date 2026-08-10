# SeaHivez Implementation Plan

> **Status:** Final plan prepared after theme and design reference analysis (Phase 0).  
> **Source of truth for progress:** `TASKS.md`

---

## 1. Project overview

See `PROJECT.md` for business context, goals, and initial site structure.

**Summary:** SeaHivez is a premium private yacht charter website for the Numarine 55 Fly, operating in Mallorca. The site is a fully custom WordPress theme styled with Tailwind CSS, composed from template parts, with future ACF-driven editable content and SuperSaaS booking integration.

**Design direction (from reference):** Premium, Mediterranean, modern, minimalistic, editorial. Deep navy + white + light gray palette, generous whitespace, thin-line icons, strong typographic hierarchy, clear booking CTAs throughout.

**Brand note:** The UI reference uses “Magica Yacht Rental” as placeholder branding. Production implementation uses **SeaHivez** naming and assets.

---

## 2. Existing theme analysis

### 2.1 Theme origin

The current codebase is **Underscores (_s)** — the standard WordPress starter theme. It provides a solid, standards-compliant foundation but contains no SeaHivez-specific design or business logic.

| Aspect | Current state |
|--------|---------------|
| Base | Underscores `_s` v1.0.0 boilerplate |
| Custom design | None — default _s CSS and layout |
| `front-page.php` | Does not exist |
| Tailwind CSS | Not configured |
| ACF | Not integrated |
| Build tooling | `package.json` references `node-sass` + `sass/` folder, but **`sass/` does not exist** |
| Compiled assets | Only legacy `style.css` (~970 lines normalize + _s styles) and `style-rtl.css` |

### 2.2 File inventory

**Root templates (standard _s hierarchy — preserve structure, restyle later):**

| File | Role | Reuse strategy |
|------|------|----------------|
| `index.php` | Fallback template | Keep loop logic; update markup/classes; remove sidebar |
| `page.php` | Static pages | Keep loop; add interior page layout wrapper |
| `single.php` | Single posts | Keep loop + post nav; restyle |
| `archive.php` | Archives | Keep loop; restyle |
| `search.php` | Search results | Keep loop; restyle |
| `404.php` | Not found | Simplify (remove widget clutter); restyle |
| `header.php` | Document head + header | **Replace markup** via template parts; keep `wp_head()`, skip link, `body_class()` |
| `footer.php` | Footer + closing tags | **Replace markup** via template parts; keep `wp_footer()` |
| `sidebar.php` | Widget area | Keep file; **stop calling** from templates (marketing site has no sidebar) |
| `comments.php` | Comments | Keep for blog posts; low priority for yacht site |

**`/inc` (preserve and extend):**

| File | Keep? | Notes |
|------|-------|-------|
| `template-tags.php` | ✅ Yes | `posted_on`, `posted_by`, `entry_footer`, `post_thumbnail` — useful for blog templates |
| `template-functions.php` | ✅ Yes | `body_classes`, pingback — extend with layout helpers |
| `customizer.php` | ⚠️ Partial | Keep if custom logo/title still used; reduce reliance over time in favor of ACF |
| `custom-header.php` | ❌ Deprecate | Inline `<style>` header colors conflict with Tailwind; remove require once header is rebuilt |
| `jetpack.php` | ✅ Conditional | Keep behind `JETPACK__VERSION` check |

**`/js` (legacy location — preserve logic, migrate path later):**

| File | Keep? | Notes |
|------|-------|-------|
| `navigation.js` | ✅ Yes | Vanilla JS, accessible toggle pattern — **refactor** for new header markup/IDs, move to `assets/js/src/` |
| `customizer.js` | ⚠️ Optional | Only needed if Customizer partial refresh remains |

**`/template-parts` (preserve existing content templates):**

| File | Location today | Future location |
|------|----------------|-----------------|
| `content.php` | Root of `template-parts/` | Move to `template-parts/content/content-post.php` (Phase 3) |
| `content-page.php` | Root | → `template-parts/content/content-page.php` |
| `content-search.php` | Root | → `template-parts/content/content-search.php` |
| `content-none.php` | Root | → `template-parts/content/content-none.php` |

Existing files stay in place until Phase 3 migration to avoid breaking `get_template_part()` calls.

**Empty prepared directories (ready for implementation):**

- `assets/css/`, `assets/js/`, `assets/images/`, `assets/dist/`
- `template-parts/header/`, `footer/`, `home/`, `cards/`, `content/`
- `acf-json/`

### 2.3 Repository structure (resolved — Phase 1A)

**Status:** ✅ Completed. Git repository root is now the WordPress theme root.

```
wp-content/themes/seahivez-theme/          ← Git repo root = WP theme root
├── .git/
├── style.css
├── functions.php
├── header.php
├── footer.php
├── inc/
├── template-parts/
├── assets/
├── docs/
├── acf-json/
└── …
```

WordPress requires `style.css` at `wp-content/themes/{theme-slug}/style.css`.

**Current path:** `wp-content/themes/seahivez-theme/style.css` ✓

The previous nested `seahivez-theme/seahivez-theme/` layout was flattened in Phase 1A. All theme files were moved to the repository root; `.git` was preserved in place; no PHP path changes were required because the theme uses `get_template_directory()` and `get_template_directory_uri()` for all internal references.

### 2.4 What can be preserved vs replaced

| Preserve | Replace / rebuild |
|----------|-------------------|
| Template hierarchy files and loop logic | All visual HTML/CSS in header, footer, templates |
| `inc/template-tags.php`, `inc/template-functions.php` | `style.css` body styles (replace with Tailwind build) |
| `js/navigation.js` behavior (refactored) | `header.php` and `footer.php` markup |
| i18n text domain setup | Default Underscores footer “Proudly powered by WordPress” |
| Theme support declarations (thumbnails, title-tag, html5) | `custom-header.php` inline styles |
| Conditional Jetpack compat | Sidebar usage in templates |
| PHPCS / Composer dev tooling | `package.json` node-sass scripts |

---

## 3. UI/UX reference analysis

**Reference file:** `docs/design/homepage-reference.png`

### 3.1 Global layout patterns

- **Header:** Sticky/fixed top bar. Logo (serif) left, horizontal nav center, language switcher + solid “BOOK NOW” CTA right. Active nav item uses top border underline.
- **Hero:** Full-viewport-width background image with left-aligned text overlay (not centered). Eyebrow label → H1 → subcopy → dual CTAs (solid + outline).
- **Rhythm:** Alternating white and light-gray section backgrounds. Consistent vertical section spacing. Content constrained to ~1200–1320px container.
- **Footer:** Full-width navy background, multi-column link groups, social icons, secondary booking CTA, legal links row.

### 3.2 Section breakdown (top to bottom)

| # | Section | Layout | Key elements |
|---|---------|--------|--------------|
| 1 | Header | 3-zone horizontal | Logo, nav (Home, The Yacht, Services, Extras, Gallery, Contact), EN/PL switcher, BOOK NOW |
| 2 | Hero | Full-bleed image + left text | Eyebrow “NUMARINE 55 FLY”, H1, subcopy, 2 buttons |
| 3 | Quick specs bar | 6-column icon grid on gray bg | Location, Guests, Cabins, Crew, Refit, Cruising speed |
| 4 | About yacht | 2-column ~40/60 | Heading, paragraphs, text link; large rounded image |
| 5 | Specifications | 4-column icon grid | Length, beam, draft, engines, speed, refit, capacity, cabins, bathrooms, crew, languages |
| 6 | Charter services | 3-column cards | Image, title, price (bold), description, arrow link |
| 7 | Toys & extras | 2 sub-sections + amenity row | “Included” icons, “Extra (on-request)” with prices, checkmark amenity strip |
| 8 | Gallery | 3×2 image grid | 6 images, centered “VIEW FULL GALLERY” outline button |
| 9 | Location / booking CTA | 3-column gray box | Booking prompt + button; contact details with icons; Mallorca map |
| 10 | Footer | 5-column navy | Logo + about + social; quick links; yacht links; contact; book CTA; copyright + legal |

### 3.3 Reusable UI patterns (implement once, use everywhere)

1. **Icon + label + value** — specs bar, specifications grid
2. **Primary / outline / ghost buttons** — hero, cards, footer, CTAs
3. **Experience card** — image top, title, price, excerpt, arrow
4. **Section header** — optional eyebrow + H2 + optional subheading
5. **Container** — centered max-width with horizontal padding
6. **Thin-line SVG icons** — consistent stroke width and size

### 3.4 Design interpretation guidelines

- Match **composition, hierarchy, spacing, and tone** — not pixel-perfect cloning.
- Use SeaHivez branding, real copy, and optimized images.
- Language switcher is a **future** feature (architecture only in Phase 1); render EN-only initially with hook for WPML/Polylang later.

---

## 4. Design system

Tailwind CSS v3.x (stable, well-documented for shared hosting workflow). All tokens defined in `tailwind.config.js`.

### 4.1 Color palette

| Token | Hex | Usage |
|-------|-----|-------|
| `navy-950` | `#071428` | Footer background |
| `navy-900` | `#0B1F3A` | Primary buttons, headings, header CTA |
| `navy-800` | `#123052` | Hover states |
| `navy-700` | `#1A4068` | Active nav underline |
| `white` | `#FFFFFF` | Backgrounds, button text on navy |
| `sand-50` | `#FAFAF8` | Alternate section background |
| `gray-100` | `#F3F4F6` | Specs bar, CTA section background |
| `gray-200` | `#E5E7EB` | Borders, dividers |
| `gray-500` | `#6B7280` | Secondary text, labels |
| `gray-700` | `#374151` | Body text |
| `gray-900` | `#111827` | Strong body emphasis |
| `gold-500` | `#C9A227` | Optional accent (icons, highlights) — use sparingly |

### 4.2 Typography

| Role | Font | Tailwind class | Notes |
|------|------|----------------|-------|
| Logo / display serif | **Cormorant Garamond** or **Playfair Display** | `font-display` | Logo and premium headings only |
| Headings / UI | **Inter** | `font-sans` | All headings, nav, buttons, body |
| Eyebrow / labels | Inter uppercase | `text-xs tracking-widest uppercase` | Section labels, spec labels |

**Scale (mobile → desktop):**

| Element | Mobile | Desktop |
|---------|--------|---------|
| H1 (hero) | `text-3xl` | `text-5xl lg:text-6xl` |
| H2 (section) | `text-2xl` | `text-3xl lg:text-4xl` |
| H3 (card title) | `text-lg` | `text-xl` |
| Body | `text-base` | `text-base lg:text-lg` |
| Eyebrow | `text-xs` | `text-sm` |
| Price | `text-2xl font-semibold` | `text-3xl font-semibold` |

**Line height:** `leading-relaxed` for body, `leading-tight` for headings.

### 4.3 Spacing

| Token | Value | Usage |
|-------|-------|-------|
| Section Y (mobile) | `py-12` (48px) | Default section padding |
| Section Y (desktop) | `py-16 lg:py-20` (64–80px) | Larger sections |
| Container X | `px-4 sm:px-6 lg:px-8` | Horizontal gutter |
| Card padding | `p-6` | Experience cards |
| Grid gap | `gap-6 lg:gap-8` | Multi-column grids |
| Stack gap | `space-y-4` / `space-y-6` | Vertical content stacks |

### 4.4 Containers

```html
<!-- Standard content container -->
<div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">

<!-- Wide container (gallery, specs) -->
<div class="mx-auto w-full max-w-screen-xl px-4 sm:px-6 lg:px-8">

<!-- Full-bleed section with inner container -->
<section class="w-full">
  <div class="mx-auto max-w-7xl …">…</div>
</section>
```

- `max-w-7xl` (1280px) — default content width matching reference
- `max-w-screen-xl` (1280px) — same; use for grids that need slightly more room

### 4.5 Buttons

| Variant | Classes (concept) | Usage |
|---------|-------------------|-------|
| Primary | `bg-navy-900 text-white hover:bg-navy-800 rounded-md px-6 py-3 text-sm font-medium uppercase tracking-wide` | BOOK NOW, primary CTAs |
| Outline | `border-2 border-navy-900 text-navy-900 hover:bg-navy-900 hover:text-white rounded-md px-6 py-3 …` | EXPLORE YACHT, secondary CTAs |
| Ghost | `border border-white/40 text-white hover:bg-white/10 rounded-md px-6 py-3 …` | Footer CTA on navy bg |
| Text link | `text-navy-900 font-medium inline-flex items-center gap-2 hover:underline` | VIEW FULL DETAILS, card arrows |

Implement as Tailwind `@layer components` classes in source CSS:

- `.btn`, `.btn-primary`, `.btn-outline`, `.btn-ghost`, `.link-arrow`

### 4.6 Cards

**Experience card:**

- White background, no heavy shadow (reference is flat/minimal)
- Optional `border border-gray-200 rounded-lg overflow-hidden`
- Image: `aspect-[4/3] object-cover`
- Body: title, price, excerpt, arrow icon bottom-right

**Spec item:**

- Centered or left-aligned column
- SVG icon (24px), uppercase label (`text-xs text-gray-500`), value (`text-sm font-medium text-navy-900`)

### 4.7 Borders and dividers

- Default border: `border-gray-200`
- Nav active indicator: `border-t-2 border-navy-900` (top border on active item)
- Section dividers: prefer background color change over visible rules

### 4.8 Icons

- Use inline SVG sprite or individual SVG files in `assets/images/icons/`
- Stroke icons, 1.5px stroke, `currentColor`, 24×24 default
- Do not depend on icon font libraries (performance + accessibility)

### 4.9 WordPress / Tailwind integration

- **`style.css`** — WordPress theme header comment only (required for theme recognition). No layout CSS.
- **`assets/css/src/main.css`** — Tailwind directives + `@layer components` for buttons, prose, etc.
- **`assets/dist/main.css`** — compiled, minified, committed to Git for production deploy.

Safelist dynamic classes if needed for WordPress menu classes (`current-menu-item`, etc.).

---

## 5. WordPress architecture

### 5.1 Target directory structure

```
seahivez-theme/                          ← Git root = WP theme root (flattened in Phase 1A)
├── style.css                            ← WP theme header only
├── functions.php                          ← Lightweight loader
├── front-page.php
├── header.php
├── footer.php
├── index.php, page.php, single.php, …
│
├── inc/
│   ├── setup.php                        ← theme supports, menus, image sizes
│   ├── enqueue.php                      ← CSS/JS loading
│   ├── menus.php                        ← menu registrations + walkers if needed
│   ├── template-tags.php                ← existing (keep)
│   ├── template-functions.php           ← existing (extend)
│   ├── acf.php                          ← ACF JSON sync path
│   └── jetpack.php                      ← conditional
│
├── template-parts/
│   ├── header/
│   │   ├── site-header.php
│   │   ├── primary-nav.php
│   │   └── mobile-nav.php
│   ├── footer/
│   │   └── site-footer.php
│   ├── home/
│   │   ├── hero.php
│   │   ├── specs-bar.php
│   │   ├── about-yacht.php
│   │   ├── specifications.php
│   │   ├── experiences.php
│   │   ├── toys-extras.php
│   │   ├── gallery.php
│   │   ├── location-cta.php
│   │   └── booking-cta.php
│   ├── cards/
│   │   ├── experience-card.php
│   │   ├── spec-item.php
│   │   ├── extra-item.php
│   │   └── gallery-item.php
│   └── content/
│       ├── content-page.php
│       ├── content-post.php
│       ├── content-search.php
│       ├── content-none.php
│       └── page-hero.php                  ← optional interior page banner
│
├── assets/
│   ├── css/src/main.css
│   ├── js/src/main.js
│   ├── js/src/navigation.js               ← migrated from /js
│   ├── images/
│   └── dist/
│       ├── main.css                       ← committed
│       └── main.js                        ← committed
│
├── acf-json/
├── languages/
└── docs/
```

### 5.2 `functions.php` refactor

```php
// functions.php — loader only
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/menus.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/acf.php';          // Phase 4
if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}
// Remove: custom-header.php, customizer.php (unless still needed)
```

### 5.3 Navigation menus

| Location | Slug | Purpose |
|----------|------|---------|
| Primary | `primary` | Header nav (replace current `menu-1`) |
| Footer quick links | `footer-quick` | Footer column links |
| Footer yacht | `footer-yacht` | Yacht-related footer links |

Register in `inc/menus.php`. Migrate existing `menu-1` assignment in WP admin to `primary`.

### 5.4 Custom post types (Phase 4)

| CPT | Slug | Fields (ACF) |
|-----|------|--------------|
| Rental package | `rental_package` | price, duration, description, featured image, optional external link |
| Extra | `extra` | price, icon, category (`included` / `on_request`) |
| FAQ | `faq` | question, answer |
| Review | `review` | author, rating, text, date |

Homepage sections query CPTs or use ACF relationship fields — prefer CPTs for reusable content across pages.

### 5.5 Sidebar strategy

Remove `get_sidebar()` from all templates. Keep `sidebar.php` and widget registration for future blog sidebar if needed, but default layout is **full-width, no sidebar**.

### 5.6 Asset enqueue strategy

```php
// Production
wp_enqueue_style( 'seahivez-main', get_theme_file_uri( 'assets/dist/main.css' ), [], filemtime(...) );
wp_enqueue_script( 'seahivez-main', get_theme_file_uri( 'assets/dist/main.js' ), [], filemtime(...), true );

// Do NOT enqueue legacy style.css body styles
// style.css exists only for WP theme header metadata
```

Google Fonts: enqueue Inter + Cormorant Garamond via `wp_enqueue_style` with `display=swap`, or self-host in `assets/fonts/` for performance (preferred for production).

---

## 6. Homepage architecture

### 6.1 `front-page.php`

Minimal orchestrator — no section HTML inline:

```php
get_header();
?>
<main id="primary" class="site-main">
  <?php
  get_template_part( 'template-parts/home/hero' );
  get_template_part( 'template-parts/home/specs-bar' );
  get_template_part( 'template-parts/home/about-yacht' );
  get_template_part( 'template-parts/home/specifications' );
  get_template_part( 'template-parts/home/experiences' );
  get_template_part( 'template-parts/home/toys-extras' );
  get_template_part( 'template-parts/home/gallery' );
  get_template_part( 'template-parts/home/location-cta' );
  ?>
</main>
<?php
get_footer();
```

**Note:** Booking CTA may be combined with `location-cta.php` per reference (single 3-column section). Split into separate template parts only if content editing requires it.

### 6.2 Template part responsibilities

| File | Section | Data source (Phase 2 → Phase 4) |
|------|---------|----------------------------------|
| `hero.php` | Full-bleed hero | Hardcoded → ACF front page fields |
| `specs-bar.php` | 6 quick stats | Hardcoded → ACF repeater |
| `about-yacht.php` | 2-col intro | Hardcoded → ACF fields |
| `specifications.php` | 4-col spec grid | Hardcoded → ACF repeater |
| `experiences.php` | 3-card grid | Hardcoded → `rental_package` CPT query |
| `toys-extras.php` | Included/extras/amenities | Hardcoded → ACF repeaters / `extra` CPT |
| `gallery.php` | 3×2 preview grid | Hardcoded → ACF gallery field |
| `location-cta.php` | Contact + map + book CTA | Hardcoded → ACF options |

### 6.3 Card partials

| File | Used by |
|------|---------|
| `cards/spec-item.php` | `specs-bar.php`, `specifications.php` |
| `cards/experience-card.php` | `experiences.php` |
| `cards/extra-item.php` | `toys-extras.php` |
| `cards/gallery-item.php` | `gallery.php` |

Pass data via `set_query_var()` or `$args` parameter in `get_template_part( 'template-parts/cards/experience-card', null, $args )`.

---

## 7. Header, footer, and interior templates

### 7.1 `header.php`

```php
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="…">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site min-h-screen flex flex-col">
  <a class="skip-link …" href="#primary">Skip to content</a>
  <?php get_template_part( 'template-parts/header/site-header' ); ?>
```

**`site-header.php` contains:**

- Sticky header (`fixed top-0 z-50 w-full bg-white/95 backdrop-blur`)
- Logo (custom logo or text)
- Desktop nav (`primary` menu) — visible `lg:flex`
- Language switcher placeholder (hidden or EN-only until multilingual plugin)
- BOOK NOW button (links to booking page or external SuperSaaS URL from ACF options)
- Mobile: hamburger → slide-down or full-screen overlay (`mobile-nav.php`)

**Navigation JS:** Extend migrated `navigation.js` for new class names (`is-open` instead of `toggled`), focus trap in mobile menu, `aria-expanded` on toggle.

### 7.2 `footer.php`

```php
  <?php get_template_part( 'template-parts/footer/site-footer' ); ?>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
```

**`site-footer.php`:** 5-column navy footer per reference. Footer menus, contact info from ACF options (Phase 4) or hardcoded (Phase 2). Social icons as accessible SVG links.

### 7.3 Interior page templates

Shared layout wrapper for `page.php`, `single.php`, `archive.php`, `search.php`, `404.php`:

```html
<main id="primary" class="site-main flex-1">
  <!-- Optional page-hero for top-level pages -->
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <!-- template-specific content -->
  </div>
</main>
```

| Template | Layout | Styling approach |
|----------|--------|------------------|
| `page.php` | Single column, max-w-3xl prose for content | `@tailwindcss/typography` `prose prose-lg` on `.entry-content` |
| `single.php` | Featured image, title, meta, prose content, post nav | Same prose styling + meta row |
| `archive.php` | Page title + card/list of posts | Reuse card pattern or simple list with thumbnail |
| `index.php` | Same as archive fallback | Identical to archive styling |
| `search.php` | Search header + results list | Compact result cards |
| `404.php` | Centered minimal message + search form + home link | Remove WP widget clutter from current _s version |

**Comments:** Disabled or hidden on pages; available on posts only if blog is used.

---

## 8. Responsive strategy

Mobile-first. Tailwind breakpoints as primary; audit at specific device widths from `TASKS.md`.

### 8.1 Breakpoint mapping

| Audit width | Tailwind breakpoint | Typical layout |
|-------------|---------------------|----------------|
| 360px | default (base) | Single column, compact type, hamburger nav |
| 390px | default | Same; verify button tap targets (min 44px) |
| 430px | default | Slightly roomier hero text |
| 768px | `md:` | 2-column grids, specs bar 3-col |
| 1024px | `lg:` | Desktop nav visible, hamburger hidden, 3–4 col grids |
| 1280px | `xl:` | Container reaches max-width |
| 1440px | `xl:` / `2xl:` | Hero typography scales up |
| 1920px | `2xl:` | Content stays capped; background images cover |

### 8.2 Section-specific responsive behavior

| Section | Mobile | Tablet (`md`) | Desktop (`lg`) |
|---------|--------|---------------|----------------|
| Header | Hamburger + logo + CTA | Same | Full horizontal nav |
| Hero | Stacked text, shorter height | Increased padding | Full-height feel, large H1 |
| Specs bar | 2 columns | 3 columns | 6 columns |
| About | Image below text | 2 columns | 40/60 split |
| Specifications | 2 columns | 3 columns | 4 columns |
| Experiences | 1 column | 2 columns | 3 columns |
| Toys & extras | Stacked sections | 2 columns per sub-section | Horizontal icon rows |
| Gallery | 1 column | 2 columns | 3 columns |
| Location CTA | Stacked | 2 columns | 3 columns |
| Footer | Accordion or stacked columns | 2 columns | 5 columns |

### 8.3 Images

- Hero: `object-cover`, min-height with `aspect-ratio` fallback
- Cards: fixed aspect ratio containers
- Use `srcset` via `wp_get_attachment_image()` — never fixed-width `<img>` without responsive attributes
- Serve WebP via WordPress or manual conversion in `assets/images/`

---

## 9. Frontend build strategy

### 9.1 Tooling

Replace current `node-sass` setup with:

| Tool | Purpose |
|------|---------|
| `tailwindcss` v3.x | Utility CSS |
| `postcss` + `autoprefixer` | Post-processing |
| `esbuild` or `@wordpress/scripts` | Bundle/minify JS |

### 9.2 Source → output

| Source | Output |
|--------|--------|
| `assets/css/src/main.css` | `assets/dist/main.css` |
| `assets/js/src/main.js` | `assets/dist/main.js` |
| `assets/js/src/navigation.js` | Imported by `main.js` |

### 9.3 npm scripts (target)

```json
{
  "scripts": {
    "dev": "tailwindcss -i ./assets/css/src/main.css -o ./assets/dist/main.css --watch",
    "build:css": "tailwindcss -i ./assets/css/src/main.css -o ./assets/dist/main.css --minify",
    "build:js": "esbuild assets/js/src/main.js --bundle --outfile=assets/dist/main.js --minify",
    "build": "npm run build:css && npm run build:js"
  }
}
```

### 9.4 Development workflow

1. Local: `npm install` once, then `npm run dev` during development
2. Before commit/deploy: `npm run build`
3. Commit `assets/dist/*` to Git
4. Production: `git pull` — **no Node.js required**

### 9.5 Legacy CSS handling

- Strip normalize and _s component styles from enqueued CSS
- Keep `style.css` theme header block (lines 1–23) for WordPress
- Tailwind preflight replaces normalize
- `@tailwindcss/typography` plugin for post/page prose

### 9.6 `.gitignore`

```
node_modules/
*.log
.DS_Store
```

Do **not** ignore `assets/dist/`.

---

## 10. ACF content model (Phase 4)

### 10.1 Options page: “Site Settings”

| Field group | Fields |
|-------------|--------|
| Contact | `phone`, `email`, `whatsapp`, `address` |
| Social | `instagram_url`, `facebook_url`, `whatsapp_url` |
| Booking | `booking_url` (SuperSaaS), `booking_cta_label` |
| Footer | `footer_about_text` |

### 10.2 Front page field group (assigned to static front page)

| Section | Fields |
|---------|--------|
| Hero | `hero_eyebrow`, `hero_heading`, `hero_subheading`, `hero_bg_image`, `hero_cta_primary` (link), `hero_cta_secondary` (link) |
| Specs bar | `quick_specs` (repeater: `icon`, `label`, `value`) |
| About | `about_heading`, `about_content` (WYSIWYG), `about_image`, `about_link` (link) |
| Specifications | `specifications_heading`, `specifications` (repeater: `icon`, `label`, `value`) |
| Experiences | `experiences_heading`, `experiences_subheading`, `experiences` (relationship → `rental_package` or repeater) |
| Toys & extras | `extras_heading`, `included_items` (repeater), `paid_extras` (repeater), `amenities` (repeater) |
| Gallery | `gallery_heading`, `gallery_images` (gallery), `gallery_cta` (link) |
| Location CTA | `cta_heading`, `cta_text`, `cta_button` (link), `map_image` or embed code |

### 10.3 ACF JSON

- Save path: `acf-json/` (configured in `inc/acf.php` via `acf/settings/save_json` and `load_json` filters)
- Commit JSON files to Git for field group versioning across environments

### 10.4 Phase 2 fallback

Until ACF is installed, homepage template parts use sensible hardcoded defaults with clear `// TODO: ACF` comments or helper functions (`seahivez_get_field()`) that fall back to defaults.

---

## 11. Performance

- Enqueue one CSS file, one JS file (plus fonts)
- Lazy-load below-fold images (`loading="lazy"`)
- Hero image: `fetchpriority="high"`, no lazy load
- Minimize DOM depth in template parts
- Avoid jQuery entirely
- Use WordPress image sizes — register custom sizes in `inc/setup.php`:
  - `seahivez-hero` (1920×1080)
  - `seahivez-card` (800×600)
  - `seahivez-gallery` (600×600)
- Defer non-critical JS (already in footer)
- Preconnect to font origin if using Google Fonts

---

## 12. Accessibility

- Skip link (preserve from _s, restyle with focus-visible)
- Semantic landmarks: `<header>`, `<nav>`, `<main>`, `<footer>`, `<section aria-labelledby="…">`
- Mobile menu: `aria-expanded`, focus trap, Escape to close
- All images: meaningful `alt` text
- Buttons vs links used correctly (CTAs that navigate = `<a>`, actions = `<button>`)
- Color contrast: navy on white and white on navy must meet WCAG AA
- Form inputs (search, contact): visible labels, focus rings
- `prefers-reduced-motion`: disable scroll animations and transitions (Phase 6)
- Keyboard navigable cards and menu

---

## 13. SEO

- `title-tag` theme support (already enabled)
- One H1 per page (hero on homepage, page title on interior)
- Logical heading hierarchy (H1 → H2 → H3)
- Schema.org `LocalBusiness` or `Product` JSON-LD on homepage (Phase 6)
- Clean permalink structure via WordPress pages
- Open Graph tags via lightweight custom code or future SEO plugin
- Semantic HTML and fast LCP (optimized hero image)

---

## 14. Deployment

```
Local (Open Server / OSPanel)
  → npm run build (local only)
  → git commit (include assets/dist/)
  → GitHub
  → production: git pull into wp-content/themes/seahivez-theme
```

**Production requirements:**

- PHP 8.0+ (theme header updated in Phase 1B)
- No Node.js on server
- Standard shared WordPress hosting (Apache/Nginx + MySQL)
- Compiled `assets/dist/main.css` and `assets/dist/main.js` committed

**Phase 1B status:** Tailwind + esbuild build configured. Frontend assets enqueued from `assets/dist/`. `style.css` is metadata-only for WordPress theme recognition.

**Pre-deploy checklist:**

1. ~~Resolve nested directory structure~~ ✅ (Phase 1A)
2. ~~Run `npm run build`~~ ✅ (Phase 1B)
3. Verify theme activates at correct path
4. Smoke test homepage and one interior page

---

## 15. Development phases

See `TASKS.md` for the full checkbox roadmap. Recommended implementation order:

1. **Phase 0** — Complete analysis ✅ (this document)
2. **Phase 1** — Tailwind build, refactor `functions.php`, header/footer shell, navigation
3. **Phase 2** — Homepage template parts with hardcoded content matching reference
4. **Phase 3** — Interior templates (page, single, archive, search, 404)
5. **Phase 4** — ACF + CPTs, make content editable
6. **Phase 5** — SuperSaaS booking integration
7. **Phase 6** — Responsive audits, accessibility, performance, SEO polish
