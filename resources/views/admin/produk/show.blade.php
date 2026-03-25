@extends('layouts.dashboard')
@section('title', 'Detail Produk')
@section('page-title', 'Review Produk')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('admin.produk.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Produk
    </a>

    {{-- Info Produk --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4">

        {{-- Foto --}}
        @if($produk->fotos->count())
        <div class="flex gap-2 p-4 bg-gray-50 border-b border-gray-200 overflow-x-auto">
            @foreach($produk->fotos as $foto)
            <div class="relative flex-shrink-0">
                <img src="{{ Storage::url($foto->path) }}"
                    class="w-24 h-24 object-cover rounded-lg border-2 {{ $foto->is_utama ? 'border-teal-500' : 'border-gray-200' }}">
                @if($foto->is_utama)
                <span class="absolute -top-1 -right-1 bg-teal-600 text-white text-xs px-1 rounded font-bold">Utama</span>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="h-32 bg-gray-100 flex items-center justify-center text-6xl border-b border-gray-200">🛍️</div>
        @endif

        <div class="p-5">
            {{-- Status --}}
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg leading-snug">{{ $produk->nama }}</h2>
                    <div class="text-xs text-gray-400 mt-0.5">{{ nama_kategori($produk->kategori) }}</div>
                </div>
                @php
                    $statusClass = match($produk->status) {
                        'aktif'    => 'bg-teal-100 text-teal-700',
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'ditolak'  => 'bg-red-100 text-red-600',
                        default    => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="text-sm font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                    {{ ucfirst($produk->status) }}
                </span>
            </div>

            {{-- Info Toko --}}
            <div class="bg-teal-50 rounded-lg p-3 mb-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($produk->toko->nama_toko ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-800">{{ $produk->toko->nama_toko ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $produk->toko->kecamatan ?? '' }} · {{ $produk->toko->user->email ?? '' }}</div>
                </div>
                <a href="{{ route('admin.umkm.show', $produk->toko_id) }}"
                    class="ml-auto text-xs text-teal-600 font-semibold hover:underline">
                    Lihat Toko →
                </a>
            </div>

            {{-- Detail --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">Harga Jual</div>
                    <div class="font-bold text-gray-800">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                    @if($produk->harga_coret)
                    <div class="text-xs text-gray-400 line-through">Rp {{ number_format($produk->harga_coret, 0, ',', '.') }}</div>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">Stok / Terjual</div>
                    <div class="font-bold text-gray-800">{{ $produk->stok }} / {{ $produk->total_terjual }}</div>
                </div>
                @if($produk->berat)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">Berat</div>
                    <div class="font-bold text-gray-800">{{ $produk->berat }}gr</div>
                </div>
                @endif
                @if($produk->sku)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">SKU</div>
                    <div class="font-bold text-gray-800 font-mono text-sm">{{ $produk->sku }}</div>
                </div>
                @endif
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">Rating</div>
                    <div class="font-bold text-amber-500">★ {{ $produk->rating }}</div>
                    <div class="text-xs text-gray-400">{{ $produk->total_ulasan }} ulasan</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="text-xs text-gray-400 mb-0.5">Dikirim</div>
                    <div class="font-bold text-gray-800">{{ $produk->created_at->format('d M Y') }}</div>
                </div>
            </div>

            @if($produk->deskripsi)
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi Produk</div>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $produk->deskripsi }}</p>
            </div>
            @endif

            @if($produk->catatan_review)
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <div class="text-xs font-bold text-red-700 mb-0.5">Catatan Review Sebelumnya:</div>
                <p class="text-sm text-red-800">{{ $produk->catatan_review }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Aksi Review --}}
    @if($produk->status === 'pending')
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <h3 class="font-bold text-gray-800 mb-4">Keputusan Review Produk</h3>

        <form method="POST" action="{{ route('admin.produk.setujui', $produk->id) }}" class="mb-3">
            @csrf @method('PATCH')
            <button class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                ✓ Setujui — Produk Langsung Tayang
            </button>
        </form>

        <form method="POST" action="{{ route('admin.produk.tolak', $produk->id) }}" class="space-y-2">
            @csrf @method('PATCH')
            <label class="block text-xs font-bold text-gray-700">Alasan Penolakan *</label>
            <textarea name="catatan_review" rows="2" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-400"
                placeholder="Contoh: Foto produk kurang jelas, deskripsi tidak sesuai kategori..."></textarea>
            <button class="w-full bg-red-100 text-red-700 font-bold py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                ✕ Tolak — Kembalikan ke Penjual
            </button>
        </form>
    </div>
    @elseif($produk->status === 'aktif')
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <h3 class="font-bold text-gray-800 mb-3">Aksi Moderasi</h3>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.produk.turunkan', $produk->id) }}"
                onsubmit="return confirm('Turunkan produk ini dari platform?')">
                @csrf @method('PATCH')
                <button class="bg-amber-100 text-amber-700 font-bold px-5 py-2 rounded-lg hover:bg-amber-600 hover:text-white transition text-sm">
                    ⬇ Turunkan Produk
                </button>
            </form>
            <form method="POST" action="{{ route('admin.produk.peringatkan', $produk->id) }}">
                @csrf @method('PATCH')
                <button class="bg-gray-100 text-gray-700 font-bold px-5 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                    ⚠ Kirim Peringatan ke Penjual
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Ulasan Produk --}}
    @if($produk->ulasans->count())
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-3">Ulasan ({{ $produk->ulasans->count() }})</h3>
        <div class="space-y-3">
            @foreach($produk->ulasans->take(5) as $ulasan)
            <div class="flex items-start gap-2 py-2 border-b border-gray-100 last:border-none">
                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr($ulasan->user->nama_depan ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="text-xs font-semibold text-gray-700">{{ $ulasan->user->nama_depan ?? 'Pembeli' }}</div>
                    <div class="text-amber-400 text-xs">{{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}</div>
                    @if($ulasan->komentar)
                    <p class="text-xs text-gray-600 mt-0.5">{{ $ulasan->komentar }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>
@endsection