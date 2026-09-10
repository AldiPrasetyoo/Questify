<?php

namespace App\Livewire\Student\Koding;

use Livewire\Component;

/** Penyorotan baris kode saat dieksekusi, sinkron dengan nilai jam yang diisi siswa */
class PenyorotanEksekusi extends Component
{
    public int $kontenMisiId;
    public array $konfigurasi = [];
    public int $jam = 715;

    public function mount(int $kontenMisiId, array $konfigurasi)
    {
        $this->kontenMisiId = $kontenMisiId;
        $this->konfigurasi = $konfigurasi;
        $this->jam = $konfigurasi['nilai_jam_awal'] ?? 715;
    }

    public function getBarisProperty(): array
    {
        $bentuk = $this->konfigurasi['bentuk'] ?? 'if_tunggal';
        return match ($bentuk) {
            'if_tunggal' => $this->alurIfTunggal(),
            'if_else' => $this->alurIfElse(),
            'if_else_if' => $this->alurIfElseIf(),
            default => [],
        };
    }

    private function alurIfTunggal(): array
    {
        $kondisi = $this->jam <= 700;
        $baris = ['if (jam <= 700) {'];
        if ($kondisi) $baris[] = '    cout << "Hadir";';
        $baris[] = '}';
        return ['kode' => $baris, 'aktif' => $kondisi ? [0, 1] : [0]];
    }

    private function alurIfElse(): array
    {
        $kondisi = $this->jam <= 700;
        $baris = ['if (jam <= 700) {', '    cout << "Hadir";', '} else {', '    cout << "Tidak hadir";', '}'];
        return ['kode' => $baris, 'aktif' => $kondisi ? [0, 1] : [0, 2, 3]];
    }

    private function alurIfElseIf(): array
    {
        $baris = ['if (jam <= 700) {', '    cout << "Hadir";', '} else if (jam <= 730) {', '    cout << "Terlambat";', '} else {', '    cout << "Alpa";', '}'];
        if ($this->jam <= 700) $aktif = [0, 1];
        elseif ($this->jam <= 730) $aktif = [0, 2, 3];
        else $aktif = [0, 2, 4, 5];
        return ['kode' => $baris, 'aktif' => $aktif];
    }

    public function selesai()
    {
        $this->dispatch('kontenSelesai', kontenMisiId: $this->kontenMisiId, data: ['dicoba' => true], poin: 5)
            ->to(\App\Livewire\Student\MisiViewer::class);
    }

    public function render()
    {
        return view('livewire.student.koding.penyorotan-eksekusi', [])->layout('layouts.questify');
    }
}
