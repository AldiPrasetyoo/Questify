<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKelompok extends Model
{
    protected $fillable = ['kelompok_belajar_id', 'user_id'];

    // Tambahkan relasi ini agar method ->with('kelompok') dikenali oleh Eloquent
    public function kelompok()
    {
        return $this->belongsTo(KelompokBelajar::class, 'kelompok_belajar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}