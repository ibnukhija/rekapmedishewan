<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hewan', function (Blueprint $table) {
            $table->id('id_hewan');
            $table->unsignedBigInteger('id_pemilik');
            $table->unsignedBigInteger('id_jenis');
            $table->string('nama_hewan', 100);
            $table->enum('jenis_kelamin', ['Jantan', 'Betina']);
            $table->integer('umur');
            $table->string('warna', 50);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_pemilik')->references('id_pemilik')->on('pemilik')
                    ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis_hewan')
                    ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hewan');
    }
};