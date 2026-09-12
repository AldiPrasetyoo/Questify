<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah enum untuk menambahkan ujisoal
        DB::statement("ALTER TABLE ujians MODIFY COLUMN tipe ENUM('pretest', 'posttest', 'ujisoal') NOT NULL DEFAULT 'pretest'");
    }

    public function down()
    {
        // Kembalikan ke semula jika di-rollback
        DB::statement("ALTER TABLE ujians MODIFY COLUMN tipe ENUM('pretest', 'posttest') NOT NULL DEFAULT 'pretest'");
    }
};