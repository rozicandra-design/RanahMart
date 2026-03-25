@props(['produk'])

<a href="{{ route('produk.show', $produk->slug) }}"
    class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition group block">

    {{-- Foto --}}
    <div class="h-36 bg-gray-100 flex items-center justify-center overflow-hidden relative">
        @if($produk->fotoUtama)
            <img src="{{ Storage::url($produk->fotoUtama->path) }}"
                alt="{{ $produk->nama }}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="text-5xl">🛍️</div>
        @endif

        {{-- Badge diskon --}}
        @if($produk->harga_coret && $produk->harga_coret > $produk->harga)
            @php $diskon = (int) round((1 - $produk->harga / $produk->harga_coret) * 100); @endphp
            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                -{{ $diskon }}%
            </span>
        @endif

        {{-- Badge verifikasi --}}
        @if($produk->toko->terverifikasi_dinas ?? false)
            <span class="absolute top-2 right-2 bg-teal-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                ✓
            </span>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-3">
        <div class="text-xs text-gray-400 truncate mb-0.5">
            {{ $produk->toko->nama_toko ?? '' }}
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
        <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
            <span>★ {{ $produk->rating }}</span>
            <span>·</span>
            <span>{{ number_format($produk->total_terjual) }} terjual</span>
        </div>
    </div>
</a>