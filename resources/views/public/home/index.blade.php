@extends('layouts.app')
@section('title', 'Beranda — Platform UMKM Kota Padang')

@section('content')

{{-- ─── HERO ─── --}}
<div class="bg-gradient-to-br from-red-600 to-red-700 text-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <span class="text-xs font-bold bg-white/20 inline-block px-4 py-1.5 rounded-full mb-5 uppercase tracking-wider">
            Platform Resmi UMKM Kota Padang
        </span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
            Belanja Produk Lokal<br>
            <span class="text-amber-300">Minangkabau</span> Terbaik
        </h1>
        <p class="text-white/80 mb-8 text-lg max-w-xl mx-auto leading-relaxed">
            Dukung ribuan UMKM Padang — dari Rendang, Batik Tanah Liek,
            hingga kerajinan tangan asli Minang.
        </p>
        <div class="flex gap-3 justify-center flex-wrap">
            <a href="{{ route('produk.index') }}"
                class="bg-amber-400 text-amber-900 font-bold px-7 py-3 rounded-full hover:bg-amber-300 transition shadow">
                Jelajahi Produk
            </a>
            <a href="{{ route('register.umkm') }}"
                class="bg-white/15 border border-white/30 text-white font-semibold px-7 py-3 rounded-full hover:bg-white/25 transition">
                Daftarkan UMKM →
            </a>
        </div>
        <div class="flex justify-center gap-8 mt-12 pt-8 border-t border-white/20">
            <div class="text-center"><div class="text-2xl font-bold">1.280</div><div class="text-xs text-white/60 uppercase tracking-wider mt-1">UMKM Aktif</div></div>
            <div class="text-center"><div class="text-2xl font-bold">48K+</div><div class="text-xs text-white/60 uppercase tracking-wider mt-1">Produk</div></div>
            <div class="text-center"><div class="text-2xl font-bold">11</div><div class="text-xs text-white/60 uppercase tracking-wider mt-1">Kecamatan</div></div>
            <div class="text-center"><div class="text-2xl font-bold">4.8 ★</div><div class="text-xs text-white/60 uppercase tracking-wider mt-1">Rating</div></div>
        </div>
    </div>
</div>

{{-- ─── IKLAN BANNER ─── --}}
@if(!empty($iklans) && $iklans->count())
<div class="max-w-5xl mx-auto px-4 pt-8">
    <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-3 flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-red-500 rounded-full inline-block"></span>
        Iklan Sponsor
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($iklans as $iklan)
        <div class="rounded-xl overflow-hidden shadow-sm"
            style="background: {{ $iklan->warna_tema ?? '#C0392B' }}">
            <div class="p-5 text-white">
                <div class="text-xs font-semibold opacity-70 mb-1">
                    {{ optional($iklan->toko)->nama_toko }}
                </div>
                <h3 class="text-lg font-bold mb-1">{{ $iklan->judul }}</h3>
                <p class="text-sm opacity-80 mb-4">{{ $iklan->sub_judul ?? '' }}</p>
                @if(optional($iklan->toko)->slug)
                <a href="{{ route('toko.show', $iklan->toko->slug) }}"
                    class="inline-block bg-white text-gray-800 font-bold px-4 py-2 rounded-lg text-sm hover:bg-gray-100 transition">
                    {{ $iklan->teks_cta ?? 'Lihat' }} →
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ─── KATEGORI ─── --}}
<div class="max-w-5xl mx-auto px-4 py-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Kategori Produk</h2>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('produk.index') }}"
            class="px-4 py-2 bg-red-600 text-white rounded-full text-sm font-semibold">
            Semua
        </a>
        @foreach(config('ranahmart.kategori_umkm', []) as $slug => $label)
        <a href="{{ route('produk.kategori', $slug) }}"
            class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-600 hover:border-red-400 hover:text-red-600 transition">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- ─── PRODUK TERLARIS ─── --}}
