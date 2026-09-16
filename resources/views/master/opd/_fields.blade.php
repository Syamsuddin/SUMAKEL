<fieldset class="sk-fieldset">
    <legend>Identitas</legend>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $opd?->kode) }}" required placeholder="mis. DISKOMINFO">
            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-8 mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $opd?->nama) }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</fieldset>
<fieldset class="sk-fieldset mb-3">
    <legend>Penomoran Surat Keluar</legend>
    <label for="format_nomor" class="form-label">Format Nomor</label>
    <input type="text" name="format_nomor" id="format_nomor" class="form-control font-monospace @error('format_nomor') is-invalid @enderror" value="{{ old('format_nomor', $opd?->format_nomor ?? '{klasifikasi}/{nomor}/{kode_opd}/{bulan_romawi}/{tahun}') }}">
    @error('format_nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
</fieldset>
