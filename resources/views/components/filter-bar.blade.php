@props(['action' => null, 'reset' => null, 'search' => 'cari', 'placeholder' => 'Cari...'])
<div {{ $attributes->class('sk-filter-bar') }}>
    <form method="GET" @if($action) action="{{ $action }}" @endif class="row g-2 align-items-end" role="search">
        {{ $slot }}
        <div class="col-12 col-md">
            <label for="f-cari" class="form-label">Cari</label>
            <div class="input-group input-group-sm flex-nowrap">
                <input type="search" name="{{ $search }}" id="f-cari" class="form-control" value="{{ request($search) }}" placeholder="{{ $placeholder }}">
                <button class="btn btn-primary" type="submit"><x-icon name="funnel" /> Filter</button>
            </div>
        </div>
        @if($reset && request()->query())
            <div class="col-12 col-md-auto">
                <a href="{{ $reset }}" class="btn btn-link btn-sm text-decoration-none px-1">Reset</a>
            </div>
        @endif
    </form>
</div>
