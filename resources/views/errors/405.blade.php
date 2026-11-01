<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ config('app.name') }} - Page not available</title>

    {{-- Standalone, like 404.blade.php: a request that matched an address but not
         its method skips the web middleware group. --}}
    @vite(['resources/scss/app.scss'])
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>

<body>
    <main class="container text-center py-5" style="min-height: 76vh;">
        <p class="display-1 fw-bold text-primary mb-0">405</p>
        <h1 class="h3 mb-3">That address does not work this way</h1>
        <p class="text-muted mb-4">
            This link is for a button or a form, not for opening in the address bar.
            Here are a few places to go next.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ url('/') }}" class="btn btn-primary px-4">Home</a>
            <a href="{{ url('/products') }}" class="btn btn-outline-primary px-4">Products</a>
            <a href="{{ url('/cart') }}" class="btn btn-outline-primary px-4">Cart</a>
        </div>
        <p class="small text-muted mt-5">Northlight Optical is a concept project.</p>
    </main>
</body>

</html>
