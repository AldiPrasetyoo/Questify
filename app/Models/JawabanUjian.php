<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanUjian extends Model
{
    protected $guarded = [];

    // Relasi ke siswa yang menjawab
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke induk ujian (pretest / posttest)
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Relasi ke butir soal yang dikerjakan
    public function soalUjian()
    {
        return $this->belongsTo(SoalUjian::class);
    }
}
