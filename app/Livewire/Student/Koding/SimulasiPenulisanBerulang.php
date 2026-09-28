<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Simulasi menyalin blok perintah berulang sampai 8 siswa, lalu diekstrapolasi ke 36 siswa */
class SimulasiPenulisanBerulang extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    
    // Kita langsung mulai dengan 1 blok yang berisi angka 1
    public array $salinan = [1]; 
    public const TARGET = 8;
    public const BARIS_PER_SALINAN = 2;
    
    public bool $sudahDikirim = false;
    public int $waktuDetik = 0;

    // Variabel pelacakan kesalahan
    public int $kesalahan = 0;
    public array $logKesalahan = [];
    public array $indexSalah = [];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    // FUNGSI UTAMA: Memvalidasi input saat ini SEBELUM menyalin blok baru
    public function validasiDanSalin(int $waktuSaatIni)
    {
        $this->indexSalah = [];
        $semuaBenar = true;

        // 1. Cek apakah urutan angka pada blok yang ada sudah benar
        foreach ($this->salinan as $idx => $nilai) {
            if ((int)$nilai !== ($idx + 1)) {
                $this->indexSalah[] = $idx;
                $semuaBenar = false;
            }
        }

        // 2. Jika ada angka yang salah/belum diubah, TOLAK penyalinan dan catat error
        if (!$semuaBenar) {
            $this->kesalahan++;
            $this->logKesalahan[] = $waktuSaatIni;
            return; 
        }

        // 3. Jika semua angka benar, BARU salin blok berikutnya
        if (count($this->salinan) < self::TARGET) {
            // Blok baru otomatis diisi angka 1 (sengaja agar siswa harus mengubahnya)
            $this->salinan[] = 1; 
        }
    }

    public function kirimJawaban(int $waktuSaatIni)
    {
        $this->indexSalah = [];
        $semuaBenar = true;

        foreach ($this->salinan as $idx => $nilai) {
            if ((int)$nilai !== ($idx + 1)) {
                $this->indexSalah[] = $idx;
                $semuaBenar = false;
            }
        }

        if (!$semuaBenar || count($this->salinan) < self::TARGET) {
            $this->kesalahan++;
            $this->logKesalahan[] = $waktuSaatIni;
            return; 
        }

        $this->waktuDetik = $waktuSaatIni;
        $this->sudahDikirim = true;
    }

    public function getTotalBarisProperty(): int
    {
        return count($this->salinan) * self::BARIS_PER_SALINAN;
    }

    public function getEkstrapolasiBarisProperty(): int
    {
        return 36 * self::BARIS_PER_SALINAN;
    }

    public function lanjut()
    {
        $this->dispatch(
            'kontenSelesai', 
            kontenMisiId: $this->kontenMisiId, 
            data: [
                'waktu_detik' => $this->waktuDetik, 
                'jumlah_salinan' => count($this->salinan),
                'total_kesalahan' => $this->kesalahan,
                'log_waktu_kesalahan' => $this->logKesalahan
            ], 
            poin: 10
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.simulasi-penulisan-berulang')->layout('layouts.questify');
    }
}