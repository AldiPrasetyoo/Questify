<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Perbaiki program kombinasi — dijalankan lewat JSCPP di browser, 3 pemeriksaan terpisah */
class PerbaikanProgramKombinasi extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function terimaHasil(int $jumlahLolos, string $kodeAkhir, int $poin)
    {
        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['jumlah_lolos' => $jumlahLolos, 'kode_akhir' => $kodeAkhir],
            poin: $poin,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.perbaikan-program-kombinasi', [])->layout('layouts.questify');
    }
}
