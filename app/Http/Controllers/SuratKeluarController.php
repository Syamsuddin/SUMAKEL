<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratKeluarRequest;
use App\Http\Requests\UpdateSuratKeluarRequest;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratKeluar;
use App\Services\NomorSuratService;
use App\Services\RoutingSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SuratKeluar::class, 'surat_keluar');
    }

    public function index(Request $request)
    {
        $query = SuratKeluar::with('klasifikasi', 'tujuanOpd')
            ->latest('created_at');

        if ($request->filled('dari')) {
            $query->where('tanggal_surat', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal_surat', '<=', $request->sampai);
        }
        if ($request->filled('klasifikasi_id')) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }
        if ($request->filled('sifat')) {
            $query->where('sifat', $request->sifat);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_tujuan')) {
            $query->where('jenis_tujuan', $request->jenis_tujuan);
        }
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('perihal', 'like', "%{$request->cari}%")
                    ->orWhere('nomor', 'like', "%{$request->cari}%");
            });
        }

        $suratKeluars = $query->paginate(20)->withQueryString();
        $klasifikasis = Klasifikasi::orderBy('kode')->get();

        return view('surat-keluar.index', compact('suratKeluars', 'klasifikasis'));
    }

    public function create()
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->get();
        $opds = Opd::where('is_aktif', true)
            ->where('id', '!=', auth()->user()->opd_id)
            ->orderBy('nama')
            ->get();

        return view('surat-keluar.create', compact('klasifikasis', 'opds'));
    }

    public function store(StoreSuratKeluarRequest $request)
    {
        $data = $request->validated();
        unset($data['lampirans']);

        $suratKeluar = SuratKeluar::create($data);

        $this->simpanLampirans($request, $suratKeluar);

        return redirect()->route('surat-keluar.show', $suratKeluar)->with('success', 'Draft surat keluar berhasil dibuat.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load('klasifikasi', 'tujuanOpd', 'lampirans', 'suratMasukTujuan');

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->get();
        $opds = Opd::where('is_aktif', true)
            ->where('id', '!=', auth()->user()->opd_id)
            ->orderBy('nama')
            ->get();

        return view('surat-keluar.edit', compact('suratKeluar', 'klasifikasis', 'opds'));
    }

    public function update(UpdateSuratKeluarRequest $request, SuratKeluar $suratKeluar)
    {
        $data = $request->validated();
        unset($data['lampirans']);

        $suratKeluar->update($data);

        $this->simpanLampirans($request, $suratKeluar);

        return redirect()->route('surat-keluar.show', $suratKeluar)->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        foreach ($suratKeluar->lampirans as $lampiran) {
            Storage::disk('local')->delete($lampiran->path);
        }
        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Draft surat keluar berhasil dihapus.');
    }

    public function terbitkan(SuratKeluar $suratKeluar, NomorSuratService $nomorSuratService, RoutingSuratService $routingSuratService)
    {
        $this->authorize('terbitkan', $suratKeluar);

        if ($suratKeluar->jenis_tujuan === 'internal') {
            $routingSuratService->kirim($suratKeluar);
        } else {
            $nomorSuratService->terbitkan($suratKeluar);
        }

        return redirect()->route('surat-keluar.show', $suratKeluar)->with('success', 'Surat keluar berhasil diterbitkan.');
    }

    public function arsipkan(SuratKeluar $suratKeluar)
    {
        $this->authorize('arsipkan', $suratKeluar);

        $suratKeluar->update(['status' => 'diarsip']);

        return redirect()->route('surat-keluar.show', $suratKeluar)->with('success', 'Surat keluar berhasil diarsipkan.');
    }

    private function simpanLampirans(Request $request, SuratKeluar $suratKeluar): void
    {
        if (! $request->hasFile('lampirans')) {
            return;
        }

        foreach ($request->file('lampirans') as $file) {
            $path = $file->store('lampiran', 'local');
            $suratKeluar->lampirans()->create([
                'path' => $path,
                'nama_asli' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
            ]);
        }
    }
}
