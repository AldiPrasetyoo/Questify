<div x-data="{
    kode: `int hadir = 0, terlambat = 0, alpa = 0;
int jumlah;
cin >> jumlah;

for (int i = 1; i <= jumlah; i++) {
    int jam;
    cout << \"Jam kedatangan siswa ke-\" << i << \": \";
    cin >> jam;

    if (jam <= 700) {
        hadir++;
    } else if (jam <= 730) {
        terlambat++;
    } else {
        alpa++;
    }
}

cout << \"Hadir: \" << hadir << endl;
cout << \"Terlambat: \" << terlambat << endl;
cout << \"Alpa: \" << alpa << endl;

if (jumlah > 0) {
    cout << \"Persentase: \" << hadir * 100 / jumlah << \"%\";
} else {
    cout << \"Tidak ada data untuk dihitung\";
}`,
    dataUji: [
        {jumlah: 3, jam: [648,715,800], hK: 1, tK: 1, aK: 1, persenTeks: '33', catatan: 'Menguji pembagian bilangan bulat'},
        {jumlah: 4, jam: [700,700,701,731], hK: 2, tK: 1, aK: 1, persenTeks: '50', catatan: 'Menguji jam batas'},
        {jumlah: 2, jam: [650,655], hK: 2, tK: 0, aK: 0, persenTeks: '100', catatan: ''},
        {jumlah: 0, jam: [], hK: 0, tK: 0, aK: 0, persenTeks: 'Tidak ada data', catatan: 'Menguji pembagian dengan nol'},
    ],
    hasilUji: [],
    errorTerakhir: null,
    sudahDijalankan: false,
    sedangJalan: false,
    async jalankanUji() {
        this.sedangJalan = true;
        this.hasilUji = [];
        this.errorTerakhir = null;
        await new Promise(r => setTimeout(r, 10));
        for (const d of this.dataUji) {
            const program = `#include <iostream>
using namespace std;
int main() {
${this.kode}
    return 0;
}`;
            const masukan = [d.jumlah, ...d.jam];
            const hasil = window.jalankanKodeCpp(program, masukan);
            if (hasil.error) { this.errorTerakhir = hasil.error; this.hasilUji = []; this.sudahDijalankan = true; this.sedangJalan = false; return; }
            const rekapLolos = hasil.keluaran.includes(`Hadir: ${d.hK}`) && hasil.keluaran.includes(`Terlambat: ${d.tK}`) && hasil.keluaran.includes(`Alpa: ${d.aK}`);
            const persenLolos = hasil.keluaran.includes(d.persenTeks);
            this.hasilUji.push({
                jumlah: d.jumlah, catatan: d.catatan, keluaran: hasil.keluaran,
                hK: d.hK, tK: d.tK, aK: d.aK, rekapLolos, persenLolos, batasWaktu: hasil.batasWaktuTerlampaui,
                lolos: rekapLolos && persenLolos && !hasil.batasWaktuTerlampaui,
            });
        }
        this.sudahDijalankan = true;
        this.sedangJalan = false;
    },
    get jumlahLolos() { return this.hasilUji.filter(h => h.lolos).length; },
    kirim() {
        const poin = Math.min(this.jumlahLolos * 5, 20);
        $wire.terimaHasil(this.jumlahLolos, this.kode, poin);
    }
}">
    <p class="text-sm font-semibold text-gray-600 mb-1">Tulis program rekap kehadiran lengkap, lalu uji terhadap 4 data. Rekap dan persentase dicek terpisah.</p>
    <p class="text-xs font-semibold text-gray-400 mb-3">Dijalankan sungguhan lewat JSCPP — cin akan membaca nilai jam sesuai data uji, berurutan.</p>

    <textarea x-model="kode" rows="18" class="w-full font-mono text-sm rounded-2xl border-gray-200 bg-slate-900 text-emerald-300 p-4"></textarea>

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
                <thead class="bg-teal-50 text-left"><tr><th class="p-2 font-black">Jumlah</th><th class="p-2 font-black">Rekap (H/T/A)</th><th class="p-2 font-black">Persentase</th><th class="p-2 font-black">Hasil</th></tr></thead>
                <tbody>
                    <template x-for="h in hasilUji" :key="h.jumlah">
                        <tr class="border-t" :class="h.lolos ? '' : 'bg-rose-50'">
                            <td class="p-2 font-mono">
                                <span x-text="h.jumlah"></span>
                                <template x-if="h.catatan"><span class="text-xs text-amber-600 font-semibold block" x-text="h.catatan"></span></template>
                            </td>
                            <td class="p-2 font-semibold" :class="h.rekapLolos ? 'text-emerald-600' : 'text-rose-600'">
                                <span x-text="'seharusnya ' + h.hK + '/' + h.tK + '/' + h.aK"></span>
                            </td>
                            <td class="p-2 font-bold" :class="h.persenLolos ? 'text-emerald-600' : 'text-rose-600'" x-text="h.persenLolos ? '✔' : '✘'"></td>
                            <td class="p-2 font-bold" x-text="h.lolos ? '✔ Lolos' : '✘ Belum'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 4 data uji lolos.</p>
            <template x-if="jumlahLolos === 4">
                <p class="text-sm font-bold text-emerald-600">🏅 Semua lolos, termasuk data bernilai nol!</p>
            </template>
            <button @click="kirim" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>
