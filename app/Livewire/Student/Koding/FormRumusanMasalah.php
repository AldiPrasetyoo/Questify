<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

class FormRumusanMasalah extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $pertemuanKe = 1; // Default ke pertemuan 1

    // 1. Common: Anggota & Peran
    public array $peran = [
        'juru_kode' => '',
        'penyusun_alur' => '',
        'penguji_program' => '',
        'juru_bicara' => ''
    ];

    // 2. Common: Isian Teks Atas
    public string $isian1 = '';
    public string $isian2 = '';

    // 3. Data Tabel Berdasarkan Pertemuan
    public array $tabelKondisi = [ // Untuk Pertemuan 1
        'hadir' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
        'terlambat' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
        'alpa' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
    ];

    public array $tabelAnatomi = [ // Untuk Pertemuan 2
        'pencacah' => ['isi' => '', 'alasan' => ''],
        'berhenti' => ['isi' => '', 'alasan' => ''],
        'perubahan' => ['isi' => '', 'alasan' => ''],
    ];

    public array $tabelVariabel = [ // Untuk Pertemuan 3
        'hadir' => ['nilai_awal' => '', 'letak' => '', 'batas_bawah' => '', 'batas_atas' => ''],
        'terlambat' => ['nilai_awal' => '', 'letak' => '', 'batas_bawah' => '', 'batas_atas' => ''],
        'alpa' => ['nilai_awal' => '', 'letak' => '', 'batas_bawah' => '', 'batas_atas' => ''],
    ];

    // 4. Common: Pilihan Bawah & Rencana
    public string $pilihanBentuk = '';
    public string $alasanPilihan = '';
    public string $rencanaKerja = '';

    public bool $sudahDicek = false;
    public bool $semuaBenar = false;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;

        // Melacak urutan pertemuan langsung dari database melalui relasi model
        $konten = \App\Models\KontenMisi::with('misi.pertemuan')->find($kontenMisiId);

        if ($konten && $konten->misi && $konten->misi->pertemuan) {
            // Mengambil kolom 'urutan' (1, 2, 3) dari tabel pertemuans
            $this->pertemuanKe = $konten->misi->pertemuan->urutan;
        } else {
            // Cadangan jika data tidak ditemukan
            $this->pertemuanKe = $konfigurasi['pertemuan'] ?? 1;
        }
    }

    public function cek()
    {
        $this->sudahDicek = true;
        $this->semuaBenar = true;

        if ($this->pertemuanKe == 1) {
            // Pengecekan Pertemuan 1: Percabangan[cite: 3]
            $kunciP1 = [
                'hadir' => ['batas_atas' => '700', 'kondisi_mengandung' => ['<=', '700']],
                'terlambat' => ['batas_bawah' => '701', 'batas_atas' => '730', 'kondisi_mengandung' => ['<=', '730']],
                'alpa' => ['batas_bawah' => '731', 'kondisi_mengandung' => ['else']]
            ];
            foreach ($kunciP1 as $status => $k) {
                if (isset($k['batas_bawah']) && trim($this->tabelKondisi[$status]['batas_bawah']) !== $k['batas_bawah']) $this->semuaBenar = false;
                if (isset($k['batas_atas']) && trim($this->tabelKondisi[$status]['batas_atas']) !== $k['batas_atas']) $this->semuaBenar = false;
            }
            if ($this->pilihanBentuk !== 'if_else_if') $this->semuaBenar = false;
        } elseif ($this->pertemuanKe == 2) {
            // Pengecekan Pertemuan 2: Anatomi Perulangan[cite: 4]
            $cekPencacah = str_replace(' ', '', strtolower($this->tabelAnatomi['pencacah']['isi']));
            $cekBerhenti = str_replace(' ', '', strtolower($this->tabelAnatomi['berhenti']['isi']));
            $cekPerubahan = str_replace(' ', '', strtolower($this->tabelAnatomi['perubahan']['isi']));

            if (!str_contains($cekPencacah, '1')) $this->semuaBenar = false;
            if (!str_contains($cekBerhenti, '<=') && !str_contains($cekBerhenti, 'jumlah')) $this->semuaBenar = false;
            if (!str_contains($cekPerubahan, '++') && !str_contains($cekPerubahan, '+1')) $this->semuaBenar = false;

            // For atau While benar, do-while salah[cite: 4]
            if (!in_array($this->pilihanBentuk, ['for', 'while'])) $this->semuaBenar = false;
        } elseif ($this->pertemuanKe == 3) {
            // Pengecekan Pertemuan 3: Variabel & Persentase[cite: 5]
            foreach (['hadir', 'terlambat', 'alpa'] as $status) {
                if (trim($this->tabelVariabel[$status]['nilai_awal']) !== '0') $this->semuaBenar = false;
                if ($this->tabelVariabel[$status]['letak'] !== 'sebelum') $this->semuaBenar = false;
            }
            if ($this->pilihanBentuk !== 'setelah') $this->semuaBenar = false;
        }
    }

    public function kirim()
    {
        // Validasi wajib isi
        $this->validate([
            'peran.juru_kode' => 'required|string',
            'peran.penyusun_alur' => 'required|string',
            'isian1' => 'required|string',
            'isian2' => 'required|string',
            'pilihanBentuk' => 'required|string',
            'alasanPilihan' => 'required|string|min:5',
            'rencanaKerja' => 'required|string|min:5',
        ]);

        // Menyusun payload data berdasarkan pertemuan
        $payload = [
            'pertemuan_ke' => $this->pertemuanKe,
            'nama_kelompok' => implode(', ', array_filter(array_values($this->peran))),
            'peran_kelompok' => $this->peran,
            'isian_atas' => ['isian_1' => $this->isian1, 'isian_2' => $this->isian2],
            'pilihan_bawah' => ['bentuk_atau_letak' => $this->pilihanBentuk, 'alasan' => $this->alasanPilihan],
            'rencana_kerja' => $this->rencanaKerja,
            'lengkap_benar' => $this->semuaBenar
        ];

        if ($this->pertemuanKe == 1) $payload['tabel_jawaban'] = $this->tabelKondisi;
        elseif ($this->pertemuanKe == 2) $payload['tabel_jawaban'] = $this->tabelAnatomi;
        elseif ($this->pertemuanKe == 3) $payload['tabel_jawaban'] = $this->tabelVariabel;

        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: $payload,
            poin: $this->semuaBenar ? 10 : 5,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.form-rumusan-masalah')->layout('layouts.questify');
    }
}