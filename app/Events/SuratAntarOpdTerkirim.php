<?php

namespace App\Events;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratAntarOpdTerkirim
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public SuratKeluar $suratKeluar,
        public SuratMasuk $suratMasukTujuan,
    ) {}
}
