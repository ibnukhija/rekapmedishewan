<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    protected $table = 'pelayanan';
    protected $primaryKey = 'id_pelayanan';

    protected $fillable = [
        'nama_pelayanan', 'tarif', 'keterangan'
    ];

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_pelayanan', 'id_pelayanan');
    }
}