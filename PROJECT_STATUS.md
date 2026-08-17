# Project Status — Northlight Optical

_Last updated: 2026-08-17_

See [`docs/NORTHLIGHT.md`](docs/NORTHLIGHT.md) for full architecture,
design rules, and known constraints. This file tracks live status only.

## Current milestone: Build new marketing pages on top of Sunray

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
