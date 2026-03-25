@extends('layouts.dashboard')
@section('title', 'Keuangan & Saldo')
@section('page-title', 'Keuangan & Saldo')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">

    {{-- Saldo Hero --}}
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl p-6 mb-6 text-white">
        <div class="text-xs font-bold uppercase tracking-wider opacity-70 mb-2">Saldo Tersedia</div>
        <div class="text-4xl font-bold mb-1">
            Rp {{ number_format($toko->saldo ?? 0, 0, ',', '.') }}
        </div>
        @if(($toko->saldo_proses ?? 0) > 0)
        <div class="text-teal-200 text-sm">
            + Rp {{ number_format($toko->saldo_proses, 0, ',', '.') }} sedang diproses
        </div>
        @endif
        <div class="mt-4 flex items-center gap-2 bg-white/20 rounded-lg px-3 py-2 inline-flex">
            <span class="text-sm">🏦</span>
            <div class="text-sm">
                <span class="font-semibold">{{ $toko->bank ?? 'Bank' }}</span>
                · {{ $toko->no_rekening ?? '-' }} a/n {{ $toko->atas_nama_rekening ?? '-' }}
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mb-6">

        {{-- Statistik --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Statistik Bulan Ini</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pendapatan Kotor</span>
                    <span class="font-bold text-gray-800">
                        Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Pesanan</span>
                    <span class="font-bold text-gray-800">{{ $stats['total_pesanan'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Dicairkan</span>
                    <span class="font-bold text-teal-600">
                        Rp {{ number_format($stats['total_dicairkan'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Cairkan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Cairkan Dana</h3>
            <form method="POST" action="{{ route('penjual.keuangan.cairkan') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Pencairan (Rp)</label>
                    <input type="number" name="jumlah" required min="50000"
                        max="{{ $toko->saldo ?? 0 }}"
                        placeholder="Min. Rp 50.000"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-400 mt-1">
                        Saldo tersedia: Rp {{ number_format($toko->saldo ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500">
                    Dana akan dikirim ke: <strong>{{ $toko->bank }}</strong>
                    {{ $toko->no_rekening }} a/n {{ $toko->atas_nama_rekening }}
                    <br>Estimasi: 1–2 hari kerja
                </div>
                <button type="submit"
                    class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                    Ajukan Pencairan
                </button>
            </form>
        </div>
    </div>

    {{-- Riwayat Pencairan --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat Pencairan</h3>
        </div>
        @if(isset($riwayat) && $riwayat->count())
        <div class="divide-y divide-gray-100">
            @foreach($riwayat as $r)
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-gray-800">
                        Rp {{ number_format($r->jumlah, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $r->bank }} · {{ $r->no_rekening }} · {{ $r->created_at->format('d M Y') }}
                    </div>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full
                    {{ $r->status === 'berhasil' ? 'bg-teal-100 text-teal-700' :
                       ($r->status === 'proses'   ? 'bg-amber-100 text-amber-700' :
                                                    'bg-red-100 text-red-600') }}">
                    {{ ucfirst($r->status) }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10 text-gray-400 text-sm">Belum ada riwayat pencairan</div>
        @endif
    </div>

</div>
@endsection