@extends('layouts.dashboard')
@section('title', 'Detail Iklan')
@section('page-title', 'Detail Iklan')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('admin.iklan.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Iklan
    </a>

    {{-- Preview Iklan --}}
    <div class="rounded-xl overflow-hidden shadow mb-4"
        style="background: {{ $iklan->warna_tema ?? '#C0392B' }}">
        <div class="p-6 text-white">
            @if($iklan->banner)
            <img src="{{ Storage::url($iklan->banner) }}"
                class="w-full h-32 object-cover rounded-lg mb-4">
            @endif
            <div class="text-xs font-semibold opacity-70 mb-1">
                {{ $iklan->toko->nama_toko ?? '' }}
            </div>
            <h3 class="text-xl font-bold mb-1">{{ $iklan->judul }}</h3>
            @if($iklan->sub_judul)
            <p class="text-sm opacity-80 mb-3">{{ $iklan->sub_judul }}</p>
            @endif
            <span class="inline-block bg-white text-gray-800 font-bold px-4 py-2 rounded-lg text-sm">
                {{ $iklan->teks_cta }} →
            </span>
        </div>
    </div>

    {{-- Info Iklan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <h2 class="font-bold text-gray-800">{{ $iklan->judul }}</h2>
                <div class="text-xs text-gray-400 mt-0.5">Diajukan: {{ $iklan->created_at->format('d M Y H:i') }}</div>
            </div>
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
            <span class="text-sm font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                {{ ucfirst($iklan->status) }}
            </span>
        </div>

        {{-- Info Toko --}}
        <div class="bg-amber-50 rounded-lg p-3 mb-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($iklan->toko->nama_toko ?? 'T', 0, 1)) }}
            </div>
            <div>
                <div class="text-sm font-bold text-gray-800">{{ $iklan->toko->nama_toko ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $iklan->toko->kategori_friendly ?? '' }}</div>
            </div>
            <a href="{{ route('admin.umkm.show', $iklan->toko_id) }}"
                class="ml-auto text-xs text-amber-600 font-semibold hover:underline">
                Lihat Toko →
            </a>
        </div>

        {{-- Detail Paket --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Paket</div>
                <div class="font-bold text-gray-800 capitalize">{{ $iklan->paket }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Biaya</div>
                <div class="font-bold text-gray-800">Rp {{ number_format($iklan->biaya, 0, ',', '.') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Posisi</div>
                <div class="font-bold text-gray-800 text-xs">{{ $iklan->posisi }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Periode Tayang</div>
                <div class="font-bold text-gray-800 text-xs">
                    {{ $iklan->tanggal_mulai->format('d M') }} – {{ $iklan->tanggal_selesai->format('d M Y') }}
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Teks CTA</div>
                <div class="font-bold text-gray-800">{{ $iklan->teks_cta }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Warna Tema</div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded border border-gray-300" style="background: {{ $iklan->warna_tema }}"></div>
                    <span class="font-bold text-gray-800 font-mono text-xs">{{ $iklan->warna_tema }}</span>
                </div>
            </div>
        </div>

        {{-- Statistik (jika aktif) --}}
        @if($iklan->status === 'aktif')
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
            <div class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-3">Statistik Iklan</div>
            <div class="grid grid-cols-3 gap-3 text-center">
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($iklan->total_tayangan) }}</div>
                    <div class="text-xs text-gray-500">Tayangan</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($iklan->total_klik) }}</div>
                    <div class="text-xs text-gray-500">Klik</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-amber-600">{{ $iklan->ctr }}%</div>
                    <div class="text-xs text-gray-500">CTR</div>
                </div>
            </div>
        </div>
        @endif

        @if($iklan->catatan_pengaju)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-blue-700 mb-0.5">Catatan dari Pengaju:</div>
            <p class="text-sm text-blue-800">{{ $iklan->catatan_pengaju }}</p>
        </div>
        @endif

        @if($iklan->catatan_admin)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-gray-600 mb-0.5">Catatan Admin:</div>
            <p class="text-sm text-gray-700">{{ $iklan->catatan_admin }}</p>
        </div>
        @endif

        @if($iklan->disetujui_at)
        <div class="text-xs text-gray-400">
            Disetujui: {{ $iklan->disetujui_at->format('d M Y H:i') }}
            @if($iklan->disetujuiOleh)
            oleh {{ $iklan->disetujuiOleh->nama_depan }}
            @endif
        </div>
        @endif
    </div>

    {{-- Aksi --}}
    @if(in_array($iklan->status, ['menunggu', 'ditinjau']))
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-4">Keputusan Review Iklan</h3>

        <form method="POST" action="{{ route('admin.iklan.setujui', $iklan->id) }}" class="mb-3">
            @csrf @method('PATCH')
            <button class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                ✓ Setujui & Tayangkan Iklan
            </button>
        </form>

        <form method="POST" action="{{ route('admin.iklan.revisi', $iklan->id) }}" class="space-y-2 mb-3">
            @csrf @method('PATCH')
            <label class="block text-xs font-bold text-gray-700">Catatan Revisi *</label>
            <textarea name="catatan_admin" rows="2" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-amber-400"
                placeholder="Contoh: Foto banner perlu diperbaiki, resolusi terlalu rendah..."></textarea>
            <button class="w-full bg-amber-500 text-white font-bold py-2.5 rounded-lg hover:bg-amber-600 transition text-sm">
                ↩ Minta Revisi ke Penjual
            </button>
        </form>

        <form method="POST" action="{{ route('admin.iklan.tolak', $iklan->id) }}" class="space-y-2">
            @csrf @method('PATCH')
            <label class="block text-xs font-bold text-gray-700">Alasan Penolakan *</label>
            <textarea name="catatan_admin" rows="2" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-400"
                placeholder="Contoh: Konten iklan tidak sesuai dengan kebijakan RanahMart..."></textarea>
            <button class="w-full bg-red-100 text-red-700 font-bold py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                ✕ Tolak Pengajuan Iklan
            </button>
        </form>
    </div>
    @elseif($iklan->status === 'aktif')
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-3">Aksi</h3>
        <form method="POST" action="{{ route('admin.iklan.hentikan', $iklan->id) }}"
            onsubmit="return confirm('Yakin ingin menghentikan iklan ini?')">
            @csrf @method('PATCH')
            <button class="bg-red-100 text-red-700 font-bold px-5 py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                ⏹ Hentikan Iklan
            </button>
        </form>
    </div>
    @endif

</div>
</div>
@endsection