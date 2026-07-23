<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $obats = [
            'Amoxicillin', 'Ivermectin', 'Enrofloxacin', 'Dexamethasone', 
            'B-Plex', 'Vitamin A, D, C (ADC)', 'Ranitidine', 'Bio ATP', 
            'Vetadryl', 'Tolfenamic Acid (Tolfen)', 'Intertrim LA', 
            'Atropine Sulfate', 'Xylazine', 'Ketamine', 'Lidocaine', 
            'Obat tetes mata', 'Obat tetes telinga', 'Depo Progestin', 
            'Ringer Lactate (RL)'
        ];

        foreach ($obats as $obat) {
            Obat::create(['nama_obat' => $obat]);
        }
    }
}