<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemilik;
use App\Models\Hewan;
use App\Models\RekamMedis;
use App\Models\Dokter;
use App\Models\Paramedis;
use App\Models\Pelayanan;
use App\Models\JenisHewan;
use App\Models\Diagnosa;
use App\Models\Anamnesa;
use App\Models\Obat;

class RekamMedisController extends Controller
{
    /**
     * Tampilkan halaman Form Input Rekam Medis
     */
    public function index()
    {
        $dokters = Dokter::all();
        $paramedis = Paramedis::all();
        $pelayanans = Pelayanan::all();
        $jenisHewans = JenisHewan::all();
        $anamnesas = Anamnesa::all();
        $diagnosas = Diagnosa::all(); 
        $obats = Obat::all();
        
        return view('rekam_medis.input', compact(
            'dokters', 
            'paramedis', 
            'pelayanans', 
            'jenisHewans', 
            'anamnesas', 
            'diagnosas', 
            'obats'
        ));
    }

    /**
     * Endpoint API AJAX untuk Live Search Pasien & Pemilik
     */
    public function search(Request $request)
    {
        $q = $request->q;

        if (!$q || strlen($q) < 2) {
            return response()->json(['hewans' => [], 'pemiliks' => []]);
        }

        // Cari Hewan: cocokkan nama/ID hewan ITU SENDIRI, atau lewat data pemiliknya
        $hewans = Hewan::with('pemilik')
            ->where(function ($query) use ($q) {
                $query->where('nama_hewan', 'like', "%$q%")
                    ->orWhere('id_hewan', 'like', "%$q%")
                    ->orWhereHas('pemilik', function ($sub) use ($q) {
                        $sub->where('nama_pemilik', 'like', "%$q%")
                            ->orWhere('no_hp', 'like', "%$q%");
                    });
            })
            ->limit(10)
            ->get();

        // Cari Pemilik berserta relasi Hewan-hewannya
        $pemiliks = Pemilik::with('hewans')
            ->where('nama_pemilik', 'like', "%$q%")
            ->orWhere('no_hp', 'like', "%$q%")
            ->limit(10)
            ->get();

        return response()->json([
            'hewans' => $hewans,
            'pemiliks' => $pemiliks
        ]);
    }

    /**
     * Proses Simpan Data Transaksi (Create/Update berjenjang)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pemilik' => 'required|string|max:100',
            'nama_hewan' => 'required|string|max:100',
            'jenis_hewan' => 'required',
            'jenis_kelamin' => 'required',
            'pelayanan' => 'required',
            'dokter' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan/Update Pemilik
            if ($request->id_pemilik) {
                $pemilik = Pemilik::findOrFail($request->id_pemilik);
                $pemilik->update([
                    'nama_pemilik' => $request->nama_pemilik,
                    'no_hp' => $request->no_hp_pemilik ?? '-',
                    'alamat' => $request->alamat ?? '-'
                ]);
            } else {
                $pemilik = Pemilik::create([
                    'nama_pemilik' => $request->nama_pemilik,
                    'no_hp' => $request->no_hp_pemilik ?? '-',
                    'alamat' => $request->alamat ?? '-'
                ]);
            }

            $umur = (int) preg_replace('/[^0-9]/', '', $request->umur_hewan ?? '0');

            // 2. Simpan/Update Hewan
            if ($request->id_hewan) {
                $hewan = Hewan::findOrFail($request->id_hewan);
                $hewan->update([
                    'id_jenis' => $request->jenis_hewan,
                    'nama_hewan' => $request->nama_hewan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'umur' => $umur,
                    'warna' => $request->warna_hewan ?? '-'
                ]);
            } else {
                $hewan = Hewan::create([
                    'id_pemilik' => $pemilik->id_pemilik,
                    'id_jenis' => $request->jenis_hewan,
                    'nama_hewan' => $request->nama_hewan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'umur' => $umur,
                    'warna' => $request->warna_hewan ?? '-'
                ]);
            }

            // 3. Ambil Diagnosa Pertama yang dipilih
            $id_diagnosa = null;
            if ($request->has('diagnosa') && is_array($request->diagnosa) && count($request->diagnosa) > 0) {
                $id_diagnosa = $request->diagnosa[0]; 
            }

            // 4. Simpan ke Rekam Medis
            $rekamMedis = RekamMedis::create([
                'id_hewan' => $hewan->id_hewan,
                'id_dokter' => $request->dokter,
                'id_paramedis' => $request->paramedis,
                'id_pelayanan' => $request->pelayanan,
                'id_diagnosa' => $id_diagnosa,
                'tanggal' => $request->tanggal,
                'no_karcis' => $request->no_karcis ?? '-'
            ]);

            // 5. Simpan ke Tabel Pivot (Anamnesa & Obat)
            if ($request->has('anamnesa') && is_array($request->anamnesa)) {
                $rekamMedis->anamnesas()->attach($request->anamnesa);
            }

            if ($request->has('terapi') && is_array($request->terapi)) {
                $rekamMedis->obats()->attach($request->terapi);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data Rekam Medis berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}