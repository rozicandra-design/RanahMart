<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🚫</div>
        <div class="text-6xl font-bold text-red-200 mb-2">403</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Akses Ditolak</h1>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Kamu tidak memiliki izin untuk mengakses halaman ini.
            Pastikan kamu sudah login dengan akun yang tepat.
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url('/') }}"
                class="bg-red-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-red-700 transition">
                Ke Beranda
            </a>
            <a href="{{ route('login') }}"
                class="bg-white border border-gray-300 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-50 transition">
                Masuk
            </a>
        </div>
    </div>
</body>
</html>