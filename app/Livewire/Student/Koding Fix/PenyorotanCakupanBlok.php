<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Penyorotan cakupan blok — mengetuk satu baris menyorot seluruh area blok yang memuat baris itu */
class PenyorotanCakupanBlok extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public ?int $barisAktif = null;
    public bool $tampilkanLekukan = true;
    public array $sudahDiketuk = [];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function ketuk(int $index) { $this->barisAktif = $index; $this->sudahDiketuk[$index] = true; }
    public function toggleLekukan() { $this->tampilkanLekukan = ! $this->tampilkanLekukan; }

    public function getBlokAktifProperty(): array
    {
        if ($this->barisAktif === null) return ['luar' => null, 'dalam' => null];
        $baris = $this->konfigurasi['baris'][$this->barisAktif] ?? [];
        return ['luar' => $baris['blok_luar'] ?? null, 'dalam' => $baris['blok_dalam'] ?? null];
    }

    public function getSemuaSudahDiketukProperty(): bool { return count($this->sudahDiketuk) >= count($this->konfigurasi['baris'] ?? []); }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['baris_diketuk' => count($this->sudahDiketuk)], poin: 5)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.penyorotan-cakupan-blok'); }
}
