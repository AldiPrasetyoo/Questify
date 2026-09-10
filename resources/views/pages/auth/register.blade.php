<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Questify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Nunito', sans-serif;
    }

    .float-anim {
        animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }
    </style>
</head>

<body
    class="min-h-screen w-screen bg-[#e0f7fa] relative overflow-x-hidden overflow-y-auto flex flex-col justify-between">

    <!-- Navbar -->
    <header class="w-full px-6 py-3 flex justify-between items-center relative z-40 max-w-7xl mx-auto">
        <a href="{{ url('/') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity" wire:navigate>
            <div class="w-8 h-8 bg-[#00c2cb] rounded-lg flex items-center justify-center shadow-md">
                <span class="text-white font-black text-lg">Q</span>
            </div>
            <span class="text-[#00c2cb] font-extrabold text-xl tracking-wider">QUESTIFY</span>
        </a>
    </header>

    <!-- Main Content Area -->
    <main class="flex flex-col justify-center items-center relative z-30 px-4 w-full max-w-6xl mx-auto mt-[-5px]">

        <!-- Judul -->
        <div class="text-center mb-3 relative z-40">
            <h1 class="text-2xl md:text-3xl font-black text-[#ff7f00] drop-shadow-sm mb-1 leading-tight">
                Ayo Belajar Algoritma Dasar <br class="hidden md:block" /> dengan Seru !
            </h1>
        </div>

        <div class="w-full flex flex-col md:flex-row justify-center items-center gap-4 relative z-30">

            <!-- Area Kiri: Robot -->
            <div class="hidden md:flex flex-1 justify-end pr-2">
                <img src="{{ asset('images/robot.png') }}" alt="Robot Questify"
                    class="w-36 lg:w-44 float-anim drop-shadow-xl">
            </div>

            <!-- Area Tengah: Kartu Pendaftaran -->
            <div
                class="w-full max-w-[340px] bg-white rounded-[1.5rem] shadow-xl border-t-[6px] border-t-[#ff8a00] border-x border-b border-gray-100 shrink-0">

                <div class="p-5 md:px-6 md:py-4">

                    <!-- Header Kartu -->
                    <div class="text-center mb-3">
                        <div
                            class="w-12 h-12 bg-[#e0f7fa] rounded-full flex items-center justify-center mx-auto mb-1.5">
                            <span class="text-xl">🤖</span>
                        </div>
                        <h2 class="text-xs font-extrabold text-gray-800 tracking-wide">BUAT AKUN BARU!</h2>
                    </div>

                    <x-auth-session-status class="mb-2 text-center text-[11px] text-green-600 font-bold"
                        :status="session('status')" />

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-2.5">
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                placeholder="Nama Lengkap" required autofocus autocomplete="name"
                                class="w-full px-4 py-2 text-xs font-semibold border-2 border-gray-200 rounded-full focus:outline-none focus:border-[#ff8a00] focus:ring-1 focus:ring-[#ff8a00] transition-colors placeholder-gray-400">
                            @error('name')
                            <p class="mt-0.5 text-[10px] text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-2.5">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                required autocomplete="email"
                                class="w-full px-4 py-2 text-xs font-semibold border-2 border-gray-200 rounded-full focus:outline-none focus:border-[#ff8a00] focus:ring-1 focus:ring-[#ff8a00] transition-colors placeholder-gray-400">
                            @error('email')
                            <p class="mt-0.5 text-[10px] text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-2.5">
                            <input id="password" type="password" name="password" placeholder="Password" required
                                autocomplete="new-password"
                                class="w-full px-4 py-2 text-xs font-semibold border-2 border-gray-200 rounded-full focus:outline-none focus:border-[#ff8a00] focus:ring-1 focus:ring-[#ff8a00] transition-colors placeholder-gray-400">
                            @error('password')
                            <p class="mt-0.5 text-[10px] text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                placeholder="Konfirmasi Password" required autocomplete="new-password"
                                class="w-full px-4 py-2 text-xs font-semibold border-2 border-gray-200 rounded-full focus:outline-none focus:border-[#ff8a00] focus:ring-1 focus:ring-[#ff8a00] transition-colors placeholder-gray-400">
                            @error('password_confirmation')
                            <p class="mt-0.5 text-[10px] text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Daftar -->
                        <button type="submit"
                            class="w-full bg-[#ff8a00] text-white font-bold py-2.5 rounded-full shadow-[0_3px_0_#d97300] hover:translate-y-[1px] hover:shadow-[0_2px_0_#d97300] active:shadow-none active:translate-y-[3px] transition-all text-xs mb-3">
                            {{ __('Daftar Akun') }}
                        </button>

                        <p class="text-center text-[11px] font-semibold text-gray-500">
                            Sudah punya akun? <a href="{{ route('login') }}" wire:navigate
                                class="text-[#00c2cb] font-extrabold hover:underline">Masuk Sekarang</a>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Area Kanan: Pulau -->
            <div class="hidden md:flex flex-1 justify-start pl-2">
                <img src="{{ asset('images/awan.png') }}" alt="Peta Petualangan"
                    class="w-44 lg:w-56 float-anim drop-shadow-lg" style="animation-delay: 1s;">
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-2 relative z-40">
        <p class="text-[10px] font-bold text-gray-400">&copy; {{ date('Y') }} Questify. All rights reserved.</p>
    </footer>

    @livewireScripts
</body>

</html>