<?php

namespace App\Livewire\Student;

use Livewire\Component;

class RefleksiTerbuka extends Component
{
    public int $kontenMisiId;
    public string $pertanyaan;
    public string $jawaban = '';

    public function mount(int $kontenMisiId, string $pertanyaan)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->pertanyaan = $pertanyaan;
    }

    public function kirim()
    {
        $this->validate(['jawaban' => 'required|min:10']);

        $this->dispatch(
            'kontenSelesai',
            kontenMisiId: $this->kontenMisiId,
            data: ['jawaban' => $this->jawaban],
            poin: 5,
        )->to(MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.refleksi-terbuka');
    }
}
