<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama Rekam Medis
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam');
            $table->unsignedBigInteger('id_hewan');
            $table->unsignedBigInteger('id_dokter');
            $table->unsignedBigInteger('id_paramedis');
            $table->unsignedBigInteger('id_pelayanan');
            $table->unsignedBigInteger('id_diagnosa')->nullable(); 
            
            $table->date('tanggal');
            $table->string('no_karcis', 30);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_hewan')->references('id_hewan')->on('hewan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokter')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_paramedis')->references('id_paramedis')->on('paramedis')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_pelayanan')->references('id_pelayanan')->on('pelayanan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosa')->onUpdate('cascade')->onDelete('restrict');
        });

        // Tabel Pivot Rekam Medis - Anamnesa
        Schema::create('rekam_medis_anamnesa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rekam');
            $table->unsignedBigInteger('id_anamnesa');

            $table->foreign('id_rekam')->references('id_rekam')->on('rekam_medis')->onDelete('cascade');
            $table->foreign('id_anamnesa')->references('id_anamnesa')->on('anamnesa')->onDelete('cascade');
            
            // Primary key gabungan agar tidak ada data ganda
            $table->primary(['id_rekam', 'id_anamnesa']);
        });

        // Tabel Pivot Rekam Medis - Obat
        Schema::create('rekam_medis_obat', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rekam');
            $table->unsignedBigInteger('id_obat');

            $table->foreign('id_rekam')->references('id_rekam')->on('rekam_medis')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
            
            // Primary key gabungan
            $table->primary(['id_rekam', 'id_obat']);
        });
    }

    public function down(): void
    {
        // Urutan drop harus dibalik dari bawah ke atas (anaknya dulu, baru induknya)
        Schema::dropIfExists('rekam_medis_obat');
        Schema::dropIfExists('rekam_medis_anamnesa');
        Schema::dropIfExists('rekam_medis');
    }
};