@extends('layouts.app')

@section('title', 'Kelola OPD')

@section('content')
<x-page-header title="OPD" subtitle="Perangkat daerah yang terdaftar di sistem">
    <x-slot:actions>
        <a href="{{ route('opd.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Tambah OPD</a>
    </x-slot:actions>
</x-page-header>

<div class="card">
    @if($opds->isEmpty())
        <x-empty-state icon="building" title="Belum ada OPD" text="Tambahkan perangkat daerah pertama untuk mulai mencatat surat.">
            <x-slot:action><a href="{{ route('opd.create') }}" class="btn btn-primary"><x-icon name="plus-lg" /> Tambah OPD</a></x-slot:action>
        </x-empty-state>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col" class="d-none d-md-table-cell">Format Nomor</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opds as $opd)
                        <tr>
                            <td class="sk-num">{{ $opd->kode }}</td>
                            <td>{{ $opd->nama }}</td>
                            <td class="d-none d-md-table-cell"><code>{{ $opd->format_nomor }}</code></td>
                            <td>
                                @if($opd->is_aktif)
                                    <span class="sk-chip sk-chip-success"><x-icon name="check-circle" /> Aktif</span>
                                @else
                                    <span class="sk-chip sk-chip-neutral"><x-icon name="slash-circle" /> Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('opd.edit', $opd) }}" class="btn btn-sm btn-outline-primary text-nowrap" aria-label="Ubah {{ $opd->nama }}"><x-icon name="pencil" /><span class="d-none d-lg-inline ms-1">Ubah</span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-pagination :paginator="$opds" />
@endsection
