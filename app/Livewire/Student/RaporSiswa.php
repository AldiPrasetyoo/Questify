<?php

namespace App\Livewire\Student;

use App\Models\Rapor;
use App\Models\User;
use App\Services\AnalisisKesiapan;
use Carbon\Carbon;
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

        $teks = $this->catatanGuru[$pertemuanId] ?? null;

        Rapor::updateOrCreate(
            ['user_id' => $this->user->id, 'pertemuan_id' => $pertemuanId],
            ['catatan_guru' => $teks]
        );

        $this->isEditing[$pertemuanId] = false;
        session()->flash('success', 'Catatan guru berhasil diperbarui.');
    }

    public function render()
    {
        $rapors = Rapor::where('user_id', $this->user->id)
            ->with(['pertemuan.misis.progresMisis' => function ($query) {
                $query->where('user_id', $this->user->id);
            }])
            ->join('pertemuans', 'pertemuans.id', '=', 'rapors.pertemuan_id')
            ->orderBy('pertemuans.urutan')
            ->select('rapors.*')
            ->get();

        foreach ($rapors as $r) {
            $totalDetik = 0;
            $rincianMisi = []; 

            if ($r->pertemuan && $r->pertemuan->misis) {
                foreach ($r->pertemuan->misis as $misi) {
                    $progres = $misi->progresMisis->first();
                    $detikMisi = 0;
                    $poinMisi = 0;
                    
                    if ($progres) {
                        $poinMisi = (int) $progres->poin_didapat;
                        
                        if ($progres->waktu_mulai && $progres->waktu_selesai) {
                            $detikMisi = Carbon::parse($progres->waktu_mulai)->diffInSeconds($progres->waktu_selesai);
                            $totalDetik += $detikMisi;
                        }
                    }

                    // Format desimal (contoh: 150 detik -> 2.5 menit)
                    $menitFloat = round($detikMisi / 60, 1);
                    $teksDurasi = $menitFloat > 0 ? $menitFloat . ' menit' : '—';

                    // Label fase yang bersih
                    $faseLabels = [
                        'pra_kelas' => 'Pra-Kelas',
                        'tatap_muka' => 'Tatap Muka',
                        'pasca_kelas' => 'Pasca-Kelas'
                    ];
                    $namaFase = $faseLabels[$misi->fase] ?? ucwords(str_replace('_', ' ', $misi->fase));

                    $rincianMisi[$namaFase][] = [
                        'judul' => $misi->judul,
                        'durasi' => $teksDurasi,
                        'poin' => $poinMisi,
                        'selesai' => ($progres && $progres->status === 'selesai')
                    ];
                }
            }

            // Simpan array misi yang sudah dikelompokkan
            $r->rincian_waktu_misi = $rincianMisi;

            // Format total durasi dalam bentuk desimal singkat
            if ($totalDetik > 0) {
                $totalMenitFloat = round($totalDetik / 60, 1);
                $r->total_durasi = $totalMenitFloat . ' menit';
            } else {
                $r->total_durasi = null;
            }
        }

        return view('livewire.student.rapor-siswa', [
            'rapors' => $rapors,
            'lencanas' => $this->user->lencanas,
            'kesiapan' => app(AnalisisKesiapan::class)->perPertemuan($this->user),
        ])->layout('layouts.questify', ['title' => 'Detail Pertemuan']);
    }
}