<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('cari')) {
            $query->where('nama_obat', 'like', '%' . $request->cari . '%');
        }

        $obats = $query->orderBy('nama_obat', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('data_master.obat_index', compact('obats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Obat
        if (Obat::where('nama_obat', $validated['nama_obat'])->exists()) {
            return redirect()->route('obat.index')
                ->with('error', 'Nama obat ini sudah terdaftar di sistem.');
        }

        Obat::create($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function update(Request $request, $id_obat)
    {
        $obat = Obat::findOrFail($id_obat);

        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Obat (kecuali milik sendiri)
        if (Obat::where('nama_obat', $validated['nama_obat'])->where('id_obat', '!=', $id_obat)->exists()) {
            return redirect()->route('obat.index')
                ->with('error', 'Nama obat ini sudah terdaftar di sistem.');
        }

        $obat->update($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy($id_obat)
    {
        $obat = Obat::findOrFail($id_obat);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil dihapus.');
    }
}