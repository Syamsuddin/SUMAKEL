@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Semua Notifikasi</h4>
    @if(auth()->user()->unreadNotifications()->count() > 0)
        <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-primary">Tandai Semua Dibaca</button>
        </form>
    @endif
</div>

<div class="list-group">
    @forelse($notifications as $n)
        <a href="{{ route('notifikasi.read', $n->id) }}" class="list-group-item list-group-item-action {{ $n->read_at ? '' : 'list-group-item-light fw-semibold' }}">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>{{ $n->data['judul'] ?? 'Notifikasi' }}</strong>
                    <p class="mb-0 small text-muted">{{ $n->data['pesan'] ?? '' }}</p>
                </div>
                <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
            </div>
        </a>
    @empty
        <div class="list-group-item text-center text-muted py-4">Belum ada notifikasi.</div>
    @endforelse
</div>

{{ $notifications->links() }}
@endsection
