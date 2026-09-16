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

    $myActiveDisposisi = $suratMasuk->disposisis
        ->where('kepada_user_id', $user->id)
        ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
        ->first();
@endphp

@if($myActiveDisposisi && Route::has('tindak-lanjut.store'))
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-2"><x-icon name="check2-square" /> Catat Tindak Lanjut</div>
        <div class="card-body">
            <p class="small text-muted mb-3">Instruksi dari <strong>{{ $myActiveDisposisi->dariUser->name }}</strong>: {{ $myActiveDisposisi->instruksi }}</p>
            <form action="{{ route('tindak-lanjut.store', $myActiveDisposisi) }}" method="POST" enctype="multipart/form-data" data-loading>
                @csrf
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" required>{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="tl_lampirans" class="form-label">Lampiran <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="file" name="lampirans[]" id="tl_lampirans" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="selesai" id="selesai" value="1">
                    <label class="form-check-label" for="selesai">Tandai disposisi selesai</label>
                </div>
                <button type="submit" class="btn btn-success w-100"><x-icon name="check-lg" /> Simpan Tindak Lanjut</button>
            </form>
        </div>
    </div>
@endif

@if($canDispose && Route::has('disposisi.store'))
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2"><x-icon name="diagram-3" /> {{ $myActiveDisposisi ? 'Teruskan Disposisi' : 'Disposisikan' }}</div>
        <div class="card-body">
            <form action="{{ route('disposisi.store', $suratMasuk) }}" method="POST" data-loading>
                @csrf
                <div class="mb-3">
                    <label for="kepada_user_id" class="form-label">Kepada</label>
                    <select name="kepada_user_id" id="kepada_user_id" class="form-select @error('kepada_user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach($usersSeOpd as $u)
                            <option value="{{ $u->id }}" {{ old('kepada_user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ str_replace('_', ' ', $u->getRoleNames()->first()) }})</option>
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
                    <label for="batas_waktu" class="form-label">Batas Waktu <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="date" name="batas_waktu" id="batas_waktu" class="form-control @error('batas_waktu') is-invalid @enderror" value="{{ old('batas_waktu') }}">
                    @error('batas_waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100"><x-icon name="send" /> Kirim Disposisi</button>
            </form>
        </div>
    </div>
@endif

@if(! $canDispose && ! $myActiveDisposisi)
    <div class="card">
        <div class="card-body text-muted d-flex gap-2 align-items-start">
            <x-icon name="info-circle" class="sk-icon-md" />
            <span>Tidak ada aksi yang tersedia untuk Anda pada surat ini.</span>
        </div>
    </div>
@endif
