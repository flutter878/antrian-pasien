<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_praktek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('dokter')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota')->default(20);
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['dokter_id', 'hari']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_praktek');
    }
};
