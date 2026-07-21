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
        $dokters = $query->paginate(5);

        return view('data_master.dokter_index', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100|unique:dokter,nama_dokter',
            'no_hp'       => 'nullable|string|max:20|unique:dokter,no_hp',
            'alamat'      => 'nullable|string',
        ],[
            'nama_dokter.unique' => 'Nama dokter ini sudah terdaftar di sitem.',
            'no_hp.unique'       => 'Nomor HP ini sudah terdaftar.',
        ]
        );

        Dokter::create($request->all());
        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100|unique:dokter,nama_dokter,' . $id . ',id_dokter',
            'no_hp'       => 'nullable|string|max:20|unique:dokter,no_hp,' . $id . ',id_dokter',
            'alamat'      => 'nullable|string',
        ], [
            'nama_dokter.unique' => 'Nama dokter ini sudah terdaftar di sitem.',
            'no_hp.unique'       => 'Nomor HP ini sudah terdaftar.',
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