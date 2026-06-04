@extends('layouts.app')

@section('title', 'Catat Surat Masuk')

@section('content')
<h4 class="mb-3">Catat Surat Masuk</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nomor_surat" class="form-label">Nomor Surat</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat') }}" required>
                    @error('nomor_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="asal_surat" class="form-label">Asal Surat</label>
                    <input type="text" name="asal_surat" id="asal_surat" class="form-control @error('asal_surat') is-invalid @enderror" value="{{ old('asal_surat') }}" required>
                    @error('asal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="klasifikasi_id" class="form-label">Klasifikasi</label>
                    <select name="klasifikasi_id" id="klasifikasi_id" class="form-select @error('klasifikasi_id') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach($klasifikasis as $k)
                            <option value="{{ $k->id }}" {{ old('klasifikasi_id') == $k->id ? 'selected' : '' }}>{{ $k->kode }} - {{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat') }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                    <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', date('Y-m-d')) }}" required>
                    @error('tanggal_terima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal</label>
                <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" required>
                @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="sifat" class="form-label">Sifat</label>
                    <select name="sifat" id="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa','penting','rahasia'] as $s)
                            <option value="{{ $s }}" {{ old('sifat', 'biasa') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-3">
                    <label for="lampirans" class="form-label">Lampiran (PDF/JPG/PNG, maks 10 MB)</label>
                    <input type="file" name="lampirans[]" id="lampirans" class="form-control @error('lampirans.*') is-invalid @enderror" multiple accept=".pdf,.jpg,.jpeg,.png">
                    @error('lampirans.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
