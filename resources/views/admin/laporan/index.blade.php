@extends('layouts.dashboard')
@section('title', 'Laporan Platform')
@section('page-title', 'Laporan Platform')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap items-center">
        <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ (request('bulan', now()->month) == $m) ? 'selected' : '' }}>
                {{ date('F', mktime(0,0,0,$m,1)) }}
            </option>
            @endforeach
        </select>
        <select name="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            @foreach([date('Y'), date('Y')-1] as $y)
            <option value="{{ $y }}" {{ (request('tahun', date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.laporan.export') }}"
            class="bg-amber-500 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-amber-600 transition">
            Unduh PDF
        </a>
    </form>

    {{-- Metrik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Total Transaksi</div>
            <div class="text-xl font-bold text-gray-800">
                Rp {{ number_format($stats['total_transaksi'] / 1000000, 1) }}jt
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Total Pesanan</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($stats['total_pesanan']) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Komisi Platform</div>
            <div class="text-xl font-bold text-teal-600">
                Rp {{ number_format($stats['total_komisi'] / 1000, 0) }}rb
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Pendapatan Iklan</div>
            <div class="text-xl font-bold text-amber-600">
                Rp {{ number_format($stats['pendapatan_iklan'] / 1000, 0) }}rb
            </div>
        </div>
    </div>

    {{-- Grafik Bar Bulanan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Transaksi Bulanan {{ request('tahun', date('Y')) }}</h3>
        @php $maxBulan = max(array_column($chartBulanan, 'nilai')) ?: 1; @endphp
        <div class="flex items-end gap-2 h-32">
            @foreach($chartBulanan as $d)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full rounded-t bg-amber-400 hover:bg-amber-500 transition cursor-pointer"
                    style="height: {{ max(3, round($d['nilai'] / $maxBulan * 110)) }}px"
                    title="{{ $d['bulan'] }}: Rp {{ number_format($d['nilai'], 0, ',', '.') }}">
                </div>
                <div class="text-xs text-gray-400">{{ $d['bulan'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top UMKM --}}
    @if(isset($topUmkm) && $topUmkm->count())
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-4">🏆 UMKM Terbaik Bulan Ini</h3>
        <div class="space-y-3">
            @foreach($topUmkm->take(10) as $i => $toko)
            <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-none">
                <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate">{{ $toko->nama_toko }}</div>
                    <div class="text-xs text-gray-400">{{ $toko->kecamatan }}</div>
                </div>
                <div class="text-sm font-bold text-gray-800 flex-shrink-0">
                    Rp {{ number_format(($toko->omzet ?? 0) / 1000000, 1) }}jt
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection