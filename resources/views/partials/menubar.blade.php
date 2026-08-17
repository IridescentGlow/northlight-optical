{{--
    Compact icon bar for small and medium screens (hidden at >=lg, where
    the same three affordances live inside the expanded nav instead).

    Each control is a .nav-icon-link: a 44px round tap target regardless of
    the icon inside it. The icons themselves are 24px, which met WCAG 2.5.8
    only by the exact minimum and left no margin for a mistimed thumb.

    Two icons, not three. The desktop nav's magnifying-glass icon is not a
    search field — it is a plain link to the products listing, which is
    already the "Products" item one tap away in the hamburger menu (and the
    hero CTA, and the footer). Carrying a third 44px target here for it is
    what pushed this bar onto a second row at 390px and buried the cart.
    Nothing is unreachable as a result; the desktop bar keeps it, where the
    horizontal room exists.
--}}

<div class="d-flex align-items-center">

    {{-- account --}}
    <div class="dropdown d-inline-block">
        <a class="nav-icon-link d-inline-flex align-items-center justify-content-center" href="#" role="button"
            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-brown"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true" focusable="false">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
            @include('partials.account-menu')
        </ul>
    </div>

    {{-- cart --}}
    @include('partials.cart-button', ['iconSize' => 24])

</div>
