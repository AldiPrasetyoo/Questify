<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoal extends Model
{
    protected $fillable = ['konten_misi_id', 'urutan', 'pertanyaan', 'pilihan', 'kunci_jawaban', 'pembahasan'];

    protected function casts(): array
    {
        return ['pilihan' => 'array'];
    }

    public function kontenMisi()
    {
        return $this->belongsTo(KontenMisi::class);
    }
}
