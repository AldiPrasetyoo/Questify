<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Ubah nilai jam di bawah — seluruh kolom hasil akan diperbarui seketika.</p>
    <div class="flex items-center gap-2 mb-4">
        <label class="text-sm font-bold text-gray-700">Nilai jam:</label>
        <input type="number" wire:model.live="jam" class="w-28 rounded-xl border-gray-200 text-sm font-semibold">
    </div>
    <table class="w-full text-sm border rounded-2xl overflow-hidden">
        <thead class="bg-teal-50 text-left"><tr><th class="p-2 font-black text-gray-700">Operator</th><th class="p-2 font-black text-gray-700">Artinya</th><th class="p-2 font-black text-gray-700">Contoh</th><th class="p-2 font-black text-gray-700">Hasil</th></tr></thead>
        <tbody>
            @foreach($this->baris as $b)
                <tr class="border-t">
                    <td class="p-2 font-mono font-bold">{{ $b['operator'] }}</td>
                    <td class="p-2 font-semibold text-gray-600">{{ $b['arti'] }}</td>
                    <td class="p-2 font-mono">jam {{ $b['operator'] }} {{ $b['pembanding'] }}</td>
                    <td class="p-2 font-black {{ $b['hasil'] ? 'text-emerald-600' : 'text-rose-600' }}">{{ $b['hasil'] ? 'benar' : 'salah' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button wire:click="selesai" class="mt-6 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
</div>
