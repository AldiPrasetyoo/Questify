<div class="min-h-screen bg-[#eafcff] font-['Nunito'] py-5 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-2">

        {{-- ============================================================
        HERO / PLAYER HEADER
    ============================================================= --}}
        <div
            class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-cyan-500 via-teal-500 to-cyan-600 p-8 text-white shadow-xl">

            {{-- Decorative --}}

            <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                {{-- KIRI --}}
                <div class="flex-1">

                    <div class="mb-4 text-xs font-bold uppercase tracking-[0.3em] text-white/70">
                        Questify Competition
                    </div>

                    <h1 class="text-4xl font-black tracking-tight">
                        Halo, {{ $user->name }}! 👋
                    </h1>

                    <p class="mt-3 max-w-xl text-base font-medium leading-relaxed text-white/80">
                        Kumpulkan skor dari setiap aktivitas pembelajaran
                        dan naikkan posisimu di papan peringkat.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                        {{-- SCORE --}}
                        <div class="rounded-2xl bg-white/15 px-5 py-3 backdrop-blur">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-white/60">
                                Skor Kamu
                            </div>

                            <div class="mt-1 text-xl font-black">
                                {{ number_format($skorSekarang) }}
                                <span class="text-sm font-bold text-white/60">Poin</span>
                            </div>
                        </div>

                        {{-- RANK --}}
                        <div class="rounded-2xl bg-white/15 px-5 py-3 backdrop-blur">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-white/60">
                                Peringkat
                            </div>

                            <div class="mt-1 text-xl font-black">
                                #{{ $peringkat ?? '-' }}
                            </div>
                        </div>

                    </div>

                </div>


                {{-- KANAN : COMPETITIVE CARD --}}
                <div class="w-full max-w-sm rounded-[26px] bg-white p-4 text-slate-800 shadow-2xl">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-2">
                            <span class="text-xl">🏆</span>

                            <span class="text-xs font-black uppercase tracking-wider text-slate-400">
                                Peringkat Saat Ini
                            </span>
                        </div>

                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-600">
                            #{{ $peringkat ?? '-' }}
                        </span>

                    </div>


                    {{-- SCORE --}}
                    <div class="mt-5">

                        <div class="text-4xl font-black text-slate-900">
                            {{ number_format($skorSekarang) }}
                            <span class="text-lg font-bold text-slate-400">
                                Poin
                            </span>
                        </div>

                        <div class="mt-1 text-xs font-semibold text-slate-400">
                            Total skor kompetisimu
                        </div>

                    </div>


                    {{-- TARGET ATAS --}}
                    @if($siswaDiAtas)

                    <div class="mt-5 rounded-2xl bg-cyan-50 p-4">

                        <div class="flex items-center gap-2">

                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-100 text-lg">
                                🎯
                            </div>

                            <div class="flex-1">

                                <div class="text-[10px] font-bold uppercase tracking-wider text-cyan-600">
                                    Target Berikutnya
                                </div>

                                <div class="mt-0.5 font-black text-slate-800">
                                    {{ $siswaDiAtas->name }}
                                </div>

                            </div>

                            <div class="text-right">

                                <div class="text-xs font-black text-cyan-600">
                                    #{{ $peringkat - 1 }}
                                </div>

                                <div class="text-[10px] font-bold text-slate-400">
                                    {{ number_format($siswaDiAtas->xp) }} Poin
                                </div>

                            </div>

                        </div>

                        <div class="mt-3 text-xs font-bold text-slate-500">
                            Butuh
                            <span class="font-black text-cyan-600">
                                {{ number_format($selisihAtas) }} Poin
                            </span>
                            lagi untuk menyusul!
                        </div>

                    </div>

                    @else

                    {{-- RANK 1 --}}
                    <div class="mt-5 rounded-2xl bg-amber-50 p-4">

                        <div class="flex items-center gap-3">

                            <div class="text-3xl">
                                👑
                            </div>

                            <div>
                                <div class="text-xs font-black uppercase text-amber-600">
                                    Kamu berada di puncak!
                                </div>

                                <div class="mt-1 text-sm font-bold text-slate-600">
                                    Pertahankan posisi #1.
                                </div>
                            </div>

                        </div>

                    </div>

                    @endif


                    {{-- POSISI BAWAH --}}
                    @if($siswaDiBawah)

                    <div class="mt-4 flex items-center justify-between text-xs">

                        <span class="font-semibold text-slate-400">
                            Posisi di bawahmu
                        </span>

                        <span class="font-black text-slate-700">
                            #{{ $peringkat + 1 }}
                        </span>

                    </div>

                    <div class="mt-1 flex items-center justify-between">

                        <span class="text-sm font-bold text-slate-600">
                            {{ $siswaDiBawah->name }}
                        </span>

                        <span class="text-xs font-bold text-emerald-500">
                            - {{ number_format($selisihBawah) }} Poin
                        </span>

                    </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ============================================================
        QUICK ACCESS
    ============================================================= --}}
        <div class="mt-6">

            <div class="mb-5">

                <div class="text-xs font-black uppercase tracking-[0.25em] text-cyan-500">
                    Questify
                </div>

                <h2 class="mt-1 text-xl font-black text-slate-800">
                    Akses Pembelajaran
                </h2>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- PERTEMUAN --}}
                <a href="{{ $sudahPretest && $pertemuans->first()
            ? route('pertemuan.show', $pertemuans->first())
            : '#' }}"
                    class="group rounded-3xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                    <div class="flex items-center justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-50 text-xl">
                            📚
                        </div>

                        <span class="text-slate-300 transition group-hover:translate-x-1">
                            →
                        </span>

                    </div>

                    <h3 class="mt-5 font-black text-slate-800">
                        Pertemuan
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Materi dan misi pembelajaran
                    </p>

                </a>


                {{-- PERINGKAT --}}
                <a href="{{ route('leaderboard') }}"
                    class="group rounded-3xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                    <div class="flex items-center justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-xl">
                            🏆
                        </div>

                        <span class="text-slate-300 transition group-hover:translate-x-1">
                            →
                        </span>

                    </div>

                    <h3 class="mt-5 font-black text-slate-800">
                        Papan Peringkat
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Lihat posisi dan skor siswa
                    </p>

                </a>


                {{-- RAPOR --}}
                <a href="{{ route('rapor') }}"
                    class="group rounded-3xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                    <div class="flex items-center justify-between">

                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-50 text-xl">
                            📊
                        </div>

                        <span class="text-slate-300 transition group-hover:translate-x-1">
                            →
                        </span>

                    </div>

                    <h3 class="mt-5 font-black text-slate-800">
                        Rapor Saya
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Lihat perkembangan hasil belajar
                    </p>

                </a>

            </div>

        </div>


        {{-- ============================================================
        LEARNING PROGRESS
    ============================================================= --}}
        <section class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-5 sm:p-7">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div>

                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400">
                        Your Journey
                    </p>

                    <h2 class="text-xl sm:text-xl font-black text-gray-800 mt-1">
                        Progress Pembelajaran
                    </h2>

                </div>

                <div
                    class="inline-flex items-center gap-2 self-start sm:self-auto bg-cyan-50 text-[#00aeb8] px-3 py-2 rounded-xl text-[10px] font-black">

                    <span class="w-2 h-2 rounded-full bg-[#00c2cb] animate-pulse"></span>

                    {{ $sudahPretest ? 'Pembelajaran Aktif' : 'Menunggu Pre-Test' }}

                </div>

            </div>


            {{-- Progress bar --}}
            <div class="mt-7">

                <div class="flex items-center justify-between mb-2">

                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-wider">
                        Overall Progress
                    </span>

                    <span class="text-sm font-black text-[#00aeb8]">
                        {{ $sudahPretest ? '25%' : '0%' }}
                    </span>

                </div>

                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">

                    <div class="h-full rounded-full bg-gradient-to-r from-[#00c2cb] to-teal-400 transition-all duration-700"
                        style="width: {{ $sudahPretest ? '25%' : '0%' }}">
                    </div>

                </div>

            </div>


            {{-- Journey cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-6">

                {{-- PRETEST --}}
                <div
                    class="rounded-2xl border p-4 {{ $sudahPretest ? 'bg-emerald-50 border-emerald-100' : 'bg-cyan-50 border-cyan-100' }}">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-11 h-11 rounded-xl {{ $sudahPretest ? 'bg-emerald-500 text-white' : 'bg-[#00c2cb] text-white' }} flex items-center justify-center font-black">

                            {{ $sudahPretest ? '✓' : '1' }}

                        </div>

                        <div>

                            <p class="text-[9px] uppercase font-black text-gray-400">
                                Tahap 01
                            </p>

                            <h3 class="text-sm font-black text-gray-800">
                                Pre-Test
                            </h3>

                        </div>

                    </div>

                    <p class="text-[10px] font-semibold text-gray-400 mt-3">
                        {{ $sudahPretest ? 'Telah diselesaikan.' : 'Wajib diselesaikan terlebih dahulu.' }}
                    </p>

                </div>


                {{-- PEMBELAJARAN --}}
                <div
                    class="rounded-2xl border p-4 {{ $sudahPretest ? 'bg-cyan-50 border-cyan-100' : 'bg-gray-50 border-gray-100' }}">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-11 h-11 rounded-xl {{ $sudahPretest ? 'bg-[#00c2cb] text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center font-black">

                            {{ $sudahPretest ? '2' : '🔒' }}

                        </div>

                        <div>

                            <p class="text-[9px] uppercase font-black text-gray-400">
                                Tahap 02
                            </p>

                            <h3 class="text-sm font-black {{ $sudahPretest ? 'text-gray-800' : 'text-gray-400' }}">
                                Misi Belajar
                            </h3>

                        </div>

                    </div>

                    <p class="text-[10px] font-semibold mt-3 {{ $sudahPretest ? 'text-gray-400' : 'text-gray-300' }}">
                        {{ $sudahPretest ? 'Materi dan tantangan tersedia.' : 'Terkunci sampai Pre-Test selesai.' }}
                    </p>

                </div>


                {{-- POSTTEST --}}
                <div
                    class="rounded-2xl border p-4 {{ $syaratPosttest ? 'bg-indigo-50 border-indigo-100' : 'bg-gray-50 border-gray-100' }}">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-11 h-11 rounded-xl {{ $syaratPosttest ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center font-black">

                            @if($sudahPosttest)
                            ✓
                            @elseif($syaratPosttest)
                            3
                            @else
                            🔒
                            @endif

                        </div>

                        <div>

                            <p class="text-[9px] uppercase font-black text-gray-400">
                                Tahap 03
                            </p>

                            <h3 class="text-sm font-black {{ $syaratPosttest ? 'text-gray-800' : 'text-gray-400' }}">
                                Post-Test
                            </h3>

                        </div>

                    </div>

                    <p class="text-[10px] font-semibold mt-3 {{ $syaratPosttest ? 'text-gray-400' : 'text-gray-300' }}">
                        @if($sudahPosttest)
                        Telah diselesaikan 🎉
                        @elseif($syaratPosttest)
                        Siap dikerjakan!
                        @else
                        Terbuka setelah Pertemuan 3.
                        @endif
                    </p>

                </div>

            </div>

        </section>


        {{-- ============================================================
        PRETEST LOCK
    ============================================================= --}}
        @if(!$sudahPretest)

        <section class="relative overflow-hidden rounded-[2rem] bg-white border border-cyan-100 shadow-sm">

            <div class="absolute right-0 top-0 w-72 h-72 bg-cyan-50 rounded-full -translate-y-1/2 translate-x-1/3">
            </div>

            <div class="relative p-4 sm:p-8 lg:p-10">

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">

                    <div class="max-w-2xl">

                        <div class="inline-flex items-center gap-2 bg-cyan-50 text-[#00aeb8] px-3 py-1.5 rounded-full">
                            <span>🎯</span>
                            <span class="text-[9px] font-black uppercase tracking-wider">
                                Mission Start
                            </span>
                        </div>

                        <h2 class="text-xl sm:text-3xl font-black text-gray-800 mt-4">
                            Mulai Petualanganmu!
                        </h2>

                        <p class="text-sm text-gray-500 font-semibold mt-2 leading-relaxed">
                            Sebelum memasuki misi pembelajaran, selesaikan Pre-Test
                            untuk mengetahui kemampuan awalmu.
                        </p>


                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-6">

                            <div class="bg-gray-50 rounded-2xl p-4">
                                <span class="text-xl">📝</span>
                                <p class="text-[9px] font-black uppercase text-gray-400 mt-2">
                                    Challenge
                                </p>
                                <p class="text-xs font-black text-gray-700 mt-1">
                                    Pre-Test
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-4">
                                <span class="text-xl">🔓</span>
                                <p class="text-[9px] font-black uppercase text-gray-400 mt-2">
                                    Unlock
                                </p>
                                <p class="text-xs font-black text-gray-700 mt-1">
                                    Materi Belajar
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-4">
                                <span class="text-xl">🚀</span>
                                <p class="text-[9px] font-black uppercase text-gray-400 mt-2">
                                    Reward
                                </p>
                                <p class="text-xs font-black text-gray-700 mt-1">
                                    XP & Progress
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="w-full lg:w-[220px]">

                        @if($pretest)

                        <a href="{{ route('ujian.kerjakan', 'pretest') }}"
                            class="group flex items-center justify-center gap-3 w-full bg-[#00aeb8] hover:bg-[#008f98] text-white font-black text-sm py-4 px-6 rounded-2xl shadow-lg shadow-cyan-200 hover:-translate-y-1 transition-all">

                            <span>Mulai Pre-Test</span>

                            <span class="text-lg group-hover:translate-x-1 transition-transform">
                                →
                            </span>

                        </a>

                        <p class="text-center text-[9px] font-bold text-gray-400 mt-3">
                            Pastikan kamu siap sebelum memulai.
                        </p>

                        @else

                        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 text-center">

                            <span class="text-xl">
                                ⚠️
                            </span>

                            <p class="text-xs font-black text-amber-700 mt-2">
                                Pre-Test belum tersedia.
                            </p>

                        </div>

                        @endif

                    </div>

                </div>

            </div>

        </section>


        {{-- LOCKED --}}
        <section class="bg-gray-50 border border-dashed border-gray-200 rounded-[2rem] p-8 text-center">

            <div
                class="w-16 h-16 mx-auto rounded-3xl bg-white border border-gray-100 flex items-center justify-center text-xl shadow-sm">
                🔒
            </div>

            <h3 class="font-black text-gray-700 text-lg mt-4">
                Misi Belum Terbuka
            </h3>

            <p class="text-xs sm:text-sm font-semibold text-gray-400 max-w-lg mx-auto mt-2">
                Selesaikan Pre-Test untuk membuka seluruh materi,
                pertemuan, dan misi pembelajaran.
            </p>

        </section>


        @else


        {{-- ========================================================
            UNLOCKED BANNER
        ========================================================= --}}
        <section
            class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-emerald-500 to-teal-500 shadow-lg shadow-emerald-100">

            <div class="absolute right-[-30px] top-[-70px] w-52 h-52 rounded-full bg-white/10"></div>

            <div class="relative p-5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-5">

                <div class="flex items-center gap-4">

                    <div
                        class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-xl text-white shrink-0">
                        ✓
                    </div>

                    <div class="text-white">

                        <div class="flex items-center gap-2 flex-wrap">

                            <h2 class="font-black text-lg">
                                Learning Zone Terbuka!
                            </h2>

                            <span class="text-[8px] font-black uppercase bg-white/15 px-2.5 py-1 rounded-full">
                                UNLOCKED
                            </span>

                        </div>

                        <p class="text-xs text-white/75 font-semibold mt-1">
                            Pre-Test selesai. Sekarang waktunya menaklukkan semua misi pembelajaran! 🚀
                        </p>

                    </div>

                </div>

                <div class="shrink-0 text-right">

                    <p class="text-[8px] uppercase font-black text-white/50">
                        Status
                    </p>

                    <p class="text-xs font-black text-white">
                        ● Learning Active
                    </p>

                </div>

            </div>

        </section>


        {{-- ========================================================
            MISSIONS
        ========================================================= --}}
        <section>

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">

                <div>

                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-[#00aeb8]">
                        Stage 02
                    </p>

                    <h2 class="text-xl font-black text-gray-800 mt-1">
                        Misi Pembelajaran 🗺️
                    </h2>

                    <p class="text-xs font-semibold text-gray-400 mt-1">
                        Pilih pertemuan dan lanjutkan perjalananmu.
                    </p>

                </div>

                <div
                    class="self-start sm:self-auto bg-white border border-gray-100 px-4 py-2 rounded-xl text-[10px] font-black text-gray-500 shadow-sm">
                    {{ $pertemuans->count() }} Pertemuan
                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($pertemuans as $p)
                @php
                $terbuka = $p->is_unlocked ?? true;
                @endphp

                <article class="group relative overflow-hidden rounded-[2rem] border transition-all duration-300
        {{ $terbuka 
            ? 'bg-white border-gray-100 shadow-sm hover:shadow-[0_20px_45px_rgba(0,174,184,0.13)] hover:-translate-y-1.5' 
            : 'bg-slate-50/80 border-slate-200/80 opacity-90' }}">

                    {{-- Accent Strip --}}
                    <div class="h-1.5 {{ $terbuka ? 'bg-gradient-to-r from-[#00c2cb] to-teal-400' : 'bg-slate-300' }}">
                    </div>

                    <div class="p-5 flex flex-col justify-between h-[calc(100%-6px)]">
                        <div>
                            <div class="flex items-start justify-between">
                                {{-- Avatar Urutan / Ikon Gembok --}}
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg
                        {{ $terbuka ? 'bg-cyan-50 text-[#00aeb8]' : 'bg-slate-200 text-slate-400' }}">
                                    @if($terbuka)
                                    {{ str_pad($p->urutan, 2, '0', STR_PAD_LEFT) }}
                                    @else
                                    <span>🔒</span>
                                    @endif
                                </div>

                                {{-- Badge Status --}}
                                @if($terbuka)
                                <span
                                    class="text-[9px] font-black uppercase bg-cyan-50 text-[#00aeb8] px-3 py-1.5 rounded-full">
                                    {{ $p->misis_count ?? 0 }} Misi
                                </span>
                                @else
                                <span
                                    class="text-[9px] font-black uppercase bg-slate-200 text-slate-500 px-3 py-1.5 rounded-full flex items-center gap-1">
                                    Terkunci
                                </span>
                                @endif
                            </div>

                            <div class="mt-5">
                                <p
                                    class="text-[9px] font-black uppercase tracking-wider {{ $terbuka ? 'text-gray-400' : 'text-slate-400' }}">
                                    Stage {{ $p->urutan }}
                                </p>

                                <h3 class="text-xl font-black mt-1 {{ $terbuka ? 'text-gray-800' : 'text-slate-600' }}">
                                    {{ $p->judul }}
                                </h3>

                                <p
                                    class="text-xs font-semibold mt-2 leading-relaxed line-clamp-2 {{ $terbuka ? 'text-gray-400' : 'text-slate-400' }}">
                                    {{ $p->deskripsi ?? 'Selesaikan misi pada pertemuan ini untuk melanjutkan perjalanan.' }}
                                </p>
                            </div>

                            {{-- Alert info prasyarat yang belum tuntas jika terkunci --}}
                            @if(!$terbuka && $p->lock_reason)
                            <div
                                class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-200/60 flex items-start gap-2.5">
                                <span class="text-sm shrink-0">⚠️</span>
                                <p class="text-[11px] font-bold text-amber-800 leading-tight">
                                    {{ $p->lock_reason }}
                                </p>
                            </div>
                            @endif

                            @if($p->tanggal_tatap_muka && $terbuka)
                            <div class="flex items-center gap-2 mt-4 text-[10px] font-bold text-gray-400">
                                <span>📅</span>
                                <span>
                                    {{ \Carbon\Carbon::parse($p->tanggal_tatap_muka)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        {{-- Action Button --}}
                        <div class="mt-6">
                            @if($terbuka)
                            <a href="{{ route('pertemuan.show', $p) }}"
                                class="group/btn flex items-center justify-between w-full bg-gray-50 hover:bg-[#00c2cb] text-gray-700 hover:text-white px-5 py-3.5 rounded-2xl font-black text-xs transition-all shadow-sm">
                                <span>Masuk Pertemuan</span>
                                <span class="text-lg group-hover/btn:translate-x-1 transition-transform">→</span>
                            </a>
                            @else
                            <button type="button" disabled
                                class="cursor-not-allowed flex items-center justify-center gap-2 w-full bg-slate-200/80 text-slate-400 px-5 py-3.5 rounded-2xl font-black text-xs">
                                <span>🔒 Terkunci</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </article>

                @empty
                <div
                    class="md:col-span-2 lg:col-span-3 bg-white rounded-[2rem] border border-dashed border-gray-200 p-14 text-center">
                    <div class="text-5xl mb-4">📭</div>
                    <h3 class="font-black text-gray-700">Belum Ada Pertemuan</h3>
                    <p class="text-xs font-semibold text-gray-400 mt-2">
                        Guru belum membuka pertemuan pembelajaran.
                    </p>
                </div>
                @endforelse
            </div>

        </section>


        {{-- ========================================================
            POST TEST
        ========================================================= --}}
        <section
            class="relative overflow-hidden rounded-[2rem] border {{ $syaratPosttest ? 'bg-gradient-to-br from-indigo-500 to-violet-600 border-indigo-200 shadow-lg shadow-indigo-100' : 'bg-white border-gray-100 shadow-sm' }}">

            <div
                class="absolute right-[-50px] top-[-80px] w-64 h-64 rounded-full {{ $syaratPosttest ? 'bg-white/10' : 'bg-gray-50' }}">
            </div>

            <div class="relative p-4 sm:p-8">

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $syaratPosttest ? 'bg-white/15 text-white' : 'bg-gray-50 text-gray-400' }}">

                            @if($sudahPosttest)
                            ✓
                            @elseif($syaratPosttest)
                            🎯
                            @else
                            🔒
                            @endif

                        </div>


                        <div>

                            <p
                                class="text-[9px] uppercase tracking-wider font-black {{ $syaratPosttest ? 'text-white/50' : 'text-gray-400' }}">
                                Final Challenge
                            </p>

                            <div class="flex flex-wrap items-center gap-2 mt-1">

                                <h2 class="font-black text-xl {{ $syaratPosttest ? 'text-white' : 'text-gray-800' }}">
                                    Post-Test
                                </h2>

                                @if($sudahPosttest)

                                <span
                                    class="text-[8px] font-black uppercase bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full">
                                    Selesai
                                </span>

                                @elseif($syaratPosttest)

                                <span
                                    class="text-[8px] font-black uppercase bg-white/15 text-white px-2.5 py-1 rounded-full">
                                    Terbuka
                                </span>

                                @else

                                <span
                                    class="text-[8px] font-black uppercase bg-gray-100 text-gray-400 px-2.5 py-1 rounded-full">
                                    Terkunci
                                </span>

                                @endif

                            </div>


                            <p
                                class="text-xs font-semibold mt-2 leading-relaxed {{ $syaratPosttest ? 'text-white/75' : 'text-gray-400' }}">

                                @if($sudahPosttest)

                                Perjalanan belajarmu telah selesai. Hebat! 🎉

                                @elseif($syaratPosttest)

                                Semua syarat terpenuhi. Saatnya buktikan kemampuanmu!

                                @else

                                Selesaikan pembelajaran hingga Pertemuan 3
                                untuk membuka tantangan akhir.

                                @endif

                            </p>

                        </div>

                    </div>


                    <div class="shrink-0">

                        @if($sudahPosttest)

                        <div
                            class="bg-emerald-50 text-emerald-600 px-6 py-3.5 rounded-2xl text-xs font-black text-center">
                            ✓ Telah Selesai
                        </div>

                        @elseif($syaratPosttest && $posttest)

                        <a href="{{ route('ujian.kerjakan', 'posttest') }}"
                            class="flex items-center justify-center gap-2 bg-white text-indigo-600 hover:bg-gray-50 px-6 py-3.5 rounded-2xl font-black text-xs shadow-lg transition hover:-translate-y-0.5">

                            Mulai Post-Test
                            <span>→</span>

                        </a>

                        @else

                        <div class="bg-white/70 text-gray-400 px-6 py-3.5 rounded-2xl text-xs font-black text-center">
                            🔒 Terkunci
                        </div>

                        @endif

                    </div>

                </div>

            </div>

        </section>

        @endif


        {{-- ============================================================
        FOOTER TIP
    ============================================================= --}}
        <div class="text-center py-3">

            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-300">
                Questify Learning Platform
            </p>

            <p class="text-[10px] text-gray-400 font-semibold mt-1">
                Belajar • Selesaikan Misi • Raih Achievement 🚀
            </p>

        </div>

    </div>

</div>