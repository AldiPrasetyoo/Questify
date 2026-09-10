<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Latihan melengkapi kode dengan baris terkunci — hanya kotak isian yang bisa diubah */
class LatihanMelengkapi extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $indexButir = 0;
    public array $isian = [];
    public bool $sudahDicek = false;
    public bool $semuaBenar = false;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    // Lifecycle hook: otomatis mereset status pengecekan saat input berubah
    public function updatedIsian()
    {
        $this->sudahDicek = false;
        $this->semuaBenar = false;
    }

    public function getButirAktifProperty(): array
    {
        return $this->konfigurasi['butir'][$this->indexButir] ?? ['kode' => '', 'jawaban' => []];
    }

    public function getSegmenProperty(): array
    {
        $kode = $this->butirAktif['kode'] ?? '';
        return preg_split('/(___\d+)/', $kode, -1, PREG_SPLIT_DELIM_CAPTURE);
    }

    public function cek()
    {
        $this->sudahDicek = true;
        $jawabanBenar = $this->butirAktif['jawaban'] ?? [];
        $this->semuaBenar = true;
        foreach ($jawabanBenar as $idx => $benar) {
            $isian = trim($this->isian[$this->indexButir][$idx] ?? '');
            if (strtolower($isian) !== strtolower($benar)) $this->semuaBenar = false;
        }
    }

    public function butirBerikutnya()
    {
        $total = count($this->konfigurasi['butir'] ?? []);
        if ($this->indexButir + 1 < $total) {
            $this->indexButir++;
            $this->sudahDicek = false;
            $this->semuaBenar = false;
            return;
        }
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['total_butir' => $total], poin: 0)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.latihan-melengkapi', [])->layout('layouts.questify');
    }
}