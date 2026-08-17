{{-- .nav-over-hero only on Home, which is the only route with a hero.
     It makes the bar transparent until .nav-scrolled fires (site.js, at
     24px of scroll). Safe without a light/dark variant because the new
     hero ground is white, so the dark wordmark and brown icons keep the
     same contrast they have against the normal white bar. --}}
<header class="sticky-top bg-white shadow-sm {{ request()->routeIs('home') ? 'nav-over-hero' : '' }}">
    <nav class="navbar navbar-expand-lg container">
        <div class="container-fluid px-3 px-md-2">

            <button class="navbar-toggler nav-icon-link border-0 p-0"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-primary" aria-controls="navbar-primary" aria-expanded="false"
                aria-label="Toggle navigation">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            </button>


            {{--
                me-auto pins the icon bar to the right edge. Without it the
                brand's old ms-4 pushed the whole row past 390px and the
                icon bar — cart included — wrapped onto a second line under
                the hamburger, which is the single biggest reason the cart
                read as "not there" on a phone.
            --}}
            <a class="navbar-brand p-0 d-flex align-items-center ms-2 ms-lg-0 me-auto" href="{{ route('home') }}">

                <img src="{{ asset('images/logo.png') }}" alt="Northlight Optical" style="width:38px">

                <span class="brand-wordmark fw-bold ms-2" style="letter-spacing: 0.10rem;">
                    <span class="text-brown">NORTH</span>LIGHT
                </span>

            </a>

            <div class="d-flex align-items-center d-lg-none">

                @include('partials.menubar')

            </div>

            <div class="collapse navbar-collapse" id="navbar-primary">
                <ul class="navbar-nav ms-lg-auto text-center">

                    <li class="nav-item me-lg-2">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="nav-item me-lg-2">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>

                    <li class="nav-item dropdown me-lg-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu border-0 text-center text-lg-start md:shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}?category=eyeglasses">Eye Glasses</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index') }}?category=sunglasses">Sun Glasses</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown me-lg-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu border-0 text-center text-lg-start md:shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('about') }}">Our Team</a></li>
                            <li><a class="dropdown-item" href="{{ route('services') }}">Services</a></li>
                            <li><a class="dropdown-item" href="{{ route('promise') }}">Our Promise</a></li>
                            <li><a class="dropdown-item" href="{{ route('testimonials') }}">Testimonials</a></li>
                            <li><a class="dropdown-item" href="{{ route('contact.create') }}">Contact &amp; Booking</a></li>
                        </ul>
                    </li>

                    {{--
                        Icon cluster — desktop only (d-none d-lg-inline-block).
                        Below lg these same three affordances are already on
                        screen via partials/menubar, so dropping the display
                        classes here would render the cart twice inside the
                        expanded menu rather than "fixing" mobile access.
                    --}}
                    <li class="nav-item d-none d-lg-inline-block">
                        <a href="{{ route('products.index') }}"
                            class="nav-icon-link"
                            aria-label="Browse all products">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                            class="text-brown"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true" focusable="false">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </a>
                    </li>


                    <li class="nav-item dropdown d-none d-lg-inline-block">
                        <a class="nav-icon-link dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Account menu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            class="text-brown"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            @include('partials.account-menu')
                        </ul>
                    </li>


                    <li class="nav-item me-lg-2 d-none d-lg-inline-block">
                        @include('partials.cart-button', ['iconSize' => 20])
                    </li>


                    {{--
                        Primary CTA. Booking an exam is the action this
                        business actually wants from a visitor, and until now
                        it was reachable only two levels deep (About dropdown
                        -> Contact & Booking) — and on mobile only after
                        opening the hamburger and scrolling a submenu. As a
                        real button it is one tap from every page at every
                        breakpoint.
                    --}}
                    <li class="nav-item mt-3 mt-lg-0 d-grid d-lg-block">
                        <a href="{{ route('contact.create') }}" class="btn btn-primary px-3">Book an Exam</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>