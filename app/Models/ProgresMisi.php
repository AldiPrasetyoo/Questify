<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresMisi extends Model
{
    protected $fillable = ['user_id', 'misi_id', 'status', 'poin_didapat', 'waktu_mulai', 'waktu_selesai'];

    protected function casts(): array
    {
        return ['waktu_mulai' => 'datetime', 'waktu_selesai' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function misi()
    {
        return $this->belongsTo(Misi::class);
    }
}