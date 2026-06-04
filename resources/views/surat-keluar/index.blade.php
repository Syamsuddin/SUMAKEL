@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Surat Keluar</h4>
    @can('create', App\Models\SuratKeluar::class)
        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">Buat Surat Keluar</a>
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
                <label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['draft','terbit','diarsip'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Cari</label>
                <input type="text" name="cari" class="form-control form-control-sm" value="{{ request('cari') }}" placeholder="Perihal / nomor">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nomor</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Sifat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluars as $sk)
                    <tr>
                        <td>{{ $sk->nomor ?? '-' }}</td>
                        <td>{{ Str::limit($sk->perihal, 40) }}</td>
                        <td>
                            @if($sk->jenis_tujuan === 'internal')
                                <span class="badge bg-info">{{ $sk->tujuanOpd?->nama }}</span>
                            @else
                                {{ $sk->tujuan_eksternal }}
                            @endif
                        </td>
                        <td>{{ $sk->tanggal_surat->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $sk->sifat === 'rahasia' ? 'danger' : ($sk->sifat === 'penting' ? 'warning' : 'info') }}">
                                {{ ucfirst($sk->sifat) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $sk->status === 'terbit' ? 'success' : ($sk->status === 'diarsip' ? 'secondary' : 'warning') }}">
                                {{ ucfirst($sk->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('surat-keluar.show', $sk) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada surat keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $suratKeluars->links() }}
@endsection
