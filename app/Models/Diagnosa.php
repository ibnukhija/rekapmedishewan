<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $table = 'diagnosa';
    protected $primaryKey = 'id_diagnosa';

    protected $fillable = [
        'nama_diagnosa'
    ];

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_diagnosa', 'id_diagnosa');
    }
}