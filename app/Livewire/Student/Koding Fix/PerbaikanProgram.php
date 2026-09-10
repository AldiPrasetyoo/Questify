<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Perbaiki program — eksekusi & penilaian lewat JSCPP di browser (lihat cpp-runner.js) */
class PerbaikanProgram extends Component
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
        $this->dispatch('kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['jumlah_lolos' => $jumlahLolos, 'kode_akhir' => $kodeAkhir],
            poin: $poin,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.perbaikan-program'); }
}
