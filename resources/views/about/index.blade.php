@extends('layouts.app')

@section('title', 'About & Team')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h2 class="mb-3">About {{ $business['name'] }}</h2>
            <p class="lead text-muted">
                We opened our doors in Portland with one goal: make good eye care feel like it was
                built for you, not just tolerable for you. That means fitting stations at wheelchair
                height, staff who can talk to your family in the language you're most comfortable in,
                and enough time in every appointment to actually answer your questions.
            </p>
        </div>
    </header>

    <header class="row align-items-center mb-4">
        <div class="col-8">
            <h4 class="mb-0">Meet the Team</h4>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <a href="{{ route('contact.create') }}" class="text-muted small">Book with us</a>
        </div>
    </header>

    <div class="row">

        @foreach($team as $member)

        <div class="col-md-6 col-lg-4 mb-4">
            @include('partials.team-member', ['member' => $member])
        </div>

        @endforeach

    </div>

</section>

@endsection
