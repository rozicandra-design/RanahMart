@extends('layouts.dashboard')
@section('title', 'Wishlist')
@section('page-title', 'Wishlist Saya')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    @if(isset($wishlists) && $wishlists->count())
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-gray-500">{{ $wishlists->count() }} produk di wishlist</div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($wishlists as $wishlist)
        @if($wishlist->produk)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition group relative">

            {{-- Tombol hapus wishlist --}}
            <form method="POST" action="{{ route('pembeli.wishlist.toggle', $wishlist->produk->id) }}"
                class="absolute top-2 right-2 z-10">
                @csrf
                <button class="w-7 h-7 bg-white/90 hover:bg-red-50 rounded-full flex items-center justify-center text-red-500 hover:text-red-600 transition shadow-sm">
                    ❤️
                </button>
            </form>

            <a href="{{ route('produk.show', $wishlist->produk->slug) }}">
                <div class="h-36 bg-gray-100 flex items-center justify-center text-4xl overflow-hidden">
                    @if($wishlist->produk->fotoUtama)
                        <img src="{{ Storage::url($wishlist->produk->fotoUtama->path) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        🛍️
                    @endif
                </div>
                <div class="p-3">
                    <div class="text-xs text-gray-400 truncate mb-0.5">
                        {{ $wishlist->produk->toko->nama_toko ?? '' }}
                    </div>
                    <div class="text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600">
                        {{ $wishlist->produk->nama }}
                    </div>
                    <div class="text-sm font-bold text-red-600 mt-1">
                        Rp {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        ★ {{ $wishlist->produk->rating }} · {{ $wishlist->produk->total_terjual }} terjual
                    </div>
                </div>
            </a>

            {{-- Tambah ke keranjang --}}
            @if($wishlist->produk->stok > 0)
            <div class="px-3 pb-3">
                <form method="POST" action="{{ route('pembeli.keranjang.tambah') }}">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $wishlist->produk->id }}">
                    <input type="hidden" name="jumlah" value="1">
                    <button class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg text-xs hover:bg-blue-700 transition">
                        + Tambah ke Keranjang
                    </button>
                </form>
            </div>
            @else
            <div class="px-3 pb-3">
                <div class="w-full bg-gray-100 text-gray-400 font-bold py-2 rounded-lg text-xs text-center">
                    Stok Habis
                </div>
            </div>
            @endif

        </div>
        @endif
        @endforeach
    </div>
    @else
    <div class="text-center py-20 bg-white border border-gray-200 rounded-xl text-gray-400">
        <div class="text-7xl mb-4">❤️</div>
        <p class="font-semibold text-gray-600 text-lg">Wishlist masih kosong</p>
        <p class="text-sm mt-1">Simpan produk favorit kamu di sini</p>
        <a href="{{ route('produk.index') }}"
            class="inline-block mt-5 bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 transition">
            Jelajahi Produk
        </a>
    </div>
    @endif

</div>
@endsection