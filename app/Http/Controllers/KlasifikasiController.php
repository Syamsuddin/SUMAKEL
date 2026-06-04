<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKlasifikasiRequest;
use App\Http\Requests\UpdateKlasifikasiRequest;
use App\Models\Klasifikasi;

class KlasifikasiController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Klasifikasi::class, 'klasifikasi');
    }

    public function index()
    {
        $klasifikasis = Klasifikasi::orderBy('kode')->paginate(20);

        return view('master.klasifikasi.index', compact('klasifikasis'));
    }

    public function create()
    {
        return view('master.klasifikasi.create');
    }

    public function store(StoreKlasifikasiRequest $request)
    {
        Klasifikasi::create($request->validated());

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function edit(Klasifikasi $klasifikasi)
    {
        return view('master.klasifikasi.edit', compact('klasifikasi'));
    }

    public function update(UpdateKlasifikasiRequest $request, Klasifikasi $klasifikasi)
    {
        $klasifikasi->update($request->validated());

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    public function destroy(Klasifikasi $klasifikasi)
    {
        $klasifikasi->delete();

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil dihapus.');
    }
}
