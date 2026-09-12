<?php

namespace App\Livewire;

use App\Models\Ujian;
use App\Models\HasilUjiPublik;
use Livewire\Component;

class UjiSoal extends Component
{
    public $soals = [];
    public $jawaban = [];
    public int $indexSoal = 0;

    public $namaSiswa = '';
    public $kelasSiswa = '';

    public $sudahMulai = false;
    public $sudahSelesai = false;
    public $skorAkhir = 0;
    public $jumlahBenar = 0;

    public function mount()
    {
        $ujian = Ujian::where('tipe', 'ujisoal')->first();

        if ($ujian) {
            $this->soals = $ujian->soalUjians()->get();
        } else {
            $this->soals = collect();
        }
    }

    public function mulaiKuis()
    {
        $this->validate([
            'namaSiswa' => 'required|min:3',
            'kelasSiswa' => 'required'
        ], [
            'namaSiswa.required' => 'Nama wajib diisi agar bisa memulai.',
            'namaSiswa.min' => 'Nama terlalu pendek (minimal 3 huruf).',
            'kelasSiswa.required' => 'Kelas/Instansi wajib diisi.',
        ]);

        $this->sudahMulai = true;
    }

    public function pilihJawaban($soalId, $pilihan)
    {
        $this->jawaban[$soalId] = $pilihan;
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

    public function kumpulkan()
    {
        // 1. Validasi: Pastikan semua soal dijawab
        $rules = [];
        $messages = [];
        foreach ($this->soals as $s) {
            $rules['jawaban.' . $s->id] = 'required';
            $messages['jawaban.' . $s->id . '.required'] = 'Kamu belum menjawab soal ini.';
        }
        $this->validate($rules, $messages);

        // 2. Evaluasi dan simpan per baris soal ke tabel hasil_uji_publiks
        $benar = 0;

        foreach ($this->soals as $soal) {
            $pilihanSiswa = $this->jawaban[$soal->id] ?? '';
            $isBenar = ($pilihanSiswa === $soal->kunci_jawaban) ? 1 : 0;

            if ($isBenar === 1) {
                $benar++;
            }

            // Menyimpan baris demi baris menyerupai format tabel jawaban_ujians + nama & kelas
            HasilUjiPublik::create([
                'nama'            => $this->namaSiswa,
                'kelas'           => $this->kelasSiswa,
                'ujian_id'        => $soal->ujian_id,
                'soal_ujian_id'   => $soal->id,
                'jawaban_dipilih' => $pilihanSiswa,
                'is_benar'        => $isBenar,
            ]);
        }

        // 3. Kalkulasi Skor Akhir untuk ditampilkan di kartu hasil layar
        $totalSoal = count($this->soals);
        $this->jumlahBenar = $benar;
        $this->skorAkhir = $totalSoal > 0 ? round(($benar / $totalSoal) * 100) : 0;

        $this->sudahSelesai = true;
    }

    public function ulangiKuis()
    {
        $this->reset(['jawaban', 'indexSoal', 'sudahMulai', 'sudahSelesai', 'skorAkhir', 'jumlahBenar', 'namaSiswa', 'kelasSiswa']);
    }

    public function render()
    {
        return view('livewire.uji-soal')->layout('layouts.clear', ['title' => 'Uji Soal Publik']);
    }
}