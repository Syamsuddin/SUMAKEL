@extends('layouts.app')

@section('title', 'Ubah Klasifikasi')

@section('content')
<x-page-header title="Ubah Klasifikasi" subtitle="{{ $klasifikasi->kode }} — {{ $klasifikasi->nama }}" />

<x-form-card :action="route('klasifikasi.update', $klasifikasi)" method="PUT" :cancel="route('klasifikasi.index')" submit="Simpan Perubahan">
    @include('master.klasifikasi._fields', ['klasifikasi' => $klasifikasi])
</x-form-card>
@endsection
