<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    protected $table = 'jenis_hewan';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'nama_jenis'
    ];

    public function hewans()
    {
        return $this->hasMany(Hewan::class, 'id_jenis', 'id_jenis');
    }
}