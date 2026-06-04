@extends('layouts.app')

@section('title', 'Kelola OPD')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar OPD</h4>
    <a href="{{ route('opd.create') }}" class="btn btn-primary">Tambah OPD</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Format Nomor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opds as $opd)
                    <tr>
                        <td>{{ $opd->kode }}</td>
                        <td>{{ $opd->nama }}</td>
                        <td><code>{{ $opd->format_nomor }}</code></td>
                        <td>
                            <span class="badge bg-{{ $opd->is_aktif ? 'success' : 'secondary' }}">
                                {{ $opd->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('opd.edit', $opd) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data OPD.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $opds->links() }}
@endsection
