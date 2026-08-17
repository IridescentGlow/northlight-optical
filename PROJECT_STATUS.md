# Project Status — Northlight Optical

_Last updated: 2026-08-17_

> **Not pushed and not deployed.** All work is local commits on `main`.

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

---

## Milestone 3: Signature "wow" details (last UI pass)

**Status: complete and verified.**

### Done

Six of nine candidate details, picked for on-brand fit and risk/reward.
Full reasoning (including the three skipped) in `docs/NORTHLIGHT.md` §3b.

- Blur-to-focus reveal (`.reveal-blur` modifier) — product images,
  product detail image, About avatar circles, Promise panel icons.
- Before/after comparison slider on Promise ("See the Difference") —
  draggable + keyboard-operable, blurred vs. sharp eye-chart-styled
  brand wordmark (no photography exists in this repo, so this is a
  content substitution, flagged explicitly).
- Lens-style cursor zoom on product imagery (`.lens-zoom`), replacing
  the original flat `.card img:hover { scale(1.04) }`.
- Upgraded toast notifications (`partials/alerts.blade.php`): entrance
  animation, 6-second auto-dismiss, and a fix for a latent bug where
  two simultaneous flash messages would have rendered exactly on top of
  each other.
- Animated underline on nav link hover.
- Animated stat counters on About (2 illustrative placeholders + 1 real
  number computed from team data — see Known Constraints below).

Skipped on purpose: magnetic buttons (redundant with existing button
lift, tonally off for a healthcare-adjacent brand), transparent-over-hero
nav (only Home has a hero; doing it properly means conditional per-route
header behavior in a shared partial — bigger structural risk than the
rest of this pass for a "solidify on scroll" effect already covered),
staggered headline text reveal (six other new details already landing on
these five pages; a seventh risked "too many reads busy").

### Verification performed

- Full Playwright sweep re-run after **each** fix, not once at the end —
  10 routes × desktop (1440px)/mobile (390px) × normal/`reducedMotion:
  'reduce'`, 40 combinations. Final run: zero overflow, zero page
  errors, zero elements stuck in a hidden pre-reveal state.
- **Three real bugs found and fixed by this process, not by inspection:**
  1. Blur bleed past the viewport edge on Promise's full-bleed icon
     panels (`overflow: true`, motion-enabled runs only) — moved the
     blur-focus effect from the panel onto the icon itself.
  2. A `transition` shorthand conflict where `.lens-zoom`'s hover
     duration was silently overridden by `.reveal.reveal-blur`'s
     higher-specificity rule after the first reveal — confirmed via
     `getComputedStyle`, fixed with a scoped `!important`.
  3. The nav underline's `::after` collided with Bootstrap's own
     dropdown-toggle caret (also `::after`), visibly corrupting the
     "Categories" and "About" carets — caught by a close-up screenshot,
     fixed by switching to `::before`.
- Targeted functional checks: compare-slider drag-to-20% and two
  `ArrowRight` presses verified via `aria-valuenow` (20 → 30); lens-zoom
  `transform-origin` verified to track a simulated cursor position; a
  full register → toast-appears → 7s-wait → toast-gone run verified
  auto-dismiss; `php artisan test` (2/2 pass) and `php -l` on every
  touched file.

### Known gaps / deliberate substitutions
- The before/after slider's "photo" is a styled eye chart (brand
  wordmark typography), not a real image — same root cause as the
  Promise icon-panels (no photography in this repo).
- Two of the three About stat counters ("15+ Years Serving Portland,"
  "8,000+ Patients Cared For") are illustrative placeholders, explicitly
  requested as such — not audited business metrics. The third
  ("Languages Spoken by Our Team") is computed live from real team data
  and is not a placeholder. **Before this site is treated as real, the
  two placeholder numbers need replacing with actual figures.**
- Same pre-existing gaps as Milestones 1–2 (product photography, no
  outbound mail, SQLite-only local dev) — unchanged by this milestone.

### Blocked on
- Nothing currently.

---

## Milestone 4: Global nav/cart access, then homepage expansion

**Status: complete and verified.** Two parts, done in sequence and
committed separately — the nav is the more load-bearing of the two.

