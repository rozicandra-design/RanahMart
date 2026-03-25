@extends('layouts.dashboard')
@section('title', 'Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">

    {{-- Tab filter --}}
    <div class="flex gap-2 mb-5 flex-wrap">
        @php
        $tabs = [
            ''           => 'Semua',
            'menunggu'   => 'Baru (' . ($counts['baru'] ?? 0) . ')',
            'diproses'   => 'Diproses (' . ($counts['proses'] ?? 0) . ')',
            'dikirim'    => 'Dikirim (' . ($counts['kirim'] ?? 0) . ')',
            'selesai'    => 'Selesai (' . ($counts['selesai'] ?? 0) . ')',
            'dibatalkan' => 'Dibatalkan',
        ];
        @endphp
        @foreach($tabs as $val => $label)
        <a href="{{ route('penjual.pesanan.index', ['status' => $val]) }}"
            class="px-4 py-2 rounded-full text-sm font-semibold border transition
            {{ request('status') == $val
                ? 'bg-teal-600 text-white border-teal-600'
                : 'bg-white text-gray-600 border-gray-200 hover:border-teal-400 hover:text-teal-600' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- List pesanan --}}
    <div class="space-y-3">
        @forelse($pesanans as $pesanan)
        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">

                {{-- Kiri: info pesanan --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="font-bold text-gray-800 text-sm">{{ $pesanan->kode_pesanan }}</span>
                        @php
                            $statusClass = match($pesanan->status_pesanan) {
                                'menunggu'    => 'bg-amber-100 text-amber-700',
                                'dikonfirmasi','diproses' => 'bg-blue-100 text-blue-700',
                                'dikirim'     => 'bg-indigo-100 text-indigo-700',
                                'selesai'     => 'bg-teal-100 text-teal-700',
                                'dibatalkan'  => 'bg-red-100 text-red-600',
                                default       => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabel = match($pesanan->status_pesanan) {
                                'menunggu'    => 'Menunggu Konfirmasi',
                                'dikonfirmasi'=> 'Dikonfirmasi',
                                'diproses'    => 'Diproses',
                                'dikirim'     => 'Dikirim',
                                'selesai'     => 'Selesai',
                                'dibatalkan'  => 'Dibatalkan',
                                default       => ucfirst($pesanan->status_pesanan),
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-500 mb-2">
                        👤 {{ $pesanan->pembeli->nama_lengkap ?? 'Pembeli' }}
                        · {{ $pesanan->items->count() }} item
                        · {{ $pesanan->created_at->format('d M Y H:i') }}
                    </div>

                    {{-- Item preview --}}
                    <div class="space-y-1">
                        @foreach($pesanan->items->take(2) as $item)
                        <div class="text-xs text-gray-600 flex items-center gap-2">
                            <span class="w-1 h-1 bg-gray-400 rounded-full flex-shrink-0"></span>
                            {{ $item->nama_produk }} ×{{ $item->jumlah }}
                            <span class="text-gray-400">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}/pcs</span>
                        </div>
                        @endforeach
                        @if($pesanan->items->count() > 2)
                        <div class="text-xs text-gray-400 italic">
                            +{{ $pesanan->items->count() - 2 }} item lainnya
                        </div>
                        @endif
                    </div>

                    @if($pesanan->no_resi)
                    <div class="text-xs text-teal-600 mt-1.5 font-semibold">
                        🚚 {{ $pesanan->jasa_kirim }} · No. Resi: {{ $pesanan->no_resi }}
                    </div>
                    @endif
                </div>

                {{-- Kanan: total & aksi --}}
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    <div class="text-base font-bold text-gray-800">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </div>

                    <a href="{{ route('penjual.pesanan.show', $pesanan->id) }}"
                        class="text-xs bg-gray-100 text-gray-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-200 transition">
                        Detail
                    </a>

                    @if($pesanan->status_pesanan === 'menunggu')
                    <div class="flex gap-1">
                        <form method="POST" action="{{ route('penjual.pesanan.konfirmasi', $pesanan->id) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-teal-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">
                                ✓ Konfirmasi
                            </button>
                        </form>
                        <form method="POST" action="{{ route('penjual.pesanan.tolak', $pesanan->id) }}"
                            onsubmit="return confirm('Tolak pesanan ini?')">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 hover:text-white transition">
                                ✕ Tolak
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">📭</div>
            <p class="font-semibold text-gray-600">Tidak ada pesanan</p>
            <p class="text-sm mt-1">Belum ada pesanan masuk saat ini</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $pesanans->withQueryString()->links() }}</div>

</div>
@endsection