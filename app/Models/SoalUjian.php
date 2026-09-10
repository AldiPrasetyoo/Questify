<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    protected $guarded = [];

    // Kolom pilihan bertipe JSON diubah otomatis menjadi array PHP
    protected $casts = [
        'pilihan' => 'array',
    ];

    // Relasi ke induk ujian (pretest / posttest)
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Relasi ke detail jawaban siswa
    public function jawabanUjians()
    {
        return $this->hasMany(JawabanUjian::class);
    }
}