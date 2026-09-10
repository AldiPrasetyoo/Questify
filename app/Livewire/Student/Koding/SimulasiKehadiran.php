<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Simulasi rekap kehadiran manual — 12 baris data, timer, hitung kekeliruan, ekstrapolasi ke 864 siswa */
class SimulasiKehadiran extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public bool $sudahDikirim = false;
    public int $waktuDetik = 0;
    public array $jawaban = [];

    public array $data = [
        ['no' => 1, 'nama' => 'Anisa P.', 'jam' => '06:48', 'kunci' => 'Hadir'],
        ['no' => 2, 'nama' => 'Bagas W.', 'jam' => '07:00', 'kunci' => 'Hadir'],
        ['no' => 3, 'nama' => 'Citra M.', 'jam' => '07:01', 'kunci' => 'Terlambat'],
        ['no' => 4, 'nama' => 'Dimas A.', 'jam' => '07:12', 'kunci' => 'Terlambat'],
        ['no' => 5, 'nama' => 'Eka R.', 'jam' => '07:30', 'kunci' => 'Terlambat'],
        ['no' => 6, 'nama' => 'Fajar H.', 'jam' => '07:31', 'kunci' => 'Alpa'],
        ['no' => 7, 'nama' => 'Gita N.', 'jam' => '06:55', 'kunci' => 'Hadir'],
        ['no' => 8, 'nama' => 'Hasan L.', 'jam' => '08:05', 'kunci' => 'Alpa'],
        ['no' => 9, 'nama' => 'Indah S.', 'jam' => '06:59', 'kunci' => 'Hadir'],
        ['no' => 10, 'nama' => 'Joko P.', 'jam' => '07:25', 'kunci' => 'Terlambat'],
        ['no' => 11, 'nama' => 'Kirana D.', 'jam' => '07:40', 'kunci' => 'Alpa'],
        ['no' => 12, 'nama' => 'Lutfi A.', 'jam' => '07:00', 'kunci' => 'Hadir'],
    ];

    private array $barisJamBatas = [2, 3, 5, 12];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function kirim(int $waktuDetik)
    {
        $this->waktuDetik = $waktuDetik;
        $this->sudahDikirim = true;
    }

    public function getJumlahKeliruProperty(): int
    {
        $keliru = 0;
        foreach ($this->data as $d) {
            if (($this->jawaban[$d['no']] ?? null) !== $d['kunci']) $keliru++;
        }
        return $keliru;
    }

    public function getKeliruDiJamBatasProperty(): bool
    {
        foreach ($this->barisJamBatas as $no) {
            $d = collect($this->data)->firstWhere('no', $no);
            if (($this->jawaban[$no] ?? null) !== $d['kunci']) return true;
        }
        return false;
    }

    public function getEkstrapolasiProperty(): string
    {
        if ($this->waktuDetik <= 0) return '-';
        $totalDetik = (int) round(($this->waktuDetik / 12) * 864);
        $jam = intdiv($totalDetik, 3600);
        $menit = intdiv($totalDetik % 3600, 60);
        return $jam > 0 ? "{$jam} jam {$menit} menit" : "{$menit} menit";
    }

    public function lanjut()
    {
        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['waktu_detik' => $this->waktuDetik, 'jumlah_keliru' => $this->jumlahKeliru],
            poin: 10,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.simulasi-kehadiran', [])->layout('layouts.questify');
    }
}
