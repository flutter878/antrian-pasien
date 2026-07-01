<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokter')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_praktek')->onDelete('cascade');
            $table->date('tanggal_daftar');
            $table->string('no_antrian', 10);
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->text('keluhan')->nullable();
            $table->timestamps();

            $table->unique(['dokter_id', 'tanggal_daftar', 'no_antrian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
