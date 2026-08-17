/**
 * Motion layer for Northlight Optical: scroll-triggered reveals, a
 * sticky-nav "solidify" transition, a before/after comparison slider, a
 * cursor-tracked lens zoom for product imagery, toast auto-dismiss, and
 * animated stat counters. Progressive enhancement throughout — every
 * effect here is decoration on top of content/values that are already
 * correct in the server-rendered HTML (see individual comments below for
 * how each one degrades if JS never runs or reduced motion is set).
 */

import { Alert } from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // --- <model-viewer>, dynamically imported ---
    //
    // Registered from the bundle (via @google/model-viewer, not the
    // ajax.googleapis.com CDN this replaced), but only ever fetched on a
    // page that actually contains one. A static top-level import in app.js
    // pulled the ~300KB-gzipped Three.js payload this component bundles
    // into the *shared* JS chunk every page loads — About, Cart, Login,
    // all of it — for a component only the homepage hero uses. Vite splits
    // a dynamic import() into its own chunk automatically, so this fetch
    // only happens when document.querySelector below finds an element.
    if (document.querySelector('model-viewer')) {
        // The hero's GLB is Draco-compressed (11.7MB -> 585KB with no
        // visible quality loss). model-viewer decodes Draco via a WASM
        // module that, left at its default, is fetched from
        // www.gstatic.com — reintroducing the exact external-host
        // dependency @google/model-viewer was installed to remove. These
        // three files (public/draco/, copied from
        // three/examples/jsm/libs/draco/gltf/, decoder only — the encoder
        // is not needed at runtime) make that fetch same-origin instead.
        //
        // Must be set on the *global* `window.ModelViewerElement`, not on
        // the class binding the import below resolves to — the library
        // reads `self.ModelViewerElement.dracoDecoderLocation` itself
        // (see its own bundled source), and it does so synchronously as
        // each <model-viewer> instance's constructor runs, which happens
        // during import() as custom elements upgrade — before any .then()
        // on that import could ever fire. So this line has to run first,
        // not after.
        window.ModelViewerElement = { dracoDecoderLocation: `${window.location.origin}/draco/` };
        import('@google/model-viewer');
    }

    // --- Scroll reveal (incl. .reveal-blur variant — same elements, CSS
    // in app.scss handles the extra blur/scale for that modifier) ---
    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        const revealEls = document.querySelectorAll('.reveal');

        revealEls.forEach((el) => el.classList.add('reveal-pending'));

        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
        );

        revealEls.forEach((el) => revealObserver.observe(el));
    }

    // --- Sticky nav "solidify" on scroll ---
    const header = document.querySelector('header.sticky-top');

    if (header) {
        const toggleScrolled = () => {
            header.classList.toggle('nav-scrolled', window.scrollY > 24);
        };

        toggleScrolled();
        window.addEventListener('scroll', toggleScrolled, { passive: true });
    }

    // --- Lens-style cursor zoom on product imagery ---
    // Purely a hover enhancement: with no JS, .lens-zoom images simply
    // have no hover transform (no base "flat scale" left to fall back to,
    // since that's what this replaces) — the image itself is always
    // fully visible either way.
    document.querySelectorAll('.lens-zoom').forEach((img) => {
        img.addEventListener('mousemove', (event) => {
            const rect = img.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;
            img.style.transformOrigin = `${x}% ${y}%`;
        });

        img.addEventListener('mouseleave', () => {
            img.style.transformOrigin = '50% 50%';
        });
    });

    // --- Before/after comparison slider ("See the Difference") ---
    // The slider's own position update is a direct 1:1 response to
    // pointer/keyboard input, not a decorative animation, so there is
    // nothing here for prefers-reduced-motion to disable. Both layers'
    // content is present in the DOM regardless of JS; with no JS the
    // slider simply stays at its default 50/50 split rather than being
    // draggable, and both the "with"/"without" labels are still readable.
    document.querySelectorAll('.compare-slider').forEach((slider) => {
        const afterLayer = slider.querySelector('.compare-slider__after');
        const handle = slider.querySelector('.compare-slider__handle');
        let dragging = false;

        const setPosition = (percent) => {
            const clamped = Math.min(100, Math.max(0, percent));
            afterLayer.style.clipPath = `inset(0 ${100 - clamped}% 0 0)`;
            handle.style.left = `${clamped}%`;
            slider.setAttribute('aria-valuenow', Math.round(clamped));
        };

        const percentFromEvent = (event) => {
            const rect = slider.getBoundingClientRect();
            return ((event.clientX - rect.left) / rect.width) * 100;
        };

        slider.addEventListener('pointerdown', (event) => {
            dragging = true;
            slider.setPointerCapture(event.pointerId);
            setPosition(percentFromEvent(event));
        });

        slider.addEventListener('pointermove', (event) => {
            if (dragging) {
                setPosition(percentFromEvent(event));
            }
        });

        ['pointerup', 'pointercancel'].forEach((type) => {
            slider.addEventListener(type, () => {
                dragging = false;
            });
        });

        slider.addEventListener('keydown', (event) => {
            const current = parseFloat(slider.getAttribute('aria-valuenow')) || 50;

            if (event.key === 'ArrowLeft') {
                setPosition(current - 5);
                event.preventDefault();
            } else if (event.key === 'ArrowRight') {
                setPosition(current + 5);
                event.preventDefault();
            }
        });
    });

    // --- Toast auto-dismiss ---
    // Every toast is fully readable and dismissible by hand
    // (data-bs-dismiss="alert") regardless of this timer; this just
    // clears them automatically after a delay.
    document.querySelectorAll('.toast-notification[data-auto-dismiss]').forEach((toastEl) => {
        const delay = parseInt(toastEl.dataset.autoDismiss, 10);

        if (!delay) {
            return;
        }

        setTimeout(() => {
            Alert.getOrCreateInstance(toastEl).close();
        }, delay);
    });

    // --- One-shot light sweep across the sale banner ---
    //
    // Fires once the section has fully entered the viewport — i.e. once its
    // bottom edge has scrolled above the fold — not on first partial entry.
    //
    // Not `threshold: 1.0` alone: a threshold of 1 can never be reached by an
    // element taller than the viewport, and the sweep would then silently
    // never run. The sale banner measures 300–345px against a 844px+ viewport
    // today, so this is insurance rather than a live case, but it is exactly
    // the kind of thing that breaks later when copy grows. The ratio branch
    // below covers it.
    //
    // Purely decorative: nothing is hidden pending the sweep, so with reduced
    // motion set we simply never add the class.
    if (!prefersReducedMotion) {
        const sweepTargets = document.querySelectorAll('.light-sweep');

        if (sweepTargets.length && 'IntersectionObserver' in window) {
            const sweepObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        const rect = entry.boundingClientRect;
                        const tallerThanViewport = rect.height > window.innerHeight;
                        const fullyPast = rect.bottom <= window.innerHeight;

                        if (fullyPast || (tallerThanViewport && entry.intersectionRatio >= 0.9)) {
                            entry.target.classList.add('is-swept');
                            sweepObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: [0, 0.25, 0.5, 0.75, 0.9, 1] }
            );

            sweepTargets.forEach((el) => sweepObserver.observe(el));
        }
    }

    // --- Animated stat counters (About page) ---
    // Each .stat-counter already renders its correct final value (e.g.
    // "15+") as static text in the HTML. If this never runs, that value
    // is exactly what a visitor sees — the count-up only ever runs on
    // top of an already-correct number, it never withholds it.
    const counters = document.querySelectorAll('.stat-counter');

    if (counters.length) {
        const animateCounter = (el) => {
            const target = parseInt(el.dataset.target, 10) || 0;
            const suffix = el.dataset.suffix || '';

            if (prefersReducedMotion) {
                el.textContent = target.toLocaleString() + suffix;
                return;
            }

            const duration = 1200;
            const start = performance.now();

            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target).toLocaleString() + suffix;

                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            };

            requestAnimationFrame(step);
        };

        if ('IntersectionObserver' in window) {
            const counterObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            counterObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.5 }
            );

            counters.forEach((el) => counterObserver.observe(el));
        } else {
            counters.forEach((el) => animateCounter(el));
        }
    }
});
