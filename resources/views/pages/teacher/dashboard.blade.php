<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    // Data Dinamis
    public int $studentCount = 0;
    public int $readinessPercentage = 0;

    // Dummy Data untuk visualisasi dashboard
    public int $activeModuleCount = 4;
    public int $readyStudentCount = 24;
    public int $attentionStudentCount = 8;
    
    public int $pretestCompletedCount = 32;
    public int $materialCompletedCount = 28;
    public int $microExerciseCompletedCount = 24;
    public int $quizCompletedCount = 20;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Keamanan: Jika BUKAN teacher, kembalikan ke gerbang utama
        if (Auth::user()->role !== 'teacher') {
            return redirect()->route('dashboard'); 
        }

        // Mengambil jumlah siswa dari database
        $this->studentCount = User::where('role', 'student')->count();

        // Menghitung persentase kesiapan
        $this->readinessPercentage = $this->studentCount > 0 
            ? (int) round(($this->readyStudentCount / $this->studentCount) * 100) 
            : 0;
    }
};
?>

<x-layouts::questify :title="'Dashboard Guru'">
    <div class="min-h-screen bg-[#e0f7fa] font-['Nunito'] py-8">
        <div class="max-w-6xl mx-auto px-4 space-y-6">

            <!-- HERO SECTION -->
            <div
                class="bg-white rounded-[2rem] shadow-xl border-t-[6px] border-t-[#ff8a00] p-8 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="absolute -right-10 -top-10 opacity-10 pointer-events-none">
                    <span class="text-9xl">👨‍🏫</span>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="size-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-extrabold text-gray-500 tracking-wider">DASHBOARD GURU</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-800">
                        <!-- PERUBAHAN DISINI: Menggunakan auth()->user()->name secara langsung -->
                        Selamat datang, <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-[#ff8a00] to-orange-400">{{ auth()->user()->name }}!</span>
                    </h1>
                    <p class="text-sm font-semibold text-gray-500 mt-2 max-w-xl">
                        Pantau kesiapan belajar, perkembangan misi, serta hasil pembelajaran peserta didik dalam satu
                        ruang kerja Questify.
                    </p>
                </div>
            </div>

            <!-- STATISTIK RINGKAS -->
            <div>
                <h2 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                    <span>📊</span> Ringkasan Pembelajaran
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div
                        class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                                🧑‍💻</div>
                            <span
                                class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Siswa</span>
                        </div>
                        <p class="text-3xl font-black text-gray-800 relative z-10">{{ $studentCount }}</p>
                        <p class="text-xs font-bold text-gray-400 relative z-10">Total Terdaftar</p>
                    </div>

                    <div
                        class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div
                                class="w-10 h-10 rounded-xl bg-violet-50 text-violet-500 flex items-center justify-center text-xl">
                                📚</div>
                            <span
                                class="bg-violet-50 text-violet-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Materi</span>
                        </div>
                        <p class="text-3xl font-black text-gray-800 relative z-10">{{ $activeModuleCount }}</p>
                        <p class="text-xs font-bold text-gray-400 relative z-10">Modul Aktif</p>
                    </div>

                    <div
                        class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                                🚀</div>
                            <span
                                class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Aman</span>
                        </div>
                        <p class="text-3xl font-black text-gray-800 relative z-10">{{ $readyStudentCount }}</p>
                        <p class="text-xs font-bold text-gray-400 relative z-10">Siap Belajar</p>
                    </div>

                    <div
                        class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl">
                                ⚠️</div>
                            <span
                                class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Pantau</span>
                        </div>
                        <p class="text-3xl font-black text-gray-800 relative z-10">{{ $attentionStudentCount }}</p>
                        <p class="text-xs font-bold text-gray-400 relative z-10">Perlu Perhatian</p>
                    </div>

                </div>
            </div>

            <!-- KESIAPAN BELAJAR -->
            <div class="bg-white rounded-[1.5rem] p-6 sm:p-8 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <span class="text-[10px] font-extrabold text-gray-400 tracking-wider uppercase">PRE-CLASS
                            READINESS</span>
                        <h2 class="text-xl font-black text-gray-800 mt-1">Progres Kesiapan Peserta Didik</h2>
                        <p class="text-xs font-semibold text-gray-500 mt-1">Persentase siswa yang telah menyelesaikan
                            prakelas sebelum tatap muka.</p>
                    </div>
                    <div
                        class="w-20 h-20 rounded-full border-8 border-teal-50 flex items-center justify-center text-xl font-black text-[#00c2cb] shrink-0">
                        {{ $readinessPercentage }}%
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-gray-500">Penyelesaian Keseluruhan</span>
                        <span class="text-[#00c2cb]">{{ $readyStudentCount }} dari {{ $studentCount }} Siswa</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-gradient-to-r from-[#00c2cb] to-teal-400 h-3 rounded-full"
                            style="width: {{ $readinessPercentage }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Indikator 1 -->
                    <div class="border-2 border-blue-50 bg-blue-50/30 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl">📝</span>
                            <span
                                class="text-[10px] font-bold {{ $pretestCompletedCount === $studentCount && $studentCount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} px-2 py-1 rounded-full">
                                {{ $pretestCompletedCount === $studentCount && $studentCount > 0 ? 'LENGKAP' : 'DIPANTAU' }}
                            </span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-blue-500">Pretest</p>
                        <p class="text-lg font-black text-gray-800">{{ $pretestCompletedCount }} <span
                                class="text-xs text-gray-400 font-bold">/ {{ $studentCount }}</span></p>
                    </div>

                    <!-- Indikator 2 -->
                    <div class="border-2 border-violet-50 bg-violet-50/30 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl">📖</span>
                            <span
                                class="text-[10px] font-bold {{ $materialCompletedCount === $studentCount && $studentCount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} px-2 py-1 rounded-full">
                                {{ $materialCompletedCount === $studentCount && $studentCount > 0 ? 'LENGKAP' : 'DIPANTAU' }}
                            </span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-violet-500">Materi Baca</p>
                        <p class="text-lg font-black text-gray-800">{{ $materialCompletedCount }} <span
                                class="text-xs text-gray-400 font-bold">/ {{ $studentCount }}</span></p>
                    </div>

                    <!-- Indikator 3 -->
                    <div class="border-2 border-emerald-50 bg-emerald-50/30 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl">⚡</span>
                            <span
                                class="text-[10px] font-bold {{ $microExerciseCompletedCount === $studentCount && $studentCount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} px-2 py-1 rounded-full">
                                {{ $microExerciseCompletedCount === $studentCount && $studentCount > 0 ? 'LENGKAP' : 'DIPANTAU' }}
                            </span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-emerald-500">Latihan Mikro</p>
                        <p class="text-lg font-black text-gray-800">{{ $microExerciseCompletedCount }} <span
                                class="text-xs text-gray-400 font-bold">/ {{ $studentCount }}</span></p>
                    </div>

                    <!-- Indikator 4 -->
                    <div class="border-2 border-amber-50 bg-amber-50/30 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl">❓</span>
                            <span
                                class="text-[10px] font-bold {{ $quizCompletedCount === $studentCount && $studentCount > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} px-2 py-1 rounded-full">
                                {{ $quizCompletedCount === $studentCount && $studentCount > 0 ? 'LENGKAP' : 'DIPANTAU' }}
                            </span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-amber-500">Kuis Evaluasi</p>
                        <p class="text-lg font-black text-gray-800">{{ $quizCompletedCount }} <span
                                class="text-xs text-gray-400 font-bold">/ {{ $studentCount }}</span></p>
                    </div>
                </div>
            </div>

            <!-- AKTIVITAS UTAMA -->
            <div>
                <h2 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                    <span>⚙️</span> Menu Akses Cepat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100">
                        <div
                            class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl mb-4">
                            🛠️</div>
                        <h3 class="font-black text-gray-800 mb-1">Kelola Pertemuan</h3>
                        <p class="text-xs text-gray-500 font-semibold mb-6">Atur materi, latihan, dan urutan modul
                            belajar siswa.</p>
                        <a href="{{ route('pertemuan.index') }}"
                            class="inline-block bg-[#ff8a00] hover:bg-orange-600 text-white text-xs font-bold py-2.5 px-5 rounded-xl transition-colors">Buka
                            Pengaturan &rarr;</a>
                    </div>

                    <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100">
                        <div
                            class="w-12 h-12 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl mb-4">
                            📡</div>
                        <h3 class="font-black text-gray-800 mb-1">Monitoring Siswa</h3>
                        <p class="text-xs text-gray-500 font-semibold mb-6">Pantau aktivitas, waktu akses, dan kendala
                            siswa.</p>
                        <a href="#"
                            class="inline-block bg-[#00c2cb] hover:bg-teal-500 text-white text-xs font-bold py-2.5 px-5 rounded-xl transition-colors">Lihat
                            Pantauan &rarr;</a>
                    </div>

                    <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100">
                        <div
                            class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl mb-4">
                            🏆</div>
                        <h3 class="font-black text-gray-800 mb-1">Hasil Pembelajaran</h3>
                        <p class="text-xs text-gray-500 font-semibold mb-6">Rekap nilai evaluasi, posttest, dan
                            perkembangan skor.</p>
                        <a href="#"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2.5 px-5 rounded-xl transition-colors">Buka
                            Penilaian &rarr;</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-layouts::questify>