<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use App\Models\Ujian;
use App\Models\ProgresUjian;
use App\Models\KelompokBelajar;
use App\Models\AnggotaKelompok;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageStudents extends Component
{
    use WithPagination;

    public string $cari = '';

    // Properti Tambahan untuk Generator Kelompok
    public int $jumlahKelompok = 4;
    public $kelompokTerbentuk = [];

    public function updatingCari()
    {
        $this->resetPage();
    }

    public function generateKelompokOtomatis()
    {
        $this->validate([
            'jumlahKelompok' => 'required|integer|min:2|max:10',
        ], [
            'jumlahKelompok.min' => 'Minimal buat 2 kelompok.',
            'jumlahKelompok.max' => 'Maksimal 10 kelompok.',
        ]);

        // 1. Cari ujian pretest
        $ujianPretest = Ujian::where('tipe', 'pretest')->first();

        if (!$ujianPretest) {
            session()->flash('error', 'Data ujian Pre-Test belum tersedia di sistem.');
            return;
        }

        // 2. Ambil seluruh siswa beserta skor pretest, urutkan dari TERTINGGI ke TERENDAH
        // Gunakan groupBy atau unique berdasarkan user_id untuk memastikan data mentah tidak dobel
        $hasilSiswa = ProgresUjian::where('ujian_id', $ujianPretest->id)
            ->with('user')
            ->orderBy('skor_total', 'desc')
            ->get()
            ->unique('user_id'); // Menghindari siswa yang datanya tercatat ganda di tabel progres

        if ($hasilSiswa->isEmpty()) {
            session()->flash('error', 'Belum ada siswa yang menyelesaikan Pre-Test, kelompok belum bisa digenerate.');
            return;
        }

        $pertemuanDefaultId = 1;

        // 3. Bersihkan data kelompok sebelumnya
        $kelompokLama = KelompokBelajar::where('pertemuan_id', $pertemuanDefaultId)->pluck('id');
        AnggotaKelompok::whereIn('kelompok_belajar_id', $kelompokLama)->delete();
        KelompokBelajar::where('pertemuan_id', $pertemuanDefaultId)->delete();

        // 4. Buat wadah kelompok baru
        $wadahKelompok = [];
        for ($i = 1; $i <= $this->jumlahKelompok; $i++) {
            $kelompok = KelompokBelajar::create([
                'pertemuan_id' => $pertemuanDefaultId,
                'nama_kelompok' => 'Kelompok ' . $i
            ]);
            $wadahKelompok[] = $kelompok->id;
        }

        // 5. Algoritma Distribusi Zig-Zag (Serpentine) dengan Pengaman Unik
        $indexKelompok = 0;
        $arah = 1;
        $siswaSudahMasuk = []; // Array pelacak agar tidak ada orang yang sama

        foreach ($hasilSiswa as $dataSiswa) {
            if (!$dataSiswa->user) continue;

            // CEK PENGAMAN: Jika user_id sudah pernah dimasukkan, skip agar tidak dobel!
            if (in_array($dataSiswa->user_id, $siswaSudahMasuk)) {
                continue;
            }

            $targetKelompokId = $wadahKelompok[$indexKelompok];

            AnggotaKelompok::create([
                'kelompok_belajar_id' => $targetKelompokId,
                'user_id' => $dataSiswa->user_id
            ]);

            // Tandai siswa ini sudah masuk kelompok
            $siswaSudahMasuk[] = $dataSiswa->user_id;

            // Pergerakan indeks zig-zag
            $indexKelompok += $arah;
            if ($indexKelompok >= $this->jumlahKelompok) {
                $indexKelompok = $this->jumlahKelompok - 1;
                $arah = -1;
            } elseif ($indexKelompok < 0) {
                $indexKelompok = 0;
                $arah = 1;
            }
        }

        session()->flash('success', 'Berhasil! Kelompok heterogen terbentuk.');
    }

    public function render()
    {
        // Ambil aktivitas terakhir tiap user dari tabel sessions
        $sesiTerakhir = DB::table('sessions')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'))
            ->groupBy('user_id')
            ->pluck('last_activity', 'user_id');

        $batasOnline = Carbon::now()->subMinutes(5)->timestamp;

        $students = User::where('role', 'student')
            ->when($this->cari, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->cari . '%')
                        ->orWhere('email', 'like', '%' . $this->cari . '%');
                });
            })
            ->orderBy('name')
            ->paginate(12);

        // Pasangkan status online dan durasi ke koleksi siswa
        $students->getCollection()->transform(function ($student) use ($sesiTerakhir, $batasOnline) {
            $lastActivity = $sesiTerakhir[$student->id] ?? null;

            if ($lastActivity) {
                $waktuAktivitas = Carbon::createFromTimestamp($lastActivity);
                $isOnline = $lastActivity >= $batasOnline;
                $durasi = $isOnline
                    ? 'Aktif saat ini'
                    : 'Terakhir terlihat ' . $waktuAktivitas->diffForHumans();
                $waktuLengkap = $waktuAktivitas->translatedFormat('d M Y, H:i');
            } else {
                $isOnline = false;
                $durasi = 'Belum pernah aktif';
                $waktuLengkap = '-';
            }

            $student->is_online = $isOnline;
            $student->status_durasi = $durasi;
            $student->waktu_terakhir = $waktuLengkap;

            return $student;
        });

        // Ambil data kelompok yang sudah tergenerate untuk ditampilkan di view
        $daftarKelompok = KelompokBelajar::with('anggotas.user')->get();

        return view('livewire.teacher.manage-students', [
            'students' => $students,
            'totalOnline' => $students->getCollection()->where('is_online', true)->count(),
            'daftarKelompok' => $daftarKelompok,
        ])->layout('layouts.questify', ['title' => 'Daftar Siswa & Kelompok']);
    }
}