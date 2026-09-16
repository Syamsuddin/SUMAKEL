@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h2>Masuk</h2>
<p class="text-muted mb-4">Gunakan akun yang diberikan oleh admin OPD Anda.</p>

@if(session('status'))
    <div class="alert alert-success" role="status">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" data-loading novalidate>
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
               value="{{ old('email') }}" required autocomplete="email" autofocus inputmode="email"
               @error('email') aria-describedby="email-error" @enderror>
        @error('email')
            <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group has-validation">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                   required autocomplete="current-password" @error('password') aria-describedby="password-error" @enderror>
            <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="password"
                    aria-label="Tampilkan kata sandi" aria-pressed="false">
                <x-icon name="eye" />
            </button>
            @error('password')
                <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        @if(Route::has('password.request'))
            <a class="small" href="{{ route('password.request') }}">Lupa kata sandi?</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <x-icon name="box-arrow-in-right" /> Masuk
    </button>
</form>
@endsection
