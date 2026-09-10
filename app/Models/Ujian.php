<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $guarded = [];

    // Relasi ke butir soal ujian
    public function soalUjians()
    {
        return $this->hasMany(SoalUjian::class);
    }

    // Relasi ke progres pengerjaan siswa
    public function progresUjians()
    {
        return $this->hasMany(ProgresUjian::class);
    }
}