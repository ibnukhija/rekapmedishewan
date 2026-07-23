<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;

class DiagnosaController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Diagnosa::query();
        if ($request->has('cari')) {
            $query->where('nama_diagnosa', 'like', '%' . $request->cari . '%');
        }
        $dataDiagnosa = $query->paginate(10);

        return view('data_master.diagnosa_index', compact('dataDiagnosa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_diagnosa' => 'required|string|max:100'
        ], [
            'nama_diagnosa.required' => 'Nama diagnosa wajib diisi.'
        ]);

        // Pengecekan manual untuk duplikat Nama Diagnosa
        if (Diagnosa::where('nama_diagnosa', $validated['nama_diagnosa'])->exists()) {
            return redirect()->route('diagnosa.index')
                ->with('error', 'Nama diagnosa ini sudah terdaftar di sistem.');
        }

        Diagnosa::create($validated);
        
        return redirect()->route('diagnosa.index')
            ->with('success', 'Data diagnosa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $diagnosa = Diagnosa::findOrFail($id);

        $validated = $request->validate([
            'nama_diagnosa' => 'required|string|max:100'
        ], [
            'nama_diagnosa.required' => 'Nama diagnosa wajib diisi.'
        ]);

        // Pengecekan manual untuk duplikat Nama Diagnosa (kecuali milik sendiri)
        if (Diagnosa::where('nama_diagnosa', $validated['nama_diagnosa'])->where('id_diagnosa', '!=', $id)->exists()) {
            return redirect()->route('diagnosa.index')
                ->with('error', 'Nama diagnosa ini sudah terdaftar di sistem.');
        }

        $diagnosa->update($validated);
        
        return redirect()->route('diagnosa.index')
            ->with('success', 'Data diagnosa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->delete();
        
        return redirect()->route('diagnosa.index')
            ->with('success', 'Data diagnosa berhasil dihapus!');
    }
}