@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Surat Masuk</h4>
    @can('create', App\Models\SuratMasuk::class)
        <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">Catat Surat Masuk</a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Klasifikasi</label>
                <select name="klasifikasi_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($klasifikasis as $k)
                        <option value="{{ $k->id }}" {{ request('klasifikasi_id') == $k->id ? 'selected' : '' }}>{{ $k->kode }} - {{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small">Sifat</label>
                <select name="sifat" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['biasa','penting','rahasia'] as $s)
                        <option value="{{ $s }}" {{ request('sifat') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['baru','didisposisi','selesai','diarsip'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Cari</label>
                <input type="text" name="cari" class="form-control form-control-sm" value="{{ request('cari') }}" placeholder="Perihal / asal / nomor">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Agenda</th>
                    <th>Nomor Surat</th>
                    <th>Asal</th>
                    <th>Perihal</th>
                    <th>Tanggal Terima</th>
                    <th>Sifat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuks as $sm)
                    <tr>
                        <td>{{ $sm->nomor_agenda }}</td>
                        <td>{{ $sm->nomor_surat }}</td>
                        <td>{{ $sm->asal_surat }}</td>
                        <td>{{ Str::limit($sm->perihal, 40) }}</td>
                        <td>{{ $sm->tanggal_terima->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $sm->sifat === 'rahasia' ? 'danger' : ($sm->sifat === 'penting' ? 'warning' : 'info') }}">
                                {{ ucfirst($sm->sifat) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $sm->status === 'selesai' ? 'success' : ($sm->status === 'diarsip' ? 'secondary' : 'primary') }}">
                                {{ ucfirst($sm->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada surat masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $suratMasuks->links() }}
@endsection
