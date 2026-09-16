@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<x-page-header title="Surat Masuk" subtitle="Daftar surat masuk {{ auth()->user()->opd->nama ?? 'seluruh OPD' }}">
    <x-slot:actions>
        @can('create', App\Models\SuratMasuk::class)
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Catat Surat Masuk</a>
        @endcan
    </x-slot:actions>
</x-page-header>

<x-filter-bar placeholder="Perihal / asal / nomor" :reset="route('surat-masuk.index')">
    <div class="col-6 col-md-auto">
        <label for="f-dari" class="form-label">Dari</label>
        <input type="date" name="dari" id="f-dari" class="form-control form-control-sm" value="{{ request('dari') }}">
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-sampai" class="form-label">Sampai</label>
        <input type="date" name="sampai" id="f-sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-klasifikasi" class="form-label">Klasifikasi</label>
        <select name="klasifikasi_id" id="f-klasifikasi" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach($klasifikasis as $k)
                <option value="{{ $k->id }}" {{ request('klasifikasi_id') == $k->id ? 'selected' : '' }}>{{ $k->kode }} - {{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-sifat" class="form-label">Sifat</label>
        <select name="sifat" id="f-sifat" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach(['biasa','penting','rahasia'] as $s)
                <option value="{{ $s }}" {{ request('sifat') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-status" class="form-label">Status</label>
        <select name="status" id="f-status" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach(['baru','didisposisi','selesai','diarsip'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($suratMasuks->isEmpty())
    <div class="card">
        <x-empty-state icon="envelope-open" title="Belum ada surat masuk"
                       :text="request()->hasAny(['dari','sampai','klasifikasi_id','sifat','status','cari']) ? 'Tidak ada surat yang cocok dengan filter.' : 'Surat yang dicatat atau diterima dari OPD lain akan muncul di sini.'">
            <x-slot:action>
                @can('create', App\Models\SuratMasuk::class)
                    <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Catat Surat Masuk</a>
                @endcan
            </x-slot:action>
        </x-empty-state>
    </div>
@else
    <div class="card sk-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">No. Agenda</th>
                        <th scope="col">Perihal</th>
                        <th scope="col">Asal</th>
                        <th scope="col">Tgl Terima</th>
                        <th scope="col">Sifat</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratMasuks as $sm)
                        <tr>
                            <td class="sk-num">{{ $sm->nomor_agenda }}</td>
                            <td>
                                <div class="sk-clamp-2">{{ $sm->perihal }}</div>
                                <small class="text-muted">{{ $sm->nomor_surat }}</small>
                            </td>
                            <td>{{ $sm->asal_surat }}</td>
                            <td class="text-nowrap">{{ $sm->tanggal_terima->format('d-m-Y') }}</td>
                            <td><x-status-chip :sifat="$sm->sifat" /></td>
                            <td><x-status-chip :status="$sm->status" /></td>
                            <td class="text-end">
                                <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-sm btn-outline-primary text-nowrap" aria-label="Lihat detail agenda {{ $sm->nomor_agenda }}">
                                    <x-icon name="eye" /><span class="d-none d-lg-inline ms-1">Detail</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="sk-card-list">
        @foreach($suratMasuks as $sm)
            <a href="{{ route('surat-masuk.show', $sm) }}" class="sk-list-card">
                <div class="d-flex justify-content-between gap-2">
                    <span class="sk-num">{{ $sm->nomor_agenda }}</span>
                    <span class="sk-list-card-meta">{{ $sm->tanggal_terima->format('d-m-Y') }}</span>
                </div>
                <div class="sk-list-card-title sk-clamp-2">{{ $sm->perihal }}</div>
                <div class="sk-list-card-meta">{{ $sm->asal_surat }} &middot; {{ $sm->nomor_surat }}</div>
                <div class="sk-list-card-chips">
                    <x-status-chip :sifat="$sm->sifat" />
                    <x-status-chip :status="$sm->status" />
                </div>
            </a>
        @endforeach
    </div>

    <x-pagination :paginator="$suratMasuks" />
@endif
@endsection