### Part 1 — Global navigation & commerce UX

**The reported premise was wrong, and checking it first is what shaped the
work.** The cart was reported as unreachable without going through a
product page. It was already rendered on every page at both breakpoints
(`layouts/app.blade.php` includes the header everywhere; the header
renders a cart link at >=lg, `partials/menubar` renders one below lg).
Measured across 10 routes x 5 widths: exactly one visible cart link,
everywhere. Nothing was missing — so this became a discoverability and
mobile-ergonomics fix instead of new plumbing. Full detail in
`docs/NORTHLIGHT.md` §2b.

Fixed:
- **The 390px header wrapped onto a second row**, dropping the cart below
  the primary line where it read as page content, not navigation. This is
  almost certainly what produced the original report. Header is now a
  single 60px row from 360px up.
- **The count badge always rendered** — every page showed a `0` pill,
  which destroyed the badge's only real signal. Now renders only when the
  cart has items.
- **Icon-only controls had no accessible name.** All labelled; the cart's
  announces its count, with the badge `aria-hidden` so it isn't read twice.
- **Tap targets were the bare 20–24px icons.** New `.nav-icon-link` gives
  44px (WCAG 2.5.8) plus a Bootstrap-shaped focus ring these controls
  never had — they are neither `.btn` nor `.nav-link`, so they were
  falling back to the browser's 1px default outline.
- **Cart and account markup were duplicated** across two partials and had
  already drifted. Extracted `partials/cart-button` and
  `partials/account-menu`.
- **A dead `<a href="#">`** on the signed-in user's name, first in the
  account menu's tab order. Now a `dropdown-header`.
- **No primary CTA in the nav.** Added "Book an Exam"; booking was
  previously two levels deep.

Deliberately not built: no global checkout link (`checkout.create` is
behind `auth` + `IsCartEmpty` and would bounce guests and empty carts), no
mini-cart dropdown or offcanvas (new commerce functionality, out of
scope), and the header's `d-none d-lg-inline-block` classes stayed —
removing them would render the cart *twice* in the expanded mobile menu.

One deliberate removal: the mobile bar carries two icons, not three. The
magnifying glass is not a search field, just a link to the products
listing that is already in the menu, the hero CTA and the footer; its
44px target is what pushed the bar onto a second row.

### Part 2 — Homepage expansion

Hero + two near-identical product grids + a sale banner became eight
sections on an explicit arc (minimal → intriguing → informative →
impressive → convincing → action). Section table and per-module design
reasoning in `docs/NORTHLIGHT.md` §2c.

- Each module is composed for the homepage, not shrunk from the page it
  links to: Services is an asymmetric heading/list split (the Services
  page is a numbered full-width list); Promise is one gradient band (the
  Promise page is alternating panels); the team strip is names and roles
  only, no bios.
- Featured frames deliberately keeps the shared `partials/card` — a
  shopping module should look like one, and the card carries the
  add-to-cart form, sale badge and lens-zoom. The homepage work there is
  the framing, not a bespoke card.
- **"Special Offers" was removed** — a second three-card grid directly
  below the first is exactly the "longer list of cards" this pass was
  meant to end. The sale banner still carries the offer and still links to
  `/products?type=sale`; nothing became unreachable.
- Curation lives in `HomeController`, selected **by title/name, not array
  index**, through a `pick()` helper that throws a named exception if a
  config entry is renamed — index slicing would silently change the
  homepage when `config/northlight.php` is reordered.
- Every module links forward to its full page.

### Verification performed

- **Full sweep, 100 combinations**: 10 routes x 1440/992/991/768/390 x
  normal and reduced motion. Final run: **0 flagged**. The 991/992 pair is
  deliberate — that is the `navbar-expand-lg` boundary where the cart
  swaps rendering paths entirely, and desktop+mobile alone would not
  discriminate it. Per combination the sweep asserts: no horizontal
  overflow, no page errors, no reveal stuck hidden, **exactly one visible
  cart**, no unnamed header control, no sub-44px icon target.
