<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveilansResource;
use App\Services\SurveilansService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SurveilansApiController extends Controller
{
    public function __construct(protected SurveilansService $service) {}

    public function index(Request $request)
    {
        $daerah  = $request->input('daerah', 'semua');
        $jenis   = $request->input('jenis', 'semua');
        $periode = (int) $request->input('periode', 6);

        // Cache 15 menit, biar dashboard publik tidak menghajar DB tiap kali refresh
        $cacheKey = "surveilans_api_{$daerah}_{$jenis}_{$periode}";

        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($daerah, $jenis, $periode) {
            return $this->service->getData($daerah, $jenis, $periode);
        });

        return (new SurveilansResource($data))
            ->additional(['status' => 'success'])
            ->response()
            ->header('Access-Control-Allow-Origin', '*'); // atau atur lewat CORS config, lihat langkah 5
    }
}