<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progres_kontens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('konten_misi_id')->constrained()->cascadeOnDelete();
            // data bebas per tipe konten: hasil interaksi komponen koding,
            // jawaban refleksi teks, jumlah_kesalahan, waktu_pengerjaan_detik, dll
            $table->json('data')->nullable();
            $table->boolean('selesai')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'konten_misi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progres_kontens');
    }
};