- **Two real defects found by measurement, not inspection:**
  1. **White text on the brand gradient fails AA** — 2.41:1 and 2.77:1
     against the two `.icon-panel` stops, failing even the 3:1 large-text
     threshold. Found by scripting a contrast audit over the rendered
     band rather than eyeballing it. The Promise band now uses near-black
     type (6.39:1 / 5.57:1) and a `.btn-dark` CTA (15.4:1). 17/17 samples
     pass. The brand colour is unchanged; only the ink changed.
  2. **`g-5` overflowed the viewport by 12px at 390px** in *both* motion
     modes, so not the blur-reveal bug pattern from Milestone 3. A 3rem
     gutter gives -24px row margins against `.container`'s 12px padding;
     harmless while the container is centred with slack, an overflow once
     it goes fluid below `sm`. Located by scripting a per-element
     bounding-box scan rather than guessing. Homepage rows use
     `gy-5 g-lg-5`.
- **Accessibility, homepage:** 27 keyboard stops walked at 1440 and 390 —
  tab order follows visual order, **0 stops without a focus indicator**,
  0 nested interactive controls, 13/13 `main` links have an accessible
  name (Playwright's own name computation, not `textContent`). Under
  `prefers-reduced-motion`, all 14 reveals render fully visible and
  unblurred on first paint.
- **Nav, functional:** badge and `aria-label` confirmed empty vs
  populated; cart reached from `/promise` (a non-product page) with the
  checkout CTA present on arrival; account menu checked **signed in** at
  both breakpoints — identical entries, every destination 200, logout
  returns the menu to its guest state.
- **Signed-in sweep, 21 further combinations** (7 routes x 1440/991/390,
  with an item in the cart and the account dropdown opened on each). The
  100-combination sweep above ran entirely as a guest, which never enters
  the authenticated rendering path: the account menu is taller signed in
  (header + divider + two links + a logout form) and hangs off a
  `.nav-icon-link` that also carries Bootstrap's `dropdown-toggle::after`
  caret — the exact case `min-width` was chosen for. This run also covers
  `/account` and `/orders`, which a guest sweep cannot reach at all.
  **0 flagged**: no overflow with the menu shut or open, no menu escaping
  the viewport, one visible cart, no unnamed or sub-44px control, three
  menu entries everywhere, no page errors.
- **Links:** every forward link from every homepage module resolves 200;
  heading order matches the intended arc.
- `php artisan test` 2/2 pass, `php -l` clean on every touched file,
  `npm run build` clean. `pick()`'s guard verified to actually throw its
  named exception, and to preserve requested order.

### Known gaps / deliberate substitutions

- **Product photography is still missing** (pre-existing, Milestone 1).
  The remaining console errors on `/` and `/products` are those image
  404s and nothing else — confirmed by reading the messages, not assumed.
  This is most visible on the homepage's featured-frames module, which is
  a shopping module rendering alt text instead of frames.
- **The Promise *page* still puts white icons on the brand gradient** at
  that same 2.4–2.8:1. Defensible today — they're decorative and sit
  beside text saying the same thing — but that band must never gain white
  *text*. Left alone as outside this milestone's scope; flagged in
  `docs/NORTHLIGHT.md` §6.
- `partials/sale.blade.php`'s heading changed `h1` → `h2.h1`: the hero
  already owns the page's only `h1` and the banner now sits mid-page
  among `h3`s. Rendered size is byte-identical.
- Same pre-existing gaps as Milestones 1–3 (no outbound mail, SQLite-only
  local dev, invented business details, illustrative About stats).

### Blocked on

- Nothing. **Not pushed, not deployed** — awaiting the go-ahead.

---

## Milestone 5: Final adjustment pass

**Status: complete and verified. Not pushed, not deployed.**

### Two report-only items (no code change, by design)

- **Product image filenames.** `storage:link` is correctly set up
  (`public/storage` -> `storage/app/public`). The app requests exactly
  eight files, confirmed from live 404s rather than only from the seeder:
  `sunglasses1.jpg`, `sunglasses2.jpg`, `sunglasses3.jpg`,
  `sunglasses4.jpg`, `eyeglasses1.jpg`, `eyeglasses3.jpg`,
  `eyeglasses4.jpg`, `eyeglasses5.jpg` — all `.jpg`, all in
  `storage/app/public/images/` (note: there is no `eyeglasses2`). The
  directory now exists and is empty, ready for them.
  **Caveat for deploy:** `storage/app/public/` is gitignored by stock
  Laravel, so files placed there are local-only and will not reach a
  server via `git push`. They need to be uploaded, committed to a tracked
  path, or handled by the deploy process.
- **Licence.** No `LICENSE` file exists and the README has no licence or
  attribution section. `composer.json`'s `"license": "MIT"` is the stock
  `laravel/laravel` skeleton value describing the framework, not the
  Sunray work. With no licence granted, default copyright applies —
  *fewer* rights than MIT, not more. Removing the footer credit is
  therefore not demonstrably permitted, so **it has not been touched**.

### Done

- **Full-viewport 3D hero** — white ground, `<model-viewer>` GLB, model
  drops in from above while the copy fades up, bolder single `<h1>`
  (weight 700, replacing the old two-h1 breakpoint pair).
- **Transparent-over-hero nav on Home only**, solidifying via the existing
  `.nav-scrolled`. This was skipped in Milestone 3 as too risky; a white
  hero removes the need for a light/dark nav variant, which is what made
  it safe now.
- **One-shot light sweep** on the 25%-off banner, fired once the section
  is fully in view.
- **Six corrections**, including two regressions from Milestone 4 (the
  always-visible hamburger and the stuck outline button). Full cause
  analysis in `docs/NORTHLIGHT.md` §2d.
- **`/products` 404 explained and fixed.** Nothing had been deleted; a
  `public/products/` staging directory shadowed the route, since the web
  server matches a real path in the document root before Laravel routes
  the request. Proven by moving it away (200) and back (404). Images moved
  to `reference/product-images/`. This required no tracked-file change.

### Verification performed

- **Full sweep, 100 combinations** (10 routes x 1440/992/991/768/390 x
  normal and reduced motion): **0 flagged**. Assertions per combination:
  no horizontal overflow, no page errors, no reveal stuck hidden, exactly
  one visible cart, no unnamed or sub-44px header control, and — new this
  pass — **nothing left invisible by an entrance animation**.
- **Signed-in sweep, 21 combinations** (7 routes x 1440/991/390, cart
  populated, account dropdown opened on each): 0 flagged.
- **Hamburger**: hidden at 1440 and 992; at 991 and 390 it still opens the
  menu (collapse 0 -> 214px). Nav links reachable at all four widths.
- **Outline buttons**: all five on the homepage confirmed returning to
  gold-on-transparent after click-then-unhover, with keyboard
  `:focus-visible` still filling.
- **Light sweep**: does not fire on partial entry, fires once the bottom
  edge clears the fold, does **not** replay after scrolling away and back,
  and never fires under reduced motion.
- **Hero**: hero+header = exactly the viewport at 1440x900 and 390x844;
  white ground; nav transparent at rest and opaque after scroll; nav stays
  opaque on `/about` (not-Home); single `<h1>` at weight 700; all
  entrance-animated elements settle at opacity 1 in **both** motion modes.
- **Hero CDN failure path verified, not assumed** — with requests to
  `ajax.googleapis.com` aborted, the component never upgrades, the poster
  fallback holds the space, and headline/copy/CTA render normally with no
  page errors and no overflow. Both the working and failing paths were
  confirmed.
- **Staggered spacing**: Promise gaps 0px -> 80px; About 80–100px.
- **Hours card**: persistent resting shadow, hover adds a -4px lift plus
  an accent bar scaling 0 -> 1.
- All 90 console errors across the sweep are the pre-existing product
  image 404s, enumerated by URL — nothing from `model-viewer` or the GLB.
- `php artisan test` 2/2, `php -l` clean on all touched files,
  `npm run build` clean.

### Known gaps

- **`origin` still points at `bhupindersingh007/sunray`.** A push from
  this clone targets the upstream author's repository. Must be repointed
  before any push.
- **The GLB is 11.7MB** — a heavy first-paint cost on the most important
  page. Draco/meshopt + KTX2 would typically get it under 2MB.
- **The hero depends on an external CDN at runtime**; npm-installing
  `@google/model-viewer` would remove that at ~300KB of bundle.
- Product photography still absent until the eight files above are added.
- Everything previously listed for Milestones 1–4 is unchanged, including
  the Promise page's white-on-gradient icons.

### Blocked on

- Push/deploy decision, and the remote question above.

---

## Milestone 6: Pre-push cleanup

**Status: complete and verified. Not pushed, not deployed.**

### The "deleted products section" — not a deletion

Working tree was clean at the start of this pass; nothing had been
removed. `GET /products` returned 404 because a new `public/products/`
staging folder shadowed the route at the web-server level (a real
directory in the document root resolves before Laravel ever sees the
request). Proven by moving it away (200) and back (404). Images relocated
to `reference/product-images/` (untracked, safe).

### Product photos

Only 2 of the 9 images supplied were clean. The other 7: 5 have a fake
"pay to unlock the real transparent file" checkerboard baked directly
into their pixels (confirmed via alpha-channel extraction — 100% opaque
on every one, so this is not a conversion bug), 2 carry a tiled "pngtree"
watermark, and one of the checkerboard images is a real Ray-Ban stock
photo with the wordmark legible on the lens and arm.

This was surfaced to the user in full — which image has which defect,
which slot it would fill — before any of them were wired in, with three
options offered. **The user chose "ship all 8 as converted, watermarks
and all."** All 8 are now live at the exact filenames the catalog
requests, tracked past `storage/app/public/`'s default gitignore (was
gitignoring everything; these are static catalog assets with no upload
flow to recreate them).

