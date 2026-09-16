@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi')

@section('content')
<h2>Lupa Kata Sandi</h2>
<p class="text-muted mb-4">Masukkan email Anda; tautan untuk mengatur ulang kata sandi akan dikirim.</p>

@if(session('status'))
    <div class="alert alert-success" role="status">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}" data-loading novalidate>
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label">Alamat Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
               value="{{ old('email') }}" required autocomplete="email" autofocus inputmode="email"
               @error('email') aria-describedby="email-error" @enderror>
        @error('email')
            <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <x-icon name="send" /> Kirim Tautan Reset
    </button>

    <a href="{{ route('login') }}" class="d-block text-center small py-2"><x-icon name="arrow-left" /> Kembali ke halaman masuk</a>
</form>
@endsection
