@extends('layouts.dashboard')
@section('title', 'Pengembalian Barang')
@section('page-title', 'Pengembalian & Retur')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Form Ajukan Retur --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Ajukan Pengembalian Baru</h3>
            <form method="POST" action="{{ route('pembeli.retur.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Pesanan *</label>
                    <select name="pesanan_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih pesanan yang ingin diretur</option>
                        @foreach(auth()->user()->pesanans()->where('status_pesanan','selesai')->whereDoesntHave('retur')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->kode_pesanan }} — {{ $p->toko->nama_toko ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Pengembalian *</label>
                    <select name="alasan" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih alasan</option>
                        @foreach([
                            'Produk tidak sesuai deskripsi',
                            'Produk rusak / cacat',
                            'Salah kirim produk',
                            'Kualitas tidak sesuai',
                            'Produk tidak berfungsi',
                        ] as $alasan)
                        <option value="{{ $alasan }}">{{ $alasan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Jelaskan kondisi produk secara detail..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">
                        Foto Bukti <span class="text-gray-400 font-normal">(wajib, maks. 3 foto)</span>
                    </label>
                    <input type="file" name="foto_bukti[]" multiple accept="image/*" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
                    ℹ️ Pengajuan retur akan ditinjau admin dalam 1–3 hari kerja. Pastikan foto bukti jelas dan terbaca.
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition text-sm">
                    Kirim Pengajuan Retur
                </button>
            </form>
        </div>

        {{-- Riwayat Retur --}}
        <div>
            <h3 class="font-bold text-gray-800 mb-4">Riwayat Pengembalian</h3>
            @if(isset($returs) && $returs->count())
            <div class="space-y-3">
                @foreach($returs as $retur)
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <div class="text-sm font-bold text-gray-800">{{ $retur->kode_retur }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $retur->pesanan->kode_pesanan ?? '' }} ·
                                {{ $retur->created_at->format('d M Y') }}
                            </div>
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
                        <span class="text-xs font-bold px-2 py-1 rounded {{ $statusClass }}">
                            {{ ucfirst($retur->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-600">{{ $retur->alasan }}</div>
                    <div class="text-sm font-bold text-gray-800 mt-1">
                        Nilai: Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}
                    </div>
                    @if($retur->keputusan_admin)
                    <div class="mt-2 bg-gray-50 rounded p-2 text-xs text-gray-600">
                        Keputusan admin: {{ $retur->keputusan_admin }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
                <div class="text-5xl mb-3">🔄</div>
                <p class="text-sm">Belum ada pengajuan retur</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection