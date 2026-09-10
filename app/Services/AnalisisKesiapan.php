<?php

namespace App\Services;

use App\Models\KuisJawaban;
use App\Models\KuisSoal;
use App\Models\Misi;
use App\Models\Pertemuan;
use App\Models\User;

/**
 * Menghitung nilai kuis "Uji Kesiapan" (Misi 4) tiap pertemuan untuk seorang siswa,
 * lalu menandai pertemuan mana yang capaiannya masih di bawah ambang batas (75) —
 * dipakai untuk rekomendasi materi yang perlu diulang sebelum post test, sesuai
 * dokumen: "guru perlu mengetahui materi mana yang masih lemah bagi tiap peserta didik".
 */
class AnalisisKesiapan
{
    private const AMBANG_BATAS = 75;

    /** @return array<int, array{pertemuan: Pertemuan, persentase: ?int, perlu_diulang: bool}> */
    public function perPertemuan(User $user): array
    {
        $hasil = [];

        foreach (Pertemuan::orderBy('urutan')->get() as $pertemuan) {
            $misiUjiKesiapan = Misi::where('pertemuan_id', $pertemuan->id)
                ->where('judul', 'like', '%Uji Kesiapan%')->first();

            if (! $misiUjiKesiapan) {
                $hasil[] = ['pertemuan' => $pertemuan, 'persentase' => null, 'perlu_diulang' => false];
                continue;
            }

            $kontenKuisIds = $misiUjiKesiapan->kontens()->where('tipe', 'kuis')->pluck('id');
            $soalIds = KuisSoal::whereIn('konten_misi_id', $kontenKuisIds)->pluck('id');

            $jawabanTerbaru = KuisJawaban::where('user_id', $user->id)
                ->whereIn('kuis_soal_id', $soalIds)
                ->orderByDesc('created_at')
                ->get()
                ->unique('kuis_soal_id'); // hanya percobaan terakhir per soal yang dihitung

            $persentase = $soalIds->isEmpty() || $jawabanTerbaru->isEmpty()
                ? null
                : (int) round($jawabanTerbaru->where('benar', true)->count() / $soalIds->count() * 100);

            $hasil[] = [
                'pertemuan' => $pertemuan,
                'persentase' => $persentase,
                'perlu_diulang' => $persentase !== null && $persentase < self::AMBANG_BATAS,
            ];
        }

        return $hasil;
    }
}
