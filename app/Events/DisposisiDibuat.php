<?php

namespace App\Events;

use App\Models\Disposisi;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisposisiDibuat
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Disposisi $disposisi,
    ) {}
}
