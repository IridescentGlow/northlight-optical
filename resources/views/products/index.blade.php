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
                
                    {{-- role="status" so the result is announced after a search or filter. --}}
                    <div class="col-12" role="status">
                        <p class="text-muted mb-2">
                            @if(is_string(request('search')) && request('search') !== '')
                            No products match &ldquo;{{ request('search') }}&rdquo;.
                            @else
                            No products match these filters.
                            @endif
                        </p>
                        <a href="{{ route('products.index') }}" class="small">Reset filters and see all products</a>
                    </div>
                    
                @endif

            </div>


        </div>

    </div>

</section>

@endsection