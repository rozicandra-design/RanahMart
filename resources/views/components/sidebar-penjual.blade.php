<div class="bg-white flex flex-col h-full border-r border-gray-200">

    {{-- Header --}}
    <div class="p-4 bg-teal-700 flex-shrink-0">
        <div class="font-bold text-base text-white">
            Ranah<span class="text-teal-200">Mart</span>
        </div>
        <div class="text-xs text-teal-200 mt-0.5 font-semibold">Dashboard Penjual</div>
    </div>

    {{-- Profil Toko --}}
    @if(auth()->user()->toko)
    <div class="p-3 border-b border-gray-100 flex items-center gap-2 flex-shrink-0">
        <div class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->toko->nama_toko, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-xs font-bold text-gray-800 truncate">
                {{ auth()->user()->toko->nama_toko }}
            </div>
            <div class="text-xs {{ auth()->user()->toko->isAktif() ? 'text-teal-600' : 'text-amber-500' }}">
                {{ auth()->user()->toko->isAktif() ? '● Aktif' : '● Belum Aktif' }}
            </div>
        </div>
    </div>
    @endif

    {{-- Menu --}}
    <nav class="flex-1 py-2 overflow-y-auto">

        {{-- Menu Utama --}}
        <div class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Menu Utama
        </div>

        <a href="{{ route('penjual.dashboard') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.dashboard') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('penjual.pesanan.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.pesanan.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Pesanan Masuk
            @php
                try {
                    $pesananBaru = auth()->user()->toko
                        ? \App\Models\Pesanan::where('toko_id', auth()->user()->toko->id)
                            ->where('status_pesanan', 'menunggu')
                            ->count()
                        : 0;
                } catch (\Exception $e) {
                    $pesananBaru = 0;
                }
            @endphp
            @if($pesananBaru > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                {{ $pesananBaru > 9 ? '9+' : $pesananBaru }}
            </span>
            @endif
        </a>

        <a href="{{ route('penjual.produk.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.produk.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
            </svg>
            Kelola Produk
        </a>

        <a href="{{ route('penjual.keuangan.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.keuangan.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
            Keuangan & Saldo
        </a>

        <a href="{{ route('penjual.ulasan.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.ulasan.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            Ulasan Pembeli
        </a>

        <a href="{{ route('penjual.laporan') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.laporan') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            Laporan & Analitik
        </a>

        {{-- Promosi --}}
        <div class="px-3 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Promosi
        </div>

        <a href="{{ route('penjual.iklan.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.iklan.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 11l19-9-9 19-2-8-8-2z"/>
            </svg>
            Pasang Iklan
        </a>

        <a href="{{ route('penjual.promo.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.promo.*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            Promo & Voucher
        </a>

        {{-- Toko --}}
        <div class="px-3 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Toko
        </div>

        <a href="{{ route('penjual.toko.edit') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.toko.edit') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Profil Toko
        </a>

        {{-- Dokumen Toko — dengan indikator kalau menunggu dokumen --}}
        <a href="{{ route('penjual.toko.dokumen') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.toko.dokumen*') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
            Dokumen Toko
            @if(auth()->user()->toko?->status === 'menunggu_dokumen')
            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full flex-shrink-0">!</span>
            @endif
        </a>

        <a href="{{ route('penjual.notifikasi') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.notifikasi') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 01-3.46 0"/>
            </svg>
            Notifikasi
            @if(isset($notifCount) && $notifCount > 0)
            <span class="ml-auto w-1.5 h-1.5 bg-red-500 rounded-full flex-shrink-0"></span>
            @endif
        </a>

        <a href="{{ route('penjual.pengaturan') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
            {{ request()->routeIs('penjual.pengaturan') ? 'border-teal-600 bg-teal-50 text-teal-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>
            </svg>
            Pengaturan
        </a>

    </nav>

    {{-- Keluar --}}
    <div class="p-2 border-t border-gray-100 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

</div>