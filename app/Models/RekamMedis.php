<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'id_rekam';

    protected $fillable = [
        'id_hewan', 
        'id_dokter', 
        'id_paramedis', 
        'id_pelayanan', 
        'id_diagnosa', 
        'tanggal', 
        'no_karcis'
    ];

    // --- RELASI ONE TO MANY (BelongsTo) ---
    public function hewan() {
        return $this->belongsTo(Hewan::class, 'id_hewan', 'id_hewan');
    }
    
    public function dokter() {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }
    
    public function paramedis() {
        return $this->belongsTo(Paramedis::class, 'id_paramedis', 'id_paramedis');
    }
    
    public function pelayanan() {
        return $this->belongsTo(Pelayanan::class, 'id_pelayanan', 'id_pelayanan');
    }
    
    public function diagnosa() {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa', 'id_diagnosa');
    }

    // --- RELASI MANY TO MANY (BelongsToMany via Tabel Pivot) ---
    public function anamnesas() {
        return $this->belongsToMany(Anamnesa::class, 'rekam_medis_anamnesa', 'id_rekam', 'id_anamnesa');
    }

    public function obats() {
        return $this->belongsToMany(Obat::class, 'rekam_medis_obat', 'id_rekam', 'id_obat');
    }
}