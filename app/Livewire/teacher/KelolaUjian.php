<?php

namespace App\Livewire\Teacher;

use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\User;
use App\Models\HasilUjiPublik;
use Livewire\Component;

class KelolaUjian extends Component
{
    public $tipeAktif = 'pretest'; // Pilihan tab: pretest, posttest, atau ujisoal
    public $pertanyaan, $pilihanA, $pilihanB, $pilihanC, $pilihanD, $pilihanE, $kunciJawaban = 'A';
    public $editSoalId = null;

    public function render()
    {
        // Ambil atau buat otomatis master ujian berdasarkan tipe aktif
        $ujian = Ujian::firstOrCreate(
            ['tipe' => $this->tipeAktif],
            ['judul' => 'Ujian ' . ucfirst($this->tipeAktif)]
        );

        $soals = $ujian->soalUjians()->get();

        // Ambil data untuk rekapitulasi preview di bagian bawah
        $rekapPrePost = User::where('role', 'student')
            ->with(['progresUjians.ujian'])
            ->get();

        $rekapUjiSoal = HasilUjiPublik::select('nama', 'kelas', 'ujian_id')
            ->selectRaw('SUM(is_benar) as total_benar, COUNT(*) as total_soal')
            ->groupBy('nama', 'kelas', 'ujian_id')
            ->get();

        return view('livewire.teacher.kelola-ujian', [
            'ujian' => $ujian,
            'soals' => $soals,
            'rekapPrePost' => $rekapPrePost,
            'rekapUjiSoal' => $rekapUjiSoal,
        ])->layout('layouts.questify', ['title' => 'Kelola Soal ' . ucfirst($this->tipeAktif)]);
    }

    public function simpanSoal()
    {
        $this->validate([
            'pertanyaan' => 'required',
            'pilihanA' => 'required',
            'pilihanB' => 'required',
            'pilihanC' => 'required',
            'pilihanD' => 'required',
            'pilihanE' => 'required',
            'kunciJawaban' => 'required|in:A,B,C,D,E',
        ]);

        $ujian = Ujian::where('tipe', $this->tipeAktif)->first();

        $dataPilihan = [
            'A' => $this->pilihanA,
            'B' => $this->pilihanB,
            'C' => $this->pilihanC,
            'D' => $this->pilihanD,
            'E' => $this->pilihanE,
        ];

        if ($this->editSoalId) {
            // Edit soal yang sudah ada
            $soal = SoalUjian::findOrFail($this->editSoalId);
            $soal->update([
                'pertanyaan' => $this->pertanyaan,
                'pilihan' => $dataPilihan,
                'kunci_jawaban' => $this->kunciJawaban,
            ]);
            session()->flash('success', 'Soal berhasil diperbarui!');
        } else {
            // Tambah soal baru
            SoalUjian::create([
                'ujian_id' => $ujian->id,
                'pertanyaan' => $this->pertanyaan,
                'pilihan' => $dataPilihan,
                'kunci_jawaban' => $this->kunciJawaban,
            ]);
            session()->flash('success', 'Soal baru berhasil ditambahkan!');
        }

        $this->resetForm();
    }

    public function editSoal($id)
    {
        $soal = SoalUjian::findOrFail($id);
        $this->editSoalId = $soal->id;
        $this->pertanyaan = $soal->pertanyaan;
        $this->pilihanA = $soal->pilihan['A'] ?? '';
        $this->pilihanB = $soal->pilihan['B'] ?? '';
        $this->pilihanC = $soal->pilihan['C'] ?? '';
        $this->pilihanD = $soal->pilihan['D'] ?? '';
        $this->pilihanE = $soal->pilihan['E'] ?? '';
        $this->kunciJawaban = $soal->kunci_jawaban;
    }

    public function hapusSoal($id)
    {
        SoalUjian::findOrFail($id)->delete();
        session()->flash('success', 'Soal berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->pertanyaan = '';
        $this->pilihanA = '';
        $this->pilihanB = '';
        $this->pilihanC = '';
        $this->pilihanD = '';
        $this->pilihanE = '';
        $this->kunciJawaban = 'A';
        $this->editSoalId = null;
    }
}