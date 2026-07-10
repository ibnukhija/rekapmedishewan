<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';

    protected $fillable = [
        'nama_dokter', 'alamat', 'no_hp'
    ];

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_dokter', 'id_dokter');
    }
}