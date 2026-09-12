<div class="max-w-4xl mx-auto px-4 py-2 font-['Nunito'] space-y-3" x-data="{
         audioCtx: null,
         init() {
             const aktifkanAudio = () => {
                 if (!this.audioCtx) {
                     this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                 }
                 if (this.audioCtx.state === 'suspended') {
                     this.audioCtx.resume();
                 }
             };
             window.addEventListener('click', aktifkanAudio, { once: true });
             window.addEventListener('keydown', aktifkanAudio, { once: true });
         },
         bunyikanNada(frekuensi, durasi, tipe = 'sine') {
             try {
                 if (!this.audioCtx) {
                     this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                 }
                 if (this.audioCtx.state === 'suspended') {
                     this.audioCtx.resume();
                 }

                 const osc = this.audioCtx.createOscillator();
                 const gain = this.audioCtx.createGain();

                 osc.type = tipe;
                 osc.frequency.setValueAtTime(frekuensi, this.audioCtx.currentTime);

                 gain.gain.setValueAtTime(0.15, this.audioCtx.currentTime);
                 gain.gain.exponentialRampToValueAtTime(0.0001, this.audioCtx.currentTime + durasi);

                 osc.connect(gain);
                 gain.connect(this.audioCtx.destination);

                 osc.start();
                 osc.stop(this.audioCtx.currentTime + durasi);
             } catch (e) {
                 console.warn('Audio tidak dapat diputar:', e);
             }
         },
         suaraBenar() {
             this.bunyikanNada(523.25, 0.12, 'sine');
             setTimeout(() => this.bunyikanNada(783.99, 0.25, 'sine'), 100);
         },
         suaraSalah() {
             this.bunyikanNada(220, 0.18, 'sawtooth');
             setTimeout(() => this.bunyikanNada(180, 0.25, 'sawtooth'), 120);
         }
     }" x-init="init()" @suara-benar.window="suaraBenar()" @suara-salah.window="suaraSalah()">

    {{-- HEADER MISI --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-3.5">
        <div class="flex items-center justify-between mb-0.5">
            <span
                class="text-[9px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-2.5 py-0.5 rounded-full">
                Eksplorasi Misi Belajar
            </span>
            <span class="text-[11px] font-black text-slate-400">
                Progres: {{ min($indexKontenAktif + 1, max(count($kontens), 1)) }} / {{ count($kontens) }}
            </span>
        </div>

        <h1 class="text-base sm:text-lg font-black text-slate-800">{{ $misi->judul }}</h1>
        <p class="text-[11px] font-semibold text-slate-400 truncate">{{ $misi->deskripsi }}</p>

        {{-- PROGRESS BAR --}}
        <div class="flex gap-1 mt-2 bg-slate-50 p-1 rounded-xl border border-slate-100">
            @foreach($kontens as $i => $k)
            <div
                class="h-1 flex-1 rounded-full transition-all duration-500 {{ $progresKonten->get($k->id)?->selesai ? 'bg-emerald-500' : ($i === $indexKontenAktif ? 'bg-[#ff8a00] animate-pulse' : 'bg-slate-200') }}">
            </div>
            @endforeach
        </div>
    </div>

    @php $kontenAktif = $kontens[$indexKontenAktif] ?? null; @endphp

    @if($kontenAktif)
    {{-- KOTAK KONTEN UTAMA --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-6 relative flex flex-col min-h-[300px]">

        <div class="flex-1 space-y-4">
            @switch($kontenAktif->tipe)

            @case('teks_web')
            <div class="prose max-w-none text-slate-700 font-semibold text-xs sm:text-sm leading-relaxed">
                {!! $kontenAktif->konten_html !!}
            </div>

            <div class="pt-4 mt-6 flex items-center w-full border-t border-slate-100">
                @if($indexKontenAktif > 0)
                <button wire:click="kontenSebelumnya"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs py-2.5 px-5 rounded-xl transition-all flex items-center gap-2">
                    Sebelumnya
                </button>
                @endif
                <button wire:click="kontenSelesai({{ $kontenAktif->id }})"
                    class="ml-auto bg-[#ff8a00] hover:bg-orange-600 text-white font-black text-xs py-2.5 px-6 rounded-xl shadow-md shadow-orange-500/20 transition-all flex items-center gap-2">
                    <span>Lanjut Eksplorasi</span>
                </button>
            </div>
            @break

            @case('aset_ppt')
            <div class="space-y-3 flex flex-col items-center">
                <div
                    class="rounded-xl overflow-hidden border border-slate-100 bg-slate-50 max-h-[300px] flex justify-center w-full">
                    <img src="{{ Storage::url($kontenAktif->path_gambar) }}" alt="{{ $kontenAktif->judul }}"
                        class="max-h-[280px] object-contain rounded-xl">
                </div>
            </div>

            <div class="pt-4 mt-6 flex items-center w-full border-t border-slate-100">
                @if($indexKontenAktif > 0)
                <button wire:click="kontenSebelumnya"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs py-2.5 px-5 rounded-xl transition-all flex items-center gap-2">
                    Sebelumnya
                </button>
                @endif
                <button wire:click="kontenSelesai({{ $kontenAktif->id }})"
                    class="ml-auto bg-[#ff8a00] hover:bg-orange-600 text-white font-black text-xs py-2.5 px-6 rounded-xl shadow-md shadow-orange-500/20 transition-all">
                    Selesai Membaca
                </button>
            </div>
            @break

            @case('koding')
            <div class="pb-12"> {{-- Jarak agar tombol Selesai di child sejajar dengan tombol absolut Sebelumnya --}}
                @livewire(
                'student.koding.' . \Illuminate\Support\Str::of($kontenAktif->komponen_koding)->kebab(),
                ['kontenMisiId' => $kontenAktif->id, 'konfigurasi' => $kontenAktif->konfigurasi_koding ?? []],
                key('koding-'.$kontenAktif->id)
                )
            </div>

            @if($indexKontenAktif > 0)
            <div class="absolute bottom-5 sm:bottom-6 left-5 sm:left-6">
                <button wire:click="kontenSebelumnya"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs py-2.5 px-5 rounded-xl transition-all flex items-center gap-2">
                    Sebelumnya
                </button>
            </div>
            @endif
            @break

            @case('kuis')
            @livewire('student.kuis-mini', ['kontenMisiId' => $kontenAktif->id], key('kuis-'.$kontenAktif->id))
            @break

            @case('refleksi')
            <div class="pb-12">
                @livewire('student.refleksi-terbuka', [
                'kontenMisiId' => $kontenAktif->id,
                'pertanyaan' => $kontenAktif->pertanyaan_refleksi
                ], key('refleksi-'.$kontenAktif->id))
            </div>

            @if($indexKontenAktif > 0)
            <div class="absolute bottom-5 sm:bottom-6 left-5 sm:left-6">
                <button wire:click="kontenSebelumnya"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs py-2.5 px-5 rounded-xl transition-all flex items-center gap-2">
                    Sebelumnya
                </button>
            </div>
            @endif
            @break

            @endswitch
        </div>
    </div>
    @else
    {{-- MODAL SELESAI MISI --}}
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
        <div
            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl p-6 sm:p-8 max-w-md w-full text-center relative">
            <div
                class="w-16 h-16 bg-gradient-to-tr from-amber-400 to-orange-400 text-white rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg shadow-orange-500/30 animate-bounce mb-3">
                🏆
            </div>

            <h2 class="text-xl font-black text-slate-800">Misi Berhasil Dituntaskan!</h2>
            <p class="text-xs font-semibold text-slate-400 mt-1 mb-4">Progres belajarmu telah otomatis tersimpan.</p>

            <div
                class="bg-amber-50/80 border border-amber-200/60 rounded-2xl p-3.5 mb-5 flex items-center justify-center gap-3">
                <span class="text-2xl">{{ $poinDidapat > 0 ? '✨' : '🔁' }}</span>
                <div class="text-left">
                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-600 block">
                        {{ $poinDidapat > 0 ? 'Hadiah Eksplorasi' : 'Penyelesaian Ulang' }}
                    </span>
                    <span class="text-base font-black text-amber-700">
                        @if($poinDidapat > 0)
                        +{{ number_format($poinDidapat) }} Poin XP
                        @else
                        Misi Selesai (Poin sudah diklaim sebelumnya)
                        @endif
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('pertemuan.show', $misi->pertemuan) }}"
                    class="block w-full bg-[#00c2cb] hover:bg-[#00aeb8] text-white font-black text-xs py-3 rounded-xl shadow-md transition-all">
                    Kembali ke Peta Misi
                </a>
                <a href="{{ route('rapor') }}"
                    class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs py-2.5 rounded-xl transition-all">
                    Periksa Rapor
                </a>
            </div>
        </div>
    </div>

    {{-- POPUP FLOATING LENCANA --}}
    @if($lencanaBaru)
    <div x-data="{ tampil: true }" x-init="setTimeout(() => tampil = false, 5000)" x-show="tampil"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-8 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-400"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="fixed bottom-6 right-6 z-[60] max-w-sm w-full p-4 bg-white/95 backdrop-blur-md rounded-2xl border-2 border-amber-300 shadow-2xl flex items-center gap-4">

        <div
            class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-400 to-yellow-300 flex items-center justify-center text-2xl shadow-md shrink-0">
            @if(!empty($lencanaBaru->ikon))
            <span>{{ $lencanaBaru->ikon }}</span>
            @elseif(!empty($lencanaBaru->gambar))
            <img src="{{ Storage::url($lencanaBaru->gambar) }}" alt="{{ $lencanaBaru->nama }}"
                class="w-10 h-10 object-contain">
            @else
            <span>🎖️</span>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <span
                    class="text-[9px] font-black uppercase tracking-wider text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full">Lencana
                    Baru!</span>
                <button @click="tampil = false"
                    class="text-slate-400 hover:text-slate-600 text-sm font-bold">&times;</button>
            </div>
            <h4 class="text-sm font-black text-slate-800 truncate mt-1">{{ $lencanaBaru->nama ?? 'Pencapaian Baru' }}
            </h4>
            <p class="text-[11px] font-semibold text-slate-500 line-clamp-1">
                {{ $lencanaBaru->deskripsi ?? 'Selamat atas pencapaian barumu!' }}</p>
        </div>
    </div>
    @endif
    @endif
</div>