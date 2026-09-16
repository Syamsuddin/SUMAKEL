@extends('layouts.app')

@section('title', 'Dashboard Admin TU')

@section('content')
<x-page-header title="Dashboard" subtitle="Ringkasan persuratan {{ auth()->user()->opd->nama ?? '' }} · {{ now()->translatedFormat('F Y') }}" />

<div class="sk-stat-grid">
    <x-stat-card icon="envelope-arrow-down" label="Surat Masuk Bulan Ini" :value="$smBulanIni" tone="primary" :href="route('surat-masuk.index', ['dari' => now()->startOfMonth()->toDateString(), 'sampai' => now()->endOfMonth()->toDateString()])" />
    <x-stat-card icon="envelope-arrow-up" label="Surat Keluar Bulan Ini" :value="$skBulanIni" tone="info" :href="route('surat-keluar.index', ['dari' => now()->startOfMonth()->toDateString(), 'sampai' => now()->endOfMonth()->toDateString()])" />
    <x-stat-card icon="inbox" label="Belum Didisposisi" :value="$smBelumDisposisi" tone="warning" :href="route('surat-masuk.index', ['status' => 'baru'])" hint="Perlu tindakan" />
    <x-stat-card icon="alarm" label="Disposisi Lewat Tenggat" :value="$disposisiLewatTenggat" :tone="$disposisiLewatTenggat > 0 ? 'danger' : 'success'" />
</div>

<div class="row g-4">
    <div class="col-xl-7">
        @include('dashboard._chart')
    </div>
    <div class="col-xl-5">
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="d-flex align-items-center gap-2"><x-icon name="clock-history" /> 5 Surat Masuk Terbaru</span>
                <a href="{{ route('surat-masuk.index') }}" class="small text-decoration-none">Lihat semua</a>
            </div>
            @if($smTerbaru->isEmpty())
                <x-empty-state icon="envelope-open" title="Belum ada surat masuk" class="py-4">
                    <x-slot:action>
                        <a href="{{ route('surat-masuk.create') }}" class="btn btn-sm btn-primary"><x-icon name="plus-lg" /> Catat Surat Masuk</a>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="list-group list-group-flush">
                    @foreach($smTerbaru as $sm)
                        <a href="{{ route('surat-masuk.show', $sm) }}" class="list-group-item list-group-item-action py-2">
                            <div class="d-flex justify-content-between gap-2">
                                <span class="sk-num">{{ $sm->nomor_agenda }}</span>
                                <small class="text-muted">{{ $sm->tanggal_terima->format('d-m-Y') }}</small>
                            </div>
                            <div class="sk-clamp-2">{{ $sm->perihal }}</div>
                            <div class="d-flex gap-1 mt-1">
                                <x-status-chip :sifat="$sm->sifat" />
                                <x-status-chip :status="$sm->status" />
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
