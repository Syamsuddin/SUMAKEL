@props(['icon', 'label', 'value', 'tone' => 'primary', 'href' => null, 'hint' => null])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} {{ $attributes->class(['sk-stat', 'sk-stat-'.$tone]) }} @if($href) href="{{ $href }}" @endif>
    <span class="sk-stat-icon" aria-hidden="true"><x-icon :name="$icon" /></span>
    <span class="sk-stat-body">
        <span class="sk-stat-value">{{ $value }}</span>
        <span class="sk-stat-label">{{ $label }}</span>
        @if($hint)
            <span class="sk-stat-hint">{{ $hint }}</span>
        @endif
    </span>
</{{ $tag }}>
