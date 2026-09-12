<div x-data="ujiPerulangan()">
    <p class="text-sm font-semibold text-gray-600 mb-1">Tulis program pendataan kehadiran sejumlah siswa, lalu uji
        terhadap 5 data (termasuk jumlah = 0).</p>
    <p class="text-xs font-semibold text-gray-400 mb-3">Pengecekan menghitung berapa kali program benar-benar meminta
        masukan, dijalankan sungguhan lewat JSCPP.</p>

    <textarea x-model="kode" rows="6"
        class="w-full font-mono text-sm rounded-2xl border-gray-200 bg-slate-900 text-emerald-300 p-4"></textarea>

    <button @click="jalankanUji" :disabled="sedangJalan"
        class="mt-3 bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-50">
        <span x-show="!sedangJalan">▶ Jalankan Uji</span>
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
                        <th class="p-2 font-black">Jumlah siswa</th>
                        <th class="p-2 font-black">Seharusnya</th>
                        <th class="p-2 font-black">Aktual</th>
                        <th class="p-2 font-black">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="h in hasilUji" :key="h.jumlah">
                        <tr class="border-t" :class="h.lolos ? '' : 'bg-rose-50'">
                            <td class="p-2 font-mono">
                                <span x-text="h.jumlah"></span>
                                <template x-if="h.catatan"><span class="text-xs text-amber-600 font-semibold block"
                                        x-text="h.catatan"></span></template>
                            </td>
                            <td class="p-2 font-semibold"><span x-text="h.seharusnya"></span> kali</td>
                            <td class="p-2 font-semibold"
                                x-text="h.batasWaktu ? 'batas waktu terlampaui' : h.aktual + ' kali'"></td>
                            <td class="p-2 font-bold" x-text="h.lolos ? '✔ Lolos' : '✘ Belum'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 5 data uji lolos.</p>
            <button @click="kirim"
                class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim
                &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>

<script>
function ujiPerulangan() {
    return {
        kode: ``,
        dataUji: [
            [1, 1, ''],
            [3, 3, ''],
            [5, 5, ''],
            [10, 10, ''],
            [0, 0, 'Data uji penentu — do-while akan gagal di sini']
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

                for (const [jumlah, seharusnya, catatan] of this.dataUji) {
                    if (typeof window.jalankanKodeCpp !== 'function') {
                        throw new Error("Sistem gagal memuat eksekutor C++ (jalankanKodeCpp tidak ditemukan).");
                    }

                    const program =
                        `#include <iostream>\nusing namespace std;\nint main() {\n    int jumlah = ${jumlah};\n    int jam;\n${this.kode}\n    return 0;\n}`;
                    // Sediakan 20 nilai masukan cadangan — cukup untuk kasus normal maupun bug
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

                    const jumlahPrompt = (hasil.keluaran.match(/Jam kedatangan siswa ke-/g) || []).length;
                    this.hasilUji.push({
                        jumlah,
                        seharusnya,
                        catatan,
                        aktual: jumlahPrompt,
                        batasWaktu: hasil.batasWaktuTerlampaui,
                        lolos: !hasil.batasWaktuTerlampaui && jumlahPrompt === seharusnya
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
            const poin = Math.min(this.jumlahLolos * 7, 35);
            this.$wire.terimaHasil(this.jumlahLolos, this.kode, poin);
        }
    };
}
</script>