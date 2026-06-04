@extends('layouts.app')

@section('title', 'Buat Surat Keluar')

@section('content')
<h4 class="mb-3">Buat Surat Keluar</h4>

<div class="card">
    <div class="card-body">
        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="sifat" class="form-label">Sifat</label>
                    <select name="sifat" id="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa','penting','rahasia'] as $s)
                            <option value="{{ $s }}" {{ old('sifat', 'biasa') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal</label>
                <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" required>
                @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="jenis_tujuan" class="form-label">Jenis Tujuan</label>
                    <select name="jenis_tujuan" id="jenis_tujuan" class="form-select @error('jenis_tujuan') is-invalid @enderror" required onchange="toggleTujuan(this.value)">
                        <option value="eksternal" {{ old('jenis_tujuan', 'eksternal') === 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        <option value="internal" {{ old('jenis_tujuan') === 'internal' ? 'selected' : '' }}>Internal (Antar-OPD)</option>
                    </select>
                    @error('jenis_tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-3" id="tujuan-eksternal-group">
                    <label for="tujuan_eksternal" class="form-label">Tujuan Eksternal</label>
                    <input type="text" name="tujuan_eksternal" id="tujuan_eksternal" class="form-control @error('tujuan_eksternal') is-invalid @enderror" value="{{ old('tujuan_eksternal') }}">
                    @error('tujuan_eksternal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-3" id="tujuan-internal-group" style="display:none">
                    <label for="tujuan_opd_id" class="form-label">Tujuan OPD</label>
                    <select name="tujuan_opd_id" id="tujuan_opd_id" class="form-select @error('tujuan_opd_id') is-invalid @enderror">
                        <option value="">-- Pilih OPD --</option>
                        @foreach($opds as $opd)
                            <option value="{{ $opd->id }}" {{ old('tujuan_opd_id') == $opd->id ? 'selected' : '' }}>{{ $opd->nama }}</option>
                        @endforeach
                    </select>
                    @error('tujuan_opd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="lampirans" class="form-label">Lampiran PDF</label>
                <input type="file" name="lampirans[]" id="lampirans" class="form-control @error('lampirans.*') is-invalid @enderror" multiple accept=".pdf">
                @error('lampirans.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Draft</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTujuan(val) {
    document.getElementById('tujuan-eksternal-group').style.display = val === 'eksternal' ? '' : 'none';
    document.getElementById('tujuan-internal-group').style.display = val === 'internal' ? '' : 'none';
}
toggleTujuan(document.getElementById('jenis_tujuan').value);
</script>
@endpush
