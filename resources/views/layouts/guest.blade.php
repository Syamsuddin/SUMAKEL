<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0F172A">
    <title>@yield('title', 'Masuk') &mdash; {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <a href="#konten-utama" class="sk-skip-link">Lewati ke konten utama</a>

    <div class="sk-guest">
        <aside class="sk-guest-panel">
            <img src="{{ asset(config('app.logo')) }}" alt="Lambang {{ config('app.pemda') }}">
            <div class="sk-guest-pemda">{{ config('app.pemda') }}</div>
            <h1>{{ config('app.name') }}</h1>
            <p class="sk-guest-tagline">{{ config('app.tagline') }}</p>
        </aside>

        <main id="konten-utama" class="sk-guest-body" tabindex="-1">
            <div class="sk-guest-card">
                @yield('content')
            </div>
            <div class="sk-guest-footer">
                SUMAKEL v{{ config('app.version') }} &middot; &copy; {{ date('Y') }} {{ config('app.pemda') }}
            </div>
        </main>
    </div>
</body>
</html>
