<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\JenisHewan;
use Illuminate\Http\Request;

class PelayananController extends Controller
{
    /**
     * Menampilkan daftar pelayanan, dilengkapi fitur pencarian & pagination.
     */
    public function index(Request $request)
    {
        $cari = $request->input('cari');

        $pelayanans = Pelayanan::with('jenisHewan')
            ->when($cari, function ($query, $cari) {
                return $query->where('nama_pelayanan', 'like', "%{$cari}%");
            })
            ->orderBy('nama_pelayanan', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Untuk dropdown pilihan jenis hewan di form modal
        $jenisHewans = JenisHewan::orderBy('nama_jenis')->get();

        return view('data_master.pelayanan', compact('pelayanans', 'jenisHewans'));
    }

    /**
     * Menyimpan data pelayanan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelayanan' => 'required|string|max:100',
            'id_jenis'       => 'nullable|exists:jenis_hewan,id_jenis',
            'jenis_kelamin' => 'nullable|in:Jantan,Betina',
            'tarif'          => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string|max:255',
        ], [
            'nama_pelayanan.required' => 'Nama pelayanan wajib diisi.',
            'tarif.required'          => 'Tarif wajib diisi.',
            'tarif.numeric'           => 'Tarif harus berupa angka.',
        ]);

        // Cegah duplikat kombinasi nama + jenis hewan + jenis kelamin
        $exists = Pelayanan::where('nama_pelayanan', $validated['nama_pelayanan'])
            ->where('id_jenis', $validated['id_jenis'] ?? null)
            ->where('jenis_kelamin', $validated['jenis_kelamin'] ?? null)
            ->exists();

        if ($exists) {
            return redirect()->route('pelayanan.index')
                ->with('error', 'Kombinasi pelayanan, jenis hewan, dan jenis kelamin ini sudah terdaftar.');
        }

        Pelayanan::create($validated);

        return redirect()->route('pelayanan.index')
            ->with('success', 'Data pelayanan berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pelayanan yang sudah ada.
     */
    public function update(Request $request, $id_pelayanan)
    {
        $pelayanan = Pelayanan::findOrFail($id_pelayanan);

        $validated = $request->validate([
            'nama_pelayanan' => 'required|string|max:100',
            'id_jenis'       => 'nullable|exists:jenis_hewan,id_jenis',
            'jenis_kelamin' => 'nullable|in:Jantan,Betina',
            'tarif'          => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string|max:255',
        ], [
            'nama_pelayanan.required' => 'Nama pelayanan wajib diisi.',
            'tarif.required'          => 'Tarif wajib diisi.',
            'tarif.numeric'           => 'Tarif harus berupa angka.',
        ]);

        $exists = Pelayanan::where('nama_pelayanan', $validated['nama_pelayanan'])
            ->where('id_jenis', $validated['id_jenis'] ?? null)
            ->where('jenis_kelamin', $validated['jenis_kelamin'] ?? null)
            ->where('id_pelayanan', '!=', $id_pelayanan)
            ->exists();

        if ($exists) {
            return redirect()->route('pelayanan.index')
                ->with('error', 'Kombinasi pelayanan, jenis hewan, dan jenis kelamin ini sudah terdaftar.');
        }

        $pelayanan->update($validated);

        return redirect()->route('pelayanan.index')
            ->with('success', 'Data pelayanan berhasil diperbarui.');
    }

    /**
     * Menghapus data pelayanan.
     */
    public function destroy($id_pelayanan)
    {
        $pelayanan = Pelayanan::findOrFail($id_pelayanan);
        $pelayanan->delete();

        return redirect()->route('pelayanan.index')
            ->with('success', 'Data pelayanan berhasil dihapus.');
    }
}