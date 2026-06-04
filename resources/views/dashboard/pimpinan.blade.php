@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="card">
    <div class="card-header">Disposisi Menunggu ({{ $disposisiMenunggu->count() }})</div>
    <div class="list-group list-group-flush">
        @forelse($disposisiMenunggu as $d)
            <a href="{{ route('surat-masuk.show', $d->surat_masuk_id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $d->suratMasuk->perihal ?? '-' }}</strong>
                        <p class="mb-0 small text-muted">{{ $d->instruksi }}</p>
                    </div>
                    <span class="badge bg-{{ $d->status === 'terkirim' ? 'danger' : 'primary' }}">{{ ucfirst($d->status) }}</span>
                </div>
            </a>
        @empty
            <div class="list-group-item text-center text-muted py-4">Tidak ada disposisi menunggu.</div>
        @endforelse
    </div>
</div>
@endsection
