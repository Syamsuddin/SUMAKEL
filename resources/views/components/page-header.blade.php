@props(['title', 'subtitle' => null])
<div {{ $attributes->class('sk-page-header') }}>
    <div>
        <h1>{{ $title }}</h1>
        @if($subtitle)
            <p class="sk-page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="sk-page-actions">{{ $actions }}</div>
    @endisset
</div>
