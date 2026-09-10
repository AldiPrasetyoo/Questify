<div x-data="{ detik: 0, berjalan: true }" x-init="const t = setInterval(() => { if (berjalan) detik++ }, 1000); cleanup(() => clearInterval(t))">
    @if(!$sudahDikirim)
        <p class="text-sm font-semibold text-gray-600 mb-1">Tentukan status kehadiran 12 siswa berikut berdasarkan jam kedatangannya.</p>
        <p class="text-xs font-semibold text-gray-400 mb-4">
            Pukul 07:00 atau sebelumnya &rarr; Hadir · 07:01–07:30 &rarr; Terlambat · setelah 07:30 &rarr; Alpa ·
            Waktu berjalan: <span x-text="detik" class="font-mono"></span> detik
        </p>
        <table class="w-full text-sm border rounded-2xl overflow-hidden mb-4">
            <thead class="bg-teal-50 text-left"><tr><th class="p-2 font-black">No</th><th class="p-2 font-black">Nama</th><th class="p-2 font-black">Jam</th><th class="p-2 font-black">Status</th></tr></thead>
            <tbody>
                @foreach($data as $d)
                    <tr class="border-t">
                        <td class="p-2">{{ $d['no'] }}</td>
                        <td class="p-2 font-semibold">{{ $d['nama'] }}</td>
                        <td class="p-2 font-mono">{{ $d['jam'] }}</td>
                        <td class="p-2">
                            <select wire:model="jawaban.{{ $d['no'] }}" class="rounded-xl border-gray-200 text-sm">
                                <option value="">— pilih —</option>
                                <option value="Hadir">Hadir</option><option value="Terlambat">Terlambat</option><option value="Alpa">Alpa</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button @click="berjalan = false" wire:click="kirim(detik)" class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Selesai</button>
    @else
        <div class="bg-teal-50 rounded-2xl p-5 text-sm font-semibold text-gray-700 leading-relaxed">
            <p>Kamu butuh <strong>{{ $waktuDetik }} detik</strong> untuk 12 siswa. Kekeliruanmu: <strong>{{ $this->jumlahKeliru }}</strong> dari 12.</p>
            <p class="mt-2">Sekolah ini punya 864 siswa. Kalau kecepatanmu tetap, satu hari rekap memakan waktu <strong>{{ $this->ekstrapolasi }}</strong>. Dan itu baru satu hari.</p>
            @if($this->keliruDiJamBatas)<p class="mt-2 text-amber-600">Kekeliruanmu ada di jam 07:00, 07:01, atau 07:30 — tepat di batas antar-status.</p>@endif
        </div>
        <button wire:click="lanjut" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
    @endif
</div>
