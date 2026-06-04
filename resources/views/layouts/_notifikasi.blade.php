@auth
@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $recentNotifications = auth()->user()->unreadNotifications()->take(10)->get();
@endphp
<div class="dropdown me-2">
    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
        🔔
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li><h6 class="dropdown-header">Notifikasi</h6></li>
        @forelse($recentNotifications as $n)
            <li>
                <a class="dropdown-item small py-2" href="{{ route('notifikasi.read', $n->id) }}">
                    <strong>{{ $n->data['judul'] ?? 'Notifikasi' }}</strong><br>
                    <span class="text-muted">{{ Str::limit($n->data['pesan'] ?? '', 80) }}</span><br>
                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                </a>
            </li>
        @empty
            <li><span class="dropdown-item-text text-muted small">Tidak ada notifikasi baru.</span></li>
        @endforelse
        <li><hr class="dropdown-divider"></li>
        <li class="d-flex justify-content-between px-3 pb-1">
            <a href="{{ route('notifikasi.index') }}" class="small">Lihat semua</a>
            @if($unreadCount > 0)
                <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                    @csrf
                    <button class="btn btn-link btn-sm p-0 small">Tandai semua dibaca</button>
                </form>
            @endif
        </li>
    </ul>
</div>
@endauth
