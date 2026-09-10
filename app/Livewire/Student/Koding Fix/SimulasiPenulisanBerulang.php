<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Simulasi menyalin blok perintah berulang sampai 8 siswa, lalu diekstrapolasi ke 36 siswa */
class SimulasiPenulisanBerulang extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $jumlahSalinan = 0;
    public const TARGET = 8;
    public const BARIS_PER_SALINAN = 2;
    public int $target = self::TARGET;
    public bool $sudahDikirim = false;
    public int $waktuDetik = 0;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function salinBlok() { if ($this->jumlahSalinan < self::TARGET) $this->jumlahSalinan++; }
    public function kirim(int $waktuDetik) { $this->waktuDetik = $waktuDetik; $this->sudahDikirim = true; }
    public function getTotalBarisProperty(): int { return $this->jumlahSalinan * self::BARIS_PER_SALINAN; }
    public function getEkstrapolasiBarisProperty(): int { return (int) round(($this->totalBaris / self::TARGET) * 36); }

    public function lanjut()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['waktu_detik' => $this->waktuDetik, 'jumlah_salinan' => $this->jumlahSalinan], poin: 10)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.simulasi-penulisan-berulang'); }
}
