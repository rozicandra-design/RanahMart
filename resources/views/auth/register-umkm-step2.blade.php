@extends('layouts.auth')
@section('title', 'Daftar UMKM — Step 2 dari 3')

@section('content')

<h2 class="text-lg font-bold text-gray-800 mb-1 text-center">Data Usaha UMKM</h2>
<p class="text-xs text-gray-500 text-center mb-5">Lengkapi informasi toko dan usaha kamu</p>

{{-- Step Indicator --}}
<div class="flex items-center mb-6">
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="text-xs text-teal-600 font-bold mt-1">Data Akun</div>
    </div>
    <div class="flex-1 h-0.5 bg-red-400 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">2</div>
        <div class="text-xs text-red-600 font-bold mt-1">Data Usaha</div>
    </div>
    <div class="flex-1 h-0.5 bg-gray-200 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div>
        <div class="text-xs text-gray-400 mt-1">Dokumen</div>
    </div>
</div>

<form method="POST" action="{{ route('register.umkm.step2.store') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Toko / Usaha *</label>
        <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('nama_toko') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Contoh: Dapur Uni Rani">
        <p class="text-xs text-gray-400 mt-1">Nama yang akan tampil ke pembeli</p>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kategori Usaha *</label>
        <select name="kategori" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm bg-white
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('kategori') ? 'border-red-400 bg-red-50' : '' }}">
            <option value="">Pilih kategori usaha</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
            <option value="{{ $slug }}" {{ old('kategori') === $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kecamatan *</label>
        <select name="kecamatan" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm bg-white
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('kecamatan') ? 'border-red-400 bg-red-50' : '' }}">
            <option value="">Pilih kecamatan</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
            <option value="{{ $kec }}" {{ old('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap *</label>
        <textarea name="alamat_lengkap" rows="2" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm resize-none
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('alamat_lengkap') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Jl. Veteran No. 12, RT 03/RW 01...">{{ old('alamat_lengkap') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Deskripsi Singkat Usaha</label>
        <textarea name="deskripsi" rows="2"
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="Ceritakan tentang usaha kamu, produk unggulan, dan keunikannya...">{{ old('deskripsi') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">NIB (Nomor Induk Berusaha)</label>
        <input type="text" name="nib" value="{{ old('nib') }}"
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="Opsional — jika sudah punya">
        <p class="text-xs text-gray-400 mt-1">NIB mempercepat proses verifikasi Dinas</p>
    </div>

    <button type="submit"
        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center gap-2">
        Lanjut ke Upload Dokumen
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>

</form>

@endsection