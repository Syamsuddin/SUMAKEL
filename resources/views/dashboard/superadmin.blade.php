@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<x-page-header title="Dashboard Pemda" subtitle="Rekapitulasi seluruh OPD aktif · {{ config('app.pemda') }}" />

<div class="sk-stat-grid">
    <x-stat-card icon="building" label="OPD Aktif" :value="$totalOpd" tone="primary" :href="route('opd.index')" />
    <x-stat-card icon="people" label="Pengguna" :value="$totalUser" tone="info" :href="route('user.index')" />
    <x-stat-card icon="envelope-arrow-down" label="Total Surat Masuk" :value="$totalSm" tone="success" />
    <x-stat-card icon="envelope-arrow-up" label="Total Surat Keluar" :value="$totalSk" tone="neutral" />
</div>

<div class="card">
    <div class="card-header d-flex align-items-center gap-2"><x-icon name="bar-chart" /> Rekap Per OPD</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col">OPD</th>
                    <th scope="col" class="text-end">Pengguna</th>
                    <th scope="col" class="text-end">Surat Masuk</th>
                    <th scope="col" class="text-end">Surat Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapOpd as $opd)
                    <tr>
                        <td><strong>{{ $opd->nama }}</strong> <small class="text-muted">({{ $opd->kode }})</small></td>
                        <td class="text-end sk-num">{{ $opd->users_count }}</td>
                        <td class="text-end sk-num">{{ $opd->sm_count }}</td>
                        <td class="text-end sk-num">{{ $opd->sk_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('dashboard._chart')
@endsection
