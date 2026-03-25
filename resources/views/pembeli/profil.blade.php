@extends('layouts.dashboard')
@section('title', 'Profil & Akun')
@section('page-title', 'Profil & Keamanan')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Header Info User --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6 flex items-center gap-5">
        <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-3xl overflow-hidden flex-shrink-0 ring-4 ring-blue-50">
            @if($user->foto_profil)
                <img src="{{ Storage::url($user->foto_profil) }}" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($user->nama_depan, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="font-bold text-gray-900 text-xl">{{ $user->nama_lengkap }}</div>
            <div class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</div>
            <span class="inline-block mt-2 bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">
                🎁 {{ $user->total_poin }} poin
            </span>
        </div>
    </div>

    {{-- 2 Kolom --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- Kolom Kiri: Edit Profil --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-800 text-base mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Informasi Pribadi
            </h3>

            <form method="POST" action="{{ route('pembeli.profil.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Foto Profil</label>
                    <input type="file" name="foto_profil" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600
                        file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                        file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Depan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_depan" value="{{ old('nama_depan', $user->nama_depan) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Belakang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_belakang" value="{{ old('nama_belakang', $user->nama_belakang) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">No. WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="08xxxxxxxxxx">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Pilih</option>
                            <option value="laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 active:scale-95 transition-all text-sm mt-1">
                    Simpan Profil
                </button>
            </form>
        </div>

        {{-- Kolom Kanan: Ubah Password --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-800 text-base mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                Ubah Kata Sandi
            </h3>

            <form method="POST" action="{{ route('pembeli.profil.password') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Kata Sandi Lama <span class="text-red-500">*</span></label>
                    <input type="password" name="password_lama" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Kata Sandi Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                {{-- Tips Keamanan --}}
                <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500 space-y-1">
                    <div class="font-bold text-gray-600 mb-1">Tips kata sandi kuat:</div>
                    <div>✅ Minimal 8 karakter</div>
                    <div>✅ Kombinasi huruf besar & kecil</div>
                    <div>✅ Mengandung angka atau simbol</div>
                </div>

                <button type="submit"
                    class="w-full bg-gray-800 text-white font-bold py-2.5 rounded-lg hover:bg-gray-900 active:scale-95 transition-all text-sm">
                    Ubah Kata Sandi
                </button>
            </form>
        </div>

    </div>
</div>
@endsection