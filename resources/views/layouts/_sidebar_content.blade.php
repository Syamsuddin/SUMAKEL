@php
    $currentRoute = Route::currentRouteName() ?? '';
    $user = auth()->user();
    $initials = Str::of($user->name)->explode(' ')->map(fn ($w) => Str::substr($w, 0, 1))->take(2)->implode('');
    $navItems = [
        ['label' => 'Persuratan', 'roles' => 'admin_tu|pimpinan|staf', 'items' => [
            ['route' => 'surat-masuk.index', 'prefix' => 'surat-masuk', 'icon' => 'envelope-arrow-down', 'label' => 'Surat Masuk'],
            ['route' => 'surat-keluar.index', 'prefix' => 'surat-keluar', 'icon' => 'envelope-arrow-up', 'label' => 'Surat Keluar'],
            ['route' => 'agenda.index', 'prefix' => 'agenda', 'icon' => 'journal-text', 'label' => 'Agenda'],
        ]],
        ['label' => 'Master Data', 'roles' => 'superadmin|admin_tu', 'items' => [
            ['route' => 'opd.index', 'prefix' => 'opd', 'icon' => 'building', 'label' => 'OPD', 'role' => 'superadmin'],
            ['route' => 'klasifikasi.index', 'prefix' => 'klasifikasi', 'icon' => 'tags', 'label' => 'Klasifikasi', 'role' => 'superadmin'],
            ['route' => 'user.index', 'prefix' => 'user', 'icon' => 'people', 'label' => 'Pengguna'],
        ]],
    ];
@endphp

<a class="sk-brand" href="{{ route('dashboard') }}">
    <img src="{{ asset(config('app.logo')) }}" alt="Lambang {{ config('app.pemda') }}">
    <span>
        <span class="sk-brand-name d-block">{{ config('app.name') }}</span>
        <span class="sk-brand-sub d-block">{{ config('app.pemda') }}</span>
    </span>
</a>

<div class="sk-opd">
    <span class="sk-opd-label">Unit kerja</span>
    <span class="sk-opd-name">{{ $user->opd->nama ?? 'Seluruh OPD' }}</span>
</div>

<ul class="sk-nav">
    <li>
        <a class="sk-nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}" @if($currentRoute === 'dashboard') aria-current="page" @endif>
            <x-icon name="speedometer2" /> Dashboard
        </a>
    </li>

    @foreach($navItems as $group)
        @hasanyrole($group['roles'])
            <li class="sk-nav-label">{{ $group['label'] }}</li>
            @foreach($group['items'] as $item)
                @if(Route::has($item['route']) && (empty($item['role']) || $user->hasRole($item['role'])))
                    @php $active = str_starts_with($currentRoute, $item['prefix']); @endphp
                    <li>
                        <a class="sk-nav-link {{ $active ? 'active' : '' }}" href="{{ route($item['route']) }}" @if($active) aria-current="page" @endif>
                            <x-icon :name="$item['icon']" /> {{ $item['label'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        @endhasanyrole
    @endforeach
</ul>

<div class="sk-sidebar-footer">
    <div class="sk-user">
        <span class="sk-avatar" aria-hidden="true">{{ $initials }}</span>
        <span class="min-w-0">
            <span class="sk-user-name d-block">{{ $user->name }}</span>
            <span class="sk-role-chip">{{ str_replace('_', ' ', $user->getRoleNames()->first()) }}</span>
        </span>
    </div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="sk-logout"><x-icon name="box-arrow-right" /> Keluar</button>
    </form>
    <div class="sk-version">SUMAKEL v{{ config('app.version') }}</div>
</div>
