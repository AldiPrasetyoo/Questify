<div x-data="{ detik: 0, berjalan: true }" x-init="const t = setInterval(() => { if (berjalan) detik++ }, 1000); cleanup(() => clearInterval(t))">
    @if(!$sudahDikirim)
        <p class="text-sm font-semibold text-gray-600 mb-1">Salin blok perintah berikut sampai mampu mendata 8 siswa.</p>
        <p class="text-xs font-semibold text-gray-400 mb-4">Waktu: <span x-text="detik" class="font-mono"></span> detik · Baris kode: {{ $this->totalBaris }}</p>
        <div class="font-mono bg-slate-900 text-emerald-300 text-sm rounded-2xl p-4 mb-3 space-y-3 max-h-72 overflow-y-auto">
            @for($n = 1; $n <= $jumlahSalinan; $n++)
                <div>cout &lt;&lt; "Jam siswa ke-{{ $n }}: ";<br>cin &gt;&gt; jam;</div>
            @endfor
            @if($jumlahSalinan === 0)<div class="text-slate-500">cout &lt;&lt; "Jam siswa ke-1: ";<br>cin &gt;&gt; jam;</div>@endif
        </div>
        <button wire:click="salinBlok" @disabled($jumlahSalinan >= $target) class="bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-40">+ Salin Blok ({{ $jumlahSalinan }}/{{ $target }})</button>
        @if($jumlahSalinan >= $target)
            <button @click="berjalan = false" wire:click="kirim(detik)" class="ml-2 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Selesai</button>
        @endif
    @else
        <div class="bg-teal-50 rounded-2xl p-5 text-sm font-semibold text-gray-700 leading-relaxed">
            <p>Kamu menulis <strong>{{ $this->totalBaris }} baris kode</strong> dalam <strong>{{ $waktuDetik }} detik</strong>, hanya untuk delapan siswa.</p>
            <p class="mt-2">Untuk 36 siswa, kamu perlu sekitar <strong>{{ $this->ekstrapolasiBaris }} baris</strong>. Dan programnya tetap tidak bisa dipakai kalau jumlah siswanya berubah.</p>
        </div>
        <button wire:click="lanjut" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
    @endif
</div>
