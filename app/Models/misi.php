<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class misi extends Model
{
    protected $fillable = [
        'pertemuan_id', 'fase', 'urutan', 'judul', 'deskripsi',
        'estimasi_menit', 'poin_maksimal', 'misi_prasyarat_id',
        'wajib_kerja_kelompok', 'aktif',
    ];

    protected function casts(): array
    {
        return ['wajib_kerja_kelompok' => 'boolean', 'aktif' => 'boolean'];
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function prasyarat()
    {
        return $this->belongsTo(Misi::class, 'misi_prasyarat_id');
    }

    public function kontens()
    {
        return $this->hasMany(KontenMisi::class)->orderBy('urutan');
    }

    public function progresUntuk(User $user)
    {
        return $this->hasMany(ProgresMisi::class)->where('user_id', $user->id)->first();
    }

    /** Cek apakah misi ini sudah terbuka untuk seorang siswa (progressive unlock) */
    public function terbukaUntuk(User $user): bool
    {
        if (! $this->misi_prasyarat_id) {
            return true;
        }

        return ProgresMisi::where('user_id', $user->id)
            ->where('misi_id', $this->misi_prasyarat_id)
            ->where('status', 'selesai')
            ->exists();
    }
}