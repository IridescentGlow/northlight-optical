# Northlight Optical — Project Documentation

## 1. Project Context

This repository started as **Sunray**, an open-source eyewear e-commerce demo
(Laravel 10 + Blade + Bootstrap 5). We are turning it into **Northlight
Optical**, a fictional local eyewear store with a full marketing site layered
on top of the existing shop.

The guiding constraint for this work: **the existing implementation is a
foundation, not a redesign target.** Every color, font, spacing rule,
component, animation, and interaction pattern that Sunray already has stays
exactly as it is. What changes is:

- Brand text ("SUNRAY" → "Northlight Optical" / "Northlight") in the places
  it appears — nav wordmark, hero headline, footer, `<title>`, logo alt text.
- New pages for content Sunray never had: Services, About/Team, Promise/Why
  Us, Testimonials, Contact/Booking.

Everything else — the product catalog, cart, checkout, auth, account
dashboard — is reused as-is. It already behaves like a real eyewear store's
shop section, so there was no reason to touch it.

## 2. Site / Page Architecture

### Existing routes (unmodified — see `routes/web.php`)

| Route | Name | Notes |
|---|---|---|
| `GET /` | `home` | Hero + featured products + special offers + sale band |
| `GET /products` | `products.index` | Search/category/type filters, pagination |
| `GET /products/{product}` | `products.show` | |
| `GET,POST,PUT,DELETE /cart...` | `cart.*` | Resource route, `CartService` |
| `GET,POST /register`, `/login` | `register.*`, `login.*` | |
| `GET,POST /logout` | `logout` | auth-gated |
| `GET /account`, `/orders`, `/update-account`, `/update-password` | `account.show`, `orders.index`, `update.account.*`, `update.password.*` | auth-gated |
| `GET,POST /checkout`, `GET /confirmation` | `checkout.*`, `confirmation` | auth + non-empty-cart gated |

### New routes (this project)

| Route | Name | Controller | Auth |
|---|---|---|---|
| `GET /about` | `about` | `AboutController` (single-action) | none |
| `GET /services` | `services` | `ServicesController` (single-action) | none |
| `GET /promise` | `promise` | `PromiseController` (single-action) | none |
| `GET /testimonials` | `testimonials` | `TestimonialController` (single-action) | none |
| `GET /contact` | `contact.create` | `ContactController@create` | none |
| `POST /contact` | `contact.store` | `ContactController@store` | none, CSRF-protected |

All five new pages are static/editorial — no new tables, migrations, or
seeders. Content lives in `config/northlight.php` (business info, hours,
team bios, service list, promise features, testimonials, contact-form
reason options) and is passed straight to Blade views by thin single-action
controllers, mirroring how `HomeController` already works.

