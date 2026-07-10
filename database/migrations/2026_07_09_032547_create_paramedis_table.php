<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paramedis', function (Blueprint $table) {
            $table->id('id_paramedis');
            $table->string('nama_paramedis', 100);
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paramedis');
    }
};