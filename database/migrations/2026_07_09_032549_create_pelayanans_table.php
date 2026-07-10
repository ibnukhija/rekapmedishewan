<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelayanan', function (Blueprint $table) {
            $table->id('id_pelayanan');
            $table->string('nama_pelayanan', 100);
            $table->decimal('tarif', 10, 2);
            $table->string('keterangan', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelayanan');
    }
};