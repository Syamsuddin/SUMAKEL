<?php

namespace App\Http\Controllers;

use App\Events\DisposisiDibuat;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        $this->authorize('view', $suratMasuk);

        $user = auth()->user();

        if ($user->hasRole('admin_tu') && $suratMasuk->status === 'baru') {
            $parentId = null;
        } else {
            $activeDisposisi = $suratMasuk->disposisis()
                ->where('kepada_user_id', $user->id)
                ->whereIn('status', ['terkirim', 'dibaca', 'diproses'])
                ->first();

            abort_unless($activeDisposisi, 403);

            $parentId = $activeDisposisi->id;

            if ($activeDisposisi->status === 'terkirim') {
                $activeDisposisi->update(['status' => 'diproses']);
            }
        }

        $validated = $request->validate([
            'kepada_user_id' => ['required', 'exists:users,id'],
            'instruksi' => ['required', 'string'],
            'batas_waktu' => ['nullable', 'date'],
        ]);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $suratMasuk->id,
            'parent_id' => $parentId,
            'dari_user_id' => $user->id,
            'kepada_user_id' => $validated['kepada_user_id'],
            'instruksi' => $validated['instruksi'],
            'batas_waktu' => $validated['batas_waktu'] ?? null,
        ]);

        $suratMasuk->updateStatusFromDisposisi();

        event(new DisposisiDibuat($disposisi));

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Disposisi berhasil dikirim.');
    }

    public function markRead(Disposisi $disposisi)
    {
        abort_unless($disposisi->kepada_user_id === auth()->id(), 403);

        if ($disposisi->status === 'terkirim') {
            $disposisi->update(['status' => 'dibaca']);
        }

        return redirect()->route('surat-masuk.show', $disposisi->surat_masuk_id);
    }
}
