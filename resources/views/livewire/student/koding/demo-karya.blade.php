<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Siapkan bahan presentasi kelompokmu. Guru akan meminta kalian
        menjelaskan alasan penyusunan urutan kondisi.</p>
    <label class="text-xs font-bold text-gray-500">Urutan kondisi yang kalian pakai</label>
    <textarea wire:model="urutanKondisi" rows="2" class="w-full rounded-xl border-gray-200 text-sm mb-3"></textarea>
    @error('urutanKondisi')<p class="text-rose-600 text-xs font-semibold">{{ $message }}</p>@enderror
    <label class="text-xs font-bold text-gray-500">Alasan penyusunan urutan tersebut</label>
    <textarea wire:model="alasanPenyusunan" rows="2" class="w-full rounded-xl border-gray-200 text-sm mb-3"></textarea>
    @error('alasanPenyusunan')<p class="text-rose-600 text-xs font-semibold">{{ $message }}</p>@enderror
    <label class="text-xs font-bold text-gray-500">Bagian tersulit menurut kelompok</label>
    <textarea wire:model="bagianTersulit" rows="2" class="w-full rounded-xl border-gray-200 text-sm mb-3"></textarea>
    @error('bagianTersulit')<p class="text-rose-600 text-xs font-semibold">{{ $message }}</p>@enderror
    <label class="text-xs font-bold text-gray-500">Tanggapan untuk kelompok lain (opsional)</label>
    <textarea wire:model="tanggapanUntukKelompokLain" rows="2"
        class="w-full rounded-xl border-gray-200 text-sm mb-4"></textarea>
    <button wire:click="kirim"
        class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim
        &amp; Lanjut &rarr;</button>
</div>