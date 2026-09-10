<?php

namespace App\Livewire\Teacher;

use App\Models\KontenMisi;
use App\Models\KuisSoal;
use Livewire\Component;


class KuisSoalManage extends Component
{
    public KontenMisi $kontenMisi;

    public bool $formTerbuka = false;
    public ?int $editId = null;

    public int $urutan = 1;
    public string $pertanyaan = '';
    public string $pilihan_a = '';
    public string $pilihan_b = '';
    public string $pilihan_c = '';
    public string $pilihan_d = '';
    public string $kunci_jawaban = 'A';
    public string $pembahasan = '';

    public function mount(KontenMisi $kontenMisi)
    {
        abort_unless($kontenMisi->tipe === 'kuis', 400, 'Konten ini bukan bertipe kuis.');
        $this->kontenMisi = $kontenMisi;
    }

    protected function rules(): array
    {
        return [
            'urutan' => 'required|integer|min:1',
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'kunci_jawaban' => 'required|in:A,B,C,D',
            'pembahasan' => 'nullable|string',
        ];
    }

    public function bukaForm(?int $id = null)
    {
        $this->resetValidation();
        $this->formTerbuka = true;
        $this->editId = $id;

        if ($id) {
            $s = KuisSoal::findOrFail($id);
            $this->urutan = $s->urutan;
            $this->pertanyaan = $s->pertanyaan;
            $this->pilihan_a = $s->pilihan['a'] ?? '';
            $this->pilihan_b = $s->pilihan['b'] ?? '';
            $this->pilihan_c = $s->pilihan['c'] ?? '';
            $this->pilihan_d = $s->pilihan['d'] ?? '';
            $this->kunci_jawaban = $s->kunci_jawaban;
            $this->pembahasan = (string) $s->pembahasan;
        } else {
            $this->reset(['pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pembahasan']);
            $this->urutan = ((int) $this->kontenMisi->kuisSoals()->max('urutan')) + 1;
            $this->kunci_jawaban = 'A';
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

        KuisSoal::updateOrCreate(['id' => $this->editId], [
            'konten_misi_id' => $this->kontenMisi->id,
            'urutan' => $this->urutan,
            'pertanyaan' => $this->pertanyaan,
            'pilihan' => [
                'a' => $this->pilihan_a,
                'b' => $this->pilihan_b,
                'c' => $this->pilihan_c,
                'd' => $this->pilihan_d,
            ],
            'kunci_jawaban' => $this->kunci_jawaban,
            'pembahasan' => $this->pembahasan ?: null,
        ]);

        session()->flash('sukses', 'Soal berhasil disimpan.');
        $this->tutupForm();
    }

    public function hapus(int $id)
    {
        KuisSoal::findOrFail($id)->delete();
        session()->flash('sukses', 'Soal dihapus.');
    }

    public function render()
    {
        return view('livewire.teacher.kuis-soal-manage', [
            'soals' => $this->kontenMisi->kuisSoals,
        ])
            ->layout('layouts.questify', ['title' => 'Kelola Kuis Soal']);;
    }
}