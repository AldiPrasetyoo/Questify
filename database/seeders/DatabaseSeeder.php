<?php

namespace Database\Seeders;

use App\Models\KontenMisi;
use App\Models\Lencana;
use App\Models\Misi;
use App\Models\Pertemuan;
use App\Models\KuisSoal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Berisi konten ASLI dari dokumen "Bahan Ajar Lengkap Pertemuan 1, 2, dan 3"
 * dikombinasikan dengan akun autentikasi utama (teacher & student) untuk platform Questify.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Akun Utama (Teacher & Student) sesuai standar Questify
        User::updateOrCreate(
            [
                'email' => 'guru@questify.test',
            ],
            [
                'name' => 'Guru Questify',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'siswa@questify.test',
            ],
            [
                'name' => 'Siswa Questify',
                'password' => Hash::make('12345678'),
                'role' => 'student',
            ]
        );

        // 2. Jalankan Seeder Materi Pertemuan 1, 2, dan 3
        $this->seedPertemuan1();
        $this->seedPertemuan2();
        $this->seedPertemuan3();

        $this->command->info('Login utama: guru@questify.test / 12345678 | siswa@questify.test / 12345678');
        $this->command->info('SELURUH RANGKAIAN TIGA PERTEMUAN SELESAI DISEED.');
    }

    private function seedPertemuan1(): void
    {
        $p1 = Pertemuan::create([
            'urutan' => 1,
            'judul' => 'Percabangan',
            'deskripsi' => 'Struktur kontrol percabangan: pengertian, operator relasi & logika, sintaks if/if-else/if-else if, dan penerapannya pada kasus penentuan status kehadiran.',
            'tujuan_pembelajaran' => "X.PD.1.1 Mengidentifikasi pengertian percabangan dan simbol decision pada flowchart\nX.PD.1.2 Mengidentifikasi jenis dan fungsi operator relasi serta operator logika\nX.PD.1.3 Mengenali bentuk umum sintaks if, if-else, dan if-else if\nX.PD.1.4 Menjelaskan alur eksekusi dan menentukan hasil evaluasi ekspresi kondisi\nX.PD.1.5 Menerapkan percabangan bertingkat pada kasus penentuan status kehadiran\nX.PD.1.6 Menganalisis kesalahan sintaks dan logika serta memperbaikinya\nX.PD.1.7 Membandingkan switch-case dengan if-else if dan menerapkannya",
            'aktif' => true,
        ]);

        // ================= MISI 1 — Orientasi Masalah (F.1, 10 menit) =================
        $misi1 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'pra_kelas',
            'urutan' => 1,
            'judul' => 'Misi 1: Orientasi Masalah',
            'estimasi_menit' => 10,
            'poin_maksimal' => 25,
            'deskripsi' => 'Mengalami sendiri masalah rekap kehadiran manual, sebelum diberi tahu nama konsepnya (percabangan).',
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Layar 1 — Kartu Tugas',
            'konten_html' => <<<'HTML'
<p><strong>PERMINTAAN PENGEMBANGAN SISTEM</strong></p>
<p>Dari: Wakil Kepala Sekolah Bidang Kesiswaan<br>Untuk: Tim Pengembang Kelas X PPLG</p>
<p>Pencatatan kehadiran siswa masih dilakukan manual di buku absensi. Saat merekap, guru harus memeriksa jam kedatangan setiap siswa satu per satu untuk menentukan statusnya. Kami membutuhkan program yang dapat menentukan status kehadiran seorang siswa secara otomatis berdasarkan jam kedatangannya.</p>
<p><strong>Ketentuan status kehadiran:</strong></p>
<ul>
<li>Pukul 07.00 atau sebelumnya &rarr; Hadir</li>
<li>Pukul 07.01 sampai 07.30 &rarr; Terlambat</li>
<li>Setelah pukul 07.30 &rarr; Alpa</li>
</ul>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Layar 2-3 — Simulasi Rekap Manual',
            'komponen_koding' => 'simulasi-kehadiran',
            'konfigurasi_koding' => [],
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Menurutmu, keputusan apa saja yang kamu ulang-ulang tadi waktu menentukan status?',
        ]);
        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 4,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Supaya komputer bisa mengambil keputusan itu sendiri, apa yang harus kamu pelajari lebih dulu?',
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 5,
            'tipe' => 'teks_web',
            'judul' => 'Layar 5 — Pengenalan Konsep',
            'konten_html' => <<<'HTML'
<p>Yang barusan kamu lakukan berulang-ulang tadi punya nama. Setiap kali kamu melihat jam kedatangan lalu memutuskan status yang tepat, kamu sedang mengambil keputusan berdasarkan suatu kondisi.</p>
<p>Dalam pemrograman, kemampuan program untuk memilih jalur perintah berdasarkan hasil pemeriksaan suatu kondisi disebut <strong>percabangan</strong>.</p>
<p>Tanpa percabangan, program hanya bisa menjalankan seluruh perintah berurutan dari atas ke bawah, tanpa ada satu pun yang boleh dilewati. Dengan percabangan, program memeriksa keadaan lebih dahulu, lalu memilih perintah mana yang dijalankan dan mana yang dilewati.</p>
<p>Pada flowchart, keputusan digambarkan dengan simbol belah ketupat yang disebut simbol <em>decision</em>. Isinya selalu berupa kondisi yang jawabannya hanya benar atau salah, dan dari simbol itu keluar tepat dua jalur, yaitu Ya dan Tidak.</p>
HTML,
        ]);

        $kuis1 = KontenMisi::create(['misi_id' => $misi1->id, 'urutan' => 6, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 1']);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 1,
            'pertanyaan' => 'Apa yang membedakan alur bercabang dengan alur sekuensial?',
            'pilihan' => ['a' => 'Perintah dijalankan lebih cepat', 'b' => 'Ada perintah yang bisa dilewati', 'c' => 'Perintah ditulis lebih pendek', 'd' => 'Program berhenti lebih awal'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 2,
            'pertanyaan' => 'Simbol apa yang dipakai untuk menggambarkan keputusan pada flowchart?',
            'pilihan' => ['a' => 'Persegi panjang', 'b' => 'Jajar genjang', 'c' => 'Belah ketupat', 'd' => 'Lingkaran'],
            'kunci_jawaban' => 'C',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 3,
            'pertanyaan' => 'Berapa banyak keluaran yang dimiliki sebuah simbol decision?',
            'pilihan' => ['a' => 'Satu', 'b' => 'Dua', 'c' => 'Tiga', 'd' => 'Sesuai banyaknya kondisi'],
            'kunci_jawaban' => 'B',
        ]);

        // ================= MISI 2 — Operator Kondisi (F.2, 15 menit) =================
        $misi2 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'pra_kelas',
            'urutan' => 2,
            'judul' => 'Misi 2: Operator Kondisi',
            'estimasi_menit' => 15,
            'poin_maksimal' => 25,
            'misi_prasyarat_id' => $misi1->id,
            'deskripsi' => 'Alat untuk menyusun kondisi (operator relasi & logika), sebelum masuk ke bentuk penulisan sintaks.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1 — Nilai Logika',
            'konten_html' => <<<'HTML'
<p>Sebuah kondisi selalu bernilai benar atau salah. Tidak ada jawaban ketiga. Nilai inilah yang dibaca program untuk menentukan jalur mana yang akan dijalankan.</p>
<p>Dalam C++, nilai benar dan salah disebut nilai boolean, dan ditampilkan sebagai angka: benar sebagai 1, salah sebagai 0.</p>
<p>Misalnya, apabila nilai jam adalah 648, maka:</p>
<pre>jam &lt;= 700    &rarr;  benar
jam == 700    &rarr;  salah
jam &gt; 730     &rarr;  salah
jam != 700    &rarr;  benar</pre>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Bagian 2 — Operator Relasi',
            'komponen_koding' => 'evaluator-kondisi',
            'konfigurasi_koding' => ['nilai_jam_awal' => 715],
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 3,
            'tipe' => 'koding',
            'judul' => 'Bagian 3 — Operator Logika & Tabel Kebenaran',
            'komponen_koding' => 'tabel-kebenaran',
            'konfigurasi_koding' => ['nilai_jam_awal' => 715],
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 4,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 4 — Dua Tanda yang Sering Tertukar',
            'konten_html' => <<<'HTML'
<p>Ada satu kesalahan yang sangat sering terjadi dan sulit ditemukan, yaitu tertukarnya tanda sama dengan tunggal dengan tanda sama dengan ganda. Tanda tunggal berarti mengisi nilai, tanda ganda berarti membandingkan nilai.</p>
<p><strong>Keliru:</strong></p>
<pre>if (jam = 700) { ... }</pre>
<p>Nilai jam berubah menjadi 700, dan kondisi dianggap benar untuk siapa pun.</p>
<p><strong>Benar:</strong></p>
<pre>if (jam == 700) { ... }</pre>
<p>Program memeriksa apakah nilai jam sama dengan 700, tanpa mengubah nilainya.</p>
HTML,
        ]);

        $kuis2 = KontenMisi::create(['misi_id' => $misi2->id, 'urutan' => 5, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 2']);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 1,
            'pertanyaan' => 'Operator mana yang bernilai benar hanya apabila kedua kondisi bernilai benar?',
            'pilihan' => ['a' => '&&', 'b' => '||', 'c' => '!', 'd' => '=='],
            'kunci_jawaban' => 'A',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 2,
            'pertanyaan' => 'Apa akibat menulis if (jam = 700) padahal maksudnya membandingkan?',
            'pilihan' => ['a' => 'Program error saat dikompilasi', 'b' => 'Nilai jam berubah jadi 700 dan kondisi selalu benar', 'c' => 'Tidak ada bedanya dengan ==', 'd' => 'Program tidak akan berjalan'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 3,
            'pertanyaan' => 'Jika jam = 715, apa hasil dari ekspresi jam >= 701?',
            'pilihan' => ['a' => 'benar', 'b' => 'salah', 'c' => '1 dan 0', 'd' => 'tidak dapat ditentukan'],
            'kunci_jawaban' => 'A',
        ]);

        // ================= MISI 3 — Sintaks Percabangan (F.3, 15 menit) =================
        $misi3 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'pra_kelas',
            'urutan' => 3,
            'judul' => 'Misi 3: Sintaks Percabangan',
            'estimasi_menit' => 15,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi2->id,
            'deskripsi' => 'Mengenali bentuk penulisan if tunggal, if-else, dan if-else if — bukan menulis program dari nol.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1a — If Tunggal',
            'konten_html' => <<<'HTML'
<p>Bentuk paling sederhana adalah if tunggal. Apabila kondisi bernilai benar, blok di dalam kurung kurawal dijalankan. Apabila salah, blok itu dilewati dan program lanjut ke baris berikutnya. Tidak ada jalur cadangan.</p>
<pre>if (jam &lt;= 700) {
    cout &lt;&lt; "Hadir";
}</pre>
<p>Bentuk ini dipakai ketika ada perintah yang hanya perlu dijalankan pada keadaan tertentu saja.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1b — If-Else',
            'konten_html' => <<<'HTML'
<p>Bentuk kedua menambahkan jalur cadangan. Selalu ada tepat satu blok yang dijalankan. Tidak mungkin keduanya dijalankan bersamaan, dan tidak mungkin keduanya dilewati.</p>
<pre>if (jam &lt;= 700) {
    cout &lt;&lt; "Hadir";
} else {
    cout &lt;&lt; "Tidak hadir";
}</pre>
<p>Kata <code>else</code> tidak memiliki kondisi sendiri. Artinya sederhana saja, yaitu selain itu. Dipakai ketika ada dua kemungkinan yang saling berlawanan.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 3,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1c — If-Else If',
            'konten_html' => <<<'HTML'
<p>Bentuk ketiga dipakai ketika kemungkinannya ada tiga atau lebih. Kondisi diperiksa dari atas ke bawah, dan pemeriksaan berhenti pada kondisi pertama yang bernilai benar. Kondisi di bawahnya tidak diperiksa lagi.</p>
<pre>if (jam &lt;= 700) {
    cout &lt;&lt; "Hadir";
} else if (jam &lt;= 730) {
    cout &lt;&lt; "Terlambat";
} else {
    cout &lt;&lt; "Alpa";
}</pre>
<p>Perhatikan kondisi kedua. Cukup ditulis <code>jam &lt;= 730</code>, tidak perlu <code>jam &gt; 700 &amp;&amp; jam &lt;= 730</code>. Alasannya, apabila program sampai ke baris tersebut, berarti kondisi pertama sudah pasti salah, sehingga nilai jam sudah pasti lebih dari 700.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 4,
            'tipe' => 'koding',
            'judul' => 'Bagian 1d — Penyorotan Baris (If Tunggal)',
            'komponen_koding' => 'penyorotan-eksekusi',
            'konfigurasi_koding' => ['bentuk' => 'if_tunggal', 'nilai_jam_awal' => 715],
        ]);
        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 5,
            'tipe' => 'koding',
            'judul' => 'Bagian 1d — Penyorotan Baris (If-Else)',
            'komponen_koding' => 'penyorotan-eksekusi',
            'konfigurasi_koding' => ['bentuk' => 'if_else', 'nilai_jam_awal' => 715],
        ]);
        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 6,
            'tipe' => 'koding',
            'judul' => 'Bagian 1d — Penyorotan Baris (If-Else If)',
            'komponen_koding' => 'penyorotan-eksekusi',
            'konfigurasi_koding' => ['bentuk' => 'if_else_if', 'nilai_jam_awal' => 715],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 7,
            'tipe' => 'koding',
            'judul' => 'Bagian 2 — Bedah Anatomi Kode',
            'komponen_koding' => 'bedah-kode',
            'konfigurasi_koding' => [
                'kode' => "if (jam <= 700) {\n    cout << \"Hadir\";\n}",
                'bagian' => [
                    ['nama' => 'Kata kunci', 'potongan' => 'if', 'keterangan' => 'Menandai awal sebuah percabangan'],
                    ['nama' => 'Kondisi', 'potongan' => ' (jam <= 700) ', 'keterangan' => 'Ditulis dalam tanda kurung. Isinya diperiksa benar atau salah'],
                    ['nama' => 'Kurung kurawal', 'potongan' => "{\n    ", 'keterangan' => 'Menandai awal dan akhir blok perintah'],
                    ['nama' => 'Isi blok', 'potongan' => 'cout << "Hadir";', 'keterangan' => 'Perintah yang dijalankan apabila kondisi bernilai benar'],
                    ['nama' => 'Kurung kurawal', 'potongan' => "\n}", 'keterangan' => 'Menandai awal dan akhir blok perintah'],
                ],
            ],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 8,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 3 — Urutan Kondisi Menentukan Hasil',
            'konten_html' => <<<'HTML'
