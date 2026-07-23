<?php

namespace Database\Seeders;

use App\Models\Diagnosa;
use Illuminate\Database\Seeder;

class DiagnosaSeeder extends Seeder
{
    public function run()
    {
        $diagnosas = [
            'Feline Panleukopenia (FPV)', 'Feline Calicivirus (FCV)', 
            'Feline Viral Rhinotracheitis (FVR)', 'Gastritis', 'Enteritis', 
            'Abses', 'Traumatik', 'FLUTD (Feline Lower Urinary Tract Disease)', 
            'Infeksi Saluran Kemih (ISK)', 'Scabies', 'Dermatitis', 
            'Endoparasitosis', 'Ektoparasitosis', 'Sehat', 'Anoreksia', 'Vomit', 'Vulnus', 'Flucat', 'Suspek', 'Dermatitis', 'Mite', 'Ring worm'
        ];

        foreach ($diagnosas as $diagnosa) {
            Diagnosa::create(['nama_diagnosa' => $diagnosa]);
        }
    }
}