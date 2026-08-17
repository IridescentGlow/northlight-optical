# Northlight Optical — Project Documentation

## 1. Project Context

This repository started as **Sunray**, an open-source eyewear e-commerce demo
(Laravel 10 + Blade + Bootstrap 5). We are turning it into **Northlight
Optical**, a fictional local eyewear store with a full marketing site layered
on top of the existing shop.

The guiding constraint for this work: **the existing implementation is a
foundation, not a redesign target.** Every color, font, and spacing rule
Sunray already has stays exactly as it is — that constraint never moved.
What changed across two milestones:

**First milestone** — brand text ("SUNRAY" → "Northlight Optical" /
"Northlight") in the places it appears, and new pages for content Sunray
never had: Services, About/Team, Promise/Why Us, Testimonials,
Contact/Booking. No new SCSS, no new animation.

**Second milestone** — three things the first pass didn't cover:
1. The checkout auth prompt (`cart/index.blade.php`) was too low-visibility
   for an action-critical step; replaced with a real callout.
2. The five new pages all shared one repeated card-grid template; each now
   has its own structure (§2a).
3. A site-wide motion/interaction layer (§3a) — hover states with real
   weight, scroll-triggered reveals, a sticky-nav scroll transition, button
   micro-interactions — explicitly requested and explicitly scoped to
   structure/motion only, never color or type.

Everything else — the product catalog, cart, checkout, auth, account
dashboard — is reused as-is. It already behaves like a real eyewear store's
shop section, so there was no reason to touch it beyond the auth-prompt fix.

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
| Testimonial grid (`partials/testimonial-card.blade.php`) | Same "partial included in a `@foreach`" shape as `partials/card.blade.php` on Home/Products |
| Section headers ("Our Team", "Why Choose Us", etc.) | The `row align-items-center mb-4` + `h4` header block already used on Home/Products |
| Contact form | Field/label/`@error()` markup copied from `checkout/create.blade.php` and `auth/register.blade.php` |
| Icons throughout | Inline Feather-style SVGs, same `stroke="currentColor" stroke-width="2" fill="none"` attributes already used for search/cart/user icons |
| Checkout auth callout (`cart/index.blade.php`) | Bootstrap's own `border-primary-subtle` / `bg-primary-subtle` / `btn-primary` / `btn-outline-primary` utilities, generated from the existing `$primary: $brown` token — no new classes |

`partials/team-member.blade.php` existed briefly in the first milestone and
was deleted once the About page moved to bespoke alternating rows (see
§2a) — it had no other caller.

### 2a. Per-page structural differentiation (second milestone)

The first pass of these five pages used one repeated template — a 3-column
card grid with icon/text swapped per page. That template still exists
(Contact's info card, the testimonial supporting grid), but each page's
*primary* content now has its own structure so the site doesn't read as
one component reused five times:

| Page | Structure | Why |
|---|---|---|
| About | Full-width alternating rows (`about/index.blade.php`): large 9rem initials circle, slightly rotated, alternating left/right via `$loop->even` + Bootstrap's `order-md-*`/`text-md-end`. No card, no grid. | "Staggered/asymmetric… give staff more personality" — the only asset available per person is an initials circle (no team photos), so the personality has to come from scale, alternation, and generous bio text, not imagery. |
| Services | Full-width numbered icon-led list (`.service-row`), hairline-divided, no cards. | "Icon-led list or numbered/stepped layout instead of cards." |
| Promise | Full-bleed alternating split panels (`row g-0`, `flex-md-row-reverse` on even items), one side a large icon on an `.icon-panel` gradient, the other side text. | "Alternating split panels (image/text)" — there is no photography for the "image" half (see Design Rule 3, unchanged), so the image half is a large-scale icon on a full-bleed gradient built from the same two brand tokens ($brown → $brown-dark), not a new color. This is a deliberate substitution for a photo, not an unremarked deviation — flagged here and in `PROJECT_STATUS.md`. |
| Testimonials | One large featured pull-quote (`TestimonialController` splits `config('northlight.testimonials')[0]` out), then the remaining five in a tighter supporting grid. | "Featured-quote-plus-supporting-quotes layout… instead of three even cards." |
| Contact | Unchanged two-column form + info card. | Explicitly asked to stay "form-forward and simple" — the deliberate contrast with the other four is the differentiation. |

## 3. Design / UX Rules

1. **No new colors, fonts, or spacing scale.** Only `$brown` / `$brown-dark`
   (via `.text-brown` / `.bg-brown`) and Bootstrap's default utility classes
   are used, exactly as the existing pages use them.
2. **New SCSS is allowed for structure and motion — not for the brand
   system.** The first milestone shipped with a "no new SCSS" rule; the
   second milestone (checkout-prompt fix + differentiated layouts +
   motion/interaction polish) explicitly needed it and the rule was
   relaxed on request. What still holds: no new colors, no new font, no
   new spacing scale. Every addition to `app.scss` reads `$primary`,
   `$brown`, or `$brown-dark` — the same two tokens defined at the top of
   the file — or is pure motion (`transform`, `opacity`, `transition`,
   `backdrop-filter`) with no color of its own. See §3a below.
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

### 3a. Motion & interaction layer

Added in `resources/scss/app.scss` (bottom of file) and
`resources/js/site.js` (new, imported from `app.js`):

- **`.btn-outline-primary` fix.** The pre-existing global `.btn { color:
  $white }` rule (written for `.btn-primary`) made stock
  `.btn-outline-primary` invisible at rest (white text, no fill). Scoped
  override restores `$primary` at rest / `$white` on hover-fill, so it's
  usable as a real secondary action. This is what the "Create Account"
  button on the checkout callout needed; verified with a resting-state and
  hover-state screenshot, not just read from the SCSS.
- **`.hover-lift`** — opt-in lift + shadow on hover, applied to product
  cards (`partials/card.blade.php`) and testimonial cards, alongside the
  existing `.card img:hover` zoom (they compose fine; the lift is on the
  `.card`, the zoom is on the `img` inside it).
- **`.btn`** gained a small press/lift transform on hover/active. Applies
  everywhere `.btn` already does, existing pages included.
- **Sticky header "solidify" on scroll.** `header.sticky-top` is visually
  identical at rest; past 24px of scroll, `site.js` adds `.nav-scrolled`,
  which layers a deeper shadow + `backdrop-filter: blur` + a slightly
  translucent background (`!important`, matching the existing
  `.sale-badge` convention for overriding Bootstrap's `!important`
  utility classes already on the header markup).
- **`.service-row`** — a soft background-tint hover for the Services
  icon-led list rows (a lift/shadow didn't suit a full-width list).
- **`.icon-panel`** — the gradient background for Promise's split panels;
  see §2a for why it exists in place of a photo.
- **Scroll reveal (`.reveal` / `.reveal-pending` / `.is-visible`).**
  Applied to each major section on About/Services/Promise/Testimonials
  and to Contact's two columns. **Accessibility is structural, not a
  checklist item:** an element only ever receives the hidden
  `.reveal-pending` state if `site.js` runs, confirms
  `IntersectionObserver` support, *and* confirms
  `prefers-reduced-motion` is not set. No JS, an old browser, a JS
  error, or a reduced-motion preference all resolve to "fully visible,
  no animation" by construction — there is no code path where content
  depends on motion to become visible. Verified empirically with
  Playwright in both `reducedMotion: 'reduce'` and default contexts,
  scrolled through every route: zero elements ever left in
  `.reveal-pending:not(.is-visible)`.

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

**Second milestone (checkout-prompt fix + differentiated layouts + motion
polish):**

12. ~~Fix the checkout auth prompt on `cart/index.blade.php`~~
13. ~~Add the site-wide motion/interaction layer (§3a) and verify no
    regression, normal + `prefers-reduced-motion`, all routes~~
14. ~~Restructure About/Services/Promise/Testimonials per §2a; leave
    Contact deliberately unchanged~~
15. ~~Re-verify: overflow, console/page errors, stuck-hidden reveals —
    desktop + mobile × normal + reduced motion (40 combinations)~~
16. ~~Update this document and `PROJECT_STATUS.md`~~

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
- **Promise's "split panels" use an icon, not a photo.** The requested
  layout was "alternating split panels (image/text)". There is still no
  photography in this repo (Design Rule 3 is unchanged). The image half
  is a large icon on a full-bleed two-token gradient (`.icon-panel`)
  instead — a deliberate, documented substitution, not a silent
  simplification of the request.
- **`.hover-lift`, the button micro-interaction, and the reveal system
  are visual/motion only.** None of them change what a screen reader
  announces, tab order, or focus behavior. `prefers-reduced-motion`
  removes the animation but never the content (§3a).
