<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi (membuat tabel jenis_hewan).
     */
    public function up(): void
    {
        Schema::create('jenis_hewan', function (Blueprint $table) {
            $table->id('id_jenis');
            $table->string('nama_jenis', 100)->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel jenis_hewan).
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_hewan');
    }
};