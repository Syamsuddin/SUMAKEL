<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\Storage;

class LampiranController extends Controller
{
    public function download(Lampiran $lampiran)
    {
        $parent = $lampiran->lampiranable;

        if (! $parent) {
            abort(403);
        }

        if ($parent instanceof SuratMasuk) {
            $this->authorize('view', $parent);
        } elseif ($parent instanceof SuratKeluar) {
            $this->authorize('view', $parent);
        } elseif ($parent instanceof TindakLanjut) {
            $this->authorize('view', $parent->disposisi->suratMasuk);
        } else {
            abort(403);
        }

        return Storage::disk('local')->download($lampiran->path, $lampiran->nama_asli);
    }
}
