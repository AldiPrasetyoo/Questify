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
        Schema::create('lencanas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // "sang_pemecah_kondisi", "master_loop", dll
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('ikon')->nullable(); // path/emoji
            $table->string('syarat')->nullable(); // deskripsi manusiawi syarat perolehan
            $table->json('kriteria')->nullable(); // struktur kriteria perolehan, bisa berupa JSON
            $table->timestamps();
        });

        Schema::create('user_lencanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lencana_id')->constrained()->cascadeOnDelete();
            $table->timestamp('diperoleh_pada');
            $table->timestamps();
            $table->unique(['user_id', 'lencana_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lencanas');
        Schema::dropIfExists('user_lencanas'); 
        Schema::enableForeignKeyConstraints();  
    }
};