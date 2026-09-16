@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<x-page-header title="Surat Masuk #{{ $suratMasuk->nomor_agenda }}" :subtitle="Str::limit($suratMasuk->perihal, 90)">
    <x-slot:actions>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-link text-decoration-none"><x-icon name="arrow-left" /> Kembali</a>
        @can('update', $suratMasuk)
            <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="btn btn-outline-primary"><x-icon name="pencil" /> Ubah</a>
        @endcan
        @can('arsipkan', $suratMasuk)
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-arsipkan">
                <x-icon name="archive" /> Arsipkan
            </button>
        @endcan
    </x-slot:actions>
</x-page-header>

<div class="row g-4">
    <div class="col-lg-8">
        <x-doc-header label="Nomor Agenda" :nomor="$suratMasuk->nomor_agenda" :perihal="$suratMasuk->perihal">
            <x-slot:chips>
                <x-status-chip :sifat="$suratMasuk->sifat" />
                <x-status-chip :status="$suratMasuk->status" />
            </x-slot:chips>
            <x-slot:meta>
                <div><dt>Nomor Surat</dt><dd>{{ $suratMasuk->nomor_surat }}</dd></div>
                <div><dt>Asal Surat</dt><dd>{{ $suratMasuk->asal_surat }}</dd></div>
                <div><dt>Klasifikasi</dt><dd>{{ $suratMasuk->klasifikasi->kode }} &mdash; {{ $suratMasuk->klasifikasi->nama }}</dd></div>
                <div><dt>Tanggal Surat</dt><dd>{{ $suratMasuk->tanggal_surat->format('d-m-Y') }}</dd></div>
                <div><dt>Tanggal Terima</dt><dd>{{ $suratMasuk->tanggal_terima->format('d-m-Y') }}</dd></div>
                <div><dt>Dicatat</dt><dd>{{ $suratMasuk->created_at->format('d-m-Y H:i') }}</dd></div>
            </x-slot:meta>
        </x-doc-header>

        <x-attachment-list :lampirans="$suratMasuk->lampirans" />

        @include('surat-masuk._timeline', ['suratMasuk' => $suratMasuk])
    </div>

    <div class="col-lg-4">
        <div class="sk-side-panel">
            @if($suratMasuk->status !== 'diarsip')
                @include('surat-masuk._form_disposisi', ['suratMasuk' => $suratMasuk, 'usersSeOpd' => $usersSeOpd])
            @else
                <div class="card">
                    <div class="card-body text-muted d-flex gap-2 align-items-start">
                        <x-icon name="archive" class="sk-icon-md" />
                        <span>Surat ini sudah diarsipkan. Disposisi dan tindak lanjut tidak dapat ditambahkan.</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@can('arsipkan', $suratMasuk)
    @push('modals')
        <x-confirm-modal id="modal-arsipkan" :action="route('surat-masuk.arsipkan', $suratMasuk)"
                         title="Arsipkan surat masuk?" tone="primary" confirm="Ya, arsipkan"
                         text="Surat #{{ $suratMasuk->nomor_agenda }} akan dipindahkan ke arsip. Disposisi dan tindak lanjut baru tidak dapat ditambahkan setelah diarsipkan." />
    @endpush
@endcan
@endsection
