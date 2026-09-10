<?php

namespace App\Livewire\Student;

use App\Models\Rapor;
use App\Models\User;
use App\Services\AnalisisKesiapan;
use Livewire\Component;

class RaporSiswa extends Component
{
    public User $user;
    public array $catatanGuru = [];
    public array $isEditing = []; // Melacak status edit per pertemuan

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
            $this->isEditing[$r->pertemuan_id] = false; // Default awal mode lihat (bukan edit)
        }
    }

    public function toggleEdit(int $pertemuanId)
    {
        // Toggle status true/false untuk baris pertemuan tersebut
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

        // Matikan mode edit setelah disimpan
        $this->isEditing[$pertemuanId] = false;

        session()->flash('success', 'Catatan guru berhasil diperbarui.');
    }

    public function render()
    {
        $rapors = Rapor::where('user_id', $this->user->id)
            ->with('pertemuan')
            ->join('pertemuans', 'pertemuans.id', '=', 'rapors.pertemuan_id')
            ->orderBy('pertemuans.urutan')
            ->select('rapors.*')
            ->get();

        return view('livewire.student.rapor-siswa', [
            'rapors' => $rapors,
            'lencanas' => $this->user->lencanas,
            'kesiapan' => app(AnalisisKesiapan::class)->perPertemuan($this->user),
        ])->layout('layouts.questify', ['title' => 'Detail Pertemuan']);
    }
}