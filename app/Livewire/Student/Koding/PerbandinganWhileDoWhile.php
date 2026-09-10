<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Perbandingan while vs do-while — dijalankan sungguhan lewat JSCPP di browser */
class PerbandinganWhileDoWhile extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function terimaHasil(int $poin)
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['dicoba' => true], poin: $poin)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.perbandingan-while-dowhile', [])->layout('layouts.questify');
    }
}
