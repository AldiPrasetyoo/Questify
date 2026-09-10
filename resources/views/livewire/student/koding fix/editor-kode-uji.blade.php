<div x-data="{
    kode: `if (jam <= 700) {
    cout << \"Hadir\";
} else if (jam <= 730) {
    cout << \"Terlambat\";
} else {
    cout << \"Alpa\";
}`,
    dataUji: [[648,'Hadir',''],[700,'Hadir','Jam batas'],[701,'Terlambat','Jam batas'],[715,'Terlambat',''],[730,'Terlambat','Jam batas'],[731,'Alpa','Jam batas'],[800,'Alpa','']],
    hasilUji: [],
    errorTerakhir: null,
    sudahDijalankan: false,
    sedangJalan: false,
    async jalankanUji() {
        this.sedangJalan = true;
        this.hasilUji = [];
        this.errorTerakhir = null;
        await new Promise(r => setTimeout(r, 10)); // biar UI sempat render 'sedang menjalankan'
        for (const [jam, seharusnya, catatan] of this.dataUji) {
            const program = window.bungkusKode(this.kode, { jam });
            const hasil = window.jalankanKodeCpp(program, []);
            if (hasil.error) { this.errorTerakhir = hasil.error; this.hasilUji = []; this.sudahDijalankan = true; this.sedangJalan = false; return; }
            this.hasilUji.push({ jam, seharusnya, catatan, keluaran: hasil.keluaran, lolos: hasil.keluaran.trim() === seharusnya });
        }
        this.sudahDijalankan = true;
        this.sedangJalan = false;
    },
    get jumlahLolos() { return this.hasilUji.filter(h => h.lolos).length; },
    get semuaJamBatasLolos() { return this.hasilUji.filter(h => h.catatan === 'Jam batas').every(h => h.lolos); },
    kirim() {
        const poin = Math.min(this.jumlahLolos * 5, 35);
        $wire.terimaHasil(this.jumlahLolos, this.kode, poin);
    }
}">
    <p class="text-sm font-semibold text-gray-600 mb-1">Tulis program penentu status kehadiran, lalu jalankan terhadap tabel uji.</p>
    <p class="text-xs font-semibold text-gray-400 mb-3">Kode dijalankan sungguhan lewat JSCPP (C++ interpreter) langsung di browsermu.</p>

    <textarea x-model="kode" rows="8" class="w-full font-mono text-sm rounded-2xl border-gray-200 bg-slate-900 text-emerald-300 p-4"></textarea>

    <button @click="jalankanUji" :disabled="sedangJalan" class="mt-3 bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-50">
        <span x-show="!sedangJalan">▶ Jalankan Uji</span>
        <span x-show="sedangJalan">Menjalankan...</span>
    </button>

    <template x-if="errorTerakhir">
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl p-3 text-sm font-semibold" x-text="'⚠ ' + errorTerakhir"></div>
    </template>

    <template x-if="sudahDijalankan && !errorTerakhir">
        <div>
            <table class="w-full text-sm mt-4 border rounded-2xl overflow-hidden">
                <thead class="bg-teal-50 text-left"><tr><th class="p-2 font-black">Jam</th><th class="p-2 font-black">Seharusnya</th><th class="p-2 font-black">Keluaran</th><th class="p-2 font-black">Hasil</th></tr></thead>
                <tbody>
                    <template x-for="h in hasilUji" :key="h.jam">
                        <tr class="border-t" :class="h.lolos ? '' : 'bg-rose-50'">
                            <td class="p-2 font-mono" x-text="h.jam"></td>
                            <td class="p-2 font-semibold" x-text="h.seharusnya"></td>
                            <td class="p-2 font-mono" x-text="h.keluaran || '(kosong)'"></td>
                            <td class="p-2 font-bold" x-text="h.lolos ? '✔ Lolos' : '✘ Belum'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 7 data uji lolos.</p>
            <template x-if="jumlahLolos === 7 && semuaJamBatasLolos">
                <p class="text-sm font-bold text-emerald-600">🏅 Seluruh jam batas lolos!</p>
            </template>
            <button @click="kirim" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>
