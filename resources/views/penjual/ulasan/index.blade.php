@extends('layouts.dashboard')
@section('title', 'Ulasan Pembeli')
@section('page-title', 'Ulasan Pembeli')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-amber-500">★ {{ $stats['rating'] ?? '0.0' }}</div>
            <div class="text-xs text-gray-400 mt-1">Rating Toko</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-xs text-gray-400 mt-1">Total Ulasan</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-red-500">{{ $stats['belum_dibalas'] ?? 0 }}</div>
            <div class="text-xs text-gray-400 mt-1">Belum Dibalas</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-teal-600">{{ $stats['bintang5'] ?? 0 }}</div>
            <div class="text-xs text-gray-400 mt-1">Bintang 5 ⭐</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex gap-2 mb-5 flex-wrap">
        <a href="{{ route('penjual.ulasan.index') }}"
            class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
            {{ !request('status') && !request('rating') ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-gray-600 border-gray-200 hover:border-teal-400' }}">
            Semua
        </a>
        <a href="{{ route('penjual.ulasan.index', ['status' => 'belum_dibalas']) }}"
            class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
            {{ request('status') === 'belum_dibalas' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-gray-600 border-gray-200 hover:border-teal-400' }}">
            Belum Dibalas
        </a>
        @foreach([5,4,3,2,1] as $r)
        <a href="{{ route('penjual.ulasan.index', ['rating' => $r]) }}"
            class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
            {{ request('rating') == $r ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-gray-600 border-gray-200 hover:border-amber-400' }}">
            {{ $r }}★
        </a>
        @endforeach
    </div>

    {{-- List ulasan --}}
    <div class="space-y-4">
        @forelse($ulasans as $ulasan)
        <div class="bg-white border border-gray-200 rounded-xl p-5
            {{ !$ulasan->balasan ? 'border-l-4 border-l-amber-400' : '' }}">

            {{-- Header ulasan --}}
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($ulasan->user->nama_depan ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $ulasan->user->nama_depan ?? 'Pembeli' }}
                        </div>
                        <div class="text-xs text-gray-400">{{ $ulasan->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="text-amber-500 text-sm font-bold flex-shrink-0">
                    {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                    <span class="text-gray-500 font-normal">({{ $ulasan->rating }}/5)</span>
                </div>
            </div>

            {{-- Produk --}}
            <div class="text-xs text-gray-400 mb-2">
                Produk: <span class="font-semibold text-gray-600">{{ $ulasan->produk->nama ?? '-' }}</span>
            </div>

            {{-- Komentar --}}
            @if($ulasan->komentar)
            <p class="text-sm text-gray-700 mb-3 leading-relaxed">{{ $ulasan->komentar }}</p>
            @endif

            {{-- Balasan --}}
            @if($ulasan->balasan)
            <div class="bg-teal-50 border-l-4 border-teal-400 pl-4 py-2 rounded-r-lg mb-3">
                <div class="text-xs font-bold text-teal-700 mb-1">Balasan Penjual:</div>
                <p class="text-sm text-teal-800">{{ $ulasan->balasan }}</p>
                <div class="text-xs text-teal-500 mt-1">{{ $ulasan->dibalas_at?->diffForHumans() }}</div>
            </div>
            @else
            {{-- Form balas --}}
            <form method="POST" action="{{ route('penjual.ulasan.balas', $ulasan->id) }}" class="mt-3">
                @csrf
                <div class="flex gap-2">
                    <textarea name="balasan" rows="2" required
                        placeholder="Tulis balasan untuk ulasan ini..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                    <button type="submit"
                        class="bg-teal-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm self-end flex-shrink-0">
                        Kirim
                    </button>
                </div>
            </form>
            @endif

        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">⭐</div>
            <p class="font-semibold text-gray-600">Belum ada ulasan</p>
            <p class="text-sm mt-1">Ulasan dari pembeli akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $ulasans->withQueryString()->links() }}</div>

</div>
@endsection