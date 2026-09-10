<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Kedua program terlihat sama, hanya beda letak baris pemberian nilai awal. Tekan lanjut untuk melangkah bersama-sama.</p>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-xs font-black text-rose-600 mb-1">Panel kiri — keliru</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">for (int i = 1; i &lt;= 12; i++) {
    int hadir = 0, terlambat = 0, alpa = 0;
    ...
}</div>
        </div>
        <div>
            <p class="text-xs font-black text-emerald-600 mb-1">Panel kanan — benar</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">int hadir = 0, terlambat = 0, alpa = 0;
for (int i = 1; i &lt;= 12; i++) {
    ...
}</div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <table class="w-full text-xs border rounded-2xl overflow-hidden">
            <thead class="bg-rose-50 text-left"><tr><th class="p-1.5 font-black">#</th><th class="p-1.5 font-black">H</th><th class="p-1.5 font-black">T</th><th class="p-1.5 font-black">A</th></tr></thead>
            <tbody>@foreach($this->penelusuranKiri as $i => $b)<tr class="border-t"><td class="p-1.5">{{ $i + 1 }}</td><td class="p-1.5">{{ $b['hadir'] }}</td><td class="p-1.5">{{ $b['terlambat'] }}</td><td class="p-1.5">{{ $b['alpa'] }}</td></tr>@endforeach</tbody>
        </table>
        <table class="w-full text-xs border rounded-2xl overflow-hidden">
            <thead class="bg-emerald-50 text-left"><tr><th class="p-1.5 font-black">#</th><th class="p-1.5 font-black">H</th><th class="p-1.5 font-black">T</th><th class="p-1.5 font-black">A</th></tr></thead>
            <tbody>@foreach($this->penelusuranKanan as $i => $b)<tr class="border-t"><td class="p-1.5">{{ $i + 1 }}</td><td class="p-1.5">{{ $b['hadir'] }}</td><td class="p-1.5">{{ $b['terlambat'] }}</td><td class="p-1.5">{{ $b['alpa'] }}</td></tr>@endforeach</tbody>
        </table>
    </div>
    <div class="flex items-center gap-3">
        <button wire:click="lanjutSatu" @disabled($langkah >= count($data)) class="bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-40">Lanjut satu siswa ({{ $langkah }}/{{ count($data) }})</button>
        @if($langkah >= count($data))<span class="text-sm font-semibold text-gray-500">Kiri berakhir 0/0/1, kanan berakhir 4/4/4.</span>@endif
    </div>
    @if($langkah >= count($data))<button wire:click="selesai" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>@endif
</div>
