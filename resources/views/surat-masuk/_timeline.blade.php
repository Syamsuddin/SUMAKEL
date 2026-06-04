@php
    $events = collect();

    foreach ($suratMasuk->disposisis as $d) {
        $events->push(['waktu' => $d->created_at, 'tipe' => 'disposisi', 'data' => $d]);
        foreach ($d->tindakLanjuts as $tl) {
            $events->push(['waktu' => $tl->created_at, 'tipe' => 'tindak_lanjut', 'data' => $tl, 'disposisi' => $d]);
        }
    }

    $events = $events->sortBy('waktu');
@endphp

@if($events->isNotEmpty())
    <div class="card">
        <div class="card-header">Timeline</div>
        <div class="list-group list-group-flush">
            @foreach($events as $event)
                <div class="list-group-item">
                    @if($event['tipe'] === 'disposisi')
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $event['data']->dariUser->name }}</strong>
                                mendisposisikan ke
                                <strong>{{ $event['data']->kepadaUser->name }}</strong>
                            </div>
                            <span class="badge bg-{{ $event['data']->status === 'selesai' ? 'success' : 'primary' }}">
                                {{ ucfirst($event['data']->status) }}
                            </span>
                        </div>
                        <p class="mb-0 mt-1 text-muted small">{{ $event['data']->instruksi }}</p>
                        @if($event['data']->batas_waktu)
                            <small class="text-danger">Batas waktu: {{ $event['data']->batas_waktu->format('d-m-Y') }}</small>
                        @endif
                    @else
                        <div>
                            <strong>{{ $event['data']->user->name }}</strong> mencatat tindak lanjut
                        </div>
                        <p class="mb-0 mt-1 text-muted small">{{ $event['data']->catatan }}</p>
                        @if($event['data']->lampirans->isNotEmpty())
                            <div class="mt-1">
                                @foreach($event['data']->lampirans as $l)
                                    <a href="{{ route('lampiran.download', $l) }}" class="badge bg-light text-dark text-decoration-none">{{ $l->nama_asli }}</a>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    <small class="text-muted d-block mt-1">{{ $event['waktu']->format('d-m-Y H:i') }}</small>
                </div>
            @endforeach
        </div>
    </div>
@endif
