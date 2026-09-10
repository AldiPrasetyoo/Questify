<div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 font-['Nunito']">
    @if($sudahMengerjakan)
    <div class="text-center py-12 space-y-4">
        <div class="text-5xl">⚠️</div>
        <h2 class="text-xl font-black text-slate-800">Ujian Telah Selesai</h2>
        <p class="text-xs font-semibold text-slate-400">Kamu sudah pernah mengerjakan ujian ini dan dibatasi hanya 1
            kali percobaan.</p>
        <a href="{{ route('student.dashboard') }}"
            class="inline-block bg-[#00c2cb] text-white font-black text-xs py-3 px-6 rounded-2xl">Kembali ke
            Dashboard</a>
    </div>
    @else
    @php $soalAktif = $soals[$indexSoal] ?? null; @endphp
    @if($soalAktif)
    <div class="flex items-center justify-between mb-6">
        <span class="text-[10px] font-black text-[#00c2cb] uppercase bg-teal-50 px-3 py-1 rounded-full">
            Ujian {{ ucfirst($ujian->tipe) }}
        </span>
        <span class="text-xs font-black text-slate-400">Soal {{ $indexSoal + 1 }} dari {{ count($soals) }}</span>
    </div>

    <p class="font-black text-base text-slate-800 mb-6 leading-relaxed">{{ $soalAktif->pertanyaan }}</p>

    <div class="space-y-3 mb-8">
        @foreach($soalAktif->pilihan as $huruf => $teks)
        @php $hurufUpper = strtoupper($huruf); @endphp
        <button type="button" wire:click="pilihJawaban({{ $soalAktif->id }}, '{{ $hurufUpper }}')"
            class="w-full text-left px-5 py-4 rounded-2xl border-2 text-xs sm:text-sm font-bold transition-all flex items-center gap-3
                        {{ ($jawabanSiswa[$soalAktif->id] ?? '') === $hurufUpper ? 'bg-teal-50 border-[#00c2cb] text-teal-950 shadow-sm' : 'bg-slate-50/50 border-slate-100 text-slate-700' }}">
            <span
                class="w-7 h-7 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs font-black text-slate-500 shrink-0">
                {{ $hurufUpper }}
            </span>
            <span>{{ $teks }}</span>
        </button>
        @endforeach
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
        <button wire:click="sebelumnya" @disabled($indexSoal===0)
            class="px-5 py-2.5 rounded-xl text-xs font-black bg-slate-100 text-slate-600 disabled:opacity-40">
            &larr; Sebelumnya
        </button>

        @if($indexSoal < count($soals) - 1) <button wire:click="selanjutnya"
            class="px-6 py-2.5 rounded-xl text-xs font-black bg-[#00c2cb] text-white shadow-md">
            Selanjutnya &rarr;
            </button>
            @else
            <button wire:click="selesaiUjian" onclick="return confirm('Yakin ingin mengumpulkan jawaban ujian ini?')"
                class="px-6 py-2.5 rounded-xl text-xs font-black bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg shadow-emerald-500/20">
                Kumpulkan Ujian 🚀
            </button>
            @endif
    </div>
    @endif
    @endif
</div>