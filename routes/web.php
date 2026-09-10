<?php

use Illuminate\Support\Facades\Route;

// ======================================================
// TEACHER COMPONENTS
// ======================================================
use App\Livewire\teacher\TeacherDashboard;
use App\Livewire\teacher\ManagePertemuan;
use App\Livewire\teacher\ManageMisi;
use App\Livewire\teacher\KontenManageMisi;
use App\Livewire\teacher\KuisSoalManage;
use App\Livewire\teacher\LencanaManager;
use App\Livewire\teacher\PapanGuru;
use App\Livewire\teacher\PapanGuruDetail;
use App\Livewire\teacher\KelolaUjian;

// ======================================================
// STUDENT COMPONENTS
// ======================================================
use App\Livewire\Student\StudentDashboard;
use App\Livewire\Student\MisiViewer;
use App\Livewire\Student\PertemuanShow;
use App\Livewire\Student\PapanPeringkat;
use App\Livewire\Student\RaporSiswa;
use App\Livewire\Student\KerjakanUjian;

// ======================================================
// CONTROLLER
// ======================================================
use App\Http\Controllers\teacher\downloadData;
use App\Livewire\Teacher\ManageStudents;

// ======================================================
// HALAMAN UTAMA
// ======================================================

Route::view('/', 'welcome')->name('home');


// ======================================================
// DASHBOARD UTAMA
// /dashboard
//
// teacher -> /teacher/dashboard
// student -> /home
// ======================================================

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    abort(403, 'Role pengguna tidak dikenali.');
})->name('dashboard');


// ======================================================
// ROUTE YANG BISA DIAKSES SEMUA USER LOGIN
//
// Teacher + Student
// ======================================================

Route::middleware([
    'auth',
    'verified'
])->group(function () {

    // ==================================================
    // RAPOR
    //
    // Bisa diakses:
    // - Teacher
    // - Student
    // ==================================================

    Route::get(
        '/rapor',
        RaporSiswa::class
    )->name('rapor');
});


// ======================================================
// TEACHER ROUTES
// ======================================================

Route::middleware([
    'auth',
    'verified',
    'role:teacher'
])->group(function () {

    // ==================================================
    // DASHBOARD GURU
    // ==================================================

    Route::get(
        '/teacher/dashboard',
        TeacherDashboard::class
    )->name('teacher.dashboard');


    // ==================================================
    // PERTEMUAN
    // ==================================================

    Route::get(
        '/teacher/pertemuan',
        ManagePertemuan::class
    )->name('teacher.pertemuan');


    // ==================================================
    // MISI
    // ==================================================

    Route::get(
        '/teacher/pertemuan/{pertemuan}/misi',
        ManageMisi::class
    )->name('teacher.misi');


    // ==================================================
    // KONTEN MISI
    // ==================================================

    Route::get(
        '/misi/{misi}/konten',
        KontenManageMisi::class
    )->name('konten.index');


    // ==================================================
    // SOAL KUIS
    // ==================================================

    Route::get(
        '/konten/{kontenMisi}/soal',
        KuisSoalManage::class
    )->name('soal.index');


    // ==================================================
    // LENCANA
    // ==================================================

    Route::get(
        '/lencana',
        LencanaManager::class
    )->name('lencana.index');

    // ==================================================
    // Student Manage
    // ==================================================

    Route::get(
        '/student',
        ManageStudents::class
    )->name('manage.student');


    // ==================================================
    // PAPAN GURU
    // ==================================================

    Route::get(
        '/papan-guru/{pertemuan}',
        PapanGuru::class
    )->name('papan-guru');


    // ==================================================
    // DETAIL PAPAN GURU
    // ==================================================

    Route::get(
        '/papan-guru-detail',
        PapanGuruDetail::class
    )->name('papan-guru-detail');


    // ==================================================
    // KELOLA UJIAN
    // ==================================================

    Route::get(
        '/teacher/kelola-ujian',
        KelolaUjian::class
    )->name('teacher.ujian');


    // ==================================================
    // EXPORT DATA
    // ==================================================

    Route::get(
        '/teacher/export-rekap',
        [downloadData::class, 'downloadExcel']
    )->name('teacher.export');
});


// ======================================================
// STUDENT ROUTES
// ======================================================

Route::middleware([
    'auth',
    'verified',
    'role:student'
])->group(function () {

    // ==================================================
    // DASHBOARD SISWA
    // ==================================================

    Route::get(
        '/home',
        StudentDashboard::class
    )->name('student.dashboard');


    // ==================================================
    // PERTEMUAN
    // ==================================================

    Route::get(
        '/pertemuan/{pertemuan}',
        PertemuanShow::class
    )->name('pertemuan.show');


    // ==================================================
    // MISI
    // ==================================================

    Route::get(
        '/misi/{misi}',
        MisiViewer::class
    )->name('misi.show');


    // ==================================================
    // PAPAN PERINGKAT
    // ==================================================

    Route::get(
        '/papan-peringkat',
        PapanPeringkat::class
    )->name('leaderboard');


    // ==================================================
    // KERJAKAN UJIAN
    // ==================================================

    Route::get(
        '/kerjakan-ujian/{tipe}',
        KerjakanUjian::class
    )->name('ujian.kerjakan');
});


// ======================================================
// SETTINGS
// ======================================================

require __DIR__ . '/settings.php';