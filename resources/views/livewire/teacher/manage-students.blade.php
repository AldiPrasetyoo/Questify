<div class="max-w-7xl mx-auto px-4 py-8 font-['Nunito'] space-y-6" wire:poll.15s>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('teacher.dashboard') }}"
                    class="text-xs font-black text-slate-400 hover:text-[#00c2cb] transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>
            <h1 class="text-2xl font-black text-slate-800">Daftar Siswa Questify</h1>
            <p class="text-slate-400 text-xs sm:text-sm font-semibold mt-0.5">
                Pantau kehadiran siswa, status online langsung, serta catatan aktivitas terakhir.
            </p>
        </div>

        {{-- Ringkasan Online Real-Time --}}
        <div
            class="flex items-center gap-3 bg-white border border-slate-100 px-5 py-3 rounded-2xl shadow-sm self-start sm:self-auto">
            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Sedang Belajar</p>
                <p class="text-sm font-black text-slate-800">
                    <span class="text-emerald-600">{{ $totalOnline }}</span> Siswa Online
                </p>
            </div>
        </div>
    </div>

    {{-- PANEL GENERATOR KELOMPOK OTOMATIS BERDASARKAN PRE-TEST --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <span
                    class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
                    Fitur Kolaborasi
                </span>
                <h2 class="text-xl font-black text-slate-800 mt-2">Pembagian Kelompok Belajar Otomatis</h2>
                <p class="text-xs font-semibold text-slate-400 mt-1">Sistem akan meratakan siswa mahir, sedang, dan
                    kurang berdasarkan nilai Pre-Test.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-32">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-1">Jml
                        Kelompok</label>
                    <input type="number" wire:model="jumlahKelompok" min="2" max="10"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-700 text-center">
                </div>
                <button wire:click="generateKelompokOtomatis"
                    class="px-6 py-3.5 mt-4 bg-[#ff8a00] hover:bg-orange-600 text-white font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2 shrink-0">
                    Generate Kelompok ⚡
                </button>
            </div>
        </div>

        @if (session()->has('success'))
        <div
            class="mb-4 bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl text-xs font-black">
            ✔ {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-600 px-4 py-3 rounded-xl text-xs font-black">
            ⚠ {{ session('error') }}
        </div>
        @endif

        {{-- TAMPILAN HASIL KELOMPOK YANG TERBENTUK --}}
        @if(count($daftarKelompok ?? []) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-4 border-t border-slate-100 mt-4">
            @foreach($daftarKelompok as $kelompok)
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/60">
                <h3 class="font-black text-slate-800 text-sm mb-3 flex items-center justify-between">
                    <span>{{ $kelompok->nama_kelompok }}</span>
                    <span class="text-[10px] bg-teal-100 text-teal-800 px-2 py-0.5 rounded-md font-bold">
                        {{ count($kelompok->anggotas) }} Anggota
                    </span>
                </h3>
                <ul class="space-y-2">
                    @foreach($kelompok->anggotas as $anggota)
                    <li
                        class="text-xs font-semibold text-slate-600 bg-white p-2.5 rounded-xl border border-slate-100 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-[10px]">
                            👤
                        </span>
                        <span class="truncate">{{ $anggota->user->name ?? 'Siswa' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Filter Pencarian --}}
    <div
        class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                🔍
            </span>
            <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari nama atau email siswa..."
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] transition-all">
        </div>
        <p class="text-xs font-bold text-slate-400 self-end sm:self-center">
            Pembaruan otomatis setiap 15 detik
        </p>
    </div>

    {{-- Grid Kartu Siswa --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($students as $s)
        <div
            class="bg-white rounded-[1.8rem] border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                {{-- Header Kartu: Avatar, Nama, Status Online --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="relative">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center text-sm font-black shadow-sm shrink-0">
                                {{ strtoupper(substr($s->name, 0, 2)) }}
                            </div>
                            {{-- Titik Indikator Online --}}
                            <span
                                class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white {{ $s->is_online ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-sm font-black text-slate-800 truncate">{{ $s->name }}</h3>
                            <p class="text-[11px] font-semibold text-slate-400 truncate">{{ $s->email }}</p>
                        </div>
                    </div>

                    {{-- Badge Status Online --}}
                    @if($s->is_online)
                    <span
                        class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider shrink-0 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        Online
                    </span>
                    @else
                    <span
                        class="bg-slate-100 text-slate-400 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider shrink-0">
                        Offline
                    </span>
                    @endif
                </div>

                {{-- Informasi Durasi & Poin --}}
                <div class="space-y-2 bg-slate-50/80 border border-slate-100/80 rounded-2xl p-3.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-bold text-[11px]">Keaktifan:</span>
                        <span class="font-extrabold {{ $s->is_online ? 'text-emerald-600' : 'text-slate-600' }}">
                            {{ $s->status_durasi }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-bold text-[11px]">Aktivitas Terakhir:</span>
                        <span class="font-semibold text-slate-600 text-[11px]">
                            {{ $s->waktu_terakhir }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                        <span class="text-slate-400 font-bold text-[11px]">Akumulasi XP:</span>
                        <span class="font-black text-[#ff8a00]">
                            {{ number_format($s->total_poin ?? 0) }} Pts
                        </span>
                    </div>
                </div>
            </div>

            {{-- Link Rapor Siswa --}}
            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                <a href="{{ route('rapor', ['userId' => $s->id]) }}"
                    class="inline-flex items-center gap-1 text-[#00c2cb] hover:text-teal-700 text-xs font-black hover:underline">
                    <span>Lihat Rekap Rapor</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl border border-dashed border-slate-200 p-12 text-center">
            <span class="text-4xl block mb-2">🧑‍💻</span>
            <p class="text-slate-400 text-sm font-bold">Tidak ada siswa ditemukan.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
    <div class="pt-4">
        {{ $students->links() }}
    </div>
    @endif

</div>