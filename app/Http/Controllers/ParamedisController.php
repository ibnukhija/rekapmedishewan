<?php

namespace App\Http\Controllers;

use App\Models\Paramedis;
use Illuminate\Http\Request;

class ParamedisController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Paramedis::query();
        if ($request->has('cari')) {
            $query->where('nama_paramedis', 'like', '%' . $request->cari . '%');
        }
        $dataParamedis = $query->orderBy('nama_paramedis', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view('data_master.paramedis_index', compact('dataParamedis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paramedis' => 'required|string|max:100',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ], [
            'nama_paramedis.required' => 'Nama paramedis wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Paramedis
        if (Paramedis::where('nama_paramedis', $validated['nama_paramedis'])->exists()) {
            return redirect()->back()
                ->with('error', 'Nama paramedis ini sudah terdaftar di sistem.');
        }

        // Pengecekan manual untuk duplikat No HP (jika diisi)
        if (!empty($validated['no_hp']) && Paramedis::where('no_hp', $validated['no_hp'])->exists()) {
            return redirect()->back()
                ->with('error', 'Nomor HP ini sudah digunakan.');
        }

        Paramedis::create($validated);
        
        return redirect()->back()
            ->with('success', 'Data paramedis berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $paramedis = Paramedis::findOrFail($id);

        $validated = $request->validate([
            'nama_paramedis' => 'required|string|max:100',
            'no_hp'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ], [
            'nama_paramedis.required' => 'Nama paramedis wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Paramedis (kecuali milik sendiri)
        if (Paramedis::where('nama_paramedis', $validated['nama_paramedis'])->where('id_paramedis', '!=', $id)->exists()) {
            return redirect()->back()
                ->with('error', 'Nama paramedis ini sudah terdaftar di sistem.');
        }

        // Pengecekan manual untuk duplikat No HP (kecuali milik sendiri)
        if (!empty($validated['no_hp']) && Paramedis::where('no_hp', $validated['no_hp'])->where('id_paramedis', '!=', $id)->exists()) {
            return redirect()->back()
                ->with('error', 'Nomor HP ini sudah digunakan.');
        }

        $paramedis->update($validated);
        
        return redirect()->back()
            ->with('success', 'Data paramedis berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $paramedis = Paramedis::findOrFail($id);
        $paramedis->delete();
        
        return redirect()->back()
            ->with('success', 'Data paramedis berhasil dihapus!');
    }
}