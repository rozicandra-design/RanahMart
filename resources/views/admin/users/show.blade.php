@extends('layouts.dashboard')
@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">
<div class="max-w-2xl">

    <a href="{{ route('admin.users.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Daftar User
    </a>

    {{-- Profil User --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-start justify-between gap-3 mb-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0
                    {{ $user->foto_profil ? '' : 'bg-blue-100 flex items-center justify-center' }}">
                    @if($user->foto_profil)
                        <img src="{{ Storage::url($user->foto_profil) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-blue-700">
                            {{ strtoupper(substr($user->nama_depan, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">{{ $user->nama_lengkap }}</h2>
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ $user->no_hp ?? '-' }}</div>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                @php
                    $roleClass = match($user->role) {
                        'admin'   => 'bg-amber-100 text-amber-700',
                        'penjual' => 'bg-teal-100 text-teal-700',
                        'dinas'   => 'bg-purple-100 text-purple-700',
                        default   => 'bg-blue-100 text-blue-700',
                    };
                    $roleLabel = match($user->role) {
                        'admin'   => 'Admin',
                        'penjual' => 'Penjual UMKM',
                        'dinas'   => 'Dinas',
                        default   => 'Pembeli',
                    };
                @endphp
                <span class="text-xs font-bold px-2 py-1 rounded {{ $roleClass }}">
                    {{ $roleLabel }}
                </span>
                <span class="text-xs font-bold px-2 py-1 rounded
                    {{ $user->status === 'aktif' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-600' }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>

        {{-- Info Detail --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Bergabung</div>
                <div class="font-semibold text-gray-800 text-sm">{{ $user->created_at->format('d M Y') }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Kecamatan</div>
                <div class="font-semibold text-gray-800 text-sm">{{ $user->kecamatan ?? '-' }}</div>
            </div>
            @if($user->tanggal_lahir)
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Tanggal Lahir</div>
                <div class="font-semibold text-gray-800 text-sm">{{ $user->tanggal_lahir->format('d M Y') }}</div>
            </div>
            @endif
            @if($user->jenis_kelamin)
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Jenis Kelamin</div>
                <div class="font-semibold text-gray-800 text-sm capitalize">{{ $user->jenis_kelamin }}</div>
            </div>
            @endif
            @if($user->isPembeli())
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Total Pesanan</div>
                <div class="font-semibold text-gray-800 text-sm">{{ $user->pesanans->count() }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Total Belanja</div>
                <div class="font-semibold text-gray-800 text-sm">
                    Rp {{ number_format($user->pesanans->where('status_bayar','lunas')->sum('total'), 0, ',', '.') }}
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Poin Reward</div>
                <div class="font-semibold text-blue-600 text-sm">{{ $user->total_poin }} poin</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs text-gray-400 mb-0.5">Total Ulasan</div>
                <div class="font-semibold text-gray-800 text-sm">{{ $user->ulasans->count() }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Info Toko (jika penjual) --}}
    @if($user->isPenjual() && $user->toko)
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800">Toko UMKM</h3>
            <a href="{{ route('admin.umkm.show', $user->toko->id) }}"
                class="text-xs text-teal-600 font-semibold hover:underline">
                Lihat Detail Toko →
            </a>
        </div>
        <div class="flex items-center gap-3 p-3 bg-teal-50 rounded-lg">
            <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($user->toko->nama_toko, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-bold text-gray-800">{{ $user->toko->nama_toko }}</div>
                <div class="text-xs text-gray-500">
                    {{ $user->toko->kategori_friendly }} · {{ $user->toko->kecamatan }}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">
                    ★ {{ $user->toko->rating }} · {{ $user->toko->total_pesanan }} pesanan ·
                    Saldo: Rp {{ number_format($user->toko->saldo, 0, ',', '.') }}
                </div>
            </div>
            @php
                $tokoStatusClass = match($user->toko->status) {
                    'aktif'          => 'bg-teal-100 text-teal-700',
                    'pending'        => 'bg-amber-100 text-amber-700',
                    'menunggu_dinas' => 'bg-blue-100 text-blue-700',
                    'ditolak'        => 'bg-red-100 text-red-600',
                    default          => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <span class="text-xs font-bold px-2 py-1 rounded {{ $tokoStatusClass }} flex-shrink-0">
                {{ ucfirst(str_replace('_', ' ', $user->toko->status)) }}
            </span>
        </div>
    </div>
    @endif

    {{-- Pesanan Terbaru (pembeli) --}}
    @if($user->isPembeli() && $user->pesanans->count())
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800">Pesanan Terbaru</h3>
            <span class="text-xs text-gray-400">{{ $user->pesanans->count() }} total</span>
        </div>
        <div class="space-y-2">
            @foreach($user->pesanans->sortByDesc('created_at')->take(5) as $pesanan)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                <div>
                    <div class="text-sm font-semibold text-gray-800 font-mono">{{ $pesanan->kode_pesanan }}</div>
                    <div class="text-xs text-gray-400">
                        {{ $pesanan->toko->nama_toko ?? '-' }} · {{ $pesanan->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-bold px-2 py-0.5 rounded
                        {{ match($pesanan->status_pesanan) {
                            'selesai'    => 'bg-teal-100 text-teal-700',
                            'dikirim'    => 'bg-indigo-100 text-indigo-700',
                            'diproses'   => 'bg-blue-100 text-blue-700',
                            'menunggu'   => 'bg-amber-100 text-amber-700',
                            'dibatalkan' => 'bg-red-100 text-red-600',
                            default      => 'bg-gray-100 text-gray-500',
                        } }}">
                        {{ ucfirst($pesanan->status_pesanan) }}
                    </span>
                    <span class="text-sm font-bold text-gray-800">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </span>
                    <a href="{{ route('admin.transaksi.show', $pesanan->id) }}"
                        class="text-xs text-blue-600 font-semibold hover:underline">
                        Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Ulasan Terbaru --}}
    @if($user->ulasans->count())
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800">Ulasan Ditulis</h3>
            <span class="text-xs text-gray-400">{{ $user->ulasans->count() }} ulasan</span>
        </div>
        <div class="space-y-2">
            @foreach($user->ulasans->sortByDesc('created_at')->take(3) as $ulasan)
            <div class="flex items-start gap-2 py-2 border-b border-gray-100 last:border-none">
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold text-gray-700 truncate">
                        {{ $ulasan->produk->nama ?? '-' }}
                    </div>
                    <div class="text-amber-400 text-xs">
                        {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                    </div>
                    @if($ulasan->komentar)
                    <p class="text-xs text-gray-500 truncate">{{ $ulasan->komentar }}</p>
                    @endif
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">
                    {{ $ulasan->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Aksi Admin --}}
    @if($user->id !== auth()->id())
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-bold text-gray-800 mb-4">Aksi Admin</h3>
        <div class="flex gap-3 flex-wrap">

            {{-- Toggle status --}}
            <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}">
                @csrf @method('PATCH')
                <button class="font-bold px-5 py-2.5 rounded-lg transition text-sm
                    {{ $user->status === 'aktif'
                        ? 'bg-red-100 text-red-700 hover:bg-red-600 hover:text-white'
                        : 'bg-teal-100 text-teal-700 hover:bg-teal-600 hover:text-white' }}">
                    {{ $user->status === 'aktif' ? '🚫 Blokir User' : '✓ Aktifkan User' }}
                </button>
            </form>

            {{-- Reset password --}}
            <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}"
                onsubmit="return confirm('Reset password user ini? Password baru akan dikirim ke email mereka.')">
                @csrf @method('PATCH')
                <button class="bg-amber-100 text-amber-700 font-bold px-5 py-2.5 rounded-lg hover:bg-amber-600 hover:text-white transition text-sm">
                    🔑 Reset Password
                </button>
            </form>

            {{-- Hapus --}}
            @if(!$user->isPenjual() && !$user->isAdmin())
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                onsubmit="return confirm('Hapus user ini secara permanen? Tindakan tidak bisa dibatalkan!')">
                @csrf @method('DELETE')
                <button class="bg-gray-100 text-gray-500 font-bold px-5 py-2.5 rounded-lg hover:bg-red-600 hover:text-white transition text-sm">
                    🗑 Hapus User
                </button>
            </form>
            @endif
        </div>

        @if($user->status === 'diblokir')
        <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
            ⚠ User ini sedang diblokir dan tidak bisa login ke platform.
        </div>
        @endif
    </div>
    @else
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-700">
        ℹ️ Ini adalah akun admin Anda sendiri. Aksi moderasi tidak tersedia.
    </div>
    @endif

</div>
</div>
@endsection