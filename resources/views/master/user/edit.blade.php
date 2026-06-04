@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<h4 class="mb-3">Edit Pengguna: {{ $user->name }}</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('user.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ $user->getRoleNames()->first() === $role ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                    @endforeach
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @if($opds->isNotEmpty())
                <div class="mb-3">
                    <label for="opd_id" class="form-label">OPD</label>
                    <select name="opd_id" id="opd_id" class="form-select @error('opd_id') is-invalid @enderror">
                        <option value="">-- Tanpa OPD (Superadmin) --</option>
                        @foreach($opds as $opd)
                            <option value="{{ $opd->id }}" {{ old('opd_id', $user->opd_id) == $opd->id ? 'selected' : '' }}>{{ $opd->nama }}</option>
                        @endforeach
                    </select>
                    @error('opd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @endif
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $user->is_aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif">Aktif</label>
                </div>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
