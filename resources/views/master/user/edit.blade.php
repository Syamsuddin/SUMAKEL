@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
<x-page-header title="Ubah Pengguna" :subtitle="$user->name" />

<x-form-card :action="route('user.update', $user)" method="PUT" :cancel="route('user.index')" submit="Simpan Perubahan">
    @include('master.user._fields', ['user' => $user])
</x-form-card>
@endsection
