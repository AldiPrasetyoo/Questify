<div>
    <p class="text-xs font-bold text-gray-400 mb-2">Butir {{ $indexButir + 1 }} / {{ count($konfigurasi['butir'] ?? []) }}</p>
    <div class="font-mono bg-slate-900 text-slate-200 text-sm rounded-2xl p-4 mb-4 whitespace-pre-wrap leading-relaxed">
        @foreach($this->segmen as $s)
            @if(preg_match('/^___(\d+)$/', $s, $m))
                <input type="text" wire:model="isian.{{ $indexButir }}.{{ $m[1] }}" class="inline-block w-16 mx-1 px-1 rounded bg-slate-700 border-slate-600 text-amber-300 text-sm text-center">
            @else
                {{ $s }}
            @endif
        @endforeach
    </div>
    @if($sudahDicek)
        <p class="text-sm font-bold mb-3 {{ $semuaBenar ? 'text-emerald-600' : 'text-rose-600' }}">
            {{ $semuaBenar ? '✔ Benar! Kodenya sekarang berjalan seperti yang diharapkan.' : '✘ Coba periksa lagi bagian yang kosong.' }}
        </p>
    @endif
    <div class="flex gap-2">
        <button wire:click="cek" class="bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm">Cek Jawaban</button>
        @if($sudahDicek && $semuaBenar)
            <button wire:click="butirBerikutnya" class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
        @endif
    </div>
</div>
