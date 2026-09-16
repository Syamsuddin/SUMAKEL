@extends('layouts.app')

@section('title', 'Detail Surat Keluar')

@php
    $isInternal = $suratKeluar->jenis_tujuan === 'internal';
    $isPublished = in_array($suratKeluar->status, ['terbit', 'diarsip']);
    $isRouted = $isPublished && $suratKeluar->suratMasukTujuan;
@endphp

@section('content')
<x-page-header title="Surat Keluar {{ $suratKeluar->nomor ?? '(Draft)' }}" :subtitle="Str::limit($suratKeluar->perihal, 90)">
    <x-slot:actions>
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-link text-decoration-none"><x-icon name="arrow-left" /> Kembali</a>
        @can('update', $suratKeluar)
            <a href="{{ route('surat-keluar.edit', $suratKeluar) }}" class="btn btn-outline-primary"><x-icon name="pencil" /> Ubah</a>
        @endcan
        @can('arsipkan', $suratKeluar)
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-arsipkan"><x-icon name="archive" /> Arsipkan</button>
        @endcan
        @can('terbitkan', $suratKeluar)
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-terbitkan"><x-icon name="patch-check" /> Terbitkan</button>
        @endcan
    </x-slot:actions>
</x-page-header>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body pb-2">
                <ol class="sk-stepper" aria-label="Tahapan surat keluar">
                    <li class="sk-step {{ $isPublished ? 'is-done' : 'is-current' }}">
                        <span class="sk-step-dot"><x-icon :name="$isPublished ? 'check-lg' : 'pencil'" /></span>
                        <span class="sk-step-label">Draft</span>
                        <span class="sk-step-sub">{{ $suratKeluar->created_at->format('d-m-Y') }}</span>
                    </li>
                    <li class="sk-step {{ $isPublished ? ($isInternal ? 'is-done' : 'is-current') : '' }}">
                        <span class="sk-step-dot">@if($isPublished)<x-icon name="check-lg" />@else 2 @endif</span>
                        <span class="sk-step-label">Terbit</span>
                        <span class="sk-step-sub">{{ $isPublished ? 'Nomor diterbitkan' : 'Menunggu' }}</span>
                    </li>
                    @if($isInternal)
                        <li class="sk-step {{ $isRouted ? 'is-current' : '' }}">
                            <span class="sk-step-dot">@if($isRouted)<x-icon name="check-lg" />@else 3 @endif</span>
                            <span class="sk-step-label">Terkirim ke OPD</span>
                            <span class="sk-step-sub">{{ $isRouted ? 'Agenda #'.$suratKeluar->suratMasukTujuan->nomor_agenda : ($suratKeluar->tujuanOpd?->nama ?? '') }}</span>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        <x-doc-header label="Nomor Surat" :nomor="$suratKeluar->nomor" placeholder="Nomor diterbitkan saat surat terbit" :perihal="$suratKeluar->perihal">
            <x-slot:chips>
                <x-status-chip :sifat="$suratKeluar->sifat" />
                <x-status-chip :status="$suratKeluar->status" />
            </x-slot:chips>
            <x-slot:meta>
                <div><dt>Klasifikasi</dt><dd>{{ $suratKeluar->klasifikasi->kode }} &mdash; {{ $suratKeluar->klasifikasi->nama }}</dd></div>
                <div><dt>Tanggal Surat</dt><dd>{{ $suratKeluar->tanggal_surat->format('d-m-Y') }}</dd></div>
                <div>
                    <dt>Tujuan</dt>
                    <dd>
                        @if($isInternal)
                            <span class="sk-chip sk-chip-info"><x-icon name="building" /> {{ $suratKeluar->tujuanOpd?->nama }}</span>
                        @else
                            {{ $suratKeluar->tujuan_eksternal }}
                        @endif
                    </dd>
                </div>
                <div><dt>Jenis Tujuan</dt><dd><x-status-chip :status="$suratKeluar->jenis_tujuan" /></dd></div>
            </x-slot:meta>
        </x-doc-header>

        @if($isRouted)
            <div class="alert alert-info d-flex align-items-center gap-2">
                <x-icon name="envelope-arrow-down" class="sk-icon-md" />
                <div>Diterima sebagai agenda <strong>#{{ $suratKeluar->suratMasukTujuan->nomor_agenda }}</strong> di {{ $suratKeluar->tujuanOpd?->nama }}.</div>
            </div>
        @endif

        <x-attachment-list :lampirans="$suratKeluar->lampirans" title="Lampiran PDF" />
    </div>

    <div class="col-lg-4">
        <div class="sk-side-panel">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2"><x-icon name="info-circle" /> Keterangan</div>
                <div class="card-body small">
                    @if($suratKeluar->status === 'draft')
                        <p class="mb-2">Surat masih berupa <strong>draft</strong>. Nomor resmi akan dibuat otomatis mengikuti format OPD saat tombol <em>Terbitkan</em> ditekan.</p>
                        @if($isInternal)
                            <p class="mb-2">Karena tujuannya antar-OPD, saat terbit surat ini otomatis tercatat sebagai surat masuk di <strong>{{ $suratKeluar->tujuanOpd?->nama }}</strong>.</p>
                        @endif
                        @if($suratKeluar->lampirans->isEmpty())
                            <div class="alert alert-warning py-2 px-3 mb-0 d-flex gap-2 align-items-start">
                                <x-icon name="exclamation-triangle" />
                                <span>Unggah <strong>PDF surat final</strong> lewat tombol <em>Ubah</em> sebelum surat dapat diterbitkan.</span>
                            </div>
                        @endif
                    @elseif($suratKeluar->status === 'terbit')
                        <p class="mb-0">Surat sudah <strong>terbit</strong> dan nomornya bersifat tetap. Arsipkan bila surat tidak lagi memerlukan tindak lanjut.</p>
                    @else
                        <p class="mb-0">Surat sudah <strong>diarsipkan</strong>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
    @can('terbitkan', $suratKeluar)
        <x-confirm-modal id="modal-terbitkan" :action="route('surat-keluar.terbitkan', $suratKeluar)"
                         title="Terbitkan surat keluar?" tone="success" confirm="Ya, terbitkan"
                         text="Nomor surat akan digenerate sesuai format OPD dan tidak dapat diubah setelah terbit.{{ $isInternal ? ' Surat juga akan dikirim ke '.$suratKeluar->tujuanOpd?->nama.' sebagai surat masuk.' : '' }}" />
    @endcan
    @can('arsipkan', $suratKeluar)
        <x-confirm-modal id="modal-arsipkan" :action="route('surat-keluar.arsipkan', $suratKeluar)"
                         title="Arsipkan surat keluar?" tone="primary" confirm="Ya, arsipkan"
                         text="Surat akan dipindahkan ke arsip." />
    @endcan
@endpush
@endsection
