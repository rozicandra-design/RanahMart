@extends('layouts.dashboard')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('penjual.pesanan.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Pesanan
    </a>

    {{-- Info Pesanan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="font-bold text-gray-800 text-base">{{ $pesanan->kode_pesanan }}</div>
                <div class="text-xs text-gray-400 mt-0.5">
                    {{ $pesanan->created_at->format('d M Y H:i') }}
                </div>
            </div>
            @php
                $statusClass = match($pesanan->status_pesanan) {
                    'menunggu'    => 'bg-amber-100 text-amber-700',
                    'diproses'    => 'bg-blue-100 text-blue-700',
                    'dikirim'     => 'bg-indigo-100 text-indigo-700',
                    'selesai'     => 'bg-teal-100 text-teal-700',
                    'dibatalkan'  => 'bg-red-100 text-red-600',
                    default       => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="text-sm font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                {{ ucfirst($pesanan->status_pesanan) }}
            </span>
        </div>

        {{-- Info Pembeli --}}
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Data Pembeli</div>
            <div class="text-sm font-semibold text-gray-800">{{ $pesanan->pembeli->nama_lengkap ?? '' }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $pesanan->pembeli->no_hp ?? '' }}</div>
        </div>

        {{-- Alamat Pengiriman --}}
        @if($pesanan->alamat)
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Pengiriman</div>
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

        {{-- Item --}}
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

        {{-- Total --}}
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
            <div class="flex justify-between text-sm text-green-600">
                <span>Diskon Voucher</span>
                <span>-Rp {{ number_format($pesanan->diskon_voucher, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-base font-bold text-gray-800 pt-1 border-t border-gray-200">
                <span>Total</span>
                <span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($pesanan->catatan)
        <div class="mt-3 text-xs text-gray-500 bg-amber-50 p-2 rounded-lg">
            📝 Catatan: {{ $pesanan->catatan }}
        </div>
        @endif
    </div>

    {{-- Info resi (jika sudah dikirim) --}}
    @if($pesanan->no_resi)
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-4">
        <div class="text-xs font-bold text-teal-700 uppercase tracking-wider mb-1">Info Pengiriman</div>
        <div class="text-sm text-teal-800">
            🚚 <strong>{{ $pesanan->jasa_kirim }}</strong> · No. Resi: <strong>{{ $pesanan->no_resi }}</strong>
        </div>
        <div class="text-xs text-teal-600 mt-0.5">
            Dikirim: {{ $pesanan->dikirim_at?->format('d M Y H:i') }}
        </div>
    </div>
    @endif

    {{-- Aksi --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Aksi</div>

        @if($pesanan->status_pesanan === 'menunggu')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('penjual.pesanan.konfirmasi', $pesanan->id) }}" class="flex-1">
                @csrf @method('PATCH')
                <button class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                    ✓ Konfirmasi & Proses Pesanan
                </button>
            </form>
            <form method="POST" action="{{ route('penjual.pesanan.tolak', $pesanan->id) }}"
                onsubmit="return confirm('Yakin ingin menolak pesanan ini?')" class="flex-1">
                @csrf @method('PATCH')
                <button class="w-full bg-red-100 text-red-700 font-bold py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                    ✕ Tolak Pesanan
                </button>
            </form>
        </div>

        @elseif($pesanan->status_pesanan === 'diproses')
        <form method="POST" action="{{ route('penjual.pesanan.kirim', $pesanan->id) }}" class="space-y-3">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jasa Pengiriman *</label>
                    <select name="jasa_kirim" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih jasa kirim</option>
                        @foreach(['JNE', 'J&T Express', 'SiCepat', 'Anteraja', 'Pos Indonesia', 'Ninja Xpress', 'Gosend'] as $jk)
                        <option value="{{ $jk }}">{{ $jk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Resi *</label>
                    <input type="text" name="no_resi" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Masukkan nomor resi">
                </div>
            </div>
            <button type="submit"
                class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                🚚 Input Resi & Tandai Dikirim
            </button>
        </form>

        @elseif($pesanan->status_pesanan === 'selesai')
        <div class="text-center py-4 text-teal-600">
            <div class="text-3xl mb-2">✅</div>
            <p class="font-semibold">Pesanan selesai</p>
            <p class="text-xs text-gray-400 mt-0.5">
                Selesai: {{ $pesanan->selesai_at?->format('d M Y H:i') }}
            </p>
        </div>

        @elseif($pesanan->status_pesanan === 'dibatalkan')
        <div class="text-center py-4 text-red-500">
            <div class="text-3xl mb-2">❌</div>
            <p class="font-semibold">Pesanan dibatalkan</p>
        </div>
        @endif
    </div>

</div>
</div>
@endsection