<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Hewan;
use App\Models\RekamMedis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDokter = Dokter::count();
        $totalRegistrasiSatwa = Hewan::count();
        $totalKunjungan = RekamMedis::count();
        $kunjunganHariIni = RekamMedis::whereDate('tanggal', Carbon::today())->count();

        $totalRetribusiHariIni = RekamMedis::whereDate('tanggal', Carbon::today())
            ->with('pelayanan')
            ->get()
            ->sum(fn ($item) => $item->pelayanan?->tarif ?? 0);

        return view('dashboard', compact(
            'totalDokter',
            'totalRegistrasiSatwa',
            'totalKunjungan',
            'kunjunganHariIni',
            'totalRetribusiHariIni'
        ));
    }
}