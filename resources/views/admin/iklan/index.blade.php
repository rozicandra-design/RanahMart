@extends('layouts.dashboard')
@section('title', 'Kelola Iklan')
@section('page-title', 'Kelola Iklan')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-amber-600">{{ $stats['menunggu'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Menunggu Review</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-teal-600">{{ $stats['aktif'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Iklan Aktif</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-800">
                Rp {{ number_format($stats['pendapatan'] / 1000, 0) }}rb
            </div>
            <div class="text-xs text-gray-400 mt-1">Pendapatan Bulan Ini</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $stats['total_pengiklan'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Total Pengiklan</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="ditinjau" {{ request('status') === 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
            <option value="selesai"  {{ request('status') === 'selesai'  ? 'selected' : '' }}>Selesai</option>
        </select>
    </form>

    {{-- List Iklan --}}
    <div class="space-y-4">
        @forelse($iklans as $iklan)
        <div class="bg-white border-2 rounded-xl p-5
            {{ in_array($iklan->status, ['menunggu','ditinjau']) ? 'border-amber-300' : 'border-gray-200' }}">

            <div class="flex items-start justify-between gap-3 mb-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="font-bold text-gray-800">{{ $iklan->judul }}</div>
                        @php
                            $statusClass = match($iklan->status) {
                                'aktif'      => 'bg-teal-100 text-teal-700',
                                'menunggu'   => 'bg-amber-100 text-amber-700',
                                'ditinjau'   => 'bg-blue-100 text-blue-700',
                                'ditolak'    => 'bg-red-100 text-red-600',
                                'selesai'    => 'bg-gray-100 text-gray-500',
                                'dihentikan' => 'bg-gray-100 text-gray-500',
                                default      => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $statusClass }}">
                            {{ ucfirst($iklan->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $iklan->toko->nama_toko ?? '' }} ·
                        Paket {{ ucfirst($iklan->paket) }} · Rp {{ number_format($iklan->biaya, 0, ',', '.') }} ·
                        {{ $iklan->posisi }}
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $iklan->tanggal_mulai->format('d M') }} – {{ $iklan->tanggal_selesai->format('d M Y') }}
                    </div>
                    @if($iklan->catatan_pengaju)
                    <div class="text-xs text-gray-600 mt-1 bg-gray-50 px-2 py-1 rounded">
                        Catatan: {{ $iklan->catatan_pengaju }}
                    </div>
                    @endif
                </div>

                @if($iklan->status === 'aktif')
                <div class="text-right flex-shrink-0">
                    <div class="text-xs text-gray-400">Tayangan</div>
                    <div class="font-bold text-gray-800">{{ number_format($iklan->total_tayangan) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Klik</div>
                    <div class="font-bold text-gray-800">{{ number_format($iklan->total_klik) }}</div>
                    <div class="text-xs text-gray-400 mt-1">CTR</div>
                    <div class="font-bold text-amber-600">{{ $iklan->ctr }}%</div>
                </div>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="flex gap-2 flex-wrap">
                @if(in_array($iklan->status, ['menunggu', 'ditinjau']))
                <form method="POST" action="{{ route('admin.iklan.setujui', $iklan->id) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs bg-teal-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">
                        ✓ Setujui & Tayangkan
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.iklan.revisi', $iklan->id) }}" class="flex gap-1">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan_admin" placeholder="Catatan revisi..."
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs w-40">
                    <button class="text-xs bg-amber-500 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition">
                        ↩ Revisi
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.iklan.tolak', $iklan->id) }}" class="flex gap-1">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan_admin" placeholder="Alasan penolakan..."
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs w-36">
                    <button class="text-xs bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 hover:text-white transition">
                        ✕ Tolak
                    </button>
                </form>
                @elseif($iklan->status === 'aktif')
                <form method="POST" action="{{ route('admin.iklan.hentikan', $iklan->id) }}"
                    onsubmit="return confirm('Hentikan iklan ini?')">
                    @csrf @method('PATCH')
                    <button class="text-xs bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 hover:text-white transition">
                        ⏹ Hentikan Iklan
                    </button>
                </form>
                @endif
            </div>

        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">📢</div>
            <p class="font-semibold text-gray-600">Tidak ada iklan</p>
        </div>
        @endforelse
    </div>

    <div class="mt-5">{{ $iklans->withQueryString()->links() }}</div>

</div>
@endsection