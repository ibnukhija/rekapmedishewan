<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveilansController extends Controller
{
    public function index(Request $request)
    {
        $daerah  = $request->input('daerah', 'semua');
        $jenis   = $request->input('jenis', 'semua');
        $periode = (int) $request->input('periode', 6);

        $mulai = Carbon::now()->subMonths(max($periode - 1, 0))->startOfMonth();

        $base = DB::table('rekam_medis as rm')
            ->join('hewan as h', 'rm.id_hewan', '=', 'h.id_hewan')
            ->join('jenis_hewan as jh', 'h.id_jenis', '=', 'jh.id_jenis')
            ->join('pemilik as p', 'h.id_pemilik', '=', 'p.id_pemilik')
            ->join('diagnosa as d', 'rm.id_diagnosa', '=', 'd.id_diagnosa')
            ->whereNotNull('rm.id_diagnosa')
            ->where('d.nama_diagnosa', '!=', 'Sehat') // Kecualikan diagnosa "Sehat" dari data surveilans
            ->where('rm.tanggal', '>=', $mulai);

        if ($daerah !== 'semua') {
            $base->where('p.alamat', $daerah);
        }
        if ($jenis !== 'semua') {
            $base->where('jh.nama_jenis', $jenis);
        }

        $rows = $base->select(
            'rm.tanggal',
            'p.alamat as daerah',
            'jh.nama_jenis as jenis',
            'd.nama_diagnosa as diagnosa',
            'd.perlu_vaksin'
        )->get();

        // Matriks jenis hewan x diagnosa
        $matrix = $rows->groupBy(fn ($r) => $r->jenis.'|'.$r->diagnosa)
            ->map(fn ($g) => [
                'jenis'    => $g->first()->jenis,
                'diagnosa' => $g->first()->diagnosa,
                'count'    => $g->count(),
                'vaksin'   => (bool) $g->first()->perlu_vaksin,
            ])->values();

        // Tren bulanan, khusus diagnosa yang bisa dicegah vaksin
        $trend = $rows->where('perlu_vaksin', 1)
            ->groupBy(fn ($r) => Carbon::parse($r->tanggal)->format('Y-m'))
            ->map->count()
            ->sortKeys();

        $ringkasan = [
            'total'            => $rows->count(),
            'perlu_vaksin'     => $rows->where('perlu_vaksin', 1)->count(),
            'daerah_terdampak' => $rows->pluck('daerah')->unique()->count(),
            'kombinasi_tinggi' => $matrix->where('count', '>=', 10)->count(),
        ];

        $rekamTerbaru = $rows->sortByDesc('tanggal')->take(10)->values();

        // ==== Ringkasan kasus tertinggi per jenis hewan ====
        $jenisBreakdown = $rows->groupBy('jenis')->map(function ($group, $jenisNama) {
            $total = $group->count();

            $countPerDiagnosa = $group->groupBy('diagnosa')
                ->map(fn ($g) => $g->count())
                ->sortDesc();

            $diagnosaTertinggi = $countPerDiagnosa->keys()->first();
            $jumlahTertinggi   = $countPerDiagnosa->first() ?? 0;

            $kasusTertinggi = $diagnosaTertinggi
                ? $group->where('diagnosa', $diagnosaTertinggi)
                : collect();

            $dariKota = $kasusTertinggi->filter(
                fn ($r) => str_contains($r->daerah, 'Kota Kediri')
            );

            $asalKota = $dariKota->count();
            $asalLuar = $kasusTertinggi->count() - $asalKota;

            $kelurahanTerdampak = $dariKota->map(function ($r) {
                if (preg_match('/Kelurahan\s+([^,]+),/i', $r->daerah, $m)) {
                    return trim($m[1]);
                }
                return trim($r->daerah);
            })->unique()->count();

            return [
                'jenis'               => $jenisNama,
                'total'               => $total,
                'diagnosa_tertinggi'  => $diagnosaTertinggi,
                'jumlah_tertinggi'    => $jumlahTertinggi,
                'asal_kota'           => $asalKota,
                'kelurahan_terdampak' => $kelurahanTerdampak,
                'asal_luar'           => $asalLuar,
            ];
        })->sortByDesc('total')->values();

        // ==== Data diagram batang: top 5 diagnosa + "Lain-lain" per jenis hewan ====
        $chartData = $rows->groupBy('jenis')->map(function ($group) {
            $counts = $group->groupBy('diagnosa')
                ->map(fn ($g) => $g->count())
                ->sortDesc();

            $top5 = $counts->take(5);
            $sisa = $counts->slice(5)->sum();

            $labels = $top5->keys()->values()->toArray();
            $data   = $top5->values()->toArray();

            if ($sisa > 0) {
                $labels[] = 'Lain-lain';
                $data[]   = $sisa;
            }

            return ['labels' => $labels, 'data' => $data];
        });

        $daftarDaerah = DB::table('pemilik')->whereNotNull('alamat')->distinct()->orderBy('alamat')->pluck('alamat');
        $daftarJenis  = DB::table('jenis_hewan')->orderBy('nama_jenis')->pluck('nama_jenis');

        return view('surveilans.index', compact(
            'matrix', 'trend', 'ringkasan', 'rekamTerbaru',
            'jenisBreakdown', 'chartData',
            'daftarDaerah', 'daftarJenis', 'daerah', 'jenis', 'periode'
        ));
    }
}