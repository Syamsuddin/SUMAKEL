@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<x-page-header title="Surat Keluar" subtitle="Daftar surat keluar {{ auth()->user()->opd->nama ?? 'seluruh OPD' }}">
    <x-slot:actions>
        @can('create', App\Models\SuratKeluar::class)
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Buat Surat Keluar</a>
        @endcan
    </x-slot:actions>
</x-page-header>

<x-filter-bar placeholder="Perihal / nomor" :reset="route('surat-keluar.index')">
    <div class="col-6 col-md-auto">
        <label for="f-dari" class="form-label">Dari</label>
        <input type="date" name="dari" id="f-dari" class="form-control form-control-sm" value="{{ request('dari') }}">
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-sampai" class="form-label">Sampai</label>
        <input type="date" name="sampai" id="f-sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
    </div>
    <div class="col-6 col-md-auto">
        <label for="f-status" class="form-label">Status</label>
        <select name="status" id="f-status" class="form-select form-select-sm">
            <option value="">Semua</option>
            @foreach(['draft','terbit','diarsip'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($suratKeluars->isEmpty())
    <div class="card">
        <x-empty-state icon="envelope-arrow-up" title="Belum ada surat keluar"
                       :text="request()->hasAny(['dari','sampai','status','cari']) ? 'Tidak ada surat yang cocok dengan filter.' : 'Buat draft surat keluar, lalu terbitkan untuk mendapatkan nomor resmi.'">
            <x-slot:action>
                @can('create', App\Models\SuratKeluar::class)
                    <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Buat Surat Keluar</a>
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
                        <th scope="col">Nomor</th>
                        <th scope="col">Perihal</th>
                        <th scope="col">Tujuan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Sifat</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratKeluars as $sk)
                        <tr>
                            <td class="sk-num">
                                @if($sk->nomor)
                                    {{ $sk->nomor }}
                                @else
                                    <span class="text-muted fw-normal fst-italic">Belum bernomor</span>
                                @endif
                            </td>
                            <td><div class="sk-clamp-2">{{ $sk->perihal }}</div></td>
                            <td>
                                @if($sk->jenis_tujuan === 'internal')
                                    <span class="sk-chip sk-chip-info"><x-icon name="building" /> {{ $sk->tujuanOpd?->nama }}</span>
                                @else
                                    {{ $sk->tujuan_eksternal }}
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $sk->tanggal_surat->format('d-m-Y') }}</td>
                            <td><x-status-chip :sifat="$sk->sifat" /></td>
                            <td><x-status-chip :status="$sk->status" /></td>
                            <td class="text-end">
                                <a href="{{ route('surat-keluar.show', $sk) }}" class="btn btn-sm btn-outline-primary text-nowrap" aria-label="Lihat detail surat {{ $sk->nomor ?? 'draft '.$sk->id }}">
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
        @foreach($suratKeluars as $sk)
            <a href="{{ route('surat-keluar.show', $sk) }}" class="sk-list-card">
                <div class="d-flex justify-content-between gap-2">
                    <span class="sk-num">{{ $sk->nomor ?? 'Belum bernomor' }}</span>
                    <span class="sk-list-card-meta">{{ $sk->tanggal_surat->format('d-m-Y') }}</span>
                </div>
                <div class="sk-list-card-title sk-clamp-2">{{ $sk->perihal }}</div>
                <div class="sk-list-card-meta">
                    <x-icon :name="$sk->jenis_tujuan === 'internal' ? 'building' : 'globe2'" />
                    {{ $sk->jenis_tujuan === 'internal' ? $sk->tujuanOpd?->nama : $sk->tujuan_eksternal }}
                </div>
                <div class="sk-list-card-chips">
                    <x-status-chip :sifat="$sk->sifat" />
                    <x-status-chip :status="$sk->status" />
                </div>
            </a>
        @endforeach
    </div>

    <x-pagination :paginator="$suratKeluars" />
@endif
@endsection
