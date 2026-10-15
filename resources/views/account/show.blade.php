@extends('layouts.app')

@section('title', 'My Account')

@section('content')

<div class="container mt-5 pb-5">

    <div class="row">
        <div class="col-lg-3">

            @include('partials.sidebar')

        </div>



        <div class="col-lg-9">

            <h1 class="h4 mb-3">My Account</h1>

            <div class="row">
                <div class="col-md-6">

                    <article class="d-flex align-items-center border p-3 rounded text-muted mb-3 mb-md-0">
                        
                        <a href="{{ route('update.account.create') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                class="text-brown mb-2" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>

                        <p class="ms-2 mb-0">{{ Str::limit(auth()->user()->fullName, 25) }} | {{ Str::limit(auth()->user()->email, 25) }}</p>
                    </article>

                </div>

                <div class="col-md-6">

                    <article class="d-flex align-items-center border p-3 rounded text-muted">

                        <a href="{{ route('orders.index') }}" class="d-inline-block text-muted position-relative">

                            <span
                                class="position-absolute start-100 translate-middle badge rounded-pill bg-brown text-white fw-semibold"
                                style="top: 4px;">
                                {{ $orders_count }}
                            </span>

                            <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="2"
                                class="text-brown mb-2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6">
                                </line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </a>

                        <p class="ms-3 mb-0">Total Orders</p>
                    </article>

                </div>


            </div>


        </div>
    </div>
</div>
@endsection