**Real trademark now live in the catalog, by the user's informed choice:**
`sunglasses1.jpg` ("Classic Black Sunglasses") is Ray-Ban's own product
photo. This should be resolved with a rights-cleared photo before the
site is shown to anyone outside this project.

### Hero: 11.7MB → 585KB, CDN dependency removed

Both flagged as needed before push in the prior milestone; both done, and
both took a second attempt after the first one silently didn't work —
caught by testing, not assumed:

- Compression via `gltf-transform optimize`. First run used the
  pipeline's default (meshopt) and produced a GLB that failed to render
  at all in model-viewer's bundled loader. Switched to
  `--compress draco`, confirmed pixel-equivalent to the original via a
  fixed-camera-angle screenshot comparison including a cropped closeup on
  the model's fine bead/knot detail.
- CDN removal via `@google/model-viewer` as an npm dependency — but as a
  **dynamic** import gated on the page actually containing a
  `<model-viewer>` element. A static import first tried in `app.js` put
  ~300KB gzipped in the *shared* bundle every route loads; caught by
  comparing build output sizes before/after, fixed by moving the import
  into `site.js` behind a `document.querySelector` check.
- That still weren't enough: Draco's WASM decoder is fetched from
  `www.gstatic.com` by default, which would have quietly reintroduced an
  external-host dependency of the same kind just removed. Caught by
  inspecting the actual network requests, not by reading the library's
  advertised API. Fixed by self-hosting the three decoder files at
  `public/draco/` and setting `window.ModelViewerElement.dracoDecoderLocation`
  as a **global, before** the dynamic import resolves — the library reads
  that property synchronously as each element upgrades, during import
  evaluation, before any `.then()` could run. Found by reading the
  library's own bundled source after the "set it after import" version
  silently did nothing.

