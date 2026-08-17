{{--
    Full-viewport hero: white ground, 3D frames model, bold headline.

    Height is calc(100svh - header) rather than 100svh. The header is
    position:sticky, so it occupies flow rather than overlaying — hero +
    header together fill exactly one screen, and because the header is
    transparent over this section (see .nav-over-hero in app.scss) and the
    hero ground is white, the two read as a single uninterrupted surface.
    svh, not vh, so mobile browser chrome doesn't push the CTA below the
    fold on first paint.

    One <h1>, not the old breakpoint pair — the previous markup shipped two
    copies and hid one per breakpoint, which put a second h1 in the DOM.
    A clamp() between two existing display sizes covers the same range.

    <model-viewer> is registered via resources/js/app.js
    (`import '@google/model-viewer'`), bundled through Vite rather than
    loaded from ajax.googleapis.com — the hero no longer depends on an
    external host being reachable at runtime. The slot="poster" fallback
    below still matters even without that dependency: it covers the
    ~1 second the element takes to register and the model to decode, so
    the poster is never literally optional, just no longer load-bearing
    against a third-party outage.
--}}

<section class="hero-stage d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center g-4">

            {{-- Model first in DOM order so it is above the copy on mobile,
                 reordered to the right of it from lg up. --}}
            <div class="col-lg-6 order-0 order-lg-2">
                <div class="hero-model">
                    <model-viewer
                        src="{{ asset('models/hero-glasses.glb') }}"
                        alt="A pair of Northlight Optical glasses, rotating slowly"
                        camera-controls
                        auto-rotate
                        disable-zoom
                        shadow-intensity="1"
                        exposure="1"
                        environment-image="neutral"
                        loading="eager"
                        touch-action="pan-y">

                        {{-- Shown while the model loads, and left in place if
                             it fails or the component never upgrades. --}}
                        <div slot="poster" class="hero-model__fallback">
                            @include('partials.icon', ['icon' => 'aperture', 'size' => 64])
                        </div>
                    </model-viewer>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-1 hero-copy text-center text-lg-start">
                <h1 class="hero-headline mb-3">Welcome to Northlight Optical</h1>
                <p class="hero-sub mb-4">
                    Explore from a Wide Range of Stylish Sunglasses &amp; Eyeglasses.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary text-uppercase py-2 px-4">
                    Shop Products
                </a>
            </div>

        </div>
    </div>
</section>
