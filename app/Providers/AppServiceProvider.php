<?php

namespace App\Providers;

use App\Events\DisposisiDibuat;
use App\Events\SuratAntarOpdTerkirim;
use App\Events\TindakLanjutDicatat;
use App\Listeners\KirimNotifikasiDisposisi;
use App\Listeners\KirimNotifikasiSuratAntarOpd;
use App\Listeners\KirimNotifikasiTindakLanjut;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(DisposisiDibuat::class, KirimNotifikasiDisposisi::class);
        Event::listen(TindakLanjutDicatat::class, KirimNotifikasiTindakLanjut::class);
        Event::listen(SuratAntarOpdTerkirim::class, KirimNotifikasiSuratAntarOpd::class);
    }
}
