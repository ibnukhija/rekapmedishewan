<?php

namespace App\Http\Controllers;

use App\Models\Paramedis;
use Illuminate\Http\Request;

class ParamedisController
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Paramedis::query();
        if ($request->has('cari')) {
            $query->where('nama_paramedis', 'like', '%' . $request->cari . '%');
        }
        $dataParamedis = $query->paginate(5);

        return view('data_master.paramedis_index', compact('dataParamedis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paramedis' => 'required|string|max:100|unique:paramedis,nama_paramedis',
            'no_hp'          => 'nullable|string|max:20|unique:paramedis,no_hp',
            'alamat'         => 'nullable|string',
        ], [
            'nama_paramedis.unique' => 'Nama paramedis ini sudah terdaftar di sistem.',
            'no_hp.unique'          => 'Nomor HP ini sudah digunakan.'
        ]);

        Paramedis::create($request->all());
        return redirect()->route('paramedis.index')->with('success', 'Data paramedis berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paramedis' => 'required|string|max:100|unique:paramedis,nama_paramedis,' . $id . ',id_paramedis',
            'no_hp'          => 'nullable|string|max:20|unique:paramedis,no_hp,' . $id . ',id_paramedis',
            'alamat'         => 'nullable|string',
        ], [
            'nama_paramedis.unique' => 'Nama paramedis ini sudah terdaftar di sistem.',
            'no_hp.unique'          => 'Nomor HP ini sudah digunakan.'
        ]);

        $paramedis = Paramedis::findOrFail($id);
        $paramedis->update($request->all());
        
        return redirect()->route('paramedis.index')->with('success', 'Data paramedis berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $paramedis = Paramedis::findOrFail($id);
        $paramedis->delete();
        
        return redirect()->route('paramedis.index')->with('success', 'Data paramedis berhasil dihapus!');
    }
}