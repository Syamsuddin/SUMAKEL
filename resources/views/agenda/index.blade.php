@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
<h4 class="mb-3">Buku Agenda</h4>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Dari <span class="text-danger">*</span></label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ $dari }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai <span class="text-danger">*</span></label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ $sampai }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Klasifikasi</label>
                <select name="klasifikasi_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($klasifikasis as $k)
                        <option value="{{ $k->id }}" {{ request('klasifikasi_id') == $k->id ? 'selected' : '' }}>{{ $k->kode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-sm btn-outline-primary">Filter</button>
                @if($dari && $sampai)
                    <a href="{{ route('agenda.cetak', request()->only(['dari', 'sampai', 'jenis', 'klasifikasi_id'])) }}" class="btn btn-sm btn-outline-danger" target="_blank">Cetak PDF</a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($items->isNotEmpty())
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Jenis</th>
                        <th>Nomor</th>
                        <th>Perihal</th>
                        <th>Tanggal</th>
                        <th>Asal/Tujuan</th>
                        <th>Klasifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge bg-{{ $item['jenis'] === 'Masuk' ? 'primary' : 'success' }}">{{ $item['jenis'] }}</span></td>
                            <td>{{ $item['nomor'] }}</td>
                            <td>{{ $item['perihal'] }}</td>
                            <td>{{ $item['tanggal'] instanceof \Carbon\Carbon ? $item['tanggal']->format('d-m-Y') : $item['tanggal'] }}</td>
                            <td>{{ $item['pihak'] }}</td>
                            <td>{{ $item['klasifikasi'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@elseif($dari && $sampai)
    <div class="alert alert-info">Tidak ada data agenda untuk periode tersebut.</div>
@endif
@endsection