<p>Dua program berikut terlihat mirip, tetapi hasilnya berbeda jauh.</p>
<p><strong>Urutan benar:</strong></p>
<pre>if (jam &lt;= 700) cout &lt;&lt; "Hadir";
else if (jam &lt;= 730) cout &lt;&lt; "Terlambat";</pre>
<p><strong>Urutan terbalik:</strong></p>
<pre>if (jam &lt;= 730) cout &lt;&lt; "Terlambat";
else if (jam &lt;= 700) cout &lt;&lt; "Hadir";</pre>
<p>Pada program kedua, siswa yang datang pukul 06.50 akan tercatat Terlambat, dan cabang Hadir tidak akan pernah tercapai. Program tetap berjalan tanpa pesan galat, sehingga kekeliruannya tidak terlihat. Inilah yang disebut <strong>kesalahan logika</strong>.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 9,
            'tipe' => 'koding',
            'judul' => 'Bagian 4 — Latihan Melengkapi Kode',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => [
                'butir' => [
                    [
                        'kode' => "if (nilai ___0 75) {\n    cout << \"Lulus\";\n} ___1 {\n    cout << \"Belum lulus\";\n}",
                        'jawaban' => ['0' => '>=', '1' => 'else'],
                    ],
                    [
                        'kode' => "if (angka % 2 ___0 0) {\n    cout << \"Genap\";\n}",
                        'jawaban' => ['0' => '=='],
                    ],
                    [
                        'kode' => "if (nilai >= 85) {\n    cout << \"A\";\n} ___0 (nilai >= 70) {\n    cout << \"B\";\n} ___1 {\n    cout << \"C\";\n}",
                        'jawaban' => ['0' => 'else if', '1' => 'else'],
                    ],
                ],
            ],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 10,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 5 — Ringkasan Tiga Bentuk',
            'konten_html' => <<<'HTML'
