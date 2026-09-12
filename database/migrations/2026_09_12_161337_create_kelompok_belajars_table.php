<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelompok_belajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuans')->onDelete('cascade');
            $table->string('nama_kelompok'); // Contoh: "Kelompok 1"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompok_belajars');
    }
};