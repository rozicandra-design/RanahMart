@extends('layouts.dashboard')
@section('title', 'Pesanan Saya')
@section('page-title', 'Pesanan Saya')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Tab filter --}}
    <div class="flex gap-2 mb-5 flex-wrap">
        @foreach([
            ''           => 'Semua',
            'menunggu'   => 'Menunggu',
            'diproses'   => 'Diproses',
            'dikirim'    => 'Dikirim',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ] as $val => $label)
        <a href="{{ route('pembeli.pesanan.index', ['status' => $val]) }}"
            class="px-4 py-2 rounded-full text-sm font-semibold border transition
            {{ request('status') == $val
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400 hover:text-blue-600' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- List Pesanan --}}
    <div class="space-y-4">
        @forelse($pesanans as $pesanan)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-sm transition">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="text-sm font-bold text-gray-800">{{ $pesanan->toko->nama_toko ?? 'Toko' }}</div>
                    @if($pesanan->toko->terverifikasi_dinas ?? false)
                    <span class="text-xs bg-teal-100 text-teal-700 font-bold px-1.5 py-0.5 rounded">✓ Dinas</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusClass = match($pesanan->status_pesanan) {
                            'menunggu'   => 'bg-amber-100 text-amber-700',
                            'diproses'   => 'bg-blue-100 text-blue-700',
                            'dikirim'    => 'bg-indigo-100 text-indigo-700',
                            'selesai'    => 'bg-teal-100 text-teal-700',
                            'dibatalkan' => 'bg-red-100 text-red-600',
                            default      => 'bg-gray-100 text-gray-600',
                        };
                        $statusLabel = match($pesanan->status_pesanan) {
                            'menunggu'   => 'Menunggu Konfirmasi Penjual',
                            'diproses'   => 'Sedang Diproses',
                            'dikirim'    => 'Dalam Pengiriman',
                            'selesai'    => 'Pesanan Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default      => ucfirst($pesanan->status_pesanan),
                        };
                    @endphp
                    <span class="text-xs font-bold px-2 py-1 rounded {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="px-5 py-3 space-y-3">
                @foreach($pesanan->items->take(2) as $item)
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
                        🛍️
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_produk }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $item->jumlah }}x · Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-800 flex-shrink-0">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
                @if($pesanan->items->count() > 2)
                <div class="text-xs text-gray-400 italic pl-17">
                    +{{ $pesanan->items->count() - 2 }} produk lainnya
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 bg-gray-50">
                <div class="text-xs text-gray-400">
                    {{ $pesanan->created_at->format('d M Y') }} ·
                    {{ $pesanan->kode_pesanan }}
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-sm">
                        Total: <strong class="text-gray-800">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                    </div>
                    <a href="{{ route('pembeli.pesanan.show', $pesanan->id) }}"
                        class="text-xs bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                        Detail →
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">📭</div>
            <p class="font-semibold text-gray-600">Belum ada pesanan</p>
            <a href="{{ route('produk.index') }}"
                class="inline-block mt-3 bg-blue-600 text-white font-bold px-6 py-2.5 rounded-lg hover:bg-blue-700 transition text-sm">
                Mulai Belanja →
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $pesanans->withQueryString()->links() }}</div>

</div>
@endsection