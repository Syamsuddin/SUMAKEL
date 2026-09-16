@props(['icon' => 'inbox', 'title', 'text' => null])
<div {{ $attributes->class('sk-empty') }}>
    <div class="sk-empty-icon"><x-icon :name="$icon" /></div>
    <div class="sk-empty-title">{{ $title }}</div>
    @if($text)
        <p class="mb-3">{{ $text }}</p>
    @endif
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>