### Verification performed

- Every image's checkerboard/watermark defect confirmed by direct visual
  inspection at 2x-3x zoom, not inferred from thumbnails.
- Alpha-channel extraction (`mean alpha: 1` on all 5 checkerboard sources)
  confirming the pattern is baked into RGB, not a real-transparency
  conversion bug on our end.
- All 8 final image URLs return 200; 0 broken `/storage/` requests
  site-wide (down from the pre-existing 9 broken image requests present
  since Milestone 1).
- GLB before/after compared at a **fixed** camera angle (no auto-rotate
  drift to hide behind) plus a cropped closeup on the model's most
  detailed feature — pixel-equivalent.
- Bundle sizes compared before/after each hero JS change: shared bundle
  confirmed back at ~26KB gzip (matching pre-hero baseline) with
  `model-viewer` isolated into its own ~298KB chunk.
- Per-route network logging: `model-viewer` chunk requested on `/`,
  absent on `/products`, `/about`, `/cart`. Zero non-font external
  requests on `/` (down from the CDN script plus its gstatic.com
  sub-fetch). Model reports `loaded:true`. GLB served at 572KB. All three
  self-hosted decoder files return 200.
- Full 100-combination sweep re-run: **0 flagged, 0 console errors across
  all 100 runs** (previously 90 — every one was a product-image 404, now
  fixed). 21-combination signed-in sweep re-run: 0 flagged.
- `php artisan test` 2/2, `php -l` clean, `npm run build` clean.
- Confirmed `origin` unchanged throughout
  (`git@github.com:bhupindersingh007/sunray.git`) and nothing pushed.

### Known gaps

- **The Ray-Ban trademark in `sunglasses1.jpg`** — see above. Needs a
  rights-cleared replacement before this is shown outside this project.
- **`origin` still points at the upstream repo.** Must be repointed
  before any push — this was never in scope to change unilaterally.
- **The upstream licence question is unresolved** (Milestone 5): no
  LICENSE file, no attribution terms in the README; the footer credit
  stays until the upstream author says otherwise.
- Everything else from Milestones 1–5 not listed above is unchanged.

### Blocked on

- Push/deploy decision, the remote question, and the trademark image —
  all the user's call, not made here.

---

## Milestone 7: Pushed and deployed

**Status: live.**

- Pushed to a new repo under the user's own GitHub account:
  `github.com/IridescentGlow/northlight-optical` (`upstream` remote keeps
  `bhupindersingh007/sunray` for reference; nothing was ever pushed there).
- Deployed to Railway (chosen over Vercel — no native PHP/Laravel support
  there): project `northlight-optical`, MySQL service + a Dockerfile-built
  web service, auto-deploying from `main` on every push.
- **Live at: https://northlight-web-production.up.railway.app**

Two real bugs shipped and were fixed within minutes of being found —
both by actually running the container and hitting real routes, not by
reading the Dockerfile:

1. `mkdir -p storage/framework/{sessions,views,cache}` doesn't expand
   under Docker's default `/bin/sh` (dash isn't bash) — created one
   literal directory instead of three, so every request 500'd on
   `file_put_contents(.../sessions/<id>)`. This briefly went live before
   the fix landed (auto-deployed from the first push). Fixed by writing
   three explicit `mkdir -p` calls.
2. `TrustProxies::$proxies` was at the framework default (trust nothing),
   which reads `$request->secure()` as false behind any TLS-terminating
   reverse proxy — silently emits `http://` links from `url()`/`asset()`
   regardless of `APP_URL`. Set to `'*'`, safe here since the app only
   ever receives connections from Railway's own edge on a private network.

Full functional verification on the live deployment (curl-based — this
sandbox's headless browser cannot reach external network, a limitation
hit earlier in this project too):

- All 10 routes 200 on a fresh guest session.
- Product image served from the tracked catalog: 200.
- Registered a real account against live MySQL → session cookie set with
  `secure` flag (confirms the TrustProxies fix) → homepage correctly
  shows "My Account" instead of "Login".
- Added a product to cart → cart page shows the item and a visible
  Checkout link → `/checkout`, `/account`, `/orders` all 200 while
  authenticated.

### Known gaps carried forward, unchanged by this milestone

- `sunglasses1.jpg` is a real Ray-Ban stock photo, live now — see
  Milestone 6. Still needs a rights-cleared replacement.
- The upstream licence question is still open — footer credit unchanged.
- `php artisan serve` (Laravel's built-in single-threaded dev server) is
  what's actually running in production, not php-fpm+nginx or Octane —
  adequate for this traffic level, not a real production web server.
  Worth revisiting if this needs to handle concurrent load.
- No custom domain configured — running on Railway's `*.up.railway.app`.
