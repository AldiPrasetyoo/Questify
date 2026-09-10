<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/**
 * Editor kode kelompok + tabel uji 7 data (jam). Eksekusi & penilaian kini
 * terjadi di BROWSER lewat JSCPP (lihat resources/js/cpp-runner.js) — komponen
 * PHP ini hanya menerima hasil akhir yang sudah dihitung di sisi klien.
 */
class EditorKodeUji extends Component
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
        return view('livewire.student.koding.editor-kode-uji', [])->layout('layouts.questify');
    }
}
