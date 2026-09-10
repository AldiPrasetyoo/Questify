<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Masukkan nilai awal, kondisi berhenti, dan perubahan nilai. Lihat sendiri berapa kali perulangannya berjalan.</p>
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div><label class="text-xs font-bold text-gray-500">Nilai awal</label><input type="text" wire:model.live.debounce.400ms="nilaiAwal" class="w-full rounded-xl border-gray-200 text-sm font-mono"></div>
        <div><label class="text-xs font-bold text-gray-500">Kondisi berhenti</label><input type="text" wire:model.live.debounce.400ms="kondisi" class="w-full rounded-xl border-gray-200 text-sm font-mono"></div>
        <div><label class="text-xs font-bold text-gray-500">Perubahan nilai</label><input type="text" wire:model.live.debounce.400ms="perubahan" class="w-full rounded-xl border-gray-200 text-sm font-mono"></div>
    </div>
    <div class="flex flex-wrap gap-2 mb-4 text-xs">
        <span class="text-gray-400 font-semibold">Coba:</span>
        @foreach([['i = 1','i <= 5','i++'], ['i = 0','i <= 5','i++'], ['i = 1','i <= 5','i--'], ['i = 6','i <= 5','i++']] as [$na, $k, $p])
            <button wire:click="$set('nilaiAwal', '{{ $na }}')" class="underline text-[#00c2cb] font-semibold">{{ $na }} / {{ $k }} / {{ $p }}</button>
        @endforeach
    </div>
    @php $hasil = $this->penelusuran; @endphp
    <div class="bg-slate-900 text-emerald-300 font-mono text-xs rounded-2xl p-4 mb-2 max-h-64 overflow-y-auto">
        @forelse($hasil['baris'] as $idx => $nilai)
            <div>Pengulangan ke-{{ $idx + 1 }}: i = {{ $nilai }}</div>
        @empty
            <div class="text-slate-500">(tidak ada baris yang dihasilkan)</div>
        @endforelse
    </div>
    <p class="text-sm font-bold mb-1">Jumlah pengulangan: {{ count($hasil['baris']) }}</p>
    @if($hasil['keterangan'])<p class="text-sm text-amber-600 font-semibold">⚠ {{ $hasil['keterangan'] }}</p>@endif
    <button wire:click="selesai" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
</div>
