<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Opd;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            return $this->superadmin();
        }
        if ($user->hasRole('admin_tu')) {
            return $this->adminTu();
        }
        if ($user->hasRole('pimpinan')) {
            return $this->pimpinan();
        }

        return $this->staf();
    }

    private function superadmin()
    {
        $rekapOpd = Opd::withCount([
            'users',
        ])->where('is_aktif', true)->get()->map(function ($opd) {
            $opd->sm_count = SuratMasuk::withoutGlobalScopes()->where('opd_id', $opd->id)->count();
            $opd->sk_count = SuratKeluar::withoutGlobalScopes()->where('opd_id', $opd->id)->count();

            return $opd;
        });

        $chartData = $this->chartVolume12Bulan();

        return view('dashboard.superadmin', compact('rekapOpd', 'chartData'));
    }

    private function adminTu()
    {
        $bulanIni = now()->startOfMonth();
        $smBulanIni = SuratMasuk::where('tanggal_terima', '>=', $bulanIni)->count();
        $skBulanIni = SuratKeluar::where('created_at', '>=', $bulanIni)->count();
        $smBelumDisposisi = SuratMasuk::where('status', 'baru')->count();
        $chartData = $this->chartVolume12Bulan(auth()->user()->opd_id);

        return view('dashboard.admin_tu', compact('smBulanIni', 'skBulanIni', 'smBelumDisposisi', 'chartData'));
    }

    private function pimpinan()
    {
        $disposisiMenunggu = Disposisi::where('kepada_user_id', auth()->id())
            ->whereIn('status', ['terkirim', 'dibaca'])
            ->with('suratMasuk')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.pimpinan', compact('disposisiMenunggu'));
    }

    private function staf()
    {
        $tugasDisposisi = Disposisi::where('kepada_user_id', auth()->id())
            ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
            ->with('suratMasuk')
            ->orderBy('batas_waktu')
            ->take(10)
            ->get();

        return view('dashboard.staf', compact('tugasDisposisi'));
    }

    private function chartVolume12Bulan(?int $opdId = null): array
    {
        $labels = [];
        $smData = [];
        $skData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $smQuery = SuratMasuk::withoutGlobalScopes()->whereBetween('tanggal_terima', [$start, $end]);
            $skQuery = SuratKeluar::withoutGlobalScopes()->whereBetween('created_at', [$start, $end]);

            if ($opdId) {
                $smQuery->where('opd_id', $opdId);
                $skQuery->where('opd_id', $opdId);
            }

            $smData[] = $smQuery->count();
            $skData[] = $skQuery->count();
        }

        return compact('labels', 'smData', 'skData');
    }
}
