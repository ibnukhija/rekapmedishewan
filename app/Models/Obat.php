<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat'
    ];

    // Relasi Many-to-Many balikan ke Rekam Medis
    public function rekamMedis()
    {
        return $this->belongsToMany(RekamMedis::class, 'rekam_medis_obat', 'id_obat', 'id_rekam');
    }
}