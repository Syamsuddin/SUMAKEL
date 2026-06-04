<?php

namespace App\Listeners;

use App\Events\DisposisiDibuat;
use App\Notifications\DisposisiBaru;

class KirimNotifikasiDisposisi
{
    public function handle(DisposisiDibuat $event): void
    {
        $event->disposisi->kepadaUser->notify(new DisposisiBaru($event->disposisi));
    }
}
