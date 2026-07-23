<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnosa', function (Blueprint $table) {
            // Dipakai untuk menandai diagnosa mana yang bisa dicegah lewat vaksinasi
            // (dicentang lewat halaman "Kelola Diagnosa" yang sudah ada)
            $table->boolean('perlu_vaksin')->default(false)->after('nama_diagnosa');
        });
    }

    public function down(): void
    {
        Schema::table('diagnosa', function (Blueprint $table) {
            $table->dropColumn('perlu_vaksin');
        });
    }
};