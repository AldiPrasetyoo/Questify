<?php

namespace App\Livewire\Student;

use App\Models\Pertemuan;
use App\Models\Ujian;
use App\Models\ProgresUjian;
use App\Models\User;
// Pastikan import model progres misi jika ada (misal App\Models\ProgresMisi)
use App\Models\ProgresMisi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\AnggotaKelompok;

class StudentDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // 1. DATA MASTER UJIAN
        $pretest = Ujian::where('tipe', 'pretest')->first();
        $posttest = Ujian::where('tipe', 'posttest')->first();

        // 2. STATUS PENGERJAAN UJIAN
        $sudahPretest = $pretest
            ? ProgresUjian::where('user_id', $user->id)
            ->where('ujian_id', $pretest->id)
            ->exists()
            : false;

        $sudahPosttest = $posttest
            ? ProgresUjian::where('user_id', $user->id)
            ->where('ujian_id', $posttest->id)
            ->exists()
            : false;

        // 3. SYARAT POSTTEST
        $syaratPosttest = $user->rapors()
            ->whereHas('pertemuan', function ($q) {
                $q->where('urutan', '>=', 3);
            })
            ->where('nilai_tatap_muka', '>', 0)
            ->exists();

        // 4. DAFTAR ID MISI YANG TELAH DISELESAIKAN SISWA
        // Mengambil seluruh id misi yang sudah selesai dikerjakan oleh user ini
        $misiSelesaiIds = ProgresMisi::where('user_id', $user->id)
            ->where('status', "selesai")
            ->pluck('misi_id')
            ->toArray();

        // 5. DATA PERTEMUAN & LOGIKA GEMBOK/KUNCI
        $pertemuans = collect();

        if ($sudahPretest) {
            $rawPertemuans = Pertemuan::where('aktif', true)
                ->withCount('misis')
                ->with(['misis.prasyarat.pertemuan'])
                ->orderBy('urutan')
                ->get();

            $pertemuans = $rawPertemuans->map(function ($p) use ($misiSelesaiIds) {
                $isUnlocked = true;
                $lockReason = null;

                // Cek semua misi di dalam pertemuan ini
                foreach ($p->misis as $misi) {
                    // Jika misi punya prasyarat yang berasal dari pertemuan lain
                    if ($misi->misi_prasyarat_id && $misi->prasyarat) {
                        $prasyarat = $misi->prasyarat;

                        // Jika prasyarat ada di pertemuan sebelumnya dan belum tuntas
                        if ($prasyarat->pertemuan_id !== $p->id && !in_array($prasyarat->id, $misiSelesaiIds)) {
                            $isUnlocked = false;
                            $namaPertemuanAsal = $prasyarat->pertemuan ? "Pertemuan {$prasyarat->pertemuan->urutan}" : 'sebelumnya';
                            $lockReason = "Selesaikan misi \"{$prasyarat->judul}\" di {$namaPertemuanAsal}";
                            break; // Stop pengecekan, pertemuan langsung digembok
                        }
                    }
                }

                $p->is_unlocked = $isUnlocked;
                $p->lock_reason = $lockReason;
                return $p;
            });
        }

        // 6. DATA KOMPETISI
        $semuaSiswa = User::where('role', 'student')
            ->orderByDesc('total_poin')
            ->orderBy('name')
            ->get();

        $skorSekarang = (int) ($user->total_poin ?? 0);

        $indexSiswa = $semuaSiswa->search(fn($siswa) => $siswa->id === $user->id);
        $peringkat = $indexSiswa !== false ? $indexSiswa + 1 : null;
        $totalSiswa = $semuaSiswa->count();

        $siswaDiAtas = ($peringkat && $peringkat > 1) ? $semuaSiswa->get($peringkat - 2) : null;
        $siswaDiBawah = ($peringkat && $peringkat < $totalSiswa) ? $semuaSiswa->get($peringkat) : null;

        $selisihAtas = $siswaDiAtas ? max(0, (int) $siswaDiAtas->total_poin - $skorSekarang) : 0;
        $selisihBawah = $siswaDiBawah ? max(0, $skorSekarang - (int) $siswaDiBawah->total_poin) : 0;

        $kelompokSaya = AnggotaKelompok::where('user_id', auth()->id())
            ->with('kelompok')
            ->first()?->kelompok;

        return view('livewire.student.student-dashboard', [
            'user'           => $user,
            'pretest'        => $pretest,
            'posttest'       => $posttest,
            'sudahPretest'   => $sudahPretest,
            'sudahPosttest'  => $sudahPosttest,
            'syaratPosttest' => $syaratPosttest,
            'pertemuans'     => $pertemuans,
            'semuaSiswa'     => $semuaSiswa,
            'skorSekarang'   => $skorSekarang,
            'peringkat'      => $peringkat,
            'totalSiswa'     => $totalSiswa,
            'siswaDiAtas'    => $siswaDiAtas,
            'siswaDiBawah'   => $siswaDiBawah,
            'selisihAtas'    => $selisihAtas,
            'selisihBawah'   => $selisihBawah,
            'kelompokSaya'   => $kelompokSaya, 
        ])->layout('layouts.questify', ['title' => 'Dashboard Siswa']);
    }
}