<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan'); // 1, 2, 3
            $table->string('judul'); // "Percabangan", "Perulangan", "Kombinasi Percabangan & Perulangan"
            $table->text('deskripsi')->nullable();
            $table->text('tujuan_pembelajaran')->nullable();
            $table->date('tanggal_tatap_muka')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};