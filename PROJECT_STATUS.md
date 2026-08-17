# Project Status — Northlight Optical

_Last updated: 2026-08-17_

See [`docs/NORTHLIGHT.md`](docs/NORTHLIGHT.md) for full architecture,
design rules, and known constraints. This file tracks live status only.
(Referred to as "PROGRESS.md" in one request — this is the file that
tracks live status; no separate PROGRESS.md exists.)

## Milestone 1: Build new marketing pages on top of Sunray

**Status: complete and verified.**

### Done
- Repository inspected end-to-end (routes, controllers, models, views,
  partials, SCSS, assets).
- Local toolchain stood up: PHP 8.5 + Composer installed, `pdo_sqlite`
  enabled, `composer install --ignore-platform-reqs`, `.env` configured for
  SQLite, app key generated, migrations run, products seeded (via query
  builder, not the MySQL-only tracked seeder), storage symlinked, `npm
  install` + `npm run build` both clean.
- Documentation written (`docs/NORTHLIGHT.md`, this file).
- Content source added: `config/northlight.php` (business info, hours,
  team, services, promise features, testimonials, contact reasons).
- Five new pages shipped, each a thin single-action controller + Blade view
  reading from `config/northlight.php`:
  - `GET /about` — team bios
  - `GET /services` — six service offerings
  - `GET /promise` — accessibility/insurance/multilingual features
  - `GET /testimonials` — six patient testimonials
  - `GET /contact` + `POST /contact` — booking/inquiry form (validated,
    logged, flashes success via the existing alert partial)
- Two new shared partials (`partials/team-member.blade.php`,
  `partials/testimonial-card.blade.php`) mirroring the existing
  `partials/card.blade.php` "include-in-a-foreach" pattern, plus one shared
  `partials/icon.blade.php` for the Feather-style SVGs used on
  Services/Promise/Contact.
- `About` nav dropdown added to `partials/header.blade.php`, copied
  structurally from the existing `Categories` dropdown. Mobile
  hamburger/collapse behavior covers it automatically — verified.
- Brand-text rename ("SUNRAY" → "Northlight" / "Northlight Optical") in
  nav wordmark, hero headline (both breakpoints), footer copyright, logo
  alt text, and `APP_NAME` (`.env` + `.env.example`).
- No SCSS changes, no new binary assets, no new database tables/migrations.

### Verification performed
- `php artisan route:list` — all 6 new named routes resolve correctly.
- `php -l` on every new/modified PHP file — clean.
- `php artisan test` — 2/2 existing tests pass (default Laravel examples;
  repo has no feature-specific tests to extend).
- `npm run build` — Vite/SCSS pipeline builds clean, no new warnings.
- Live HTTP smoke test (`php artisan serve` + `curl`) — every existing
  route (`/`, `/products`, `/products/{slug}`, `/login`, `/register`,
  `/cart`) and every new route returns 200.
- Contact form tested end-to-end over HTTP: valid submission redirects
  with a flash success message and logs the payload; invalid submission
  redirects back with field-level `@error` messages rendered correctly.
- Playwright (desktop 1440px + mobile 390px) across all 10 routes:
  **zero console errors, zero page errors, zero horizontal overflow**
  on every route. The only failed network requests are pre-existing
  product-image 404s on Home/Products (missing `storage/app/public`
  files — present before this work, unrelated to it).
- Visual review of rendered screenshots for Home, About, Services,
  Promise, Testimonials, Contact, and the mobile nav with the `About`
  dropdown expanded — nav fits without wrapping/overflow on both
  breakpoints, hero headline wraps cleanly on mobile.
- Existing pages (Products listing/filters/pagination, product detail,
  cart badge, login/register forms) spot-checked visually — unchanged.

### Known gaps (see `docs/NORTHLIGHT.md` § Known Constraints for detail)
- Product photography was already broken before this work (no seeded
  `storage/app/public` images) — out of scope.
- No outbound email; contact form logs instead of sending mail (no mailer
  configured in this environment).
