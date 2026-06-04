@extends('layouts.app')

@section('title', 'Edit OPD')

@section('content')
<h4 class="mb-3">Edit OPD: {{ $opd->nama }}</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('opd.update', $opd) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="kode" class="form-label">Kode</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $opd->kode) }}" required>
                @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $opd->nama) }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="format_nomor" class="form-label">Format Nomor</label>
                <input type="text" name="format_nomor" id="format_nomor" class="form-control @error('format_nomor') is-invalid @enderror" value="{{ old('format_nomor', $opd->format_nomor) }}">
                <div class="form-text">Token: {klasifikasi}, {nomor}, {kode_opd}, {bulan_romawi}, {tahun}</div>
                @error('format_nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $opd->is_aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif">Aktif</label>
                </div>
            </div>
            <a href="{{ route('opd.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
