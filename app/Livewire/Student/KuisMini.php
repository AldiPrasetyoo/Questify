<?php

namespace App\Livewire\Student;

use App\Models\KuisJawaban;
use App\Models\KuisSoal;
use Livewire\Attributes\Computed; // Wajib di-import
use Livewire\Component;

class KuisMini extends Component
{
    public int $kontenMisiId;
    public int $indexSoal = 0;
    public ?string $jawabanDipilih = null;
    public bool $sudahDijawab = false;
    public bool $benar = false;
    public int $skorBenar = 0;

    public function mount(int $kontenMisiId)
    {
        $this->kontenMisiId = $kontenMisiId;
    }

    // Gunakan #[Computed] pengganti getSoalsProperty() di Livewire 3
    #[Computed]
    public function soals()
    {
        return KuisSoal::where('konten_misi_id', $this->kontenMisiId)->orderBy('urutan')->get();
    }

    public function jawab(string $pilihan)
    {
        $soal = $this->soals[$this->indexSoal];
        $this->jawabanDipilih = $pilihan;
        $this->benar = $pilihan === $soal->kunci_jawaban;
        $this->sudahDijawab = true;

        $percobaanKe = KuisJawaban::where('user_id', auth()->id())
            ->where('kuis_soal_id', $soal->id)
            ->count() + 1;

        KuisJawaban::create([
            'user_id' => auth()->id(),
            'kuis_soal_id' => $soal->id,
            'jawaban_dipilih' => $pilihan,
            'benar' => $this->benar,
            'percobaan_ke' => $percobaanKe,
        ]);

        if ($this->benar) {
            $this->skorBenar++;
            $this->dispatch('suara-benar');
        } else {
            $this->dispatch('suara-salah');
        }
    }

    public function lanjut()
    {
        $total = $this->soals->count();

        if ($this->indexSoal + 1 < $total) {
            $this->indexSoal++;
            $this->jawabanDipilih = null;
            $this->sudahDijawab = false;
            return;
        }

        // ==========================================
        // SKEMA POIN KUIS (ADA PEMBEDA JIKA BENAR SEMUA)
        // ==========================================
        $poinPerSoal = 10;
        $bonusSempurna = 20; // Tambahan bonus jika tidak ada salah

        if ($this->skorBenar === $total && $total > 0) {
            // Benar semua: dapat total poin soal + bonus kesempurnaan
            $poin = ($total * $poinPerSoal) + $bonusSempurna;
        } else {
            // Hanya dapat poin dari soal yang benar
            $poin = $this->skorBenar * $poinPerSoal;
        }

        $data = [
            'skor_benar'     => $this->skorBenar,
            'total_soal'     => $total,
            'sempurna'       => ($this->skorBenar === $total),
            'poin_dihasilkan' => $poin,
        ];

        $this->dispatch('kontenSelesai', $this->kontenMisiId, $data, $poin);
    }

    public function render()
    {
        return view('livewire.student.kuis-mini');
    }
}
