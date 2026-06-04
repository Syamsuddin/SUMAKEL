<?php

namespace App\Listeners;

use App\Events\TindakLanjutDicatat;
use App\Notifications\TindakLanjutBaru;

class KirimNotifikasiTindakLanjut
{
    public function handle(TindakLanjutDicatat $event): void
    {
        $dariUser = $event->tindakLanjut->disposisi->dariUser;
        $dariUser->notify(new TindakLanjutBaru($event->tindakLanjut));
    }
}
