@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Pengguna</h4>
    <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah Pengguna</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    @role('superadmin')
                        <th>OPD</th>
                    @endrole
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->getRoleNames()->first() }}</td>
                        @role('superadmin')
                            <td>{{ $u->opd?->nama ?? '-' }}</td>
                        @endrole
                        <td>
                            <span class="badge bg-{{ $u->is_aktif ? 'success' : 'secondary' }}">
                                {{ $u->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('user.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $users->links() }}
@endsection
