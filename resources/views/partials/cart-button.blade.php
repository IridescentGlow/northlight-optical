{{--
    Global cart affordance.

    Single source of truth for the cart link. Previously the same markup
    (including its own `new CartService()` call and badge rule) was
    duplicated in partials/header.blade.php and partials/menubar.blade.php,
    which is how the two copies drifted apart. Both call sites now include
    this, so the count, the badge threshold, and the accessible name can
    only ever be defined once.

    $iconSize — SVG px size. 20 in the desktop nav, 24 in the compact
    mobile bar. The 44px tap target comes from .nav-icon-link, not from
    the icon, so both sizes are equally reachable.
--}}

@php
    $cartCount = (new App\Services\CartService())->getCartItemsCount();
@endphp

<a href="{{ route('cart.index') }}"
    class="nav-icon-link position-relative text-muted"
    aria-label="{{ $cartCount > 0
        ? 'Shopping cart, ' . $cartCount . ' ' . Str::plural('item', $cartCount)
        : 'Shopping cart, empty' }}">

    {{--
        Only rendered when there is something to count. The old markup
        rendered the pill unconditionally, so every visitor on every page
        saw a "0" badge — visual noise that also destroyed the badge's one
        real job: signalling that the cart has items in it.

        aria-hidden because the count is already in the link's aria-label
        above; without this a screen reader announces the number twice.
    --}}
    @if($cartCount > 0)
    <span class="cart-badge badge rounded-pill bg-brown text-white fw-semibold position-absolute" aria-hidden="true">
        {{ $cartCount }}
    </span>
    @endif

    <svg viewBox="0 0 24 24" width="{{ $iconSize ?? 20 }}" height="{{ $iconSize ?? 20 }}" stroke="currentColor"
        stroke-width="2" class="text-brown" fill="none" stroke-linecap="round" stroke-linejoin="round"
        aria-hidden="true" focusable="false">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <path d="M16 10a4 4 0 0 1-8 0"></path>
    </svg>
</a>
