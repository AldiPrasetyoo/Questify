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
        // Rapor akhir per pertemuan — dihitung/di-generate, bisa dicatat manual oleh guru juga
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pertemuan_id')->constrained()->cascadeOnDelete();
            $table->integer('nilai_pra_kelas')->nullable();
            $table->integer('nilai_tatap_muka')->nullable();
            $table->integer('nilai_pasca_kelas')->nullable();
            $table->integer('total_poin')->default(0);
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'pertemuan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapors');
    }
};