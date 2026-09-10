<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten_misis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('misi_id')->constrained()->cascadeOnDelete();
            $table->integer('urutan');
            $table->enum('tipe', ['teks_web', 'aset_ppt', 'koding', 'kuis', 'refleksi']);
            $table->string('judul')->nullable();

            // tipe = teks_web -> HTML body ada di sini (bukan gambar, harus bisa dicopy/zoom/cari)
            $table->longText('konten_html')->nullable();

            // tipe = aset_ppt -> path gambar statis hasil export slide (1600x900 PNG)
            $table->string('path_gambar')->nullable();

            // tipe = koding -> nama komponen Livewire interaktif yang dirender,
            // contoh: 'evaluator-kondisi', 'tabel-kebenaran', 'penelusuran-loop', 'perbandingan-kode'
            $table->string('komponen_koding')->nullable();
            $table->json('konfigurasi_koding')->nullable(); // parameter/soal utk komponen tsb

            // tipe = refleksi -> pertanyaan reflektif terbuka (dibaca guru di Papan Guru)
            $table->text('pertanyaan_refleksi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_misis');
    }
};
