{{--
    Account dropdown contents, shared by the desktop nav and the compact
    mobile bar so a signed-in visitor gets the same destinations at every
    breakpoint (they previously differed: the desktop menu had "My Account"
    but no orders link, the mobile one had neither).

    Renders <li> elements only — the caller owns the <ul class="dropdown-menu">.
--}}

@auth
{{--
    The visitor's own name is a label, not a destination. It used to be
    <a href="#"> here, which put a focusable link that goes nowhere at the
    top of the keyboard tab order for every signed-in user.
--}}
<li><h6 class="dropdown-header text-truncate">{{ str()->limit(auth()->user()->fullName, 25) }}</h6></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="{{ route('account.show') }}">My Account</a></li>
<li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
<li>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="dropdown-item">Logout</button>
    </form>
</li>
@endauth

@guest
<li><a class="dropdown-item" href="{{ route('login.create') }}">Login</a></li>
<li><a class="dropdown-item" href="{{ route('register.create') }}">Register</a></li>
@endguest
