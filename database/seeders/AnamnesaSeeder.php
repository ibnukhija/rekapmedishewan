<?php

namespace Database\Seeders;

use App\Models\Anamnesa;
use Illuminate\Database\Seeder;

class AnamnesaSeeder extends Seeder
{
    public function run()
    {
        $anamnesas = [
            'Tidak mau makan', 'Tidak mau minum', 'Muntah', 'Diare', 
            'Demam', 'Gatal', 'Keluar cairan dari telinga', 'Retensi urin', 'Kutuan', 'Luka', 'Steril', 'Bulu rontok', 'Cek kesehatan', 'Geetar', 'Panas', 'Vaksin', 'Kastrasi', 'Pipis darah', 'Tidak bisa jalan', 'Tidak bisa kencing', 'Makan sedikit', 'Tenggorokan bengkak', 'Kumis tumbuh patah-patah', 'Bulu rontok', 'Keluar cairan dari telinga', 'Luka', 'Retensi urin',
        ];

        foreach ($anamnesas as $anamnesa) {
            Anamnesa::create(['nama_anamnesa' => $anamnesa]);
        }
    }
}