<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TindakLanjut extends Model
{
    protected $fillable = [
        'disposisi_id',
        'user_id',
        'catatan',
    ];

    public function disposisi(): BelongsTo
    {
        return $this->belongsTo(Disposisi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lampirans(): MorphMany
    {
        return $this->morphMany(Lampiran::class, 'lampiranable');
    }
}
