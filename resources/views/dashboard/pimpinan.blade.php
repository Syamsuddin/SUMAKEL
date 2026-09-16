@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
<x-page-header title="Dashboard" subtitle="Disposisi yang menunggu keputusan Anda · {{ auth()->user()->opd->nama ?? '' }}" />

<div class="sk-stat-grid">
    <x-stat-card icon="send" label="Menunggu" :value="$ringkasan['menunggu']" tone="danger" />
    <x-stat-card icon="hourglass-split" label="Diproses" :value="$ringkasan['diproses']" tone="info" />
    <x-stat-card icon="patch-check" label="Selesai Bulan Ini" :value="$ringkasan['selesai']" tone="success" />
    <x-stat-card icon="alarm" label="Lewat Tenggat" :value="$ringkasan['lewat']" :tone="$ringkasan['lewat'] > 0 ? 'danger' : 'neutral'" />
</div>

<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <x-icon name="diagram-3" /> Disposisi Menunggu
        <span class="badge text-bg-secondary ms-1">{{ $disposisiMenunggu->count() }}</span>
    </div>
    @include('dashboard._tugas_list', ['tugas' => $disposisiMenunggu])
</div>
@endsection
