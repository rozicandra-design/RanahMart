@extends('layouts.dashboard')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6 max-w-2xl">

    <a href="{{ route('penjual.produk.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        ← Kembali ke Produk
    </a>

    @if($produk->status === 'ditolak')
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-sm text-red-700">
        <strong>Produk ditolak.</strong>
        @if($produk->catatan_review)
        Alasan: {{ $produk->catatan_review }}
        @endif
        Perbaiki dan simpan untuk dikirim ulang ke review.
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="font-bold text-gray-800 mb-5">Edit Produk</h2>

        <form method="POST" action="{{ route('penjual.produk.update', $produk->id) }}"
            enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            {{-- Kategori --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                            <option value="{{ $slug }}" {{ old('kategori', $produk->kategori) == $slug ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Status</label>
                    <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        <option value="aktif"    {{ old('status', $produk->status) == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $produk->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="habis"    {{ old('status', $produk->status) == 'habis'    ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                    placeholder="Bahan, ukuran, cara pemakaian, dll">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>

            {{-- Harga --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga', (int)$produk->harga) }}" required min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Harga Coret (Rp)</label>
                    <input type="number" name="harga_coret" value="{{ old('harga_coret', (int)$produk->harga_coret) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Opsional">
                </div>
            </div>

            {{-- Stok, Berat, SKU --}}
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Berat (gram)</label>
                    <input type="number" name="berat" value="{{ old('berat', $produk->berat) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $produk->sku) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Opsional">
                </div>
            </div>

            {{-- Foto --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Foto Produk</label>

                {{-- Foto saat ini --}}
                @if($produk->foto)
                <div class="mb-3">
                    <div class="text-xs text-gray-400 mb-1.5">Foto saat ini:</div>
                    <img src="{{ Storage::url($produk->foto) }}"
                        class="h-28 w-28 object-cover rounded-xl border-2 border-gray-200">
                </div>
                @endif

                <label for="foto-edit-input"
                    class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-teal-400 hover:bg-teal-50 transition cursor-pointer flex flex-col items-center gap-1 block">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-600">
                        {{ $produk->foto ? 'Ganti foto' : 'Upload foto' }}
                    </span>
                    <span class="text-xs text-gray-400">JPG, PNG · Maks. 2MB · Kosongkan untuk tidak mengganti</span>
                    <input type="file" name="foto" accept="image/*" class="hidden" id="foto-edit-input">
                </label>

                {{-- Preview foto baru --}}
                <div id="foto-edit-preview" class="mt-3 hidden">
                    <div class="text-xs text-gray-400 mb-1.5">Foto baru:</div>
                    <img id="foto-edit-img" class="h-28 w-28 object-cover rounded-xl border-2 border-teal-300">
                </div>
            </div>

            {{-- Error messages --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('penjual.produk.index') }}"
                    class="px-5 border border-gray-300 text-gray-600 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('foto-edit-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('foto-edit-img').src = ev.target.result;
        document.getElementById('foto-edit-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush