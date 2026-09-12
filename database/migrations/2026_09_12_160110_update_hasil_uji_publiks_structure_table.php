<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus tabel lama secara bersih jika sudah terlanjur ada
        Schema::dropIfExists('hasil_uji_publiks');

        // Buat ulang tabel dengan struktur persis seperti jawaban_ujians + identitas
        Schema::create('hasil_uji_publiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelas');
            $table->foreignId('ujian_id');
            $table->foreignId('soal_ujian_id');
            $table->string('jawaban_dipilih');
            $table->boolean('is_benar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_uji_publiks');
    }
};