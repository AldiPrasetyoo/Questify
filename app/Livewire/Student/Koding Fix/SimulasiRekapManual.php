<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Simulasi penghitungan rekap manual — 12 baris keluaran, isi Hadir/Terlambat/Alpa/persentase */
class SimulasiRekapManual extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public bool $sudahDikirim = false;
    public int $waktuDetik = 0;
    public $isianHadir = '';
    public $isianTerlambat = '';
    public $isianAlpa = '';
    public $isianPersentase = '';

    public array $data = [
        [1, 712, 'Terlambat'], [2, 648, 'Hadir'], [3, 740, 'Alpa'], [4, 701, 'Terlambat'],
        [5, 815, 'Alpa'], [6, 700, 'Hadir'], [7, 659, 'Hadir'], [8, 805, 'Alpa'],
        [9, 725, 'Terlambat'], [10, 655, 'Hadir'], [11, 730, 'Terlambat'], [12, 731, 'Alpa'],
    ];

    private array $kunci = ['hadir' => 4, 'terlambat' => 4, 'alpa' => 4, 'persentase' => 33.33];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function kirim(int $waktuDetik) { $this->waktuDetik = $waktuDetik; $this->sudahDikirim = true; }

    public function getJumlahKeliruProperty(): int
    {
        $keliru = 0;
        if ((int) $this->isianHadir !== $this->kunci['hadir']) $keliru++;
        if ((int) $this->isianTerlambat !== $this->kunci['terlambat']) $keliru++;
        if ((int) $this->isianAlpa !== $this->kunci['alpa']) $keliru++;
        if (abs((float) str_replace(',', '.', $this->isianPersentase) - $this->kunci['persentase']) > 0.5) $keliru++;
        return $keliru;
    }

    public function lanjut()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['waktu_detik' => $this->waktuDetik, 'jumlah_keliru' => $this->jumlahKeliru], poin: 10)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.simulasi-rekap-manual'); }
}
