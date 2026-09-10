<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
    }

    public function render()
    {
        return $this->view([
            'user' => Auth::user(),
        ])->layout('layouts.app');
    }
};
?>
<x-layouts::questify :title="'Dashboard Siswa'">

    <div class="min-h-screen bg-[#e0f7fa] font-['Nunito'] py-8">
        <div class="max-w-5xl mx-auto px-4">

            <div class="bg-white rounded-[2rem] shadow-xl border-t-[6px] border-t-[#00c2cb] p-6 md:p-8">

                <h1 class="text-3xl font-black text-gray-800">
                    HALO, KODER! 👋
                </h1>

                <p class="text-gray-600 mt-2">
                    {{ auth()->user()->name }}
                </p>

            </div>

        </div>
    </div>

</x-layouts::questify>