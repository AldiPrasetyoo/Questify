<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ujian;
use Illuminate\Http\Request;

class downloadData extends Controller
{
    public function downloadExcel()
    {
        $filename = "rekap_validitas_nilai_siswa_" . date('Y-m-d') . ".xls";

        $siswas = User::where('role', 'student')->with(['progresUjians.ujian', 'jawabanUjians'])->get();
        $pretest = Ujian::where('tipe', 'pretest')->first();
        $posttest = Ujian::where('tipe', 'posttest')->first();

        $jumlahSoalPre = $pretest ? $pretest->soalUjians()->count() : 0;
        $jumlahSoalPost = $posttest ? $posttest->soalUjians()->count() : 0;

        $html = '<table border="1">
            <thead>
                <tr style="background-color: #f2f2f2; text-align: center;">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Siswa</th>';

        // Header kelompok utama: Pretest dan Posttest
        if ($pretest && $jumlahSoalPre > 0) {
            $html .= '<th colspan="' . $jumlahSoalPre . '">Pretest</th>';
        }
        if ($posttest && $jumlahSoalPost > 0) {
            $html .= '<th colspan="' . $jumlahSoalPost . '">Posttest</th>';
        }

        $html .= '<th rowspan="2">Nilai Pretest</th>
                  <th rowspan="2">Nilai Posttest</th>
                </tr>
                <tr style="background-color: #f9f9f9; text-align: center;">';

        // Sub-header butir soal Pretest
        if ($pretest) {
            foreach ($pretest->soalUjians()->get() as $i => $s) {
                $html .= '<th>Soal ' . ($i + 1) . '</th>';
            }
        }

        // Sub-header butir soal Posttest
        if ($posttest) {
            foreach ($posttest->soalUjians()->get() as $i => $s) {
                $html .= '<th>Soal ' . ($i + 1) . '</th>';
            }
        }

        $html .= '</tr></thead><tbody>';

        // Baris Data Siswa
        foreach ($siswas as $idx => $siswa) {
            $nPre = $siswa->progresUjians->where('ujian_id', $pretest?->id)->first()?->skor_total ?? 0;
            $nPost = $siswa->progresUjians->where('ujian_id', $posttest?->id)->first()?->skor_total ?? 0;

            $html .= '<tr>
                <td style="text-align: center;">' . ($idx + 1) . '</td>
                <td>' . htmlspecialchars($siswa->name) . '</td>';

            // Data butir jawaban Pretest (1 / 0)
            if ($pretest) {
                foreach ($pretest->soalUjians as $s) {
                    $ans = $siswa->jawabanUjians->where('soal_ujian_id', $s->id)->first();
                    $val = $ans ? $ans->is_benar : 0;
                    $html .= '<td style="text-align: center;">' . $val . '</td>';
                }
            }

            // Data butir jawaban Posttest (1 / 0)
            if ($posttest) {
                foreach ($posttest->soalUjians as $s) {
                    $ans = $siswa->jawabanUjians->where('soal_ujian_id', $s->id)->first();
                    $val = $ans ? $ans->is_benar : 0;
                    $html .= '<td style="text-align: center;">' . $val . '</td>';
                }
            }

            // Kolom Nilai Akhir
            $html .= '<td style="text-align: center; font-weight: bold;">' . $nPre . '</td>
                      <td style="text-align: center; font-weight: bold;">' . $nPost . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
