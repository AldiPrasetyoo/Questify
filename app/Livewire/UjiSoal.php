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
    public $konfirmasiModal = false; // State untuk modal konfirmasi kumpul

    public function mount()
    {
        if (session()->has('sudah_mengerjakan_uji_soal')) {
            $this->sudahMulai = true;
            $this->sudahSelesai = true;
            return;
        }

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

    // Membuka modal popup konfirmasi sebelum kumpul
    public function konfirmasiKumpulkan()
    {
        // Validasi opsional: Pastikan semua soal diisi sebelum modal muncul
        $rules = [];
        $messages = [];
        foreach ($this->soals as $s) {
            $rules['jawaban.' . $s->id] = 'required';
            $messages['jawaban.' . $s->id . '.required'] = 'Kamu belum menjawab soal ini.';
        }
        $this->validate($rules, $messages);

        $this->konfirmasiModal = true;
    }

    public function tutupModal()
    {
        $this->konfirmasiModal = false;
    }

    public function kumpulkan()
    {
        $this->konfirmasiModal = false;

        // Simpan jawaban ke database
        foreach ($this->soals as $soal) {
            $pilihanSiswa = $this->jawaban[$soal->id] ?? '';
            $isBenar = ($pilihanSiswa === $soal->kunci_jawaban) ? 1 : 0;

            HasilUjiPublik::create([
                'nama'            => $this->namaSiswa,
                'kelas'           => $this->kelasSiswa,
                'ujian_id'        => $soal->ujian_id,
                'soal_ujian_id'   => $soal->id,
                'jawaban_dipilih' => $pilihanSiswa,
                'is_benar'        => $isBenar,
            ]);
        }

        // Kunci sesi ujian
        session(['sudah_mengerjakan_uji_soal' => true]);

        $this->sudahSelesai = true;
    }

    public function render()
    {
        return view('livewire.uji-soal')->layout('layouts.clear', ['title' => 'Uji Soal Publik']);
    }
}