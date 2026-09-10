<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Latihan isian singkat (reusable) — jawaban dicek longgar (tanpa spasi berlebih, tanpa besar/kecil) */
class LatihanIsianSingkat extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $indexSoal = 0;
    public string $jawaban = '';
    public bool $sudahDijawab = false;
    public bool $benar = false;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function getSoalAktifProperty(): array { return $this->konfigurasi['soal'][$this->indexSoal] ?? []; }
    private function normalisasi(string $s): string { return strtolower(trim(preg_replace('/\s+/', ' ', $s))); }

    public function jawab()
    {
        $this->sudahDijawab = true;
        $this->benar = $this->normalisasi($this->jawaban) === $this->normalisasi($this->soalAktif['kunci'] ?? '');
    }

    public function lanjut()
    {
        $total = count($this->konfigurasi['soal'] ?? []);
        if ($this->indexSoal + 1 < $total) {
            $this->indexSoal++; $this->jawaban = ''; $this->sudahDijawab = false;
            return;
        }
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['total_soal' => $total], poin: 0)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.latihan-isian-singkat'); }
}
