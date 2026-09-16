@extends('layouts.guest')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
<h2>Atur Ulang Kata Sandi</h2>
<p class="text-muted mb-4">Buat kata sandi baru untuk akun Anda.</p>

<form method="POST" action="{{ route('password.update') }}" data-loading novalidate>
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
               value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus inputmode="email"
               @error('email') aria-describedby="email-error" @enderror>
        @error('email')
            <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi Baru</label>
        <div class="input-group has-validation">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                   required autocomplete="new-password" @error('password') aria-describedby="password-error" @enderror>
            <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="password"
                    aria-label="Tampilkan kata sandi" aria-pressed="false">
                <x-icon name="eye" />
            </button>
            @error('password')
                <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-4">
        <label for="password-confirm" class="form-label">Ulangi Kata Sandi Baru</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <x-icon name="key" /> Simpan Kata Sandi
    </button>
</form>
@endsection
