<div x-data="{
    nilaiAwal: 1,
    sudahDicoba: false,
    keluaranWhile: '',
    keluaranDoWhile: '',
    init() {
        this.jalankan();
        this.$watch('nilaiAwal', () => this.jalankan());
    },
    jalankan() {
        this.sudahDicoba = true;

        if (typeof window.jalankanKodeCpp !== 'function') {
            this.keluaranWhile = '(kesalahan)';
            this.keluaranDoWhile = '(kesalahan)';
            return;
        }

        const programWhile = `#include <iostream>
using namespace std;
int main() {
    int i = ${this.nilaiAwal};
    while (i <= 5) { cout << i; i++; }
    return 0;
}`;
        const hasilWhile = window.jalankanKodeCpp(programWhile, []);
        this.keluaranWhile = hasilWhile.error ? '(kesalahan)' : (hasilWhile.keluaran || '(tidak ada keluaran)');

        const programDoWhile = `#include <iostream>
using namespace std;
int main() {
    int i = ${this.nilaiAwal};
    do { cout << i; i++; } while (i <= 5);
    return 0;
}`;
        const hasilDoWhile = window.jalankanKodeCpp(programDoWhile, []);
        this.keluaranDoWhile = hasilDoWhile.error ? '(kesalahan)' : (hasilDoWhile.keluaran || '(tidak ada keluaran)');
    },
    selesai() { 
        this.$wire.terimaHasil(5); 
    }
}">
    <div class="flex items-center gap-2 mb-4">
        <label class="text-sm font-bold text-gray-700">Nilai awal i:</label>
        <input type="number" x-model.number="nilaiAwal" class="w-24 rounded-xl border-gray-200 text-sm p-2 border">
        <span class="text-xs font-semibold text-gray-400">coba nilai 6 untuk melihat perbedaan mencoloknya</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-xs font-black text-gray-500 mb-1">while</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">int i = <span
                    x-text="nilaiAwal"></span>;
                while (i &lt;= 5) {
                cout &lt;&lt; i;
                i++;
                }</div>
            <p class="text-sm font-bold mt-2">Keluaran: <span class="font-mono" x-text="keluaranWhile"></span></p>
        </div>
        <div>
            <p class="text-xs font-black text-gray-500 mb-1">do-while</p>
            <div class="font-mono bg-slate-900 text-slate-300 text-xs rounded-2xl p-3 whitespace-pre">int i = <span
                    x-text="nilaiAwal"></span>;
                do {
                cout &lt;&lt; i;
                i++;
                } while (i &lt;= 5);</div>
            <p class="text-sm font-bold mt-2">Keluaran: <span class="font-mono" x-text="keluaranDoWhile"></span></p>
        </div>
    </div>
    <button @click="selesai"
        class="mt-6 bg-[#00c2cb] hover:bg-teal-500 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition">Lanjut
        &rarr;</button>
</div>