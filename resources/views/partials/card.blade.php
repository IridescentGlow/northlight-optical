<article class="card border-0 position-relative hover-lift">

    @if($product->on_sale == true)

    <span class="sale-badge position-absolute top-0 end-0 badge 
        rounded-pill fw-semibold py-2 rounded-circle 
        d-flex justify-content-center align-items-center">
        SALE
    </span>

    @endif


    <a href="{{ route('products.show', ['product' => $product]) }}" class="d-block overflow-hidden rounded">
        <img src="{{ asset('storage/'. $product->image_url) }}"
        class="card-img-top object-fit-contain lens-zoom reveal reveal-blur" alt="{{ $product->name  }}">
    </a>

    <div class="card-body px-0">

        <h5 class="card-title">{{ $product->name }}</h5>

        <p class="small text-muted mb-2">{{ $product->category }}</p>

        <p class="card-text">

            @if($product->on_sale == true)
            <del class="text-danger">${{ number_format($product->price + $product->price * 0.25, 2); }}</del>
            @endif

            <span class="fw-bold lead">${{ $product->price }}</span>
        </p>

        
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_slug" value="{{ $product->slug }}">

            <button class="d-flex align-items-center justify-content-center btn btn-primary w-100 py-2">

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
</article>