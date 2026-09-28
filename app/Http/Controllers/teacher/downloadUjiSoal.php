<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\HasilUjiPublik;
use Illuminate\Http\Request;

class downloadUjiSoal extends Controller
{
    // Pastikan nama method di sini adalah downloadExcel
    public function downloadExcel()
    {
        $filename = "Hasil_Uji_Soal" . date('Y-m-d_H-i') . ".xls";

        // 1. Ambil ID Soal yang unik untuk dijadikan Header Kolom
        $soalIds = HasilUjiPublik::select('soal_ujian_id')
            ->distinct()
            ->orderBy('soal_ujian_id', 'asc')
            ->pluck('soal_ujian_id');

        $jumlahSoal = $soalIds->count();

        // 2. Ambil daftar responden unik (berdasarkan nama dan kelas)
        $respondents = HasilUjiPublik::select('nama', 'kelas')
            ->groupBy('nama', 'kelas')
            ->orderBy('nama', 'asc')
            ->get();

        // 3. Ambil semua data jawaban untuk mempercepat pencocokan
        $allAnswers = HasilUjiPublik::all();

        $html = '<table border="1">
            <thead>
                <tr style="background-color: #f2f2f2; text-align: center;">
                    <th rowspan="2" style="vertical-align: middle;">No</th>
                    <th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>
                    <th rowspan="2" style="vertical-align: middle;">Kelas</th>
                    <th colspan="' . $jumlahSoal . '">Butir Soal (1 = Benar, 0 = Salah)</th>
                    <th rowspan="2" style="vertical-align: middle;">Jumlah Benar</th>
                    <th rowspan="2" style="vertical-align: middle;">Nilai Akhir</th>
                </tr>
                <tr style="background-color: #f9f9f9; text-align: center;">';

        for ($i = 1; $i <= $jumlahSoal; $i++) {
            $html .= '<th>No ' . $i . '</th>';
        }

        $html .= '</tr>
            </thead>
            <tbody>';

        foreach ($respondents as $idx => $res) {
            $html .= '<tr>
                <td style="text-align: center;">' . ($idx + 1) . '</td>
                <td>' . htmlspecialchars($res->nama) . '</td>
                <td style="text-align: center;">' . htmlspecialchars($res->kelas) . '</td>';

            $benar = 0;

            foreach ($soalIds as $soalId) {
                $ans = $allAnswers->where('nama', $res->nama)
                                  ->where('kelas', $res->kelas)
                                  ->where('soal_ujian_id', $soalId)
                                  ->first();

                $val = $ans ? (int) $ans->is_benar : 0;
                
                if ($val === 1) {
                    $benar++;
                }

                $html .= '<td style="text-align: center;">' . $val . '</td>';
            }

            $skor = $jumlahSoal > 0 ? round(($benar / $jumlahSoal) * 100) : 0;

            $html .= '<td style="text-align: center; font-weight: bold;">' . $benar . '</td>
                      <td style="text-align: center; font-weight: bold; color: #16a34a;">' . $skor . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}