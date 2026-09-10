<div class="max-w-4xl mx-auto px-4 py-8 font-['Nunito']">

    @if (session()->has('success'))
    <div
        class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-5 py-4 rounded-2xl text-xs font-black flex items-center justify-between">
        <span>✔ {{ session('success') }}</span>
    </div>
    @endif

    {{-- HEADER & TOMBOL TAB PRE-TEST / POST-TEST --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
        <div>
            <span
                class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
                Panel Pengajar
            </span>
            <h1 class="text-2xl font-black text-slate-800 mt-2">Kelola Bank Soal Evaluasi</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Tambah, edit, dan atur kunci jawaban pre-test serta
                post-test siswa.</p>
        </div>

        <div class="flex gap-2 bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shrink-0">
            <button wire:click="$set('tipeAktif', 'pretest')"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition-all {{ $tipeAktif === 'pretest' ? 'bg-[#00c2cb] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60' }}">
                Pre-Test
            </button>
            <button wire:click="$set('tipeAktif', 'posttest')"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition-all {{ $tipeAktif === 'posttest' ? 'bg-[#00c2cb] text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60' }}">
                Post-Test
            </button>
        </div>
    </div>

    {{-- FORM TAMBAH / EDIT SOAL --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 sm:p-8 mb-8">
        <h2 class="text-base font-black text-slate-800 mb-4">
            {{ $editSoalId ? '✏️ Edit Butir Soal' : '➕ Tambah Soal Baru (' . ucfirst($tipeAktif) . ')' }}
        </h2>

        <div class="space-y-4">
            <div>
                <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pertanyaan
                    Soal</label>
                <textarea wire:model="pertanyaan" rows="3" placeholder="Tuliskan pertanyaan ujian di sini..."
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] resize-none"></textarea>
                @error('pertanyaan') <span class="text-rose-500 text-[10px] font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pilihan
                        A</label>
                    <input type="text" wire:model="pilihanA" placeholder="Teks pilihan A"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pilihan
                        B</label>
                    <input type="text" wire:model="pilihanB" placeholder="Teks pilihan B"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pilihan
                        C</label>
                    <input type="text" wire:model="pilihanC" placeholder="Teks pilihan C"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pilihan
                        D</label>
                    <input type="text" wire:model="pilihanD" placeholder="Teks pilihan D"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Pilihan
                        E</label>
                    <input type="text" wire:model="pilihanE" placeholder="Teks pilihan E"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="w-full sm:w-48">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wider block mb-1">Kunci Jawaban
                        Benar</label>
                    <select wire:model="kunciJawaban"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb]">
                        <option value="A">Pilihan A</option>
                        <option value="B">Pilihan B</option>
                        <option value="C">Pilihan C</option>
                        <option value="D">Pilihan D</option>
                        <option value="E">Pilihan E</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    @if($editSoalId)
                    <button wire:click="resetForm"
                        class="px-5 py-3 rounded-xl text-xs font-black bg-slate-200 text-slate-600">Batal</button>
                    @endif
                    <button wire:click="simpanSoal"
                        class="px-7 py-3 rounded-xl text-xs font-black bg-[#ff8a00] hover:bg-orange-600 text-white shadow-lg shadow-orange-500/20 transition-all">
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
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span
                        class="w-7 h-7 rounded-xl bg-teal-50 text-[#00c2cb] flex items-center justify-center text-xs font-black shrink-0">
                        {{ $idx + 1 }}
                    </span>
                    <h3 class="font-black text-slate-800 text-sm sm:text-base">{{ $s->pertanyaan }}</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pl-9">
                    @foreach($s->pilihan as $huruf => $teks)
                    @php $isKunci = (strtoupper($huruf) === $s->kunci_jawaban); @endphp
                    <div
                        class="text-xs p-2.5 rounded-xl border {{ $isKunci ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                        <strong>{{ strtoupper($huruf) }}:</strong> {{ $teks }}
                        @if($isKunci) <span class="block text-[9px] text-emerald-600 font-black uppercase">✓ Kunci
                            Benar</span> @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-2 shrink-0">
                <button wire:click="editSoal({{ $s->id }})"
                    class="px-4 py-2 rounded-xl text-xs font-black bg-slate-100 hover:bg-slate-200 text-slate-700">
                    Edit ✏️
                </button>
                <button wire:click="hapusSoal({{ $s->id }})" onclick="return confirm('Yakin ingin menghapus soal ini?')"
                    class="px-4 py-2 rounded-xl text-xs font-black bg-rose-50 hover:bg-rose-100 text-rose-600">
                    Hapus 🗑️
                </button>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-slate-100 p-12 text-center">
            <span class="text-4xl mb-2 block">📭</span>
            <p class="text-slate-400 font-bold text-sm">Belum ada soal untuk kategori {{ ucfirst($tipeAktif) }} ini.
                Silakan buat melalui form di atas.</p>
        </div>
        @endforelse
    </div>

</div>