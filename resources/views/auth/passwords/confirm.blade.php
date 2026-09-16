@extends('layouts.guest')

@section('title', 'Konfirmasi Kata Sandi')

@section('content')
<h2>Konfirmasi Kata Sandi</h2>
<p class="text-muted mb-4">Demi keamanan, masukkan kembali kata sandi Anda sebelum melanjutkan.</p>

<form method="POST" action="{{ route('password.confirm') }}" data-loading novalidate>
    @csrf

    <div class="mb-4">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group has-validation">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                   required autocomplete="current-password" autofocus @error('password') aria-describedby="password-error" @enderror>
            <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="password"
                    aria-label="Tampilkan kata sandi" aria-pressed="false">
                <x-icon name="eye" />
            </button>
            @error('password')
                <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Konfirmasi</button>

    @if(Route::has('password.request'))
        <a class="d-block text-center small py-2" href="{{ route('password.request') }}">Lupa kata sandi?</a>
    @endif
</form>
@endsection
