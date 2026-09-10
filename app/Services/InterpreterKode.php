<?php

namespace App\Services;

/**
 * ============================================================================
 *  INTERPRETER KODE — CATATAN KEAMANAN (v2 — mendukung perulangan)
 * ============================================================================
 * Komponen "Editor Kode" di media ini TIDAK PERNAH menjalankan kode siswa lewat
 * eval(), exec(), shell_exec(), proc_open(), include() dinamis, atau memanggil
 * kompiler sungguhan. Kode siswa adalah input tidak tepercaya dari peramban,
 * jadi kelas ini adalah interpreter buatan sendiri untuk SATU subset bahasa
 * yang sengaja dibuat kecil dan ketat — cukup untuk kasus Pertemuan 1 (percabangan)
 * dan Pertemuan 2 (perulangan), tidak lebih.
 *
 *   Tata bahasa yang DIIZINKAN:
 *     - if (KONDISI) { PERNYATAAN* } [else if (...) {...}]* [else {...}]
 *     - for ([int] VAR = EKSPR; KONDISI; VAR++|VAR--|VAR+=INT|VAR-=INT) { PERNYATAAN* }
 *     - while (KONDISI) { PERNYATAAN* }
 *     - do { PERNYATAAN* } while (KONDISI);
 *     - [int] VAR = EKSPR [, VAR = EKSPR]*;   (pemberian nilai / deklarasi, boleh berganda dipisah koma)
 *     - VAR++; VAR--; VAR += INT; VAR -= INT;   (pernyataan mandiri)
 *     - cin >> VAR;                     (dihitung sebagai satu "permintaan masukan",
 *                                        TIDAK membaca input sungguhan apa pun)
 *     - cout << (STRING|VAR|EKSPR) (<< ...)* ;   (endl diabaikan nilainya, dihitung baris baru)
 *     - KONDISI  ::= EKSPRESI (&&|\|\|) EKSPRESI | ! EKSPRESI | EKSPRESI
 *     - EKSPRESI (kondisi) ::= TERM (==|!=|<=|>=|<|>) TERM
 *     - EKSPR (nilai)      ::= TERM ((+|-|*|/|%) TERM)*      -- berurutan kiri ke kanan, tanpa presedensi
 *     - TERM ::= VAR | BILANGAN_BULAT
 *
 *   Yang TIDAK ADA di tata bahasa ini, sehingga TIDAK MUNGKIN ditulis siswa:
 *     - Tidak ada pemanggilan fungsi apa pun (tidak ada printf, system, fopen, dst)
 *     - Tidak ada operasi file, jaringan, proses, atau kelas/objek
 *     - Tidak ada array, pointer, atau tipe data selain bilangan bulat
 *     - Tidak ada variabel baru selain yang dideklarasikan lewat "int" di kode itu sendiri
 *       atau yang sudah disediakan sistem (mis. "jumlah") — dan jumlah variabel dibatasi
 *
 *   INI YANG PALING PENTING — bagaimana perulangan bisa aman:
 *     Perulangan (for/while/do-while) di dunia nyata BISA tidak pernah berhenti.
 *     Alih-alih melarangnya sama sekali (yang dibutuhkan Pertemuan 2), setiap
 *     pengeksekusian tubuh perulangan menghabiskan satu "langkah" dari anggaran
 *     langkah global (self::LANGKAH_MAKS, lihat konstanta). Begitu anggaran habis,
 *     interpreter BERHENTI SENDIRI dan mengembalikan keterangan yang jelas
 *     ("batas pengulangan terlampaui"), bukan mengeksekusi tanpa akhir. Karena
 *     ini murni penghitung bilangan bulat di dalam proses PHP yang sama (bukan
 *     proses/thread terpisah), tidak ada risiko peramban atau server hang —
 *     paling lama interpreter berhenti sendiri setelah sejumlah langkah tetap.
 *
 *   Batas keamanan tambahan:
 *     - Panjang kode maksimal 3000 karakter
 *     - Kedalaman blok bersarang (if/for/while di dalam if/for/while) maksimal 10 tingkat
 *     - Anggaran langkah global 2000 (lebih dari cukup untuk latihan kelas X, jauh dari
 *       cukup untuk membebani server kalaupun disalahgunakan)
 *     - Setiap token yang tidak dikenali tata bahasa -> ditolak dengan pesan kesalahan
 * ============================================================================
 */
