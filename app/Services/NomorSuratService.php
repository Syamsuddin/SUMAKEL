<?php

namespace App\Services;

use App\Models\SuratKeluar;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    public function __construct(
        private PenomoranService $penomoranService,
    ) {}

    public function terbitkan(SuratKeluar $sk): void
    {
        DB::transaction(function () use ($sk) {
            $nomorUrut = $this->penomoranService->next(
                $sk->opd_id,
                'surat_keluar',
                $sk->tanggal_surat->year
            );

            $nomor = $this->renderNomor($sk, $nomorUrut);

            $sk->update([
                'nomor' => $nomor,
                'nomor_urut' => $nomorUrut,
                'status' => 'terbit',
            ]);
        });
    }

    private function renderNomor(SuratKeluar $sk, int $nomorUrut): string
    {
        $opd = $sk->opd;
        $klasifikasi = $sk->klasifikasi;

        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        $replacements = [
            '{klasifikasi}' => $klasifikasi->kode,
            '{nomor}' => str_pad($nomorUrut, 3, '0', STR_PAD_LEFT),
            '{kode_opd}' => $opd->kode,
            '{bulan_romawi}' => $bulanRomawi[$sk->tanggal_surat->month],
            '{tahun}' => $sk->tanggal_surat->year,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $opd->format_nomor
        );
    }
}
