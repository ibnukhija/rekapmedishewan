<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paramedis extends Model
{
    protected $table = 'paramedis';
    protected $primaryKey = 'id_paramedis';

    protected $fillable = [
        'nama_paramedis', 'alamat', 'no_hp'
    ];

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_paramedis', 'id_paramedis');
    }
}