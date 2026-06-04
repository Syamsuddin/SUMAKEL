@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<h4 class="mb-3">Dashboard Superadmin</h4>

<div class="card">
    <div class="card-header">Rekap Per OPD</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>OPD</th>
                    <th>Pengguna</th>
                    <th>Surat Masuk</th>
                    <th>Surat Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapOpd as $opd)
                    <tr>
                        <td>{{ $opd->nama }}</td>
                        <td>{{ $opd->users_count }}</td>
                        <td>{{ $opd->sm_count }}</td>
                        <td>{{ $opd->sk_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('dashboard._chart')
@endsection
