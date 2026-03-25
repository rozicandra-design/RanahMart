@extends('layouts.dashboard')
@section('title', 'Dashboard Penjual')
@section('page-title', 'Dashboard Toko')
@section('notif-route', route('penjual.notifikasi'))

@section('sidebar')
    @include('components.sidebar-penjual')
@endsection

@section('content')
<div class="p-6">

    {{-- Alert toko belum aktif --}}
    @if(auth()->user()->toko && !auth()->user()->toko->isAktif())
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-start gap-3">
        <span class="text-2xl flex-shrink-0">⚠️</span>
        <div>
            <div class="font-semibold text-amber-800 text-sm">Toko belum aktif</div>
            <div class="text-xs text-amber-600 mt-0.5">
                Status: <strong>{{ ucfirst(str_replace('_', ' ', auth()->user()->toko->status)) }}</strong>.
                Tunggu verifikasi dari admin (1–3 hari kerja).
            </div>
        </div>
    </div>
    @endif

    {{-- Metrik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Pendapatan Bulan Ini</div>
            <div class="text-xl font-bold text-gray-800">
                Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Pesanan Baru</div>
            <div class="text-xl font-bold text-gray-800">{{ $stats['pesanan_baru'] ?? 0 }}</div>
            @if(($stats['pesanan_baru'] ?? 0) > 0)
            <div class="text-xs text-red-500 font-semibold mt-1">Perlu dikonfirmasi!</div>
            @endif
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Produk Aktif</div>
            <div class="text-xl font-bold text-gray-800">{{ $stats['produk_aktif'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Rating Toko</div>
            <div class="text-xl font-bold text-gray-800">
                ★ {{ number_format($stats['rating_toko'] ?? 0, 1) }}
            </div>
            <div class="text-xs text-gray-400 mt-1">{{ $stats['total_ulasan'] ?? 0 }} ulasan</div>
        </div>
    </div>

    {{-- Iklan aktif --}}
    @if(isset($stats['iklan_aktif']) && $stats['iklan_aktif'])
    @php $iklan = $stats['iklan_aktif']; @endphp
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5 flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">📢 Iklan Sedang Tayang</div>
            <div class="font-semibold text-gray-800">{{ $iklan->judul }}</div>
            <div class="text-xs text-gray-500 mt-0.5">
                {{ $iklan->total_tayangan }} tayangan ·
                {{ $iklan->total_klik }} klik ·
                CTR {{ $iklan->total_tayangan > 0 ? round($iklan->total_klik / $iklan->total_tayangan * 100, 1) : 0 }}%
                · s/d {{ $iklan->tanggal_selesai->format('d M Y') }}
            </div>
        </div>
        <a href="{{ route('penjual.iklan.index') }}"
            class="text-xs bg-amber-500 text-white font-bold px-3 py-2 rounded-lg hover:bg-amber-600 transition flex-shrink-0">
            Lihat Detail
        </a>
    </div>
    @endif

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Pesanan baru --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Pesanan Menunggu</h2>
                <a href="{{ route('penjual.pesanan.index', ['status' => 'menunggu']) }}"
                    class="text-teal-600 text-xs font-semibold hover:underline">
                    Lihat semua →
                </a>
            </div>
            @if(isset($pesananBaru) && $pesananBaru->count())
            <div class="space-y-3">
                @foreach($pesananBaru as $pesanan)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $pesanan->pembeli->nama_depan ?? '' }} ·
                            {{ $pesanan->items->count() }} item ·
                            {{ $pesanan->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-sm font-bold text-gray-800">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('penjual.pesanan.show', $pesanan->id) }}"
                            class="text-xs bg-teal-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">
                            Proses
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">📭</div>
                <p class="text-sm">Tidak ada pesanan baru</p>
            </div>
            @endif
        </div>

        {{-- Top Produk --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Produk Terlaris</h2>
                <a href="{{ route('penjual.produk.index') }}"
                    class="text-teal-600 text-xs font-semibold hover:underline">
                    Kelola →
                </a>
            </div>
            @if(isset($topProduk) && $topProduk->count())
            <div class="space-y-3">
                @foreach($topProduk as $i => $produk)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition">
                    <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
                        {{ $i + 1 }}
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-lg flex-shrink-0">
                        🛍️
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-800 truncate">{{ $produk->nama }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $produk->total_terjual }} terjual · Stok: {{ $produk->stok }}
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-800 flex-shrink-0">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">📦</div>
                <p class="text-sm">Belum ada produk</p>
                <a href="{{ route('penjual.produk.create') }}"
                    class="text-teal-600 text-xs font-semibold hover:underline mt-1 inline-block">
                    + Tambah produk
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Shortcut --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-5">
        <a href="{{ route('penjual.produk.create') }}"
            class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-teal-400 hover:shadow-sm transition group">
            <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-lg">📦</div>
            <div>
                <div class="text-xs font-bold text-gray-700 group-hover:text-teal-700">Tambah Produk</div>
                <div class="text-xs text-gray-400">Kirim ke review</div>
            </div>
        </a>
        <a href="{{ route('penjual.iklan.create') }}"
            class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-amber-400 hover:shadow-sm transition group">
            <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-lg">📢</div>
            <div>
                <div class="text-xs font-bold text-gray-700 group-hover:text-amber-700">Pasang Iklan</div>
                <div class="text-xs text-gray-400">Mulai Rp 50.000</div>
            </div>
        </a>
        <a href="{{ route('penjual.promo.index') }}"
            class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-purple-400 hover:shadow-sm transition group">
            <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg">🏷️</div>
            <div>
                <div class="text-xs font-bold text-gray-700 group-hover:text-purple-700">Buat Voucher</div>
                <div class="text-xs text-gray-400">Promo pembeli</div>
            </div>
        </a>
        <a href="{{ route('penjual.keuangan.index') }}"
            class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-green-400 hover:shadow-sm transition group">
            <div class="w-9 h-9 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-lg">💰</div>
            <div>
                <div class="text-xs font-bold text-gray-700 group-hover:text-green-700">Cairkan Dana</div>
                <div class="text-xs text-gray-400">Saldo: Rp {{ number_format(auth()->user()->toko->saldo ?? 0, 0, ',', '.') }}</div>
            </div>
        </a>
    </div>

</div>
@endsection