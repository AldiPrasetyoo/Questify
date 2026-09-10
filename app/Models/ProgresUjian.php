<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresUjian extends Model
{
    protected $guarded = [];

    // Relasi ke user / siswa yang mengerjakan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke ujian terkait
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}