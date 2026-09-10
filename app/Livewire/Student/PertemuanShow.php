<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Pertemuan;

class PertemuanShow extends Component
{
    public Pertemuan $pertemuan;
    
    public function render()
    {
        return view('livewire.student.pertemuan-show',[
            $this->pertemuan->judul
        ])->layout('layouts.questify', ['title' => 'Detail Pertemuan']);
    }
}