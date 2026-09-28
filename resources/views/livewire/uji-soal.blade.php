<div class="max-w-3xl mx-auto px-4 py-10 font-['Nunito'] min-h-screen relative">

    @if(!$sudahMulai)
    {{-- FASE 1: HEADER & FORM BIODATA --}}
    <div class="text-center mb-10">
        <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#00c2cb] to-teal-300 text-white shadow-lg shadow-teal-500/30 mb-4 text-3xl">
            🚀
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-800">Uji Coba Kemampuan</h1>
        <p class="text-sm font-semibold text-slate-500 mt-2">Platform evaluasi mandiri. Jawab pertanyaan dengan teliti!
        </p>
    </div>

    <div
        class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl p-8 sm:p-12 max-w-lg mx-auto animate-fade-in-up">
        <h2 class="text-lg font-black text-slate-800 mb-6 text-center">Lengkapi Data Diri</h2>

        <form wire:submit.prevent="mulaiKuis" class="space-y-5">
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1.5">Nama
                    Lengkap</label>
                <input type="text" wire:model.defer="namaSiswa" placeholder="Ketik namamu di sini..."
                    class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl p-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] {{ $errors->has('namaSiswa') ? 'border-rose-300 bg-rose-50' : '' }}">
                @error('namaSiswa') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1.5">Kelas / Asal
                    Instansi</label>
                <input type="text" wire:model.defer="kelasSiswa" placeholder="Misal: XII TKJ 1 / SMK Negeri 1"
                    class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl p-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00c2cb] {{ $errors->has('kelasSiswa') ? 'border-rose-300 bg-rose-50' : '' }}">
                @error('kelasSiswa') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠
                    {{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-[#00c2cb] hover:bg-teal-500 text-white font-black px-6 py-4 rounded-xl shadow-lg shadow-teal-500/20 transition-all flex items-center justify-center gap-2">
                    Mulai Ujian Sekarang &rarr;
                </button>
            </div>
        </form>
    </div>

    @else
    {{-- FASE 2: PENGERJAAN SOAL --}}
    <div
        class="max-w-2xl mx-auto bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 animate-fade-in {{ $sudahSelesai ? 'opacity-50 pointer-events-none' : '' }}">
        @php $soalAktif = $soals[$indexSoal] ?? null; @endphp

        @if($soalAktif)
        {{-- Header Mini Identitas & Navigasi Progres --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-100">
            <div>
                <span
                    class="text-[10px] font-black text-[#00c2cb] uppercase tracking-widest bg-teal-50 px-3 py-1 rounded-full">
                    Peserta: {{ $namaSiswa }}
                </span>
            </div>
            <span class="text-xs font-black text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                Soal {{ $indexSoal + 1 }} dari {{ count($soals) }}
            </span>
        </div>

        {{-- Teks Pertanyaan --}}
        <p class="font-black text-base sm:text-lg text-slate-800 mb-8 leading-relaxed">
            {{ $soalAktif->pertanyaan }}
        </p>

        {{-- Pilihan Ganda --}}
        <div class="space-y-3 mb-8">
            @foreach($soalAktif->pilihan ?? [] as $huruf => $teks)
            @php $hurufUpper = strtoupper($huruf); @endphp
            <button type="button" wire:click="pilihJawaban({{ $soalAktif->id }}, '{{ $hurufUpper }}')"
                class="w-full text-left px-5 py-4 rounded-2xl border-2 text-xs sm:text-sm font-bold transition-all flex items-center gap-4 hover:border-[#00c2cb]/30
                        {{ (isset($jawaban[$soalAktif->id]) && $jawaban[$soalAktif->id] === $hurufUpper) ? 'bg-teal-50 border-[#00c2cb] text-teal-950 shadow-sm' : 'bg-slate-50/50 border-slate-100 text-slate-700' }}">
                <span
                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs font-black text-slate-500 shrink-0 transition-colors {{ (isset($jawaban[$soalAktif->id]) && $jawaban[$soalAktif->id] === $hurufUpper) ? 'text-[#00c2cb] border-[#00c2cb]' : '' }}">
                    {{ $hurufUpper }}
                </span>
                <span class="leading-snug">{{ $teks }}</span>
            </button>
            @endforeach
        </div>

        @error('jawaban.'.$soalAktif->id)
        <div
            class="mb-6 p-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-xs font-bold flex items-center gap-2">
            ⚠ {{ $message }}
        </div>
        @enderror

        {{-- Navigasi Bawah --}}
        <div class="flex items-center justify-between pt-2">
            <button wire:click="sebelumnya" @disabled($indexSoal===0)
                class="px-5 py-3 rounded-xl text-xs font-black bg-slate-100 hover:bg-slate-200 text-slate-600 disabled:opacity-40 transition-colors">
                &larr; Sebelumnya
            </button>

            @if($indexSoal < count($soals) - 1) <button wire:click="selanjutnya"
                class="px-6 py-3 rounded-xl text-xs font-black bg-[#00c2cb] hover:bg-teal-500 text-white shadow-md transition-all">
                Selanjutnya &rarr;
                </button>
                @else
                {{-- Tombol Pemicu Modal Konfirmasi Kustom --}}
                <button wire:click="konfirmasiKumpulkan"
                    class="px-6 py-3 rounded-xl text-xs font-black bg-[#ff8a00] hover:bg-orange-600 text-white shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                    Kumpulkan Jawaban 🚀
                </button>
                @endif
        </div>
        @else
        <div class="text-center py-10">
            <span class="text-5xl mb-4 block">📭</span>
            <p class="text-slate-500 font-bold text-base">Soal belum dikonfigurasi oleh pengajar.</p>
        </div>
        @endif
    </div>

    {{-- MODAL POPUP KONFIRMASI KUMPULKAN --}}
    @if($konfirmasiModal)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
        <div
            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl p-8 max-w-sm w-full text-center relative overflow-hidden animate-fade-in-up">
            <div
                class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-inner mb-4">
                🎯
            </div>

            <h3 class="text-lg font-black text-slate-800 mb-2">Kumpulkan Jawaban?</h3>
            <p class="text-xs font-semibold text-slate-400 mb-6 leading-relaxed">
                Pastikan seluruh soal telah dijawab dengan teliti. Ujian yang telah dikumpulkan tidak dapat diulang
                kembali.
            </p>

            <div class="flex gap-3">
                <button wire:click="tutupModal"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs py-3.5 rounded-xl transition-all">
                    Periksa Lagi
                </button>
                <button wire:click="kumpulkan"
                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs py-3.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                    Ya, Kumpulkan
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- FASE 3: MODAL POPUP SELESAI & UCAPAN TERIMA KASIH --}}
    @if($sudahSelesai)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
        <div
            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl p-8 max-w-sm w-full text-center relative overflow-hidden animate-fade-in-up">
            <div
                class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full mx-auto flex items-center justify-center text-4xl mb-4 shadow-inner">
                🙏
            </div>

            <h3 class="text-xl font-black text-slate-800 mb-2">Terima Kasih!</h3>

            <p class="text-xs font-semibold text-slate-500 mb-8 leading-relaxed">
                Jawabanmu telah berhasil dikirim.
                <br>Terima kasih atas partisipasinya dalam uji coba kemampuan ini.
            </p>
        </div>
    </div>
    @endif

    @endif

</div>