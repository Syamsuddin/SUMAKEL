@if(count($items) > 1)
<nav aria-label="Breadcrumb" class="d-none d-sm-block flex-grow-1 min-w-0">
    <ol class="breadcrumb sk-breadcrumb">
        @foreach($items as $item)
            @if($loop->last || empty($item['url']))
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>{{ $item['label'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
@else
<div class="flex-grow-1 fw-semibold text-primary d-none d-sm-block">{{ $items[0]['label'] ?? '' }}</div>
@endif