- Local dev DB is SQLite; the tracked MySQL-only `ProductSeeder` is
  untouched and still needs a real MySQL database to run as shipped.

### Blocked on
- Nothing currently.

---

## Milestone 2: Checkout auth prompt fix + layout differentiation + motion polish

**Status: complete and verified.**

### Done
- **Checkout auth prompt fix.** `cart/index.blade.php`: replaced the
  single muted text line ("Please Register / Login…") shown to guests
  with items in their cart with a bordered/filled callout (Bootstrap's
  `border-primary-subtle` / `bg-primary-subtle`, generated from the
  existing `$primary` token) with a heading and two full-size buttons
  ("Log In", "Create Account").
- **`.btn-outline-primary` cascade fix** in `app.scss` — the button was
  needed for the callout above and was unreadable (white-on-white) under
  the pre-existing global `.btn { color: $white }` rule. Scoped override
  added; verified with resting-state and hover-state screenshots.
- **Site-wide motion/interaction layer** (`app.scss` + new
  `resources/js/site.js`, imported from `app.js`): `.hover-lift`,
  a `.btn` press/lift micro-interaction, a sticky-nav scroll "solidify"
  transition (`.nav-scrolled`, backdrop-blur), and a scroll-reveal system
  (`.reveal` / `.reveal-pending` / `.is-visible`). Full detail and the
  accessibility guarantee (no motion-only dependency) in
  `docs/NORTHLIGHT.md` §3a.
- **Per-page structural differentiation** — About, Services, Promise, and
  Testimonials each rebuilt with their own layout instead of a shared
  card-grid template; Contact deliberately left as-is. Full detail in
  `docs/NORTHLIGHT.md` §2a:
  - About: full-width alternating bio rows, large rotated initials
    circles, no grid.
  - Services: numbered icon-led list, no cards.
  - Promise: full-bleed alternating split panels (icon-on-gradient +
    text) — the "image" half of "image/text panels" is a large icon on
    a two-token gradient (`.icon-panel`), since there is still no
    photography in this repo. Flagged explicitly, not a silent
    simplification.
  - Testimonials: one large featured quote + a five-item supporting
    grid, instead of six even cards.
  - `partials/team-member.blade.php` deleted (no longer used once About
    moved off the card grid).
- `.hover-lift` also applied to the pre-existing product card
  (`partials/card.blade.php`), composing with its existing `img`
  zoom-on-hover rather than replacing it.

### Verification performed
- `php artisan test` — 2/2 pass. `php -l` on all touched PHP files —
  clean. `npm run build` — clean after every change.
- Live HTTP smoke test — all 5 restructured/touched routes return 200.
- Playwright regression sweep across **all 10 routes × desktop (1440px)
  and mobile (390px) × normal motion and `reducedMotion: 'reduce'`**
  (40 combinations): zero horizontal overflow, zero page errors, and —
  the specific a11y check for the reveal system — **zero elements ever
  left in a hidden `.reveal-pending:not(.is-visible)` state** in any
  combination, including after scrolling the full page height. This is
  the empirical proof that reduced-motion users see full content on
  first paint, not just an inference from reading the CSS.
- Visual review of full-page screenshots (desktop + mobile) for About,
  Services, Promise, Testimonials — confirmed each page reads as
  structurally distinct, no clipped/overlapping content in the
  alternating layouts, icon panels stack correctly above text on mobile
  regardless of alternation.
- Product-card `.hover-lift` checked directly: sale badge stays
  correctly anchored through the lift, no shadow clipping from the
  parent `.row`.
- Cart auth callout checked as an actual guest session (product added to
  cart via browser automation, not a stubbed state): heading, both
  buttons, and the outline button's readable-at-rest / fills-on-hover
  states all confirmed by screenshot.

### Known gaps / deliberate substitutions
- Promise's split panels use a large icon on a gradient in place of a
  photo — see `docs/NORTHLIGHT.md` Known Constraints.
- Same pre-existing gaps as Milestone 1 (product photography, no
  outbound mail, SQLite-only local dev) — unchanged by this milestone.

### Blocked on
- Nothing currently.
