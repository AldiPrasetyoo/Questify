<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokBelajar extends Model
{
    protected $fillable = ['pertemuan_id', 'nama_kelompok'];

    public function anggotas()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_belajar_id');
    }
}