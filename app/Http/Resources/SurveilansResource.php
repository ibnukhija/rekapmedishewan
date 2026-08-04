<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveilansResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'periode' => [
                'bulan' => (int) $request->input('periode', 6),
                'diperbarui_pada' => now()->toIso8601String(),
            ],
            'ringkasan' => $this->resource['ringkasan'],
            'matriks_jenis_diagnosa' => $this->resource['matrix'],
            'tren_bulanan_vaksin' => $this->resource['trend'],
            'ringkasan_per_jenis' => $this->resource['jenisBreakdown'],
            'grafik_top5_per_jenis' => $this->resource['chartData'],
        ];
    }
}