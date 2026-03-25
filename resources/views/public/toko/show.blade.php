@extends('layouts.app')
@section('title', $toko->nama_toko)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Profil Toko --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
        {{-- Banner --}}
        <div class="h-32 bg-gradient-to-r from-teal-600 to-teal-700 relative">
            @if($toko->banner)
                <img src="{{ Storage::url($toko->banner) }}"
                    class="w-full h-full object-cover">
            @endif
        </div>

        <div class="px-6 pb-6">
            {{-- Logo --}}
            <div class="flex items-end gap-4 -mt-8 mb-4">
                <div class="w-16 h-16 rounded-xl bg-white border-4 border-white shadow flex items-center justify-center text-2xl font-bold text-teal-700 bg-teal-100">
                    @if($toko->logo)
                        <img src="{{ Storage::url($toko->logo) }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                    @endif
                </div>
                <div class="pb-1">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-800">{{ $toko->nama_toko }}</h1>
                        @if($toko->terverifikasi_dinas)
                        <span class="bg-teal-100 text-teal-700 text-xs font-bold px-2 py-0.5 rounded">
                            ✓ Terverifikasi Dinas
                        </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 mt-0.5">
                        {{ $toko->kategori_friendly }} · {{ $toko->kecamatan }}
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-6 text-sm text-gray-600 mb-4">
                <div class="flex items-center gap-1">
                    <span class="text-amber-500 font-bold">★ {{ $toko->rating }}</span>
                    <span class="text-gray-400">({{ $toko->total_ulasan }} ulasan)</span>
                </div>
                <div>{{ $toko->total_pesanan }} pesanan selesai</div>
                @if($toko->jam_operasional)
                <div>🕐 {{ $toko->jam_operasional }}</div>
                @endif
                @if($toko->no_hp)
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $toko->no_hp) }}"
                    target="_blank"
                    class="flex items-center gap-1 bg-green-50 text-green-700 font-semibold px-3 py-1 rounded-lg text-xs hover:bg-green-100 transition">
                    💬 Chat WhatsApp
                </a>
                @endif
            </div>

            @if($toko->deskripsi)
            <p class="text-sm text-gray-600 leading-relaxed">{{ $toko->deskripsi }}</p>
            @endif
        </div>
    </div>

    {{-- Produk Toko --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">
            Semua Produk
            <span class="text-gray-400 font-normal text-base">({{ $toko->produksAktif->count() }})</span>
        </h2>
    </div>

    @if($toko->produksAktif->count())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($toko->produksAktif as $produk)
            @include('components.produk-card', ['produk' => $produk])
        @endforeach
    </div>
    @else
    <div class="text-center py-16 text-gray-400 bg-white border border-gray-200 rounded-xl">
        <div class="text-5xl mb-3">📦</div>
        <p class="font-semibold">Toko ini belum memiliki produk</p>
    </div>
    @endif

</div>
@endsection