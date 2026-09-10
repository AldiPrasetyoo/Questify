<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use App\Models\Pertemuan;
use App\Models\Misi;
use App\Models\ProgresMisi;
use App\Models\Ujian;
use App\Models\JawabanUjian;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherDashboard extends Component
{
    public function render()
    {
        $teacher = Auth::user();

        // 1. Total siswa terdaftar
        $studentCount = User::where('role', 'student')->count();

        // 2. Modul/Pertemuan aktif
        $activeModuleCount = Pertemuan::where('aktif', true)->count();

        // 3. Logika Kesiapan Belajar & Perlu Pendampingan
        $totalMisiCount = Misi::count();
        $readyStudentCount = 0;
        $attentionStudentCount = 0;

        $students = User::where('role', 'student')->with('progresMisis')->get();

        foreach ($students as $student) {
            $selesaiCount = $student->progresMisis->where('status', 'selesai')->count();

            if ($totalMisiCount > 0 && ($selesaiCount / $totalMisiCount) >= 0.5) {
                $readyStudentCount++;
            } else {
                $attentionStudentCount++;
            }
        }

        $readinessPercentage = $studentCount > 0 
            ? (int) round(($readyStudentCount / $studentCount) * 100) 
            : 0;

        // 4. Hitung Nilai & Partisipasi Berdasarkan tabel jawaban_ujians
        $pretest = Ujian::where('tipe', 'pretest')->first();
        $posttest = Ujian::where('tipe', 'posttest')->first();

        $hitungUjian = function (?Ujian $ujian) {
            if (!$ujian) {
                return ['avg' => 0, 'count' => 0];
            }

            $jawabanGrouped = JawabanUjian::where('ujian_id', $ujian->id)
                ->get()
                ->groupBy('user_id');

            $totalSiswaMengerjakan = $jawabanGrouped->count();

            if ($totalSiswaMengerjakan === 0) {
                return ['avg' => 0, 'count' => 0];
            }

            $skorSiswa = $jawabanGrouped->map(function ($jawabans) {
                $totalSoal = $jawabans->count();
                $benar = $jawabans->where('is_benar', 1)->count();
                return $totalSoal > 0 ? ($benar / $totalSoal) * 100 : 0;
            });

            return [
                'avg'   => (int) round($skorSiswa->avg()),
                'count' => $totalSiswaMengerjakan,
            ];
        };

        $dataPretest = $hitungUjian($pretest);
        $dataPosttest = $hitungUjian($posttest);

        // 5. Indikator Rinci Aktivitas Pembelajaran
        $materialCompletedCount = ProgresMisi::where('status', 'selesai')
            ->whereHas('misi', function ($q) {
                $q->where('judul', 'like', '%Materi%')
                  ->orWhere('judul', 'like', '%Orientasi%');
            })
            ->distinct('user_id')
            ->count('user_id');

        $microExerciseCompletedCount = ProgresMisi::where('status', 'selesai')
            ->whereHas('misi', function ($q) {
                $q->where('judul', 'like', '%Koding%')
                  ->orWhere('judul', 'like', '%Latihan%')
                  ->orWhere('judul', 'like', '%Bangun Program%');
            })
            ->distinct('user_id')
            ->count('user_id');

        $quizCompletedCount = ProgresMisi::where('status', 'selesai')
            ->whereHas('misi', function ($q) {
                $q->where('judul', 'like', '%Kuis%')
                  ->orWhere('judul', 'like', '%Uji Kesiapan%');
            })
            ->distinct('user_id')
            ->count('user_id');

        return view('livewire.teacher.teacher-dashboard', [
            'user'                        => $teacher,
            'studentCount'                => $studentCount,
            'activeModuleCount'           => $activeModuleCount,
            'readyStudentCount'           => $readyStudentCount,
            'attentionStudentCount'       => $attentionStudentCount,
            'readinessPercentage'         => $readinessPercentage,
            'avgPretest'                  => $dataPretest['avg'],
            'avgPosttest'                 => $dataPosttest['avg'],
            'pretestCompletedCount'       => $dataPretest['count'],
            'posttestCompletedCount'      => $dataPosttest['count'],
            'materialCompletedCount'      => $materialCompletedCount,
            'microExerciseCompletedCount' => $microExerciseCompletedCount,
            'quizCompletedCount'          => $quizCompletedCount,
        ])->layout('layouts.questify', ['title' => 'Dashboard Guru']);
    }
}