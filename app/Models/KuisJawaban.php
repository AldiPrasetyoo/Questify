<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisJawaban extends Model
{
    protected $fillable = ['user_id', 'kuis_soal_id', 'jawaban_dipilih', 'benar', 'percobaan_ke'];

    protected function casts(): array
    {
        return ['benar' => 'boolean'];
    }
}