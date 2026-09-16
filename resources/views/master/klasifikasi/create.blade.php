@extends('layouts.app')

@section('title', 'Tambah Klasifikasi')

@section('content')
<x-page-header title="Tambah Klasifikasi" />

<x-form-card :action="route('klasifikasi.store')" :cancel="route('klasifikasi.index')">
    @include('master.klasifikasi._fields', ['klasifikasi' => null])
</x-form-card>
@endsection
