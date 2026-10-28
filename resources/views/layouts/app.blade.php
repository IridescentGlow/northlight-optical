<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - @yield('title')</title>

    {{-- Search and social share previews. This site is a concept project, and
         the default description says so, because the link gets shared. --}}
    <meta name="description" content="@yield('description', 'Northlight Optical is a concept eyewear store: marketing pages, scroll motion and a 3D hero built on the open-source Sunray Laravel shop.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ config('app.name') }} - @yield('title')">
    <meta property="og:description" content="@yield('description', 'Northlight Optical is a concept eyewear store: marketing pages, scroll motion and a 3D hero built on the open-source Sunray Laravel shop.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- The default share image is a 1200x630 crop (the size link previews use),
         so its size and alt text are stated. A page that supplies its own
         og_image (the product page) has different dimensions, so it gets neither. --}}
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    @sectionMissing('og_image')
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="A pair of round gold-framed sunglasses with green lenses on white marble">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    {{-- Brand gold ($brown in app.scss) for the mobile browser toolbar. --}}
    <meta name="theme-color" content="#D39D4E">

    {{-- Bootstrap --}}
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])


    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">

</head>

<body>

    {{-- Lets keyboard and screen-reader users jump past the navigation. Bootstrap's
         .visually-hidden-focusable keeps it invisible until it receives focus. --}}
    <a class="visually-hidden-focusable" href="#main">Skip to main content</a>

    @include('partials.header')
    @include('partials.alerts')


    <main id="main" style="min-height: 76vh;">
        @yield('content')
    </main>

    @include('partials.footer')

</body>

</html>