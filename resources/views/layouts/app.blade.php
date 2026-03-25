<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RanahMart') }} — @yield('title', 'Platform UMKM Padang')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    @include('components.navbar')

    @if(session('success'))
        <div class="bg-green-100 border-b border-green-300 text-green-800 px-4 py-3 text-sm text-center">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-b border-red-300 text-red-800 px-4 py-3 text-sm text-center">
            ✗ {{ session('error') }}
        </div>
    @endif

    <main>@yield('content')</main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>