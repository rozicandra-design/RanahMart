<div class="bg-white flex flex-col h-full border-r border-gray-200">

    {{-- Header --}}
    <div class="p-4 bg-purple-700 flex-shrink-0">
        <div class="font-bold text-base text-white">
            Ranah<span class="text-purple-200">Mart</span>
        </div>
        <div class="text-xs text-purple-200 mt-0.5 font-semibold">Portal Dinas UMKM</div>
    </div>

    {{-- Profil --}}
    <div class="p-3 border-b border-gray-100 flex-shrink-0">
        <div class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->nama_lengkap }}</div>
        <div class="text-xs text-gray-400 mt-0.5">Dinas Koperasi & UMKM</div>
        <div class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded mt-1">
            Kepala Seksi UMKM
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-2 overflow-y-auto">

        <div class="px-3 pt-2 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Monitoring</div>

        @php
        $menus = [
            ['route' => 'dinas.dashboard',         'label' => 'Dashboard',          'group' => 'monitoring'],
            ['route' => 'dinas.verifikasi.index',  'label' => 'Verifikasi UMKM',    'group' => 'monitoring'],
            ['route' => 'dinas.monitoring.index',  'label' => 'Monitoring UMKM',    'group' => 'monitoring'],
            ['route' => 'dinas.statistik.index',   'label' => 'Statistik Wilayah',  'group' => 'monitoring'],
            ['route' => 'dinas.pembinaan.index',   'label' => 'Program Pembinaan',  'group' => 'program'],
            ['route' => 'dinas.pengumuman.index',  'label' => 'Pengumuman UMKM',    'group' => 'program'],
            ['route' => 'dinas.laporan.index',     'label' => 'Rekap & Laporan',    'group' => 'laporan'],
            ['route' => 'dinas.notifikasi',        'label' => 'Notifikasi',         'group' => 'laporan'],
            ['route' => 'dinas.pengaturan',        'label' => 'Pengaturan',         'group' => 'laporan'],
        ];
        $currentGroup = '';
        $groupLabels  = ['program' => 'Program', 'laporan' => 'Laporan'];
        @endphp

        @foreach($menus as $menu)
            @if($menu['group'] !== $currentGroup && isset($groupLabels[$menu['group']]))
                @php $currentGroup = $menu['group']; @endphp
                <div class="px-3 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    {{ $groupLabels[$menu['group']] }}
                </div>
            @endif
            @php $active = request()->routeIs($menu['route']) || request()->routeIs($menu['route'].'.*'); @endphp
            <a href="{{ route($menu['route']) }}"
                class="flex items-center gap-2 px-4 py-2.5 text-sm border-l-2 transition
                {{ $active
                    ? 'border-purple-600 bg-purple-50 text-purple-700 font-semibold'
                    : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                {{ $menu['label'] }}
                @if($menu['route'] === 'dinas.verifikasi.index')
                    @php $pending = \App\Models\Toko::where('status','menunggu_dinas')->count(); @endphp
                    @if($pending > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                        {{ $pending > 9 ? '9+' : $pending }}
                    </span>
                    @endif
                @endif
                @if($menu['route'] === 'dinas.notifikasi' && isset($notifCount) && $notifCount > 0)
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