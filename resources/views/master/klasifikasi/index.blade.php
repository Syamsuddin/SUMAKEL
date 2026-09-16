@extends('layouts.app')

@section('title', 'Kelola Klasifikasi')

@section('content')
<x-page-header title="Klasifikasi" subtitle="Kode klasifikasi arsip yang dipakai seluruh OPD">
    <x-slot:actions>
        <a href="{{ route('klasifikasi.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Tambah Klasifikasi</a>
    </x-slot:actions>
</x-page-header>

<div class="card">
    @if($klasifikasis->isEmpty())
        <x-empty-state icon="tags" title="Belum ada klasifikasi" text="Kode klasifikasi diperlukan saat mencatat surat.">
            <x-slot:action><a href="{{ route('klasifikasi.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Tambah Klasifikasi</a></x-slot:action>
        </x-empty-state>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 8rem">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($klasifikasis as $item)
                        <tr>
                            <td class="sk-num">{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('klasifikasi.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Ubah {{ $item->kode }}"><x-icon name="pencil" /><span class="d-none d-lg-inline ms-1">Ubah</span></a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-hapus-{{ $item->id }}" aria-label="Hapus {{ $item->kode }}"><x-icon name="trash" /><span class="d-none d-lg-inline ms-1">Hapus</span></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-pagination :paginator="$klasifikasis" />

@push('modals')
    @foreach($klasifikasis as $item)
        <x-confirm-modal id="modal-hapus-{{ $item->id }}" :action="route('klasifikasi.destroy', $item)" method="DELETE"
                         title="Hapus klasifikasi?" confirm="Ya, hapus"
                         text="Klasifikasi {{ $item->kode }} — {{ $item->nama }} akan dihapus. Surat yang sudah memakai kode ini tidak ikut terhapus." />
    @endforeach
@endpush
@endsection
