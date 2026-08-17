<header class="sticky-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg container">
        <div class="container-fluid px-3 px-md-2">

            <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-primary" aria-controls="navbar-primary" aria-expanded="false"
                aria-label="Toggle navigation">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" 
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            </button>
                

            <a class="navbar-brand p-0 d-flex align-items-center ms-4 ms-lg-0" href="{{ route('home') }}">

                <img src="{{ asset('images/logo.png') }}" alt="Northlight Optical" style="width:38px">

                <span class="fw-bold fs-4 ms-2" style="letter-spacing: 0.10rem;">
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

                    <li class="nav-item me-lg-2 d-none d-lg-inline-block">
                        <a href="{{ route('products.index') }}" class="mt-3 mt-lg-1 d-inline-block text-muted">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                            class="text-brown"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </a>
                    </li>


                    <li class="nav-item dropdown me-lg-2 d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle pt-1" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            class="text-brown"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            
                            @auth
                                <li><a class="dropdown-item" href="#">{{ str()->limit(auth()->user()->fullName, 25) }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.show') }}">My Account</a></li>
                                <li>
                                    <form class="dropdown-item" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item p-0">Logout</button>
                                </form>
                            </li>
                            @endauth

                            @guest
                                <li><a class="dropdown-item" href="{{ route('register.create') }}">Regsiter</a></li>
                                <li><a class="dropdown-item" href="{{ route('login.create') }}">Login</a></li>
                            @endguest
                        </ul>
                    </li>


                    <li class="nav-item d-none d-lg-inline-block">
                        <a href="{{ route('cart.index') }}" class="mt-3 mt-lg-1 d-inline-block text-muted position-relative">

                            <span
                                class="position-absolute start-100 translate-middle badge rounded-pill bg-brown text-white fw-semibold"
                                style="top: 4px;">
                                
                                {{ (new App\Services\CartService())->getCartItemsCount() }}

                            </span>

                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                            class="text-brown"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6">
                                </line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>