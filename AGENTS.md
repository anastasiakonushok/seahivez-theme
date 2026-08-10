# SeaHivez Theme — Agent Rules

Permanent development rules for Cursor and AI agents working on this project.

## Theme type

- This is a **fully custom WordPress theme**.
- Do **not** use Elementor, Divi, WPBakery, or other page builders.

## Styling and frontend

- **Tailwind CSS** is the primary styling system.
- Use **Vanilla JavaScript** for new frontend functionality.
- Do **not** introduce jQuery unless an existing WordPress dependency explicitly requires it.

## WordPress conventions

- Use native WordPress APIs and the WordPress template hierarchy.
- Do **not** hardcode WordPress URLs such as `/wp-content/themes/...`.
- Use functions such as:
  - `get_header()`
  - `get_footer()`
  - `get_template_part()`
  - `wp_nav_menu()`
  - `get_theme_file_uri()`
  - `home_url()`
  - `get_permalink()`
  - `the_title()`
  - `the_content()`
  - `the_post_thumbnail()`

## Architecture

- Keep `functions.php` lightweight.
- Put functionality into logical files inside `/inc`.
- Keep `front-page.php` lightweight and compose it from template parts.
- Prefer reusable components and template parts instead of duplication.

## Security and data handling

- Escape output using WordPress escaping functions.
- Sanitize input.
- Never commit secrets, passwords, API keys, or environment credentials.

## HTML, UX, and quality

- Follow semantic HTML5.
- Mobile-first responsive development.
- Accessibility is required.
- Performance is required.

## Existing code

- Do **not** remove existing working code without first understanding why it exists.
- Before large architectural changes, explain the intended changes.

## Build and deployment

- Build production CSS/JS locally.
- Keep production assets deployable to shared hosting **without** requiring Node.js on production.
- Maintain compatibility with standard shared WordPress hosting.

## Reference documents

- `PROJECT.md` — project overview and goals
- `TASKS.md` — development roadmap and progress
- `docs/IMPLEMENTATION_PLAN.md` — architecture and implementation details
- `docs/design/` — UI/UX reference assets
