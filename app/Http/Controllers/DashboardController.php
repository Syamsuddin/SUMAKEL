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

        $totalOpd = $rekapOpd->count();
        $totalUser = $rekapOpd->sum('users_count');
        $totalSm = $rekapOpd->sum('sm_count');
        $totalSk = $rekapOpd->sum('sk_count');
        $chartData = $this->chartVolume12Bulan();

        return view('dashboard.superadmin', compact('rekapOpd', 'chartData', 'totalOpd', 'totalUser', 'totalSm', 'totalSk'));
    }

    private function adminTu()
    {
        $bulanIni = now()->startOfMonth();
        $smBulanIni = SuratMasuk::where('tanggal_terima', '>=', $bulanIni)->count();
        $skBulanIni = SuratKeluar::where('created_at', '>=', $bulanIni)->count();
        $smBelumDisposisi = SuratMasuk::where('status', 'baru')->count();
        $disposisiLewatTenggat = Disposisi::whereIn('status', ['terkirim', 'dibaca', 'diproses'])
            ->whereDate('batas_waktu', '<', today())
            ->count();
        $smTerbaru = SuratMasuk::with('klasifikasi')->latest()->take(5)->get();
        $chartData = $this->chartVolume12Bulan(auth()->user()->opd_id);

        return view('dashboard.admin_tu', compact('smBulanIni', 'skBulanIni', 'smBelumDisposisi', 'disposisiLewatTenggat', 'smTerbaru', 'chartData'));
    }

    private function pimpinan()
    {
        $disposisiMenunggu = Disposisi::where('kepada_user_id', auth()->id())
            ->whereIn('status', ['terkirim', 'dibaca'])
            ->with(['suratMasuk', 'dariUser'])
            ->latest()
            ->take(10)
            ->get();

        $ringkasan = $this->ringkasanDisposisi();

        return view('dashboard.pimpinan', compact('disposisiMenunggu', 'ringkasan'));
    }

    private function staf()
    {
        $tugasDisposisi = Disposisi::where('kepada_user_id', auth()->id())
            ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
            ->with(['suratMasuk', 'dariUser'])
            ->orderByRaw('batas_waktu IS NULL')
            ->orderBy('batas_waktu')
            ->take(10)
            ->get();

        $ringkasan = $this->ringkasanDisposisi();

        return view('dashboard.staf', compact('tugasDisposisi', 'ringkasan'));
    }

    /**
     * Counts of the current user's dispositions: waiting, in progress,
     * completed this month, overdue.
     */
    private function ringkasanDisposisi(): array
    {
        $base = Disposisi::where('kepada_user_id', auth()->id());

        return [
            'menunggu' => (clone $base)->whereIn('status', ['terkirim', 'dibaca'])->count(),
            'diproses' => (clone $base)->where('status', 'diproses')->count(),
            'selesai' => (clone $base)->where('status', 'selesai')->where('updated_at', '>=', now()->startOfMonth())->count(),
            'lewat' => (clone $base)->whereIn('status', ['terkirim', 'dibaca', 'diproses'])->whereDate('batas_waktu', '<', today())->count(),
        ];
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
