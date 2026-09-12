<div x-data="{ detik: 0, berjalan: true }"
    x-init="const t = setInterval(() => { if (berjalan) detik++ }, 1000); cleanup(() => clearInterval(t))">
    @if(!$sudahDikirim)
    <p class="text-sm font-semibold text-gray-600 mb-1">Ini keluaran program kalian untuk dua belas siswa. Hitung
        sendiri rekapnya.</p>
    <p class="text-xs font-semibold text-gray-400 mb-4">Waktu: <span x-text="detik" class="font-mono"></span> detik</p>
    <div class="grid grid-cols-2 gap-x-6 font-mono bg-slate-900 text-slate-300 text-sm rounded-2xl p-4 mb-4">
        @foreach($data as [$no, $jam, $status])
        <div>Siswa ke-{{ $no }}: {{ $jam }} &rarr; {{ $status }}</div>
        @endforeach
    </div>
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div><label class="text-xs font-bold text-gray-500">Hadir</label><input type="number" wire:model="isianHadir"
                class="w-full rounded-xl border-gray-200 text-sm"></div>
        <div><label class="text-xs font-bold text-gray-500">Terlambat</label><input type="number"
                wire:model="isianTerlambat" class="w-full rounded-xl border-gray-200 text-sm"></div>
        <div><label class="text-xs font-bold text-gray-500">Alpa</label><input type="number" wire:model="isianAlpa"
                class="w-full rounded-xl border-gray-200 text-sm"></div>
        <div><label class="text-xs font-bold text-gray-500">Persentase (%)</label><input type="text"
                wire:model="isianPersentase" placeholder="33,33" class="w-full rounded-xl border-gray-200 text-sm">
        </div>
    </div>
    <button @click="berjalan = false" wire:click="kirim(detik)"
        class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Selesai</button>
    @else
    <div class="bg-teal-50 rounded-2xl p-5 text-sm font-semibold text-gray-700 leading-relaxed">
        <p>Kamu butuh <strong>{{ $waktuDetik }} detik</strong> untuk merekap 12 siswa, dengan
            <strong>{{ $this->jumlahKeliru }} kekeliruan</strong>.</p>
        <p class="mt-2">Padahal seluruh data yang kamu butuhkan sudah ada di dalam program. Pekerjaan menghitung ini
            seharusnya bisa diambil alih oleh programnya sendiri.</p>
    </div>
    <button wire:click="lanjut"
        class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut
        &rarr;</button>
    @endif
</div>