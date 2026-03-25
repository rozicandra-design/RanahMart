@extends('layouts.dashboard')
@section('title', 'Detail Retur')
@section('page-title', 'Detail Retur')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('admin.retur.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar Retur
    </a>

    {{-- Info Retur --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <div class="font-bold text-gray-800 text-base font-mono">{{ $retur->kode_retur }}</div>
                <div class="text-xs text-gray-400 mt-0.5">Diajukan: {{ $retur->created_at->format('d M Y H:i') }}</div>
            </div>
            @php
                $statusClass = match($retur->status) {
                    'diajukan'  => 'bg-amber-100 text-amber-700',
                    'ditinjau'  => 'bg-blue-100 text-blue-700',
                    'disetujui' => 'bg-teal-100 text-teal-700',
                    'ditolak'   => 'bg-red-100 text-red-600',
                    'selesai'   => 'bg-gray-100 text-gray-500',
                    default     => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="text-sm font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                {{ ucfirst($retur->status) }}
            </span>
        </div>

        {{-- Pihak --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Pembeli (Pengaju)</div>
                <div class="text-sm font-semibold text-gray-800">{{ $retur->pembeli->nama_lengkap ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $retur->pembeli->email ?? '' }}</div>
                <div class="text-xs text-gray-500">{{ $retur->pembeli->no_hp ?? '' }}</div>
            </div>
            <div class="bg-teal-50 rounded-lg p-3">
                <div class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">Penjual</div>
                <div class="text-sm font-semibold text-gray-800">{{ $retur->toko->nama_toko ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $retur->toko->kecamatan ?? '' }}</div>
            </div>
        </div>

        {{-- Detail Retur --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Pesanan Terkait</span>
                <a href="{{ route('admin.transaksi.show', $retur->pesanan_id) }}"
                    class="font-mono font-semibold text-blue-600 hover:underline">
                    {{ $retur->pesanan->kode_pesanan ?? '-' }}
                </a>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Alasan Retur</span>
                <span class="font-semibold text-gray-800">{{ $retur->alasan }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nilai Retur</span>
                <span class="font-bold text-gray-800">Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($retur->keterangan)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
            <div class="text-xs font-bold text-amber-700 mb-1">Keterangan dari Pembeli:</div>
            <p class="text-sm text-amber-800 leading-relaxed">{{ $retur->keterangan }}</p>
        </div>
        @endif

        {{-- Foto Bukti --}}
        @if($retur->fotos->count())
        <div class="mb-4">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Foto Bukti</div>
            <div class="flex gap-2 flex-wrap">
                @foreach($retur->fotos as $foto)
                <a href="{{ Storage::url($foto->path) }}" target="_blank">
                    <img src="{{ Storage::url($foto->path) }}"
                        class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-400 transition">
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Item Pesanan --}}
        @if($retur->pesanan && $retur->pesanan->items->count())
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Item yang Diretur</div>
            <div class="space-y-2">
                @foreach($retur->pesanan->items as $item)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $item->nama_produk }}</div>
                        <div class="text-xs text-gray-400">×{{ $item->jumlah }}</div>
                    </div>
                    <div class="text-sm font-bold text-gray-800">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Keputusan Admin --}}
    @if($retur->keputusan_admin)
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <h3 class="font-bold text-gray-800 mb-2">Keputusan Admin</h3>
        <p class="text-sm text-gray-700">{{ $retur->keputusan_admin }}</p>
    </div>
    @endif

    {{-- Aksi Mediasi --}}
    @if(in_array($retur->status, ['diajukan', 'ditinjau']))
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-4">Keputusan Mediasi</h3>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-xs text-blue-700">
            ℹ️ Tinjau bukti foto, alasan pembeli, dan respons penjual sebelum membuat keputusan.
            Keputusan Anda akan dikirimkan sebagai notifikasi ke kedua pihak.
        </div>

        <div class="space-y-3">
            <form method="POST" action="{{ route('admin.retur.setujui', $retur->id) }}" class="space-y-2">
                @csrf @method('PATCH')
                <label class="block text-xs font-bold text-gray-700">Keputusan / Keterangan untuk Pembeli *</label>
                <textarea name="keputusan_admin" rows="2" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="Contoh: Setelah ditinjau, retur disetujui. Dana akan dikembalikan dalam 1-3 hari kerja..."></textarea>
                <button class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                    ✓ Setujui Retur — Proses Refund ke Pembeli
                </button>
            </form>

            <div class="relative flex items-center">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="mx-3 text-xs text-gray-400 flex-shrink-0">atau</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <form method="POST" action="{{ route('admin.retur.tolak', $retur->id) }}" class="space-y-2">
                @csrf @method('PATCH')
                <label class="block text-xs font-bold text-gray-700">Alasan Penolakan *</label>
                <textarea name="keputusan_admin" rows="2" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="Contoh: Setelah ditinjau, bukti tidak mencukupi. Retur ditolak..."></textarea>
                <button class="w-full bg-red-100 text-red-700 font-bold py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                    ✕ Tolak Retur — Dana Tetap ke Penjual
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
</div>
@endsection