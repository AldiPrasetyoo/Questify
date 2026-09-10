<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Questify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Nunito -->
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
            transform: translateY(-12px);
        }

        100% {
            transform: translateY(0px);
        }
    }
    </style>
</head>

<body class="h-screen w-screen bg-[#e0f7fa] relative overflow-hidden flex flex-col">

    <!-- Navbar Area -->
    <header class="w-full px-6 py-4 flex justify-between items-center relative z-40 max-w-7xl mx-auto shrink-0">
        <!-- Logo Kiri -->
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#00c2cb] rounded-lg flex items-center justify-center shadow-md">
                <span class="text-white font-black text-lg">Q</span>
            </div>
            <span class="text-[#00c2cb] font-extrabold text-xl tracking-wider">QUESTIFY</span>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col justify-center items-center relative z-30 px-4 w-full max-w-6xl mx-auto">

        <!-- Judul Halaman -->
        <div class="text-center mb-6 relative z-40">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-[#ff7f00] drop-shadow-md mb-2 leading-tight">
                Ayo Belajar Algoritma Dasar <br class="hidden md:block" /> dengan Seru !
            </h1>
            <p class="text-gray-600 font-bold text-sm md:text-base max-w-lg mx-auto">
                Selesaikan misi, kumpulkan bintang, dan jadilah master algoritma bersama Questify.
            </p>
        </div>

        <!-- Layout Karakter, Kartu, & Peta -->
        <div class="w-full flex flex-col md:flex-row justify-center items-center gap-4 relative z-30">

            <!-- Area Kiri: Robot -->
            <div class="hidden md:flex flex-1 justify-end pr-2">
                <img src="{{ asset('images/robot.png') }}" alt="Robot Questify"
                    class="w-48 lg:w-56 float-anim drop-shadow-xl">
            </div>

            <!-- Area Tengah: Kartu -->
            <div
                class="w-full max-w-[340px] bg-white rounded-[2rem] shadow-2xl border-4 border-white overflow-hidden shrink-0">
                <div class="p-6 text-center flex flex-col items-center">

                    <div class="w-16 h-16 bg-[#e0f7fa] rounded-full flex items-center justify-center mb-3">
                        <span class="text-3xl">🚀</span>
                    </div>

                    <h2 class="text-lg font-extrabold text-gray-800 mb-1">Mulai Petualanganmu!</h2>
                    <p class="text-xs text-gray-500 font-semibold mb-5">Pilih jalurmu dan mulai perjalanan belajar yang
                        interaktif.</p>

                    <div class="w-full flex flex-col gap-2">
                        @auth
                        <a href="{{ url('/dashboard') }}"
                            class="w-full bg-[#ff8a00] text-white font-bold py-3 rounded-full shadow-[0_4px_0_#d97300] hover:translate-y-[2px] hover:shadow-[0_2px_0_#d97300] active:shadow-none active:translate-y-[4px] transition-all text-sm">
                            Lanjutkan Belajar
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="w-full bg-[#ff8a00] text-white font-bold py-3 rounded-full shadow-[0_4px_0_#d97300] hover:translate-y-[2px] hover:shadow-[0_2px_0_#d97300] active:shadow-none active:translate-y-[4px] transition-all text-sm">
                            Mulai Sekarang
                        </a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="w-full bg-[#00c2cb] text-white font-bold py-3 rounded-full shadow-[0_4px_0_#00a4ac] hover:translate-y-[2px] hover:shadow-[0_2px_0_#00a4ac] active:shadow-none active:translate-y-[4px] transition-all mt-1 text-sm">
                            Daftar Akun Baru
                        </a>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Area Kanan: Pulau -->
            <div class="hidden md:flex flex-1 justify-start pl-2">
                <img src="{{ asset('images/awan.png') }}" alt="Peta Petualangan"
                    class="w-56 lg:w-72 float-anim drop-shadow-lg" style="animation-delay: 1s;">
            </div>

        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="w-full text-center py-3 relative z-40 shrink-0">
        <p class="text-xs font-bold text-gray-400">
            &copy; {{ date('Y') }} Questify. All rights reserved.
        </p>
    </footer>
</body>

</html>