@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<x-page-header title="Pengguna" subtitle="Akun pengguna {{ auth()->user()->hasRole('superadmin') ? 'seluruh OPD' : (auth()->user()->opd->nama ?? '') }}">
    <x-slot:actions>
        <a href="{{ route('user.create') }}" class="btn btn-primary"><x-icon name="person-plus" /> Tambah Pengguna</a>
    </x-slot:actions>
</x-page-header>

<div class="card">
    @if($users->isEmpty())
        <x-empty-state icon="people" title="Belum ada pengguna" text="Tambahkan akun untuk admin TU, pimpinan, atau staf.">
            <x-slot:action><a href="{{ route('user.create') }}" class="btn btn-primary"><x-icon name="person-plus" /> Tambah Pengguna</a></x-slot:action>
        </x-empty-state>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col" class="d-none d-md-table-cell">Email</th>
                        <th scope="col">Peran</th>
                        @role('superadmin')
                            <th scope="col" class="d-none d-lg-table-cell">OPD</th>
                        @endrole
                        <th scope="col">Status</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="sk-avatar" style="width:32px;height:32px;font-size:.75rem" aria-hidden="true">{{ Str::of($u->name)->explode(' ')->map(fn ($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}</span>
                                    <span>
                                        <span class="d-block fw-semibold">{{ $u->name }}</span>
                                        <small class="text-muted d-md-none">{{ $u->email }}</small>
                                    </span>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $u->email }}</td>
                            <td><span class="sk-chip sk-chip-neutral text-capitalize">{{ str_replace('_', ' ', $u->getRoleNames()->first()) }}</span></td>
                            @role('superadmin')
                                <td class="d-none d-lg-table-cell">{{ $u->opd?->nama ?? '—' }}</td>
                            @endrole
                            <td>
                                @if($u->is_aktif)
                                    <span class="sk-chip sk-chip-success"><x-icon name="check-circle" /> Aktif</span>
                                @else
                                    <span class="sk-chip sk-chip-neutral"><x-icon name="slash-circle" /> Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('user.edit', $u) }}" class="btn btn-sm btn-outline-primary text-nowrap" aria-label="Ubah {{ $u->name }}"><x-icon name="pencil" /><span class="d-none d-lg-inline ms-1">Ubah</span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-pagination :paginator="$users" />
@endsection
