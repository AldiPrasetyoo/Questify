<div>
    <p class="text-xs font-bold text-gray-400 mb-2">Soal {{ $indexSoal + 1 }} / {{ count($konfigurasi['soal'] ?? []) }}</p>
    <p class="font-black text-gray-800 text-lg mb-3">{{ $this->soalAktif['pertanyaan'] ?? '' }}</p>
    <div class="flex gap-2">
        <input type="text" wire:model="jawaban" @disabled($sudahDijawab) class="flex-1 rounded-xl border-gray-200 text-sm" placeholder="Tulis jawabanmu...">
        @if(!$sudahDijawab)<button wire:click="jawab" class="bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm">Cek</button>@endif
    </div>
    @if($sudahDijawab)
        <p class="text-sm font-bold mt-3 {{ $benar ? 'text-emerald-600' : 'text-rose-600' }}">
            {{ $benar ? '✔ Benar!' : '✘ Belum tepat. '.($this->soalAktif['umpan_balik_salah'] ?? '') }}
            @if(!$benar)<span class="block text-gray-500 font-semibold mt-1">Kunci: {{ $this->soalAktif['kunci'] }}</span>@endif
        </p>
        <button wire:click="lanjut" class="mt-3 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">
            {{ $indexSoal + 1 < count($konfigurasi['soal'] ?? []) ? 'Soal berikutnya' : 'Selesai' }}
        </button>
    @endif
</div>