class InterpreterKode
{
    private const PANJANG_MAKS = 3000;
    private const KEDALAMAN_MAKS = 10;
    private const LANGKAH_MAKS = 2000;

    private int $langkah = 0;
    private int $jumlahCin = 0;
    private array $antrianMasukan = [];

    /**
     * @param  string  $kode  Kode yang ditulis siswa
     * @param  array<string,int>  $variabel  Variabel yang tersedia di awal, misal ['jumlah' => 3]
     * @param  array<string,int[]>  $masukan  Nilai yang akan "dibaca" cin secara berurutan per variabel,
     *                                         misal ['jam' => [648, 715, 800]] — cin ke-1 mengisi 648, dst.
     *                                         Ini BUKAN input sungguhan dari mana pun, murni larik yang
     *                                         sudah disiapkan sistem sebagai data uji.
     * @return array{keluaran: ?string, jumlah_cin: int, error: ?string, batas_terlampaui: bool, variabel_akhir: array}
     */
    public function jalankan(string $kode, array $variabel, array $masukan = []): array
    {
        $this->langkah = 0;
        $this->jumlahCin = 0;
        $this->antrianMasukan = $masukan;

        if (mb_strlen($kode) > self::PANJANG_MAKS) {
            return $this->hasil(null, "Kode terlalu panjang (maksimal ".self::PANJANG_MAKS." karakter).", false, $variabel);
        }

        try {
            $token = $this->tokenisasi($kode);
            [$daftarPernyataan, $i] = $this->parseBlok($token, 0, 0);

            if ($i < count($token)) {
                throw new \RuntimeException('Ada bagian kode yang tidak dikenali di akhir. Periksa kurung kurawal dan titik koma.');
            }

            $keluaran = '';
            $batasTerlampaui = false;

            try {
                foreach ($daftarPernyataan as $p) {
                    $this->eksekusi($p, $variabel, $keluaran);
                }
            } catch (BatasPengulanganException $e) {
                $batasTerlampaui = true;
            }

            return $this->hasil($keluaran, null, $batasTerlampaui, $variabel);
        } catch (\RuntimeException $e) {
            return $this->hasil(null, $e->getMessage(), false, $variabel);
        }
    }

    private function hasil(?string $keluaran, ?string $error, bool $batasTerlampaui, array $variabelAkhir): array
    {
        return [
            'keluaran' => $keluaran, 'jumlah_cin' => $this->jumlahCin,
            'error' => $error, 'batas_terlampaui' => $batasTerlampaui,
            'variabel_akhir' => $variabelAkhir,
        ];
    }

    // ---------------------------------------------------------------- Tokenizer

    private function tokenisasi(string $kode): array
    {
        $pola = '/\s*(if|else|for|while|do|int|cout|cin|<<|>>|&&|\|\||\+\+|--|\+=|-=|==|!=|<=|>=|[<>!(){};,=+\-*%\/]|"[^"]*"|[A-Za-z_][A-Za-z0-9_]*|-?\d+)\s*/';
        $pos = 0;
        $token = [];
        $panjang = strlen($kode);

        while ($pos < $panjang) {
            if (! preg_match($pola, $kode, $m, 0, $pos) || $m[1] === '') {
                $sisa = trim(substr($kode, $pos, 15));
                throw new \RuntimeException("Karakter tidak dikenali di dekat: \"{$sisa}\".");
            }
            $token[] = $m[1];
            $pos += strlen($m[0]);
        }

        return $token;
    }

    // ---------------------------------------------------------------- Parser

    private function parseBlok(array $t, int $i, int $depth): array
    {
        $pernyataan = [];
        while (($t[$i] ?? null) !== '}' && $i < count($t)) {
            [$p, $i] = $this->parsePernyataan($t, $i, $depth);
            $pernyataan[] = $p;
        }
        return [$pernyataan, $i];
    }

