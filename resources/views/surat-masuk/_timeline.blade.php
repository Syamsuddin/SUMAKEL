@php
    $disposisis = $suratMasuk->disposisis->sortBy('created_at');
    $toneMap = ['terkirim' => 'danger', 'dibaca' => 'warning', 'diproses' => 'info', 'selesai' => 'success'];
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <x-icon name="diagram-3" /> Rantai Disposisi
        @if($disposisis->isNotEmpty())
            <span class="badge text-bg-secondary ms-1">{{ $disposisis->count() }}</span>
        @endif
    </div>
    <div class="card-body">
        @if($disposisis->isEmpty())
            <x-empty-state icon="diagram-3" title="Belum ada disposisi" text="Surat ini belum didisposisikan kepada siapa pun." class="py-4" />
        @else
            <ol class="sk-timeline">
                @foreach($disposisis as $d)
                    @php $overdue = $d->batas_waktu && $d->status !== 'selesai' && $d->batas_waktu->isPast(); @endphp
                    <x-timeline-item icon="send" :tone="$toneMap[$d->status] ?? 'neutral'" :time="$d->created_at->format('d-m-Y H:i')">
                        <x-slot:title>
                            <strong>{{ $d->dariUser->name }}</strong>
                            <x-icon name="arrow-right-short" class="text-muted" />
                            <strong>{{ $d->kepadaUser->name }}</strong>
                        </x-slot:title>
                        <x-slot:badge><x-status-chip :status="$d->status" /></x-slot:badge>
                        <p class="sk-timeline-body">{{ $d->instruksi }}</p>
                        @if($d->batas_waktu)
                            <span class="sk-timeline-deadline {{ $overdue ? 'is-overdue' : 'text-muted' }}">
                                <x-icon :name="$overdue ? 'alarm-fill' : 'calendar-event'" />
                                {{ $overdue ? 'Lewat tenggat' : 'Tenggat' }}: {{ $d->batas_waktu->format('d-m-Y') }}
                            </span>
                        @endif
                    </x-timeline-item>

                    @foreach($d->tindakLanjuts->sortBy('created_at') as $tl)
                        <x-timeline-item icon="check2-square" tone="success" nested :time="$tl->created_at->format('d-m-Y H:i')">
                            <x-slot:title><strong>{{ $tl->user->name }}</strong> <span class="text-muted">mencatat tindak lanjut</span></x-slot:title>
                            <p class="sk-timeline-body">{{ $tl->catatan }}</p>
                            @if($tl->lampirans->isNotEmpty())
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($tl->lampirans as $l)
                                        <a href="{{ route('lampiran.download', $l) }}" class="sk-chip sk-chip-neutral text-decoration-none">
                                            <x-icon name="paperclip" /> {{ Str::limit($l->nama_asli, 30) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </x-timeline-item>
                    @endforeach
                @endforeach
            </ol>
        @endif
    </div>
</div>
