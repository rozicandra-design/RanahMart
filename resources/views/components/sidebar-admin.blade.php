<div class="bg-gray-900 text-gray-300 flex flex-col h-full">

    {{-- Header --}}
    <div class="p-4 border-b border-gray-700 flex-shrink-0">
        <div class="font-bold text-base text-white">
            Ranah<span class="text-amber-400">Mart</span>
        </div>
        <div class="text-xs text-amber-400 font-semibold mt-0.5">Admin Panel</div>
    </div>

    {{-- Profil Admin --}}
    <div class="p-3 border-b border-gray-700 flex items-center gap-2 flex-shrink-0">
        <div class="w-9 h-9 rounded-lg bg-gray-700 flex items-center justify-center text-amber-400 font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->nama_depan, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nama_lengkap }}</div>
            <div class="text-xs text-gray-400">Super Admin</div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-2 overflow-y-auto">

        <div class="px-3 pt-2 pb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Utama</div>

        @php
        $adminMenus = [
            ['route' => 'admin.dashboard',       'label' => 'Dashboard',       'icon' => 'grid'],
            ['route' => 'admin.users.index',     'label' => 'Manajemen User',  'icon' => 'users'],
            ['route' => 'admin.umkm.index',      'label' => 'UMKM Terdaftar', 'icon' => 'store'],
            ['route' => 'admin.produk.index',    'label' => 'Kelola Produk',   'icon' => 'box'],
            ['route' => 'admin.transaksi.index', 'label' => 'Transaksi',       'icon' => 'money'],
        ];
        $moderasiMenus = [
            ['route' => 'admin.iklan.index',  'label' => 'Kelola Iklan',  'icon' => 'megaphone'],
            ['route' => 'admin.retur.index',  'label' => 'Kelola Retur',  'icon' => 'return'],
        ];
        $sistemMenus = [
            ['route' => 'admin.laporan.index', 'label' => 'Laporan',       'icon' => 'chart'],
            ['route' => 'admin.notifikasi',    'label' => 'Notifikasi',    'icon' => 'bell'],
            ['route' => 'admin.pengaturan',    'label' => 'Pengaturan',    'icon' => 'settings'],
        ];
        $svgs = [
            'grid'      => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
            'users'     => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            'store'     => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'box'       => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>',
            'money'     => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
            'megaphone' => '<path d="M3 11l19-9-9 19-2-8-8-2z"/>',
            'return'    => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>',
            'chart'     => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
            'bell'      => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
            'settings'  => '<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>',
        ];
        @endphp

        @foreach($adminMenus as $menu)
        @php $active = request()->routeIs($menu['route']) || request()->routeIs($menu['route'].'.*'); @endphp
        <a href="{{ route($menu['route']) }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm transition
            {{ $active ? 'bg-gray-800 text-amber-400 font-semibold border-l-2 border-amber-400' : 'hover:bg-gray-800 hover:text-white border-l-2 border-transparent' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $svgs[$menu['icon']] !!}
            </svg>
            {{ $menu['label'] }}

            @if($menu['route'] === 'admin.umkm.index')
                @php
                    $pendingUmkm = 0;
                    if (\Illuminate\Support\Facades\Schema::hasColumn('tokos', 'status')) {
                        $pendingUmkm = \App\Models\Toko::whereIn('status', ['pending', 'menunggu_dinas'])->count();
                    }
                @endphp
                @if($pendingUmkm > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                        {{ $pendingUmkm > 9 ? '9+' : $pendingUmkm }}
                    </span>
                @endif
            @endif

            @if($menu['route'] === 'admin.iklan.index')
                @php $pendingIklan = \App\Models\Iklan::where('status','menunggu')->count(); @endphp
                @if($pendingIklan > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                    {{ $pendingIklan }}
                </span>
                @endif
            @endif
        </a>
        @endforeach

        <div class="px-3 pt-3 pb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Moderasi</div>

        @foreach($moderasiMenus as $menu)
        @php $active = request()->routeIs($menu['route']) || request()->routeIs($menu['route'].'.*'); @endphp
        <a href="{{ route($menu['route']) }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm transition
            {{ $active ? 'bg-gray-800 text-amber-400 font-semibold border-l-2 border-amber-400' : 'hover:bg-gray-800 hover:text-white border-l-2 border-transparent' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $svgs[$menu['icon']] !!}
            </svg>
            {{ $menu['label'] }}
        </a>
        @endforeach

        <div class="px-3 pt-3 pb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Sistem</div>

        @foreach($sistemMenus as $menu)
        @php $active = request()->routeIs($menu['route']) || request()->routeIs($menu['route'].'.*'); @endphp
        <a href="{{ route($menu['route']) }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm transition
            {{ $active ? 'bg-gray-800 text-amber-400 font-semibold border-l-2 border-amber-400' : 'hover:bg-gray-800 hover:text-white border-l-2 border-transparent' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $svgs[$menu['icon']] !!}
            </svg>
            {{ $menu['label'] }}
            @if($menu['route'] === 'admin.notifikasi' && isset($notifCount) && $notifCount > 0)
            <span class="ml-auto w-2 h-2 bg-red-500 rounded-full flex-shrink-0"></span>
            @endif
        </a>
        @endforeach

    </nav>

    {{-- Keluar --}}
    <div class="p-2 border-t border-gray-700 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

</div>