    private function parsePernyataan(array $t, int $i, int $depth): array
    {
        if ($depth > self::KEDALAMAN_MAKS) {
            throw new \RuntimeException('Blok bersarang terlalu dalam (maksimal '.self::KEDALAMAN_MAKS.' tingkat).');
        }

        $tok = $t[$i] ?? null;

        if ($tok === 'if') return $this->parseIf($t, $i, $depth);
        if ($tok === 'for') return $this->parseFor($t, $i, $depth);
        if ($tok === 'while') return $this->parseWhile($t, $i, $depth);
        if ($tok === 'do') return $this->parseDoWhile($t, $i, $depth);
        if ($tok === 'cout') return $this->parseCout($t, $i);
        if ($tok === 'cin') return $this->parseCin($t, $i);
        if ($tok === 'int' || $this->tampakSepertiPenugasan($t, $i)) return $this->parsePenugasanAtauIncDec($t, $i);

        throw new \RuntimeException("Pernyataan tidak dikenali di dekat \"{$tok}\".");
    }

    private function tampakSepertiPenugasan(array $t, int $i): bool
    {
        $tok = $t[$i] ?? null;
        $next = $t[$i + 1] ?? null;
        return $tok !== null && preg_match('/^[A-Za-z_]/', $tok)
            && in_array($next, ['=', '++', '--', '+=', '-='], true);
    }

    private function parseIf(array $t, int $i, int $depth): array
    {
        $i++;
        $this->harap($t, $i++, '(');
        [$kondisi, $i] = $this->parseKondisi($t, $i);
        $this->harap($t, $i++, ')');
        $this->harap($t, $i++, '{');
        [$blokThen, $i] = $this->parseBlok($t, $i, $depth + 1);
        $this->harap($t, $i++, '}');

        $blokElse = [];
        if (($t[$i] ?? null) === 'else') {
            $i++;
            if (($t[$i] ?? null) === 'if') {
                [$nested, $i] = $this->parsePernyataan($t, $i, $depth + 1);
                $blokElse = [$nested];
            } else {
                $this->harap($t, $i++, '{');
                [$blokElse, $i] = $this->parseBlok($t, $i, $depth + 1);
                $this->harap($t, $i++, '}');
            }
        }

        return [['tipe' => 'if', 'kondisi' => $kondisi, 'then' => $blokThen, 'else' => $blokElse], $i];
    }

    private function parseFor(array $t, int $i, int $depth): array
    {
        $i++;
        $this->harap($t, $i++, '(');
        if (($t[$i] ?? null) === 'int') $i++;
        $var = $this->harapVar($t, $i++);
        $this->harap($t, $i++, '=');
        [$nilaiAwal, $i] = $this->parseEkspr($t, $i);
        $this->harap($t, $i++, ';');
        [$kondisi, $i] = $this->parseKondisi($t, $i);
        $this->harap($t, $i++, ';');
        [$update, $i] = $this->parseUpdate($t, $i, $var);
        $this->harap($t, $i++, ')');
        $this->harap($t, $i++, '{');
        [$blok, $i] = $this->parseBlok($t, $i, $depth + 1);
        $this->harap($t, $i++, '}');

        return [['tipe' => 'for', 'var' => $var, 'nilai_awal' => $nilaiAwal, 'kondisi' => $kondisi, 'update' => $update, 'blok' => $blok], $i];
    }

    private function parseWhile(array $t, int $i, int $depth): array
    {
        $i++;
        $this->harap($t, $i++, '(');
        [$kondisi, $i] = $this->parseKondisi($t, $i);
        $this->harap($t, $i++, ')');
        $this->harap($t, $i++, '{');
        [$blok, $i] = $this->parseBlok($t, $i, $depth + 1);
        $this->harap($t, $i++, '}');

        return [['tipe' => 'while', 'kondisi' => $kondisi, 'blok' => $blok], $i];
    }

