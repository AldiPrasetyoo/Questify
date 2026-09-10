<?php

namespace App\Services;

use App\Models\Misi;
use App\Models\Rapor;
use App\Models\User;

class PenghitungRapor
{
    /** Hitung ulang nilai rapor user untuk 1 pertemuan, berdasarkan total poin per fase dari poin_logs */
    public function perbaruiUntuk(User $user, int $pertemuanId): Rapor
    {
        $poinPraKelas = $this->totalPoinFase($user, $pertemuanId, 'pra_kelas');
        $poinTatapMuka = $this->totalPoinFase($user, $pertemuanId, 'tatap_muka');
        $poinPascaKelas = $this->totalPoinFase($user, $pertemuanId, 'pasca_kelas');

        $totalPoinPertemuan = $user->poinLogs()
            ->whereIn('misi_id', Misi::where('pertemuan_id', $pertemuanId)->pluck('id'))
            ->sum('jumlah');

        return Rapor::updateOrCreate(
            ['user_id' => $user->id, 'pertemuan_id' => $pertemuanId],
            [
                'nilai_pra_kelas' => $poinPraKelas,
                'nilai_tatap_muka' => $poinTatapMuka,
                'nilai_pasca_kelas' => $poinPascaKelas,
                'total_poin' => $totalPoinPertemuan,
            ]
        );
    }

    private function totalPoinFase(User $user, int $pertemuanId, string $fase): ?int
    {
        $misiIds = Misi::where('pertemuan_id', $pertemuanId)
            ->where('fase', $fase)
            ->pluck('id');

        if ($misiIds->isEmpty()) {
            return null; // fase ini tidak ada di pertemuan ini
        }

        // Ambil akumulasi poin langsung dari tabel poin_logs milik user
        $poinDidapat = $user->poinLogs()
            ->whereIn('misi_id', $misiIds)
            ->sum('jumlah');

        return (int) $poinDidapat;
    }
}