<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Tabel operator relasi live-update — nilai jam diubah siswa, seluruh kolom hasil ikut berubah */
class EvaluatorKondisi extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $jam = 715;
    public bool $sudahDicoba = false;

    public array $operators = [
        '==' => ['sama dengan', 700],
        '!=' => ['tidak sama dengan', 700],
        '>' => ['lebih dari', 730],
        '<' => ['kurang dari', 700],
        '>=' => ['lebih dari atau sama dengan', 701],
        '<=' => ['kurang dari atau sama dengan', 730],
    ];

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
        $this->jam = $konfigurasi['nilai_jam_awal'] ?? 715;
    }

    public function updatedJam()
    {
        $this->sudahDicoba = true;
    }

    private function evaluasi(string $operator, int $pembanding): bool
    {
        return match ($operator) {
            '==' => $this->jam === $pembanding,
            '!=' => $this->jam !== $pembanding,
            '>' => $this->jam > $pembanding,
            '<' => $this->jam < $pembanding,
            '>=' => $this->jam >= $pembanding,
            '<=' => $this->jam <= $pembanding,
            default => false,
        };
    }

    public function getBarisProperty(): array
    {
        $baris = [];
        foreach ($this->operators as $op => [$arti, $pembanding]) {
            $baris[] = ['operator' => $op, 'arti' => $arti, 'pembanding' => $pembanding, 'hasil' => $this->evaluasi($op, $pembanding)];
        }
        return $baris;
    }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['dicoba' => $this->sudahDicoba], poin: 10)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.evaluator-kondisi', [])->layout('layouts.questify');
    }
}
