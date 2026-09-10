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
        Schema::create('soal_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained()->onDelete('cascade');
            $table->text('pertanyaan');
            $table->json('pilihan'); // Format: ['A' => 'Teks A', 'B' => 'Teks B', ...]
            $table->string('kunci_jawaban'); // 'A', 'B', 'C', atau 'D'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ujians');
    }
};
