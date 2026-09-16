@auth
@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $recentNotifications = auth()->user()->unreadNotifications()->take(10)->get();
    $notifIcons = ['DisposisiBaru' => 'diagram-3', 'TindakLanjutBaru' => 'check2-square', 'SuratAntarOpdMasuk' => 'envelope-arrow-down'];
@endphp
<div class="dropdown">
    <button class="sk-topbar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
            aria-label="Notifikasi{{ $unreadCount > 0 ? ', '.$unreadCount.' belum dibaca' : '' }}">
        <x-icon name="bell" />
        @if($unreadCount > 0)
            <span class="sk-topbar-badge" aria-hidden="true">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end sk-notif-menu p-0">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-semibold">Notifikasi</span>
            @if($unreadCount > 0)
                <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                    @csrf
                    <button class="btn btn-link btn-sm p-0">Tandai semua dibaca</button>
                </form>
            @endif
        </div>
        @forelse($recentNotifications as $n)
            <a class="dropdown-item sk-notif-item border-bottom" href="{{ route('notifikasi.read', $n->id) }}">
                <x-icon :name="$notifIcons[class_basename($n->type)] ?? 'bell'" />
                <span class="min-w-0">
                    <strong class="d-block">{{ $n->data['judul'] ?? 'Notifikasi' }}</strong>
                    <span class="text-muted small d-block">{{ Str::limit($n->data['pesan'] ?? '', 80) }}</span>
                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                </span>
            </a>
        @empty
            <div class="px-3 py-4 text-center text-muted small">Tidak ada notifikasi baru.</div>
        @endforelse
        <a href="{{ route('notifikasi.index') }}" class="dropdown-item text-center small fw-semibold py-2">Lihat semua notifikasi</a>
    </div>
</div>
@endauth
