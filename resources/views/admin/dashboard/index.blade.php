@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Platform')
@section('notif-route', route('admin.notifikasi'))
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Metrik Utama --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Total UMKM</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_umkm']) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">
                @php $newUmkm = \App\Models\Toko::whereMonth('created_at', now()->month)->count(); @endphp
                ↑ {{ $newUmkm }} bulan ini
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Total Pembeli</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_pembeli']) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">
                @php $newPembeli = \App\Models\User::where('role','pembeli')->whereMonth('created_at', now()->month)->count(); @endphp
                ↑ {{ $newPembeli }} bulan ini
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Transaksi Bulan Ini</div>
            <div class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($stats['transaksi_bulan'] / 1000000, 1) }}jt
            </div>
            <div class="text-xs text-teal-600 font-semibold mt-1">↑ Dari bulan lalu</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Perlu Tindakan</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['perlu_tindakan'] }}</div>
            <div class="text-xs text-red-500 font-semibold mt-1">Segera ditangani</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mb-5">

        {{-- Chart Transaksi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Transaksi 7 Hari Terakhir</h2>
                <a href="{{ route('admin.laporan.index') }}"
                    class="text-xs text-amber-600 font-semibold hover:underline">Laporan lengkap →</a>
            </div>
            @php $maxVal = max(array_column($chartData, 'value')) ?: 1; @endphp
            <div class="flex items-end gap-1.5 h-24">
                @foreach($chartData as $d)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-t-md bg-amber-400 hover:bg-amber-500 transition cursor-pointer"
                        style="height: {{ max(4, round($d['value'] / $maxVal * 80)) }}px"
                        title="Rp {{ number_format($d['value'], 0, ',', '.') }}">
                    </div>
                    <div class="text-xs text-gray-400">{{ $d['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Sebaran UMKM --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h2 class="font-bold text-gray-800 mb-4">Sebaran UMKM per Kategori</h2>
            @php $totalUmkm = $sebaranKategori->sum('total') ?: 1; @endphp
            <div class="space-y-2.5">
                @foreach($sebaranKategori->take(5) as $kat)
                @php $pct = round($kat->total / $totalUmkm * 100); @endphp
                <div class="flex items-center gap-3">
                    <div class="text-xs text-gray-600 w-28 flex-shrink-0 truncate">
                        {{ nama_kategori($kat->kategori) }}
                    </div>
                    <div class="flex-1 h-4 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full transition-all"
                            style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="text-xs font-bold text-gray-700 w-10 text-right flex-shrink-0">
                        {{ $pct }}%
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Perlu Tindakan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h2 class="font-bold text-gray-800 mb-4">⚡ Perlu Tindakan Segera</h2>
        <div class="space-y-3">

            @if($perluTindakan['umkm_pending'] > 0)
            <div class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-lg">🏪</div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $perluTindakan['umkm_pending'] }} UMKM menunggu verifikasi
                        </div>
                        <div class="text-xs text-gray-500">Termasuk pendaftaran baru hari ini</div>
                    </div>
                </div>
                <a href="{{ route('admin.umkm.index') }}"
                    class="text-xs bg-amber-500 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition flex-shrink-0">
                    Tinjau →
                </a>
            </div>
            @endif

            @if($perluTindakan['iklan_pending'] > 0)
            <div class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-lg">📢</div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $perluTindakan['iklan_pending'] }} pengajuan iklan menunggu review
                        </div>
                        <div class="text-xs text-gray-500">Iklan belum bisa tayang sebelum disetujui</div>
                    </div>
                </div>
                <a href="{{ route('admin.iklan.index') }}"
                    class="text-xs bg-amber-500 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition flex-shrink-0">
                    Tinjau →
                </a>
            </div>
            @endif

            @if($perluTindakan['produk_laporan'] > 0)
            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center text-lg">📦</div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $perluTindakan['produk_laporan'] }} produk menunggu review
                        </div>
                        <div class="text-xs text-gray-500">Produk baru dari penjual, belum aktif</div>
                    </div>
                </div>
                <a href="{{ route('admin.produk.index') }}"
                    class="text-xs bg-red-500 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 transition flex-shrink-0">
                    Tinjau →
                </a>
            </div>
            @endif

            @if($perluTindakan['retur_aktif'] > 0)
            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center text-lg">🔄</div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $perluTindakan['retur_aktif'] }} pengajuan retur membutuhkan mediasi
                        </div>
                        <div class="text-xs text-gray-500">Sengketa antara pembeli dan penjual</div>
                    </div>
                </div>
                <a href="{{ route('admin.retur.index') }}"
                    class="text-xs bg-red-500 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 transition flex-shrink-0">
                    Tinjau →
                </a>
            </div>
            @endif

            @if($stats['perlu_tindakan'] === 0)
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">✅</div>
                <p class="text-sm font-semibold text-gray-600">Semua sudah ditangani</p>
                <p class="text-xs mt-1">Tidak ada item yang perlu tindakan saat ini</p>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection