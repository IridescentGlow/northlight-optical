<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ config('app.name') }} - Page expired</title>

    {{-- Standalone, like 404.blade.php: the session that just expired is the
         reason this page is showing, so it must not depend on it. --}}
    @vite(['resources/scss/app.scss'])
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>

<body>
    <main class="container text-center py-5" style="min-height: 76vh;">
        <p class="display-1 fw-bold text-primary mb-0">419</p>
        <h1 class="h3 mb-3">This page has expired</h1>
        <p class="text-muted mb-4">
            For your security a form can only be sent for a limited time, and this one waited too long.
            This free demo also restarts after a quiet spell, which ends sessions. Go back, reload the page and send it again.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-primary px-4">Go back</a>
            <a href="{{ url('/') }}" class="btn btn-outline-primary px-4">Home</a>
        </div>
        <p class="small text-muted mt-5">Northlight Optical is a concept project.</p>
    </main>
</body>

</html>
