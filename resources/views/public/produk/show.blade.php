@extends('layouts.app')
@section('title', $produk->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('produk.kategori', $produk->kategori) }}"
            class="hover:text-red-600">{{ $produk->nama_kategori }}</a>
        <span>/</span>
        <span class="text-gray-600 truncate max-w-xs">{{ $produk->nama }}</span>
    </nav>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="grid md:grid-cols-2">

            {{-- Foto --}}
            <div class="h-80 bg-gray-100 flex items-center justify-center relative">
                @if($produk->fotoUtama)
                    <img src="{{ Storage::url($produk->fotoUtama->path) }}"
                        alt="{{ $produk->nama }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="text-8xl">🛍️</div>
                @endif
                @if($produk->harga_coret && $produk->harga_coret > $produk->harga)
                    @php $diskon = (int) round((1 - $produk->harga / $produk->harga_coret) * 100); @endphp
                    <span class="absolute top-4 left-4 bg-red-600 text-white font-bold px-3 py-1 rounded-lg text-sm">
                        -{{ $diskon }}%
                    </span>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-6">
                {{-- Toko --}}
                <a href="{{ route('toko.show', $produk->toko->slug ?? '#') }}"
                    class="flex items-center gap-2 mb-4 group">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs">
                        {{ strtoupper(substr($produk->toko->nama_toko ?? 'T', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-700 group-hover:text-teal-600 transition">
                            {{ $produk->toko->nama_toko ?? '' }}
                        </div>
                        @if($produk->toko->terverifikasi_dinas ?? false)
                            <div class="text-xs text-teal-600">✓ Terverifikasi Dinas</div>
                        @endif
                    </div>
                </a>

                <h1 class="text-xl font-bold text-gray-800 mb-3 leading-snug">
                    {{ $produk->nama }}
                </h1>

                {{-- Harga --}}
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </span>
                    @if($produk->harga_coret)
                        <span class="text-sm text-gray-400 line-through">
                            Rp {{ number_format($produk->harga_coret, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                {{-- Rating & stok --}}
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-5">
                    <span>★ {{ $produk->rating }} ({{ $produk->total_ulasan }} ulasan)</span>
                    <span>·</span>
                    <span>{{ $produk->total_terjual }} terjual</span>
                    <span>·</span>
                    <span class="{{ $produk->stok > 0 ? 'text-teal-600' : 'text-red-500' }} font-semibold">
                        Stok: {{ $produk->stok > 0 ? $produk->stok : 'Habis' }}
                    </span>
                </div>

                {{-- Deskripsi --}}
                @if($produk->deskripsi)
                <p class="text-sm text-gray-600 leading-relaxed mb-5 border-t border-gray-100 pt-4">
                    {{ $produk->deskripsi }}
                </p>
                @endif

                {{-- Detail --}}
                @if($produk->berat)
                <div class="text-xs text-gray-400 mb-4">Berat: {{ $produk->berat }}gr</div>
                @endif

                {{-- Tombol aksi --}}
                @if($produk->stok > 0)
                    @auth
                        @if(auth()->user()->isPembeli())
                        <form method="POST" action="{{ route('pembeli.keranjang.tambah') }}">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <input type="hidden" name="jumlah" value="1">
                            <div class="flex gap-3">
                                <button class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition">
                                    🛒 Tambah ke Keranjang
                                </button>
                                <form method="POST" action="{{ route('pembeli.wishlist.toggle', $produk->id) }}" class="inline">
                                    @csrf
                                    <button class="w-12 h-12 bg-gray-100 hover:bg-red-50 hover:text-red-600 rounded-xl flex items-center justify-center transition text-xl">
                                        ♡
                                    </button>
                                </form>
                            </div>
                        </form>
                        @else
                        <div class="bg-gray-100 text-gray-500 text-center py-3 rounded-xl text-sm">
                            Login sebagai pembeli untuk membeli
                        </div>
                        @endif
                    @else
                    <a href="{{ route('login') }}"
                        class="block w-full bg-red-600 text-white font-bold py-3 rounded-xl text-center hover:bg-red-700 transition">
                        Masuk untuk Beli
                    </a>
                    @endauth
                @else
                    <div class="w-full bg-gray-200 text-gray-500 font-bold py-3 rounded-xl text-center">
                        Stok Habis
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Foto tambahan --}}
    @if($produk->fotos->count() > 1)
    <div class="flex gap-3 mt-4">
        @foreach($produk->fotos as $foto)
        <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden border-2 border-gray-200 hover:border-red-400 cursor-pointer transition">
            <img src="{{ Storage::url($foto->path) }}" class="w-full h-full object-cover">
        </div>
        @endforeach
    </div>
    @endif

    {{-- Ulasan --}}
    @if($produk->ulasans->count())
    <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            Ulasan Pembeli ({{ $produk->ulasans->count() }})
        </h2>
        <div class="space-y-4">
            @foreach($produk->ulasans->take(5) as $ulasan)
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">
                        {{ strtoupper(substr($ulasan->user->nama_depan ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-700">
                            {{ $ulasan->user->nama_depan ?? 'Pembeli' }}
                        </div>
                        <div class="text-xs text-amber-500">
                            {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                        </div>
                    </div>
                    <div class="ml-auto text-xs text-gray-400">
                        {{ $ulasan->created_at->diffForHumans() }}
                    </div>
                </div>
                @if($ulasan->komentar)
                <p class="text-sm text-gray-600">{{ $ulasan->komentar }}</p>
                @endif
                @if($ulasan->balasan)
                <div class="mt-3 bg-gray-50 border-l-2 border-teal-400 pl-3 py-2 rounded-r-lg">
                    <div class="text-xs font-semibold text-teal-700 mb-1">
                        Balasan Penjual:
                    </div>
                    <p class="text-xs text-gray-600">{{ $ulasan->balasan }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Produk Terkait --}}
    @if(isset($produkTerkait) && $produkTerkait->count())
    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Produk Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($produkTerkait as $p)
                @include('components.produk-card', ['produk' => $p])
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection