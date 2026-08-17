/**
 * Motion layer for Northlight Optical: scroll-triggered reveals and a
 * sticky-nav "solidify" transition. Progressive enhancement only — if this
 * never runs (JS disabled/error) or the visitor prefers reduced motion,
 * every .reveal element is already fully visible via the base CSS rule in
 * app.scss (.reveal only gets the hidden .reveal-pending state below).
 */

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        const revealEls = document.querySelectorAll('.reveal');

        revealEls.forEach((el) => el.classList.add('reveal-pending'));

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
        );

        revealEls.forEach((el) => observer.observe(el));
    }

    const header = document.querySelector('header.sticky-top');

    if (header) {
        const toggleScrolled = () => {
            header.classList.toggle('nav-scrolled', window.scrollY > 24);
        };

        toggleScrolled();
        window.addEventListener('scroll', toggleScrolled, { passive: true });
    }
});
