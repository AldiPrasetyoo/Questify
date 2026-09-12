<?php

namespace App\Livewire\Student;

use App\Models\Misi;
use App\Models\User;
use App\Models\ProgresKonten;
use App\Models\ProgresMisi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class MisiViewer extends Component
{
    public Misi $misi;
    public int $indexKontenAktif = 0;

    // Properti untuk notifikasi modal & popup
    public int $poinDidapat = 0;
    public ?object $lencanaBaru = null;

    public function mount(Misi $misi)
    {
        $user = Auth::user();

        abort_unless($misi->terbukaUntuk($user), 403, 'Misi ini masih terkunci. Selesaikan misi sebelumnya dulu ya.');

        $this->misi = $misi;

        $progres = ProgresMisi::firstOrCreate(
            ['user_id' => $user->id, 'misi_id' => $misi->id],
            ['status' => 'sedang_berjalan', 'waktu_mulai' => now()]
        );

        if ($progres->status === 'belum_dimulai') {
            $progres->status = 'sedang_berjalan';
            $progres->waktu_mulai = now();
            $progres->save();
        }
    }

    // Fungsi Navigasi Mundur
    public function kontenSebelumnya()
    {
        $kontenSaatIni = $this->misi->kontens[$this->indexKontenAktif] ?? null;

        // Keamanan Backend: Blokir aksi mundur jika sedang berada di mode kuis
        if ($kontenSaatIni && $kontenSaatIni->tipe === 'kuis') {
            return;
        }

        if ($this->indexKontenAktif > 0) {
            $this->indexKontenAktif--;
        }
    }

    #[On('kontenSelesai')]
    public function kontenSelesai(int $kontenMisiId, array $data = [], int $poin = 0)
    {
        $user = Auth::user();

        // 1. Cek apakah konten ini SUDAH PERNAH diselesaikan sebelumnya
        $progresKontenLama = ProgresKonten::where('user_id', $user->id)
            ->where('konten_misi_id', $kontenMisiId)
            ->first();

        $sudahPernahKonten = $progresKontenLama && $progresKontenLama->selesai;

        // Simpan atau update progres konten
        ProgresKonten::updateOrCreate(
            ['user_id' => $user->id, 'konten_misi_id' => $kontenMisiId],
            ['data' => $data, 'selesai' => true]
        );

        // HANYA tambah poin konten jika baru pertama kali menyelesaikan
        if ($poin > 0 && !$sudahPernahKonten) {
            $tipeAktivitas = isset($data['total_soal']) ? 'kuis' : 'koding';
            $user->tambahPoin($poin, $tipeAktivitas, $this->misi->id);
            app(\App\Services\PenghitungRapor::class)->perbaruiUntuk($user, $this->misi->pertemuan_id);
        }

        // 2. Cek penyelesaian misi secara keseluruhan
        $totalKonten = $this->misi->kontens()->count();
        $kontenSelesai = ProgresKonten::where('user_id', $user->id)
            ->whereIn('konten_misi_id', $this->misi->kontens()->pluck('id'))
            ->where('selesai', true)
            ->count();

        if ($totalKonten > 0 && $kontenSelesai >= $totalKonten) {
            $lencanaSebelumnyaIds = method_exists($user, 'lencanas')
                ? $user->lencanas()->pluck('lencanas.id')->toArray()
                : [];

            $progresMisi = ProgresMisi::firstOrNew([
                'user_id' => $user->id,
                'misi_id' => $this->misi->id,
            ]);

            // Cek apakah MISI ini sebelumnya sudah pernah selesai
            $sudahPernahMisi = ($progresMisi->exists && $progresMisi->status === 'selesai');

            if (!$sudahPernahMisi) {
                // Poin misi penuh hanya didapat saat pertama kali tuntas
                $this->poinDidapat = (int) ($this->misi->poin_maksimal ?? 0);

                $progresMisi->status = 'selesai';
                $progresMisi->waktu_selesai = now();
                $progresMisi->poin_didapat = $this->poinDidapat;
                $progresMisi->save();

                // Jika tidak memakai observer otomatis, panggil tambahPoin:
                if ($this->poinDidapat > 0) {
                    $user->tambahPoin($this->poinDidapat, 'misi', $this->misi->id);
                }

                // Pengecekan Lencana Baru
                if (method_exists($user, 'lencanas')) {
                    $user->load('lencanas');
                    $this->lencanaBaru = $user->lencanas
                        ->whereNotIn('id', $lencanaSebelumnyaIds)
                        ->last();
                }
            } else {
                // Jika mengulang pengerjaan, modal tetap tampil tapi perolehan poin 0
                $this->poinDidapat = 0;
            }
        }

        $this->indexKontenAktif++;
    }

    public function render()
    {
        return view('livewire.student.misi-viewer', [
            'kontens' => $this->misi->kontens,
            'progresKonten' => ProgresKonten::where('user_id', Auth::id())
                ->whereIn('konten_misi_id', $this->misi->kontens->pluck('id'))
                ->get()
                ->keyBy('konten_misi_id'),
        ])->layout('layouts.clear', ['title' => 'Misi Pembelajaran']);
    }
}