The `/contact` POST handler validates the booking/inquiry form and redirects
back with `session('success', ...)`, reusing the flash-alert partial the app
already has (`partials/alerts.blade.php`) instead of inventing a new
notification pattern. It logs the submission via `Log::info()` — there's no
mailer configured in this environment (`.env.example` points `MAIL_HOST` at
`mailpit`, which isn't running), and no new `contact_messages` table, since
that would be schema growth for a demo contact form.

### Navigation integration

The existing header (`partials/header.blade.php`) has a `Categories`
dropdown built from a standard Bootstrap `nav-item dropdown` block. The five
new pages are exposed through one additional dropdown of the same shape,
labeled `About`, placed right after `Categories`:

```
About ▾
  Our Team
  Services
  Our Promise
  Testimonials
  Contact & Booking
```

No new markup pattern, no new CSS — it's the same dropdown structure copied
once. Because it lives inside `#navbar-primary`, the existing mobile
hamburger/collapse behavior covers it automatically; `partials/menubar.blade.php`
(the icon-only bar for small screens) is untouched.

### Reused components

| New usage | Existing pattern it copies |
|---|---|
| Team member grid (`partials/team-member.blade.php`) | Same "partial included in a `@foreach`" shape as `partials/card.blade.php` on Home/Products |
| Testimonial grid (`partials/testimonial-card.blade.php`) | Same shape as above |
| Section headers ("Our Team", "Why Choose Us", etc.) | The `row align-items-center mb-4` + `h4` header block already used on Home/Products |
| Contact form | Field/label/`@error()` markup copied from `checkout/create.blade.php` and `auth/register.blade.php` |
| Promo-style intro bands on About/Promise/Services | The `hero`/`sale` gradient-banner pattern (`partials/hero.blade.php`, `partials/sale.blade.php`) |
| Icons throughout | Inline Feather-style SVGs, same `stroke="currentColor" stroke-width="2" fill="none"` attributes already used for search/cart/user icons |

## 3. Design / UX Rules

1. **No new colors, fonts, or spacing scale.** Only `$brown` / `$brown-dark`
   (via `.text-brown` / `.bg-brown`) and Bootstrap's default utility classes
   are used, exactly as the existing pages use them.
2. **No new SCSS.** `resources/scss/app.scss` is untouched. If a new page
   needs a visual treatment, it's built from utility classes and the
   patterns already in `app.scss` (`.banner` gradient bands, `.card img`
   hover zoom, `.sale-badge`), not new rules.
3. **No new binary assets.** `public/images/` only has `home-banner.jpg`,
   `logo.png`, and `sale-banner.jpg`. Team members and service icons use
   inline SVG and CSS-only initials avatars (`.bg-brown` circle + white
   initials), not photos, because there are no team photos to place without
   inventing fake stock imagery.
4. **No embedded map.** There's no Maps API key and no network access
   assumed for this build, so Contact links out to Google Maps via a plain
   `https://www.google.com/maps/search/?api=1&query=...` URL instead of an
   iframe embed.
5. **Content lives in `config/northlight.php`, not the database.** Team
   bios, services, promise features, and testimonials are editorial copy.
   Adding a migration/model/seeder for content that never needs to be
   queried, filtered, or user-edited would be scope creep against the
   "smallest possible extension" rule.
6. **Diversity is in the copy, not a separate section.** Team and
   testimonial content reflects a range of age, ethnicity, gender, and
   visible disability naturally inside real bios/quotes (a wheelchair-using
   optician, a stylist with macular degeneration, multilingual staff who
   interpret for patients) rather than as a checklist or a labeled
   "diversity" block.

## 4. Implementation Roadmap

1. ~~Inspect repository, confirm architecture~~
2. ~~Get a working local toolchain (PHP/Composer weren't preinstalled;
   SQLite used locally instead of MySQL — see Known Constraints)~~
3. ~~Write this document~~
4. Add `config/northlight.php` content source
5. Add five single-action controllers + routes
6. Build five new Blade views + two new shared partials
7. Rename brand text in `header`, `hero`, `footer`, `.env(.example)`
8. Add the `About` nav dropdown
9. Verify: routes, `npm run build`, live browser check (desktop + mobile),
   console errors, asset 404s, horizontal overflow, nav round-trips
10. Regression-check every pre-existing route still returns 200 and still
    reads "Northlight Optical" consistently
11. Commit in focused, reviewable chunks

## 5. Current Project Status

See [`../PROJECT_STATUS.md`](../PROJECT_STATUS.md) for the live status
snapshot (updated as work lands).

## 6. Known Constraints

- **Local dev DB is SQLite, not MySQL.** The repo's default `.env.example`
  and README assume MySQL. This machine has PHP 8.5 + SQLite only. The
  `products` table seeder (`database/seeders/ProductSeeder.php`) uses raw
  `DB::statement()` INSERT SQL with backslash-escaped quotes — valid MySQL,
  invalid SQLite. That seeder is untouched (it must keep working for real
  MySQL deployments); local verification seeds the same 8 products directly
  via the query builder instead, without touching tracked files.
- **PHP 8.5 is newer than this Laravel 10 lockfile's dev-tooling
  constraints** (`nette/schema`, pulled in by `spatie/laravel-ignition`,
  caps at PHP 8.3). `composer install --ignore-platform-reqs` was used to
  install; this affects only dev-only error-page tooling, not the app
  itself, and `composer.lock` was not regenerated.
- **Product photography is still broken, pre-existing.** `partials/card.blade.php`
  renders `asset('storage/' . $product->image_url)`, but no product images
  ship in the repo (`storage/app/public` is empty aside from the symlink).
  This was true before this project and is out of scope — it's a shop-catalog
  concern, not something the new marketing pages touch.
- **No outbound mail.** `.env(.example)` configures `MAIL_HOST=mailpit`,
  which isn't running anywhere in this setup. The Contact form does not
  attempt to send email; it validates, logs, and flashes a success message.
- **No real map embed, no real business.** Northlight Optical, its address,
  phone number, hours, and every team member and testimonial are invented
  for this project. The Google Maps link is a plain search-query URL, not
  an API-backed embed.
