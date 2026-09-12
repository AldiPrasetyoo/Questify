<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_uji_publiks', function (Blueprint $table) {
            // Ubah tipe data jika belum unsignedBigInteger
            $table->unsignedBigInteger('ujian_id')->change();
            $table->unsignedBigInteger('soal_ujian_id')->change();

            // Tambahkan Foreign Key agar phpMyAdmin mengenalinya sebagai relasi
            $table->foreign('ujian_id')->references('id')->on('ujians')->onDelete('cascade');
            $table->foreign('soal_ujian_id')->references('id')->on('soal_ujians')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('hasil_uji_publiks', function (Blueprint $table) {
            $table->dropForeign(['ujian_id']);
            $table->dropForeign(['soal_ujian_id']);
        });
    }
};