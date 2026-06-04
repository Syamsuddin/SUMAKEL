<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;
        $items = collect();

        if ($dari && $sampai) {
            $sm = SuratMasuk::with('klasifikasi')
                ->whereBetween('tanggal_terima', [$dari, $sampai])
                ->when($request->klasifikasi_id, fn ($q, $v) => $q->where('klasifikasi_id', $v))
                ->when($request->jenis === 'masuk', fn ($q) => $q)
                ->get()
                ->map(fn ($s) => [
                    'jenis' => 'Masuk',
                    'nomor' => $s->nomor_surat,
                    'perihal' => $s->perihal,
                    'tanggal' => $s->tanggal_terima,
                    'pihak' => $s->asal_surat,
                    'klasifikasi' => $s->klasifikasi->kode,
                ]);

            $sk = SuratKeluar::with('klasifikasi')
                ->where('status', '!=', 'draft')
                ->whereBetween('tanggal_surat', [$dari, $sampai])
                ->when($request->klasifikasi_id, fn ($q, $v) => $q->where('klasifikasi_id', $v))
                ->when($request->jenis === 'keluar', fn ($q) => $q)
                ->get()
                ->map(fn ($s) => [
                    'jenis' => 'Keluar',
                    'nomor' => $s->nomor,
                    'perihal' => $s->perihal,
                    'tanggal' => $s->tanggal_surat,
                    'pihak' => $s->jenis_tujuan === 'internal' ? $s->tujuanOpd?->nama : $s->tujuan_eksternal,
                    'klasifikasi' => $s->klasifikasi->kode,
                ]);

            if ($request->jenis === 'masuk') {
                $items = $sm;
            } elseif ($request->jenis === 'keluar') {
                $items = $sk;
            } else {
                $items = $sm->concat($sk);
            }

            $items = $items->sortBy('tanggal')->values();
        }

        $klasifikasis = Klasifikasi::orderBy('kode')->get();

        return view('agenda.index', compact('items', 'klasifikasis', 'dari', 'sampai'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date'],
        ]);

        $dari = $request->dari;
        $sampai = $request->sampai;

        $sm = SuratMasuk::with('klasifikasi')
            ->whereBetween('tanggal_terima', [$dari, $sampai])
            ->get()
            ->map(fn ($s) => [
                'jenis' => 'Masuk',
                'nomor' => $s->nomor_surat,
                'perihal' => $s->perihal,
                'tanggal' => $s->tanggal_terima->format('d-m-Y'),
                'pihak' => $s->asal_surat,
                'klasifikasi' => $s->klasifikasi->kode,
            ]);

        $sk = SuratKeluar::with('klasifikasi')
            ->where('status', '!=', 'draft')
            ->whereBetween('tanggal_surat', [$dari, $sampai])
            ->get()
            ->map(fn ($s) => [
                'jenis' => 'Keluar',
                'nomor' => $s->nomor,
                'perihal' => $s->perihal,
                'tanggal' => $s->tanggal_surat->format('d-m-Y'),
                'pihak' => $s->jenis_tujuan === 'internal' ? $s->tujuanOpd?->nama : $s->tujuan_eksternal,
                'klasifikasi' => $s->klasifikasi->kode,
            ]);

        $items = $sm->concat($sk)->sortBy('tanggal')->values();
        $opd = auth()->user()->opd;

        $pdf = Pdf::loadView('agenda.cetak', compact('items', 'dari', 'sampai', 'opd'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("agenda-{$dari}-{$sampai}.pdf");
    }
}
