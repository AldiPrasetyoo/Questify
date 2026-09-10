<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Tabel kebenaran interaktif — tekan benar/salah pada A dan B, baris sesuai menyala */
class TabelKebenaran extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public bool $a = true;
    public bool $b = true;
    public int $jam = 715;
    public bool $sudahDicoba = false;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
        $this->jam = $konfigurasi['nilai_jam_awal'] ?? 715;
    }

    public function setA(bool $nilai)
    {
        $this->a = $nilai;
        $this->sudahDicoba = true;
    }
    public function setB(bool $nilai)
    {
        $this->b = $nilai;
        $this->sudahDicoba = true;
    }
    public function getHasilContohProperty(): bool
    {
        return $this->jam > 700 && $this->jam <= 730;
    }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['dicoba' => $this->sudahDicoba], poin: 10)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.tabel-kebenaran', [])->layout('layouts.questify');
    }
}
