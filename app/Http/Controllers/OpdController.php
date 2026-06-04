<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpdRequest;
use App\Http\Requests\UpdateOpdRequest;
use App\Models\Opd;

class OpdController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Opd::class, 'opd');
    }

    public function index()
    {
        $opds = Opd::orderBy('nama')->paginate(20);

        return view('master.opd.index', compact('opds'));
    }

    public function create()
    {
        return view('master.opd.create');
    }

    public function store(StoreOpdRequest $request)
    {
        Opd::create($request->validated());

        return redirect()->route('opd.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    public function edit(Opd $opd)
    {
        return view('master.opd.edit', compact('opd'));
    }

    public function update(UpdateOpdRequest $request, Opd $opd)
    {
        $opd->update($request->validated());

        return redirect()->route('opd.index')->with('success', 'OPD berhasil diperbarui.');
    }

    public function destroy(Opd $opd)
    {
        $opd->update(['is_aktif' => false]);

        return redirect()->route('opd.index')->with('success', 'OPD berhasil dinonaktifkan.');
    }
}
