<?php

namespace App\Listeners;

use App\Events\SuratAntarOpdTerkirim;
use App\Models\User;
use App\Notifications\SuratAntarOpdMasuk;
use Illuminate\Support\Facades\Notification;

class KirimNotifikasiSuratAntarOpd
{
    public function handle(SuratAntarOpdTerkirim $event): void
    {
        $adminTus = User::where('opd_id', $event->suratMasukTujuan->opd_id)
            ->role('admin_tu')
            ->get();

        Notification::send($adminTus, new SuratAntarOpdMasuk($event->suratMasukTujuan));
    }
}
