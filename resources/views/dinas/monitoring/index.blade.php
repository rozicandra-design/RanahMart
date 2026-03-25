@extends('layouts.dashboard')
@section('title', 'Monitoring UMKM')
@section('page-title', 'Monitoring UMKM Aktif')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama toko atau pemilik..."
            class="flex-1 min-w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select name="kecamatan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Kecamatan</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
            <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
            <option value="{{ $slug }}" {{ request('kategori') === $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button class="bg-purple-600 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition">Cari</button>
        <a href="{{ route('dinas.statistik.export') }}"
            class="bg-white border border-gray-300 text-gray-600 font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            Export Data
        </a>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Toko</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pemilik</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sertifikat</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tokos as $toko)
                    @php
                        $sertExpired   = $toko->kadaluarsa_sertifikat && $toko->kadaluarsa_sertifikat->isPast();
                        $sertNearExpiry = $toko->kadaluarsa_sertifikat && $toko->kadaluarsa_sertifikat->diffInDays(now()) <= 30 && !$sertExpired;
                    @endphp
                    <tr class="hover:bg-gray-50 transition {{ $sertExpired ? 'bg-red-50/30' : ($sertNearExpiry ? 'bg-amber-50/30' : '') }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $toko->nama_toko }}</div>
                                    @if($toko->terverifikasi_dinas)
                                    <div class="text-xs text-teal-600 font-semibold">✓ Terverifikasi</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $toko->user->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $toko->kecamatan }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $toko->kategori_friendly }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-amber-500">★ {{ $toko->rating }}</td>
                        <td class="px-4 py-3">
                            @if($toko->kadaluarsa_sertifikat)
                            <div class="text-xs {{ $sertExpired ? 'text-red-500 font-bold' : ($sertNearExpiry ? 'text-amber-600 font-semibold' : 'text-teal-600') }}">
                                {{ $sertExpired ? '⚠ Kadaluarsa' : ($sertNearExpiry ? '⚠ Segera Habis' : '✓ Aktif') }}
                            </div>
                            <div class="text-xs text-gray-400">s/d {{ $toko->kadaluarsa_sertifikat->format('d M Y') }}</div>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('dinas.monitoring.show', $toko->id) }}"
                                class="text-xs bg-purple-100 text-purple-700 font-bold px-2 py-1 rounded hover:bg-purple-600 hover:text-white transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400 text-sm">
                            Tidak ada UMKM ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $tokos->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection