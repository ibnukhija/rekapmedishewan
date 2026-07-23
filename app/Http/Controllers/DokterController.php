<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Pencarian & Pagination
        $query = Dokter::query();
        if ($request->has('cari')) {
            $query->where('nama_dokter', 'like', '%' . $request->cari . '%');
        }
        $dokters = $query->orderBy('nama_dokter', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view('data_master.dokter_index', compact('dokters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
        ], [
            'nama_dokter.required' => 'Nama dokter wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Dokter
        if (Dokter::where('nama_dokter', $validated['nama_dokter'])->exists()) {
            return redirect()->back()
                ->with('error', 'Nama dokter ini sudah terdaftar di sistem.');
        }

        // Pengecekan manual untuk duplikat No HP (jika diisi)
        if (!empty($validated['no_hp']) && Dokter::where('no_hp', $validated['no_hp'])->exists()) {
            return redirect()->back()
                ->with('error', 'Nomor HP ini sudah digunakan.');
        }

        Dokter::create($validated);
        
        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
        ], [
            'nama_dokter.required' => 'Nama dokter wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Dokter (kecuali milik sendiri)
        if (Dokter::where('nama_dokter', $validated['nama_dokter'])->where('id_dokter', '!=', $id)->exists()) {
            return redirect()->back()
                ->with('error', 'Nama dokter ini sudah terdaftar di sistem.');
        }

        // Pengecekan manual untuk duplikat No HP (kecuali milik sendiri)
        if (!empty($validated['no_hp']) && Dokter::where('no_hp', $validated['no_hp'])->where('id_dokter', '!=', $id)->exists()) {
            return redirect()->back()
                ->with('error', 'Nomor HP ini sudah digunakan.');
        }
        
        $dokter->update($validated);
        
        return redirect()->back()
            ->with('success', 'Data dokter berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();
        
        return redirect()->back()
            ->with('success', 'Data dokter berhasil dihapus!');
    }
}