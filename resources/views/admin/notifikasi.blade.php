@extends('layouts.dashboard')
@section('title', 'Notifikasi Admin')
@section('page-title', 'Notifikasi')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="w-full p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="text-sm text-gray-500">
            <span class="font-semibold text-gray-700">{{ $notifikasis->total() }}</span> notifikasi
        </div>
        <form method="POST" action="{{ route('admin.notifikasi.baca-semua') }}">
            @csrf @method('PATCH')
            <button class="text-xs text-gray-500 hover:text-gray-700 font-semibold border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    {{-- List Notifikasi --}}
    <div class="space-y-2">
        @forelse($notifikasis as $notif)
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-start gap-3
            {{ !$notif->sudah_dibaca ? 'border-l-4 border-l-amber-400 bg-amber-50/30' : '' }}
            hover:shadow-sm transition">

            @php
                $icon = match($notif->tipe) {
                    'success' => '✅', 'danger' => '❌', 'warning' => '⚠️', default => 'ℹ️',
                };
                $bg = match($notif->tipe) {
                    'success' => 'bg-teal-100', 'danger' => 'bg-red-100',
                    'warning' => 'bg-amber-100', default => 'bg-blue-100',
                };
            @endphp

            <div class="w-9 h-9 rounded-full {{ $bg }} flex items-center justify-center text-base flex-shrink-0">
                {{ $icon }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-800">{{ $notif->judul }}</div>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                <div class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                @if($notif->url)
                <a href="{{ $notif->url }}" class="text-xs text-amber-600 font-semibold hover:underline mt-0.5 inline-block">
                    Lihat →
                </a>
                @endif
            </div>

            @if(!$notif->sudah_dibaca)
            <div class="w-2 h-2 bg-amber-500 rounded-full flex-shrink-0 mt-1.5"></div>
            @endif
        </div>
        @empty
        <div class="text-center py-20 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">🔔</div>
            <p class="font-semibold text-gray-600">Tidak ada notifikasi</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">{{ $notifikasis->links() }}</div>

</div>
@endsection