{{-- Shared task list for pimpinan/staf dashboards: $tugas (Collection<Disposisi>) --}}
@if($tugas->isEmpty())
    <x-empty-state icon="check2-circle" title="Tidak ada tugas" text="Semua disposisi untuk Anda sudah ditangani." class="py-4" />
@else
    <div class="list-group list-group-flush">
        @foreach($tugas as $d)
            @php
                $sm = $d->suratMasuk;
                $overdue = $d->batas_waktu && $d->batas_waktu->isPast();
            @endphp
            <div class="list-group-item sk-task">
                <div class="sk-task-icon"><x-status-chip :sifat="$sm->sifat ?? 'biasa'" /></div>
                <div class="sk-task-body">
                    <div class="sk-task-title">{{ $sm->perihal ?? '-' }}</div>
                    <div class="sk-task-meta">
                        <span><x-icon name="person" /> {{ $d->dariUser->name }}</span>
                        @if($sm)<span><x-icon name="hash" /> Agenda {{ $sm->nomor_agenda }}</span>@endif
                        @if($d->batas_waktu)
                            <span class="{{ $overdue ? 'text-danger fw-semibold' : '' }}">
                                <x-icon :name="$overdue ? 'alarm-fill' : 'calendar-event'" />
                                {{ $overdue ? 'Lewat tenggat' : 'Tenggat' }} {{ $d->batas_waktu->format('d-m-Y') }}
                            </span>
                        @endif
                    </div>
                    <p class="sk-task-instr">{{ $d->instruksi }}</p>
                </div>
                <div class="sk-task-actions">
                    <x-status-chip :status="$d->status" />
                    <a href="{{ route('surat-masuk.show', $d->surat_masuk_id) }}" class="btn btn-sm btn-primary text-nowrap">Buka <x-icon name="arrow-right" /></a>
                </div>
            </div>
        @endforeach
    </div>
@endif
