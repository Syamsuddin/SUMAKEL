<?php

namespace App\Services;

use App\Models\NomorCounter;
use Illuminate\Support\Facades\DB;

class PenomoranService
{
    public function next(int $opdId, string $jenis, int $tahun): int
    {
        return DB::transaction(function () use ($opdId, $jenis, $tahun) {
            $counter = NomorCounter::firstOrCreate(
                ['opd_id' => $opdId, 'jenis' => $jenis, 'tahun' => $tahun],
                ['nilai' => 0]
            );

            $counter = NomorCounter::where('id', $counter->id)->lockForUpdate()->first();
            $counter->increment('nilai');

            return $counter->fresh()->nilai;
        });
    }
}
