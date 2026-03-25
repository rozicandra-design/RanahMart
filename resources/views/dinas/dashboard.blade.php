@extends('layouts.dashboard')
@section('title', 'Dashboard Dinas')
@section('page-title', 'Monitoring UMKM Kota Padang')
@section('notif-route', route('dinas.notifikasi'))
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    {{-- Metrik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">UMKM Terverifikasi</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['umkm_terverifikasi']) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">
                @php $newVer = \App\Models\Toko::where('terverifikasi_dinas', true)->whereMonth('updated_at', now()->month)->count(); @endphp
                ↑ {{ $newVer }} bulan ini
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Menunggu Verifikasi</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['menunggu_verifikasi'] }}</div>
            @if($stats['menunggu_verifikasi'] > 0)
            <div class="text-xs text-red-500 font-semibold mt-1">Perlu segera ditindak</div>
            @endif
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Total Omzet UMKM</div>
            <div class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($stats['total_omzet'] / 1000000, 1) }}jt
            </div>
            <div class="text-xs text-gray-400 mt-1">Bulan ini</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">UMKM Binaan Aktif</div>
            <div class="text-2xl font-bold text-purple-600">{{ $stats['umkm_binaan'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Program 2025</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mb-5">

        {{-- Sebaran Kecamatan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h2 class="font-bold text-gray-800 mb-4">Sebaran UMKM per Kecamatan</h2>
            @php $maxKec = $sebaranKecamatan->max('total') ?: 1; @endphp
            <div class="space-y-2.5">
                @foreach($sebaranKecamatan->take(8) as $k)
                @php $pct = round($k->total / $maxKec * 100); @endphp
                <div class="flex items-center gap-3">
                    <div class="text-xs text-gray-600 w-28 flex-shrink-0 truncate">{{ $k->kecamatan }}</div>
                    <div class="flex-1 h-4 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full"
                            style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="text-xs font-bold text-gray-700 w-8 text-right flex-shrink-0">
                        {{ $k->total }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Antrian Verifikasi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Antrian Verifikasi Terbaru</h2>
                <a href="{{ route('dinas.verifikasi.index') }}"
                    class="text-xs text-purple-600 font-semibold hover:underline">
                    Lihat semua →
                </a>
            </div>
            @if(isset($antrian) && $antrian->count())
            <div class="space-y-3">
                @foreach($antrian as $toko)
                <div class="flex items-start gap-3 p-3 bg-purple-50 border border-purple-200 rounded-xl">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-800 truncate">{{ $toko->nama_toko }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $toko->kecamatan }} · {{ $toko->kategori_friendly }}
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Daftar: {{ $toko->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <a href="{{ route('dinas.verifikasi.show', $toko->id) }}"
                        class="text-xs bg-purple-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-purple-700 transition flex-shrink-0">
                        Tinjau
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">✅</div>
                <p class="text-sm">Tidak ada antrian verifikasi</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Shortcut --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach([
            ['route' => 'dinas.verifikasi.index', 'icon' => '✅', 'label' => 'Verifikasi UMKM',    'color' => 'purple'],
            ['route' => 'dinas.monitoring.index', 'icon' => '👁',  'label' => 'Monitoring Aktif',   'color' => 'blue'],
            ['route' => 'dinas.pembinaan.index',  'icon' => '📚', 'label' => 'Program Pembinaan',  'color' => 'teal'],
            ['route' => 'dinas.laporan.index',    'icon' => '📊', 'label' => 'Rekap Laporan',      'color' => 'amber'],
        ] as $sc)
        <a href="{{ route($sc['route']) }}"
            class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-purple-400 hover:shadow-sm transition group">
            <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg flex-shrink-0">
                {{ $sc['icon'] }}
            </div>
            <div class="text-xs font-bold text-gray-700 group-hover:text-purple-700">{{ $sc['label'] }}</div>
        </a>
        @endforeach
    </div>

</div>
@endsection