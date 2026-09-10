<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('misis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained()->cascadeOnDelete();
            $table->enum('fase', ['pra_kelas', 'tatap_muka', 'pasca_kelas']);
            $table->integer('urutan'); // urutan misi dalam fase (Misi 1, Misi 2, dst — global per pertemuan)
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('estimasi_menit')->nullable();
            $table->integer('poin_maksimal')->default(0);
            // Progressive unlock: misi ini terkunci sampai misi_prasyarat_id selesai
            $table->foreignId('misi_prasyarat_id')->nullable()->constrained('misis')->nullOnDelete();
            $table->boolean('wajib_kerja_kelompok')->default(false); // relevan utk fase tatap_muka
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('misis');
    }
};