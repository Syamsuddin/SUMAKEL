@props(['icon' => 'circle', 'tone' => 'neutral', 'time' => null, 'nested' => false])
<li {{ $attributes->class(['sk-timeline-item', 'is-nested' => $nested]) }}>
    <span class="sk-timeline-dot tone-{{ $tone }}" aria-hidden="true"><x-icon :name="$icon" /></span>
    <div class="sk-timeline-card">
        <div class="sk-timeline-head">
            <div>{{ $title }}</div>
            @isset($badge)
                <div>{{ $badge }}</div>
            @endisset
        </div>
        {{ $slot }}
        @if($time)
            <div class="sk-timeline-time mt-2"><x-icon name="clock" /> {{ $time }}</div>
        @endif
    </div>
</li>
