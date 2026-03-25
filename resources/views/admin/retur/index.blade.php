@extends('layouts.dashboard')
@section('title', 'Kelola Retur')
@section('page-title', 'Kelola Retur & Komplain')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="diajukan"  {{ request('status') === 'diajukan'  ? 'selected' : '' }}>Diajukan</option>
            <option value="ditinjau"  {{ request('status') === 'ditinjau'  ? 'selected' : '' }}>Ditinjau</option>
            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
        </select>
    </form>

    <div class="space-y-4">
        @forelse($returs as $retur)
        <div class="bg-white border-2 rounded-xl p-5
            {{ in_array($retur->status, ['diajukan','ditinjau']) ? 'border-amber-300' : 'border-gray-200' }}">

            <div class="flex items-start justify-between gap-3 mb-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-gray-800">{{ $retur->kode_retur }}</span>
                        @php
                            $statusClass = match($retur->status) {
                                'diajukan'  => 'bg-amber-100 text-amber-700',
                                'ditinjau'  => 'bg-blue-100 text-blue-700',
                                'disetujui' => 'bg-teal-100 text-teal-700',
                                'ditolak'   => 'bg-red-100 text-red-600',
                                default     => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $statusClass }}">
                            {{ ucfirst($retur->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        Pembeli: {{ $retur->pembeli->nama_lengkap ?? '-' }} ·
                        Penjual: {{ $retur->toko->nama_toko ?? '-' }} ·
                        Pesanan: {{ $retur->pesanan->kode_pesanan ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-600 mt-1">
                        Alasan: <strong>{{ $retur->alasan }}</strong>
                    </div>
                    @if($retur->keterangan)
                    <div class="text-xs text-gray-500 mt-0.5">{{ $retur->keterangan }}</div>
                    @endif
                    <div class="font-bold text-gray-800 mt-1">
                        Nilai Retur: Rp {{ number_format($retur->nilai_retur, 0, ',', '.') }}
                    </div>
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">
                    {{ $retur->created_at->format('d M Y') }}
                </div>
            </div>

            @if(in_array($retur->status, ['diajukan', 'ditinjau']))
            <div class="space-y-2">
                <form method="POST" action="{{ route('admin.retur.setujui', $retur->id) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <input type="text" name="keputusan_admin" required
                        placeholder="Keputusan / keterangan untuk pembeli..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-xs">
                    <button class="text-xs bg-teal-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">
                        ✓ Setujui Retur
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.retur.tolak', $retur->id) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <input type="text" name="keputusan_admin" required
                        placeholder="Alasan penolakan..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-xs">
                    <button class="text-xs bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 hover:text-white transition">
                        ✕ Tolak Retur
                    </button>
                </form>
            </div>
            @elseif($retur->keputusan_admin)
            <div class="bg-gray-50 rounded-lg p-2 text-xs text-gray-600">
                Keputusan Admin: {{ $retur->keputusan_admin }}
            </div>
            @endif

        </div>
        @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
            <div class="text-6xl mb-4">🔄</div>
            <p class="font-semibold text-gray-600">Tidak ada pengajuan retur</p>
        </div>
        @endforelse
    </div>

    <div class="mt-5">{{ $returs->withQueryString()->links() }}</div>

</div>
@endsection