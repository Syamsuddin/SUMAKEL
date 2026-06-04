@extends('layouts.app')

@section('title', 'Kelola Klasifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Klasifikasi</h4>
    <a href="{{ route('klasifikasi.create') }}" class="btn btn-primary">Tambah Klasifikasi</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($klasifikasis as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            <a href="{{ route('klasifikasi.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('klasifikasi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus klasifikasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada data klasifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $klasifikasis->links() }}
@endsection
