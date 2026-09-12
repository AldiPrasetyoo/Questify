<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_uji_publiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelas');
            $table->integer('skor');
            $table->integer('jumlah_benar');
            $table->integer('total_soal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_uji_publiks');
    }
};