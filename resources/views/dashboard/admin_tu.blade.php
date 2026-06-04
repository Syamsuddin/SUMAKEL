@extends('layouts.app')

@section('title', 'Dashboard Admin TU')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title">{{ $smBulanIni }}</h5>
                <p class="card-text">Surat Masuk Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title">{{ $skBulanIni }}</h5>
                <p class="card-text">Surat Keluar Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5 class="card-title">{{ $smBelumDisposisi }}</h5>
                <p class="card-text">SM Belum Disposisi</p>
            </div>
        </div>
    </div>
</div>

@include('dashboard._chart')
@endsection