    private function parseDoWhile(array $t, int $i, int $depth): array
    {
        $i++;
        $this->harap($t, $i++, '{');
        [$blok, $i] = $this->parseBlok($t, $i, $depth + 1);
        $this->harap($t, $i++, '}');
        $this->harap($t, $i++, 'while');
        $this->harap($t, $i++, '(');
        [$kondisi, $i] = $this->parseKondisi($t, $i);
        $this->harap($t, $i++, ')');
        $this->harap($t, $i++, ';');

        return [['tipe' => 'dowhile', 'kondisi' => $kondisi, 'blok' => $blok], $i];
    }

    private function parseUpdate(array $t, int $i, string $varDiharapkan): array
    {
        $var = $this->harapVar($t, $i++);
        if ($var !== $varDiharapkan) {
            throw new \RuntimeException("Bagian perubahan nilai pada for harus memakai variabel yang sama, yaitu \"{$varDiharapkan}\".");
        }

        $op = $t[$i] ?? null;
        if ($op === '++') { $i++; return [['tipe' => 'inc', 'var' => $var, 'delta' => 1], $i]; }
        if ($op === '--') { $i++; return [['tipe' => 'inc', 'var' => $var, 'delta' => -1], $i]; }
        if ($op === '+=') { $i++; $n = $this->harapAngka($t, $i++); return [['tipe' => 'inc', 'var' => $var, 'delta' => $n], $i]; }
        if ($op === '-=') { $i++; $n = $this->harapAngka($t, $i++); return [['tipe' => 'inc', 'var' => $var, 'delta' => -$n], $i]; }

        throw new \RuntimeException('Bagian perubahan nilai pada for harus salah satu dari: ++, --, +=N, -=N.');
    }

    private function parsePenugasanAtauIncDec(array $t, int $i): array
    {
        $adalahDeklarasi = ($t[$i] ?? null) === 'int';
        if ($adalahDeklarasi) $i++;

        $var = $this->harapVar($t, $i++);
        $op = $t[$i] ?? null;

        if (! $adalahDeklarasi && ($op === '++' || $op === '--')) {
            $i++;
            $this->harap($t, $i++, ';');
            return [['tipe' => 'inc', 'var' => $var, 'delta' => $op === '++' ? 1 : -1], $i];
        }
        if (! $adalahDeklarasi && ($op === '+=' || $op === '-=')) {
            $i++;
            $n = $this->harapAngka($t, $i++);
            $this->harap($t, $i++, ';');
            return [['tipe' => 'inc', 'var' => $var, 'delta' => $op === '+=' ? $n : -$n], $i];
        }

        $this->harap($t, $i++, '=');
        [$ekspr, $i] = $this->parseEkspr($t, $i);
        $daftar = [['tipe' => 'assign', 'var' => $var, 'ekspr' => $ekspr]];

        // Dukung deklarasi berganda: int a = 0, b = 0, c = 0;
        while ($adalahDeklarasi && ($t[$i] ?? null) === ',') {
            $i++;
            $var2 = $this->harapVar($t, $i++);
            $this->harap($t, $i++, '=');
            [$ekspr2, $i] = $this->parseEkspr($t, $i);
            $daftar[] = ['tipe' => 'assign', 'var' => $var2, 'ekspr' => $ekspr2];
        }

        $this->harap($t, $i++, ';');

        return count($daftar) === 1 ? [$daftar[0], $i] : [['tipe' => 'multi', 'daftar' => $daftar], $i];
    }

    private function parseCout(array $t, int $i): array
    {
        $i++;
        $bagian = [];
        do {
            $this->harap($t, $i++, '<<');
            $nilai = $t[$i] ?? null;
            if ($nilai === null) throw new \RuntimeException('Ada "cout <<" tanpa nilai yang dicetak.');
            $i++;
            if ($nilai === 'endl') $bagian[] = ['literal' => true, 'nilai' => "\n"];
            elseif (str_starts_with($nilai, '"')) $bagian[] = ['literal' => true, 'nilai' => trim($nilai, '"')];
            else $bagian[] = ['literal' => false, 'nilai' => $nilai];
        } while (($t[$i] ?? null) === '<<');
        $this->harap($t, $i++, ';');

        return [['tipe' => 'cout', 'bagian' => $bagian], $i];
    }

    private function parseCin(array $t, int $i): array
    {
        $i++;
        $this->harap($t, $i++, '>>');
        $var = $this->harapVar($t, $i++);
        $this->harap($t, $i++, ';');
        return [['tipe' => 'cin', 'var' => $var], $i];
    }

