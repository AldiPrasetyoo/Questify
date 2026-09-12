<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjiPublik extends Model
{
    protected $fillable = [
        'nama', 
        'kelas', 
        'ujian_id', 
        'soal_ujian_id', 
        'jawaban_dipilih', 
        'is_benar'
    ];
}