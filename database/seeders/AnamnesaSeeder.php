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
            'Demam', 'Gatal', 'Keluar cairan dari telinga', 'Retensi urin'
        ];

        foreach ($anamnesas as $anamnesa) {
            Anamnesa::create(['nama_anamnesa' => $anamnesa]);
        }
    }
}