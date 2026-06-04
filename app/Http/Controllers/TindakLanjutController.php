<?php

namespace App\Http\Controllers;

use App\Events\TindakLanjutDicatat;
use App\Models\Disposisi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
    public function store(Request $request, Disposisi $disposisi)
    {
        abort_unless($disposisi->kepada_user_id === auth()->id(), 403);
        abort_unless(in_array($disposisi->status, ['terkirim', 'dibaca', 'diproses']), 403);

        $validated = $request->validate([
            'catatan' => ['required', 'string'],
            'lampirans' => ['nullable', 'array'],
            'lampirans.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'selesai' => ['nullable', 'boolean'],
        ]);

        $tindakLanjut = TindakLanjut::create([
            'disposisi_id' => $disposisi->id,
            'user_id' => auth()->id(),
            'catatan' => $validated['catatan'],
        ]);

        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $file) {
                $path = $file->store('lampiran', 'local');
                $tindakLanjut->lampirans()->create([
                    'path' => $path,
                    'nama_asli' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'ukuran' => $file->getSize(),
                ]);
            }
        }

        if ($request->boolean('selesai')) {
            $disposisi->update(['status' => 'selesai']);
        }

        $suratMasuk = $disposisi->suratMasuk;
        $suratMasuk->updateStatusFromDisposisi();

        event(new TindakLanjutDicatat($tindakLanjut));

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Tindak lanjut berhasil dicatat.');
    }
}
