<?php

namespace App\Livewire\Teacher;

use App\Models\Lencana;
use Livewire\Component;

class LencanaManager extends Component
{

    public bool $formTerbuka = false;
    public ?int $editId = null;

    public string $kode = '';
    public string $nama = '';
    public string $deskripsi = '';
    public string $ikon = '🏅';
    public string $syarat = '';

    protected function rules(): array
    {
        return [
            'kode' => 'required|string|max:100|alpha_dash',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:10',
            'syarat' => 'nullable|string',
        ];
    }

    public function bukaForm(?int $id = null)
    {
        $this->resetValidation();
        $this->formTerbuka = true;
        $this->editId = $id;

        if ($id) {
            $l = Lencana::findOrFail($id);
            $this->fill($l->only(['kode', 'nama', 'deskripsi', 'ikon', 'syarat']));
        } else {
            $this->reset(['kode', 'nama', 'deskripsi', 'syarat']);
            $this->ikon = '🏅';
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
        Lencana::updateOrCreate(['id' => $this->editId], $data);
        session()->flash('sukses', 'Lencana disimpan.');
        $this->tutupForm();
    }

    public function hapus(int $id)
    {
        Lencana::findOrFail($id)->delete();
        session()->flash('sukses', 'Lencana dihapus.');
    }

    public function render()
    {
        return view('livewire.teacher.lencana-manager', [
            'lencanas' => Lencana::withCount('users')->get()
        ])->layout('layouts.questify', ['title' => 'Kelola Lencana']);;;
    }
}