<table border="1" cellpadding="6">
<tr><th>Bentuk</th><th>Banyaknya jalur</th><th>Dipakai ketika</th><th>Contoh pada kasus absensi</th></tr>
<tr><td>if</td><td>Satu, boleh tidak dijalankan</td><td>Ada perintah yang hanya perlu dijalankan pada keadaan tertentu</td><td>Mencetak pesan hanya untuk siswa yang hadir</td></tr>
<tr><td>if-else</td><td>Dua, pasti satu dijalankan</td><td>Ada dua kemungkinan yang saling berlawanan</td><td>Membedakan hadir dan tidak hadir</td></tr>
<tr><td>if-else if</td><td>Tiga atau lebih</td><td>Ada tiga kemungkinan atau lebih</td><td>Membedakan Hadir, Terlambat, dan Alpa</td></tr>
</table>
HTML,
        ]);

        $kuis3 = KontenMisi::create(['misi_id' => $misi3->id, 'urutan' => 11, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 3']);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 1,
            'pertanyaan' => 'Sebuah program harus memilih di antara empat keterangan berbeda. Bentuk mana yang tepat?',
            'pilihan' => ['a' => 'if tunggal', 'b' => 'if-else', 'c' => 'if-else if', 'd' => 'Tidak perlu percabangan'],
            'kunci_jawaban' => 'C',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 2,
            'pertanyaan' => 'Pada if-else if, apa yang terjadi setelah ditemukan kondisi pertama yang bernilai benar?',
            'pilihan' => ['a' => 'Kondisi berikutnya tetap diperiksa', 'b' => 'Pemeriksaan berhenti', 'c' => 'Program berhenti', 'd' => 'Seluruh blok dijalankan'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 3,
            'pertanyaan' => 'Bagian manakah yang menandai awal dan akhir sebuah blok perintah?',
            'pilihan' => ['a' => 'Tanda kurung', 'b' => 'Kurung kurawal', 'c' => 'Titik koma', 'd' => 'Kata kunci if'],
            'kunci_jawaban' => 'B',
        ]);

        // ================= MISI 4 — Uji Kesiapan (F.4, 20 menit) =================
        $misi4 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'pra_kelas',
            'urutan' => 4,
            'judul' => 'Misi 4: Uji Kesiapan',
            'estimasi_menit' => 20,
            'poin_maksimal' => 50,
            'misi_prasyarat_id' => $misi3->id,
            'deskripsi' => 'Gerbang menuju fase tatap muka — kuis komprehensif pra-kelas + kolom hal yang belum dipahami.',
        ]);

        $kuis4 = KontenMisi::create([
            'misi_id' => $misi4->id,
            'urutan' => 1,
            'tipe' => 'kuis',
            'judul' => 'Kuis Keseluruhan Pra-Kelas',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 1,
            'pertanyaan' => 'Simbol belah ketupat pada flowchart menandai adanya...',
            'pilihan' => ['a' => 'Proses perhitungan', 'b' => 'Keputusan (decision)', 'c' => 'Masukan data', 'd' => 'Keluaran data'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 2,
            'pertanyaan' => 'Sebuah simbol decision pada flowchart selalu memiliki keluaran sebanyak...',
            'pilihan' => ['a' => 'Satu', 'b' => 'Dua', 'c' => 'Tiga', 'd' => 'Bergantung jumlah kondisi'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 3,
            'pertanyaan' => 'Jika jam = 648, berapa hasil dari jam <= 700?',
            'pilihan' => ['a' => 'benar', 'b' => 'salah', 'c' => '648', 'd' => 'tidak dapat ditentukan'],
            'kunci_jawaban' => 'A',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 4,
            'pertanyaan' => 'Jika jam = 731, berapa hasil dari jam > 700 && jam <= 730?',
            'pilihan' => ['a' => 'benar', 'b' => 'salah', 'c' => '731', 'd' => 'tidak dapat ditentukan'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 5,
            'pertanyaan' => 'Jika jam = 648, berapa hasil dari !(jam <= 700)?',
            'pilihan' => ['a' => 'benar', 'b' => 'salah', 'c' => '648', 'd' => 'tidak dapat ditentukan'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 6,
            'pertanyaan' => 'Untuk kode "if (jam <= 700) cout << \"Hadir\"; else if (jam <= 730) cout << \"Terlambat\";" dengan jam = 650, apa keluarannya?',
            'pilihan' => ['a' => 'Hadir', 'b' => 'Terlambat', 'c' => 'Keduanya', 'd' => 'Tidak ada'],
            'kunci_jawaban' => 'A',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 7,
            'pertanyaan' => 'Kasus: nilai 90 ke atas = A, 75 sampai 89 = B, di bawah 75 = C. Susunan if-else if yang tepat memeriksa kondisi dari...',
            'pilihan' => ['a' => 'Batas terendah ke tertinggi', 'b' => 'Batas tertinggi ke terendah', 'c' => 'Urutan bebas, hasil selalu sama', 'd' => 'Acak'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 8,
            'pertanyaan' => 'Mengapa if (jam = 700) berbahaya sebagai pengganti if (jam == 700)?',
            'pilihan' => ['a' => 'Sintaksnya salah dan tidak akan dikompilasi', 'b' => 'Mengubah nilai jam menjadi 700 dan selalu bernilai benar', 'c' => 'Hasilnya identik, hanya gaya penulisan', 'd' => 'Program akan berhenti'],
            'kunci_jawaban' => 'B',
        ]);

        KontenMisi::create([
            'misi_id' => $misi4->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bagian mana yang masih membuatmu bingung dari Misi 1 sampai 3?',
        ]);

        // ================= MISI 5 — Ruang Kelompok (F.6, Tatap Muka, 10 menit) =================
        $misi5 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'tatap_muka',
            'urutan' => 1,
            'judul' => 'Misi 5: Ruang Kelompok',
            'estimasi_menit' => 10,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi4->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Merumuskan masalah bersama kelompok: tabel kondisi status kehadiran dan bentuk percabangan yang tepat.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi5->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Form Rumusan Masalah Kelompok',
            'komponen_koding' => 'form-rumusan-masalah',
            'konfigurasi_koding' => [],
        ]);

        // ================= MISI 6 — Bangun Program (F.7, Tatap Muka, 30 menit) =================
        $misi6 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'tatap_muka',
            'urutan' => 2,
            'judul' => 'Misi 6: Bangun Program',
            'estimasi_menit' => 30,
            'poin_maksimal' => 35,
            'misi_prasyarat_id' => $misi5->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menulis program penentu status kehadiran dan mengujinya terhadap 7 data uji, termasuk seluruh jam batas.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi6->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Editor Kode Kelompok & Tabel Uji',
            'komponen_koding' => 'editor-kode-uji',
            'konfigurasi_koding' => [],
        ]);

        // ================= MISI 7 — Demo Karya (F.8, Tatap Muka, 20 menit) =================
        $misi7 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'tatap_muka',
            'urutan' => 3,
            'judul' => 'Misi 7: Demo Karya',
            'estimasi_menit' => 20,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi6->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Presentasi program kelompok dan tanggapan antar-kelompok.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi7->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Ruang Presentasi dan Tanggapan',
            'komponen_koding' => 'demo-karya',
            'konfigurasi_koding' => [],
        ]);

        // ================= MISI 8 — Perbaiki Program (F.9, Tatap Muka, 15 menit) =================
        $misi8 = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'tatap_muka',
            'urutan' => 4,
            'judul' => 'Misi 8: Perbaiki Program',
            'estimasi_menit' => 15,
            'poin_maksimal' => 40,
            'misi_prasyarat_id' => $misi7->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menemukan dan memperbaiki 4 kesalahan (sintaks & logika) pada program yang disajikan.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi8->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Program Bermasalah & Perbaikan',
            'komponen_koding' => 'perbaikan-program',
            'konfigurasi_koding' => [],
        ]);

        // ================= REFLEKSI & PENUTUP (F.10, 5 menit) =================
        $refleksi = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'tatap_muka',
            'urutan' => 5,
            'judul' => 'Refleksi dan Penutup',
            'estimasi_menit' => 5,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi8->id,
            'deskripsi' => 'Tiga pertanyaan refleksi penutup sesi tatap muka.',
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 1,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bagian mana dari kegiatan hari ini yang paling menantang bagi kelompokmu?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Apa satu hal baru yang kamu pahami hari ini yang belum kamu pahami semalam?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Kesalahan mana yang paling ingin kamu hindari saat menulis program berikutnya?'
        ]);

        // ================= MISI BONUS — Struktur Alternatif (F.11, Pasca-Kelas) =================
        $bonus = Misi::create([
            'pertemuan_id' => $p1->id,
            'fase' => 'pasca_kelas',
            'urutan' => 1,
            'judul' => 'Misi Bonus: Struktur Alternatif (switch-case)',
            'estimasi_menit' => 15,
            'poin_maksimal' => 20,
            'deskripsi' => 'Terbuka untuk semua peserta didik tanpa syarat, termasuk yang belum tuntas misi sebelumnya.',
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Materi Switch-Case',
            'konten_html' => <<<'HTML'
<p>Selain if-else if, C++ menyediakan bentuk lain untuk memilih di antara banyak kemungkinan, yaitu switch-case.</p>
<pre>switch (kode) {
    case 1: cout << "Hadir"; break;
    case 2: cout << "Terlambat"; break;
    default: cout << "Alpa";
}</pre>
<p>Kata <code>break</code> menandai akhir setiap pilihan. Apabila break tidak dituliskan, program akan lanjut menjalankan pilihan di bawahnya meskipun tidak sesuai.</p>
<p>Kata <code>default</code> berperan seperti else, yaitu dijalankan apabila tidak ada satu pun case yang cocok.</p>
<p><strong>Batasan penting:</strong> switch-case hanya dapat dipakai untuk membandingkan nilai yang setara, bukan rentang nilai. Karena itu, untuk kasus absensi yang memakai rentang jam, bentuk yang tepat tetap if-else if.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Perbandingan Dua Bentuk',
            'konten_html' => <<<'HTML'
<table border="1" cellpadding="6">
<tr><th></th><th>if-else if</th><th>switch-case</th></tr>
<tr><td>Jenis perbandingan</td><td>Rentang maupun nilai setara</td><td>Hanya nilai setara</td></tr>
<tr><td>Penanda akhir pilihan</td><td>Tidak diperlukan</td><td>Kata break</td></tr>
<tr><td>Penampung sisa</td><td>else</td><td>default</td></tr>
<tr><td>Cocok untuk kasus absensi</td><td>Ya</td><td>Tidak, karena memakai rentang jam</td></tr>
</table>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 3,
            'tipe' => 'koding',
            'judul' => 'Latihan Melengkapi Switch-Case',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => [
                'butir' => [[
                    'kode' => "switch (kode) {\n    case 1: cout << \"Hadir\"; ___0\n    case 2: cout << \"Terlambat\"; ___1\n    ___2: cout << \"Alpa\";\n}",
                    'jawaban' => ['0' => 'break;', '1' => 'break;', '2' => 'default'],
                ]],
            ],
        ]);

        $kuisBonus = KontenMisi::create(['misi_id' => $bonus->id, 'urutan' => 4, 'tipe' => 'kuis', 'judul' => 'Kuis Struktur Alternatif']);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 1,
            'pertanyaan' => 'Kasus manakah yang lebih tepat memakai switch-case?',
            'pilihan' => ['a' => 'Menentukan status dari rentang jam', 'b' => 'Menentukan nama hari dari angka 1 sampai 7', 'c' => 'Menentukan lulus dari rentang nilai', 'd' => 'Menentukan diskon dari rentang belanja'],
            'kunci_jawaban' => 'B',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 2,
            'pertanyaan' => 'Apa yang terjadi apabila break tidak dituliskan pada suatu case?',
            'pilihan' => ['a' => 'Program gagal dikompilasi', 'b' => 'Program berhenti', 'c' => 'Program lanjut menjalankan case di bawahnya', 'd' => 'Tidak berpengaruh'],
            'kunci_jawaban' => 'C',
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 3,
            'pertanyaan' => 'Bagian manakah pada switch-case yang berperan seperti else?',
            'pilihan' => ['a' => 'case', 'b' => 'break', 'c' => 'default', 'd' => 'switch'],
            'kunci_jawaban' => 'C',
        ]);

        // ================= LENCANA =================
        Lencana::create([
            'kode' => 'penemu_masalah',
            'nama' => 'Penemu Masalah',
            'ikon' => '🔍',
            'deskripsi' => 'Diberikan setelah menyelesaikan simulasi rekap kehadiran manual di Misi 1.',
            'syarat' => 'Selesaikan Misi 1: Orientasi Masalah',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi1->id],
        ]);

        Lencana::create([
            'kode' => 'pembaca_kondisi',
            'nama' => 'Pembaca Kondisi',
            'ikon' => '⚡',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 2 tuntas.',
            'syarat' => 'Selesaikan Misi 2: Operator Kondisi',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi2->id],
        ]);

        Lencana::create([
            'kode' => 'penulis_cabang',
            'nama' => 'Penulis Cabang',
            'ikon' => '🌿',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 3 tuntas.',
            'syarat' => 'Selesaikan Misi 3: Sintaks Percabangan',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi3->id],
        ]);

        Lencana::create([
            'kode' => 'tuntas_pra_kelas_p1',
            'nama' => 'Siap Tatap Muka',
            'ikon' => '🎯',
            'deskripsi' => 'Diberikan setelah menyelesaikan seluruh misi pra-kelas Pertemuan 1 — membuka fase tatap muka.',
            'syarat' => 'Selesaikan semua misi pra-kelas Pertemuan 1',
            'kriteria' => ['tipe' => 'selesaikan_fase', 'pertemuan_id' => $p1->id, 'fase' => 'pra_kelas'],
        ]);

        Lencana::create([
            'kode' => 'penjaga_batas',
            'nama' => 'Penjaga Batas',
            'ikon' => '🛡️',
            'deskripsi' => 'Diberikan apabila ketujuh data uji Misi 6 lolos, termasuk seluruh jam batas.',
            'syarat' => 'Lolos 7/7 data uji di Misi 6: Bangun Program',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi6->id],
        ]);

        Lencana::create([
            'kode' => 'pemburu_bug',
            'nama' => 'Pemburu Bug',
            'ikon' => '🐛',
            'deskripsi' => 'Diberikan apabila keempat kesalahan pada Misi 8 berhasil ditemukan dan diperbaiki.',
            'syarat' => 'Lolos 3/3 data uji perbaikan di Misi 8',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi8->id],
        ]);

        Lencana::create([
            'kode' => 'ahli_alternatif',
            'nama' => 'Ahli Alternatif',
            'ikon' => '🔀',
            'deskripsi' => 'Diberikan setelah menyelesaikan Misi Bonus switch-case. Tidak dikunci — semua siswa berpeluang memperolehnya.',
            'syarat' => 'Selesaikan Misi Bonus: Struktur Alternatif',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $bonus->id],
        ]);

        $this->command->info('Seeder selesai: Pertemuan 1 LENGKAP.');
    }

    private function seedPertemuan2(): void
    {
        $p2 = Pertemuan::create([
            'urutan' => 2,
            'judul' => 'Perulangan',
            'deskripsi' => 'Struktur kontrol perulangan: anatomi (inisialisasi, kondisi berhenti, perubahan nilai), sintaks for/while/do-while, dan penerapannya untuk mendata kehadiran sejumlah siswa.',
            'tujuan_pembelajaran' => "X.PD.2.1 Mengidentifikasi pengertian perulangan dan simbol pengendali alur pada flowchart\nX.PD.2.2 Mengidentifikasi inisialisasi, kondisi berhenti, dan perubahan nilai\nX.PD.2.3 Mengenali bentuk umum sintaks for, while, dan do-while\nX.PD.2.4 Menjelaskan alur eksekusi dan menentukan banyaknya pengulangan\nX.PD.2.5 Menerapkan perulangan untuk pendataan kehadiran sejumlah siswa\nX.PD.2.6 Menganalisis kesalahan sintaks dan logika serta memperbaikinya\nX.PD.2.7 Membandingkan ketiga bentuk perulangan dan menentukan yang tepat",
            'aktif' => true,
        ]);

        // ================= MISI 1 — Orientasi Masalah (F.1, 10 menit) =================
        $misi1 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'pra_kelas',
            'urutan' => 1,
            'judul' => 'Misi 1: Orientasi Masalah',
            'estimasi_menit' => 10,
            'poin_maksimal' => 25,
            'deskripsi' => 'Mengalami sendiri keterbatasan program yang hanya bisa memproses satu siswa sekali jalan.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Layar 1 — Kartu Tugas Lanjutan',
            'konten_html' => <<<'HTML'
<p><strong>PERMINTAAN PENGEMBANGAN LANJUTAN</strong></p>
<p>Dari: Wakil Kepala Sekolah Bidang Kesiswaan<br>Untuk: Tim Pengembang Kelas X PPLG</p>
<p>Program yang kalian buat pada pertemuan lalu sudah berjalan dengan baik. Sayangnya, program itu hanya mampu memproses satu orang siswa dalam satu kali jalan. Untuk mendata satu kelas berisi 36 siswa, program harus dijalankan sebanyak 36 kali.</p>
<p>Kami membutuhkan program yang mampu mendata kehadiran sejumlah siswa secara berurutan, cukup dalam satu kali jalan program.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Layar 2-3 — Simulasi Penulisan Berulang',
            'komponen_koding' => 'simulasi-penulisan-berulang',
            'konfigurasi_koding' => [],
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Kegiatan apa saja dalam kehidupan sehari-hari yang dilakukan dengan langkah yang sama secara berulang?'
        ]);
        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 4,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bagaimana cara menyelesaikan pekerjaan yang sama dalam jumlah banyak agar lebih efisien daripada melakukannya satu per satu?'
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 5,
            'tipe' => 'teks_web',
            'judul' => 'Layar 5 — Pengenalan Konsep',
            'konten_html' => <<<'HTML'
