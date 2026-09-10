<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Bedah kode yang dapat diketuk — tiap bagian kode diketuk untuk lihat keterangannya */
class BedahKode extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public ?int $bagianAktif = null;
    public array $sudahDiketuk = [];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function ketuk(int $index) { $this->bagianAktif = $index; $this->sudahDiketuk[$index] = true; }
    public function getSemuaSudahDiketukProperty(): bool { return count($this->sudahDiketuk) >= count($this->konfigurasi['bagian'] ?? []); }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['bagian_diketuk' => count($this->sudahDiketuk)], poin: 5)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.bedah-kode'); }
}
