@extends('layouts.dashboard')
@section('title', 'Keranjang Belanja')
@section('page-title', 'Keranjang Belanja')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

@if(isset($keranjangs) && collect($keranjangs)->flatten(1)->count())
<div class="grid md:grid-cols-3 gap-5">

    {{-- Kiri: List Keranjang --}}
    <div class="md:col-span-2 space-y-4">
        @foreach($keranjangs as $tokoId => $items)
        @php $toko = $items->first()->produk->toko; @endphp
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

            {{-- Header Toko --}}
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-gray-50">
                <div class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs">
                    {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                </div>
                <div class="text-sm font-semibold text-gray-800">{{ $toko->nama_toko }}</div>
                @if($toko->terverifikasi_dinas)
                <span class="text-xs bg-teal-100 text-teal-700 font-bold px-1.5 py-0.5 rounded">✓ Dinas</span>
                @endif
            </div>

            {{-- Item --}}
            <div class="divide-y divide-gray-100">
                @foreach($items as $item)
                <div class="flex items-start gap-3 p-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0 overflow-hidden">
                        @if($item->produk->fotoUtama)
                            <img src="{{ Storage::url($item->produk->fotoUtama->path) }}"
                                class="w-full h-full object-cover">
                        @else
                            🛍️
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('produk.show', $item->produk->slug) }}"
                            class="text-sm font-semibold text-gray-800 hover:text-blue-600 truncate block">
                            {{ $item->produk->nama }}
                        </a>
                        <div class="text-sm font-bold text-red-600 mt-1">
                            Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Stok: {{ $item->produk->stok }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        {{-- Qty Control --}}
                        <form method="POST" action="{{ route('pembeli.keranjang.update', $item->id) }}"
                            class="flex items-center gap-1">
                            @csrf @method('PATCH')
                            <button type="submit" name="jumlah" value="{{ max(1, $item->jumlah - 1) }}"
                                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-sm font-bold transition">
                                −
                            </button>
                            <span class="w-8 text-center text-sm font-bold">{{ $item->jumlah }}</span>
                            <button type="submit" name="jumlah" value="{{ min($item->produk->stok, $item->jumlah + 1) }}"
                                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-sm font-bold transition">
                                +
                            </button>
                        </form>
                        <div class="text-sm font-bold text-gray-800">
                            Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}
                        </div>
                        <form method="POST" action="{{ route('pembeli.keranjang.hapus', $item->id) }}"
                            onsubmit="return confirm('Hapus item ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600 font-semibold transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Kanan: Summary --}}
    <div class="space-y-4">
        {{-- Voucher --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Kode Voucher / Promo</h3>
            <div class="flex gap-2">
                <input type="text" id="kode-voucher" placeholder="Masukkan kode voucher"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                <button onclick="cekVoucher()"
                    class="bg-blue-600 text-white font-bold px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                    Pakai
                </button>
            </div>
            <div id="voucher-info" class="mt-2 text-xs hidden"></div>
        </div>

        {{-- Ringkasan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <h3 class="font-bold text-gray-800 mb-3">Ringkasan Belanja</h3>
            @php
                $subtotal = 0;
                foreach($keranjangs as $items) {
                    foreach($items as $item) {
                        $subtotal += $item->jumlah * $item->produk->harga;
                    }
                }
                $ongkir = 12000;
                $total = $subtotal + $ongkir;
            @endphp
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Estimasi Ongkir</span>
                    <span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>
                <div id="diskon-baris" class="flex justify-between text-teal-600 hidden">
                    <span>Diskon Voucher</span>
                    <span id="diskon-amount">-Rp 0</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-200 text-base">
                    <span>Total</span>
                    <span id="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('produk.index') }}"
                class="block w-full mt-4 bg-blue-600 text-white font-bold py-3 rounded-xl text-center text-sm hover:bg-blue-700 transition">
                Lanjut ke Checkout →
            </a>

            <form method="POST" action="{{ route('pembeli.keranjang.kosongkan') }}"
                onsubmit="return confirm('Kosongkan semua keranjang?')" class="mt-2">
                @csrf @method('DELETE')
                <button class="w-full text-xs text-gray-400 hover:text-red-500 py-1 transition">
                    Kosongkan Keranjang
                </button>
            </form>
        </div>
    </div>

</div>
@else
<div class="text-center py-20 bg-white border border-gray-200 rounded-xl text-gray-400">
    <div class="text-7xl mb-4">🛒</div>
    <p class="font-semibold text-gray-600 text-lg">Keranjang masih kosong</p>
    <p class="text-sm mt-1">Yuk mulai belanja produk UMKM Padang!</p>
    <a href="{{ route('produk.index') }}"
        class="inline-block mt-5 bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 transition">
        Mulai Belanja
    </a>
</div>
@endif

</div>

@push('scripts')
<script>
function cekVoucher() {
    const kode = document.getElementById('kode-voucher').value.trim();
    if (!kode) return;
    const info = document.getElementById('voucher-info');
    info.className = 'mt-2 text-xs text-blue-600';
    info.textContent = 'Mengecek voucher...';
    info.classList.remove('hidden');
    fetch("{{ route('pembeli.voucher.validasi') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ kode, total: {{ $total ?? 0 }} })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valid) {
            info.className = 'mt-2 text-xs text-teal-600 font-semibold';
            info.textContent = '✓ Voucher valid! Hemat Rp ' + data.diskon.toLocaleString('id-ID');
            const diskonBaris = document.getElementById('diskon-baris');
            diskonBaris.classList.remove('hidden');
            document.getElementById('diskon-amount').textContent = '-Rp ' + data.diskon.toLocaleString('id-ID');
            document.getElementById('total-amount').textContent = 'Rp ' + ({{ $total ?? 0 }} - data.diskon).toLocaleString('id-ID');
        } else {
            info.className = 'mt-2 text-xs text-red-500';
            info.textContent = '✕ ' + data.message;
        }
    });
}
</script>
@endpush
@endsection