    private function parseKondisi(array $t, int $i): array
    {
        if (($t[$i] ?? null) === '!') {
            [$dalam, $i2] = $this->parseKondisi($t, $i + 1);
            return [['tipe' => 'not', 'operand' => $dalam], $i2];
        }

        [$kiri, $i] = $this->parseBanding($t, $i);

        if (in_array($t[$i] ?? null, ['&&', '||'], true)) {
            $op = $t[$i];
            [$kanan, $i] = $this->parseKondisi($t, $i + 1);
            return [['tipe' => 'logika', 'op' => $op, 'kiri' => $kiri, 'kanan' => $kanan], $i];
        }

        return [$kiri, $i];
    }

    private function parseBanding(array $t, int $i): array
    {
        [$kiri, $i] = $this->parseTerm($t, $i);

        $op = $t[$i] ?? null;
        if (! in_array($op, ['==', '!=', '<=', '>=', '<', '>'], true)) {
            throw new \RuntimeException('Kondisi harus berbentuk perbandingan, misalnya: i <= 5.');
        }
        $i++;

        [$kanan, $i] = $this->parseTerm($t, $i);

        return [['tipe' => 'banding', 'op' => $op, 'kiri' => $kiri, 'kanan' => $kanan], $i];
    }

    /** EKSPR ::= TERM ((+|-|*|/|%) TERM)* — dievaluasi berurutan kiri ke kanan, cukup untuk kebutuhan kelas X */
    private function parseEkspr(array $t, int $i): array
    {
        [$hasil, $i] = $this->parseTerm($t, $i);

        while (in_array($t[$i] ?? null, ['+', '-', '*', '/', '%'], true)) {
            $op = $t[$i];
            [$kanan, $iBaru] = $this->parseTerm($t, $i + 1);
            $hasil = ['tipe' => 'aritmetika', 'op' => $op, 'kiri' => $hasil, 'kanan' => $kanan];
            $i = $iBaru;
        }

        return [$hasil, $i];
    }

    private function parseTerm(array $t, int $i): array
    {
        $tok = $t[$i] ?? null;
        if ($tok === null) throw new \RuntimeException('Ekspresi tidak lengkap.');

        if (preg_match('/^-?\d+$/', $tok)) return [['tipe' => 'angka', 'nilai' => (int) $tok], $i + 1];
        if (preg_match('/^[A-Za-z_]/', $tok)) return [['tipe' => 'var', 'nama' => $tok], $i + 1];

        throw new \RuntimeException("Diharapkan variabel atau bilangan, ditemukan \"{$tok}\".");
    }

    private function harap(array $t, int $i, string $diharapkan): void
    {
        if (($t[$i] ?? null) !== $diharapkan) {
            $ditemukan = $t[$i] ?? '(akhir kode)';
            throw new \RuntimeException("Diharapkan \"{$diharapkan}\" tetapi ditemukan \"{$ditemukan}\".");
        }
    }

    private function harapVar(array $t, int $i): string
    {
        $tok = $t[$i] ?? null;
        if ($tok === null || ! preg_match('/^[A-Za-z_]/', $tok)) {
            throw new \RuntimeException('Diharapkan nama variabel di sini.');
        }
        return $tok;
    }

    private function harapAngka(array $t, int $i): int
    {
        $tok = $t[$i] ?? null;
        if ($tok === null || ! preg_match('/^-?\d+$/', $tok)) {
            throw new \RuntimeException('Diharapkan bilangan bulat di sini.');
        }
        return (int) $tok;
    }

    // ---------------------------------------------------------------- Evaluator

    private function langkahBerikutnya(): void
    {
        $this->langkah++;
        if ($this->langkah > self::LANGKAH_MAKS) {
            throw new BatasPengulanganException();
        }
    }

