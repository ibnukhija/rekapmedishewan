<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'nama_pemilik', 'alamat', 'no_hp'
    ];

    // Relasi ke Hewan (1 Pemilik punya Banyak Hewan)
    public function hewans()
    {
        return $this->hasMany(Hewan::class, 'id_pemilik', 'id_pemilik');
    }
}