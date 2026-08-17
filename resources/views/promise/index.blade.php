@extends('layouts.app')

@section('title', 'Our Promise')

@section('content')

<section class="container my-5">

    <header class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h2 class="mb-3">Our Promise</h2>
            <p class="lead text-muted">
                Good eye care shouldn't depend on how well you match the "default" patient. Here's
                what we do about that.
            </p>
        </div>
    </header>

    <div class="row">

        @foreach($promise as $feature)

        <div class="col-md-6 col-lg-4 mb-4">
            <article class="card border-0 h-100">
                <div class="card-body px-0">

                    <div class="bg-brown text-white rounded-circle d-flex align-items-center justify-content-center mb-3"
                        style="width: 3rem; height: 3rem;">
                        @include('partials.icon', ['icon' => $feature['icon'], 'size' => 22])
                    </div>

                    <h5 class="card-title">{{ $feature['title'] }}</h5>
                    <p class="card-text text-muted">{{ $feature['description'] }}</p>

                </div>
            </article>
        </div>

        @endforeach

    </div>

</section>

@endsection
