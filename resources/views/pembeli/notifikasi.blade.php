@extends('layouts.dashboard')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div class="text-sm text-gray-500">
            <span class="font-bold text-gray-700">{{ $notifikasis->total() }}</span> notifikasi
        </div>
        @if($notifikasis->total() > 0)
        <form method="POST" action="{{ route('pembeli.notifikasi.baca-semua') }}">
            @csrf @method('PATCH')
            <button type="submit"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline transition">
                ✓ Tandai semua sudah dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- List Notifikasi --}}
    @php $perPage = 5; $allNotif = $notifikasis->items(); $total = count($allNotif); $totalPages = max(1, ceil($total / $perPage)); @endphp

    <div class="space-y-2" id="notif-wrapper">
        @forelse($notifikasis as $i => $notif)
        @php
            $icon = match($notif->tipe) {
                'success' => '✅', 'danger' => '❌', 'warning' => '⚠️', default => 'ℹ️',
            };
            $bgColor = match($notif->tipe) {
                'success' => 'bg-teal-100', 'danger' => 'bg-red-100',
                'warning' => 'bg-amber-100', default => 'bg-blue-100',
            };
        @endphp
        <div class="notif-item {{ $i >= $perPage ? 'hidden' : '' }} bg-white border border-gray-200 rounded-xl p-4 flex items-start gap-3 transition-all
            {{ !$notif->sudah_dibaca ? 'border-l-4 border-l-blue-500 bg-blue-50/30' : '' }}">

            <div class="w-10 h-10 rounded-full {{ $bgColor }} flex items-center justify-center text-base flex-shrink-0">
                {{ $icon }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-800">{{ $notif->judul }}</div>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                <div class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                @if($notif->url)
                <a href="{{ $notif->url }}" class="text-xs text-blue-600 font-semibold hover:underline mt-1 inline-block">
                    Lihat →
                </a>
                @endif
            </div>

            @if(!$notif->sudah_dibaca)
            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></div>
            @endif
        </div>
        @empty
        <div class="text-center py-20 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">🔔</div>
            <p class="font-semibold text-gray-600">Tidak ada notifikasi</p>
            <p class="text-xs mt-1">Kamu akan melihat notifikasi pesanan dan promo di sini</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($totalPages > 1)
    <div class="flex items-center justify-center gap-2 mt-6">
        <button onclick="gantiHalaman(currentPage - 1)" id="btn-prev"
            class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-50 disabled:opacity-30 transition"
            disabled>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>

        <div class="flex gap-1">
            @for($p = 1; $p <= $totalPages; $p++)
            <button onclick="gantiHalaman({{ $p }})"
                class="page-btn w-8 h-8 rounded-lg text-xs font-bold transition
                {{ $p === 1 ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                {{ $p }}
            </button>
            @endfor
        </div>

        <button onclick="gantiHalaman(currentPage + 1)" id="btn-next"
            class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </button>
    </div>
    @endif

</div>

<script>
    const perPage = 5;
    let currentPage = 1;
    const items = document.querySelectorAll('.notif-item');
    const totalPages = Math.ceil(items.length / perPage);

    function gantiHalaman(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;

        items.forEach((el, i) => {
            const start = (currentPage - 1) * perPage;
            const end   = start + perPage;
            el.classList.toggle('hidden', i < start || i >= end);
        });

        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        if (btnPrev) btnPrev.disabled = currentPage === 1;
        if (btnNext) btnNext.disabled = currentPage === totalPages;

        document.querySelectorAll('.page-btn').forEach((btn, i) => {
            const active = i + 1 === currentPage;
            btn.className = `page-btn w-8 h-8 rounded-lg text-xs font-bold transition ${
                active ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600 hover:bg-gray-50'
            }`;
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection