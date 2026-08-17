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

**Third milestone** ("last UI pass," §3b) — six signature "wow" details
picked from a list of nine candidates, chosen for on-brand fit and
risk/reward over forcing every option in: a blur-to-focus variant of the
reveal system, a draggable before/after comparison slider, a cursor-lens
zoom on product imagery, upgraded toast notifications, an animated nav
underline, and animated stat counters. Three candidates were deliberately
skipped — reasoning in §3b.

**Fourth milestone** — two things, in the order they were prioritised:
1. **Global navigation & commerce access** (§2b). The cart was reported as
   unreachable without going through a product page. It was in fact already
   rendered on every page at both breakpoints — the real defects were
   discoverability and mobile ergonomics, documented in full in §2b.
2. **Homepage expansion** (§2c). The homepage was three sections long and
   disconnected from the depth of the rest of the site. It is now a curated
   overview built on a deliberate narrative arc, with every module linking
   forward to its full page.

**Fifth milestone** (§2d) — a final adjustment pass before push: a
full-viewport 3D hero with a transparent-over-hero nav, a one-shot light
sweep on the sale banner, and six corrections (two of them regressions
introduced by the fourth milestone). Also two report-only investigations
— the upstream licence position and the product-image filenames — which
resulted in no code change by design.

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
hamburger/collapse behavior covers it automatically.

(`partials/menubar.blade.php` was untouched by this milestone. It was
rebuilt in the fourth — see §2b.)

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

### 2b. Global navigation & commerce access (fourth milestone)

**The reported problem was that there is no globally accessible cart — that
a visitor can't get back to their cart without navigating to a product
page. That premise turned out to be wrong, and checking it first changed
what got built.** `layouts/app.blade.php` includes the header on every
page; the header renders a cart link at ≥lg and `partials/menubar.blade.php`
renders one below lg. Measured across 10 routes × 5 widths, exactly one
cart link is visible everywhere. Nothing was missing.

What *was* wrong was that the cart was hard to see and hard to hit:

| Defect | Fix |
|---|---|
| **At 390px the icon bar wrapped onto a second row** under the hamburger. The brand's `ms-4` plus a full-size wordmark overflowed the flex row, so the cart sat below the primary line, left-aligned, looking like page content rather than navigation. This is almost certainly what produced the report. | Brand takes `me-auto` to pin the bar right; `ms-4` → `ms-2`; the wordmark steps `fs-4` → `fs-5` below lg with a `clamp()` floor so it still fits at 360px. Header is a single 60px row at every width. |
| **The count badge always rendered**, so every page showed a `0` pill — noise that also destroyed the badge's only real signal. | Renders only when the cart has items. |
| **Icon-only controls had no accessible name.** Cart, search, account and the hamburger were unlabelled SVGs. | `aria-label` on each; the cart's announces its count ("Shopping cart, 2 items"), with the badge `aria-hidden` so the number isn't read twice. |
| **Tap targets were the bare icons** — 20px on desktop, 24px on mobile. WCAG 2.5.8 wants 44px; 24px met the older floor by exactly nothing. | New `.nav-icon-link`: 44px minimum target, `min-width` so the account caret can extend it, plus a Bootstrap-shaped focus ring these controls never had (they are neither `.btn` nor `.nav-link`, so they were falling back to the browser's 1px default). |
| **Cart markup was duplicated** between header and menubar, each with its own `CartService` instantiation — and the account menus had already drifted apart (desktop had "My Account", mobile had neither it nor an orders link). | Extracted `partials/cart-button.blade.php` and `partials/account-menu.blade.php`; both call sites include them. |
| **A dead `<a href="#">`** wrapped the signed-in user's name at the top of the account dropdown — a focusable link to nowhere, first in the menu's tab order. | It's a label, so it's now a `dropdown-header`. |
| **No primary CTA anywhere in the nav.** Booking — the action this business actually wants — was two levels deep (`About ▾ → Contact & Booking`). | A "Book an Exam" `btn-primary` in the nav; full-width in the collapsed mobile menu. |

Three things were deliberately **not** built:

- **No global checkout link.** `checkout.create` sits behind `auth` +
  `IsCartEmpty`, so a nav entry would bounce guests and empty carts. The
  cart page is the checkout entry point, and its auth callout (second
  milestone) already handles the guest case.
- **No mini-cart dropdown or offcanvas.** That is new commerce
  functionality; the brief scoped this to surfacing what exists.
- **The header's `d-none d-lg-inline-block` classes stayed.** Removing them
  looks like "making the cart available on mobile" but would render it
  twice inside the expanded menu, since the menubar already shows one.

One deliberate removal: the mobile bar carries **two** icons, not three.
The magnifying glass is not a search field — it is a plain link to the
products listing, which is already one tap away in the menu, plus the hero
CTA and the footer. Its 44px target was what pushed the bar onto a second
row. The desktop bar keeps it, where there is room.

### 2c. Homepage as curated entry point (fourth milestone)

The homepage was hero + two near-identical product grids + a sale banner.
It is now eight sections following an explicit narrative arc — *minimal →
intriguing → informative → impressive → convincing → action*:

| # | Section | Arc beat | Links forward to |
|---|---|---|---|
| 1 | Hero (`partials/hero`, unchanged) | minimal | `/products` |
| 2 | Statement — "Good eye care shouldn't require you to explain yourself first" | intriguing | — |
| 3 | Services preview, 3 of 6 | informative | `/services` |
| 4 | Featured frames, 3 products | impressive | `/products`, each product page |
| 5 | Sale banner (`partials/sale`) | impressive | `/products?type=sale` |
| 6 | Promise band, 3 of 6 | convincing (what's different) | `/promise` |
| 7 | Team strip (3 of 6) + one testimonial | convincing (who's behind it) | `/about`, `/testimonials` |
| 8 | Booking CTA + hours + address | action | `/contact`, `tel:`, Maps |

**Each module is composed for this page, not shrunk down from the page it
links to:**

- **Services** is an asymmetric split — heading, framing copy and the
  forward link in a left column, a tight icon-led list on the right. The
  Services page is a full-width *numbered* list; the homepage version is
  neither numbered nor full-width.
- **Promise** is a single full-width gradient band with three centred
  items. The Promise page is alternating split panels. They share the
  `.icon-panel` gradient so the two read as one brand, without the
  homepage repeating that page's layout.
- **Team** is names and roles only, at 7rem instead of 9rem. Bios belong
  to the About page — that's the reason to click through.
- **Featured frames** deliberately keeps the shared `partials/card`. A
  shopping module should look like a shopping module, and the card carries
  the add-to-cart form, sale badge and lens-zoom hover. The
  homepage-specific work here is the framing — a real heading and a proper
  button in place of the muted "See More" text link.

**The "Special Offers" grid was removed.** It was a second three-card
product grid immediately below the first, which is exactly the "longer
list of cards" the homepage was supposed to stop being. The sale banner
directly beneath still carries the offer story and still links to
`/products?type=sale`, and the sale route stays reachable from the
Categories nav — so nothing became unreachable. `HomeController` no longer
runs that query.

**Curation lives in `HomeController`, selected by title/name rather than
array index**, via a small `pick()` helper that throws a named exception if
a config entry it expects has been renamed. Index-based slicing would
silently change what the homepage shows when `config/northlight.php` is
reordered; this fails loudly instead. Each pick is commented with why those
specific entries: the three *core* services (the differentiators are
covered by the Promise band), the three promises the practice leads with,
one team member per function rather than three optometrists, and —
deliberately — **not** the testimonial the Testimonials page features, but
the one about Soo-ah, who appears in the team strip directly above it, so
the section reads as a single thought.

**Two accessibility defects were found and fixed while building this**, both
by measurement rather than inspection — see §6 and `PROJECT_STATUS.md`:
white text on the brand gradient (2.41:1, fails AA), and a `g-5` gutter
overflowing the viewport below `sm`.

### 2d. Final adjustment pass (fifth milestone)

**Hero.** `partials/hero.blade.php` is now a full-viewport white stage with
a 3D model instead of a photographic banner:

- Height is `calc(100svh - 3.75rem)`, not `100svh`. The header is
  `position: sticky` so it occupies flow rather than overlaying; hero plus
  header therefore fill exactly one screen (verified: 840+60 = 900 at
  1440x900, 784+60 = 844 at 390x844). `svh` rather than `vh` so mobile
  browser chrome can't push the CTA below the fold.
- The model is Google's `<model-viewer>`, loaded as an ES module from
  `ajax.googleapis.com`. **This makes the hero depend on an external host
  at runtime** — see Known Constraints. If that host is unreachable the
  custom element never upgrades, the `slot="poster"` fallback stays on
  screen, and the headline/copy/CTA are unaffected (verified by aborting
  requests to the CDN, not assumed).
- One `<h1>`, replacing the old breakpoint pair that shipped two and hid
  one per breakpoint. A `clamp()` between two existing display sizes
  covers the same range at `font-weight: 700` — Bootstrap's `.display-*`
  are weight 300, which is why the previous hero read as thin.
- Entrance: model drops from above, copy fades up beneath it. The
  0-opacity state exists only inside the keyframes and never as a resting
  style, so `animation: none` under reduced motion falls back to fully
  visible — the same guarantee the `.reveal` system makes (§3a).

**Transparent-over-hero nav.** Skipped in the third milestone as too risky
(§3b) because only Home has a hero and doing it properly meant conditional
per-route header behaviour. What resolves that now is that the new hero
ground is *white*: the existing dark wordmark and brown icons keep exactly
the contrast they have against the normal white bar, so no light/dark nav
variant is needed. Scoped with `request()->routeIs('home')`, and the
existing `.nav-scrolled` handles solidify-on-scroll unchanged.

**Light sweep** (`.light-sweep` + `site.js`). A one-shot diagonal sweep
across the sale banner, fired once the section's bottom edge has scrolled
into view. Deliberately *not* `threshold: 1.0` alone: a threshold of 1 can
never be reached by an element taller than the viewport, which would make
the effect silently never run. The banner measures 300–345px against
844px+ viewports today, so the ratio fallback is insurance against copy
growth rather than a live case. `forwards` holds the finished state, so it
cannot loop or replay on scroll-back (verified by scrolling away and
returning).

**Six corrections**, two of which were regressions from the fourth
milestone:

| Fix | Cause |
|---|---|
| Hamburger visible at >=lg, where clicking it did nothing | **My regression.** The fourth milestone put `d-inline-flex` on `.navbar-toggler`; Bootstrap's display utilities carry `!important` and so beat `.navbar-expand-lg .navbar-toggler { display: none }`. Centring now lives inside `.nav-icon-link` as a plain single-class rule, which correctly loses to Bootstrap's two-class selector at >=lg. **Not deleted** — the element is the only way to reach the nav below lg. |
| `.btn-outline-primary` stuck white-on-transparent after a click | **My regression.** My override set `color: $white` on `:focus`. Bootstrap 5.3 fills the button background on `:hover`, `:focus-visible` and `:active`, but *not* on plain `:focus` — which is exactly what a mouse click leaves behind. Now `:focus-visible`, matching Bootstrap's own state list. |
| Staggered sections cramped | The Promise page's alternating panels are consecutive `<section>`s with no margin, so six alternating colour blocks read as one striped slab. New `.stagger-section` supplies 3rem/5rem between siblings, applied to both places the pattern appears (Promise panels, About bio rows) rather than one. |
| "Read more patient stories" was a bare text link | Now `.btn.btn-outline-primary`, consistent with every other forward link on the homepage. |
| Opening-hours card had hover-only shadow | New `.card-elevated`: persistent resting shadow, and a hover that lifts *and* wipes a brand accent bar across the top edge rather than only deepening the same shadow. |
| `/products` returned 404 | Not a code fault and nothing was deleted — a `public/products/` directory had been created for staging images, and the web server matches a real directory in the document root before Laravel's router ever sees the request. Proven by moving it away (200) and back (404). See Known Constraints. |

### 2e. Pre-push cleanup (sixth milestone)

**Product photos.** `config('northlight.*')` content was never in question,
but the eight product photos referenced by the seeded catalog
(`storage/app/public/images/*.jpg`) were missing since Milestone 1 — the
site's oldest known gap. The user supplied 9 candidate images. Inspecting
them (not just converting them) found that only 2 of the 9 were clean:

| Source file | Defect | Used as |
|---|---|---|
| `image1.png` | none | `eyeglasses3.jpg` |
| `image2.jpg` | none | `eyeglasses5.jpg` |
| `image3.png` | fake checkerboard baked into pixels | `eyeglasses4.jpg` |
| `image4.webp` | fake checkerboard baked into pixels | `eyeglasses1.jpg` |
| `image5.png` | fake checkerboard baked into pixels | `sunglasses3.jpg` |
| `image6.jpg` | "pngtree" watermark tiled across image | not used |
| `image7.jpg` | "pngtree" watermark tiled across image | `sunglasses4.jpg` |
| `image8.png` | fake checkerboard **+** real Ray-Ban wordmark visible on lens/arm | `sunglasses1.jpg` |
| `image9.webp` | fake checkerboard baked into pixels | `sunglasses2.jpg` |

The "checkerboard" is not a transparency bug from converting these to
JPEG — verified by extracting each source's alpha channel and confirming
it is 100% opaque (`mean alpha: 1` on every file). It is drawn directly
into the RGB pixels, the way several stock/mockup marketplaces render a
free preview: a fake "this file is normally transparent, pay to unlock
the real one" watermark. Neither that pattern nor the "pngtree" text was
edited out — doing so would mean defeating another site's rights-
protection mechanism, which was out of bounds regardless of instruction.

This was surfaced to the user directly (not silently shipped, not
silently substituted) with three options — ship only the 2 clean images
and leave the rest broken, ship all 8 with the defects visible, or pause
for different source images. **The user chose to ship all 8 as
converted**, an informed decision made after seeing the specific defects
listed above. `sunglasses1.jpg` ("Classic Black Sunglasses") therefore
carries a real third-party trademark in production; see Known
Constraints.

Converted to the eight exact filenames the app requests (confirmed live
via 404s, not just read from the seeder) and normalized to `.jpg`
regardless of source format. `storage/app/public/.gitignore` gained
per-filename negations — these are static catalog assets with no upload
flow that recreates them, and left ignored they would never have reached
a deploy.

**Hero GLB compression and CDN removal.** Two things flagged as needed
before push, both now done:

- The 11.7MB model is now 585KB, via `gltf-transform`'s optimize pipeline
  (weld, meshoptimizer simplify at a 0.6 vertex-ratio target, Draco
  geometry compression, 2048px textures resized to 1024px and re-encoded
  as WebP). Verified visually, not just by file size — the pipeline's
  *default* compression method is meshopt, which failed outright in
  model-viewer's bundled loader (`setMeshoptDecoder must be called before
  loading compressed files`); switched to `--compress draco`, which
  model-viewer decodes natively. Confirmed pixel-equivalent at a fixed
  camera angle against the original, including a cropped closeup on the
  model's decorative bead/knot detail.
- `@google/model-viewer` replaces the `ajax.googleapis.com` `<script>`
  tag as an npm dependency — but only as a **dynamic** import
  (`resources/js/site.js`, gated on `document.querySelector('model-viewer')`),
  not a static one. A static import in `app.js` was tried first and put
  the ~300KB-gzipped Three.js payload this component bundles into the
  *shared* chunk every page loads, taking it from 26KB to 324KB gzipped
  on About, Cart, Login — everywhere, not just Home. Caught by comparing
  the built bundle sizes before and after, not assumed.
- That import alone was not sufficient: Draco decoding fetches its WASM
  module from `www.gstatic.com` by default, which would have silently
  reintroduced an external-host dependency identical in kind to the one
  just removed. The three required decoder files are now self-hosted
  (`public/draco/`, copied from `three`'s own package — decoder only, the
  encoder isn't needed at runtime) and wired up via
  `window.ModelViewerElement.dracoDecoderLocation`, set as a plain global
  **before** `import('@google/model-viewer')` resolves. This ordering is
  load-bearing: the library reads `self.ModelViewerElement.dracoDecoderLocation`
  itself, synchronously, from each `<model-viewer>` instance's constructor
  as it upgrades during module evaluation — before any `.then()` on that
  import could ever run. Found by reading the library's own bundled
  source after the first attempt (setting the property on the imported
  class binding, after the import resolved) silently didn't work.

Verified: zero non-font external requests on the homepage (down from the
CDN script plus its own `gstatic.com` sub-fetch); model reports
`loaded: true`; GLB served at 572KB; all three decoder files 200 at their
new same-origin path; the `model-viewer` JS chunk requested on `/` and
confirmed absent from network logs on `/products`, `/about`, `/cart`.
Full 100-combination sweep re-run clean, with total console errors across
all 100 runs at **0** (previously 90 — all the product-image 404s the
first fix above resolved). 21-combination signed-in sweep re-run clean.

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
  cards (`partials/card.blade.php`) and testimonial cards. (Originally
  composed with a flat `.card img:hover` zoom on the same image; that zoom
  was replaced by `.lens-zoom` in the third milestone — see §3b.)
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

### 3b. Signature "wow" details (third milestone — last UI pass)

The brief was a list of nine candidate details, pick 4–6, prioritize
on-brand fit, skip anything that doesn't earn its place. Six were built;
three were skipped on purpose. All of it lives in `resources/scss/app.scss`
(bottom of file, under "Signature detail layer") and
`resources/js/site.js`.

**Picked, in the order they appear in `app.scss`:**

1. **Blur-to-focus reveal** (`.reveal-blur`, a modifier on the existing
   `.reveal` system) — an element starts blurred and slightly enlarged,
   then sharpens into focus, instead of just fading/sliding.
   **Why this one first:** it's the most literally on-brand item on the
   list for an optical store — "coming into focus" is what glasses
   *do*. Applied to product card images, the product detail page image,
   About's initials circles, and the icon inside each Promise split
   panel.
   A `--tilt` CSS custom property (defaulting to `0deg`) lets the About
   page's rotated avatar circles keep their static rotation without
   fighting this rule for the `transform` property, since inline
   `style="transform: rotate(...)"` and an external stylesheet's
   `transform` can't both apply — only one wins, and it isn't additive.
   **Bug caught and fixed during verification:** the first version put
   `.reveal-blur` directly on Promise's full-bleed `.icon-panel` divs
   (edge-to-edge, no container gutter). `filter: blur(14px)` bleeds
   visually past an element's box edges, and with the panel already
   touching the viewport edge, that bleed pushed past `100vw` —
   `/promise` measured `overflow: true` (scrollWidth 1452/1440 desktop,
   398/390 mobile) in the Playwright sweep, but only with motion enabled
   (reduced-motion never applies the blurred state, which is exactly why
   it didn't show up there — a good example of why the sweep runs both
   ways). Fixed by moving `.reveal-blur` off the full-width panel and
   onto a small wrapper around just the icon SVG, which sits comfortably
   inset from the panel's edges regardless of blur radius. This is also
   a better effect: blurring a flat color panel is barely visible in the
   first place, since there's no detail in it to blur.
2. **Before/after comparison slider** (`.compare-slider`, on the Promise
   page — "See the Difference"). A draggable divider between a blurred
   layer and a sharp layer of the same content.
   **Why this one:** the second-most on-brand item — a blurry/sharp
   comparison is literally what corrective lenses do, which is a more
   specific and more delightful fit than a generic image slider.
   Since there's still no photography in this repo, both layers render
   the same CSS/HTML "content" rather than a photo: the brand wordmark
   set in decreasing sizes, deliberately styled like a Snellen eye
   chart. That's a substitution in the same spirit as Promise's
   icon-panels (§2a) — flagged here rather than left as an unremarked
   simplification.
   Interaction is Pointer Events (`pointerdown`/`pointermove`/`pointerup`
   in `site.js`, unifying mouse and touch) plus `ArrowLeft`/`ArrowRight`
   keyboard support (`role="slider"`, `aria-valuenow` kept in sync). The
   position update is a direct, immediate response to input — not a
   decorative animation — so there's nothing for `prefers-reduced-motion`
   to disable in the interaction itself; the only thing gated by it is
   the handle's own hover transition. With no JS at all, the slider
   simply stays at its default 50/50 split — both labels ("Without
   Correction" / "With Northlight") are still present and readable in
   the static HTML either way.
3. **Lens-style cursor zoom** (`.lens-zoom`) on product imagery —
   supersedes the original repo's flat `.card img:hover { scale(1.04) }`.
   `site.js` tracks the cursor position over the image and updates
   `transform-origin` on `mousemove`, so the zoom (now `scale(1.18)`)
   centers on whatever part of the image the cursor is over, reading
   like a magnifying lens rather than a uniform center-zoom. Applied to
   `partials/card.blade.php` and the product detail page image, each
   now wrapped in an `overflow-hidden` container so the larger zoom
   doesn't spill past the image's rounded corners.
   **Bug caught and fixed during verification:** these same images also
   carry `.reveal.reveal-blur`. Once an image's reveal fires,
   `.reveal.reveal-blur.reveal-pending.is-visible` (4 classes) stays on
   the element permanently and keeps setting its own `transition`
   shorthand — which silently beat `.lens-zoom`'s 1-class `transition:
   transform 0.3s ease` on specificity. Every hover *after* the initial
   reveal was inheriting the reveal's 0.7s duration instead of the
   intended 0.3s. Not a visible break, just wrong on inspection — caught
   by checking `getComputedStyle(img).transitionDuration` after
   scrolling the image into view, not by assumption. Fixed with a scoped
   `!important` on `.lens-zoom`'s transition (documented inline next to
   the rule, same convention as the pre-existing `.sale-badge`
   override).
4. **Toast notifications** — `partials/alerts.blade.php` rebuilt around
   a `.toast-stack` wrapper and a `.toast-notification` entrance
   animation (`@keyframes northlight-toast-in`, translateY+scale+fade).
   **Why this one:** the existing flash messages were already
   toast-*positioned* (fixed, bottom-end, dismissible) but had no
   entrance motion and, on closer inspection, a latent bug — the cart
   message and the success message each used the exact same fixed
   position independently, so if both ever fired at once they'd have
   rendered exactly on top of each other. The rebuild fixes that
   (`d-flex flex-column-reverse` stacks them) as a natural consequence
   of building this properly, not a separate fix. `site.js` also adds a
   6-second auto-dismiss via Bootstrap's own `Alert.getOrCreateInstance(el).close()` API — every toast
   remains fully readable and manually dismissible
   (`data-bs-dismiss="alert"`) with or without that timer.
5. **Animated underline on nav link hover** — a `::before` pseudo-element
   that grows from 0 to 100% width under `.navbar-nav .nav-link` on
   hover/focus.
   **Bug caught and fixed during verification:** the first version used
   `::after`, which collided with Bootstrap's own dropdown-toggle caret
   — also implemented via `::after` on the same `.nav-link` element.
   The two rules don't merge cleanly (the underline rule's higher
   specificity won for `position`/`content`, keeping Bootstrap's
   border-triangle shape but repositioning it), and the result was a
   visibly broken caret on both "Categories" and "About." Caught by a
   close-up screenshot of the nav, not by reading the CSS — switched to
   `::before`, which nothing else on `.nav-link` uses, confirmed fixed
   by a second screenshot.
6. **Animated stat counters** — three tiles near the top of the About
   page ("Years Serving Portland," "Patients Cared For," "Languages
   Spoken by Our Team"), counting up via `requestAnimationFrame` when
   scrolled into view.
   **Placeholder numbers, flagged as such (per the request):** "15+
   Years Serving Portland" and "8,000+ Patients Cared For" are
   illustrative, not audited business metrics — flagged in a Blade
   comment directly above the markup and here. "15" was deliberately
   chosen to match Dr. Okafor's stated tenure in her bio for narrative
   consistency, not because it's real practice history. "Languages
   Spoken by Our Team" is **not** a placeholder — `AboutController`
   computes it live (`collect($team)->pluck('languages')->flatten()->unique()->count()`),
   currently `8`.
   Each counter's HTML always contains its correct final value as
   static text (e.g. `15+`) — the count-up only ever animates *on top
   of* an already-correct number; if `site.js` never runs, that's
   exactly what a visitor sees. This mirrors the reveal system's rule:
   the animation is additive, never a withholding mechanism.

**Skipped, and why:**

- **Magnetic buttons** (cursor-following shift on primary CTAs). The
  button layer already has a lift/press micro-interaction from the
  second milestone (§3a); adding cursor-chasing motion on top read as
  compounding rather than complementing, and felt tonally off for a
  healthcare-adjacent local business — playful cursor-chase effects fit
  a fashion or consumer-tech brand more than an eye-care practice
  building trust.
- **Transparent-over-hero nav.** The literal version of this pattern
  only makes sense on a page with a hero image behind the header — that
  is Home alone; every other route's header sits on plain page content
  with no hero to be transparent over. Doing it properly would mean
  conditional per-route header behavior (position, z-index, initial
  text color) in a single shared partial used on every page, which is a
  meaningfully bigger structural change than the rest of this pass, and
  risks exactly the kind of "preserve existing layout" regression this
  project has otherwise avoided. The second milestone's `.nav-scrolled`
  "solidify" transition already gives the nav scroll-responsive weight
  without that risk.
- **Staggered word/character reveal on headlines.** Splitting headline
  text into per-character/word spans is a generic premium technique,
  not something that reads as specifically *this* brand's move the way
  blur-to-focus or the before/after slider do — and with six other
  details already landing on these same five pages, adding a seventh
  risked exactly what the brief warned against: "too many reads busy."
  Skipped in favor of restraint.

**Verification for this milestone:** full Playwright sweep re-run after
every fix (not just once at the end) — 10 routes × desktop/mobile ×
normal/reduced motion, 40 combinations, zero overflow / zero page errors
/ zero stuck-hidden reveals in the final run. Plus targeted checks:
`.compare-slider` drag-to-20% and two `ArrowRight` presses verified via
`aria-valuenow` (20 → 30, exactly as expected); `.lens-zoom`
`transform-origin` verified to track a simulated mouse position; a full
register→toast→7-second-wait run verified the toast appears and
auto-dismisses; the nav caret fix and the underline itself were each
confirmed by screenshot, not inferred from the CSS.

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

**Third milestone (signature "wow" details — last UI pass):**

17. ~~Pick 4–6 of 9 candidate details, prioritizing on-brand fit;
    document what was skipped and why (§3b)~~
18. ~~Implement all six: blur-to-focus reveal, before/after slider,
    lens-zoom, toast notifications, animated nav underline, stat
    counters~~
19. ~~Re-run the full Playwright sweep after each fix, not just once at
    the end — caught and fixed three real bugs this way (full-bleed
    blur overflow, a transition-shorthand conflict, a colliding
    `::after` pseudo-element) rather than assuming the CSS was correct~~
20. ~~Targeted verification of each interactive mechanism: slider
    drag + keyboard, lens cursor-tracking, toast auto-dismiss~~
21. ~~Update this document and `PROJECT_STATUS.md`~~

**Fourth milestone (global nav/cart, then homepage expansion — in that
order, since the nav is the more load-bearing of the two):**

22. ~~Audit the actual cart/checkout architecture before changing it —
    established that the cart was already global and that the real
    defects were discoverability and mobile ergonomics (§2b)~~
23. ~~Extract `partials/cart-button` + `partials/account-menu`; fix the
    390px row wrap, the always-on `0` badge, missing accessible names,
    sub-44px tap targets, the dead `href="#"`, and add the "Book an
    Exam" CTA~~
24. ~~Verify at the `navbar-expand-lg` boundary specifically (991 vs
    992), not just desktop/mobile — that is where the cart swaps
    rendering paths entirely~~
25. ~~Commit the nav work on its own before starting the homepage~~
26. ~~Rebuild the homepage on an explicit narrative arc, with curation in
    `HomeController` selected by name rather than index (§2c)~~
27. ~~Re-run the full sweep — caught a real `g-5` viewport overflow and,
    via a contrast audit, white-on-gradient text failing AA at 2.41:1~~
28. ~~Update this document and `PROJECT_STATUS.md`~~

**Sixth milestone (pre-push cleanup):**

29. ~~Diagnose the "products section deleted" report — found no deletion;
    `public/products/` (a staging folder for new photos) was shadowing
    the `/products` route at the web-server level~~
30. ~~Inspect all 9 candidate product photos individually rather than
    converting them blind — found 6 with a watermark or a fake
    checkerboard baked into their pixels, one of those also a real
    Ray-Ban trademark, and surfaced the full breakdown to the user
    before proceeding~~
31. ~~Convert and place all 8 (per the user's explicit choice), track
    them past `storage/app/public/`'s default gitignore~~
32. ~~Compress the hero GLB — first attempt (meshopt) silently failed to
    render in model-viewer; caught by testing, switched to Draco~~
33. ~~Remove the hero's CDN dependency on ajax.googleapis.com — first
    attempt reintroduced an equivalent dependency on www.gstatic.com via
    Draco's default decoder location; caught by network-request
    inspection, not assumed fixed~~
34. ~~Re-run the full sweep and the signed-in sweep after every change~~
35. ~~Update this document and `PROJECT_STATUS.md`~~

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
- **Product photography now exists, but with real defects the user chose
  to accept.** `storage/app/public/images/*.jpg` (8 files, tracked) render
  correctly across the catalog. Six of the eight visibly show a fake
  "unlock to get the real transparent file" checkerboard or a tiled
  "pngtree" watermark, and `sunglasses1.jpg` ("Classic Black Sunglasses")
  is a real Ray-Ban stock photo with the Ray-Ban wordmark legible in the
  image itself — a genuine third-party trademark now live in the catalog.
  Full per-image breakdown in §2e. **This needs resolving with real,
  rights-cleared photography before the site is shown to anyone outside
  this project** — the trademark in particular is a different order of
  problem than a cosmetic watermark.
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
- **The before/after slider's "photo" is a styled eye chart, not an
  image.** Same root cause as the Promise icon-panels above — no
  photography exists in this repo. Both slider layers render identical
  brand-wordmark typography (styled like a Snellen eye chart), one
  layer blurred, the other sharp. Flagged here and in §3b, not a silent
  substitution.
- **Two About-page stats are illustrative placeholders, one is real.**
  "15+ Years Serving Portland" and "8,000+ Patients Cared For" are not
  audited business metrics — they exist to demonstrate the count-up
  interaction and were explicitly requested as placeholders. "Languages
  Spoken by Our Team" is computed live from `config('northlight.team')`
  in `AboutController` and is not a placeholder. Full detail in §3b.
  Before this site is treated as real, the two illustrative numbers need
  replacing with actual figures (or removing).
- **White text does not pass contrast on the brand gradient, anywhere.**
  Measured against both `.icon-panel` stops, white is **2.41:1** and
  **2.77:1** — failing WCAG AA for normal text (4.5:1) *and* for large
  text (3:1). The homepage's Promise band therefore uses near-black type
  on the gradient (6.39:1 / 5.57:1) with a `.btn-dark` CTA (15.4:1). The
  brand colour itself is untouched; only the ink changed.
  **Still open:** the Promise *page* (§2a) puts white 64px icons on the
  same gradient, at that same 2.4–2.8:1. Those icons are decorative and
  sit beside text that states the same thing, so they are defensible
  under SC 1.4.11's decorative exemption — but if that band ever gains
  white *text*, it inherits a real AA failure. Not changed here because
  it is outside this milestone's scope and would alter an established
  page's visual direction.
- **Never create a directory under `public/` whose name collides with a
  route.** `public/products/` made `GET /products` return 404 site-wide:
  the web server resolves a real path in the document root before Laravel
  routes the request. It looks exactly like a deleted controller or view.
  Staging images live in `reference/product-images/` (untracked) instead.
- **The upstream project ships no licence.** There is no `LICENSE` file,
  and the README has no licence or attribution section.
  `composer.json`'s `"license": "MIT"` sits beside `"name":
  "laravel/laravel"` — it is the stock Laravel skeleton value describing
  the framework, not the Sunray work built on top of it. Absent a licence,
  default copyright applies, which grants *fewer* rights than MIT rather
  than more. There is therefore no basis on which removing the footer
  credit is clearly permitted, and it stays. This needs a decision from
  the upstream author, not an inference from us.
- **`origin` still points at the upstream repository**
  (`git@github.com:bhupindersingh007/sunray.git`). Nothing has been
  pushed. A `git push` from this clone targets the original author's
  repository, not a fork — the remote has to be repointed before any push.
  **Not resolved — still blocks push.**
- ~~The hero depends on an external host at runtime.~~ **Resolved in the
  sixth milestone.** `@google/model-viewer` is now an npm dependency,
  dynamically imported only on the route that uses it, with its Draco
  decoder self-hosted at `public/draco/` rather than fetched from
  `www.gstatic.com`. Verified zero non-font external requests on `/`. See
  §2e for why this took two attempts to get right.
- ~~`public/models/hero-glasses.glb` is 11.7MB.~~ **Resolved in the sixth
  milestone.** Now 585KB via Draco geometry compression + WebP textures,
  confirmed pixel-equivalent to the original at a fixed camera angle. See
  §2e.
- **`g-5` overflows the viewport below `sm`.** A 3rem horizontal gutter
  gives a `.row` -24px side margins while `.container` only pads 12px.
  Above `sm` the container is centred with slack to absorb it; below,
  it's fluid, so the page scrolls sideways by 12px. Homepage rows use
  `gy-5 g-lg-5` instead. Worth knowing before adding `g-5` anywhere new —
  it looks correct at every width a desktop browser is usually tested at.
