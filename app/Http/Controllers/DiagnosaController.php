<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;

class DiagnosaController
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Diagnosa::query();
        if ($request->has('cari')) {
            $query->where('nama_diagnosa', 'like', '%' . $request->cari . '%');
        }
        $dataDiagnosa = $query->paginate(5);

        return view('data_master.diagnosa_index', compact('dataDiagnosa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_diagnosa' => 'required|string|max:100|unique:diagnosa,nama_diagnosa'
        ], [
            'nama_diagnosa.unique' => 'Nama diagnosa ini sudah terdaftar di sistem.'
        ]);

        Diagnosa::create($request->all());
        return redirect()->route('diagnosa.index')->with('success', 'Data diagnosa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_diagnosa' => 'required|string|max:100|unique:diagnosa,nama_diagnosa,' . $id . ',id_diagnosa'
        ], [
            'nama_diagnosa.unique' => 'Nama diagnosa ini sudah terdaftar di sistem.'
        ]);

        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->update($request->all());
        
        return redirect()->route('diagnosa.index')->with('success', 'Data diagnosa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->delete();
        
        return redirect()->route('diagnosa.index')->with('success', 'Data diagnosa berhasil dihapus!');
    }
}
