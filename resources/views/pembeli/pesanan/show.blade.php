@extends('layouts.dashboard')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('pembeli.pesanan.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Pesanan
    </a>

    {{-- Tracker Status --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-bold text-gray-800">{{ $pesanan->kode_pesanan }}</div>
            <div class="text-xs text-gray-400">{{ $pesanan->created_at->format('d M Y H:i') }}</div>
        </div>

        {{-- Progress Steps --}}
        @php
        $steps = [
            ['key' => 'menunggu',   'label' => 'Pesanan Dibuat',     'icon' => '📋'],
            ['key' => 'diproses',   'label' => 'Dikonfirmasi Penjual','icon' => '✅'],
            ['key' => 'dikirim',    'label' => 'Dikirim',            'icon' => '🚚'],
            ['key' => 'selesai',    'label' => 'Pesanan Selesai',     'icon' => '🎉'],
        ];
        $stepOrder = ['menunggu','dikonfirmasi','diproses','dikirim','selesai'];
        $currentIdx = array_search($pesanan->status_pesanan, $stepOrder) ?: 0;
        @endphp

        @if($pesanan->status_pesanan !== 'dibatalkan')
        <div class="flex items-center gap-1 mb-4">
            @foreach($steps as $i => $step)
            <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm
                        {{ $i <= $currentIdx ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                        {{ $step['icon'] }}
                    </div>
                    <div class="text-xs text-center mt-1.5 leading-tight max-w-16
                        {{ $i <= $currentIdx ? 'text-blue-600 font-semibold' : 'text-gray-400' }}">
                        {{ $step['label'] }}
                    </div>
                </div>
                @if($i < count($steps) - 1)
                <div class="flex-1 h-0.5 mx-1 mb-5
                    {{ $i < $currentIdx ? 'bg-blue-600' : 'bg-gray-200' }}">
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-center">
            <div class="text-2xl mb-1">❌</div>
            <div class="font-semibold text-red-700 text-sm">Pesanan Dibatalkan</div>
        </div>
        @endif

        {{-- Info Resi --}}
        @if($pesanan->no_resi)
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3 text-sm">
            🚚 <strong>{{ $pesanan->jasa_kirim }}</strong>
            · No. Resi: <span class="font-mono font-bold">{{ $pesanan->no_resi }}</span>
            <div class="text-xs text-indigo-500 mt-0.5">Dikirim: {{ $pesanan->dikirim_at?->format('d M Y H:i') }}</div>
        </div>
        @endif
    </div>

    {{-- Info Toko --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 font-bold">
            {{ strtoupper(substr($pesanan->toko->nama_toko ?? 'T', 0, 1)) }}
        </div>
        <div>
            <div class="text-sm font-bold text-gray-800">{{ $pesanan->toko->nama_toko ?? 'Toko' }}</div>
            <div class="text-xs text-gray-400">{{ $pesanan->toko->kecamatan ?? '' }}</div>
        </div>
        @if($pesanan->toko->no_hp ?? false)
        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pesanan->toko->no_hp) }}"
            target="_blank"
            class="ml-auto text-xs bg-green-100 text-green-700 font-bold px-3 py-1.5 rounded-lg hover:bg-green-600 hover:text-white transition">
            💬 Chat Penjual
        </a>
        @endif
    </div>

    {{-- Item Pesanan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <h3 class="font-bold text-gray-800 mb-3">Item Pesanan</h3>
        <div class="space-y-3">
            @foreach($pesanan->items as $item)
            <div class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-none">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                    🛍️
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_produk }}</div>
                    <div class="text-xs text-gray-400">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }} × {{ $item->jumlah }}
                    </div>
                </div>
                <div class="text-sm font-bold text-gray-800 flex-shrink-0">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan Harga --}}
        <div class="mt-3 bg-gray-50 rounded-lg p-3 space-y-1.5">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal ({{ $pesanan->items->count() }} item)</span>
                <span>Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
            </div>
            @if($pesanan->diskon_voucher > 0)
            <div class="flex justify-between text-sm text-teal-600">
                <span>Diskon Voucher</span>
                <span>-Rp {{ number_format($pesanan->diskon_voucher, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($pesanan->diskon_poin > 0)
            <div class="flex justify-between text-sm text-teal-600">
                <span>Diskon Poin</span>
                <span>-Rp {{ number_format($pesanan->diskon_poin, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-base font-bold text-gray-800 pt-2 border-t border-gray-200">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>
            <div class="text-xs text-gray-400">
                Metode: {{ ucfirst(str_replace('_', ' ', $pesanan->metode_bayar)) }}
            </div>
        </div>
    </div>

    {{-- Alamat Pengiriman --}}
    @if($pesanan->alamat)
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Pengiriman</h3>
        <div class="text-sm font-semibold text-gray-800">{{ $pesanan->alamat->nama_penerima }}</div>
        <div class="text-xs text-gray-500 mt-0.5">{{ $pesanan->alamat->no_hp }}</div>
        <div class="text-xs text-gray-600 mt-1 leading-relaxed">
            {{ $pesanan->alamat->alamat_lengkap }},
            {{ $pesanan->alamat->kecamatan }},
            {{ $pesanan->alamat->kota }}, {{ $pesanan->alamat->provinsi }}
            {{ $pesanan->alamat->kode_pos }}
        </div>
    </div>
    @endif

    {{-- Aksi --}}
    <div class="flex gap-3 flex-wrap">
        @if($pesanan->status_pesanan === 'dikirim')
        <form method="POST" action="{{ route('pembeli.pesanan.konfirmasi-terima', $pesanan->id) }}" class="flex-1">
            @csrf @method('PATCH')
            <button class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition text-sm">
                ✅ Konfirmasi Pesanan Diterima
            </button>
        </form>
        @endif

        @if($pesanan->status_pesanan === 'selesai' && !$pesanan->retur)
        <a href="{{ route('pembeli.ulasan.index') }}"
            class="flex-1 bg-amber-500 text-white font-bold py-3 rounded-xl hover:bg-amber-600 transition text-sm text-center">
            ⭐ Beri Ulasan
        </a>
        @endif

        @if(in_array($pesanan->status_pesanan, ['menunggu', 'dikonfirmasi']))
        <form method="POST" action="{{ route('pembeli.pesanan.batalkan', $pesanan->id) }}"
            onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')" class="flex-1">
            @csrf @method('PATCH')
            <button class="w-full bg-red-50 border border-red-200 text-red-600 font-bold py-3 rounded-xl hover:bg-red-600 hover:text-white transition text-sm">
                ✕ Batalkan Pesanan
            </button>
        </form>
        @endif

        @if($pesanan->status_pesanan === 'selesai' && !$pesanan->retur)
        <a href="{{ route('pembeli.retur.index') }}"
            class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition text-sm text-center">
            🔄 Ajukan Retur
        </a>
        @endif
    </div>

</div>
</div>
@endsection