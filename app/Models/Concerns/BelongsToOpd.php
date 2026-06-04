<?php

namespace App\Models\Concerns;

use App\Models\Opd;
use App\Models\Scopes\OpdScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOpd
{
    public static function bootBelongsToOpd(): void
    {
        static::addGlobalScope(new OpdScope);

        static::creating(function ($model) {
            if (! $model->opd_id && auth()->check() && auth()->user()->opd_id) {
                $model->opd_id = auth()->user()->opd_id;
            }
        });
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }
}
