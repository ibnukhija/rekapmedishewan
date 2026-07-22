<?php

namespace App\Http\Controllers;

use App\Models\Anamnesa;
use Illuminate\Http\Request;

class AnamnesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anamnesa::query();

        if ($request->filled('cari')) {
            $query->where('nama_anamnesa', 'like', '%' . $request->cari . '%');
        }

        $anamnesas = $query->orderBy('nama_anamnesa', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('data_master.anamnesa', compact('anamnesas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_anamnesa' => 'required|string|max:150|unique:anamnesa,nama_anamnesa',
        ], [
            'nama_anamnesa.unique' => 'Nama anamnesa ini sudah terdaftar di sistem.',
        ]);

        Anamnesa::create($validated);

        return redirect()->route('anamnesa.index')
            ->with('success', 'Data anamnesa berhasil ditambahkan.');
    }

    public function update(Request $request, $id_anamnesa)
    {
        $anamnesa = Anamnesa::findOrFail($id_anamnesa);

        $validated = $request->validate([
            'nama_anamnesa' => 'required|string|max:150|unique:anamnesa,nama_anamnesa,' . $id_anamnesa . ',id_anamnesa',
        ], [
            'nama_anamnesa.unique' => 'Nama anamnesa ini sudah terdaftar di sistem.',
        ]);

        $anamnesa->update($validated);

        return redirect()->route('anamnesa.index')
            ->with('success', 'Data anamnesa berhasil diperbarui.');
    }

    public function destroy($id_anamnesa)
    {
        $anamnesa = Anamnesa::findOrFail($id_anamnesa);
        $anamnesa->delete();

        return redirect()->route('anamnesa.index')
            ->with('success', 'Data anamnesa berhasil dihapus.');
    }
}
