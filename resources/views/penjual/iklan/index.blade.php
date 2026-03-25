@extends('layouts.dashboard')
@section('title', 'Kelola Iklan')
@section('page-title', 'Pasang Iklan')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">

    {{-- Iklan Aktif --}}
    @if(isset($iklanAktif) && $iklanAktif)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">📢 Iklan Sedang Tayang</div>
                <div class="font-bold text-gray-800 text-base mb-1">{{ $iklanAktif->judul }}</div>
                <div class="text-xs text-gray-500">
                    Posisi: {{ $iklanAktif->posisi }} ·
                    Paket: {{ ucfirst($iklanAktif->paket) }} ·
                    s/d {{ $iklanAktif->tanggal_selesai->format('d M Y') }}
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-white rounded-lg p-2 border border-amber-200">
                        <div class="text-lg font-bold text-gray-800">{{ number_format($iklanAktif->total_tayangan) }}</div>
                        <div class="text-xs text-gray-400">Tayangan</div>
                    </div>
                    <div class="bg-white rounded-lg p-2 border border-amber-200">
                        <div class="text-lg font-bold text-gray-800">{{ number_format($iklanAktif->total_klik) }}</div>
                        <div class="text-xs text-gray-400">Klik</div>
                    </div>
                    <div class="bg-white rounded-lg p-2 border border-amber-200">
                        <div class="text-lg font-bold text-gray-800">
                            {{ $iklanAktif->total_tayangan > 0
                                ? round($iklanAktif->total_klik / $iklanAktif->total_tayangan * 100, 1)
                                : 0 }}%
                        </div>
                        <div class="text-xs text-gray-400">CTR</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-gray-800">Riwayat Iklan</h2>
        <a href="{{ route('penjual.iklan.create') }}"
            class="bg-amber-500 text-white font-bold text-sm px-4 py-2 rounded-lg hover:bg-amber-600 transition">
            + Pasang Iklan Baru
        </a>
    </div>

    @if(isset($iklans) && $iklans->count())
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="divide-y divide-gray-100">
            @foreach($iklans as $iklan)
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ $iklan->judul }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ ucfirst($iklan->paket) }} · {{ $iklan->posisi }} ·
                        {{ $iklan->tanggal_mulai->format('d M') }} – {{ $iklan->tanggal_selesai->format('d M Y') }} ·
                        Rp {{ number_format($iklan->biaya, 0, ',', '.') }}
                    </div>
                    @if($iklan->catatan_admin)
                    <div class="text-xs text-red-500 mt-0.5">Catatan: {{ $iklan->catatan_admin }}</div>
                    @endif
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @php
                        $statusClass = match($iklan->status) {
                            'aktif'     => 'bg-teal-100 text-teal-700',
                            'menunggu'  => 'bg-amber-100 text-amber-700',
                            'ditinjau'  => 'bg-blue-100 text-blue-700',
                            'ditolak'   => 'bg-red-100 text-red-600',
                            'selesai'   => 'bg-gray-100 text-gray-500',
                            'dihentikan'=> 'bg-gray-100 text-gray-500',
                            default     => 'bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <span class="text-xs font-bold px-2 py-1 rounded {{ $statusClass }}">
                        {{ ucfirst($iklan->status) }}
                    </span>
                    @if(in_array($iklan->status, ['menunggu', 'ditinjau']))
                    <form method="POST" action="{{ route('penjual.iklan.destroy', $iklan->id) }}"
                        onsubmit="return confirm('Batalkan pengajuan iklan ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-500 hover:text-red-700 font-semibold">Batalkan</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
        <div class="text-5xl mb-3">📢</div>
        <p class="font-semibold text-gray-600">Belum pernah pasang iklan</p>
        <a href="{{ route('penjual.iklan.create') }}"
            class="inline-block mt-3 text-amber-600 font-semibold hover:underline text-sm">
            Mulai pasang iklan →
        </a>
    </div>
    @endif

</div>
@endsection