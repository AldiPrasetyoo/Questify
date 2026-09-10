<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Penelusuran loop otomatis — 3 kotak isian (nilai awal, kondisi, perubahan), tabel terisi otomatis */
class PenelusuranLoop extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public string $nilaiAwal = 'i = 1';
    public string $kondisi = 'i <= 5';
    public string $perubahan = 'i++';
    public bool $sudahDicoba = false;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
        $this->nilaiAwal = $konfigurasi['contoh_nilai_awal'] ?? 'i = 1';
        $this->kondisi = $konfigurasi['contoh_kondisi'] ?? 'i <= 5';
        $this->perubahan = $konfigurasi['contoh_perubahan'] ?? 'i++';
    }

    public function updated($nama) { if (in_array($nama, ['nilaiAwal', 'kondisi', 'perubahan'])) $this->sudahDicoba = true; }

    private function evaluasiKondisi(int $i, string $kondisi): bool
    {
        if (! preg_match('/i\s*(<=|>=|<|>|==|!=)\s*(-?\d+)/', $kondisi, $m)) return false;
        [, $op, $batas] = $m;
        $batas = (int) $batas;
        return match ($op) {
            '<=' => $i <= $batas, '>=' => $i >= $batas, '<' => $i < $batas,
            '>' => $i > $batas, '==' => $i === $batas, '!=' => $i !== $batas, default => false,
        };
    }

    private function hitungPerubahan(int $i, string $perubahan): ?int
    {
        $p = trim($perubahan);
        if ($p === '' || strtolower($p) === 'tidak ada') return null;
        if ($p === 'i++') return $i + 1;
        if ($p === 'i--') return $i - 1;
        if (preg_match('/i\s*\+=\s*(\d+)/', $p, $m)) return $i + (int) $m[1];
        if (preg_match('/i\s*-=\s*(\d+)/', $p, $m)) return $i - (int) $m[1];
        return null;
    }

    public function getPenelusuranProperty(): array
    {
        if (! preg_match('/i\s*=\s*(-?\d+)/', $this->nilaiAwal, $m)) {
            return ['baris' => [], 'keterangan' => 'Format nilai awal tidak dikenali, contoh: i = 1'];
        }
        $i = (int) $m[1];
        $baris = [];
        $batasAman = 50;
        while ($this->evaluasiKondisi($i, $this->kondisi) && count($baris) < $batasAman) {
            $baris[] = $i;
            $baru = $this->hitungPerubahan($i, $this->perubahan);
            if ($baru === null || $baru === $i) {
                return ['baris' => $baris, 'keterangan' => 'Dihentikan: nilai pencacah tidak pernah berubah, ini akan menjadi perulangan tak berhenti.'];
            }
            $i = $baru;
        }
        if (count($baris) >= $batasAman) {
            return ['baris' => $baris, 'keterangan' => 'Dihentikan di baris ke-50: kombinasi ini menghasilkan perulangan tak berhenti.'];
        }
        return ['baris' => $baris, 'keterangan' => count($baris) === 0 ? 'Tidak berjalan sama sekali: kondisi sudah salah sejak awal.' : null];
    }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['dicoba' => $this->sudahDicoba], poin: 10)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render() { return view('livewire.student.koding.penelusuran-loop'); }
}
