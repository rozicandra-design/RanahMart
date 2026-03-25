@extends('layouts.auth')
@section('title', 'Daftar Pembeli')

@section('content')

<h2 class="text-xl font-bold text-gray-800 mb-1 text-center">Daftar sebagai Pembeli</h2>
<p class="text-sm text-gray-500 text-center mb-6">Belanja produk UMKM Kota Padang</p>

<form method="POST" action="{{ route('register.pembeli') }}" class="space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Depan *</label>
            <input type="text" name="nama_depan" value="{{ old('nama_depan') }}" required
                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       {{ $errors->has('nama_depan') ? 'border-red-400 bg-red-50' : '' }}"
                placeholder="Budi">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Belakang *</label>
            <input type="text" name="nama_belakang" value="{{ old('nama_belakang') }}" required
                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       {{ $errors->has('nama_belakang') ? 'border-red-400 bg-red-50' : '' }}"
                placeholder="Santoso">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email *</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-blue-500
                   {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="email@contoh.com">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">No. WhatsApp *</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-blue-500
                   {{ $errors->has('no_hp') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="08xxxxxxxxxx">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kata Sandi *</label>
        <input type="password" name="password" required minlength="8"
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-blue-500
                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Minimal 8 karakter">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi *</label>
        <input type="password" name="password_confirmation" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Ulangi kata sandi">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kecamatan</label>
        <select name="kecamatan"
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih kecamatan (opsional)</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
            <option value="{{ $kec }}" {{ old('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
    </div>

    <label class="flex items-start gap-2.5 cursor-pointer">
        <input type="checkbox" name="setuju" required value="1"
            class="mt-0.5 w-4 h-4 rounded border-gray-300 accent-blue-600 flex-shrink-0">
        <span class="text-xs text-gray-600 leading-relaxed">
            Saya menyetujui
            <a href="#" class="text-blue-600 hover:underline font-semibold">Syarat & Ketentuan</a>
            dan
            <a href="#" class="text-blue-600 hover:underline font-semibold">Kebijakan Privasi</a>
            RanahMart.
        </span>
    </label>

    <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm">
        Buat Akun Pembeli
    </button>

</form>

<div class="text-center mt-5 text-sm text-gray-500">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-red-600 font-bold hover:underline">Masuk</a>
</div>

<div class="text-center mt-2 text-sm text-gray-500">
    Punya usaha?
    <a href="{{ route('register.umkm') }}" class="text-teal-600 font-bold hover:underline">Daftar UMKM →</a>
</div>

@endsection