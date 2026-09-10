<?php

namespace App\Observers;

use App\Models\ProgresMisi;
use App\Services\PengecekLencana;
use App\Services\PenghitungRapor;

class ProgresMisiObserver
{
    /**
     * Dipanggil ketika progres misi dibuat.
     */
    public function created(ProgresMisi $progresMisi): void
    {
        if ($progresMisi->status === 'selesai') {
            $this->prosesSelesai($progresMisi);
        }
    }

    /**
     * Dipanggil ketika progres misi diperbarui.
     */
    public function updated(ProgresMisi $progresMisi): void
    {
        if (
            $progresMisi->wasChanged('status') &&
            $progresMisi->status === 'selesai'
        ) {
            $this->prosesSelesai($progresMisi);
        }
    }

    /**
     * Proses setelah misi selesai.
     */
    private function prosesSelesai(ProgresMisi $progresMisi): void
    {
        $user = $progresMisi->user;

        // Cek apakah ada lencana yang baru diperoleh
        app(PengecekLencana::class)->cekUntuk($user);

        // Update nilai rapor berdasarkan pertemuan
        app(PenghitungRapor::class)->perbaruiUntuk(
            $user,
            $progresMisi->misi->pertemuan_id
        );
    }
}