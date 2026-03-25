<div class="bg-white flex flex-col h-full border-r border-gray-200">

    {{-- Header --}}
    <div class="p-4 bg-blue-600 flex-shrink-0">
        <div class="font-bold text-base text-white">
            Ranah<span class="text-blue-200">Mart</span>
        </div>
        <div class="text-xs text-blue-200 mt-0.5 font-semibold">Dashboard Pembeli</div>
    </div>

    {{-- Profil --}}
    <div class="p-3 border-b border-gray-100 flex items-center gap-2 flex-shrink-0">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->nama_depan, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->nama_lengkap }}</div>
            <div class="text-xs text-blue-600 font-semibold">{{ auth()->user()->total_poin }} poin</div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-2 overflow-y-auto">

        @php
        $menus = [
            ['route' => 'pembeli.dashboard',      'label' => 'Beranda',        'icon' => 'home'],
            ['route' => 'pembeli.pesanan.index',   'label' => 'Pesanan Saya',   'icon' => 'box'],
            ['route' => 'pembeli.keranjang.index', 'label' => 'Keranjang',      'icon' => 'cart'],
            ['route' => 'pembeli.wishlist.index',  'label' => 'Wishlist',       'icon' => 'heart'],
            ['route' => 'pembeli.ulasan.index',    'label' => 'Ulasan Saya',    'icon' => 'star'],
            ['route' => 'pembeli.retur.index',     'label' => 'Pengembalian',   'icon' => 'return'],
            ['route' => 'pembeli.profil.edit',     'label' => 'Profil & Akun',  'icon' => 'user'],
            ['route' => 'pembeli.alamat.index',    'label' => 'Alamat',         'icon' => 'map'],
            ['route' => 'pembeli.voucher.index',   'label' => 'Voucher',        'icon' => 'tag'],
            ['route' => 'pembeli.poin.index',      'label' => 'Poin Reward',    'icon' => 'gift'],
            ['route' => 'pembeli.notifikasi',      'label' => 'Notifikasi',     'icon' => 'bell'],
            ['route' => 'pembeli.pengaturan',      'label' => 'Pengaturan',     'icon' => 'settings'],
        ];
        $svgIcons = [
            'home'     => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'box'      => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
            'cart'     => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',
            'heart'    => '<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>',
            'star'     => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'return'   => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>',
            'user'     => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'map'      => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
            'tag'      => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
            'gift'     => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/>',
            'bell'     => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
            'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>',
        ];
        @endphp

        @foreach($menus as $menu)
        @php $isActive = request()->routeIs($menu['route']) || request()->routeIs($menu['route'].'.*'); @endphp
        <a href="{{ route($menu['route']) }}"
            class="flex items-center gap-2.5 px-4 py-2.5 text-sm border-l-2 transition
            {{ $isActive ? 'border-blue-600 bg-blue-50 text-blue-700 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $svgIcons[$menu['icon']] !!}
            </svg>
            {{ $menu['label'] }}
            @if($menu['route'] === 'pembeli.keranjang.index')
                @php $cartCount = auth()->user()->keranjangs()->count(); @endphp
                @if($cartCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                </span>
                @endif
            @endif
            @if($menu['route'] === 'pembeli.notifikasi' && isset($notifCount) && $notifCount > 0)
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full flex-shrink-0"></span>
            @endif
        </a>
        @endforeach

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