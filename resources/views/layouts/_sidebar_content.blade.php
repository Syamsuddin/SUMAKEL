<div class="p-3">
    <span class="fs-5 fw-semibold">e-Surat</span>
</div>
<hr class="text-secondary my-0">
<ul class="nav nav-pills flex-column p-2">
    <li class="nav-item">
        <a class="nav-link text-white {{ ($currentRoute ?? '') === 'dashboard' ? 'active' : '' }}"
           href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </li>

    @hasanyrole('admin_tu|pimpinan|staf')
        @if(Route::has('surat-masuk.index'))
            <li class="nav-item mt-2">
                <small class="text-secondary px-3 text-uppercase">Persuratan</small>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'surat-masuk') ? 'active' : '' }}"
                   href="{{ route('surat-masuk.index') }}">
                    Surat Masuk
                </a>
            </li>
        @endif
        @if(Route::has('surat-keluar.index'))
            <li class="nav-item">
                <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'surat-keluar') ? 'active' : '' }}"
                   href="{{ route('surat-keluar.index') }}">
                    Surat Keluar
                </a>
            </li>
        @endif
        @if(Route::has('agenda.index'))
            <li class="nav-item">
                <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'agenda') ? 'active' : '' }}"
                   href="{{ route('agenda.index') }}">
                    Agenda
                </a>
            </li>
        @endif
    @endhasanyrole

    @hasanyrole('superadmin|admin_tu')
        <li class="nav-item mt-2">
            <small class="text-secondary px-3 text-uppercase">Master Data</small>
        </li>
        @role('superadmin')
            @if(Route::has('opd.index'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'opd') ? 'active' : '' }}"
                       href="{{ route('opd.index') }}">
                        OPD
                    </a>
                </li>
            @endif
            @if(Route::has('klasifikasi.index'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'klasifikasi') ? 'active' : '' }}"
                       href="{{ route('klasifikasi.index') }}">
                        Klasifikasi
                    </a>
                </li>
            @endif
        @endrole
        @if(Route::has('user.index'))
            <li class="nav-item">
                <a class="nav-link text-white {{ str_starts_with($currentRoute ?? '', 'user') ? 'active' : '' }}"
                   href="{{ route('user.index') }}">
                    Pengguna
                </a>
            </li>
        @endif
    @endhasanyrole
</ul>
