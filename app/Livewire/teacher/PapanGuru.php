<?php

namespace App\Livewire\Teacher;

use App\Models\KontenMisi;
use App\Models\Misi;
use App\Models\Pertemuan;
use App\Models\PoinLog;
use App\Models\ProgresKonten;
use App\Models\User;
use Livewire\Component;

class PapanGuru extends Component
{
    public Pertemuan $pertemuan;

    public function mount(Pertemuan $pertemuan)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $this->pertemuan = $pertemuan;
    }

    public function render()
    {
        $misiPertemuanIds = Misi::where('pertemuan_id', $this->pertemuan->id)->pluck('id');
        $misiIdsPraKelas = $this->pertemuan->misiPraKelas()->pluck('id');

        // Ambil akumulasi poin siswa di pertemuan ini sekaligus (mencegah N+1 query di loop blade)
        $poinPerSiswa = PoinLog::whereIn('misi_id', $misiPertemuanIds)
            ->groupBy('user_id')
            ->selectRaw('user_id, sum(jumlah) as total_poin')
            ->pluck('total_poin', 'user_id');

        $siswa = User::query()
            ->where('role', 'student')
            ->with(['progresMisis' => fn($q) => $q->whereIn('misi_id', $misiIdsPraKelas)])
            ->orderBy('name')
            ->get()
            ->map(function (User $u) use ($misiIdsPraKelas, $poinPerSiswa) {
                $selesai = $u->progresMisis->where('status', 'selesai')->count();
                $u->persen_pra_kelas = $misiIdsPraKelas->count() > 0
                    ? round($selesai / $misiIdsPraKelas->count() * 100)
                    : 0;
                $u->poin_pertemuan_ini = (int) ($poinPerSiswa[$u->id] ?? 0);
                return $u;
            });

        $siswaMap = $siswa->keyBy('id');

        $refleksiKontenIds = KontenMisi::whereIn('misi_id', $misiIdsPraKelas)
            ->where('tipe', 'refleksi')
            ->pluck('id');

        $jawabanRefleksi = ProgresKonten::whereIn('konten_misi_id', $refleksiKontenIds)
            ->with(['kontenMisi'])
            ->where('selesai', true)
            ->latest('updated_at')
            ->get()
            ->map(function ($r) use ($siswaMap) {
                $teks = trim((string) ($r->data['jawaban'] ?? $r->data['teks'] ?? ''));
                if (empty($teks)) return null;

                return [
                    'nama_siswa' => $siswaMap->get($r->user_id)->name ?? 'Siswa Anonim',
                    'pertanyaan' => $r->kontenMisi->pertanyaan_refleksi ?? 'Refleksi Siswa',
                    'teks'       => $teks,
                    'waktu'      => $r->updated_at ? $r->updated_at->diffForHumans() : 'Baru saja',
                ];
            })
            ->filter()
            ->values();

        return view('livewire.teacher.papan-guru', [
            'siswa'           => $siswa,
            'jawabanRefleksi' => $jawabanRefleksi,
        ])->layout('layouts.questify', ['title' => 'Papan Guru']);
    }
}
