<div class="max-w-4xl mx-auto px-4 py-8 font-['Nunito']">

    @if (session()->has('success'))
    <div
        class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-5 py-4 rounded-2xl text-xs font-black flex items-center justify-between animate-fade-in">
        <span>✔ {{ session('success') }}</span>
    </div>
    @endif

    {{-- HEADER & TOMBOL TAB (PRE / POST / UJI SOAL) --}}
    <div
        class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
        <div>
            <span
                class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
                Panel Pengajar
            </span>
            <h1 class="text-2xl font-black text-slate-800 mt-2">Kelola Bank Soal</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Atur soal untuk evaluasi internal maupun uji coba
                publik.</p>
        </div>

        <div class="flex flex-wrap gap-2 bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shrink-0">
            <button wire:click="$set('tipeAktif', 'pretest')"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition-all {{ $tipeAktif === 'pretest' ? 'bg-[#00c2cb] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60' }}">
                Pre-Test
            </button>
            <button wire:click="$set('tipeAktif', 'posttest')"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition-all {{ $tipeAktif === 'posttest' ? 'bg-[#00c2cb] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60' }}">
                Post-Test
            </button>
            <button wire:click="$set('tipeAktif', 'ujisoal')"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition-all {{ $tipeAktif === 'ujisoal' ? 'bg-[#00c2cb] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60' }}">
                Uji Soal Publik
            </button>
        </div>
    </div>

    {{-- BANNER INFORMASI KHUSUS UJI SOAL (Tanpa Login) --}}
    @if($tipeAktif === 'ujisoal')
    <div
        class="mb-6 bg-indigo-50 border border-indigo-100 text-indigo-700 px-5 py-5 rounded-[2rem] text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-xl shrink-0">🔗</div>
            <div>
                <strong class="font-black block text-sm mb-0.5">Link Akses Publik (Guest Mode)</strong>
                <span class="font-semibold opacity-80 block mb-2">Soal di kategori ini bisa diakses langsung oleh siapa
                    saja tanpa perlu login.</span>
                <code
                    class="text-indigo-900 bg-white/60 border border-indigo-200 px-3 py-1.5 rounded-lg font-bold select-all inline-block w-full sm:w-auto">
                    {{ url('/uji-soal') }}
                </code>
            </div>
        </div>
        <a href="{{ url('/uji-soal') }}" target="_blank"
            class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition-colors shrink-0 text-center shadow-md shadow-indigo-600/20">
            Tes Tampilan Siswa &rarr;
        </a>
    </div>
    @endif

    {{-- FORM TAMBAH / EDIT SOAL --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 sm:p-8 mb-8">
        <h2 class="text-base font-black text-slate-800 mb-4 flex items-center gap-2">
            @if($editSoalId) <span>✏️</span> Edit Butir Soal @else <span>➕</span> Tambah Soal
            ({{ ucfirst($tipeAktif) }}) @endif
        </h2>

        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1.5">Pertanyaan
                    Soal</label>
                <textarea wire:model="pertanyaan" rows="3" placeholder="Tuliskan pertanyaan di sini..."
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] resize-none"></textarea>
                @error('pertanyaan') <span class="text-rose-500 text-[10px] font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach(['A','B','C','D','E'] as $opsi)
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1.5">Pilihan
                        {{ $opsi }}</label>
                    <input type="text" wire:model="pilihan{{ $opsi }}" placeholder="Teks pilihan {{ $opsi }}"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
                @endforeach
            </div>

            <div
                class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pt-4 border-t border-slate-100 mt-4">
                <div class="w-full sm:w-64">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1.5">Kunci
                        Jawaban</label>
                    <select wire:model="kunciJawaban"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                        <option value="A">Jawaban Benar: A</option>
                        <option value="B">Jawaban Benar: B</option>
                        <option value="C">Jawaban Benar: C</option>
                        <option value="D">Jawaban Benar: D</option>
                        <option value="E">Jawaban Benar: E</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    @if($editSoalId)
                    <button wire:click="resetForm"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl text-xs font-black bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">Batal</button>
                    @endif
                    <button wire:click="simpanSoal"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-xs font-black bg-[#ff8a00] hover:bg-orange-600 text-white shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
                        {{ $editSoalId ? 'Simpan Perubahan' : 'Tambah Soal 🚀' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR SOAL YANG SUDAH DIBUAT --}}
    <h2 class="text-lg font-black text-slate-800 mb-3 flex items-center gap-2">
        <span>📋</span> Daftar Soal {{ ucfirst($tipeAktif) }} ({{ count($soals) }} Soal)
    </h2>

    <div class="space-y-4">
        @forelse($soals as $idx => $s)
        <div
            class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm p-6 flex flex-col sm:flex-row items-start justify-between gap-4">
            <div class="space-y-3 w-full">
                <div class="flex items-start gap-3">
                    <span
                        class="w-8 h-8 rounded-xl bg-teal-50 text-[#00c2cb] flex items-center justify-center text-xs font-black shrink-0 pt-0.5">
                        {{ $idx + 1 }}
                    </span>
                    <h3 class="font-black text-slate-800 text-sm leading-relaxed">{{ $s->pertanyaan }}</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 pl-11">
                    @foreach($s->pilihan as $huruf => $teks)
                    @php $isKunci = (strtoupper($huruf) === $s->kunci_jawaban); @endphp
                    <div
                        class="text-xs p-2.5 rounded-xl border {{ $isKunci ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                        <strong>{{ strtoupper($huruf) }}:</strong> {{ $teks }}
                        @if($isKunci) <span class="inline-block ml-1 text-[9px] text-emerald-600 font-black uppercase">✓
                            Kunci</span> @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex sm:flex-col gap-2 shrink-0 pl-11 sm:pl-0 w-full sm:w-auto">
                <button wire:click="editSoal({{ $s->id }})"
                    class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 transition-colors">
                    Edit ✏️
                </button>
                <button wire:click="hapusSoal({{ $s->id }})" onclick="return confirm('Yakin ingin menghapus soal ini?')"
                    class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors">
                    Hapus 🗑️
                </button>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-slate-100 p-12 text-center">
            <span class="text-4xl mb-3 block">📭</span>
            <p class="text-slate-500 font-bold text-sm">Belum ada soal untuk kategori <span
                    class="text-[#00c2cb] uppercase">{{ $tipeAktif }}</span>.</p>
            <p class="text-slate-400 font-semibold text-xs mt-1">Gunakan formulir di atas untuk mulai membuat soal.</p>
        </div>
        @endforelse
    </div>

</div>