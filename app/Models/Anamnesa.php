<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anamnesa extends Model
{
    protected $table = 'anamnesa';
    protected $primaryKey = 'id_anamnesa';

    protected $fillable = [
        'nama_anamnesa'
    ];

    // Relasi Many-to-Many balikan ke Rekam Medis
    public function rekamMedis()
    {
        return $this->belongsToMany(RekamMedis::class, 'rekam_medis_anamnesa', 'id_anamnesa', 'id_rekam');
    }
}