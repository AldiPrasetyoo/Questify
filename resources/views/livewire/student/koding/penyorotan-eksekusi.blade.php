<div>
    <div class="flex items-center gap-2 mb-4">
        <label class="text-sm font-bold text-gray-700">Nilai jam:</label>
        <input type="number" wire:model.live="jam" class="w-28 rounded-xl border-gray-200 text-sm">
        <span class="text-xs font-semibold text-gray-400">coba nilai berbeda untuk melihat jalur lain</span>
    </div>
    <div class="font-mono bg-slate-900 text-slate-400 text-sm rounded-2xl p-4 whitespace-pre">
        @foreach($this->baris['kode'] as $i => $line)
            <div class="{{ in_array($i, $this->baris['aktif']) ? 'bg-emerald-500/20 text-emerald-300 -mx-4 px-4' : '' }}">{{ $line }}</div>
        @endforeach
    </div>
    <button wire:click="selesai" class="mt-6 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
</div>