    private function eksekusi(array $node, array &$variabel, string &$keluaran): void
    {
        $this->langkahBerikutnya();

        switch ($node['tipe']) {
            case 'if':
                $cabang = $this->evalKondisi($node['kondisi'], $variabel) ? $node['then'] : $node['else'];
                foreach ($cabang as $p) $this->eksekusi($p, $variabel, $keluaran);
                return;

            case 'for':
                $variabel[$node['var']] = $this->evalEkspr($node['nilai_awal'], $variabel);
                while ($this->evalKondisi($node['kondisi'], $variabel)) {
                    $this->langkahBerikutnya();
                    foreach ($node['blok'] as $p) $this->eksekusi($p, $variabel, $keluaran);
                    $variabel[$node['var']] += $node['update']['delta'];
                }
                return;

            case 'while':
                while ($this->evalKondisi($node['kondisi'], $variabel)) {
                    $this->langkahBerikutnya();
                    foreach ($node['blok'] as $p) $this->eksekusi($p, $variabel, $keluaran);
                }
                return;

            case 'dowhile':
                do {
                    $this->langkahBerikutnya();
                    foreach ($node['blok'] as $p) $this->eksekusi($p, $variabel, $keluaran);
                } while ($this->evalKondisi($node['kondisi'], $variabel));
                return;

            case 'assign':
                $variabel[$node['var']] = $this->evalEkspr($node['ekspr'], $variabel);
                return;

            case 'multi':
                foreach ($node['daftar'] as $sub) {
                    $variabel[$sub['var']] = $this->evalEkspr($sub['ekspr'], $variabel);
                }
                return;

            case 'inc':
                if (! array_key_exists($node['var'], $variabel)) $variabel[$node['var']] = 0;
                $variabel[$node['var']] += $node['delta'];
                return;

            case 'cin':
                $this->jumlahCin++;
                if (! empty($this->antrianMasukan[$node['var']])) {
                    $variabel[$node['var']] = array_shift($this->antrianMasukan[$node['var']]);
                } elseif (! array_key_exists($node['var'], $variabel)) {
                    $variabel[$node['var']] = 0;
                }
                return;

            case 'cout':
                foreach ($node['bagian'] as $b) {
                    $keluaran .= $b['literal'] ? $b['nilai'] : (string) ($variabel[$b['nilai']] ?? '');
                }
                return;
        }
    }

    private function evalKondisi(array $k, array $variabel): bool
    {
        return match ($k['tipe']) {
            'not' => ! $this->evalKondisi($k['operand'], $variabel),
            'logika' => $k['op'] === '&&'
                ? ($this->evalKondisi($k['kiri'], $variabel) && $this->evalKondisi($k['kanan'], $variabel))
                : ($this->evalKondisi($k['kiri'], $variabel) || $this->evalKondisi($k['kanan'], $variabel)),
            'banding' => $this->evalBanding($k, $variabel),
            default => false,
        };
    }

    private function evalBanding(array $k, array $variabel): bool
    {
        $kiri = $this->evalEkspr($k['kiri'], $variabel);
        $kanan = $this->evalEkspr($k['kanan'], $variabel);

        return match ($k['op']) {
            '==' => $kiri === $kanan, '!=' => $kiri !== $kanan,
            '<=' => $kiri <= $kanan, '>=' => $kiri >= $kanan,
            '<' => $kiri < $kanan, '>' => $kiri > $kanan,
            default => false,
        };
    }

    private function evalEkspr(array $e, array $variabel): int
    {
        if ($e['tipe'] === 'angka') return $e['nilai'];

        if ($e['tipe'] === 'var') {
            if (! array_key_exists($e['nama'], $variabel)) {
                throw new \RuntimeException("Variabel \"{$e['nama']}\" belum diberi nilai.");
            }
            return $variabel[$e['nama']];
        }

        if ($e['tipe'] === 'aritmetika') {
            $kiri = $this->evalEkspr($e['kiri'], $variabel);
            $kanan = $this->evalEkspr($e['kanan'], $variabel);
            return match ($e['op']) {
                '+' => $kiri + $kanan, '-' => $kiri - $kanan,
                '*' => $kiri * $kanan, '%' => $kanan !== 0 ? $kiri % $kanan : 0,
                '/' => $kanan !== 0 ? intdiv($kiri, $kanan) : 0,
                default => 0,
            };
        }

        return 0;
    }
}
