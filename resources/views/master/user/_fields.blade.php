<fieldset class="sk-fieldset">
    <legend>Identitas</legend>
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" required inputmode="email">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi @if($user)<span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span>@endif</label>
        <div class="input-group has-validation">
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" @unless($user) required @endunless>
            <button class="btn btn-outline-secondary sk-password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false"><x-icon name="eye" /></button>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</fieldset>
<fieldset class="sk-fieldset mb-0">
    <legend>Peran &amp; Unit Kerja</legend>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="role" class="form-label">Peran</label>
            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                @unless($user)<option value="">-- Pilih Peran --</option>@endunless
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user?->getRoleNames()->first()) === $role ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @if($opds->isNotEmpty())
            <div class="col-md-6 mb-3">
                <label for="opd_id" class="form-label">OPD</label>
                <select name="opd_id" id="opd_id" class="form-select @error('opd_id') is-invalid @enderror">
                    <option value="">-- Tanpa OPD (Superadmin) --</option>
                    @foreach($opds as $opd)
                        <option value="{{ $opd->id }}" {{ old('opd_id', $user?->opd_id) == $opd->id ? 'selected' : '' }}>{{ $opd->nama }}</option>
                    @endforeach
                </select>
                @error('opd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif
    </div>
    @if($user)
        <div class="form-check form-switch">
            <input type="hidden" name="is_aktif" value="0">
            <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $user->is_aktif) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_aktif">Akun aktif</label>
            <div class="form-text">Akun nonaktif tidak bisa masuk ke sistem.</div>
        </div>
    @endif
</fieldset>
