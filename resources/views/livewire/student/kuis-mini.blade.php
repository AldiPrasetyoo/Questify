<div
    class="max-w-4xl w-full mx-auto bg-white rounded-[2rem] border border-slate-100 shadow-sm p-5 sm:p-7 font-['Nunito'] relative flex flex-col justify-center my-auto">

    @php
    $soal = $this->soals[$indexSoal] ?? null;

    $pesanSalah = [
    'Kurang tepat, pejuang koding! Jangan menyerah, coba lagi di soal berikutnya!',
    'Belum pas! Ingat, kesalahan adalah bukti bahwa kamu sedang belajar dan bertumbuh.',
    'Tetap semangat! Mari pantang mundur, kamu pasti bisa menaklukkan misi ini!',
    'Hampir benar! Evaluasi lagi kodenya dan jadikan ini pelajaran berharga.',
    '💡 Pantun kilat: Burung Irian burung Cenderawasih, cukup sekian tetap semangat ya kasih! 🦅'
    ];
    $randomSalah = $pesanSalah[array_rand($pesanSalah)];

    $pesanBenar = [
    'Luar biasa! Logika kodingmu sudah level dewa! 🚀',
    'Tepat sekali! Pertahankan kecepatan dan ketepatan belajarmu!',
    'Jenius! Satu langkah lebih dekat menuju gelar master jaringan!',
    'Keren abis! Jawabanmu meluncur mulus tanpa bug! ⚡',
    '💡 Pantun kilat: Pergi ke pasar membeli nangka, jawabannya benar hatiku berbunga-bunga! 🌴'
    ];
    $randomBenar = $pesanBenar[array_rand($pesanBenar)];
    @endphp

    @if($soal)
    {{-- HEADER KUIS --}}
    <div class="flex items-center justify-between mb-3">
        <span class="text-[10px] font-black text-[#00c2cb] tracking-widest uppercase bg-teal-50 px-3 py-1 rounded-full">
            Tantangan Kuis Mini
        </span>
        <span class="text-xs font-black text-slate-400">
            Soal {{ $indexSoal + 1 }} dari {{ $this->soals->count() }}
        </span>
    </div>

    {{-- PERTANYAAN --}}
    <p class="font-black text-base sm:text-lg text-slate-800 mb-5 leading-snug">{{ $soal->pertanyaan }}</p>

    {{-- PILIHAN JAWABAN (GRID 2 KOLOM) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($soal->pilihan as $huruf => $teks)
        @php $kode = strtoupper($huruf); @endphp
        <button wire:click="jawab('{{ $kode }}')" @disabled($sudahDijawab)
            class="w-full text-left px-4 py-3 rounded-2xl border-2 text-xs sm:text-sm font-bold transition-all flex items-center justify-between
                {{ $sudahDijawab && $kode === $soal->kunci_jawaban ? 'bg-emerald-50/85 border-emerald-400 text-emerald-950 shadow-sm' : '' }}
                {{ $sudahDijawab && $kode === $jawabanDipilih && $kode !== $soal->kunci_jawaban ? 'bg-rose-50/85 border-rose-400 text-rose-950 shadow-sm' : '' }}
                {{ !$sudahDijawab ? 'bg-slate-50/60 border-slate-100 hover:border-teal-300 hover:bg-teal-50/20 text-slate-700' : '' }}">

            <div class="flex items-center gap-2.5">
                <span
                    class="w-7 h-7 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs font-black text-slate-500 shrink-0">
                    {{ $kode }}
                </span>
                <span>{{ $teks }}</span>
            </div>

            @if($sudahDijawab)
            @if($kode === $soal->kunci_jawaban)
            <span class="text-emerald-600 text-base font-black">✔</span>
            @elseif($kode === $jawabanDipilih)
            <span class="text-rose-600 text-base font-black">✘</span>
            @endif
            @endif
        </button>
        @endforeach
    </div>

    {{-- MODAL POPUP HANYA MUNCUL KETIKA SUDAH DIJAWAB (BENAR / SALAH) --}}
    @if($sudahDijawab)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
        <div
            class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-6 sm:p-8 max-w-md w-full relative overflow-hidden text-center transform animate-scale-up">

            {{-- Icon Indikator Benar/Salah --}}
            <div
                class="w-16 h-16 mx-auto rounded-3xl flex items-center justify-center text-3xl shadow-lg mb-3 animate-bounce
                {{ $benar ? 'bg-emerald-100 text-emerald-600 shadow-emerald-500/20' : 'bg-rose-100 text-rose-600 shadow-rose-500/20' }}">
                {{ $benar ? '🎉' : '💡' }}
            </div>

            <h3 class="text-lg font-black {{ $benar ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $benar ? 'Jawaban Benar!' : 'Kurang Tepat!' }}
            </h3>

            <p class="text-xs sm:text-sm font-bold text-slate-700 mt-2 leading-relaxed">
                {{ $benar ? $randomBenar : $randomSalah }}
            </p>

            @if($soal->pembahasan)
            <div class="mt-3 p-3 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-0.5">Pembahasan:</p>
                <p class="text-xs font-semibold text-slate-600">{{ $soal->pembahasan }}</p>
            </div>
            @endif

            <div class="mt-5">
                <button wire:click="lanjut"
                    class="w-full bg-[#ff8a00] hover:bg-orange-600 text-white font-black text-xs sm:text-sm py-3 px-6 rounded-2xl shadow-lg shadow-orange-500/20 transition-all">
                    {{ $indexSoal + 1 < $this->soals->count() ? 'Lanjut ke Soal Berikutnya' : 'Selesaikan Kuis 🏆' }}
                </button>
            </div>

        </div>
    </div>
    @endif

    @endif
</div>