<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Lampiran extends Model
{
    protected $fillable = [
        'lampiranable_type',
        'lampiranable_id',
        'path',
        'nama_asli',
        'mime',
        'ukuran',
    ];

    public function lampiranable(): MorphTo
    {
        return $this->morphTo();
    }
}
