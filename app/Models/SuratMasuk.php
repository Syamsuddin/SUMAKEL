<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOpd;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SuratMasuk extends Model
{
    use BelongsToOpd;

    protected $fillable = [
        'opd_id',
        'klasifikasi_id',
        'surat_keluar_id',
        'nomor_agenda',
        'nomor_surat',
        'asal_surat',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'sifat',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_terima' => 'date',
        ];
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class);
    }

    public function lampirans(): MorphMany
    {
        return $this->morphMany(Lampiran::class, 'lampiranable');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->hasRole('admin_tu') || $user->hasRole('pimpinan')) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('sifat', '!=', 'rahasia')
                ->orWhereHas('disposisis', function (Builder $dq) use ($user) {
                    $dq->where('kepada_user_id', $user->id)
                        ->orWhere('dari_user_id', $user->id);
                });
        });
    }

    public function updateStatusFromDisposisi(): void
    {
        $disposisis = $this->disposisis()->get();

        if ($disposisis->isEmpty()) {
            return;
        }

        $allDone = $disposisis->every(fn ($d) => $d->status === 'selesai');

        $this->update(['status' => $allDone ? 'selesai' : 'didisposisi']);
    }
}
