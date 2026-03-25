@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')

<h2 class="text-xl font-bold text-gray-800 mb-1 text-center">Masuk ke RanahMart</h2>
<p class="text-sm text-gray-500 text-center mb-6">Selamat datang kembali!</p>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                   {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="email@contoh.com">
    </div>

    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-bold text-gray-700">Kata Sandi</label>
            <a href="#" class="text-xs text-red-600 hover:underline">Lupa kata sandi?</a>
        </div>
        <input type="password" name="password" required
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Minimal 6 karakter">
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 accent-red-600">
            Ingat saya
        </label>
    </div>

    <button type="submit"
        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-sm">
        Masuk
    </button>

</form>

{{-- Divider --}}
<div class="relative flex items-center my-5">
    <div class="flex-1 border-t border-gray-200"></div>
    <span class="mx-3 text-xs text-gray-400">Belum punya akun?</span>
    <div class="flex-1 border-t border-gray-200"></div>
</div>

{{-- Register Options --}}
<div class="grid grid-cols-2 gap-3">
    <a href="{{ route('register.pembeli') }}"
        class="flex flex-col items-center gap-1.5 p-3 border-2 border-gray-200 rounded-xl
               hover:border-blue-400 hover:bg-blue-50 transition group text-center">
        <span class="text-2xl">🛒</span>
        <span class="text-xs font-bold text-gray-700 group-hover:text-blue-700">Daftar Pembeli</span>
        <span class="text-xs text-gray-400">Belanja produk UMKM</span>
    </a>
    <a href="{{ route('register.umkm') }}"
        class="flex flex-col items-center gap-1.5 p-3 border-2 border-gray-200 rounded-xl
               hover:border-teal-400 hover:bg-teal-50 transition group text-center">
        <span class="text-2xl">🏪</span>
        <span class="text-xs font-bold text-gray-700 group-hover:text-teal-700">Daftar UMKM</span>
        <span class="text-xs text-gray-400">Jual produk kamu</span>
    </a>
</div>

@endsection