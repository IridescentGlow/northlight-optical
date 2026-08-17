<section class="py-4 mb-4" style="background: linear-gradient(42deg, rgba(0, 0, 0, 0.7), rgb(0 0 0 / 28%)), url('images/sale-banner.jpg') center / cover no-repeat">
    <div class="col-md-10 col-lg-8 mx-md-auto p-5 text-white text-center">
        {{-- h2.h1, not h1: the hero above already owns the page's only h1,
             and this banner now sits mid-page among h3 section headings.
             The .h1 class keeps the rendered size byte-identical. --}}
        <h2 class="h1 mb-3">25% OFF SALE</h2>
        <p class="lead mb-4">Enjoy 25% off on a stunning selection of sunglasses and eyeglasses.</p>
        <a href="{{ route('products.index') }}?type=sale" class="btn btn-primary">Shop Now</a>
    </div>
</section>