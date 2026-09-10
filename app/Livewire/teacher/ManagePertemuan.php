<?php

namespace App\Livewire\teacher;

use App\Models\Pertemuan;
use Livewire\Component;

class ManagePertemuan extends Component
{
    public bool $formTerbuka = false;
    public ?int $editId = null;

    // Properti Form
    public int $urutan = 1;
    public string $judul = '';
    public string $deskripsi = '';
    public string $tujuan_pembelajaran = '';
    public ?string $tanggal_tatap_muka = null;
    public bool $aktif = true;

    protected function rules(): array
    {
        return [
            'urutan' => 'required|integer|min:1',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tujuan_pembelajaran' => 'nullable|string',
            'tanggal_tatap_muka' => 'nullable|date',
            'aktif' => 'boolean',
        ];
    }

    public function bukaForm(?int $id = null)
    {
        $this->resetValidation();
        $this->formTerbuka = true;
        $this->editId = $id;

        if ($id) {
            $p = Pertemuan::findOrFail($id);
            $this->urutan = $p->urutan;
            $this->judul = $p->judul;
            $this->deskripsi = (string) $p->deskripsi;
            $this->tujuan_pembelajaran = (string) $p->tujuan_pembelajaran;
            $this->tanggal_tatap_muka = $p->tanggal_tatap_muka ? $p->tanggal_tatap_muka->format('Y-m-d') : null;
            $this->aktif = $p->aktif;
        } else {
            $this->reset(['judul', 'deskripsi', 'tujuan_pembelajaran', 'tanggal_tatap_muka']);
            $this->urutan = (Pertemuan::max('urutan') ?? 0) + 1;
            $this->aktif = true;
        }
    }

    public function tutupForm()
    {
        $this->formTerbuka = false;
        $this->editId = null;
    }

    public function simpan()
    {
        $data = $this->validate();

        if ($this->editId) {
            Pertemuan::findOrFail($this->editId)->update($data);
            session()->flash('sukses', 'Pertemuan berhasil diperbarui.');
        } else {
            Pertemuan::create($data);
            session()->flash('sukses', 'Pertemuan baru berhasil ditambahkan.');
        }

        $this->tutupForm();
    }

    public function hapus(int $id)
    {
        Pertemuan::findOrFail($id)->delete();
        session()->flash('sukses', 'Pertemuan berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.teacher.manage-pertemuan', [
            // Mengambil data pertemuan beserta hitungan jumlah relasi misinya
            'pertemuans' => Pertemuan::withCount('misis')->orderBy('urutan')->get()
        ])->layout('layouts.questify', ['title' => 'Kelola Pertemuan']);
    }
}