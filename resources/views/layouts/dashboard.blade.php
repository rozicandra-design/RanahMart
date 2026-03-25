<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        @media print {
            /* ── Paksa warna background ikut tercetak ── */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Sembunyikan semua elemen layout dashboard */
            aside,
            header,
            .bg-green-50,
            .bg-red-50,
            .bg-amber-50 {
                display: none !important;
            }

            /* Reset wrapper */
            body, html {
                background: #fff !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            div[style*="display:flex; height:100vh"] {
                display: block !important;
                height: auto !important;
                overflow: visible !important;
            }
            div[style*="flex:1; display:flex; flex-direction:column"] {
                display: block !important;
                overflow: visible !important;
            }
            main {
                overflow: visible !important;
            }

            /* Khusus halaman sertifikat */
            .sert-wrap .cert-toolbar,
            .sert-wrap .right-panel,
            .sert-wrap .back-link {
                display: none !important;
            }
            .sert-wrap {
                background: #fff !important;
                padding: 0 !important;
                min-height: auto !important;
            }
            .sert-wrap .page-grid {
                display: block !important;
            }
            .sert-wrap .cert-outer {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }
            .sert-wrap .cert-scale-outer {
                border-radius: 0 !important;
                overflow: visible !important;
                width: 100% !important;
            }
            /* Reset scale — biarkan browser fit sendiri */
            .sert-wrap #cert-scale-inner {
                transform: none !important;
                width: 100% !important;
            }
            .sert-wrap #cert-scale-inner > div {
                width: 100% !important;
                border: 8px solid #1a3a5c !important;
            }

            @page {
                size: A4 landscape;
                margin: 6mm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div style="display:flex; height:100vh; overflow:hidden;">

        {{-- Sidebar --}}
        <aside style="width:224px; flex-shrink:0; overflow-y:auto; height:100vh; position:sticky; top:0;">
            @yield('sidebar')
        </aside>

        {{-- Main area --}}
        <div style="flex:1; display:flex; flex-direction:column; overflow:hidden;">

            {{-- Topbar --}}
            <header class="bg-white border-b border-gray-200 h-12 flex items-center justify-between px-5 flex-shrink-0">
                <div class="text-sm font-semibold text-gray-700">
                    @yield('page-title', 'Dashboard')
                </div>
                <div class="flex items-center gap-3">
                    <a href="@yield('notif-route', '#')"
                        class="relative w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        @if(isset($notifCount) && $notifCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                        @endif
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                            {{ strtoupper(substr(auth()->user()->nama_depan ?? auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-xs leading-tight">
                            <div class="font-semibold text-gray-800">{{ auth()->user()->nama_depan ?? auth()->user()->name }}</div>
                            <div class="text-gray-400 capitalize">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main style="flex:1; overflow-y:auto;">
                @if(session('success'))
                <div class="bg-green-50 border-b border-green-200 text-green-800 px-6 py-3 text-sm flex items-center gap-2">
                    ✓ {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-50 border-b border-red-200 text-red-800 px-6 py-3 text-sm">
                    ✗ {{ session('error') }}
                </div>
                @endif
                @if(session('warning'))
                <div class="bg-amber-50 border-b border-amber-200 text-amber-800 px-6 py-3 text-sm">
                    ⚠ {{ session('warning') }}
                </div>
                @endif
                @if(isset($errors) && $errors->any())
                <div class="bg-red-50 border-b border-red-200 text-red-800 px-6 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @yield('content')
            </main>

        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>