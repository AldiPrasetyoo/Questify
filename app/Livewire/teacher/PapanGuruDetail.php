<?php

namespace App\Livewire\Teacher;

use App\Models\KontenMisi;
use App\Models\Pertemuan;
use App\Models\ProgresKonten;
use App\Models\User;
use App\Services\AnalisisKesiapan;
use Livewire\Component;

class PapanGuruDetail extends Component
{
    public function render()
    {
        $siswa = User::where('role', 'student')->get();
        $analisis = app(AnalisisKesiapan::class);

        // Sebaran nilai uji kesiapan per pertemuan
        $sebaranPerPertemuan = [];
        foreach (Pertemuan::orderBy('urutan')->get() as $pertemuan) {
            $nilaiSemua = $siswa->map(function (User $u) use ($analisis, $pertemuan) {
                $k = collect($analisis->perPertemuan($u))->firstWhere('pertemuan.id', $pertemuan->id);
                return $k['persentase'] ?? null;
            })->filter(fn ($v) => $v !== null);

            $sebaranPerPertemuan[] = [
                'pertemuan' => $pertemuan,
                'rata_rata' => $nilaiSemua->isEmpty() ? null : round($nilaiSemua->avg()),
                'jumlah_di_bawah_ambang' => $nilaiSemua->filter(fn ($v) => $v < 75)->count(),
                'total_mengerjakan' => $nilaiSemua->count(),
            ];
        }

        // Ambil data progres refleksi lengkap dengan user dan pertemuannya
        $refleksiKontenIds = KontenMisi::where('tipe', 'refleksi')->pluck('id');
        
        $jawabanRefleksi = ProgresKonten::with(['user', 'kontenMisi.misi.pertemuan'])
            ->whereIn('konten_misi_id', $refleksiKontenIds)
            ->whereIn('user_id', $siswa->pluck('id'))
            ->where('selesai', true)
            ->latest('updated_at')
            ->get()
            ->map(function ($p) {
                $teks = trim((string) ($p->data['jawaban'] ?? $p->data['teks'] ?? ''));
                if (empty($teks)) return null;

                return [
                    'nama_siswa' => $p->user->name ?? 'Siswa Anonim',
                    'pertemuan'  => $p->kontenMisi->misi->pertemuan->urutan ?? null,
                    'teks'       => $teks,
                    'waktu'      => $p->updated_at ? $p->updated_at->diffForHumans() : 'Baru saja',
                ];
            })
            ->filter()
            ->values();

        return view('livewire.teacher.papan-guru-detail', [
            'sebaranPerPertemuan'    => $sebaranPerPertemuan,
            'jawabanRefleksiTeratas' => $jawabanRefleksi,
            'totalSiswa'             => $siswa->count(),
        ])->layout('layouts.questify', ['title' => 'Papan Guru Detail']);
    }
}