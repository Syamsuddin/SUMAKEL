@props(['label', 'nomor' => null, 'placeholder' => null, 'perihal' => null])
<article {{ $attributes->class('sk-doc') }}>
    <div class="sk-doc-top">
        <div>
            <span class="sk-doc-label">{{ $label }}</span>
            @if($nomor)
                <div class="sk-doc-number">{{ $nomor }}</div>
            @else
                <div class="sk-doc-number is-placeholder">{{ $placeholder }}</div>
            @endif
        </div>
        @isset($chips)
            <div class="sk-doc-chips">{{ $chips }}</div>
        @endisset
    </div>
    @isset($meta)
        <dl class="sk-doc-meta">{{ $meta }}</dl>
    @endisset
    @if($perihal)
        <div class="sk-doc-perihal">
            <h2>Perihal</h2>
            <p>{{ $perihal }}</p>
        </div>
    @endif
    {{ $slot }}
</article>
