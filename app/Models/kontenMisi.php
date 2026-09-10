<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kontenMisi extends Model
{
    protected $fillable = [
        'misi_id',
        'urutan',
        'tipe',
        'judul',
        'konten_html',
        'path_gambar',
        'komponen_koding',
        'konfigurasi_koding',
        'pertanyaan_refleksi',
    ];

    protected function casts(): array
    {
        return ['konfigurasi_koding' => 'array'];
    }

    public function misi()
    {
        return $this->belongsTo(Misi::class);
    }

    public function kuisSoals()
    {
        return $this->hasMany(KuisSoal::class)->orderBy('urutan');
    }
}
