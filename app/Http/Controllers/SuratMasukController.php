<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratMasukRequest;
use App\Http\Requests\UpdateSuratMasukRequest;
use App\Models\Klasifikasi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\PenomoranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SuratMasuk::class, 'surat_masuk');
    }

    public function index(Request $request)
    {
        $query = SuratMasuk::with('klasifikasi')
            ->visibleTo(auth()->user())
            ->latest('tanggal_terima');

        if ($request->filled('dari')) {
            $query->where('tanggal_terima', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal_terima', '<=', $request->sampai);
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
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('perihal', 'like', "%{$request->cari}%")
                    ->orWhere('asal_surat', 'like', "%{$request->cari}%")
                    ->orWhere('nomor_surat', 'like', "%{$request->cari}%");
            });
        }

        $suratMasuks = $query->paginate(20)->withQueryString();
        $klasifikasis = Klasifikasi::orderBy('kode')->get();

        return view('surat-masuk.index', compact('suratMasuks', 'klasifikasis'));
    }

    public function create()
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->get();

        return view('surat-masuk.create', compact('klasifikasis'));
    }

    public function store(StoreSuratMasukRequest $request, PenomoranService $penomoranService)
    {
        $data = $request->validated();
        unset($data['lampirans']);

        $data['nomor_agenda'] = $penomoranService->next(
            auth()->user()->opd_id,
            'agenda_masuk',
            now()->year
        );

        $suratMasuk = SuratMasuk::create($data);

        $this->simpanLampirans($request, $suratMasuk);

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Surat masuk berhasil dicatat.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load([
            'klasifikasi',
            'lampirans',
            'disposisis.dariUser',
            'disposisis.kepadaUser',
            'disposisis.tindakLanjuts.user',
            'disposisis.tindakLanjuts.lampirans',
        ]);

        $usersSeOpd = User::where('opd_id', $suratMasuk->opd_id)
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return view('surat-masuk.show', compact('suratMasuk', 'usersSeOpd'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->get();

        return view('surat-masuk.edit', compact('suratMasuk', 'klasifikasis'));
    }

    public function update(UpdateSuratMasukRequest $request, SuratMasuk $suratMasuk)
    {
        $data = $request->validated();
        unset($data['lampirans']);

        $suratMasuk->update($data);

        $this->simpanLampirans($request, $suratMasuk);

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        foreach ($suratMasuk->lampirans as $lampiran) {
            Storage::disk('local')->delete($lampiran->path);
        }
        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function arsipkan(SuratMasuk $suratMasuk)
    {
        $this->authorize('arsipkan', $suratMasuk);

        $suratMasuk->update(['status' => 'diarsip']);

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Surat masuk berhasil diarsipkan.');
    }

    private function simpanLampirans(Request $request, SuratMasuk $suratMasuk): void
    {
        if (! $request->hasFile('lampirans')) {
            return;
        }

        foreach ($request->file('lampirans') as $file) {
            $path = $file->store('lampiran', 'local');
            $suratMasuk->lampirans()->create([
                'path' => $path,
                'nama_asli' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
            ]);
        }
    }
}
