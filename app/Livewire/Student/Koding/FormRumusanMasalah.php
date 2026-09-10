<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Form rumusan masalah kelompok — tabel kondisi dicek otomatis + pilihan bentuk percabangan */
class FormRumusanMasalah extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public string $namaKelompok = '';
    public array $tabelKondisi = [
        'hadir' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
        'terlambat' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
        'alpa' => ['batas_bawah' => '', 'batas_atas' => '', 'kondisi' => ''],
    ];
    public string $bentukDipilih = '';
    public string $alasan = '';
    public string $rencanaKerja = '';
    public bool $sudahDicek = false;
    public bool $semuaBenar = false;

    private array $kunci = [
        'hadir' => ['batas_atas' => '700', 'kondisi_mengandung' => ['<=', '700']],
        'terlambat' => ['batas_bawah' => '701', 'batas_atas' => '730', 'kondisi_mengandung' => ['<=', '730']],
        'alpa' => ['batas_bawah' => '731', 'kondisi_mengandung' => ['else']],
    ];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
    }

    public function cek()
    {
        $this->sudahDicek = true;
        $this->semuaBenar = true;
        foreach ($this->kunci as $status => $k) {
            $isian = $this->tabelKondisi[$status];
            if (isset($k['batas_bawah']) && trim($isian['batas_bawah']) !== $k['batas_bawah']) $this->semuaBenar = false;
            if (isset($k['batas_atas']) && trim($isian['batas_atas']) !== $k['batas_atas']) $this->semuaBenar = false;
            foreach ($k['kondisi_mengandung'] as $perlu) {
                if (! str_contains(strtolower(str_replace(' ', '', $isian['kondisi'])), strtolower(str_replace(' ', '', $perlu)))) {
                    $this->semuaBenar = false;
                }
            }
        }
        if ($this->bentukDipilih !== 'if_else_if') $this->semuaBenar = false;
    }

    public function kirim()
    {
        $this->validate([
            'namaKelompok' => 'required|string',
            'bentukDipilih' => 'required|string',
            'alasan' => 'required|string|min:5',
            'rencanaKerja' => 'required|string|min:5',
        ]);

        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['nama_kelompok' => $this->namaKelompok, 'tabel_kondisi' => $this->tabelKondisi, 'bentuk_dipilih' => $this->bentukDipilih, 'alasan' => $this->alasan, 'lengkap_benar' => $this->semuaBenar],
            poin: $this->semuaBenar ? 10 : 5,
        )->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.form-rumusan-masalah', [])->layout('layouts.questify');
    }
}
