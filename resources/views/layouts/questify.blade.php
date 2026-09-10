<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Questify' }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Nunito', sans-serif;
    }
    </style>

    <!-- Quill Editor -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>

    <!-- Vite -->
    @vite(['resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-[#e0f7fa] flex flex-col">

    <!-- =====================================================
     NAVBAR
====================================================== -->

    <nav class="bg-white shadow-md border-b-2 border-gray-100 py-3 px-6 md:px-12 flex items-center justify-between">

        <!-- =================================================
         KIRI: LOGO + DASHBOARD
    ================================================== -->

        <div class="flex items-center gap-6">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity"
                wire:navigate>
                <div class="w-8 h-8 bg-[#00c2cb] rounded-lg flex items-center justify-center shadow-md">
                    <span class="text-white font-black text-lg">Q</span>
                </div>
                <span class="text-[#00c2cb] font-extrabold text-xl tracking-wider">QUESTIFY</span>
            </a>

            @auth
            <!-- =========================================
                 DASHBOARD BERDASARKAN ROLE
            ========================================== -->

            @if(auth()->user()->role === 'teacher')

            <a href="{{ route('teacher.dashboard') }}"
                class="text-xs font-extrabold text-gray-600 hover:text-[#00c2cb] transition-colors flex items-center gap-1.5 bg-gray-50 hover:bg-teal-50 py-2 px-4 rounded-full border border-gray-200">
                <span>🏠</span>
                Dashboard Guru
            </a>

            @elseif(auth()->user()->role === 'student')

            <a href="{{ route('student.dashboard') }}"
                class="text-xs font-extrabold text-gray-600 hover:text-[#00c2cb] transition-colors flex items-center gap-1.5 bg-gray-50 hover:bg-teal-50 py-2 px-4 rounded-full border border-gray-200">
                <span>🏠</span>
                Dashboard Siswa
            </a>

            @endif

            @endauth

        </div>


        <!-- =================================================
         KANAN: PROFILE
    ================================================== -->

        <div class="flex items-center">

            @auth

            <div class="relative">

                <!-- =====================================
                     PROFILE BUTTON
                ====================================== -->

                <button onclick="toggleDropdown()" id="profileButton" type="button"
                    class="flex items-center gap-3 focus:outline-none bg-gray-50 hover:bg-gray-100 p-1.5 pr-3 rounded-full border border-gray-200 transition-all">

                    <!-- Avatar -->
                    <div
                        class="w-8 h-8 rounded-full bg-[#00c2cb] text-white font-black flex items-center justify-center text-xs shadow-inner">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>

                    <!-- Nama + Role -->
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-black text-gray-800 leading-tight">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-bold uppercase text-[#ff8a00]">
                            {{ auth()->user()->role }}
                        </p>
                    </div>

                    <!-- Arrow -->
                    <span id="profileArrow" class="text-xs text-gray-400 transition-transform duration-200">
                        ▼
                    </span>

                </button>

                <!-- =====================================
                     DROPDOWN
                ====================================== -->

                <div id="profileDropdown"
                    class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">

                    <!-- Mobile Profile -->
                    <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
                        <p class="text-xs font-black text-gray-800 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-bold uppercase text-[#ff8a00]">
                            {{ auth()->user()->role }}
                        </p>
                    </div>

                    <!-- Account Settings -->
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-teal-50 hover:text-[#00c2cb] transition-colors">
                        ⚙️ Pengaturan Akun
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-100 my-1"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                            <span>🚪</span>
                            Keluar
                        </button>
                    </form>

                </div>

            </div>

            @endauth

        </div>

    </nav>


    <!-- =====================================================
     MAIN CONTENT
====================================================== -->

    <main class="flex-1 max-w-5xl w-full mx-auto px-4 py-8">
        {{ $slot }}
    </main>


    <!-- =====================================================
     PROFILE DROPDOWN SCRIPT
====================================================== -->

    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('profileArrow');

        if (!dropdown) {
            return;
        }

        dropdown.classList.toggle('hidden');

        if (arrow) {
            arrow.classList.toggle('rotate-180');
        }
    }

    // ================================================
    // CLOSE DROPDOWN WHEN CLICKING OUTSIDE
    // ================================================
    window.addEventListener('click', function(e) {
        const button = document.getElementById('profileButton');
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('profileArrow');

        if (!button || !dropdown) {
            return;
        }

        if (
            !button.contains(e.target) &&
            !dropdown.contains(e.target)
        ) {
            dropdown.classList.add('hidden');

            if (arrow) {
                arrow.classList.remove('rotate-180');
            }
        }
    });
    </script>

    @livewireScripts
</body>

</html>