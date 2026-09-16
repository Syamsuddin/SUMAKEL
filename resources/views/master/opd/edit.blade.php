@extends('layouts.app')

@section('title', 'Ubah OPD')

@section('content')
<x-page-header title="Ubah OPD" :subtitle="$opd->nama" />

<x-form-card :action="route('opd.update', $opd)" method="PUT" :cancel="route('opd.index')" submit="Simpan Perubahan">
    @include('master.opd._fields', ['opd' => $opd])
    <div class="form-check form-switch">
        <input type="hidden" name="is_aktif" value="0">
        <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $opd->is_aktif) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_aktif">OPD aktif</label>
        <div class="form-text">OPD nonaktif tidak bisa dipilih sebagai tujuan surat antar-OPD.</div>
    </div>
    <x-slot:aside>
        @include('master.opd._panduan_format')
    </x-slot:aside>
</x-form-card>
@endsection
