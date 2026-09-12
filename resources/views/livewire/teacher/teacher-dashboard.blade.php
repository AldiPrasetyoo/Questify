<div class="min-h-screen bg-[#f8fafc] font-['Nunito'] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- HERO SECTION --}}
        <div
            class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-[2.5rem] shadow-xl p-8 sm:p-10 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 text-white border border-slate-800">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none select-none">
                <span class="text-[12rem]">👨‍🏫</span>
            </div>

            <div class="relative z-10 max-w-2xl">
                <div
                    class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-extrabold tracking-wider uppercase mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Pendidik Questify
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                    Selamat datang,
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00c2cb] to-teal-300">
                        {{ $user->name }}!
                    </span>
                </h1>

                <p class="text-slate-400 text-sm sm:text-base font-medium mt-3 leading-relaxed">
                    Pantau kesiapan belajar, perkembangan misi, serta rekam jejak hasil evaluasi peserta didik secara
                    real-time dalam satu pusat kendali.
                </p>
            </div>

            <div class="relative z-10 flex flex-wrap sm:flex-nowrap gap-3 shrink-0">
                <a href="{{ route('teacher.pertemuan') }}"
                    class="bg-[#ff8a00] hover:bg-orange-600 text-white font-extrabold px-6 py-3.5 rounded-2xl text-sm shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                    <span>🛠️</span> Kelola Modul Misi
                </a>
            </div>
        </div>

        {{-- STATISTIK UTAMA (DENGAN SLIDER OTOMATIS PRETEST & POSTTEST) --}}
        <div x-data="{
    modeUjian: 'pretest',
    init() {
        setInterval(() => {
            this.modeUjian = (this.modeUjian === 'pretest') ? 'posttest' : 'pretest';
        }, 4000); // Berganti otomatis setiap 4 detik
    }
}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- 1. Total Siswa --}}
                <div
                    class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            🧑‍💻
                        </div>
                        <span
                            class="bg-blue-50 text-blue-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                            Peserta
                        </span>
                    </div>
                    <p class="text-4xl font-black text-slate-800 tracking-tight">{{ $studentCount }}</p>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Total Siswa Terdaftar</p>
                </div>

                {{-- 2. Modul Kurikulum --}}
                <div
                    class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            📚
                        </div>
                        <span
                            class="bg-violet-50 text-violet-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                            Kurikulum
                        </span>
                    </div>
                    <p class="text-4xl font-black text-slate-800 tracking-tight">{{ $activeModuleCount }}</p>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Pertemuan Aktif</p>
                </div>

                {{-- 3. Rata-rata Nilai Ujian (Bisa Berganti Pre-Test & Post-Test) --}}
                <div
                    class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            📊
                        </div>
                        {{-- Badge Status Berganti --}}
                        <span
                            class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider transition-colors duration-300"
                            :class="modeUjian === 'pretest' ? 'bg-cyan-50 text-[#00c2cb]' : 'bg-emerald-50 text-emerald-600'">
                            <span
                                x-text="modeUjian === 'pretest' ? 'Rata-rata Pre-Test' : 'Rata-rata Post-Test'"></span>
                        </span>
                    </div>

                    {{-- Nilai Berganti --}}
                    <div class="relative h-10">
                        {{-- Pre-Test --}}
                        <div x-show="modeUjian === 'pretest'"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200 absolute transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="text-4xl font-black text-slate-800 tracking-tight">
                            {{ $avgPretest }}<span class="text-lg text-slate-400 font-bold">/100</span>
                        </div>

                        {{-- Post-Test --}}
                        <div x-show="modeUjian === 'posttest'"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200 absolute transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="text-4xl font-black text-emerald-600 tracking-tight">
                            {{ $avgPosttest }}<span class="text-lg text-emerald-400 font-bold">/100</span>
                        </div>
                    </div>

                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">
                        <span
                            x-text="modeUjian === 'pretest' ? 'Nilai Rata-rata Pre-Test' : 'Nilai Rata-rata Post-Test'"></span>
                    </p>
                </div>

                {{-- 4. Partisipasi / Jumlah Mengerjakan (Bisa Berganti Pre-Test & Post-Test) --}}
                <div
                    class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            📝
                        </div>
                        {{-- Badge Status Berganti --}}
                        <span
                            class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider transition-colors duration-300"
                            :class="modeUjian === 'pretest' ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600'">
                            <span
                                x-text="modeUjian === 'pretest' ? 'Partisipasi Pre-Test' : 'Partisipasi Post-Test'"></span>
                        </span>
                    </div>

                    {{-- Jumlah Siswa Berganti --}}
                    <div class="relative h-10">
                        {{-- Siswa Pre-Test --}}
                        <div x-show="modeUjian === 'pretest'"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200 absolute transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="text-4xl font-black text-slate-800 tracking-tight">
                            {{ $pretestCompletedCount }}<span
                                class="text-lg text-slate-400 font-bold">/{{ $studentCount }}</span>
                        </div>

                        {{-- Siswa Post-Test --}}
                        <div x-show="modeUjian === 'posttest'"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200 absolute transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="text-4xl font-black text-indigo-600 tracking-tight">
                            {{ $posttestCompletedCount }}<span
                                class="text-lg text-indigo-300 font-bold">/{{ $studentCount }}</span>
                        </div>
                    </div>

                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">
                        <span x-text="modeUjian === 'pretest' ? 'Selesai Pre-Test' : 'Selesai Post-Test'"></span>
                    </p>
                </div>

            </div>
        </div>

        {{-- SECTION ANALISIS KESIAPAN BELAJAR --}}
        <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 pb-6 border-b border-slate-100">

                {{-- AKSES CEPAT MANAJEMEN PEMBELAJARAN (5 KARTU NAVIGASI RAPI) --}}
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            <span>⚡</span> Pusat Kendali Guru
                        </h2>
                        <span class="text-xs font-bold text-slate-400">Navigasi fitur administrasi & materi</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">

                        {{-- 1. Kelola Siswa --}}
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-emerald-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div
                                    class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm">
                                    👥
                                </div>
                                <h3 class="text-base font-black text-slate-800 mb-1.5">Data Siswa</h3>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-5">
                                    Daftar akun peserta didik, status aktif, dan profil belajar.
                                </p>
                            </div>
                            <a href="{{ route('manage.student') }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white text-xs font-black py-2.5 rounded-xl transition-all">
                                <span>Kelola Siswa</span>
                            </a>
                        </div>

                        {{-- 2. Kelola Soal Ujian --}}
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-amber-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div
                                    class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm">
                                    📑
                                </div>
                                <h3 class="text-base font-black text-slate-800 mb-1.5">Bank Soal Ujian</h3>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-5">
                                    Konfigurasi butir soal, kunci, dan pengaturan Pre-Test & Post-Test.
                                </p>
                            </div>
                            <a href="{{ Route::has('ujian.manage') ? route('ujian.manage') : (Route::has('teacher.ujian') ? route('teacher.ujian') : '#') }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white text-xs font-black py-2.5 rounded-xl transition-all">
                                <span>Atur Soal</span>
                            </a>
                        </div>

                        {{-- 3. Kelola Modul Pertemuan --}}
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-orange-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div
                                    class="w-12 h-12 bg-orange-50 text-[#ff8a00] rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm">
                                    🛠️
                                </div>
                                <h3 class="text-base font-black text-slate-800 mb-1.5">Modul Pertemuan</h3>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-5">
                                    Atur tahapan kurikulum, fase pra-kelas, dan konten belajar.
                                </p>
                            </div>
                            <a href="{{ route('teacher.pertemuan') }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-orange-50 hover:bg-[#ff8a00] text-orange-600 hover:text-white text-xs font-black py-2.5 rounded-xl transition-all">
                                <span>Buka Modul</span>
                            </a>
                        </div>

                        {{-- 4. Manajemen Lencana --}}
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-teal-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div
                                    class="w-12 h-12 bg-teal-50 text-[#00c2cb] rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm">
                                    🏆
                                </div>
                                <h3 class="text-base font-black text-slate-800 mb-1.5">Lencana XP</h3>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-5">
                                    Atur kriteria pencapaian otomatis dan apresiasi gamifikasi.
                                </p>
                            </div>
                            <a href="{{ route('lencana.index') }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-teal-50 hover:bg-[#00c2cb] text-teal-600 hover:text-white text-xs font-black py-2.5 rounded-xl transition-all">
                                <span>Atur Lencana</span>
                            </a>
                        </div>

                        {{-- 5. Papan Evaluasi & Rapor --}}
                        <div
                            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div
                                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm">
                                    📊
                                </div>
                                <h3 class="text-base font-black text-slate-800 mb-1.5">Evaluasi & Rapor</h3>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-5">
                                    Rekap nilai komprehensif, umpan balik guru, dan hasil refleksi.
                                </p>
                            </div>
                            <a href="{{ route('papan-guru-detail') }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-black py-2.5 rounded-xl transition-all">
                                <span>Buka Penilaian</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>