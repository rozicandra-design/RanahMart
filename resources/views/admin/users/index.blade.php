@extends('layouts.dashboard')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="p-6">

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        @php
            $totalPembeli = \App\Models\User::where('role','pembeli')->count();
            $totalPenjual = \App\Models\User::where('role','penjual')->count();
            $aktifHariIni = \App\Models\User::whereDate('updated_at', today())->count();
            $diblokir     = \App\Models\User::where('status','diblokir')->count();
        @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-800">{{ number_format($totalPembeli) }}</div>
            <div class="text-xs text-gray-400 mt-1">Total Pembeli</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-800">{{ number_format($totalPenjual) }}</div>
            <div class="text-xs text-gray-400 mt-1">Total Penjual UMKM</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $aktifHariIni }}</div>
            <div class="text-xs text-gray-400 mt-1">Aktif Hari Ini</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-red-500">{{ $diblokir }}</div>
            <div class="text-xs text-gray-400 mt-1">Diblokir</div>
        </div>
    </div>

    {{-- Filter & Cari --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama, email, atau no. HP..."
            class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="pembeli" {{ request('role') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
            <option value="penjual" {{ request('role') === 'penjual' ? 'selected' : '' }}>Penjual UMKM</option>
            <option value="dinas"   {{ request('role') === 'dinas'   ? 'selected' : '' }}>Dinas</option>
            <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
        </select>
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="diblokir" {{ request('status') === 'diblokir' ? 'selected' : '' }}>Diblokir</option>
        </select>
        <button class="bg-gray-800 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-900 transition">
            Cari
        </button>
        <a href="{{ route('admin.users.index') }}"
            class="bg-white border border-gray-300 text-gray-600 font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            Reset
        </a>
    </form>

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->nama_depan, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->no_hp }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $user->email }}</td>
                        <td class="px-4 py-3">
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
                            <span class="text-xs font-bold px-2 py-0.5 rounded {{ $roleClass }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $user->kecamatan ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-0.5 rounded
                                {{ $user->status === 'aktif' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-600' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-1 rounded hover:bg-gray-200 transition">
                                    Detail
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs font-semibold px-2 py-1 rounded transition
                                        {{ $user->status === 'aktif'
                                            ? 'bg-red-100 text-red-600 hover:bg-red-600 hover:text-white'
                                            : 'bg-teal-100 text-teal-700 hover:bg-teal-600 hover:text-white' }}">
                                        {{ $user->status === 'aktif' ? 'Blokir' : 'Aktifkan' }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400 text-sm">
                            Tidak ada user ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection