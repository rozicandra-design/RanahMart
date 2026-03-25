@extends('layouts.auth')
@section('title', 'Daftar UMKM — Step 1 dari 3')

@section('content')

<h2 class="text-lg font-bold text-gray-800 mb-1 text-center">Daftarkan UMKM Kamu</h2>
<p class="text-xs text-gray-500 text-center mb-5">Isi data dengan benar untuk mempercepat verifikasi</p>

{{-- Step Indicator --}}
<div class="flex items-center mb-6">
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">1</div>
        <div class="text-xs text-red-600 font-bold mt-1">Data Akun</div>
    </div>
    <div class="flex-1 h-0.5 bg-gray-200 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">2</div>
        <div class="text-xs text-gray-400 mt-1">Data Usaha</div>
    </div>
    <div class="flex-1 h-0.5 bg-gray-200 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div>
        <div class="text-xs text-gray-400 mt-1">Dokumen</div>
    </div>
</div>

<form method="POST" action="{{ route('register.umkm.step1') }}" class="space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Depan *</label>
            <input type="text" name="nama_depan" value="{{ old('nama_depan') }}" required
                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-red-500
                       {{ $errors->has('nama_depan') ? 'border-red-400 bg-red-50' : '' }}"
                placeholder="Uni">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Belakang *</label>
            <input type="text" name="nama_belakang" value="{{ old('nama_belakang') }}" required
                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-red-500
                       {{ $errors->has('nama_belakang') ? 'border-red-400 bg-red-50' : '' }}"
                placeholder="Rani">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email *</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="email@contoh.com">
        <p class="text-xs text-gray-400 mt-1">Akan digunakan untuk login dan notifikasi</p>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">No. WhatsApp Aktif *</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('no_hp') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="08xxxxxxxxxx">
        <p class="text-xs text-gray-400 mt-1">Untuk konfirmasi dan komunikasi dengan pembeli</p>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kata Sandi *</label>
        <input type="password" name="password" required minlength="8"
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Minimal 8 karakter">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi *</label>
        <input type="password" name="password_confirmation" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="Ulangi kata sandi">
    </div>

    <button type="submit"
        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center gap-2">
        Lanjut ke Data Usaha
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>

</form>

<div class="text-center mt-5 text-sm text-gray-500">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-red-600 font-bold hover:underline">Masuk</a>
</div>

@endsection