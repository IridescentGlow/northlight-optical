@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5 reveal">
        <div class="col-lg-8">
            <h2 class="mb-3">What Patients Say</h2>
            <p class="lead text-muted">
                We asked patients what actually made a difference for them. Here's what came back.
            </p>
        </div>
    </header>

    {{-- Featured quote --}}
    <div class="row justify-content-center text-center mb-5 pb-4 reveal">
        <div class="col-lg-8">
            <p class="display-4 text-brown mb-0" style="line-height: 1;">&ldquo;</p>
            <p class="fs-3 mb-4" style="line-height: 1.4;">{{ $featured['quote'] }}</p>
            <p class="fw-semibold fs-5 mb-0">{{ $featured['name'] }}</p>
            <p class="text-muted">{{ $featured['detail'] }}</p>
        </div>
    </div>

    {{-- Supporting quotes --}}
    <div class="row">

        @foreach($supporting as $testimonial)

        <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.testimonial-card', ['testimonial' => $testimonial])
        </div>

        @endforeach

    </div>

    <div class="row justify-content-center text-center mt-3 reveal">
        <div class="col-lg-6">
            <p class="text-muted mb-3">See what we offer for yourself.</p>
            <a href="{{ route('services') }}" class="btn btn-primary py-2 px-3">View Services</a>
        </div>
    </div>

</section>

@endsection
