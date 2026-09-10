<?php

namespace App\Livewire\Teacher;

use App\Models\KontenMisi;
use App\Models\Misi;
use Livewire\Component;
use Livewire\WithFileUploads;

class KontenManageMisi extends Component
{
    use WithFileUploads;

    public Misi $misi;

    public bool $formTerbuka = false;
    public ?int $editId = null;

    public int $urutan = 1;
    public string $tipe = 'teks_web';
    public string $judul = '';
    public string $konten_html = '';
    public $gambar_upload = null; // file baru (opsional saat edit)
    public ?string $path_gambar_lama = null;
    public string $komponen_koding = 'evaluator-kondisi';
    public string $konfigurasi_koding_json = "{\n    \n}";
    public string $pertanyaan_refleksi = '';

    public array $daftarKomponenKoding = [
        'evaluator-kondisi' => 'Evaluator Ekspresi Kondisi (tabel operator relasi live)',
        'tabel-kebenaran' => 'Tabel Kebenaran',
        'penelusuran-loop' => 'Penelusuran Loop (loop tracer)',
        'simulasi-kehadiran' => 'Simulasi Rekap Kehadiran (Misi 1 P1)',
        'penyorotan-eksekusi' => 'Penyorotan Eksekusi (highlight baris + jam)',
        'bedah-kode' => 'Bedah Kode (diketuk)',
        'latihan-melengkapi' => 'Latihan Melengkapi (baris terkunci)',
        'form-rumusan-masalah' => 'Form Rumusan Masalah Kelompok (Misi 5)',
        'editor-kode-uji' => 'Editor Kode + Tabel Uji (Misi 6 — aman, interpreter sendiri)',
        'demo-karya' => 'Demo Karya (Misi 7)',
        'perbaikan-program' => 'Perbaikan Program (Misi 8 P1 — aman, interpreter sendiri)',
        'simulasi-penulisan-berulang' => 'Simulasi Penulisan Berulang (Misi 1 P2)',
        'latihan-isian-singkat' => 'Latihan Isian Singkat (reusable)',
        'perbandingan-while-dowhile' => 'Perbandingan While vs Do-While (Misi 3 P2)',
        'editor-kode-uji-perulangan' => 'Editor Kode + Tabel Uji Perulangan (Misi 6 P2)',
        'perbaikan-program-perulangan' => 'Perbaikan Program Perulangan (Misi 8 P2)',
        'simulasi-rekap-manual' => 'Simulasi Rekap Manual (Misi 1 P3)',
        'peragaan-berdampingan' => 'Peragaan Berdampingan (Misi 2 P3)',
        'penyorotan-cakupan-blok' => 'Penyorotan Cakupan Blok (Misi 3 P3)',
        'editor-kode-uji-kombinasi' => 'Editor Kode + Tabel Uji Kombinasi (Misi 6 P3)',
        'perbaikan-program-kombinasi' => 'Perbaikan Program Kombinasi (Misi 8 P3)',
    ];

    public function mount(Misi $misi)
    {
        $this->misi = $misi;
    }

    protected function rules(): array
    {
        $rules = [
            'urutan' => 'required|integer|min:1',
            'tipe' => 'required|in:teks_web,aset_ppt,koding,kuis,refleksi',
            'judul' => 'nullable|string|max:255',
        ];

        return match ($this->tipe) {
            'teks_web' => $rules + ['konten_html' => 'required|string'],
            'aset_ppt' => $rules + ['gambar_upload' => ($this->editId ? 'nullable' : 'required') . '|image|max:4096'],
            'koding' => $rules + [
                'komponen_koding' => 'required|string',
                'konfigurasi_koding_json' => 'required|json',
            ],
            'refleksi' => $rules + ['pertanyaan_refleksi' => 'required|string'],
            default => $rules, // 'kuis' -> soalnya dikelola terpisah di KuisSoalManager
        };
    }

    public function bukaForm(?int $id = null)
    {
        $this->resetValidation();
        $this->formTerbuka = true;
        $this->editId = $id;
        $this->gambar_upload = null;

        if ($id) {
            $k = KontenMisi::findOrFail($id);
            $this->urutan = $k->urutan;
            $this->tipe = $k->tipe;
            $this->judul = (string) $k->judul;
            $this->konten_html = (string) $k->konten_html;
            $this->path_gambar_lama = $k->path_gambar;
            $this->komponen_koding = $k->komponen_koding ?? 'evaluator-kondisi';
            $this->konfigurasi_koding_json = $k->konfigurasi_koding
                ? json_encode($k->konfigurasi_koding, JSON_PRETTY_PRINT)
                : "{\n    \n}";
            $this->pertanyaan_refleksi = (string) $k->pertanyaan_refleksi;
        } else {
            $this->reset(['judul', 'konten_html', 'path_gambar_lama', 'pertanyaan_refleksi']);
            $this->tipe = 'teks_web';
            $this->urutan = ((int) $this->misi->kontens()->max('urutan')) + 1;
            $this->konfigurasi_koding_json = "{\n    \n}";
        }
    }

    public function tutupForm()
    {
        $this->formTerbuka = false;
        $this->editId = null;
    }

    public function simpan()
    {
        $this->validate();

        $data = [
            'misi_id' => $this->misi->id,
            'urutan' => $this->urutan,
            'tipe' => $this->tipe,
            'judul' => $this->judul ?: null,
            'konten_html' => $this->tipe === 'teks_web' ? $this->konten_html : null,
            'komponen_koding' => $this->tipe === 'koding' ? $this->komponen_koding : null,
            'konfigurasi_koding' => $this->tipe === 'koding' ? json_decode($this->konfigurasi_koding_json, true) : null,
            'pertanyaan_refleksi' => $this->tipe === 'refleksi' ? $this->pertanyaan_refleksi : null,
        ];

        if ($this->tipe === 'aset_ppt') {
            $data['path_gambar'] = $this->gambar_upload
                ? $this->gambar_upload->store('konten-misi', 'public')
                : $this->path_gambar_lama;
        }

        KontenMisi::updateOrCreate(['id' => $this->editId], $data);

        session()->flash('sukses', 'Konten berhasil disimpan.');
        $this->tutupForm();
    }

    public function hapus(int $id)
    {
        KontenMisi::findOrFail($id)->delete();
        session()->flash('sukses', 'Konten dihapus.');
    }

    public function render()
    {
        return view('livewire.teacher.konten-manage-misi', [
            'kontens' => $this->misi->kontens()->withCount('kuisSoals')->get(),
        ])
            ->layout('layouts.questify', ['title' => 'Kelola Pertemuan']);
    }
}