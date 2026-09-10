<?php

namespace App\Livewire\Student;

use App\Models\Ujian;
use App\Models\ProgresUjian;
use App\Models\JawabanUjian;
use Livewire\Component;

class KerjakanUjian extends Component
{
    public Ujian $ujian;
    public $soals;
    public int $indexSoal = 0;
    public array $jawabanSiswa = [];
    public bool $sudahMengerjakan = false;

    public function mount($tipe)
    {
        // Cari ujian berdasarkan tipe (pretest / posttest)
        $this->ujian = Ujian::where('tipe', $tipe)->firstOrFail();

        // Cek apakah user sudah pernah mengerjakan (dibatasi 1 kali)
        $cek = ProgresUjian::where('user_id', auth()->id())
            ->where('ujian_id', $this->ujian->id)
            ->exists();

        if ($cek) {
            $this->sudahMengerjakan = true;
            return;
        }

        $this->soals = $this->ujian->soalUjians()->get();
    }

    public function pilihJawaban($soalId, $pilihan)
    {
        $this->jawabanSiswa[$soalId] = $pilihan;
    }

    public function selanjutnya()
    {
        if ($this->indexSoal < count($this->soals) - 1) {
            $this->indexSoal++;
        }
    }

    public function sebelumnya()
    {
        if ($this->indexSoal > 0) {
            $this->indexSoal--;
        }
    }

    public function selesaiUjian()
    {
        $skorBenar = 0;
        $totalSoal = count($this->soals);

        foreach ($this->soals as $soal) {
            $pilihanSiswa = $this->jawabanSiswa[$soal->id] ?? '';
            $isBenar = ($pilihanSiswa === $soal->kunci_jawaban) ? 1 : 0;

            if ($isBenar === 1) {
                $skorBenar++;
            }

            // Simpan ke tabel jawaban_ujians untuk rekap validitas (1 atau 0)
            JawabanUjian::create([
                'user_id' => auth()->id(),
                'ujian_id' => $this->ujian->id,
                'soal_ujian_id' => $soal->id,
                'jawaban_dipilih' => $pilihanSiswa,
                'is_benar' => $isBenar,
            ]);
        }

        // Hitung nilai akhir skala 100
        $nilaiAkhir = ($totalSoal > 0) ? ($skorBenar / $totalSoal) * 100 : 0;

        // Simpan progres ujian selesai
        ProgresUjian::create([
            'user_id' => auth()->id(),
            'ujian_id' => $this->ujian->id,
            'skor_total' => round($nilaiAkhir),
            'waktu_selesai' => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Ujian berhasil diselesaikan!');
    }

    public function render()
    {
        return view('livewire.student.kerjakan-ujian')
            ->layout('layouts.clear', ['title' => ucfirst($this->ujian->tipe)]);
    }
}