@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<x-page-header title="Tambah Pengguna" />

<x-form-card :action="route('user.store')" :cancel="route('user.index')">
    @include('master.user._fields', ['user' => null])
</x-form-card>
@endsection
