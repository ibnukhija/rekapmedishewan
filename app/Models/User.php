<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Migration tabel 'users' pakai primary key custom 'id_user',
     * bukan 'id' bawaan Laravel. Ini WAJIB di-set supaya Auth::attempt(),
     * Auth::user()->id, dsb tetap mengarah ke kolom yang benar.
     */
    protected $primaryKey = 'id_user';

    /**
     * Kolom yang boleh diisi lewat create()/update() massal,
     * dipakai nanti untuk fitur "Tambah Operator".
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    /**
     * Password tidak boleh ikut ke-serialize/tampil kalau model ini
     * di-return sebagai JSON (misal lewat response()->json()).
     */
    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}