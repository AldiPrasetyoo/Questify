<div x-data="{ detik: 0, berjalan: true }"
    x-init="const t = setInterval(() => { if (berjalan) detik++ }, 1000); $cleanup(() => clearInterval(t))">
    @if(!$sudahDikirim)
    <p class="text-sm font-semibold text-gray-600 mb-1">
        Salin blok perintah berikut sampai 8 siswa. <strong>Ubah angkanya agar berurutan</strong> sebelum kamu bisa
        menyalin blok berikutnya!
    </p>
    <p class="text-xs font-semibold text-gray-400 mb-4">
        Waktu: <span x-text="detik" class="font-mono text-slate-700"></span> detik ·
        Baris kode: {{ $this->totalBaris }}
    </p>

    {{-- AREA LOG KESALAHAN --}}
    @if($kesalahan > 0)
    <div class="mb-4 bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-600 animate-fade-in-up">
        <span class="font-bold flex items-center gap-1">⚠ <span>Validasi Gagal ({{ $kesalahan }}x salah)</span></span>
        <ul class="mt-1.5 space-y-1 list-disc list-inside">
            @foreach($logKesalahan as $idx => $waktu)
            <li>Gagal ke-{{ $idx + 1 }} pada detik ke-{{ $waktu }}: Angka baris tidak berurutan!</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- AREA KODE --}}
    <div
        class="font-mono bg-slate-900 text-emerald-300 text-sm rounded-2xl p-4 mb-4 space-y-3 max-h-72 overflow-y-auto">
        @foreach($salinan as $index => $nilai)
        <div
            class="{{ in_array($index, $indexSalah) ? 'bg-rose-950/30 p-2 rounded-lg border border-rose-500/50' : 'p-2' }}">
            cout &lt;&lt; "Jam siswa ke-<input type="number" wire:model="salinan.{{ $index }}"
                class="w-12 bg-slate-800 text-emerald-300 border-b-2 outline-none text-center px-1 mx-1 transition-colors {{ in_array($index, $indexSalah) ? 'border-rose-500 text-rose-400' : 'border-slate-500 focus:border-emerald-400' }}">":
            ";<br>
            cin &gt;&gt; jam;
        </div>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center gap-3">
        @if(count($salinan) < 8) {{-- Tombol Cek & Salin (Melempar waktu detik dari Alpine JS) --}} <button
            type="button" @click="$wire.validasiDanSalin(detik)"
            class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-6 py-3 rounded-xl text-sm transition">
            ✓ Cek & Salin Blok ke-{{ count($salinan) + 1 }}
            </button>
            @else
            {{-- Tombol Kirim jika target sudah tercapai --}}
            <button type="button" @click="$wire.kirimJawaban(detik)"
                class="bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition flex items-center gap-2">
                Validasi Akhir & Selesai 🚀
            </button>
            @endif
    </div>

    @else
    {{-- AREA HASIL AKHIR --}}
    <div
        class="bg-teal-50 border border-teal-100 rounded-2xl p-5 text-sm font-semibold text-gray-700 leading-relaxed animate-fade-in-up">
        <p>Berhasil! Kamu menulis <strong>{{ $this->totalBaris }} baris kode</strong> dalam waktu
            <strong>{{ $waktuDetik }} detik</strong>, hanya untuk mendata 8 siswa.</p>

        @if($kesalahan > 0)
        <p class="mt-2 text-rose-600">Selama proses penyalinan manual, kamu melakukan kesalahan penomoran sebanyak
            <strong>{{ $kesalahan }} kali</strong>.</p>
        @endif

        <p class="mt-2">Bayangkan untuk 36 siswa, kamu perlu sekitar <strong>{{ $this->ekstrapolasiBaris }}
                baris</strong>, dan sangat rentan terjadi <em>human error</em> saat mengubah angkanya. Selain itu,
            programnya tetap tidak fleksibel jika jumlah siswanya mendadak berubah.</p>
    </div>

    <button wire:click="lanjut"
        class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">
        Pahami Solusinya &rarr;
    </button>
    @endif
</div>