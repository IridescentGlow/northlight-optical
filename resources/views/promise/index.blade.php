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
</div>

@foreach($promise as $feature)

<section class="reveal">
    <div class="row g-0 align-items-stretch flex-md-row{{ $loop->even ? '-reverse' : '' }}">

        <div class="col-md-5 icon-panel text-white d-flex align-items-center justify-content-center py-5"
            style="min-height: 220px;">
            @include('partials.icon', ['icon' => $feature['icon'], 'size' => 64])
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
