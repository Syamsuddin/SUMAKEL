<?php

namespace App\Services;

use App\Events\SuratAntarOpdTerkirim;
use App\Models\Scopes\OpdScope;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\DB;

class RoutingSuratService
{
    public function __construct(
        private NomorSuratService $nomorSuratService,
        private PenomoranService $penomoranService,
    ) {}

    public function kirim(SuratKeluar $sk): SuratMasuk
    {
        return DB::transaction(function () use ($sk) {
            $this->nomorSuratService->terbitkan($sk);

            $sk->refresh();

            $sm = SuratMasuk::withoutGlobalScope(OpdScope::class)->create([
                'opd_id' => $sk->tujuan_opd_id,
                'klasifikasi_id' => $sk->klasifikasi_id,
                'nomor_surat' => $sk->nomor,
                'asal_surat' => $sk->opd->nama,
                'tanggal_surat' => $sk->tanggal_surat,
                'tanggal_terima' => now(),
                'perihal' => $sk->perihal,
                'sifat' => $sk->sifat,
                'surat_keluar_id' => $sk->id,
                'nomor_agenda' => $this->penomoranService->next(
                    $sk->tujuan_opd_id,
                    'agenda_masuk',
                    now()->year
                ),
                'status' => 'baru',
            ]);

            $sk->lampirans->each(fn ($l) => $sm->lampirans()->create(
                $l->only(['path', 'nama_asli', 'mime', 'ukuran'])
            ));

            event(new SuratAntarOpdTerkirim($sk, $sm));

            return $sm;
        });
    }
}
