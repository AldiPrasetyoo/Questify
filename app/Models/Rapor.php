<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    protected $fillable = [
        'user_id', 'pertemuan_id', 'nilai_pra_kelas', 'nilai_tatap_muka',
        'nilai_pasca_kelas', 'total_poin', 'catatan_guru',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}