<?php

namespace App\Livewire\Student;

use App\Models\Rapor;
use App\Models\User;
use App\Models\PoinLog;
use App\Services\AnalisisKesiapan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class RaporSiswa extends Component
{
    public User $user;
    public array $catatanGuru = [];
    public array $isEditing = [];

    public function mount()
    {
        $userId = request()->integer('userId') ?: null;

        if ($userId && auth()->user()->role === 'teacher') {
            $this->user = User::findOrFail($userId);
        } else {
            $this->user = auth()->user();
        }

        $rapors = Rapor::where('user_id', $this->user->id)->get();
        foreach ($rapors as $r) {
            $this->catatanGuru[$r->pertemuan_id] = $r->catatan_guru;
            $this->isEditing[$r->pertemuan_id] = false;
        }
    }

    public function toggleEdit(int $pertemuanId)
    {
        $this->isEditing[$pertemuanId] = !($this->isEditing[$pertemuanId] ?? false);
    }

    public function simpanCatatan(int $pertemuanId)
    {
        if (auth()->user()->role !== 'teacher') {
            abort(403);
        }

        Rapor::updateOrCreate(
            ['user_id' => $this->user->id, 'pertemuan_id' => $pertemuanId],
            ['catatan_guru' => $this->catatanGuru[$pertemuanId] ?? null]
        );

        $this->isEditing[$pertemuanId] = false;
        session()->flash('success', 'Catatan guru berhasil diperbarui.');
    }

    public function render()
    {
        $semuaPoin = PoinLog::where('user_id', $this->user->id)->get();
        $this->user->total_poin = $semuaPoin->sum('jumlah');

        $poinPerMisi = $semuaPoin->groupBy('misi_id')->map(fn($logs) => $logs->sum('jumlah'));
        $poinPerSumber = $semuaPoin->groupBy('misi_id')->map(function ($logs) {
            return $logs->groupBy('sumber')->map(fn($sLogs) => $sLogs->sum('jumlah'));
        });

        // Eager load Jawaban Kuis untuk mencari nomor soal yang salah (Menghindari N+1 Query)
        $allKuisJawabans = DB::table('kuis_jawabans')
            ->join('kuis_soals', 'kuis_jawabans.kuis_soal_id', '=', 'kuis_soals.id')
            ->where('kuis_jawabans.user_id', $this->user->id)
            ->select('kuis_jawabans.*', 'kuis_soals.konten_misi_id', 'kuis_soals.urutan')
            ->get()
            ->groupBy('konten_misi_id');

        $rapors = Rapor::where('user_id', $this->user->id)
            ->with([
                'pertemuan.misis.progresMisis' => function ($query) {
                    $query->where('user_id', $this->user->id);
                },
                'pertemuan.misis.kontens.progresKontens' => function ($query) {
                    $query->where('user_id', $this->user->id);
                }
            ])
            ->join('pertemuans', 'pertemuans.id', '=', 'rapors.pertemuan_id')
            ->orderBy('pertemuans.urutan')
            ->select('rapors.*')
            ->get();

        foreach ($rapors as $r) {
            $totalDetik = 0;
            $rincianMisi = [];
            $poinFase = ['pra_kelas' => 0, 'tatap_muka' => 0, 'pasca_kelas' => 0];
            $totalPoinPertemuan = 0;

            if ($r->pertemuan && $r->pertemuan->misis) {
                foreach ($r->pertemuan->misis as $misi) {
                    $progres = $misi->progresMisis->first();
                    $detikMisi = 0;
                    $poinMisi = (int) ($poinPerMisi[$misi->id] ?? 0);

                    if (array_key_exists($misi->fase, $poinFase)) $poinFase[$misi->fase] += $poinMisi;
                    $totalPoinPertemuan += $poinMisi;

                    if ($progres && $progres->waktu_mulai && $progres->waktu_selesai) {
                        $detikMisi = Carbon::parse($progres->waktu_mulai)->diffInSeconds($progres->waktu_selesai);
                        $totalDetik += $detikMisi;
                    }

                    $detailJawaban = [];
                    foreach ($misi->kontens as $konten) {
                        $pk = $konten->progresKontens->first();
                        $tipe = ucwords(str_replace('_', ' ', $konten->tipe));
                        $pertanyaan = $konten->pertanyaan ?? $konten->pertanyaan_refleksi ?? $konten->judul ?? 'Tugas / Materi';
                        $poinKonten = $poinPerSumber[$misi->id][$konten->tipe] ?? 0;
                        $status = 'belum';
                        $teksJawaban = '';

                        if (!$pk) {
                            $isMateri = in_array($konten->tipe, ['teks_web', 'video', 'materi']);
                            $teksJawaban = $isMateri ? 'Materi belum dibaca.' : 'Tugas belum dikerjakan.';
                        } else {
                            $status = 'selesai';
                            $dataPK = is_string($pk->data) ? json_decode($pk->data, true) : ($pk->data ?? []);

                            // Deteksi Format Teks Web / Materi (Array Kosong)
                            if (empty($dataPK) || (is_array($dataPK) && count($dataPK) === 0)) {
                                $teksJawaban = 'Telah selesai dibaca.';
                            }
                            // Deteksi Kuis & Rincian Salah
                            elseif (isset($dataPK['skor_benar']) && isset($dataPK['total_soal'])) {
                                $teksJawaban = "Benar <b>{$dataPK['skor_benar']}</b> dari <b>{$dataPK['total_soal']}</b> soal.";

                                if (isset($allKuisJawabans[$konten->id])) {
                                    $jawabansKonten = $allKuisJawabans[$konten->id];
                                    $salahNomor = [];

                                    $bySoal = $jawabansKonten->groupBy('kuis_soal_id');
                                    foreach ($bySoal as $soalId => $attempts) {
                                        $latest = $attempts->sortByDesc('percobaan_ke')->first();
                                        if ($latest->benar == 0) {
                                            $salahNomor[] = $latest->urutan;
                                        }
                                    }

                                    if (!empty($salahNomor)) {
                                        sort($salahNomor);
                                        $teksJawaban .= "<br><span class='text-rose-500 text-[10px]'>❌ Salah di nomor: " . implode(', ', $salahNomor) . "</span>";
                                    } else {
                                        $teksJawaban .= "<br><span class='text-emerald-500 text-[10px]'>✅ Sempurna!</span>";
                                    }
                                }
                            }
                            // Deteksi Koding Testcase
                            elseif (isset($dataPK['jumlah_lolos'])) {
                                $teksJawaban = "Lolos <b>{$dataPK['jumlah_lolos']}</b> testcase.";
                            }
                            // Deteksi Koding Logic Kondisi
                            elseif (isset($dataPK['urutan_kondisi'])) {
                                $teksJawaban = "Urutan logika: <b>{$dataPK['urutan_kondisi']}</b><br>Alasan: " . ($dataPK['alasan'] ?? '-');
                            }
                            // Deteksi Kelompok (Menampilkan SELURUH Rincian Jawaban)
                            elseif (isset($dataPK['nama_kelompok'])) {
                                $teksJawaban = "Bergabung di Kelompok: <b>" . htmlspecialchars($dataPK['nama_kelompok']) . "</b>";

                                $rincianEkstra = [];
                                foreach ($dataPK as $key => $val) {
                                    // Lewati key standar agar tidak ganda
                                    if (in_array($key, ['nama_kelompok', 'kode_akhir'])) continue;

                                    $label = ucwords(str_replace('_', ' ', $key));
                                    if (is_array($val)) {
                                        // Jika bentuknya array (misal tabel_kondisi), tampilkan cantik format JSON
                                        $jsonStr = htmlspecialchars(json_encode($val, JSON_PRETTY_PRINT));
                                        $rincianEkstra[] = "<div class='mt-2'><span class='text-[9px] font-black uppercase text-indigo-400 tracking-wider'>{$label}:</span><div class='mt-1 bg-white border border-indigo-50 rounded-lg p-2.5 overflow-x-auto'><pre class='text-[10px] text-slate-600 font-mono leading-relaxed'>{$jsonStr}</pre></div></div>";
                                    } else {
                                        // Jika teks biasa
                                        $textVal = nl2br(htmlspecialchars($val));
                                        $rincianEkstra[] = "<div class='mt-2'><span class='text-[9px] font-black uppercase text-indigo-400 tracking-wider'>{$label}:</span><div class='mt-0.5 text-xs text-slate-700'>{$textVal}</div></div>";
                                    }
                                }

                                if (!empty($rincianEkstra)) {
                                    $teksJawaban .= "<div class='mt-3 pt-2 border-t border-indigo-100/60'>" . implode('', $rincianEkstra) . "</div>";
                                }
                            }
                            // Deteksi Refleksi/Isian
                            elseif (isset($dataPK['jawaban'])) {
                                $teksJawaban = nl2br(htmlspecialchars($dataPK['jawaban']));
                            }
                            // Deteksi Interaktif (Simulasi)
                            elseif (isset($dataPK['waktu_detik'])) {
                                $keliru = $dataPK['jumlah_keliru'] ?? 0;
                                $teksJawaban = "Selesai dalam {$dataPK['waktu_detik']} dtk (Kekeliruan: {$keliru}x)";
                            } else {
                                $teksJawaban = 'Selesai';
                            }

                            // TAMPILKAN KODE EDITOR (FULL, Tanpa Dipotong)
                            if (isset($dataPK['kode_akhir'])) {
                                $kode = htmlspecialchars($dataPK['kode_akhir']);
                                $teksJawaban .= "<div class='mt-3 bg-slate-900 text-emerald-400 p-3.5 rounded-xl overflow-x-auto shadow-inner border border-slate-700'><pre><code class='text-[11px] font-mono whitespace-pre-wrap leading-relaxed'>{$kode}</code></pre></div>";
                            }
                        }

                        $detailJawaban[] = [
                            'tipe'         => $tipe,
                            'pertanyaan'   => $pertanyaan,
                            'teks_jawaban' => $teksJawaban,
                            'poin'         => $poinKonten,
                            'status'       => $status
                        ];
                    }

                    $menitFloat = round($detikMisi / 60, 1);
                    $faseLabels = ['pra_kelas' => 'Pra-Kelas', 'tatap_muka' => 'Tatap Muka', 'pasca_kelas' => 'Pasca-Kelas'];

                    $rincianMisi[$faseLabels[$misi->fase] ?? ucwords(str_replace('_', ' ', $misi->fase))][] = [
                        'judul'          => $misi->judul,
                        'durasi'         => $menitFloat > 0 ? $menitFloat . ' menit' : '—',
                        'poin'           => $poinMisi,
                        'selesai'        => ($progres && $progres->status === 'selesai'),
                        'detail_jawaban' => $detailJawaban
                    ];
                }
            }

            $r->nilai_pra_kelas = $poinFase['pra_kelas'];
            $r->nilai_tatap_muka = $poinFase['tatap_muka'];
            $r->nilai_pasca_kelas = $poinFase['pasca_kelas'];
            $r->total_poin = $totalPoinPertemuan;
            $r->rincian_waktu_misi = $rincianMisi;
            $r->total_durasi = ($totalDetik > 0) ? round($totalDetik / 60, 1) . ' menit' : null;
        }

        return view('livewire.student.rapor-siswa', [
            'rapors'   => $rapors,
            'lencanas' => $this->user->lencanas,
            'kesiapan' => app(AnalisisKesiapan::class)->perPertemuan($this->user),
        ])->layout('layouts.questify', ['title' => 'Detail Rapor Pertemuan']);
    }
}