<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageStudents extends Component
{
    use WithPagination;

    public string $cari = '';

    public function updatingCari()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Ambil aktivitas terakhir tiap user dari tabel sessions
        $sesiTerakhir = DB::table('sessions')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'))
            ->groupBy('user_id')
            ->pluck('last_activity', 'user_id');

        $batasOnline = Carbon::now()->subMinutes(5)->timestamp;

        $students = User::where('role', 'student')
            ->when($this->cari, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->cari . '%')
                        ->orWhere('email', 'like', '%' . $this->cari . '%');
                });
            })
            ->orderBy('name')
            ->paginate(12);

        // Pasangkan status online dan durasi ke koleksi siswa
        $students->getCollection()->transform(function ($student) use ($sesiTerakhir, $batasOnline) {
            $lastActivity = $sesiTerakhir[$student->id] ?? null;

            if ($lastActivity) {
                $waktuAktivitas = Carbon::createFromTimestamp($lastActivity);
                $isOnline = $lastActivity >= $batasOnline;
                $durasi = $isOnline
                    ? 'Aktif saat ini'
                    : 'Terakhir terlihat ' . $waktuAktivitas->diffForHumans();
                $waktuLengkap = $waktuAktivitas->translatedFormat('d M Y, H:i');
            } else {
                $isOnline = false;
                $durasi = 'Belum pernah aktif';
                $waktuLengkap = '-';
            }

            $student->is_online = $isOnline;
            $student->status_durasi = $durasi;
            $student->waktu_terakhir = $waktuLengkap;

            return $student;
        });

        return view('livewire.teacher.manage-students', [
            'students' => $students,
            'totalOnline' => $students->getCollection()->where('is_online', true)->count(),
        ])->layout('layouts.questify', ['title' => 'Daftar Siswa']);
    }
}