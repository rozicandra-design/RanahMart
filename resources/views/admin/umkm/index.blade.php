@extends('layouts.dashboard')
@section('title', 'UMKM Terdaftar')
@section('page-title', 'UMKM Terdaftar')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Alert pending --}}
    @if($pendingCount > 0)
    <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 mb-5 flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <div>
            <div class="font-semibold text-amber-800">{{ $pendingCount }} UMKM menunggu verifikasi</div>
            <div class="text-xs text-amber-600">Segera tinjau pendaftaran yang masuk</div>
        </div>
    </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama toko..."
            class="flex-1 min-w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending"        {{ request('status') === 'pending'        ? 'selected' : '' }}>Pending</option>
            <option value="menunggu_dinas" {{ request('status') === 'menunggu_dinas' ? 'selected' : '' }}>Menunggu Dinas</option>
            <option value="aktif"          {{ request('status') === 'aktif'          ? 'selected' : '' }}>Aktif</option>
            <option value="ditolak"        {{ request('status') === 'ditolak'        ? 'selected' : '' }}>Ditolak</option>
            <option value="dokumen_kurang" {{ request('status') === 'dokumen_kurang' ? 'selected' : '' }}>Dokumen Kurang</option>
        </select>
        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
            <option value="{{ $slug }}" {{ request('kategori') === $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="kecamatan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Kecamatan</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
            <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
        <button class="bg-gray-800 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-900 transition">Cari</button>
    </form>

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Toko</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Daftar</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tokos as $toko)
                    <tr class="hover:bg-gray-50 transition {{ in_array($toko->status, ['pending','menunggu_dinas','dokumen_kurang']) ? 'bg-amber-50/30' : '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $toko->nama_toko }}</div>
                                    <div class="text-xs text-gray-400">{{ $toko->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $toko->kategori_friendly }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $toko->kecamatan }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $toko->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($toko->status) {
                                    'aktif'          => 'bg-teal-100 text-teal-700',
                                    'pending'        => 'bg-amber-100 text-amber-700',
                                    'menunggu_dinas' => 'bg-blue-100 text-blue-700',
                                    'ditolak'        => 'bg-red-100 text-red-600',
                                    'dokumen_kurang' => 'bg-orange-100 text-orange-700',
                                    default          => 'bg-gray-100 text-gray-500',
                                };
                                $statusLabel = match($toko->status) {
                                    'aktif'          => 'Aktif',
                                    'pending'        => 'Pending',
                                    'menunggu_dinas' => 'Menunggu Dinas',
                                    'ditolak'        => 'Ditolak',
                                    'dokumen_kurang' => 'Dokumen Kurang',
                                    default          => ucfirst($toko->status),
                                };
                            @endphp
                            <div>
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($toko->terverifikasi_dinas)
                                <div class="text-xs text-teal-600 font-semibold mt-0.5">✓ Dinas</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('admin.umkm.show', $toko->id) }}"
                                    class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-1 rounded hover:bg-gray-200 transition">
                                    Detail
                                </a>
                                @if(in_array($toko->status, ['pending', 'menunggu_dinas', 'dokumen_kurang']))
                                <form method="POST" action="{{ route('admin.umkm.setujui', $toko->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs bg-teal-100 text-teal-700 font-semibold px-2 py-1 rounded hover:bg-teal-600 hover:text-white transition">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.umkm.teruskan-dinas', $toko->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs bg-purple-100 text-purple-700 font-semibold px-2 py-1 rounded hover:bg-purple-600 hover:text-white transition">
                                        → Dinas
                                    </button>
                                </form>
                                @endif
                                @if($toko->status === 'aktif')
                                <form method="POST" action="{{ route('admin.umkm.nonaktif', $toko->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs bg-red-100 text-red-600 font-semibold px-2 py-1 rounded hover:bg-red-600 hover:text-white transition">
                                        Nonaktif
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400 text-sm">
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