<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0F172A">
    <title>@yield('title', 'Dashboard') &mdash; {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('app.logo')) }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <a href="#konten-utama" class="sk-skip-link">Lewati ke konten utama</a>

    <div id="app" class="sk-app">
        @auth
            @include('layouts.sidebar')
        @endauth

        <div class="sk-main">
            @include('layouts._topbar')

            <main id="konten-utama" class="sk-content" tabindex="-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="status">
                        <x-icon name="check-circle-fill" />
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                        <x-icon name="exclamation-octagon-fill" />
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
