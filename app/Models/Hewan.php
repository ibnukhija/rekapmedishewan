<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hewan extends Model
{
    protected $table = 'hewan';
    protected $primaryKey = 'id_hewan';

    protected $fillable = [
        'id_pemilik', 'id_jenis', 'nama_hewan', 'jenis_kelamin', 'umur', 'warna'
    ];

    // BelongsTo (Banyak Hewan dimiliki 1 Pemilik)
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'id_pemilik', 'id_pemilik');
    }

    // BelongsTo (Banyak Hewan memiliki 1 Jenis)
    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'id_jenis', 'id_jenis');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_hewan', 'id_hewan');
    }
}