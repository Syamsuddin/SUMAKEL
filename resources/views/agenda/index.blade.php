@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
<x-page-header title="Buku Agenda" subtitle="Rekap surat masuk dan keluar per periode untuk dicetak">
    <x-slot:actions>
        @if($dari && $sampai)
            <a href="{{ route('agenda.cetak', request()->only(['dari', 'sampai', 'jenis', 'klasifikasi_id'])) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
                <x-icon name="printer" /> Cetak PDF
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="sk-filter-bar">
    <form method="GET" class="row g-2 align-items-end" data-loading>
        <div class="col-6 col-md-auto">
            <label for="f-dari" class="form-label">Dari <span class="text-danger" aria-hidden="true">*</span></label>
            <input type="date" name="dari" id="f-dari" class="form-control form-control-sm" value="{{ $dari }}" required>
        </div>
        <div class="col-6 col-md-auto">
            <label for="f-sampai" class="form-label">Sampai <span class="text-danger" aria-hidden="true">*</span></label>
            <input type="date" name="sampai" id="f-sampai" class="form-control form-control-sm" value="{{ $sampai }}" required>
        </div>
        <div class="col-6 col-md-auto">
            <label for="f-jenis" class="form-label">Jenis</label>
            <select name="jenis" id="f-jenis" class="form-select form-select-sm">
                <option value="">Masuk &amp; Keluar</option>
                <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
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
        <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm"><x-icon name="journal-text" /> Tampilkan</button>
        </div>
    </form>
</div>

@if($items->isNotEmpty())
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span>Periode {{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }}</span>
            <span class="badge text-bg-secondary">{{ $items->count() }} surat</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="text-end">No</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Nomor</th>
                        <th scope="col">Perihal</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Asal/Tujuan</th>
                        <th scope="col">Klas.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                        <tr>
                            <td class="text-end sk-num">{{ $i + 1 }}</td>
                            <td>
                                <span class="sk-chip {{ $item['jenis'] === 'Masuk' ? 'sk-chip-info' : 'sk-chip-success' }}">
                                    <x-icon :name="$item['jenis'] === 'Masuk' ? 'envelope-arrow-down' : 'envelope-arrow-up'" /> {{ $item['jenis'] }}
                                </span>
                            </td>
                            <td class="sk-num">{{ $item['nomor'] ?? '-' }}</td>
                            <td><div class="sk-clamp-2">{{ $item['perihal'] }}</div></td>
                            <td class="text-nowrap">{{ $item['tanggal'] instanceof \Carbon\Carbon ? $item['tanggal']->format('d-m-Y') : $item['tanggal'] }}</td>
                            <td>{{ $item['pihak'] }}</td>
                            <td>{{ $item['klasifikasi'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@elseif($dari && $sampai)
    <div class="card">
        <x-empty-state icon="journal-x" title="Tidak ada surat pada periode ini" text="Coba perlebar rentang tanggal atau ubah filter jenis/klasifikasi." />
    </div>
@else
    <div class="card">
        <x-empty-state icon="calendar-range" title="Pilih periode agenda" text="Tentukan tanggal awal dan akhir, lalu tekan Tampilkan untuk melihat buku agenda." />
    </div>
@endif
@endsection
