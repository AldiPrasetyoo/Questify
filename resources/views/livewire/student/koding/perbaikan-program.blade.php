<div x-data="perbaikanProgram()">
    <p class="text-sm font-semibold text-gray-600 mb-2">Program berikut disisipi empat kesalahan. Temukan dan perbaiki
        di editor di bawah.</p>
    <div class="font-mono bg-slate-900 text-rose-300 text-sm rounded-2xl p-4 mb-4 whitespace-pre"
        x-text="kodeBermasalah"></div>

    <details class="mb-4 text-sm">
        <summary class="cursor-pointer text-[#00c2cb] font-bold">Bantuan: telusuri manual dulu untuk jam 648, 715, dan
            800</summary>
        <p class="text-gray-500 font-semibold mt-2">Coba jalankan program di atas di kepalamu untuk ketiga nilai jam
            tersebut. Bandingkan hasilnya dengan yang seharusnya: 648 &rarr; Hadir, 715 &rarr; Terlambat, 800 &rarr;
            Alpa.</p>
    </details>

    <p class="text-sm font-bold text-gray-700 mb-1">Tulis versi perbaikanmu:</p>
    <textarea x-model="kodePerbaikan" rows="7"
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
                        <th class="p-2 font-black">Jam</th>
                        <th class="p-2 font-black">Seharusnya</th>
                        <th class="p-2 font-black">Keluaran</th>
                        <th class="p-2 font-black">Hasil</th>
                    </tr>
                </thead>
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
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 3 data uji lolos.</p>
            <template x-if="jumlahLolos === 3">
                <p class="text-sm font-bold text-emerald-600">🏅 Semua lolos!</p>
            </template>
            <button @click="kirim"
                class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim
                &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>

<script>
function perbaikanProgram() {
    return {
        kodeBermasalah: ` 9      if (jam <= 730); {
10          cout << "Terlambat";
11      }
12      else if (jam = 700) {
13          cout << "Hadir";
14      }
15      else
16          cout << "Alpa";
17          cout << " - harap menghadap guru piket";`,
        kodePerbaikan: `if (jam <= 700) {
    cout << "Hadir";
} else if (jam <= 730) {
    cout << "Terlambat";
} else {
    cout << "Alpa";
}`,
        dataUji: [
            [648, 'Hadir'],
            [715, 'Terlambat'],
            [800, 'Alpa']
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

                for (const [jam, seharusnya] of this.dataUji) {
                    // Mencegah error diam-diam jika script eksekusi C++ (JSCPP) belum di-include di layout
                    if (typeof window.bungkusKode !== 'function' || typeof window.jalankanKodeCpp !== 'function') {
                        throw new Error(
                            "Sistem gagal memuat eksekutor C++ (bungkusKode / jalankanKodeCpp tidak ditemukan)."
                        );
                    }

                    const program = window.bungkusKode(this.kodePerbaikan, {
                        jam
                    });
                    const hasil = window.jalankanKodeCpp(program, []);

                    if (hasil.error) {
                        this.errorTerakhir = hasil.error;
                        this.hasilUji = [];
                        this.sudahDijalankan = true;
                        return; // Keluar dari loop, masuk ke blok finally
                    }

                    this.hasilUji.push({
                        jam,
                        seharusnya,
                        keluaran: hasil.keluaran,
                        lolos: hasil.keluaran.trim() === seharusnya
                    });
                }
                this.sudahDijalankan = true;
            } catch (err) {
                // Menangkap error JS dan menampilkannya ke UI (kotak merah)
                this.errorTerakhir = err.message || err;
            } finally {
                // Apapun yang terjadi (berhasil atau error), matikan status loading
                this.sedangJalan = false;
            }
        },

        get jumlahLolos() {
            return this.hasilUji.filter(h => h.lolos).length;
        },

        kirim() {
            const poin = Math.round((this.jumlahLolos / 3) * 40);
            this.$wire.terimaHasil(this.jumlahLolos, this.kodePerbaikan, poin);
        }
    };
}
</script>