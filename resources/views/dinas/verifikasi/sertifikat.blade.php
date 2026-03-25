@extends('layouts.dashboard')
@section('title', 'Terbitkan Sertifikat')
@section('page-title', 'Terbitkan Sertifikat')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6 w-full">
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Kolom Kiri: Form --}}
    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Data Sertifikat</h3>
            <form method="POST" action="{{ route('dinas.verifikasi.sertifikat.simpan', $toko->id) }}"
                id="form-sertifikat" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Sertifikat</label>
                    <input type="text" name="no_sertifikat"
                        value="{{ old('no_sertifikat', $toko->no_sertifikat) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kepala Dinas</label>
                    <input type="text" name="nama_kepala_dinas"
                        value="{{ old('nama_kepala_dinas', $toko->nama_kepala_dinas ?? 'Ir. H. Ahmad Fauzi, M.Si') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jabatan</label>
                    <input type="text" name="jabatan_kepala_dinas"
                        value="{{ old('jabatan_kepala_dinas', $toko->jabatan_kepala_dinas ?? 'Kepala Dinas Koperasi & UMKM Kota Padang') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_sertifikat"
                            value="{{ old('tanggal_sertifikat', now()->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Berlaku Hingga</label>
                        <input type="date" name="kadaluarsa_sertifikat"
                            value="{{ old('kadaluarsa_sertifikat', now()->addYear()->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
            </form>
        </div>

        {{-- Info Toko --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-3">Info Toko</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Toko</span>
                    <span class="font-semibold text-gray-800">{{ $toko->nama_toko }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pemilik</span>
                    <span class="font-semibold text-gray-800">{{ $toko->user->nama_lengkap ?? $toko->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">NIB</span>
                    <span class="font-semibold text-gray-800">{{ $toko->nib ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kategori</span>
                    <span class="font-semibold text-gray-800">{{ $toko->kategori_friendly }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kecamatan</span>
                    <span class="font-semibold text-gray-800">{{ $toko->kecamatan }}</span>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3">
            <a href="{{ route('dinas.verifikasi.show', $toko->id) }}"
                class="flex-1 text-center border border-gray-300 text-gray-600 font-semibold py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                ← Kembali
            </a>
            <button type="submit" form="form-sertifikat"
                class="flex-1 bg-purple-600 text-white font-bold py-2.5 rounded-lg text-sm hover:bg-purple-700 transition">
                ✓ Terbitkan &amp; Kirim ke Penjual
            </button>
        </div>

        <a href="{{ route('dinas.verifikasi.sertifikat.pdf', $toko->id) }}" target="_blank"
            class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white font-bold py-2.5 rounded-lg text-sm hover:bg-gray-900 transition">
            📄 Download PDF Sertifikat
        </a>
    </div>

    {{-- Kolom Kanan: Preview --}}
    <div>
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Sertifikat</div>
            <button type="button" onclick="refreshPreview()"
                class="text-xs bg-purple-100 text-purple-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-purple-200 transition">
                🔄 Refresh Preview
            </button>
        </div>
        <div class="border border-gray-200 rounded-xl overflow-hidden" style="height:700px;background:#e0e0e0;">
            <iframe id="preview-iframe"
                src="{{ route('dinas.verifikasi.sertifikat.pdf', $toko->id) }}"
                class="w-full h-full border-0">
            </iframe>
        </div>
    </div>

</div>
</div>

<script>
function refreshPreview() {
    const iframe = document.getElementById('preview-iframe');
    iframe.src = iframe.src.split('?')[0] + '?t=' + Date.now();
}
</script>
@endsection