@extends('layouts.dashboard')
@section('title', 'Statistik Wilayah')
@section('page-title', 'Statistik Wilayah')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    {{-- Metrik Utama --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">UMKM Aktif</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_aktif']) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">Terverifikasi Dinas</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">UMKM Baru 2025</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['baru_tahun_ini']) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">
                ↑ {{ round($stats['baru_tahun_ini'] / max($stats['total_aktif'], 1) * 100, 1) }}% pertumbuhan
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tenaga Kerja</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['tenaga_kerja']) }}</div>
            <div class="text-xs text-gray-400 mt-1">Est. dampak langsung</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Kontribusi Ekonomi</div>
            <div class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($stats['kontribusi_ekonomi'] / 1000000000, 1) }}M
            </div>
            <div class="text-xs text-gray-400 mt-1">Estimasi tahunan</div>
        </div>
    </div>

    {{-- Sebaran per Kecamatan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800">Sebaran UMKM per Kecamatan</h2>
            <a href="{{ route('dinas.statistik.export') }}"
                class="text-xs bg-purple-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-purple-700 transition">
                Unduh Data
            </a>
        </div>
        @php $maxSeb = $sebaranKecamatan->max('total') ?: 1; @endphp
        <div class="space-y-3">
            @foreach($sebaranKecamatan as $k)
            @php $pct = round($k->total / $maxSeb * 100); @endphp
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-700 w-36 flex-shrink-0 font-medium">{{ $k->kecamatan }}</div>
                <div class="flex-1 h-6 bg-gray-100 rounded-full overflow-hidden relative">
                    <div class="h-full bg-purple-500 rounded-full transition-all"
                        style="width: {{ $pct }}%"></div>
                    <span class="absolute inset-0 flex items-center pl-3 text-xs font-bold text-white">
                        {{ $pct > 20 ? $k->total . ' UMKM' : '' }}
                    </span>
                </div>
                <div class="text-sm font-bold text-gray-700 w-16 text-right flex-shrink-0">
                    {{ $k->total }} UMKM
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sebaran per Kategori --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h2 class="font-bold text-gray-800 mb-4">Sebaran per Kategori Usaha</h2>
        @php
            $kategoriStats = \App\Models\Toko::where('status','aktif')
                ->select('kategori', \DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->get();
            $totalKat = $kategoriStats->sum('total') ?: 1;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($kategoriStats as $k)
            @php $pct = round($k->total / $totalKat * 100); @endphp
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-purple-700">{{ $k->total }}</div>
                <div class="text-xs font-semibold text-gray-700 mt-1">{{ config('ranahmart.kategori_umkm')[$k->kategori] ?? $k->kategori }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $pct }}% dari total</div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection