<?php

namespace App\Services;

use App\Models\Lencana;
use App\Models\Misi;
use App\Models\ProgresMisi;
use App\Models\User;

/**
 * Dipanggil setiap kali sebuah misi ditandai selesai (lihat ProgresMisiObserver).
 * Mengecek seluruh lencana yang punya 'kriteria' terstruktur, dan memberikannya
 * ke siswa kalau syaratnya terpenuhi dan belum pernah didapat.
 */
class PengecekLencana
{
    public function cekUntuk(User $user): void
    {
        $lencanas = Lencana::whereNotNull('kriteria')->get();
        $sudahPunya = $user->lencanas()->pluck('lencanas.id')->all();

        foreach ($lencanas as $lencana) {
            if (in_array($lencana->id, $sudahPunya)) {
                continue;
            }

            if ($this->syaratTerpenuhi($user, $lencana->kriteria ?? [])) {
                $user->lencanas()->attach($lencana->id, ['diperoleh_pada' => now()]);
            }
        }
    }

    private function syaratTerpenuhi(User $user, array $kriteria): bool
    {
        return match ($kriteria['tipe'] ?? null) {
            'selesaikan_misi' => ProgresMisi::where('user_id', $user->id)
                ->where('misi_id', $kriteria['misi_id'] ?? 0)->where('status', 'selesai')->exists(),
            'selesaikan_fase' => $this->cekSelesaikanFase($user, $kriteria),
            'selesaikan_pertemuan' => $this->cekSelesaikanPertemuan($user, $kriteria),
            'kuis_sempurna' => $this->cekKuisSempurna($user, $kriteria),
            'total_poin' => $user->total_poin >= ($kriteria['minimal'] ?? PHP_INT_MAX),
            default => false,
        };
    }

    private function cekSelesaikanFase(User $user, array $kriteria): bool
    {
        $misiIds = Misi::where('pertemuan_id', $kriteria['pertemuan_id'] ?? 0)
            ->where('fase', $kriteria['fase'] ?? '')
            ->pluck('id');

        if ($misiIds->isEmpty()) {
            return false;
        }

        $selesai = ProgresMisi::where('user_id', $user->id)
            ->whereIn('misi_id', $misiIds)->where('status', 'selesai')->count();

        return $selesai >= $misiIds->count();
    }

    private function cekSelesaikanPertemuan(User $user, array $kriteria): bool
    {
        $misiIds = Misi::where('pertemuan_id', $kriteria['pertemuan_id'] ?? 0)->pluck('id');

        if ($misiIds->isEmpty()) {
            return false;
        }

        $selesai = ProgresMisi::where('user_id', $user->id)
            ->whereIn('misi_id', $misiIds)->where('status', 'selesai')->count();

        return $selesai >= $misiIds->count();
    }

    private function cekKuisSempurna(User $user, array $kriteria): bool
    {
        $misi = Misi::find($kriteria['misi_id'] ?? 0);
        if (! $misi) return false;

        $kontenKuisIds = $misi->kontens()->where('tipe', 'kuis')->pluck('id');
        $progres = \App\Models\ProgresKonten::where('user_id', $user->id)
            ->whereIn('konten_misi_id', $kontenKuisIds)->get();

        foreach ($progres as $p) {
            if (($p->data['skor_benar'] ?? 0) < ($p->data['total_soal'] ?? 1)) {
                return false;
            }
        }

        return $progres->count() > 0;
    }
}