<div class="max-w-5xl mx-auto px-4 pb-4">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-800">🔥 Produk Terlaris</h2>
        <a href="{{ route('produk.index') }}"
            class="text-red-600 text-sm font-semibold hover:underline">Lihat semua →</a>
    </div>
    @if(!empty($produkLaris) && $produkLaris->count())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($produkLaris as $produk)
        <a href="{{ route('produk.show', $produk->slug) }}"
            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition group block">
            <div class="h-36 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                @if($produk->fotoUtama)
                    <img src="{{ Storage::url($produk->fotoUtama->path) }}"
                        alt="{{ $produk->nama }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="text-4xl">🛍️</div>
                @endif
                @if($produk->harga_coret && $produk->harga_coret > $produk->harga)
                    @php $diskon = (int) round((1 - $produk->harga / $produk->harga_coret) * 100); @endphp
                    <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                        -{{ $diskon }}%
                    </span>
                @endif
            </div>
            <div class="p-3">
                <div class="text-xs text-gray-400 truncate mb-0.5">
                    {{ optional($produk->toko)->nama_toko }}
                </div>
                <div class="text-sm font-semibold text-gray-800 truncate group-hover:text-red-600 leading-snug">
                    {{ $produk->nama }}
                </div>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <div class="text-sm font-bold text-red-600">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>
                    @if($produk->harga_coret)
                    <div class="text-xs text-gray-400 line-through">
                        Rp {{ number_format($produk->harga_coret, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    ★ {{ $produk->rating ?? '0.0' }} · {{ number_format($produk->total_terjual ?? 0) }} terjual
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 text-gray-400 bg-white border border-gray-200 rounded-xl">
        <div class="text-5xl mb-3">🏪</div>
        <p class="font-semibold">Belum ada produk tersedia</p>
        <p class="text-sm mt-1">Jadilah UMKM pertama di RanahMart!</p>
        <a href="{{ route('register.umkm') }}"
            class="inline-block mt-4 bg-red-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-red-700 transition text-sm">
            Daftar UMKM Sekarang
        </a>
    </div>
    @endif
</div>

{{-- ─── BANNER CTA ─── --}}
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-red-600 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-white">
            <div class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">⚡ Promo Hari Ini</div>
            <h3 class="text-2xl font-bold mb-1">Diskon hingga 50%</h3>
            <p class="text-white/80 text-sm">Produk UMKM pilihan dengan harga spesial</p>
        </div>
        <a href="{{ route('produk.index', ['sort' => 'termurah']) }}"
            class="flex-shrink-0 bg-white text-red-600 font-bold px-6 py-3 rounded-xl hover:bg-gray-50 transition">
            Belanja Sekarang →
        </a>
    </div>
</div>

{{-- ─── UMKM TERVERIFIKASI ─── --}}
@if(!empty($umkmFeatured) && $umkmFeatured->count())
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-800">🏆 UMKM Terverifikasi Dinas</h2>
        <a href="{{ route('umkm.index') }}"
            class="text-red-600 text-sm font-semibold hover:underline">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($umkmFeatured as $toko)
        @if(!$toko->slug) @continue @endif
        <a href="{{ route('toko.show', $toko->slug) }}"
            class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-lg mx-auto mb-3">
                {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
            </div>
            <div class="text-xs font-semibold text-gray-800 group-hover:text-red-600 transition truncate">
                {{ $toko->nama_toko }}
            </div>
            <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $toko->kecamatan }}</div>
            <div class="inline-block bg-teal-100 text-teal-700 text-xs font-bold px-2 py-0.5 rounded mt-2">
                ✓ Dinas
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ─── CTA DAFTAR UMKM ─── --}}
<div class="max-w-5xl mx-auto px-4 py-6 mb-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-teal-600 rounded-2xl p-6 text-white">
            <div class="text-2xl mb-2">🏪</div>
            <h3 class="text-lg font-bold mb-1">Punya Usaha Lokal?</h3>
            <p class="text-teal-100 text-sm mb-4">Daftarkan UMKM kamu dan jangkau ribuan pembeli se-Kota Padang.</p>
            <a href="{{ route('register.umkm') }}"
                class="inline-block bg-white text-teal-700 font-bold px-5 py-2 rounded-lg text-sm hover:bg-teal-50 transition">
                Daftar Gratis →
            </a>
        </div>
        <div class="bg-amber-500 rounded-2xl p-6 text-white">
            <div class="text-2xl mb-2">📢</div>
            <h3 class="text-lg font-bold mb-1">Pasang Iklan UMKM</h3>
            <p class="text-amber-100 text-sm mb-4">Tampilkan produk kamu di banner utama dan jangkau lebih banyak pembeli.</p>
            <a href="{{ route('login') }}"
                class="inline-block bg-white text-amber-700 font-bold px-5 py-2 rounded-lg text-sm hover:bg-amber-50 transition">
                Mulai Beriklan →
            </a>
        </div>
    </div>
</div>

@endsection