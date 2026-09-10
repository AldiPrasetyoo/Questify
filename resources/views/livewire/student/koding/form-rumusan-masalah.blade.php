<div>
    <p class="text-sm font-semibold text-gray-600 mb-4">Lengkapi bersama kelompokmu. Kartu masalah: tentukan status kehadiran otomatis dari jam kedatangan.</p>
    <label class="text-xs font-bold text-gray-500">Nama kelompok</label>
    <input type="text" wire:model="namaKelompok" class="w-full rounded-xl border-gray-200 text-sm mb-4">
    <p class="text-sm font-bold text-gray-700 mb-2">Lengkapi tabel kondisi:</p>
    <table class="w-full text-sm border rounded-2xl overflow-hidden mb-4">
        <thead class="bg-teal-50 text-left"><tr><th class="p-2 font-black">Status</th><th class="p-2 font-black">Batas bawah</th><th class="p-2 font-black">Batas atas</th><th class="p-2 font-black">Bentuk kondisi</th></tr></thead>
        <tbody>
            @foreach(['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'alpa' => 'Alpa'] as $key => $label)
                <tr class="border-t">
                    <td class="p-2 font-bold">{{ $label }}</td>
                    <td class="p-2"><input type="text" wire:model="tabelKondisi.{{ $key }}.batas_bawah" placeholder="—" class="w-20 rounded-lg border-gray-200 text-sm"></td>
                    <td class="p-2"><input type="text" wire:model="tabelKondisi.{{ $key }}.batas_atas" placeholder="—" class="w-20 rounded-lg border-gray-200 text-sm"></td>
                    <td class="p-2"><input type="text" wire:model="tabelKondisi.{{ $key }}.kondisi" placeholder="mis. jam <= 700" class="w-full rounded-lg border-gray-200 text-sm font-mono"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="text-sm font-bold text-gray-700 mb-2">Bentuk percabangan yang dipilih:</p>
    <div class="flex gap-2 mb-2">
        @foreach(['if_tunggal' => 'if tunggal', 'if_else' => 'if-else', 'if_else_if' => 'if-else if'] as $val => $label)
            <button wire:click="$set('bentukDipilih', '{{ $val }}')" class="px-3 py-1.5 rounded-xl text-sm font-bold border-2 {{ $bentukDipilih === $val ? 'bg-[#00c2cb] text-white border-[#00c2cb]' : 'border-gray-200' }}">{{ $label }}</button>
        @endforeach
    </div>
    <textarea wire:model="alasan" rows="2" placeholder="Alasan memilih bentuk ini..." class="w-full rounded-xl border-gray-200 text-sm mb-4"></textarea>
    <label class="text-xs font-bold text-gray-500">Rencana kerja kelompok</label>
    <textarea wire:model="rencanaKerja" rows="2" class="w-full rounded-xl border-gray-200 text-sm mb-4"></textarea>
    <div class="flex gap-2">
        <button wire:click="cek" class="bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm">Cek Isian</button>
        <button wire:click="kirim" class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim &amp; Lanjut &rarr;</button>
    </div>
    @if($sudahDicek)
        <p class="text-sm font-bold mt-3 {{ $semuaBenar ? 'text-emerald-600' : 'text-amber-600' }}">
            {{ $semuaBenar ? '✔ Seluruh isian tepat!' : '⚠ Ada bagian yang belum tepat — kelompok tetap boleh mengirim, tapi coba periksa lagi.' }}
        </p>
    @endif
</div>
