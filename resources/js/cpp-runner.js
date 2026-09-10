import JSCPP from 'JSCPP';

/**
 * ============================================================================
 *  PEMBUNGKUS JSCPP — CATATAN KEAMANAN
 * ============================================================================
 * Berbeda dari pendekatan sebelumnya (interpreter PHP buatan sendiri di server),
 * ini menjalankan kode C++ SUNGGUHAN memakai JSCPP (github.com/felixhao28/JSCPP)
 * — LANGSUNG DI BROWSER siswa, bukan di server.
 *
 * Konsekuensi keamanan yang perlu dipahami:
 *  - Eksekusi terjadi di tab browser siswa sendiri. Kalaupun siswa menulis kode
 *    yang aneh atau berat, dampaknya hanya ke tab browsernya sendiri, TIDAK ke
 *    server atau siswa lain. Ini justru mengurangi risiko dibanding menjalankan
 *    kode tak tepercaya di server.
 *  - Infinite loop dicegah lewat `maxTimeout` bawaan JSCPP: begitu waktu eksekusi
 *    melewati batas, JSCPP melempar exception "Time limit exceeded" dan berhenti
 *    sendiri — sudah diuji nyata, bukan asumsi (lihat komentar di komponen yang
 *    memakainya).
 *  - Penilaian lolos/tidak dihitung DI SINI (client-side), lalu hasil akhirnya
 *    (jumlah lolos + kode akhir) dikirim ke Livewire lewat method PHP yang jauh
 *    lebih sederhana dari sebelumnya. Ini pilihan sadar: siswa yang sangat mahir
 *    secara teknis (buka console browser, panggil method Livewire manual) BISA
 *    memalsukan hasil. Untuk konteks latihan kelas (bukan ujian berbobot tinggi),
 *    ini trade-off yang diterima demi kemampuan menjalankan C++ yang jauh lebih
 *    lengkap (array, fungsi, switch-case, dst) dibanding interpreter buatan sendiri.
 * ============================================================================
 *
 * @param {string} kode - Kode C++ LENGKAP (harus punya #include, main(), dst).
 *                         Gunakan bungkusKode() di bawah kalau kode siswa cuma
 *                         berupa potongan tanpa boilerplate.
 * @param {number[]|string[]} nilaiMasukan - Nilai yang akan "diketik" ke cin,
 *                         berurutan, dipisah baris baru.
 * @param {number} batasWaktuMs - Batas waktu eksekusi dalam milidetik (default 3000).
 * @returns {{keluaran: string, error: string|null, batasWaktuTerlampaui: boolean}}
 */
export function jalankanKodeCpp(kode, nilaiMasukan = [], batasWaktuMs = 3000) {
    let keluaran = '';
    const config = {
        stdio: { write: (s) => { keluaran += s; } },
        maxTimeout: batasWaktuMs,
    };

    try {
        JSCPP.run(kode, nilaiMasukan.join('\n'), config);
        return { keluaran, error: null, batasWaktuTerlampaui: false };
    } catch (e) {
        const pesan = (e && e.message) ? e.message : String(e);
        const batasWaktuTerlampaui = /time limit/i.test(pesan);
        return {
            keluaran,
            error: batasWaktuTerlampaui ? null : pesan,
            batasWaktuTerlampaui,
        };
    }
}

/**
 * Membungkus potongan kode siswa (yang cuma berisi logika inti, tanpa
 * #include/main()) menjadi program C++ lengkap yang bisa dijalankan JSCPP.
 * Variabel di `variabelAwal` dideklarasikan otomatis sebelum kode siswa berjalan.
 *
 * @param {string} kodeSiswa
 * @param {Object<string,number>} variabelAwal - misal {jam: 715}
 */
export function bungkusKode(kodeSiswa, variabelAwal = {}) {
    const deklarasi = Object.entries(variabelAwal)
        .map(([nama, nilai]) => `    int ${nama} = ${nilai};`)
        .join('\n');

    return `#include <iostream>
using namespace std;
int main() {
${deklarasi}
${kodeSiswa}
    return 0;
}`;
}

// Ekspos global juga supaya bisa dipanggil langsung dari x-init/x-data di Blade
// tanpa perlu import ES module di tiap file (konsisten dengan cara Alpine dipakai
// di seluruh Questify).
window.jalankanKodeCpp = jalankanKodeCpp;
window.bungkusKode = bungkusKode;
