<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOpd;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SuratKeluar extends Model
{
    use BelongsToOpd;

    protected $fillable = [
        'opd_id',
        'klasifikasi_id',
        'nomor',
        'nomor_urut',
        'tanggal_surat',
        'jenis_tujuan',
        'tujuan_eksternal',
        'tujuan_opd_id',
        'perihal',
        'sifat',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function tujuanOpd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'tujuan_opd_id');
    }

    public function lampirans(): MorphMany
    {
        return $this->morphMany(Lampiran::class, 'lampiranable');
    }

    public function suratMasukTujuan(): HasOne
    {
        return $this->hasOne(SuratMasuk::class, 'surat_keluar_id');
    }
}
