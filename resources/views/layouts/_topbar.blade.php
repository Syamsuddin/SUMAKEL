<header class="sk-topbar">
    @auth
        <button class="sk-topbar-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile" aria-label="Buka menu navigasi">
            <x-icon name="list" />
        </button>
    @endauth

    <x-breadcrumb />

    <div class="d-flex align-items-center ms-auto gap-1">
        @include('layouts._notifikasi')
        @stack('navbar-extra')

        @auth
            <div class="dropdown">
                <button class="sk-topbar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun {{ Auth::user()->name }}">
                    <span class="sk-avatar" style="width:32px;height:32px;font-size:.8125rem">{{ Str::of(Auth::user()->name)->explode(' ')->map(fn ($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text">
                            <strong class="d-block">{{ Auth::user()->name }}</strong>
                            <small class="text-muted">
                                {{ Auth::user()->getRoleNames()->first() }}
                                @if(Auth::user()->opd)
                                    &mdash; {{ Auth::user()->opd->nama }}
                                @endif
                            </small>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @if(Route::has('profil.edit'))
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profil.edit') }}"><x-icon name="person-gear" /> Profil</a></li>
                    @endif
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2"><x-icon name="box-arrow-right" /> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</header>
