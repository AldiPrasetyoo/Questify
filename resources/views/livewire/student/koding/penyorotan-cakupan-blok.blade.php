<div>
    <div class="flex items-center justify-between mb-3">
        <p class="text-sm font-semibold text-gray-600">Ketuk baris mana pun — seluruh cakupan blok yang memuatnya akan tersorot.</p>
        <button wire:click="toggleLekukan" class="text-xs font-bold text-[#00c2cb] underline shrink-0 ml-3">{{ $tampilkanLekukan ? 'Sembunyikan' : 'Tampilkan' }} lekukan</button>
    </div>
    <div class="font-mono bg-slate-900 text-slate-300 text-sm rounded-2xl p-4 relative">
        @foreach(($konfigurasi['baris'] ?? []) as $i => $b)
            @php
                $blok = $this->blokAktif;
                $kenaLuar = $blok['luar'] && ($b['blok_luar'] ?? null) === $blok['luar'];
                $kenaDalam = $blok['dalam'] && ($b['blok_dalam'] ?? null) === $blok['dalam'];
            @endphp
            <div wire:click="ketuk({{ $i }})" class="cursor-pointer px-2 py-0.5 rounded
                {{ $kenaDalam ? 'bg-amber-500/30' : ($kenaLuar ? 'bg-teal-500/20' : 'hover:bg-slate-800') }}
                {{ $tampilkanLekukan ? 'border-l-2 border-slate-700' : '' }}"
                style="margin-left: {{ ($b['indentasi'] ?? 0) * 16 }}px">{{ $b['teks'] }}</div>
        @endforeach
    </div>
    @if($barisAktif !== null)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-sm mt-3">
            @if($this->blokAktif['luar'])<p><span class="inline-block w-3 h-3 bg-[#00c2cb] rounded-sm mr-1"></span> Blok perulangan: {{ $konfigurasi['keterangan_blok'][$this->blokAktif['luar']] ?? '' }}</p>@endif
            @if($this->blokAktif['dalam'])<p><span class="inline-block w-3 h-3 bg-amber-400 rounded-sm mr-1"></span> Blok percabangan: {{ $konfigurasi['keterangan_blok'][$this->blokAktif['dalam']] ?? '' }}</p>@endif
        </div>
    @endif
    <button wire:click="selesai" @disabled(!$this->semuaSudahDiketuk) class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition disabled:opacity-40 disabled:cursor-not-allowed">Lanjut &rarr;</button>
    @if(!$this->semuaSudahDiketuk)<p class="text-xs font-semibold text-gray-400 mt-2">Ketuk semua baris dulu ({{ count($sudahDiketuk) }}/{{ count($konfigurasi['baris'] ?? []) }})</p>@endif
</div>
