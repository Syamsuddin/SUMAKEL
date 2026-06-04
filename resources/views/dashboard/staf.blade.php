@extends('layouts.app')

@section('title', 'Dashboard Staf')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="card">
    <div class="card-header">Tugas Disposisi ({{ $tugasDisposisi->count() }})</div>
    <div class="list-group list-group-flush">
        @forelse($tugasDisposisi as $d)
            <a href="{{ route('surat-masuk.show', $d->surat_masuk_id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $d->suratMasuk->perihal ?? '-' }}</strong>
                        <p class="mb-0 small text-muted">{{ $d->instruksi }}</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $d->status === 'terkirim' ? 'danger' : 'primary' }}">{{ ucfirst($d->status) }}</span>
                        @if($d->batas_waktu)
                            <br><small class="text-{{ $d->batas_waktu->isPast() ? 'danger' : 'muted' }}">{{ $d->batas_waktu->format('d-m-Y') }}</small>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="list-group-item text-center text-muted py-4">Tidak ada tugas disposisi.</div>
        @endforelse
    </div>
</div>
@endsection
