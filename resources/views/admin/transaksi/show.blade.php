@extends('layouts.dashboard')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('admin.transaksi.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Transaksi
    </a>

    {{-- Header --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <div class="font-bold text-gray-800 text-lg font-mono">{{ $pesanan->kode_pesanan }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $pesanan->created_at->format('d M Y H:i') }}</div>
            </div>
            @php
                $statusClass = match($pesanan->status_pesanan) {
                    'selesai'    => 'bg-teal-100 text-teal-700',
                    'dikirim'    => 'bg-indigo-100 text-indigo-700',
                    'diproses'   => 'bg-blue-100 text-blue-700',
                    'menunggu'   => 'bg-amber-100 text-amber-700',
                    'dibatalkan' => 'bg-red-100 text-red-600',
                    default      => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="text-sm font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                {{ ucfirst($pesanan->status_pesanan) }}
            </span>
        </div>

        {{-- Pihak --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Pembeli</div>
                <div class="text-sm font-semibold text-gray-800">{{ $pesanan->pembeli->nama_lengkap ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $pesanan->pembeli->email ?? '' }}</div>
                <div class="text-xs text-gray-500">{{ $pesanan->pembeli->no_hp ?? '' }}</div>
            </div>
            <div class="bg-teal-50 rounded-lg p-3">
                <div class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">Penjual</div>
                <div class="text-sm font-semibold text-gray-800">{{ $pesanan->toko->nama_toko ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $pesanan->toko->kecamatan ?? '' }}</div>
                <div class="text-xs text-gray-500">{{ $pesanan->toko->no_hp ?? '' }}</div>
            </div>
        </div>

        {{-- Alamat --}}
        @if($pesanan->alamat)
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Pengiriman</div>
            <div class="text-sm font-semibold text-gray-800">{{ $pesanan->alamat->nama_penerima }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $pesanan->alamat->no_hp }}</div>
            <div class="text-xs text-gray-600 mt-1 leading-relaxed">
                {{ $pesanan->alamat->alamat_lengkap }},
                {{ $pesanan->alamat->kecamatan }},
                {{ $pesanan->alamat->kota }}
                {{ $pesanan->alamat->kode_pos }}
            </div>
        </div>
        @endif

        {{-- Items --}}
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Item Pesanan</div>
        <div class="space-y-2 mb-4">
            @foreach($pesanan->items as $item)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ $item->nama_produk }}</div>
                    <div class="text-xs text-gray-400">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }} × {{ $item->jumlah }}
                    </div>
                </div>
                <div class="text-sm font-bold text-gray-800">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan harga --}}
        <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
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
            <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-200">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-amber-700 font-semibold">
                <span>Komisi Platform (3%)</span>
                <span>Rp {{ number_format($pesanan->komisi_platform, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-400">
                <span>Metode Bayar</span>
                <span>{{ ucfirst(str_replace('_', ' ', $pesanan->metode_bayar)) }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-400">
                <span>Status Bayar</span>
                <span class="font-semibold {{ $pesanan->status_bayar === 'lunas' ? 'text-teal-600' : 'text-amber-600' }}">
                    {{ ucfirst($pesanan->status_bayar) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <h3 class="font-bold text-gray-800 mb-4">Timeline Pesanan</h3>
        <div class="space-y-3">
            @foreach([
                ['label' => 'Pesanan Dibuat',      'time' => $pesanan->created_at,       'icon' => '📋'],
                ['label' => 'Pembayaran Diterima',  'time' => $pesanan->dibayar_at,       'icon' => '💳'],
                ['label' => 'Dikonfirmasi Penjual', 'time' => $pesanan->dikonfirmasi_at,  'icon' => '✅'],
                ['label' => 'Dikirim',              'time' => $pesanan->dikirim_at,       'icon' => '🚚'],
                ['label' => 'Pesanan Selesai',      'time' => $pesanan->selesai_at,       'icon' => '🎉'],
            ] as $tl)
            @if($tl['time'])
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-sm flex-shrink-0">
                    {{ $tl['icon'] }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ $tl['label'] }}</div>
                    <div class="text-xs text-gray-400">{{ $tl['time']->format('d M Y H:i') }}</div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        @if($pesanan->no_resi)
        <div class="mt-3 bg-indigo-50 border border-indigo-200 rounded-lg p-3 text-sm">
            🚚 <strong>{{ $pesanan->jasa_kirim }}</strong>
            · No. Resi: <span class="font-mono font-bold">{{ $pesanan->no_resi }}</span>
        </div>
        @endif
    </div>

    {{-- Retur (jika ada) --}}
    @if($pesanan->retur)
    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
        <h3 class="font-bold text-red-700 mb-3">Pengajuan Retur</h3>
        <div class="text-sm space-y-1">
            <div class="flex justify-between">
                <span class="text-gray-600">Kode Retur</span>
                <span class="font-semibold font-mono">{{ $pesanan->retur->kode_retur }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Alasan</span>
                <span class="font-semibold">{{ $pesanan->retur->alasan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Nilai Retur</span>
                <span class="font-semibold">Rp {{ number_format($pesanan->retur->nilai_retur, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status</span>
                <span class="font-semibold">{{ ucfirst($pesanan->retur->status) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.retur.index') }}"
            class="inline-block mt-3 text-xs text-red-600 font-semibold hover:underline">
            Lihat detail retur →
        </a>
    </div>
    @endif

</div>
</div>
@endsection