<p>Menyalin perintah yang sama berkali-kali bukan cara yang tepat. Selain melelahkan, programnya menjadi kaku karena banyaknya pengulangan sudah ditentukan sejak kode ditulis.</p>
<p>Dalam pemrograman, kemampuan program untuk menjalankan sekelompok perintah berkali-kali selama kondisi tertentu masih terpenuhi disebut <strong>perulangan</strong>.</p>
<p>Dengan perulangan, perintahnya cukup ditulis satu kali. Program sendiri yang akan mengulangnya sebanyak yang dibutuhkan, dan banyaknya pengulangan bisa ditentukan saat program dijalankan.</p>
<p>Pada flowchart, perulangan digambarkan dengan simbol decision yang salah satu jalurnya kembali ke perintah sebelumnya. Jalur yang kembali inilah yang membuat perintah dijalankan lagi.</p>
HTML,
        ]);

        $kuis1 = KontenMisi::create(['misi_id' => $misi1->id, 'urutan' => 6, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 1']);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 1,
            'pertanyaan' => 'Apa keuntungan utama memakai perulangan dibanding menyalin perintah berkali-kali?',
            'pilihan' => ['a' => 'Program berjalan lebih cepat', 'b' => 'Banyaknya pengulangan bisa ditentukan saat program dijalankan', 'c' => 'Kode terlihat lebih rapi saja', 'd' => 'Tidak perlu variabel'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 2,
            'pertanyaan' => 'Apa ciri khas flowchart perulangan?',
            'pilihan' => ['a' => 'Ada dua simbol decision', 'b' => 'Ada jalur yang kembali ke perintah sebelumnya', 'c' => 'Tidak ada simbol decision', 'd' => 'Alurnya lurus ke bawah'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 3,
            'pertanyaan' => 'Perulangan menjalankan sekelompok perintah berkali-kali selama...',
            'pilihan' => ['a' => 'Program belum berhenti', 'b' => 'Kondisi tertentu masih terpenuhi', 'c' => 'Pengguna menekan tombol', 'd' => 'Datanya masih ada'],
            'kunci_jawaban' => 'B'
        ]);

        // ================= MISI 2 — Anatomi Perulangan (F.2, 15 menit) =================
        $misi2 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'pra_kelas',
            'urutan' => 2,
            'judul' => 'Misi 2: Anatomi Perulangan',
            'estimasi_menit' => 15,
            'poin_maksimal' => 25,
            'misi_prasyarat_id' => $misi1->id,
            'deskripsi' => 'Tiga bagian penyusun perulangan: inisialisasi, kondisi berhenti, perubahan nilai.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1 — Tiga Bagian Penyusun',
            'konten_html' => <<<'HTML'
<p>Setiap perulangan, apa pun bentuk penulisannya, selalu memiliki tiga bagian penyusun.</p>
<table border="1" cellpadding="6">
<tr><th>Bagian</th><th>Kegunaannya</th></tr>
<tr><td>Inisialisasi</td><td>Memberi nilai awal pada variabel pencacah. Dijalankan satu kali sebelum pengulangan dimulai</td></tr>
<tr><td>Kondisi berhenti</td><td>Diperiksa pada setiap pengulangan. Selama bernilai benar, pengulangan terus berlanjut</td></tr>
<tr><td>Perubahan nilai</td><td>Mengubah nilai pencacah pada setiap pengulangan, agar kondisi suatu saat bernilai salah</td></tr>
</table>
<p>Ketiganya harus ada. Apabila salah satu hilang atau keliru, perulangan bisa berjalan tanpa henti, atau justru tidak berjalan sama sekali.</p>
<p>Variabel pencacah adalah variabel yang nilainya dipakai untuk mengendalikan banyaknya pengulangan. Biasanya diberi nama i, dan nilainya bertambah satu pada setiap pengulangan.</p>
HTML,
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Bagian 2 — Penelusuran Otomatis',
            'komponen_koding' => 'penelusuran-loop',
            'konfigurasi_koding' => [],
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 3,
            'tipe' => 'koding',
            'judul' => 'Bagian 3 — Latihan Pengenalan',
            'komponen_koding' => 'latihan-isian-singkat',
            'konfigurasi_koding' => ['soal' => [
                ['pertanyaan' => 'Berapa kali perulangan berjalan bila i = 1, kondisi i <= 10, perubahan i++?', 'kunci' => '10 kali', 'umpan_balik_salah' => 'Hitung dari nilai awal sampai batas, keduanya ikut terhitung.'],
                ['pertanyaan' => 'Berapa kali bila i = 0, kondisi i < 10, perubahan i++?', 'kunci' => '10 kali', 'umpan_balik_salah' => 'Tanda kurang dari tidak mencakup nilai batasnya.'],
                ['pertanyaan' => 'Berapa kali bila i = 0, kondisi i <= 10, perubahan i++?', 'kunci' => '11 kali', 'umpan_balik_salah' => 'Mulai dari nol dan batas ikut terhitung, sehingga jumlahnya bertambah satu.'],
                ['pertanyaan' => 'Berapa kali bila i = 1, kondisi i <= 5, perubahan i += 2?', 'kunci' => '3 kali', 'umpan_balik_salah' => 'Nilai bertambah dua, jadi pencacah melompati sebagian angka.'],
            ]],
        ]);

        $kuis2 = KontenMisi::create(['misi_id' => $misi2->id, 'urutan' => 4, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 2']);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 1,
            'pertanyaan' => 'Bagian manakah yang dijalankan hanya satu kali, sebelum pengulangan dimulai?',
            'pilihan' => ['a' => 'Kondisi berhenti', 'b' => 'Inisialisasi', 'c' => 'Perubahan nilai', 'd' => 'Isi blok'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 2,
            'pertanyaan' => 'Apa yang terjadi apabila perubahan nilai tidak dituliskan?',
            'pilihan' => ['a' => 'Perulangan berjalan sekali', 'b' => 'Perulangan tidak berjalan', 'c' => 'Perulangan tidak pernah berhenti', 'd' => 'Program gagal dikompilasi'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 3,
            'pertanyaan' => 'Perulangan dengan i = 1, kondisi i <= 4, dan perubahan i++ berjalan berapa kali?',
            'pilihan' => ['a' => '3 kali', 'b' => '4 kali', 'c' => '5 kali', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'B'
        ]);

        // ================= MISI 3 — Sintaks Perulangan (F.3, 15 menit) =================
        $misi3 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'pra_kelas',
            'urutan' => 3,
            'judul' => 'Misi 3: Sintaks Perulangan',
            'estimasi_menit' => 15,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi2->id,
            'deskripsi' => 'Mengenali bentuk penulisan for, while, dan do-while.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1a — Bentuk For',
            'konten_html' => <<<'HTML'
<p>Bentuk for menuliskan ketiga bagian penyusun dalam satu baris, dipisahkan tanda titik koma. Bentuk ini dipakai ketika banyaknya pengulangan sudah diketahui sejak awal.</p>
<pre>for (int i = 1; i &lt;= 5; i++) {
    cout &lt;&lt; i &lt;&lt; endl;
}</pre>
<p>Urutan bacanya: mulai dari i bernilai 1, ulangi selama i kurang dari atau sama dengan 5, dan tambahkan satu pada i setiap kali satu pengulangan selesai.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1b — Bentuk While',
            'konten_html' => <<<'HTML'
<p>Bentuk while hanya menuliskan kondisi di dalam kurungnya. Inisialisasi ditulis sebelum perulangan, dan perubahan nilai ditulis di dalam blok.</p>
<pre>int i = 1;
while (i &lt;= 5) {
    cout &lt;&lt; i &lt;&lt; endl;
    i++;
}</pre>
<p>Alurnya sama persis dengan for. Yang berbeda hanya letak penulisan ketiga bagian penyusunnya. Kondisi tetap diperiksa sebelum blok dijalankan, sehingga apabila kondisi sudah bernilai salah sejak awal, blok tidak dijalankan sama sekali. Bentuk ini dipakai ketika banyaknya pengulangan belum diketahui.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 3,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1c — Bentuk Do-While',
            'konten_html' => <<<'HTML'
<p>Bentuk do-while memeriksa kondisi setelah blok dijalankan, bukan sebelumnya.</p>
<pre>int i = 1;
do {
    cout &lt;&lt; i &lt;&lt; endl;
    i++;
} while (i &lt;= 5);</pre>
<p>Akibat dari urutan ini, blok dipastikan dijalankan sekurang-kurangnya satu kali, bahkan ketika kondisinya sudah bernilai salah sejak awal. Sifat inilah yang membedakan do-while dari while, dan sifat ini bisa menguntungkan atau justru merugikan tergantung kasusnya.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 4,
            'tipe' => 'koding',
            'judul' => 'Bagian 2 — Perbandingan While dan Do-While',
            'komponen_koding' => 'perbandingan-while-dowhile',
            'konfigurasi_koding' => [],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 5,
            'tipe' => 'koding',
            'judul' => 'Bagian 3 — Bedah Anatomi Kode For',
            'komponen_koding' => 'bedah-kode',
            'konfigurasi_koding' => [
                'kode' => "for (int i = 1; i <= 5; i++) {\n    cout << i << endl;\n}",
                'bagian' => [
                    ['nama' => 'Inisialisasi', 'potongan' => 'int i = 1', 'keterangan' => 'Nilai awal pencacah. Dijalankan satu kali'],
                    ['nama' => 'Kondisi berhenti', 'potongan' => '; i <= 5', 'keterangan' => 'Diperiksa sebelum setiap pengulangan'],
                    ['nama' => 'Perubahan nilai', 'potongan' => '; i++', 'keterangan' => 'Dijalankan setiap kali satu pengulangan selesai'],
                    ['nama' => 'Isi blok', 'potongan' => ") {\n    cout << i << endl;\n}", 'keterangan' => 'Perintah yang diulang'],
                ],
            ],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 6,
            'tipe' => 'koding',
            'judul' => 'Bagian 4 — Latihan Melengkapi Kode',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => ['butir' => [
                ['kode' => "for (int i = 1; i ___0 5; ___1) {\n    cout << i << endl;\n}", 'jawaban' => ['0' => '<=', '1' => 'i++']],
                ['kode' => "int i = 1; while (i ___0 3) { cout << i; i___1; }", 'jawaban' => ['0' => '<=', '1' => '++']],
                ['kode' => "for (int i = 5; i ___0 1; ___1) {\n    cout << i << endl;\n}", 'jawaban' => ['0' => '>=', '1' => 'i--']],
            ]],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 7,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 5 — Kesalahan yang Paling Sering Terjadi',
            'konten_html' => <<<'HTML'
<table border="1" cellpadding="6">
<tr><th>Kesalahan</th><th>Akibatnya</th></tr>
<tr><td>Perubahan nilai tidak dituliskan</td><td>Kondisi tidak pernah bernilai salah, perulangan tidak berhenti</td></tr>
<tr><td>Arah perubahan nilai keliru</td><td>Nilai menjauhi batas, perulangan tidak berhenti</td></tr>
<tr><td>Titik koma nyasar setelah for atau while</td><td>Blok terlepas dari perulangan, perulangan berjalan tanpa isi</td></tr>
<tr><td>Nilai awal atau tanda pembanding tidak tepat</td><td>Perulangan berjalan satu kali lebih banyak atau lebih sedikit</td></tr>
<tr><td>Kurung kurawal hilang pada blok berisi banyak perintah</td><td>Hanya perintah pertama yang diulang</td></tr>
</table>
<p>Perhatikan bahwa tiga kesalahan pertama membuat program tidak pernah berhenti. Apabila hal ini terjadi saat kamu mengerjakan latihan, media akan menghentikan programnya secara otomatis dan memberi tahu penyebabnya.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 8,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 6 — Ringkasan Tiga Bentuk',
            'konten_html' => <<<'HTML'
<table border="1" cellpadding="6">
<tr><th>Bentuk</th><th>Kondisi diperiksa</th><th>Dipakai ketika</th></tr>
<tr><td>for</td><td>Sebelum blok dijalankan</td><td>Banyaknya pengulangan sudah diketahui sejak awal</td></tr>
<tr><td>while</td><td>Sebelum blok dijalankan</td><td>Banyaknya pengulangan belum diketahui, dan blok boleh tidak berjalan sama sekali</td></tr>
<tr><td>do-while</td><td>Setelah blok dijalankan</td><td>Blok harus dijalankan sekurang-kurangnya satu kali</td></tr>
</table>
HTML
        ]);

        $kuis3 = KontenMisi::create(['misi_id' => $misi3->id, 'urutan' => 9, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 3']);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 1,
            'pertanyaan' => 'Bentuk manakah yang memastikan blok dijalankan sekurang-kurangnya satu kali?',
            'pilihan' => ['a' => 'for', 'b' => 'while', 'c' => 'do-while', 'd' => 'Ketiganya sama'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 2,
            'pertanyaan' => 'Berapa baris yang tercetak oleh for (int i = 0; i <= 3; i++)?',
            'pilihan' => ['a' => '3 baris', 'b' => '4 baris', 'c' => '5 baris', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 3,
            'pertanyaan' => 'Apa akibat titik koma yang tertulis setelah while (i <= 5)?',
            'pilihan' => ['a' => 'Program gagal dikompilasi', 'b' => 'Blok terlepas dari perulangan', 'c' => 'Perulangan berjalan sekali', 'd' => 'Tidak berpengaruh'],
            'kunci_jawaban' => 'B'
        ]);

        // ================= MISI 4 — Uji Kesiapan (F.4, 20 menit) =================
        $misi4 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'pra_kelas',
            'urutan' => 4,
            'judul' => 'Misi 4: Uji Kesiapan',
            'estimasi_menit' => 20,
            'poin_maksimal' => 50,
            'misi_prasyarat_id' => $misi3->id,
            'deskripsi' => 'Gerbang menuju fase tatap muka.',
        ]);

        $kuis4 = KontenMisi::create(['misi_id' => $misi4->id, 'urutan' => 1, 'tipe' => 'kuis', 'judul' => 'Kuis Keseluruhan Pra-Kelas']);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 1,
            'pertanyaan' => 'Simbol pengendali alur pada flowchart perulangan dicirikan oleh...',
            'pilihan' => ['a' => 'Jalur lurus ke bawah', 'b' => 'Jalur yang kembali ke perintah sebelumnya', 'c' => 'Dua simbol decision', 'd' => 'Tidak ada simbol khusus'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 2,
            'pertanyaan' => 'Perulangan memungkinkan sekelompok perintah dijalankan berkali-kali selama...',
            'pilihan' => ['a' => 'Program belum ditutup', 'b' => 'Kondisi tertentu masih terpenuhi', 'c' => 'Ada input baru', 'd' => 'Variabel bernilai nol'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 3,
            'pertanyaan' => 'i = 1, kondisi i <= 8, perubahan i++. Berapa kali perulangan berjalan?',
            'pilihan' => ['a' => '7 kali', 'b' => '8 kali', 'c' => '9 kali', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 4,
            'pertanyaan' => 'i = 0, kondisi i < 8, perubahan i++. Berapa kali perulangan berjalan?',
            'pilihan' => ['a' => '7 kali', 'b' => '8 kali', 'c' => '9 kali', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 5,
            'pertanyaan' => 'i = 10, kondisi i <= 5, perubahan i++. Berapa kali perulangan berjalan?',
            'pilihan' => ['a' => '0 kali', 'b' => '1 kali', 'c' => '5 kali', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'A'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 6,
            'pertanyaan' => 'Apa keluaran dari: for (int i = 1; i <= 3; i++) cout << i;',
            'pilihan' => ['a' => '123', 'b' => '1 2 3', 'c' => '321', 'd' => 'Tidak berhenti'],
            'kunci_jawaban' => 'A'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 7,
            'pertanyaan' => 'Program "int i = 1; while (i <= 5) { cout << i; }" (tanpa i++) akan...',
            'pilihan' => ['a' => 'Berjalan 5 kali lalu berhenti', 'b' => 'Tidak pernah berhenti', 'c' => 'Tidak berjalan sama sekali', 'd' => 'Gagal dikompilasi'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 8,
            'pertanyaan' => 'Program "for (int i = 5; i <= 1; i++)" tidak pernah berhenti — apa penyebabnya?',
            'pilihan' => ['a' => 'Kondisi salah sejak awal jadi tidak pernah berjalan', 'b' => 'Arah perubahan nilai keliru, i menjauhi batas', 'c' => 'Nilai awal tidak dideklarasikan', 'd' => 'Kurung kurawal hilang'],
            'kunci_jawaban' => 'B'
        ]);

        KontenMisi::create([
            'misi_id' => $misi4->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bagian mana yang masih membuatmu bingung dari Misi 1 sampai 3?'
        ]);

        // ================= MISI 5 — Ruang Kelompok (F.6, Tatap Muka, 10 menit) =================
        $misi5 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'tatap_muka',
            'urutan' => 1,
            'judul' => 'Misi 5: Ruang Kelompok',
            'estimasi_menit' => 10,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi4->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Merumuskan tabel anatomi perulangan dan bentuk yang tepat untuk mendata kehadiran sejumlah siswa.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi5->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Form Rumusan Masalah Kelompok',
            'komponen_koding' => 'form-rumusan-masalah',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 6 — Bangun Program (F.7, Tatap Muka, 30 menit) =================
        $misi6 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'tatap_muka',
            'urutan' => 2,
            'judul' => 'Misi 6: Bangun Program',
            'estimasi_menit' => 30,
            'poin_maksimal' => 35,
            'misi_prasyarat_id' => $misi5->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menulis program pendataan kehadiran sejumlah siswa dan mengujinya, termasuk data uji jumlah = 0.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi6->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Editor Kode Kelompok & Tabel Uji',
            'komponen_koding' => 'editor-kode-uji-perulangan',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 7 — Demo Karya (F.8, Tatap Muka, 20 menit) =================
        $misi7 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'tatap_muka',
            'urutan' => 3,
            'judul' => 'Misi 7: Demo Karya',
            'estimasi_menit' => 20,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi6->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Presentasi program kelompok dan rekap bentuk perulangan yang dipilih seluruh kelas.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi7->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Ruang Presentasi dan Tanggapan',
            'komponen_koding' => 'demo-karya',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 8 — Perbaiki Program (F.9, Tatap Muka, 15 menit) =================
        $misi8 = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'tatap_muka',
            'urutan' => 4,
            'judul' => 'Misi 8: Perbaiki Program',
            'estimasi_menit' => 15,
            'poin_maksimal' => 40,
            'misi_prasyarat_id' => $misi7->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menemukan dan memperbaiki 4 kesalahan tersebar di 3 perulangan (while, for, do-while).',
        ]);
        KontenMisi::create([
            'misi_id' => $misi8->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Program Bermasalah & Perbaikan',
            'komponen_koding' => 'perbaikan-program-perulangan',
            'konfigurasi_koding' => []
        ]);

        // ================= REFLEKSI & PENUTUP (F.10, 5 menit) =================
        $refleksi = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'tatap_muka',
            'urutan' => 5,
            'judul' => 'Refleksi dan Penutup',
            'estimasi_menit' => 5,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi8->id,
            'deskripsi' => 'Tiga pertanyaan refleksi penutup sesi tatap muka.',
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 1,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bentuk perulangan mana yang menurutmu paling sering akan kamu pakai, dan mengapa?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Apa yang kamu pelajari dari data uji bernilai nol tadi?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Kesalahan mana yang paling ingin kamu hindari saat menulis program berikutnya?'
        ]);

        // ================= MISI BONUS — Pemilihan dan Konversi Bentuk (F.11, Pasca-Kelas) =================
        $bonus = Misi::create([
            'pertemuan_id' => $p2->id,
            'fase' => 'pasca_kelas',
            'urutan' => 1,
            'judul' => 'Misi Bonus: Pemilihan dan Konversi Bentuk',
            'estimasi_menit' => 15,
            'poin_maksimal' => 20,
            'deskripsi' => 'Terbuka untuk semua peserta didik tanpa syarat, termasuk yang belum tuntas misi sebelumnya.',
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Memilih Bentuk yang Tepat',
            'konten_html' => <<<'HTML'
<p>Ketiga bentuk perulangan bisa saling menggantikan. Yang membedakan bukan kemampuannya, melainkan mana yang paling jelas dibaca untuk kasus yang sedang kamu hadapi.</p>
<p>Di Misi 6 kemarin kamu sudah menemukan sendiri bahwa do-while gagal ketika banyaknya siswa bernilai nol. Sekarang saatnya melihat keadaan sebaliknya, yaitu ketika do-while justru merupakan pilihan yang tepat.</p>
<p>Contohnya, program yang meminta pengguna memasukkan kata sandi. Permintaan itu harus muncul sekurang-kurangnya satu kali, sebelum program bisa memeriksa benar atau salahnya.</p>
<pre>do {
    cout &lt;&lt; "Masukkan kata sandi: ";
    cin &gt;&gt; sandi;
} while (sandi != benar);</pre>
<p>Inilah satu-satunya keadaan yang memang menuntut do-while: ketika blok harus dijalankan lebih dahulu sebelum kondisinya bisa diperiksa.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Konversi Antar Bentuk',
            'konten_html' => <<<'HTML'
<p>Program yang ditulis dengan for selalu dapat diubah menjadi while, dan sebaliknya. Yang berpindah hanya letak ketiga bagian penyusunnya.</p>
<p><strong>Bentuk for:</strong></p>
<pre>for (int i = 1; i &lt;= 5; i++) {
    cout &lt;&lt; i;
}</pre>
<p><strong>Bentuk while yang setara:</strong></p>
<pre>int i = 1;
while (i &lt;= 5) {
    cout &lt;&lt; i;
    i++;
}</pre>
<p>Perhatikan bahwa i++ berpindah ke baris terakhir di dalam blok. Apabila lupa dipindahkan, perulangan tidak akan pernah berhenti. Inilah kesalahan yang paling sering terjadi saat mengonversi.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 3,
            'tipe' => 'koding',
            'judul' => 'Latihan Konversi Bentuk',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => ['butir' => [
                ['kode' => "for (int n = 10; n ___0 1; n___1) {\n    cout << n << \" \";\n}", 'jawaban' => ['0' => '>=', '1' => '--']],
                ['kode' => "int i = 1;\nwhile (i <= 5) {\n    cout << i;\n    ___0\n}", 'jawaban' => ['0' => 'i++;']],
            ]],
        ]);

        $kuisBonus = KontenMisi::create(['misi_id' => $bonus->id, 'urutan' => 4, 'tipe' => 'kuis', 'judul' => 'Kuis Pemilihan dan Konversi Bentuk']);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 1,
            'pertanyaan' => 'Kasus manakah yang paling tepat memakai do-while?',
            'pilihan' => ['a' => 'Mencetak angka 1 sampai 10', 'b' => 'Meminta kata sandi sampai benar', 'c' => 'Mendata siswa yang jumlahnya diketahui', 'd' => 'Menjumlahkan sepuluh bilangan'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 2,
            'pertanyaan' => 'Saat mengubah for menjadi while, ke mana perubahan nilai dipindahkan?',
            'pilihan' => ['a' => 'Sebelum while', 'b' => 'Ke dalam kurung while', 'c' => 'Ke baris terakhir di dalam blok', 'd' => 'Tidak perlu ditulis lagi'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 3,
            'pertanyaan' => 'Bentuk manakah yang paling tepat ketika banyaknya pengulangan sudah diketahui sejak awal?',
            'pilihan' => ['a' => 'for', 'b' => 'while', 'c' => 'do-while', 'd' => 'Ketiganya sama saja'],
            'kunci_jawaban' => 'A'
        ]);

        // ================= LENCANA =================
        Lencana::create([
            'kode' => 'penemu_pola',
            'nama' => 'Penemu Pola',
            'ikon' => '🔁',
            'deskripsi' => 'Diberikan setelah simulasi penulisan berulang diselesaikan.',
            'syarat' => 'Selesaikan Misi 1: Orientasi Masalah',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi1->id]
        ]);
        Lencana::create([
            'kode' => 'pengatur_pencacah',
            'nama' => 'Pengatur Pencacah',
            'ikon' => '🔢',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 2 tuntas.',
            'syarat' => 'Selesaikan Misi 2: Anatomi Perulangan',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi2->id]
        ]);
        Lencana::create([
            'kode' => 'pengendali_alur',
            'nama' => 'Pengendali Alur',
            'ikon' => '🎛️',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 3 tuntas.',
            'syarat' => 'Selesaikan Misi 3: Sintaks Perulangan',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi3->id]
        ]);
        Lencana::create([
            'kode' => 'siap_bertugas_p2',
            'nama' => 'Siap Bertugas',
            'ikon' => '🎯',
            'deskripsi' => 'Diberikan setelah menyelesaikan seluruh misi pra-kelas Pertemuan 2.',
            'syarat' => 'Selesaikan semua misi pra-kelas Pertemuan 2',
            'kriteria' => ['tipe' => 'selesaikan_fase', 'pertemuan_id' => $p2->id, 'fase' => 'pra_kelas']
        ]);
        Lencana::create([
            'kode' => 'penguji_nol',
            'nama' => 'Penguji Nol',
            'ikon' => '🕳️',
            'deskripsi' => 'Diberikan apabila kelima data uji Misi 6 lolos, termasuk data uji bernilai nol.',
            'syarat' => 'Lolos 5/5 data uji di Misi 6: Bangun Program',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi6->id]
        ]);
        Lencana::create([
            'kode' => 'pemburu_loop',
            'nama' => 'Pemburu Loop',
            'ikon' => '🐛',
            'deskripsi' => 'Diberikan apabila keempat kesalahan pada Misi 8 berhasil ditemukan dan diperbaiki.',
            'syarat' => 'Lolos 2/2 data uji perbaikan di Misi 8',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi8->id]
        ]);
        Lencana::create([
            'kode' => 'ahli_bentuk',
            'nama' => 'Ahli Bentuk',
            'ikon' => '🔀',
            'deskripsi' => 'Diberikan setelah menyelesaikan Misi Bonus konversi bentuk. Tidak dikunci.',
            'syarat' => 'Selesaikan Misi Bonus: Pemilihan dan Konversi Bentuk',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $bonus->id]
        ]);

        $this->command->info('Seeder selesai: Pertemuan 2 LENGKAP.');
    }

    private function seedPertemuan3(): void
    {
        $p3 = Pertemuan::create([
            'urutan' => 3,
            'judul' => 'Kombinasi Percabangan dan Perulangan',
            'deskripsi' => 'Struktur bersarang: percabangan di dalam perulangan, variabel penghitung kategori, dan penerapannya untuk membangun sistem rekap kehadiran lengkap dengan persentase.',
            'tujuan_pembelajaran' => "X.PD.3.1 Mengidentifikasi struktur bersarang dan letak simbol decision di dalam alur perulangan\nX.PD.3.2 Mengidentifikasi peran variabel penghitung kategori dan letak inisialisasinya\nX.PD.3.3 Mengenali penulisan percabangan di dalam perulangan beserta cakupan bloknya\nX.PD.3.4 Menjelaskan alur eksekusi bersarang dan menentukan nilai akhir tiap penghitung\nX.PD.3.5 Menerapkan kombinasi keduanya untuk membangun sistem absensi dengan rekap\nX.PD.3.6 Menganalisis kesalahan pada program bersarang serta memperbaikinya\nX.PD.3.7 Menyempurnakan program dengan pemeriksaan masukan dan penyajian rekap",
            'aktif' => true,
        ]);

        // ================= MISI 1 — Orientasi Masalah (F.1, 10 menit) =================
        $misi1 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'pra_kelas',
            'urutan' => 1,
            'judul' => 'Misi 1: Orientasi Masalah',
            'estimasi_menit' => 10,
            'poin_maksimal' => 25,
            'deskripsi' => 'Merasakan bahwa data yang dibutuhkan sudah ada, tetapi program belum menghitungnya.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Layar 1 — Kartu Tugas Lanjutan',
            'konten_html' => <<<'HTML'
<p><strong>PERMINTAAN PENAMBAHAN REKAP</strong></p>
<p>Dari: Wakil Kepala Sekolah Bidang Kesiswaan<br>Untuk: Tim Pengembang Kelas X PPLG</p>
<p>Program kalian sekarang sudah bisa mendata banyak siswa sekaligus. Terima kasih, ini sangat membantu.</p>
<p>Hanya saja, programnya masih menampilkan data satu per satu. Untuk mengetahui berapa siswa yang hadir, terlambat, dan alpa, guru masih harus menghitungnya sendiri dari daftar keluaran. Kami membutuhkan program yang langsung menampilkan rekapnya beserta persentase kehadiran.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Layar 2-3 — Simulasi Penghitungan Rekap Manual',
            'komponen_koding' => 'simulasi-rekap-manual',
            'konfigurasi_koding' => []
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Kegiatan berulang apa dalam kehidupan sehari-hari yang di dalamnya masih memerlukan keputusan berbeda untuk setiap objek yang diproses?'
        ]);
        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 4,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Apa yang dapat terjadi apabila perhitungan rekap dilakukan sebelum seluruh data selesai diproses?'
        ]);

        KontenMisi::create([
            'misi_id' => $misi1->id,
            'urutan' => 5,
            'tipe' => 'teks_web',
            'judul' => 'Layar 5 — Pengenalan Konsep',
            'konten_html' => <<<'HTML'
<p>Untuk menghitung rekap, program harus melakukan dua hal sekaligus. Pertama, mengulang pembacaan data untuk setiap siswa. Kedua, mengambil keputusan status untuk masing-masing siswa itu.</p>
<p>Menempatkan satu struktur kontrol di dalam struktur kontrol yang lain disebut <strong>struktur bersarang</strong>. Pada pertemuan ini, yang kita pakai adalah percabangan yang ditempatkan di dalam perulangan.</p>
<p>Artinya, pada setiap pengulangan, program memeriksa kembali kondisi dari awal untuk data yang sedang diproses. Keputusan diambil ulang untuk setiap siswa, bukan sekali untuk semuanya.</p>
<p>Pada flowchart, hal ini terlihat sebagai simbol decision yang berada di dalam alur perulangan.</p>
HTML
        ]);

        $kuis1 = KontenMisi::create(['misi_id' => $misi1->id, 'urutan' => 6, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 1']);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 1,
            'pertanyaan' => 'Apa yang dimaksud struktur bersarang?',
            'pilihan' => ['a' => 'Dua program dijalankan bersamaan', 'b' => 'Satu struktur kontrol berada di dalam struktur kontrol lain', 'c' => 'Dua perulangan berurutan', 'd' => 'Percabangan dengan banyak kondisi'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 2,
            'pertanyaan' => 'Pada percabangan di dalam perulangan, kapan kondisi diperiksa?',
            'pilihan' => ['a' => 'Sekali saja di awal', 'b' => 'Sekali saja di akhir', 'c' => 'Pada setiap pengulangan', 'd' => 'Hanya ketika data berubah'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis1->id,
            'urutan' => 3,
            'pertanyaan' => 'Di mana letak simbol decision pada flowchart struktur bersarang?',
            'pilihan' => ['a' => 'Sebelum perulangan', 'b' => 'Sesudah perulangan', 'c' => 'Di dalam alur perulangan', 'd' => 'Di luar flowchart'],
            'kunci_jawaban' => 'C'
        ]);

        // ================= MISI 2 — Variabel Penghitung Kategori (F.2, 15 menit) =================
        $misi2 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'pra_kelas',
            'urutan' => 2,
            'judul' => 'Misi 2: Variabel Penghitung Kategori',
            'estimasi_menit' => 15,
            'poin_maksimal' => 25,
            'misi_prasyarat_id' => $misi1->id,
            'deskripsi' => 'Misi paling menentukan pada Pertemuan 3 — letak inisialisasi variabel penghitung.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1 — Dua Jenis Variabel yang Berbeda',
            'konten_html' => <<<'HTML'
<p>Pada program rekap ini ada dua jenis variabel yang perlu dibedakan, dan keduanya sama-sama bertambah nilainya.</p>
<table border="1" cellpadding="6">
<tr><th></th><th>Variabel pencacah perulangan</th><th>Variabel penghitung kategori</th></tr>
<tr><td>Kegunaannya</td><td>Mengendalikan banyaknya pengulangan</td><td>Menghitung banyaknya data pada tiap kelompok</td></tr>
<tr><td>Letak penulisan</td><td>Pada bagian awal perulangan</td><td>Sebelum perulangan dimulai</td></tr>
<tr><td>Kapan bertambah</td><td>Setiap satu pengulangan selesai</td><td>Hanya ketika cabang yang sesuai dijalankan</td></tr>
<tr><td>Contoh</td><td>i</td><td>hadir, terlambat, alpa</td></tr>
</table>
<p>Untuk merekap tiga status kehadiran, dibutuhkan tiga variabel penghitung, satu untuk setiap status.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 2 — Letak Inisialisasi Menentukan Segalanya',
            'konten_html' => <<<'HTML'
<p>Variabel penghitung harus diberi nilai awal sebelum perulangan dimulai, bukan di dalamnya.</p>
<p>Alasannya sederhana. Apabila baris pemberian nilai awal ditulis di dalam perulangan, baris itu ikut dijalankan berulang kali. Setiap kali pengulangan dimulai, nilainya kembali ke nol, sehingga hitungan yang sudah terkumpul hilang.</p>
<p><strong>Keliru:</strong></p>
<pre>for (int i = 1; i <= jumlah; i++) {
    int hadir = 0;
    ...
}</pre>
<p><strong>Benar:</strong></p>
<pre>int hadir = 0;
for (int i = 1; i <= jumlah; i++) {
    ...
}</pre>
<p>Kedua program di atas sama-sama benar secara penulisan dan sama-sama berjalan tanpa pesan galat. Perbedaannya hanya terlihat dari hasil akhirnya.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 3,
            'tipe' => 'koding',
            'judul' => 'Bagian 3 — Peragaan Berdampingan',
            'komponen_koding' => 'peragaan-berdampingan',
            'konfigurasi_koding' => []
        ]);

        KontenMisi::create([
            'misi_id' => $misi2->id,
            'urutan' => 4,
            'tipe' => 'koding',
            'judul' => 'Bagian 4 — Latihan Pengenalan',
            'komponen_koding' => 'latihan-isian-singkat',
            'konfigurasi_koding' => ['soal' => [
                ['pertanyaan' => 'Di mana variabel hadir seharusnya diberi nilai awal?', 'kunci' => 'Sebelum perulangan', 'umpan_balik_salah' => 'Di dalam perulangan, nilainya akan kembali nol pada setiap pengulangan.'],
                ['pertanyaan' => 'Berapa nilai akhir hadir bila inisialisasinya di dalam perulangan dan data terakhir berstatus Alpa?', 'kunci' => '0', 'umpan_balik_salah' => 'Nilai kembali nol di awal pengulangan terakhir, dan cabang hadir tidak dijalankan.'],
                ['pertanyaan' => 'Berapa banyak variabel penghitung yang dibutuhkan untuk tiga status?', 'kunci' => '3', 'umpan_balik_salah' => 'Satu variabel untuk setiap kelompok yang ingin dihitung.'],
            ]],
        ]);

        $kuis2 = KontenMisi::create(['misi_id' => $misi2->id, 'urutan' => 5, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 2']);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 1,
            'pertanyaan' => 'Kapan variabel penghitung kategori bertambah nilainya?',
            'pilihan' => ['a' => 'Setiap pengulangan', 'b' => 'Ketika cabang yang sesuai dijalankan', 'c' => 'Di akhir program', 'd' => 'Ketika perulangan berhenti'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 2,
            'pertanyaan' => 'Apa akibat inisialisasi variabel penghitung diletakkan di dalam perulangan?',
            'pilihan' => ['a' => 'Program gagal dikompilasi', 'b' => 'Perulangan tidak berhenti', 'c' => 'Nilainya kembali nol pada setiap pengulangan', 'd' => 'Tidak berpengaruh'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis2->id,
            'urutan' => 3,
            'pertanyaan' => 'Manakah yang termasuk variabel pencacah perulangan?',
            'pilihan' => ['a' => 'hadir', 'b' => 'terlambat', 'c' => 'i', 'd' => 'jumlah'],
            'kunci_jawaban' => 'C'
        ]);

        // ================= MISI 3 — Struktur Bersarang (F.3, 15 menit) =================
        $misi3 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'pra_kelas',
            'urutan' => 3,
            'judul' => 'Misi 3: Struktur Bersarang',
            'estimasi_menit' => 15,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi2->id,
            'deskripsi' => 'Bentuk penulisan lengkap dan cakupan blok percabangan di dalam perulangan.',
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 1 — Bentuk Penulisan Lengkap',
            'konten_html' => <<<'HTML'
<p>Berikut bentuk lengkap percabangan yang ditempatkan di dalam perulangan.</p>
<pre>int hadir = 0, terlambat = 0, alpa = 0;

for (int i = 1; i <= jumlah; i++) {
    cin >> jam;

    if (jam <= 700) {
        hadir++;
    } else if (jam <= 730) {
        terlambat++;
    } else {
        alpa++;
    }
}</pre>
<p>Perhatikan lekukan penulisannya. Baris yang menjorok lebih jauh berarti berada di dalam blok yang lebih dalam. Lekukan ini bukan sekadar kerapian, melainkan cara paling cepat untuk membaca sampai di mana sebuah blok berlaku.</p>
<p>Pada setiap pengulangan, hanya satu dari tiga cabang yang dijalankan, dan pemeriksaan selalu dimulai kembali dari kondisi pertama.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 2,
            'tipe' => 'koding',
            'judul' => 'Bagian 2 — Penyorotan Cakupan Blok',
            'komponen_koding' => 'penyorotan-cakupan-blok',
            'konfigurasi_koding' => [
                'baris' => [
                    ['teks' => 'int hadir = 0, terlambat = 0, alpa = 0;', 'indentasi' => 0, 'blok_luar' => null, 'blok_dalam' => null],
                    ['teks' => 'for (int i = 1; i <= jumlah; i++) {', 'indentasi' => 0, 'blok_luar' => 'perulangan', 'blok_dalam' => null],
                    ['teks' => 'cin >> jam;', 'indentasi' => 1, 'blok_luar' => 'perulangan', 'blok_dalam' => null],
                    ['teks' => 'if (jam <= 700) {', 'indentasi' => 1, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_hadir'],
                    ['teks' => 'hadir++;', 'indentasi' => 2, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_hadir'],
                    ['teks' => '} else if (jam <= 730) {', 'indentasi' => 1, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_terlambat'],
                    ['teks' => 'terlambat++;', 'indentasi' => 2, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_terlambat'],
                    ['teks' => '} else {', 'indentasi' => 1, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_alpa'],
                    ['teks' => 'alpa++;', 'indentasi' => 2, 'blok_luar' => 'perulangan', 'blok_dalam' => 'cabang_alpa'],
                    ['teks' => '}', 'indentasi' => 1, 'blok_luar' => 'perulangan', 'blok_dalam' => null],
                    ['teks' => '}', 'indentasi' => 0, 'blok_luar' => 'perulangan', 'blok_dalam' => null],
                ],
                'keterangan_blok' => [
                    'perulangan' => 'dijalankan sebanyak nilai jumlah kali',
                    'cabang_hadir' => 'dijalankan hanya pada pengulangan ketika jam <= 700',
                    'cabang_terlambat' => 'dijalankan hanya pada pengulangan ketika jam antara 701 sampai 730',
                    'cabang_alpa' => 'dijalankan hanya pada pengulangan ketika jam > 730',
                ],
            ],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 3,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 3 — Menghitung Rekap dan Persentase',
            'konten_html' => <<<'HTML'
<p>Setelah seluruh pengulangan selesai, barulah rekap ditampilkan dan persentase dihitung.</p>
<pre>cout << "Hadir: " << hadir << endl;
cout << "Terlambat: " << terlambat << endl;
cout << "Alpa: " << alpa << endl;</pre>
<p>Ada dua hal yang perlu diwaspadai saat menghitung persentase.</p>
<p><strong>Pertama, pembagian antar bilangan bulat.</strong> Apabila kamu menulis hadir dibagi jumlah, sedangkan keduanya bertipe bilangan bulat, maka 1 dibagi 3 akan menghasilkan 0, bukan 0,33. Hasilnya lalu dikali seratus dan tetap menghasilkan nol. Program berjalan tanpa pesan galat, tetapi persentasenya selalu nol.</p>
<pre>// keliru
hadir / jumlah * 100

// benar, kalikan lebih dahulu
hadir * 100 / jumlah</pre>
<p><strong>Kedua, pembagian dengan nol.</strong> Apabila banyaknya siswa yang dimasukkan bernilai nol, perhitungan persentase menjadi tidak sah. Karena itu perhitungannya perlu dilindungi percabangan.</p>
<pre>if (jumlah > 0) {
    cout << hadir * 100 / jumlah << "%";
} else {
    cout << "Tidak ada data untuk dihitung";
}</pre>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 4,
            'tipe' => 'koding',
            'judul' => 'Bagian 4 — Latihan Melengkapi Kode',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => ['butir' => [
                ['kode' => "___0\nfor (int i = 1; i <= 3; i++) {\n    ___1\n    cin >> nilai;\n    if (nilai >= 75) lulus++;\n}", 'jawaban' => ['0' => 'int lulus = 0;', '1' => '']],
                ['kode' => "cout << lulus ___0 total ___1 100;", 'jawaban' => ['0' => '*', '1' => '/']],
                ['kode' => "if (total ___0 0) {\n    cout << lulus * 100 / total;\n}", 'jawaban' => ['0' => '>']],
            ]],
        ]);

        KontenMisi::create([
            'misi_id' => $misi3->id,
            'urutan' => 5,
            'tipe' => 'teks_web',
            'judul' => 'Bagian 6 — Peta Tiga Zona Program',
            'konten_html' => <<<'HTML'
<p>Setiap program rekap kombinasi selalu terbagi menjadi tiga zona:</p>
<table border="1" cellpadding="6">
<tr><th>Zona</th><th>Isinya</th><th>Berjalan berapa kali</th></tr>
<tr><td>Sebelum perulangan</td><td>Inisialisasi variabel penghitung</td><td>Satu kali</td></tr>
<tr><td>Di dalam perulangan</td><td>Pembacaan data dan percabangan penentu kategori</td><td>Sebanyak nilai jumlah</td></tr>
<tr><td>Setelah perulangan</td><td>Pencetakan rekap dan perhitungan persentase</td><td>Satu kali</td></tr>
</table>
<p>Tiga kesalahan tersering pada materi ini semuanya berupa perintah yang diletakkan di zona yang salah.</p>
HTML
        ]);

        $kuis3 = KontenMisi::create(['misi_id' => $misi3->id, 'urutan' => 6, 'tipe' => 'kuis', 'judul' => 'Kuis Penutup Misi 3']);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 1,
            'pertanyaan' => 'Di mana perhitungan persentase seharusnya diletakkan?',
            'pilihan' => ['a' => 'Sebelum perulangan', 'b' => 'Di dalam perulangan', 'c' => 'Setelah perulangan selesai', 'd' => 'Di dalam percabangan'],
            'kunci_jawaban' => 'C'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 2,
            'pertanyaan' => 'Mengapa hadir dibagi jumlah dikali seratus menghasilkan nol?',
            'pilihan' => ['a' => 'Nilai hadir memang nol', 'b' => 'Pembagian antar bilangan bulat dibulatkan ke bawah', 'c' => 'Urutan perhitungan salah', 'd' => 'Program gagal dikompilasi'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis3->id,
            'urutan' => 3,
            'pertanyaan' => 'Apa yang harus dilakukan ketika banyaknya data bernilai nol?',
            'pilihan' => ['a' => 'Tetap menghitung persentase', 'b' => 'Menghentikan program', 'c' => 'Melindungi perhitungan dengan percabangan', 'd' => 'Mengubah nilai menjadi satu'],
            'kunci_jawaban' => 'C'
        ]);

        // ================= MISI 4 — Uji Kesiapan (F.4, 20 menit) =================
        $misi4 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'pra_kelas',
            'urutan' => 4,
            'judul' => 'Misi 4: Uji Kesiapan',
            'estimasi_menit' => 20,
            'poin_maksimal' => 50,
            'misi_prasyarat_id' => $misi3->id,
            'deskripsi' => 'Gerbang menuju fase tatap muka, sekaligus penutup rangkaian pra-kelas tiga pertemuan.',
        ]);

        $kuis4 = KontenMisi::create(['misi_id' => $misi4->id, 'urutan' => 1, 'tipe' => 'kuis', 'judul' => 'Kuis Keseluruhan Pra-Kelas']);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 1,
            'pertanyaan' => 'Ciri khas struktur bersarang pada flowchart adalah...',
            'pilihan' => ['a' => 'Dua flowchart terpisah', 'b' => 'Simbol decision berada di dalam alur perulangan', 'c' => 'Tidak ada simbol decision', 'd' => 'Alur lurus tanpa cabang'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 2,
            'pertanyaan' => 'Program: int h=0; for(i=1;i<=3;i++){ if(i%2==0) h++; }. Berapa nilai akhir h?',
            'pilihan' => ['a' => '0', 'b' => '1', 'c' => '2', 'd' => '3'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 3,
            'pertanyaan' => 'Program: int h=0; for(i=1;i<=4;i++){ if(i<=2) h++; }. Berapa nilai akhir h?',
            'pilihan' => ['a' => '1', 'b' => '2', 'c' => '3', 'd' => '4'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 4,
            'pertanyaan' => 'Jika inisialisasi "int h=0;" diletakkan di dalam perulangan, apa nilai akhir h setelah 5 pengulangan (semua kondisi benar)?',
            'pilihan' => ['a' => '0', 'b' => '1', 'c' => '5', 'd' => 'Tidak dapat ditentukan'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 5,
            'pertanyaan' => 'Program A menginisialisasi hadir=0 di luar perulangan, program B di dalam. Manakah yang menghasilkan rekap benar?',
            'pilihan' => ['a' => 'Program A', 'b' => 'Program B', 'c' => 'Keduanya benar', 'd' => 'Keduanya salah'],
            'kunci_jawaban' => 'A'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 6,
            'pertanyaan' => 'Kedua program pada soal sebelumnya sama-sama berjalan tanpa pesan galat. Mengapa?',
            'pilihan' => ['a' => 'Karena compiler tidak memeriksa logika program', 'b' => 'Karena keduanya sebenarnya identik', 'c' => 'Karena kesalahannya ada di sintaks', 'd' => 'Karena program B tidak valid'],
            'kunci_jawaban' => 'A'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 7,
            'pertanyaan' => 'Program mencetak persentase selalu 0 padahal ada siswa hadir. Apa penyebab paling mungkin?',
            'pilihan' => ['a' => 'Variabel hadir tidak pernah bertambah', 'b' => 'hadir dibagi jumlah dihitung sebelum dikali 100', 'c' => 'Perulangan tidak berjalan', 'd' => 'jumlah bernilai negatif'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuis4->id,
            'urutan' => 8,
            'pertanyaan' => 'Program menghitung persentase langsung tanpa memeriksa jumlah > 0. Apa risikonya ketika jumlah = 0?',
            'pilihan' => ['a' => 'Program menjadi lebih cepat', 'b' => 'Perhitungan persentase menjadi tidak sah (pembagian dengan nol)', 'c' => 'Tidak ada risiko', 'd' => 'Program otomatis memperbaikinya'],
            'kunci_jawaban' => 'B'
        ]);

        KontenMisi::create([
            'misi_id' => $misi4->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Bagian mana yang masih membuatmu bingung dari Misi 1 sampai 3?'
        ]);

        // ================= MISI 5 — Ruang Kelompok (F.6, Tatap Muka, 10 menit) =================
        $misi5 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'tatap_muka',
            'urutan' => 1,
            'judul' => 'Misi 5: Ruang Kelompok',
            'estimasi_menit' => 10,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi4->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Merumuskan tabel perencanaan variabel penghitung dan letak perhitungan persentase.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi5->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Form Rumusan Masalah Kelompok',
            'komponen_koding' => 'form-rumusan-masalah',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 6 — Bangun Program (F.7, Tatap Muka, 30 menit) =================
        $misi6 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'tatap_muka',
            'urutan' => 2,
            'judul' => 'Misi 6: Bangun Program',
            'estimasi_menit' => 30,
            'poin_maksimal' => 20,
            'misi_prasyarat_id' => $misi5->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menulis program rekap kehadiran lengkap, diuji terhadap 4 data dengan rekap dan persentase dicek terpisah.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi6->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Editor Kode Kelompok & Tabel Uji',
            'komponen_koding' => 'editor-kode-uji-kombinasi',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 7 — Demo Karya (F.8, Tatap Muka, 20 menit) =================
        $misi7 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'tatap_muka',
            'urutan' => 3,
            'judul' => 'Misi 7: Demo Karya',
            'estimasi_menit' => 20,
            'poin_maksimal' => 10,
            'misi_prasyarat_id' => $misi6->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Presentasi program kelompok, menyandingkan rencana variabel dengan program jadinya.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi7->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Ruang Presentasi dan Tanggapan',
            'komponen_koding' => 'demo-karya',
            'konfigurasi_koding' => []
        ]);

        // ================= MISI 8 — Perbaiki Program (F.9, Tatap Muka, 15 menit) =================
        $misi8 = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'tatap_muka',
            'urutan' => 4,
            'judul' => 'Misi 8: Perbaiki Program',
            'estimasi_menit' => 15,
            'poin_maksimal' => 40,
            'misi_prasyarat_id' => $misi7->id,
            'wajib_kerja_kelompok' => true,
            'deskripsi' => 'Menemukan dan memperbaiki 4 kesalahan senyap (tanpa pesan galat) pada program rekap.',
        ]);
        KontenMisi::create([
            'misi_id' => $misi8->id,
            'urutan' => 1,
            'tipe' => 'koding',
            'judul' => 'Program Bermasalah & Perbaikan',
            'komponen_koding' => 'perbaikan-program-kombinasi',
            'konfigurasi_koding' => []
        ]);

        // ================= REFLEKSI & PENUTUP (F.10, 5 menit) =================
        $refleksi = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'tatap_muka',
            'urutan' => 5,
            'judul' => 'Refleksi dan Penutup',
            'estimasi_menit' => 5,
            'poin_maksimal' => 15,
            'misi_prasyarat_id' => $misi8->id,
            'deskripsi' => 'Refleksi penutup seluruh rangkaian tiga pertemuan.',
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 1,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Program yang kamu bangun sejak pertemuan pertama sekarang sudah lengkap. Bagian mana yang paling berkesan bagimu?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 2,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Kesalahan yang tidak menampilkan pesan galat ternyata paling sulit ditemukan. Bagaimana caramu memeriksanya lain kali?'
        ]);
        KontenMisi::create([
            'misi_id' => $refleksi->id,
            'urutan' => 3,
            'tipe' => 'refleksi',
            'pertanyaan_refleksi' => 'Materi mana dari ketiga pertemuan yang masih ingin kamu pelajari lagi sebelum post test?'
        ]);

        // ================= MISI BONUS — Penyempurnaan Program (F.11, Pasca-Kelas) =================
        $bonus = Misi::create([
            'pertemuan_id' => $p3->id,
            'fase' => 'pasca_kelas',
            'urutan' => 1,
            'judul' => 'Misi Bonus: Penyempurnaan Program',
            'estimasi_menit' => 15,
            'poin_maksimal' => 20,
            'deskripsi' => 'Terbuka untuk semua peserta didik tanpa syarat — pengayaan sekaligus persiapan post test.',
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 1,
            'tipe' => 'teks_web',
            'judul' => 'Memeriksa Kewajaran Masukan',
            'konten_html' => <<<'HTML'
<p>Program kalian sudah bekerja dengan benar. Sekarang saatnya membuatnya lebih layak dipakai orang lain.</p>
<p>Saat ini program menerima nilai jam apa pun, termasuk 9999 atau bilangan negatif. Padahal jam kedatangan yang wajar hanya berkisar antara 0 sampai 2359.</p>
<pre>if (jam < 0 || jam > 2359) {
    cout << "Jam tidak wajar, data dilewati";
} else if (jam <= 700) {
    hadir++;
} else if (jam <= 730) {
    terlambat++;
} else {
    alpa++;
}</pre>
<p>Perhatikan letak pemeriksaannya, yaitu di kondisi paling pertama. Apabila diletakkan di belakang, data yang tidak wajar sudah terlanjur masuk ke salah satu kelompok.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 2,
            'tipe' => 'teks_web',
            'judul' => 'Menyajikan Rekap yang Mudah Dibaca',
            'konten_html' => <<<'HTML'
<p>Program yang benar belum tentu nyaman dipakai. Bandingkan dua cara menampilkan rekap berikut.</p>
<p><strong>Cara pertama:</strong></p>
<pre>4
4
4</pre>
<p><strong>Cara kedua:</strong></p>
<pre>==== REKAP KEHADIRAN ====
Hadir     : 4 siswa
Terlambat : 4 siswa
Alpa      : 4 siswa
Kehadiran : 33%</pre>
<p>Keduanya menghasilkan angka yang sama persis. Yang berbeda hanya apakah guru bisa langsung membacanya tanpa bertanya. Inilah yang membedakan program yang benar dengan program yang layak dipakai.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 3,
            'tipe' => 'teks_web',
            'judul' => 'Sekilas Perulangan Bersarang (Pengayaan)',
            'konten_html' => <<<'HTML'
<p><em>Bagian ini tidak diujikan. Hanya untuk kamu yang ingin melangkah lebih jauh.</em></p>
<p>Untuk merekap kehadiran selama beberapa hari, dibutuhkan perulangan di dalam perulangan.</p>
<pre>for (int hari = 1; hari <= 5; hari++) {
    for (int i = 1; i <= jumlah; i++) {
        cin >> jam;
        ...
    }
}</pre>
<p>Perulangan di dalam berjalan sampai tuntas untuk setiap satu pengulangan perulangan luar. Jadi apabila ada 5 hari dan 36 siswa, blok terdalam dijalankan sebanyak 180 kali.</p>
HTML
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 4,
            'tipe' => 'koding',
            'judul' => 'Latihan Pemeriksaan Kewajaran & Perulangan Bersarang',
            'komponen_koding' => 'latihan-melengkapi',
            'konfigurasi_koding' => ['butir' => [
                ['kode' => "if (___0) {\n    cout << \"Jam tidak wajar\";\n} else if (jam <= 700) {\n    hadir++;\n}", 'jawaban' => ['0' => 'jam < 0 || jam > 2359']],
            ]],
        ]);

        KontenMisi::create([
            'misi_id' => $bonus->id,
            'urutan' => 5,
            'tipe' => 'koding',
            'judul' => 'Latihan Isian: Perulangan Bersarang',
            'komponen_koding' => 'latihan-isian-singkat',
            'konfigurasi_koding' => ['soal' => [
                ['pertanyaan' => 'Berapa kali baris cin dijalankan? for(a=1;a<=3;a++) for(b=1;b<=4;b++) cin >> x;', 'kunci' => '12 kali', 'umpan_balik_salah' => 'Kalikan banyaknya pengulangan perulangan luar dengan perulangan dalam.'],
            ]],
        ]);

        $kuisBonus = KontenMisi::create(['misi_id' => $bonus->id, 'urutan' => 6, 'tipe' => 'kuis', 'judul' => 'Kuis Penyempurnaan Program']);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 1,
            'pertanyaan' => 'Di mana pemeriksaan kewajaran masukan sebaiknya diletakkan?',
            'pilihan' => ['a' => 'Pada kondisi paling pertama', 'b' => 'Pada kondisi terakhir', 'c' => 'Setelah perulangan', 'd' => 'Sebelum perulangan'],
            'kunci_jawaban' => 'A'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 2,
            'pertanyaan' => 'Pada perulangan bersarang 4 kali di luar dan 5 kali di dalam, berapa kali blok terdalam dijalankan?',
            'pilihan' => ['a' => '9 kali', 'b' => '20 kali', 'c' => '5 kali', 'd' => '4 kali'],
            'kunci_jawaban' => 'B'
        ]);
        KuisSoal::create([
            'konten_misi_id' => $kuisBonus->id,
            'urutan' => 3,
            'pertanyaan' => 'Apa yang membedakan program yang benar dengan program yang layak dipakai?',
            'pilihan' => ['a' => 'Kecepatan programnya', 'b' => 'Panjang kodenya', 'c' => 'Kemudahan keluarannya dibaca pengguna', 'd' => 'Banyaknya variabel'],
            'kunci_jawaban' => 'C'
        ]);

        // ================= LENCANA =================
        Lencana::create([
            'kode' => 'penemu_rekap',
            'nama' => 'Penemu Rekap',
            'ikon' => '📊',
            'deskripsi' => 'Diberikan setelah simulasi penghitungan manual diselesaikan.',
            'syarat' => 'Selesaikan Misi 1: Orientasi Masalah',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi1->id]
        ]);
        Lencana::create([
            'kode' => 'penjaga_nilai',
            'nama' => 'Penjaga Nilai',
            'ikon' => '🛡️',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 2 tuntas.',
            'syarat' => 'Selesaikan Misi 2: Variabel Penghitung Kategori',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi2->id]
        ]);
        Lencana::create([
            'kode' => 'pembaca_cakupan',
            'nama' => 'Pembaca Cakupan',
            'ikon' => '🔍',
            'deskripsi' => 'Diberikan setelah kuis penutup Misi 3 tuntas.',
            'syarat' => 'Selesaikan Misi 3: Struktur Bersarang',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi3->id]
        ]);
        Lencana::create([
            'kode' => 'siap_merekap',
            'nama' => 'Siap Merekap',
            'ikon' => '🎯',
            'deskripsi' => 'Diberikan setelah menyelesaikan seluruh misi pra-kelas Pertemuan 3.',
            'syarat' => 'Selesaikan semua misi pra-kelas Pertemuan 3',
            'kriteria' => ['tipe' => 'selesaikan_fase', 'pertemuan_id' => $p3->id, 'fase' => 'pra_kelas']
        ]);
        Lencana::create([
            'kode' => 'perekap_tepat',
            'nama' => 'Perekap Tepat',
            'ikon' => '✅',
            'deskripsi' => 'Diberikan apabila keempat data uji Misi 6 lolos, termasuk data bernilai nol.',
            'syarat' => 'Lolos 4/4 data uji di Misi 6: Bangun Program',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi6->id]
        ]);
        Lencana::create([
            'kode' => 'pemburu_senyap',
            'nama' => 'Pemburu Senyap',
            'ikon' => '🕵️',
            'deskripsi' => 'Diberikan apabila keempat kesalahan senyap pada Misi 8 berhasil ditemukan.',
            'syarat' => 'Lolos seluruh pemeriksaan perbaikan di Misi 8',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $misi8->id]
        ]);
        Lencana::create([
            'kode' => 'penyempurna',
            'nama' => 'Penyempurna',
            'ikon' => '💎',
            'deskripsi' => 'Diberikan setelah menyelesaikan Misi Bonus penyempurnaan program. Tidak dikunci — sekaligus penutup rangkaian tiga pertemuan.',
            'syarat' => 'Selesaikan Misi Bonus: Penyempurnaan Program',
            'kriteria' => ['tipe' => 'selesaikan_misi', 'misi_id' => $bonus->id]
        ]);

        $this->command->info('Seeder selesai: Pertemuan 3 LENGKAP.');
    }
}
