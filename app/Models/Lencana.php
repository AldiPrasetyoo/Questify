<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lencana extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'ikon', 'syarat', 'kriteria'];

    protected function casts(): array
    {
        return ['kriteria' => 'array'];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_lencanas')->withTimestamps();
    }
}