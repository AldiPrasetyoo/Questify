<div x-data="{
    nilaiAwal: 1,
    sudahDicoba: false,
    get keluaranWhile() {
        this.sudahDicoba = true;
        const program = `#include <iostream>
using namespace std;
int main() {
    int i = ${this.nilaiAwal};
    while (i <= 5) { cout << i; i++; }
    return 0;
}`;
        const hasil = window.jalankanKodeCpp(program, []);
        return hasil.error ? '(kesalahan)' : (hasil.keluaran || '(tidak ada keluaran)');
    },
    get keluaranDoWhile() {
        const program = `#include <iostream>
using namespace std;
int main() {
    int i = ${this.nilaiAwal};
    do { cout << i; i++; } while (i <= 5);
    return 0;
}`;
        const hasil = window.jalankanKodeCpp(program, []);
        return hasil.error ? '(kesalahan)' : (hasil.keluaran || '(tidak ada keluaran)');
    },
    selesai() { $wire.terimaHasil(5); }
}">
    <div class="flex items-center gap-2 mb-4">
        <label class="text-sm font-bold text-gray-700">Nilai awal i:</label>
        <input type="number" x-model.number="nilaiAwal" class="w-24 rounded-xl border-gray-200 text-sm">
        <span class="text-xs font-semibold text-gray-400">coba nilai 6 untuk melihat perbedaan mencoloknya</span>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-xs font-black text-gray-500 mb-1">while</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">int i = <span x-text="nilaiAwal"></span>;
while (i &lt;= 5) {
    cout &lt;&lt; i;
    i++;
}</div>
            <p class="text-sm font-bold mt-2">Keluaran: <span class="font-mono" x-text="keluaranWhile"></span></p>
        </div>
        <div>
            <p class="text-xs font-black text-gray-500 mb-1">do-while</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">int i = <span x-text="nilaiAwal"></span>;
do {
    cout &lt;&lt; i;
    i++;
} while (i &lt;= 5);</div>
            <p class="text-sm font-bold mt-2">Keluaran: <span class="font-mono" x-text="keluaranDoWhile"></span></p>
        </div>
    </div>
    <button @click="selesai" class="mt-6 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut &rarr;</button>
</div>
