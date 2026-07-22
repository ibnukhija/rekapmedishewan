<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;

    protected $table = 'pelayanan';
    protected $primaryKey = 'id_pelayanan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_pelayanan',
        'id_jenis',
        'jenis_kelamin',
        'tarif',
        'keterangan',
    ];

    /**
     * Relasi ke jenis hewan (nullable — null berarti berlaku untuk semua jenis hewan).
     */
    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'id_jenis', 'id_jenis');
    }
}