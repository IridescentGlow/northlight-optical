<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ config('app.name') }} - Too many attempts</title>

    {{-- Standalone, like 404.blade.php and 419.blade.php, so it does not depend on
         the app layout or the session. --}}
    @vite(['resources/scss/app.scss'])
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>

<body>
    <main class="container text-center py-5" style="min-height: 76vh;">
        <p class="display-1 fw-bold text-primary mb-0">429</p>
        <h1 class="h3 mb-3">Too many attempts</h1>
        <p class="text-muted mb-4">
            That was more tries than we allow in one minute, to keep accounts safe from password guessing.
            Wait a minute, then go back and try again.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-primary px-4">Go back</a>
            <a href="{{ url('/') }}" class="btn btn-outline-primary px-4">Home</a>
        </div>
        <p class="small text-muted mt-5">Northlight Optical is a concept project.</p>
    </main>
</body>

</html>
