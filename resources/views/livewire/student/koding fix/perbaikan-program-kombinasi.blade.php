<div x-data="{
    kodeBermasalah: ` 9      for (int i = 1; i <= jumlah; i++) {
10          int hadir = 0, terlambat = 0, alpa = 0;
11          cout << \"Jam siswa ke-\" << i << \": \";
12          cin >> jam;
13
14          if (jam <= 700)
15              hadir++;
16          else if (jam <= 730)
17              terlambat++;
18          else
19              alpa++;
20              cout << \"Siswa alpa\" << endl;
21
22          cout << \"Persentase: \" << hadir / jumlah * 100 << \"%\" << endl;
23      }`,
    kodePerbaikan: `int hadir = 0, terlambat = 0, alpa = 0;

for (int i = 1; i <= jumlah; i++) {
    cout << \"Jam siswa ke-\" << i << \": \";
    cin >> jam;

    if (jam <= 700) {
        hadir++;
    } else if (jam <= 730) {
        terlambat++;
    } else {
        alpa++;
        cout << \"Siswa alpa\" << endl;
    }
}

cout << \"Persentase: \" << hadir * 100 / jumlah << \"%\" << endl;`,
    jumlahUji: 3,
    jamUji: [648, 715, 800],
    hasilUji: null,
    errorTerakhir: null,
    sudahDijalankan: false,
    sedangJalan: false,
    async jalankanUji() {
        this.sedangJalan = true;
        this.hasilUji = null;
        this.errorTerakhir = null;
        await new Promise(r => setTimeout(r, 10));
        const program = `#include <iostream>
using namespace std;
int main() {
    int jumlah = ${this.jumlahUji};
    int jam;
${this.kodePerbaikan}
    return 0;
}`;
        const hasil = window.jalankanKodeCpp(program, this.jamUji);
        if (hasil.error) { this.errorTerakhir = hasil.error; this.sudahDijalankan = true; this.sedangJalan = false; return; }

        const jumlahPesanAlpa = (hasil.keluaran.match(/Siswa alpa/g) || []).length;
        // Pengecekan presisi: karena program TIDAK mencetak rekap eksplisit (hanya persentase & pesan alpa),
        // kesalahan 1 (letak inisialisasi) dicek lewat kebenaran persentase & jumlah pesan alpa itu sendiri —
        // kalau inisialisasi salah letak, hadir/terlambat/alpa akan reset tiap iterasi sehingga persentase pasti keliru.
        const persenOk = hasil.keluaran.includes('33');
        const alpaOk = jumlahPesanAlpa === 1;
        this.hasilUji = {
            batasWaktu: hasil.batasWaktuTerlampaui,
            jumlahPesanAlpa,
            kesalahanKurung: alpaOk,
            kesalahanPersentase: persenOk,
            kesalahanInisialisasi: persenOk, // persentase yang benar hanya mungkin kalau hadir/terlambat/alpa terakumulasi dengan benar
            keluaran: hasil.keluaran,
        };
        this.sudahDijalankan = true;
        this.sedangJalan = false;
    },
    get jumlahLolos() {
        if (!this.hasilUji) return 0;
        return [this.hasilUji.kesalahanInisialisasi, this.hasilUji.kesalahanKurung, this.hasilUji.kesalahanPersentase].filter(Boolean).length;
    },
    kirim() {
        const poin = Math.round((this.jumlahLolos / 3) * 40);
        $wire.terimaHasil(this.jumlahLolos, this.kodePerbaikan, poin);
    }
}">
    <p class="text-sm font-semibold text-gray-600 mb-2">Program berikut berjalan tanpa pesan galat, tetapi hasilnya keliru. Ada empat kesalahan tersembunyi.</p>
    <div class="font-mono bg-slate-900 text-rose-300 text-xs rounded-2xl p-4 mb-4 whitespace-pre overflow-x-auto" x-text="kodeBermasalah"></div>

    <details class="mb-4 text-sm">
        <summary class="cursor-pointer text-[#00c2cb] font-bold">Bantuan: telusuri manual dulu untuk jumlah = 3, jam 648/715/800</summary>
        <p class="text-gray-500 font-semibold mt-2">Periksa baris 10 — apa yang terjadi pada nilai hadir/terlambat/alpa setiap kali pengulangan dimulai lagi? Lalu baris 18-20. Terakhir baris 22.</p>
    </details>

    <p class="text-sm font-bold text-gray-700 mb-1">Tulis versi perbaikanmu:</p>
    <textarea x-model="kodePerbaikan" rows="16" class="w-full font-mono text-sm rounded-2xl border-gray-200 bg-slate-900 text-emerald-300 p-4"></textarea>

    <button @click="jalankanUji" :disabled="sedangJalan" class="mt-3 bg-slate-700 text-white font-bold px-6 py-3 rounded-xl text-sm disabled:opacity-50">
        <span x-show="!sedangJalan">▶ Uji Perbaikan</span>
        <span x-show="sedangJalan">Menjalankan...</span>
    </button>

    <template x-if="errorTerakhir">
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl p-3 text-sm font-semibold" x-text="'⚠ ' + errorTerakhir"></div>
    </template>

    <template x-if="sudahDijalankan && !errorTerakhir && hasilUji">
        <div>
            <div class="mt-4 space-y-2 text-sm font-semibold">
                <p :class="hasilUji.kesalahanInisialisasi ? 'text-emerald-600' : 'text-rose-600'">
                    <span x-text="hasilUji.kesalahanInisialisasi ? '✔' : '✘'"></span> Letak inisialisasi (dicek lewat kebenaran persentase akhir)
                </p>
                <p :class="hasilUji.kesalahanKurung ? 'text-emerald-600' : 'text-rose-600'">
                    <span x-text="hasilUji.kesalahanKurung ? '✔' : '✘'"></span> Kurung kurawal blok else — "Siswa alpa" tercetak <span x-text="hasilUji.jumlahPesanAlpa"></span> kali (seharusnya 1 kali)
                </p>
                <p :class="hasilUji.kesalahanPersentase ? 'text-emerald-600' : 'text-rose-600'">
                    <span x-text="hasilUji.kesalahanPersentase ? '✔' : '✘'"></span> Letak &amp; perhitungan persentase (seharusnya 33%)
                </p>
            </div>
            <p class="text-sm font-bold mt-3"><span x-text="jumlahLolos"></span> dari 3 pemeriksaan lolos.</p>
            <template x-if="jumlahLolos === 3">
                <p class="text-sm font-bold text-emerald-600">🏅 Semua lolos!</p>
            </template>
            <button @click="kirim" class="mt-4 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Kirim &amp; Lanjut &rarr;</button>
        </div>
    </template>
</div>
