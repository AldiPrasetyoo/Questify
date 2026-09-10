<div>
    <p class="text-sm font-semibold text-gray-600 mb-3">Ketuk tiap bagian kode untuk melihat keterangannya.</p>
    <div class="font-mono bg-slate-900 text-slate-200 text-sm rounded-2xl p-4 mb-4 whitespace-pre-wrap">
        @foreach(($konfigurasi['bagian'] ?? []) as $i => $b)
            <span wire:click="ketuk({{ $i }})" class="cursor-pointer px-0.5 rounded {{ $bagianAktif === $i ? 'bg-amber-400 text-slate-900' : 'hover:bg-slate-700' }} {{ isset($sudahDiketuk[$i]) ? 'underline decoration-dotted' : '' }}">{{ $b['potongan'] }}</span>
        @endforeach
    </div>
    @if(!is_null($bagianAktif))
        @php $b = $konfigurasi['bagian'][$bagianAktif]; @endphp
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-sm mb-4">
            <p class="font-black text-gray-800">{{ $b['nama'] }}</p>
            <p class="font-semibold text-gray-600">{{ $b['keterangan'] }}</p>
        </div>
    @endif
    <button wire:click="selesai" @disabled(!$this->semuaSudahDiketuk)
        class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition disabled:opacity-40 disabled:cursor-not-allowed">
        Lanjut &rarr;
    </button>
    @if(!$this->semuaSudahDiketuk)<p class="text-xs font-semibold text-gray-400 mt-2">Ketuk semua bagian dulu ({{ count($sudahDiketuk) }}/{{ count($konfigurasi['bagian'] ?? []) }})</p>@endif
</div>
