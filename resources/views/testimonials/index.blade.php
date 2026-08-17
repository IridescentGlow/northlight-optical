@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h2 class="mb-3">What Patients Say</h2>
            <p class="lead text-muted">
                We asked patients what actually made a difference for them. Here's what came back.
            </p>
        </div>
    </header>

    <div class="row">

        @foreach($testimonials as $testimonial)

        <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.testimonial-card', ['testimonial' => $testimonial])
        </div>

        @endforeach

    </div>

    <div class="row justify-content-center text-center mt-3">
        <div class="col-lg-6">
            <p class="text-muted mb-3">See what we offer for yourself.</p>
            <a href="{{ route('services') }}" class="btn btn-primary py-2 px-3">View Services</a>
        </div>
    </div>

</section>

@endsection
