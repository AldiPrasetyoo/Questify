<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konten_misi_id')->constrained()->cascadeOnDelete();
            $table->integer('urutan');
            $table->text('pertanyaan');
            $table->json('pilihan'); // ["A. ...", "B. ...", "C. ...", "D. ..."]
            $table->string('kunci_jawaban', 1); // "A" / "B" / "C" / "D"
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_jawabans');
    }
};
