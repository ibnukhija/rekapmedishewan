<?php

namespace App\Http\Controllers;

use App\Models\JenisHewan;
use Illuminate\Http\Request;

class JenisHewanController extends Controller
{
    /**
     * Menampilkan daftar jenis hewan, dilengkapi fitur pencarian & pagination.
     */
    public function index(Request $request)
    {
        $cari = $request->input('cari');

        $jenisHewans = JenisHewan::when($cari, function ($query, $cari) {
                return $query->where('nama_jenis', 'like', "%{$cari}%");
            })
            ->orderBy('nama_jenis', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('data_master.jenis_hewan', compact('jenisHewans'));
    }

    /**
     * Menyimpan data jenis hewan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis hewan wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Jenis Hewan
        if (JenisHewan::where('nama_jenis', $validated['nama_jenis'])->exists()) {
            return redirect()->route('jenis_hewan.index')
                ->with('error', 'Jenis hewan ini sudah terdaftar.');
        }

        JenisHewan::create($validated);

        return redirect()->route('jenis_hewan.index')
            ->with('success', 'Data jenis hewan berhasil ditambahkan.');
    }

    /**
     * Memperbarui data jenis hewan yang sudah ada.
     */
    public function update(Request $request, $id_jenis)
    {
        $jenisHewan = JenisHewan::findOrFail($id_jenis);

        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis hewan wajib diisi.',
        ]);

        // Pengecekan manual untuk duplikat Nama Jenis Hewan (kecuali milik sendiri)
        if (JenisHewan::where('nama_jenis', $validated['nama_jenis'])->where('id_jenis', '!=', $id_jenis)->exists()) {
            return redirect()->route('jenis_hewan.index')
                ->with('error', 'Jenis hewan ini sudah terdaftar.');
        }

        $jenisHewan->update($validated);

        return redirect()->route('jenis_hewan.index')
            ->with('success', 'Data jenis hewan berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis hewan.
     */
    public function destroy($id_jenis)
    {
        $jenisHewan = JenisHewan::findOrFail($id_jenis);

        // Pengaman: cegah hapus jika jenis hewan masih dipakai oleh data hewan.
        if (method_exists($jenisHewan, 'hewans') && $jenisHewan->hewans()->exists()) {
            return redirect()->route('jenis_hewan.index')
                ->with('error', 'Jenis hewan tidak bisa dihapus karena masih digunakan pada data hewan.');
        }

        $jenisHewan->delete();

        return redirect()->route('jenis_hewan.index')
            ->with('success', 'Data jenis hewan berhasil dihapus.');
    }
}