<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Questify' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Nunito', sans-serif;
    }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    @vite(['resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#e0f7fa] flex flex-col justify-center">

    <!-- Slot Konten Utama -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 py-8 flex flex-col justify-center">
        {{ $slot }}
    </main>

</body>

</html>