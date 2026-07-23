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
use App\Exports\RekapLaporanExport;
use Maatwebsite\Excel\Facades\Excel;
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

            // 3. Ambil Diagnosa yang dipilih
            $id_diagnosa = null;
            if ($request->filled('diagnosa')) {
                if (is_array($request->diagnosa) && count($request->diagnosa) > 0) {
                    $id_diagnosa = $request->diagnosa[0];
                } else {
                    $id_diagnosa = $request->diagnosa;
                }
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

    public function rekapLaporan(Request $request)
    {
        $dokters = Dokter::all();
        $jenisHewans = JenisHewan::all();
        $pelayanans = Pelayanan::with('jenisHewan')
            ->orderBy('nama_pelayanan')
            ->orderBy('id_jenis')
            ->orderBy('jenis_kelamin')
            ->get();
        $diagnosas = Diagnosa::all();
        $anamnesas = Anamnesa::all();

        $query = RekamMedis::with([
            'hewan.pemilik',
            'hewan.jenisHewan',
            'dokter',
            'paramedis',
            'pelayanan',
            'diagnosa',
            'anamnesas',
            'obats',
        ]);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('no_karcis', 'like', "%{$search}%")
                    ->orWhereHas('hewan', function ($sub) use ($search) {
                        $sub->where('nama_hewan', 'like', "%{$search}%")
                            ->orWhere('jenis_kelamin', 'like', "%{$search}%")
                            ->orWhereHas('pemilik', function ($sub2) use ($search) {
                                $sub2->where('nama_pemilik', 'like', "%{$search}%")
                                    ->orWhere('alamat', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('diagnosa', function ($sub) use ($search) {
                        $sub->where('nama_diagnosa', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pelayanan', function ($sub) use ($search) {
                        $sub->where('nama_pelayanan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('dokter', function ($sub) use ($search) {
                        $sub->where('nama_dokter', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('dokter')) {
            $query->where('id_dokter', $request->dokter);
        }

        if ($request->filled('jenis_hewan')) {
            $query->whereHas('hewan', function ($sub) use ($request) {
                $sub->where('id_jenis', $request->jenis_hewan);
            });
        }

        if ($request->filled('pelayanan')) {
            $query->where('id_pelayanan', $request->pelayanan);
        }

        if ($request->filled('diagnosa')) {
            $query->where('id_diagnosa', $request->diagnosa);
        }

        if ($request->filled('anamnesa')) {
            $query->whereHas('anamnesas', function ($sub) use ($request) {
                $sub->where('anamnesa.id_anamnesa', $request->anamnesa);
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('hewan', function ($sub) use ($request) {
                $sub->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $rekapData = $query->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $minYear = RekamMedis::query()
            ->selectRaw('MIN(YEAR(tanggal)) as year')
            ->value('year');

        $minYear = $minYear ? (int) $minYear : now()->year;
        $years = range(now()->year, $minYear);

        return view('data_master.rekap_laporan', compact(
            'rekapData',
            'dokters',
            'jenisHewans',
            'pelayanans',
            'diagnosas',
            'anamnesas',
            'years'
        ));
    }

    public function exportRekapLaporan(Request $request)
    {
        return Excel::download(new RekapLaporanExport($request->all()), 'rekap-laporan-' . now()->format('Ymd_His') . '.xlsx');
    }
}
