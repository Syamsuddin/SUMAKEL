@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<x-page-header title="Profil Saya" subtitle="Perbarui nama, kanal notifikasi, dan kata sandi Anda" />

<x-form-card :action="route('profil.update')" method="PUT" submit="Simpan">
    <fieldset class="sk-fieldset">
        <legend>Identitas</legend>
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
            <div class="form-text">Email diubah oleh admin TU.</div>
        </div>
    </fieldset>

    <fieldset class="sk-fieldset">
        <legend>Notifikasi Telegram</legend>
        <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
        <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="form-control @error('telegram_chat_id') is-invalid @enderror" value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}" inputmode="numeric">
        <div class="form-text">Kirim pesan ke bot Telegram instansi, lalu salin Chat ID Anda ke sini untuk menerima notifikasi disposisi.</div>
        @error('telegram_chat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </fieldset>

    <fieldset class="sk-fieldset mb-0">
        <legend>Ganti Kata Sandi <span class="text-muted fw-normal fs-6">(opsional)</span></legend>
        <div class="mb-3">
            <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
            <div class="input-group has-validation">
                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="current_password" aria-label="Tampilkan kata sandi" aria-pressed="false"><x-icon name="eye" /></button>
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Kata Sandi Baru</label>
                <div class="input-group has-validation">
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false"><x-icon name="eye" /></button>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Ulangi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
            </div>
        </div>
    </fieldset>

    <x-slot:aside>
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="sk-avatar" style="width:48px;height:48px;font-size:1rem" aria-hidden="true">{{ Str::of($user->name)->explode(' ')->map(fn ($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}</span>
                <div class="min-w-0">
                    <div class="fw-semibold text-truncate">{{ $user->name }}</div>
                    <div class="small text-muted text-capitalize">{{ str_replace('_', ' ', $user->getRoleNames()->first()) }} &middot; {{ $user->opd->nama ?? 'Seluruh OPD' }}</div>
                </div>
            </div>
        </div>
    </x-slot:aside>
</x-form-card>
@endsection
