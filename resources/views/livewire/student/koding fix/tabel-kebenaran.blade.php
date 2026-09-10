<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Tekan benar/salah pada A dan B — baris yang sesuai akan menyala.</p>
    <div class="flex gap-6 mb-4">
        <div>
            <div class="text-xs font-bold text-gray-500 mb-1">A</div>
            <div class="flex gap-1">
                <button wire:click="setA(true)" class="px-3 py-1.5 rounded-xl text-sm font-bold border-2 {{ $a ? 'bg-[#00c2cb] text-white border-[#00c2cb]' : 'border-gray-200' }}">benar</button>
                <button wire:click="setA(false)" class="px-3 py-1.5 rounded-xl text-sm font-bold border-2 {{ !$a ? 'bg-[#00c2cb] text-white border-[#00c2cb]' : 'border-gray-200' }}">salah</button>
            </div>
        </div>
        <div>
            <div class="text-xs font-bold text-gray-500 mb-1">B</div>
            <div class="flex gap-1">
                <button wire:click="setB(true)" class="px-3 py-1.5 rounded-xl text-sm font-bold border-2 {{ $b ? 'bg-[#00c2cb] text-white border-[#00c2cb]' : 'border-gray-200' }}">benar</button>
                <button wire:click="setB(false)" class="px-3 py-1.5 rounded-xl text-sm font-bold border-2 {{ !$b ? 'bg-[#00c2cb] text-white border-[#00c2cb]' : 'border-gray-200' }}">salah</button>
            </div>
        </div>
    </div>
    <table class="w-full text-sm text-center border rounded-2xl overflow-hidden mb-4">
        <thead class="bg-teal-50"><tr><th class="p-2 font-black">A</th><th class="p-2 font-black">B</th><th class="p-2 font-black">A &amp;&amp; B</th><th class="p-2 font-black">A ││ B</th></tr></thead>
        <tbody>
            @foreach([[true,true],[true,false],[false,true],[false,false]] as [$va,$vb])
                <tr class="border-t {{ $a === $va && $b === $vb ? 'bg-amber-50 font-bold' : '' }}">
                    <td class="p-2">{{ $va ? 'benar' : 'salah' }}</td>
                    <td class="p-2">{{ $vb ? 'benar' : 'salah' }}</td>
                    <td class="p-2">{{ ($va && $vb) ? 'benar' : 'salah' }}</td>
                    <td class="p-2">{{ ($va || $vb) ? 'benar' : 'salah' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="bg-teal-50 rounded-2xl p-4">
        <p class="text-xs font-semibold text-gray-500 mb-2">Contoh gabungan siap coba: <code>jam &gt; 700 &amp;&amp; jam &lt;= 730</code></p>
        <div class="flex items-center gap-2">
            <label class="text-sm font-bold">Nilai jam:</label>
            <input type="number" wire:model.live="jam" class="w-24 rounded-xl border-gray-200 text-sm">
            <span class="text-sm font-black {{ $this->hasilContoh ? 'text-emerald-600' : 'text-rose-600' }}">&rarr; {{ $this->hasilContoh ? 'benar' : 'salah' }}</span>
        </div>
    </div>
    <button wire:click="selesai" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
</div>
