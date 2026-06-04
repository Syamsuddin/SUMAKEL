<?php

namespace App\Events;

use App\Models\TindakLanjut;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TindakLanjutDicatat
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TindakLanjut $tindakLanjut,
    ) {}
}
