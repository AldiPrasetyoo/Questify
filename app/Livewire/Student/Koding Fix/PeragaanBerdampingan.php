<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Peragaan berdampingan: inisialisasi di dalam vs sebelum perulangan, ditelusuri langkah demi langkah */
class PeragaanBerdampingan extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $langkah = 0;

    public array $data = [
        [712, 'Terlambat'], [648, 'Hadir'], [740, 'Alpa'], [701, 'Terlambat'],
        [815, 'Alpa'], [700, 'Hadir'], [659, 'Hadir'], [805, 'Alpa'],
        [725, 'Terlambat'], [655, 'Hadir'], [730, 'Terlambat'], [731, 'Alpa'],
    ];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    private function status(int $jam): string
    {
        if ($jam <= 700) return 'Hadir';
        if ($jam <= 730) return 'Terlambat';
        return 'Alpa';
    }

    public function getPenelusuranKiriProperty(): array
    {
        $baris = [];
        for ($i = 0; $i < $this->langkah; $i++) {
            $hadir = $terlambat = $alpa = 0;
            $s = $this->status($this->data[$i][0]);
            if ($s === 'Hadir') $hadir++; elseif ($s === 'Terlambat') $terlambat++; else $alpa++;
            $baris[] = compact('hadir', 'terlambat', 'alpa');
        }
        return $baris;
    }

    public function getPenelusuranKananProperty(): array
    {
        $baris = [];
        $hadir = $terlambat = $alpa = 0;
        for ($i = 0; $i < $this->langkah; $i++) {
            $s = $this->status($this->data[$i][0]);
            if ($s === 'Hadir') $hadir++; elseif ($s === 'Terlambat') $terlambat++; else $alpa++;
            $baris[] = ['hadir' => $hadir, 'terlambat' => $terlambat, 'alpa' => $alpa];
        }
        return $baris;
    }

    public function lanjutSatu() { if ($this->langkah < count($this->data)) $this->langkah++; }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['langkah_ditonton' => $this->langkah], poin: 5)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.peragaan-berdampingan'); }
}
