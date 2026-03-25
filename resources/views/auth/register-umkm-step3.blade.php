@extends('layouts.auth')
@section('title', 'Daftar UMKM — Step 3 dari 3')

@section('content')

<h2 class="text-lg font-bold text-gray-800 mb-1 text-center">Dokumen & Rekening</h2>
<p class="text-xs text-gray-500 text-center mb-5">Langkah terakhir — upload dokumen pelengkap</p>

{{-- Step Indicator --}}
<div class="flex items-center mb-6">
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="text-xs text-teal-600 font-bold mt-1">Data Akun</div>
    </div>
    <div class="flex-1 h-0.5 bg-teal-400 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="text-xs text-teal-600 font-bold mt-1">Data Usaha</div>
    </div>
    <div class="flex-1 h-0.5 bg-red-400 mx-2 mb-4"></div>
    <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">3</div>
        <div class="text-xs text-red-600 font-bold mt-1">Dokumen</div>
    </div>
</div>

<form method="POST" action="{{ route('register.umkm.step3.store') }}"
    enctype="multipart/form-data" class="space-y-4">
    @csrf

    {{-- Foto KTP --}}
    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">
            Foto KTP Pemilik *
            <span class="text-gray-400 font-normal">(maks. 5MB)</span>
        </label>
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-red-400 transition">
            <input type="file" name="foto_ktp" accept="image/*" required id="ktp-input"
                class="w-full text-sm text-gray-600 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">JPG, PNG · Pastikan foto jelas dan tidak terpotong</p>
        </div>
        @error('foto_ktp')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Foto Produk --}}
    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">
            Foto Produk *
            <span class="text-gray-400 font-normal">(min. 1, maks. 5 foto · maks. 5MB/foto)</span>
        </label>
        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-red-400 transition">
            <input type="file" name="foto_produk[]" accept="image/*" multiple required id="produk-input"
                class="w-full text-sm text-gray-600 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">Upload foto produk terbaik kamu · Foto pertama jadi foto utama</p>
        </div>
        <div id="produk-preview" class="flex gap-2 flex-wrap mt-2"></div>
        @error('foto_produk')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Rekening --}}
    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Bank *</label>
        <select name="bank" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm bg-white
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('bank') ? 'border-red-400 bg-red-50' : '' }}">
            <option value="">Pilih bank</option>
            @foreach(['BRI', 'BNI', 'BCA', 'Mandiri', 'BSI', 'Bank Nagari', 'CIMB Niaga', 'Permata'] as $bank)
            <option value="{{ $bank }}" {{ old('bank') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Rekening *</label>
        <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('no_rekening') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Nomor rekening aktif">
    </div>

    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1.5">Atas Nama Rekening *</label>
        <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening') }}" required
            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-red-500
                   {{ $errors->has('atas_nama_rekening') ? 'border-red-400 bg-red-50' : '' }}"
            placeholder="Nama sesuai buku tabungan">
    </div>

    {{-- Info --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-700 space-y-1">
        <div class="font-bold mb-1">📋 Proses setelah pendaftaran:</div>
        <div>1. Admin RanahMart meninjau data (1–2 hari kerja)</div>
        <div>2. Dinas Koperasi & UMKM melakukan verifikasi</div>
        <div>3. Toko aktif dan produk bisa dijual!</div>
        <div class="mt-1 text-amber-600">Kamu akan menerima notifikasi melalui WhatsApp & email.</div>
    </div>

    {{-- Persetujuan --}}
    <label class="flex items-start gap-2.5 cursor-pointer">
        <input type="checkbox" name="setuju" required value="1"
            class="mt-0.5 w-4 h-4 rounded border-gray-300 accent-red-600 flex-shrink-0">
        <span class="text-xs text-gray-600 leading-relaxed">
            Saya menyatakan bahwa semua data yang diisi adalah <strong>benar dan dapat dipertanggungjawabkan</strong>,
            serta menyetujui
            <a href="#" class="text-red-600 hover:underline font-semibold">Syarat & Ketentuan</a>
            Penjual RanahMart.
        </span>
    </label>

    <button type="submit"
        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center gap-2">
        🚀 Kirim Pendaftaran UMKM
    </button>

</form>

@push('scripts')
<script>
document.getElementById('produk-input').addEventListener('change', function(e) {
    const preview = document.getElementById('produk-preview');
    preview.innerHTML = '';
    Array.from(e.target.files).slice(0, 5).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = ev => {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${ev.target.result}"
                    class="w-14 h-14 object-cover rounded-lg border-2 ${i === 0 ? 'border-red-500' : 'border-gray-200'}">
                ${i === 0 ? '<span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1 rounded font-bold">✓</span>' : ''}
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush

@endsection