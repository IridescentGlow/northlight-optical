<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ config('app.name') }} - Something went wrong</title>

    {{-- Standalone on purpose: a 500 can come from the database or the session,
         so this page uses neither, only the compiled stylesheet and a static image. --}}
    @vite(['resources/scss/app.scss'])
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>

<body>
    <main class="container text-center py-5" style="min-height: 76vh;">
        <p class="display-1 fw-bold text-primary mb-0">500</p>
        <h1 class="h3 mb-3">Something went wrong on our side</h1>
        <p class="text-muted mb-4">
            The page could not be loaded. Wait a few seconds and try again; this free demo can take a moment to wake up after a quiet spell.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ url()->current() }}" class="btn btn-primary px-4">Try again</a>
            <a href="{{ url('/') }}" class="btn btn-outline-primary px-4">Home</a>
        </div>
        <p class="small text-muted mt-5">Northlight Optical is a concept project.</p>
    </main>
</body>

</html>
