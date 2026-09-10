<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresKonten extends Model
{
    protected $fillable = ['user_id', 'konten_misi_id', 'data', 'selesai'];

    protected function casts(): array
    {
        return ['data' => 'array', 'selesai' => 'boolean'];
    }

    public function kontenMisi()
    {
        return $this->belongsTo(KontenMisi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}