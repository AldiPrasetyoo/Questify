<?php

namespace App\Livewire\Student;

use App\Models\User;
use Livewire\Component;

class PapanPeringkat extends Component
{
    public function render()
    {
        $siswa = User::where('role', 'student')
            ->withCount('lencanas')
            ->orderByDesc('total_poin')
            ->limit(50)
            ->get();

        // Kembalikan ke compact('siswa')
        return view('livewire.student.papan-peringkat', compact('siswa'))
            ->layout('layouts.questify', ['title' => 'Papan Peringkat']);
    }
}