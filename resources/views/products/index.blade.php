@extends('layouts.app')

@section('title', 'Products')

@section('description', 'Browse sunglasses and eyeglasses in the Northlight Optical demo shop, a concept store built on the open-source Sunray shop.')

@section('content')

<section class="container my-5">

    <header class="row align-items-center mb-4">
        <div class="col-6">

            <h4 class="mb-0">All Products</h4>
        </div>
        <div class="col-6 d-flex justify-content-end">
            <a href="{{ route('products.index') }}" class="text-muted small">&times; Reset Filters</a>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-4">
            @include('partials.filters')
        </div>

        <div class="col-lg-8">

            <div class="row">

                @if ($products->count() > 0)
                    
                    @foreach($products as $product)

                    <div class="col-md-6 mb-4">
                        @include('partials.card')
                    </div>

                    @endforeach
                    
                            
                    <div class="text-end">
                        {{ $products->links() }}
                    </div>

                @else
                
                    <p class="text-muted">No Products Found.</p>
                    
                @endif

            </div>


        </div>

</section>

@endsection