<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Dokter::query();
        if ($request->has('cari')) {
            $query->where('nama_dokter', 'like', '%' . $request->cari . '%');
        }
        $dokters = $query->paginate(10);

        return view('data_master.dokter_index', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
        ]);

        Dokter::create($request->all());
        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
        ]);

        $dokter = Dokter::findOrFail($id);
        $dokter->update($request->all());
        
        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();
        
        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil dihapus!');
    }
}