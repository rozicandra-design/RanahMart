<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RanahMart') }} — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center py-10 px-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-bold text-red-600">Ranah</span><span class="text-3xl font-bold text-amber-500">Mart</span>
            </a>
            <p class="text-sm text-gray-500 mt-1">Platform UMKM Kota Padang</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-5 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm">
                ⚠ {{ session('warning') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                <div class="font-semibold mb-1">Terdapat kesalahan:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 text-xs text-gray-400">
            © {{ date('Y') }} RanahMart · Didukung Dinas Koperasi & UMKM Kota Padang
        </div>

    </div>

</body>
</html>