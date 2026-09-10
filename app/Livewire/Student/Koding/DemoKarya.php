<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Ruang presentasi & tanggapan antar-kelompok */
class DemoKarya extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public string $urutanKondisi = '';
    public string $alasanPenyusunan = '';
    public string $bagianTersulit = '';
    public string $tanggapanUntukKelompokLain = '';

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function kirim()
    {
        $this->validate([
            'urutanKondisi' => 'required|string|min:5',
            'alasanPenyusunan' => 'required|string|min:5',
            'bagianTersulit' => 'required|string|min:3',
        ]);

        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['urutan_kondisi' => $this->urutanKondisi, 'alasan' => $this->alasanPenyusunan, 'tersulit' => $this->bagianTersulit, 'tanggapan' => $this->tanggapanUntukKelompokLain],
            poin: 10,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.demo-karya', [])->layout('layouts.questify');
    }
}
