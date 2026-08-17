@extends('layouts.app')

@section('title', 'Services')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5 reveal">
        <div class="col-lg-8">
            <h2 class="mb-3">Services</h2>
            <p class="lead text-muted">
                From your first comprehensive exam to your hundredth frame adjustment, our team
                handles it in-house.
            </p>
        </div>
    </header>

    <div class="col-lg-10 mx-auto">

        @foreach($services as $service)

        <div class="service-row d-flex align-items-start py-4 {{ $loop->last ? '' : 'border-bottom' }} reveal">

            <div class="flex-shrink-0 bg-brown text-white rounded-circle d-flex align-items-center justify-content-center me-4"
                style="width: 3.5rem; height: 3.5rem;">
                @include('partials.icon', ['icon' => $service['icon'], 'size' => 24])
            </div>

            <div>
                <h5 class="mb-2">
                    <span class="text-brown small fw-semibold me-2">{{ sprintf('%02d', $loop->iteration) }}</span>
                    {{ $service['title'] }}
                </h5>
                <p class="text-muted mb-0">{{ $service['description'] }}</p>
            </div>

        </div>

        @endforeach

    </div>

    <div class="row justify-content-center text-center mt-5 reveal">
        <div class="col-lg-6">
            <p class="text-muted mb-3">Ready to come in? We'll find a time that works.</p>
            <a href="{{ route('contact.create') }}" class="btn btn-primary py-2 px-3">Book an Appointment</a>
        </div>
    </div>

</section>

@endsection
