@extends('layouts.dashboard')
@section('title', 'Rekap & Laporan')
@section('page-title', 'Rekap & Laporan Dinas')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap items-center">
        <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ (request('bulan', now()->month) == $m) ? 'selected' : '' }}>
                {{ date('F', mktime(0,0,0,$m,1)) }}
            </option>
            @endforeach
        </select>
        <select name="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            @foreach([date('Y'), date('Y')-1] as $y)
            <option value="{{ $y }}" {{ (request('tahun', date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <a href="{{ route('dinas.laporan.export') }}"
            class="bg-purple-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition">
            Cetak Laporan Resmi
        </a>
    </form>

    {{-- Metrik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-teal-600">{{ $stats['diverifikasi'] }}</div>
            <div class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-wider">UMKM Diverifikasi</div>
            <div class="text-xs text-gray-400 mt-0.5">Bulan ini</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-red-500">{{ $stats['ditolak'] }}</div>
            <div class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-wider">UMKM Ditolak</div>
            <div class="text-xs text-gray-400 mt-0.5">Dokumen tidak lengkap</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['peserta_pembinaan'] }}</div>
            <div class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-wider">Peserta Pembinaan</div>
            <div class="text-xs text-gray-400 mt-0.5">Program aktif</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['kunjungan'] }}</div>
            <div class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-wider">Kunjungan Lapangan</div>
            <div class="text-xs text-gray-400 mt-0.5">Bulan ini</div>
        </div>
    </div>

    {{-- Tabel Rekap per Kecamatan --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">
                Rekap Verifikasi per Kecamatan —
                {{ date('F', mktime(0,0,0,request('bulan', now()->month),1)) }}
                {{ request('tahun', date('Y')) }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Daftar Baru</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Disetujui</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Ditolak</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pending</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Aktif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rekapKecamatan as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $k->kecamatan }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            @php
                                $bulan = request('bulan', now()->month);
                                $tahun = request('tahun', date('Y'));
                                $daftarBaru = \App\Models\Toko::where('kecamatan', $k->kecamatan)
                                    ->whereMonth('created_at', $bulan)
                                    ->whereYear('created_at', $tahun)
                                    ->count();
                            @endphp
                            {{ $daftarBaru }}
                        </td>
                        <td class="px-4 py-3 text-teal-600 font-semibold">{{ $k->terverifikasi }}</td>
                        <td class="px-4 py-3 text-red-500">
                            @php
                                $ditolak = \App\Models\Toko::where('kecamatan', $k->kecamatan)
                                    ->where('status', 'ditolak')
                                    ->whereMonth('updated_at', $bulan)
                                    ->whereYear('updated_at', $tahun)
                                    ->count();
                            @endphp
                            {{ $ditolak }}
                        </td>
                        <td class="px-4 py-3 text-amber-600">
                            @php
                                $pending = \App\Models\Toko::where('kecamatan', $k->kecamatan)
                                    ->where('status', 'menunggu_dinas')
                                    ->count();
                            @endphp
                            {{ $pending }}
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $k->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-purple-50 border-t-2 border-purple-200">
                        <td class="px-4 py-3 font-bold text-purple-800">Total</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $rekapKecamatan->sum(fn($k) => \App\Models\Toko::where('kecamatan', $k->kecamatan)->whereMonth('created_at', request('bulan', now()->month))->count()) }}</td>
                        <td class="px-4 py-3 font-bold text-teal-600">{{ $rekapKecamatan->sum('terverifikasi') }}</td>
                        <td class="px-4 py-3 font-bold text-red-500">-</td>
                        <td class="px-4 py-3 font-bold text-amber-600">{{ \App\Models\Toko::where('status','menunggu_dinas')->count() }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $rekapKecamatan->sum('total') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Ringkasan Narasi --}}
    <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
        <h3 class="font-bold text-purple-800 mb-3">📋 Ringkasan Laporan Dinas</h3>
        <div class="text-sm text-purple-900 leading-relaxed space-y-2">
            <p>
                Pada bulan {{ date('F', mktime(0,0,0,request('bulan', now()->month),1)) }}
                {{ request('tahun', date('Y')) }}, Dinas Koperasi & UMKM Kota Padang
                telah memverifikasi <strong>{{ $stats['diverifikasi'] }} UMKM</strong>
                dan menolak <strong>{{ $stats['ditolak'] }} pengajuan</strong>
                karena dokumen tidak lengkap.
            </p>
            <p>
                Saat ini terdapat <strong>{{ \App\Models\Toko::where('terverifikasi_dinas', true)->count() }} UMKM</strong>
                aktif yang telah mendapat sertifikat resmi Dinas.
                Program pembinaan aktif diikuti oleh <strong>{{ $stats['peserta_pembinaan'] }} UMKM</strong>.
            </p>
            <p>
                Petugas Dinas telah melakukan <strong>{{ $stats['kunjungan'] }} kunjungan lapangan</strong>
                pada bulan ini untuk memastikan kualitas dan kepatuhan UMKM terhadap standar yang ditetapkan.
            </p>
        </div>
    </div>

</div>
@endsection