@extends('layouts.app')
@section('title', 'Produk UMKM Padang')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">
            @if(request('q'))
                Hasil: "<span class="text-red-600">{{ request('q') }}</span>"
            @elseif(isset($kategori))
                Kategori: <span class="text-red-600">{{ nama_kategori($kategori) }}</span>
            @else
                Semua Produk UMKM Padang
            @endif
        </h1>
        <span class="text-sm text-gray-500">{{ $produks->total() }} produk</span>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-6 flex-wrap items-center">
        @if(request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
        @endif

        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white"
            onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                <option value="{{ $slug }}" {{ request('kategori') == $slug ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <select name="kecamatan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white"
            onchange="this.form.submit()">
            <option value="">Semua Kecamatan</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
                <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                    {{ $kec }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white"
            onchange="this.form.submit()">
            <option value="">Urutkan</option>
            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="terbaru"  {{ request('sort') == 'terbaru'  ? 'selected' : '' }}>Terbaru</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
        </select>

        <div class="flex gap-2 ml-auto">
            <input type="number" name="min_harga" value="{{ request('min_harga') }}"
                placeholder="Harga min" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-28">
            <input type="number" name="max_harga" value="{{ request('max_harga') }}"
                placeholder="Harga maks" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-28">
            <button class="bg-red-600 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                Cari
            </button>
        </div>
    </form>

    {{-- Grid Produk --}}
    @if($produks->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($produks as $produk)
                @include('components.produk-card', ['produk' => $produk])
            @endforeach
        </div>
        <div class="mt-8">
            {{ $produks->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16 text-gray-400">
            <div class="text-6xl mb-4">🔍</div>
            <p class="font-semibold text-gray-600 text-lg">Produk tidak ditemukan</p>
            <p class="text-sm mt-1">Coba kata kunci atau kategori lain</p>
            <a href="{{ route('produk.index') }}"
                class="inline-block mt-4 text-red-600 font-semibold hover:underline text-sm">
                Lihat semua produk
            </a>
        </div>
    @endif

</div>
@endsection