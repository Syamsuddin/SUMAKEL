@props(['lampirans', 'title' => 'Lampiran'])
@if($lampirans->isNotEmpty())
<div {{ $attributes->class('card mb-3') }}>
    <div class="card-header d-flex align-items-center gap-2"><x-icon name="paperclip" /> {{ $title }} <span class="badge text-bg-secondary ms-1">{{ $lampirans->count() }}</span></div>
    <div class="list-group list-group-flush">
        @foreach($lampirans as $lampiran)
            @php $isImage = in_array(strtolower(pathinfo($lampiran->nama_asli, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']); @endphp
            <a href="{{ route('lampiran.download', $lampiran) }}" class="list-group-item list-group-item-action sk-attachment {{ $isImage ? 'is-image' : '' }}">
                <x-icon :name="$isImage ? 'file-earmark-image' : 'file-earmark-pdf'" class="sk-attachment-icon" />
                <span class="sk-attachment-name">{{ $lampiran->nama_asli }}</span>
                <span class="sk-attachment-size">{{ number_format($lampiran->ukuran / 1024, 1) }} KB</span>
                <x-icon name="download" label="Unduh" class="text-info" />
            </a>
        @endforeach
    </div>
</div>
@endif
