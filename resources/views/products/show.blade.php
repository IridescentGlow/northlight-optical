@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="container mt-5">
    <div class="row">

        <div class="col-md-6">

            <div class="position-relative">

                <div class="overflow-hidden rounded mb-4 mb-lg-5">
                    <img src="{{ asset('storage/'. $product->image_url) }}" class="img-fluid lens-zoom reveal reveal-blur"
                        alt="{{ $product->name }}">
                </div>

                @if($product->on_sale == true)

                <span class="sale-badge position-absolute top-0 end-0 badge 
                    rounded-pill fw-semibold py-2 rounded-circle 
                    d-flex justify-content-center align-items-center">SALE</span>

                @endif
           </div>

        </div>

        <div class="col-md-6 ps-md-4">

            <h3 class="mb-2">{{ $product->name }}</h3>

            <p class="text-muted mb-2">{{ $product->category }}</p>

            <p>{{ $product->short_description }}</p>

            <p>
                @if($product['on_sale'] == true)
                <del class="text-danger">${{ number_format($product->price + $product->price * 0.25, 2) }}</del>
                @endif

                <span class="fw-bold fs-5">${{ $product->price }}</span>
            </p>


            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <select class="form-select" name="quantity" required>
                       <option value="" disabled>Choose...</option>
                       @foreach (range(1, 5) as $item)
                           <option value="{{ $item }}">{{ $item }}</option>
                       @endforeach
                    </select>

                    @error('quantity')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror

                </div>

                <input type="hidden" name="product_slug" value="{{ $product->slug }}">


                <button class="d-flex align-items-center btn btn-primary py-2 mb-5">

                    <svg class="me-2" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6">
                        </line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>

                    Add to Cart

                </button>
            </form>

        </div>
    </div>

    <h5>Description</h5>
    <hr>
    <p>{{ $product->long_description }}</p>
</div>


@endsection