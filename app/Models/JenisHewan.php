<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'jenis_hewan';

    /**
     * Primary key custom (bukan default 'id').
     */
    protected $primaryKey = 'id_jenis';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'nama_jenis',
    ];

    /**
     * Relasi ke tabel hewan (1 jenis hewan bisa dimiliki banyak hewan).
     * Sesuaikan nama foreign key 'id_jenis' di tabel hewan jika berbeda.
     */
    public function hewans()
    {
        return $this->hasMany(Hewan::class, 'id_jenis', 'id_jenis');
    }
}