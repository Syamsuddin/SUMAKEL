<div class="row">
    <div class="col-md-4 mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $klasifikasi?->kode) }}" required placeholder="mis. 005">
        @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8 mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $klasifikasi?->nama) }}" required placeholder="mis. Undangan">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
