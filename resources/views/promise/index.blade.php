@extends('layouts.app')

@section('title', 'Our Promise')

@section('content')

<div class="container my-5">
    <header class="row justify-content-center text-center mb-5 reveal">
        <div class="col-lg-8">
            <h2 class="mb-3">Our Promise</h2>
            <p class="lead text-muted">
                Good eye care shouldn't depend on how well you match the "default" patient. Here's
                what we do about that.
            </p>
        </div>
    </header>

    <div class="row justify-content-center text-center mb-3 reveal">
        <div class="col-lg-7">
            <h3 class="mb-2">See the Difference</h3>
            <p class="text-muted">
                Drag the divider (or use your arrow keys) — this is what the right prescription
                actually changes.
            </p>
        </div>
    </div>

    <div class="row justify-content-center mb-5 reveal">
        <div class="col-lg-9">
            <div class="compare-slider" role="slider" tabindex="0" aria-label="Comparison: without correction versus with Northlight glasses"
                aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">

                <div class="compare-slider__layer compare-slider__before bg-light">
                    <div>
                        <p class="display-3 mb-2">NORTH</p>
                        <p class="display-5 mb-2">LIGHT</p>
                        <p class="fs-3 mb-2">OPTICAL</p>
                        <p class="fs-5 mb-0">SEE CLEARLY</p>
                    </div>
                    <span class="compare-slider__label compare-slider__label--before">Without Correction</span>
                </div>

                <div class="compare-slider__layer compare-slider__after bg-white">
                    <div>
                        <p class="display-3 mb-2">NORTH</p>
                        <p class="display-5 mb-2">LIGHT</p>
                        <p class="fs-3 mb-2">OPTICAL</p>
                        <p class="fs-5 mb-0">SEE CLEARLY</p>
                    </div>
                    <span class="compare-slider__label compare-slider__label--after">With Northlight</span>
                </div>

                <div class="compare-slider__handle"></div>

            </div>
        </div>
    </div>
</div>

@foreach($promise as $feature)

<section class="stagger-section reveal">
    <div class="row g-0 align-items-stretch flex-md-row{{ $loop->even ? '-reverse' : '' }}">

        <div class="col-md-5 icon-panel text-white d-flex align-items-center justify-content-center py-5"
            style="min-height: 220px;">
            <div class="reveal reveal-blur">
                @include('partials.icon', ['icon' => $feature['icon'], 'size' => 64])
            </div>
        </div>

        <div class="col-md-7 d-flex align-items-center">
            <div class="p-4 p-lg-5">
                <h4 class="mb-3">{{ $feature['title'] }}</h4>
                <p class="text-muted fs-5 mb-0">{{ $feature['description'] }}</p>
            </div>
        </div>

    </div>
</section>

@endforeach

<div class="container">
    <div class="row justify-content-center text-center my-5 reveal">
        <div class="col-lg-6">
            <p class="text-muted mb-3">Questions about how we can accommodate your visit?</p>
            <a href="{{ route('contact.create') }}" class="btn btn-primary py-2 px-3">Contact Us</a>
        </div>
    </div>
</div>

@endsection
