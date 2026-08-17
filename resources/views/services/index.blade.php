@extends('layouts.app')

@section('title', 'Services')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h2 class="mb-3">Services</h2>
            <p class="lead text-muted">
                From your first comprehensive exam to your hundredth frame adjustment, our team
                handles it in-house.
            </p>
        </div>
    </header>

    <div class="row">

        @foreach($services as $service)

        <div class="col-md-6 col-lg-4 mb-4">
            <article class="card border-0 h-100">
                <div class="card-body px-0">

                    <div class="bg-brown text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                        style="width: 3rem; height: 3rem;">
                        @include('partials.icon', ['icon' => $service['icon'], 'size' => 22])
                    </div>

                    <h5 class="card-title">{{ $service['title'] }}</h5>
                    <p class="card-text text-muted">{{ $service['description'] }}</p>

                </div>
            </article>
        </div>

        @endforeach

    </div>

    <div class="row justify-content-center text-center mt-3">
        <div class="col-lg-6">
            <p class="text-muted mb-3">Ready to come in? We'll find a time that works.</p>
            <a href="{{ route('contact.create') }}" class="btn btn-primary py-2 px-3">Book an Appointment</a>
        </div>
    </div>

</section>

@endsection
