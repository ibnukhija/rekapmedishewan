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

            // Relasi ke jenis hewan (nullable: null = berlaku untuk semua jenis hewan)
            $table->foreignId('id_jenis')->nullable()
                ->constrained('jenis_hewan', 'id_jenis')
                ->nullOnDelete();

            // Nullable: null = berlaku untuk semua jenis kelamin
            $table->enum('jenis_kelamin', ['jantan', 'betina'])->nullable();

            $table->decimal('tarif', 10, 2);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            // Cegah duplikat kombinasi nama + jenis hewan + jenis kelamin
            $table->unique(['nama_pelayanan', 'id_jenis', 'jenis_kelamin'], 'pelayanan_unique_combo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelayanan');
    }
};