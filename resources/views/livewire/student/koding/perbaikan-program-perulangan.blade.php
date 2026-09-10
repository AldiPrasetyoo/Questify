<div x-data="ujiTigaPerulangan()">
    <p class="text-sm font-semibold text-gray-600 mb-2">Program berikut berisi tiga perulangan dengan empat kesalahan
        tersebar di dalamnya.</p>
    <div class="font-mono bg-slate-900 text-rose-300 text-xs rounded-2xl p-4 mb-4 whitespace-pre overflow-x-auto"
        x-text="kodeBermasalah"></div>

    <details class="mb-4 text-sm">
        <summary class="cursor-pointer text-[#00c2cb] font-bold">Bantuan: telusuri manual dulu untuk masukan 3 dan 0
        </summary>
        <p class="text-gray-500 font-semibold mt-2">Perhatikan baris 10-11, lalu periksa apakah nilai i pernah
            bertambah. Terakhir, kalau jumlah = 0, haruskah blok baris 21-24 tetap berjalan?</p>
    </details>

    <p class="text-sm font-bold text-gray-700 mb-1">Tulis versi perbaikanmu:</p>
    <textarea x-model="kodePerbaikan" rows="14"
        class="w-full font-mono text-sm rounded-2xl border-gray-200 bg-slate-900 text-emerald-300 p-4"></textarea>

    <button @click="jalankanUji" :disabled="sedangJalan"
        class="mt-3 bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-50">
        <span x-show="!sedangJalan">▶ Uji Perbaikan</span>
        <span x-show="sedangJalan">Menjalankan...</span>
    </button>

    <template x-if="errorTerakhir">
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl p-3 text-sm font-semibold"
            x-text="'⚠ ' + errorTerakhir"></div>
    </template>

    <template x-if="sudahDijalankan && !errorTerakhir">
        <div>
            <table class="w-full text-sm mt-4 border rounded-2xl overflow-hidden">
                <thead class="bg-teal-50 text-left">
                    <tr>
                        <th class="p-2 font-black">Jumlah</th>
                        <th class="p-2 font-black">Masukan</th>
                        <th class="p-2 font-black">Baris keluaran</th>
                        <th class="p-2 font-black">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="h in hasilUji" :key="h.jumlah">
                        <tr class="border-t" :class="h.lolos ? '' : 'bg-rose-50'">
                            <td class="p-2 font-mono">
                                <span x-text="h.jumlah"></span>
                                <template x-if="h.catatan">
                                    <span class="text-xs text-amber-600 font-semibold block" x-text="h.catatan"></span>
                                </template>
                            </td>
                            <td class="p-2 font-semibold"
                                x-text="h.batasWaktu ? '⚠ batas waktu' : h.cinAktual + ' / ' + h.cinSeharusnya + ' seharusnya'">
                            </td>
                            <td class="p-2 font-semibold"
                                x-text="h.barisAktual + ' / ' + h.barisSeharusnya + ' seharusnya'"></td>
                            <td class="p-2 font-bold" x-text="h.lolos ? '✔ Lolos' : '✘ Belum'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 2 data uji lolos.</p>
            <button @click="kirim"
                class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim
                &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>

<script>
function ujiTigaPerulangan() {
    return {
        kodeBermasalah: ` 9      int i = 1;
10      while (i <= jumlah);
11      {
12          cout << " Jam siswa ke-" << i << ": "; 13 cin >> jam;
    14 }
    15
    16 for (int k = 0; k <= jumlah; k++) { 17 cout << "Data ke-" << k << " tercatat" << endl; 18 } 19 20 int m=1; 21
        do { 22 cout << "Rekap baris " << m << endl; 23 m++; 24 } while (m <= jumlah);`,
        kodePerbaikan: `int i=1; 
while (i <= jumlah) { 
    cout << "Jam siswa ke-" << i << ": "; 
    cin >> jam;
    i++;
}

for (int k = 1; k <= jumlah; k++) { 
    cout << "Data ke-" << k << " tercatat" << endl; 
} 

int m = 1; 
while (m <= jumlah) { 
    cout << "Rekap baris " << m << endl; 
    m++; 
}`,
        dataUji: [{
                jumlah: 3,
                catatan: ''
            },
            {
                jumlah: 0,
                catatan: 'Data uji penentu untuk kesalahan do-while'
            }
        ],
        hasilUji: [],
        errorTerakhir: null,
        sudahDijalankan: false,
        sedangJalan: false,

        async jalankanUji() {
            this.sedangJalan = true;
            this.hasilUji = [];
            this.errorTerakhir = null;

            try {
                await new Promise(r => setTimeout(r, 10));

                for (const d of this.dataUji) {
                    if (typeof window.jalankanKodeCpp !== 'function') {
                        throw new Error("Sistem gagal memuat eksekutor C++ (jalankanKodeCpp tidak ditemukan).");
                    }

                    const program = `#include <iostream>
using namespace std;
int main() {
    int jumlah = ${d.jumlah};
    int jam;
    ${this.kodePerbaikan}
    return 0;
}`;
                    const masukan = Array.from({
                        length: 20
                    }, () => 700);
                    const hasil = window.jalankanKodeCpp(program, masukan);

                    if (hasil.error) {
                        this.errorTerakhir = hasil.error;
                        this.hasilUji = [];
                        this.sudahDijalankan = true;
                        return;
                    }

                    const cinAktual = (hasil.keluaran.match(/Jam siswa ke-/g) || []).length;
                    const barisAktual = (hasil.keluaran.match(/\n/g) || []).length;
                    const cinSeharusnya = d.jumlah;
                    const barisSeharusnya = 2 * d.jumlah;

                    this.hasilUji.push({
                        jumlah: d.jumlah,
                        catatan: d.catatan,
                        cinAktual,
                        cinSeharusnya,
                        barisAktual,
                        barisSeharusnya,
                        batasWaktu: hasil.batasWaktuTerlampaui,
                        lolos: !hasil.batasWaktuTerlampaui && cinAktual === cinSeharusnya && barisAktual ===
                            barisSeharusnya,
                    });
                }
                this.sudahDijalankan = true;
            } catch (err) {
                this.errorTerakhir = err.message || err;
            } finally {
                this.sedangJalan = false;
            }
        },

        get jumlahLolos() {
            return this.hasilUji.filter(h => h.lolos).length;
        },

        kirim() {
            const poin = Math.round((this.jumlahLolos / 2) * 40);
            this.$wire.terimaHasil(this.jumlahLolos, this.kodePerbaikan, poin);
        }
    };
}
</script>