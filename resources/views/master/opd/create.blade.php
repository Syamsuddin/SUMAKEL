@extends('layouts.app')

@section('title', 'Tambah OPD')

@section('content')
<x-page-header title="Tambah OPD" />

<x-form-card :action="route('opd.store')" :cancel="route('opd.index')">
    @include('master.opd._fields', ['opd' => null])
    <x-slot:aside>
        @include('master.opd._panduan_format')
    </x-slot:aside>
</x-form-card>
@endsection
