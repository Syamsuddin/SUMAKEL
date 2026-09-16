@props([
    'id',
    'action',
    'method' => 'POST',
    'title' => 'Konfirmasi',
    'text' => 'Apakah Anda yakin?',
    'confirm' => 'Ya, lanjutkan',
    'tone' => 'danger',
])
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="{{ $id }}-title">{{ $title }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">{{ $text }}</p>
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ $action }}" method="POST" data-loading>
                    @csrf
                    @if(strtoupper($method) !== 'POST')
                        @method($method)
                    @endif
                    <button type="submit" class="btn btn-{{ $tone }}">{{ $confirm }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
