@php
    $canDispose = false;
    $user = auth()->user();

    if ($user->hasRole('admin_tu') && $suratMasuk->status === 'baru') {
        $canDispose = true;
    }

    if ($user->hasAnyRole(['pimpinan', 'staf'])) {
        $hasActiveDisposisi = $suratMasuk->disposisis
            ->where('kepada_user_id', $user->id)
            ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
            ->isNotEmpty();
        if ($hasActiveDisposisi) {
            $canDispose = true;
        }
    }
@endphp

@if($canDispose && Route::has('disposisi.store'))
    <div class="card mb-3">
        <div class="card-header">Disposisikan</div>
        <div class="card-body">
            <form action="{{ route('disposisi.store', $suratMasuk) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kepada_user_id" class="form-label">Kepada</label>
                    <select name="kepada_user_id" id="kepada_user_id" class="form-select @error('kepada_user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach($usersSeOpd as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNames()->first() }})</option>
                        @endforeach
                    </select>
                    @error('kepada_user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="instruksi" class="form-label">Instruksi</label>
                    <textarea name="instruksi" id="instruksi" class="form-control @error('instruksi') is-invalid @enderror" rows="3" required>{{ old('instruksi') }}</textarea>
                    @error('instruksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="batas_waktu" class="form-label">Batas Waktu</label>
                    <input type="date" name="batas_waktu" id="batas_waktu" class="form-control @error('batas_waktu') is-invalid @enderror" value="{{ old('batas_waktu') }}">
                    @error('batas_waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary w-100">Kirim Disposisi</button>
            </form>
        </div>
    </div>
@endif

@php
    $myActiveDisposisi = $suratMasuk->disposisis
        ->where('kepada_user_id', auth()->id())
        ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
        ->first();
@endphp

@if($myActiveDisposisi && Route::has('tindak-lanjut.store'))
    <div class="card">
        <div class="card-header">Tindak Lanjut</div>
        <div class="card-body">
            <form action="{{ route('tindak-lanjut.store', $myActiveDisposisi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" required>{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="tl_lampirans" class="form-label">Lampiran</label>
                    <input type="file" name="lampirans[]" id="tl_lampirans" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="selesai" id="selesai" value="1">
                        <label class="form-check-label" for="selesai">Tandai disposisi selesai</label>
                    </div>
                </div>
                <button class="btn btn-success w-100">Simpan Tindak Lanjut</button>
            </form>
        </div>
    </div>
@endif
