<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $fillable = [
        'urutan',
        'judul',
        'deskripsi',
        'tujuan_pembelajaran',
        'tanggal_tatap_muka',
        'aktif',
    ];

    protected function casts(): array
    {
        return ['tanggal_tatap_muka' => 'date', 'aktif' => 'boolean'];
    }

    public function misis()
    {
        return $this->hasMany(Misi::class)->orderBy('urutan');
    }

    public function misiPraKelas()
    {
        return $this->hasMany(Misi::class)->where('fase', 'pra_kelas')->orderBy('urutan');
    }

    public function misiTatapMuka()
    {
        return $this->hasMany(Misi::class)->where('fase', 'tatap_muka')->orderBy('urutan');
    }

    public function misiPascaKelas()
    {
        return $this->hasMany(Misi::class)->where('fase', 'pasca_kelas')->orderBy('urutan');
    }
}
