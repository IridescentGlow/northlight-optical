@extends('layouts.app')

@section('title', 'About & Team')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5 reveal">
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

    {{--
        Illustrative figures pending real business metrics — "Years Serving
        Portland" and "Patients Cared For" are placeholders (the former
        intentionally matches Dr. Okafor's stated tenure in her bio below
        for narrative consistency, not audited practice history). "Languages
        Spoken" is real: computed in AboutController from the team's actual
        language data, not a placeholder. See docs/NORTHLIGHT.md.
    --}}
    <div class="row text-center g-4 mb-5 reveal">
        <div class="col-4">
            <div class="stat-counter display-5 fw-bold text-brown mb-1" data-target="15" data-suffix="+">15+</div>
            <p class="text-muted small mb-0">Years Serving Portland</p>
        </div>
        <div class="col-4">
            <div class="stat-counter display-5 fw-bold text-brown mb-1" data-target="8000" data-suffix="+">8,000+</div>
            <p class="text-muted small mb-0">Patients Cared For</p>
        </div>
        <div class="col-4">
            <div class="stat-counter display-5 fw-bold text-brown mb-1" data-target="{{ $languageCount }}">{{ $languageCount }}</div>
            <p class="text-muted small mb-0">Languages Spoken by Our Team</p>
        </div>
    </div>

    <header class="row align-items-center mb-4 reveal">
        <div class="col-8">
            <h4 class="mb-0">Meet the Team</h4>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <a href="{{ route('contact.create') }}" class="text-muted small">Book with us</a>
        </div>
    </header>

</section>

<section class="container mb-3">

    @foreach($team as $member)

    {{-- .stagger-section supplies the gap between rows (see app.scss); the
         pb-5 keeps the hairline divider clear of the bio text above it. --}}
    <div class="row align-items-center pb-5 {{ $loop->last ? '' : 'border-bottom' }} stagger-section reveal">

        <div class="col-md-4 col-lg-3 text-center {{ $loop->even ? 'order-md-2' : '' }} mb-4 mb-md-0">
            <div class="bg-brown text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto reveal reveal-blur"
                style="width: 9rem; height: 9rem; font-size: 2.5rem; --tilt: {{ $loop->even ? '3deg' : '-3deg' }};">
                {{ $member['initials'] }}
            </div>
        </div>

        <div class="col-md-8 col-lg-9 {{ $loop->even ? 'order-md-1 text-md-end' : '' }}">

            <h3 class="mb-1">{{ $member['name'] }}</h3>
            <p class="text-brown fw-semibold mb-3">{{ $member['role'] }}</p>
            <p class="fs-5 text-muted mb-3">{{ $member['bio'] }}</p>

            <p class="small text-muted mb-0 d-flex align-items-center {{ $loop->even ? 'justify-content-md-end' : '' }}">
                @include('partials.icon', ['icon' => 'globe', 'size' => 14])
                <span class="ms-1">{{ implode(', ', $member['languages']) }}</span>
            </p>

        </div>

    </div>

    @endforeach

</section>

@endsection
