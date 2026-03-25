@extends('layouts.dashboard')
@section('title', 'Pengaturan Portal Dinas')
@section('page-title', 'Pengaturan Portal Dinas')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Notifikasi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Preferensi Notifikasi</h3>
            <form method="POST" action="{{ route('dinas.pengaturan.simpan') }}" class="space-y-4">
                @csrf @method('PUT')

                @foreach([
                    ['label' => 'Notifikasi UMKM Baru Masuk',     'sub' => 'Email saat ada pendaftaran UMKM baru untuk diverifikasi',       'key' => 'notif_umkm_baru'],
                    ['label' => 'Laporan Mingguan Otomatis',       'sub' => 'Email rekap UMKM setiap Senin pagi',                            'key' => 'notif_laporan_mingguan'],
                    ['label' => 'Pengingat Sertifikat Kadaluarsa', 'sub' => 'Notifikasi 30 hari sebelum sertifikat UMKM habis masa berlaku', 'key' => 'notif_sertifikat'],
                    ['label' => 'Laporan Bulanan',                 'sub' => 'Rekap bulanan dikirim ke email pada tanggal 1 setiap bulan',     'key' => 'notif_laporan_bulanan'],
                ] as $notif)
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $notif['label'] }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $notif['sub'] }}</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" name="{{ $notif['key'] }}" value="1" checked class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-purple-600 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                @endforeach

                <button type="submit"
                    class="w-full bg-purple-600 text-white font-bold px-5 py-2.5 rounded-lg hover:bg-purple-700 transition text-sm">
                    Simpan Pengaturan Notifikasi
                </button>
            </form>
        </div>

        {{-- Standar Verifikasi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Standar Dokumen Verifikasi</h3>
            <form method="POST" action="{{ route('dinas.pengaturan.simpan') }}" class="space-y-3">
                @csrf @method('PUT')
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Dokumen Wajib
                </div>
                <div class="space-y-2">
                    @foreach([
                        ['label' => 'NIB (Nomor Induk Berusaha)',   'key' => 'req_nib',       'default' => true],
                        ['label' => 'Foto KTP Pemilik',             'key' => 'req_ktp',       'default' => true],
                        ['label' => 'Foto Produk (min. 3)',         'key' => 'req_foto',      'default' => true],
                        ['label' => 'SKU / Sertifikat Halal',       'key' => 'req_sku',       'default' => false],
                        ['label' => 'SIUP / Izin Usaha',            'key' => 'req_siup',      'default' => false],
                        ['label' => 'Foto Tempat Produksi',         'key' => 'req_foto_prod', 'default' => false],
                    ] as $dok)
                    <label class="flex items-center gap-3 cursor-pointer py-1.5 border-b border-gray-100 last:border-none">
                        <input type="checkbox" name="{{ $dok['key'] }}" value="1"
                            {{ $dok['default'] ? 'checked' : '' }}
                            class="w-4 h-4 accent-purple-600 rounded">
                        <span class="text-sm text-gray-700">{{ $dok['label'] }}</span>
                        @if($dok['default'])
                        <span class="text-xs text-purple-600 font-semibold ml-auto">Wajib</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">
                        Masa Berlaku Sertifikat (bulan)
                    </label>
                    <input type="number" name="masa_berlaku_sertifikat" value="12" min="1" max="60"
                        class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <p class="text-xs text-gray-400 mt-0.5">Default: 12 bulan (1 tahun)</p>
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 text-white font-bold px-5 py-2.5 rounded-lg hover:bg-purple-700 transition text-sm">
                    Simpan Standar Verifikasi
                </button>
            </form>
        </div>

        {{-- Info Akun --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 md:col-span-2">
            <h3 class="font-bold text-gray-800 mb-3">Informasi Akun Dinas</h3>
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-purple-200 flex items-center justify-center text-purple-700 font-bold text-lg">
                        {{ strtoupper(substr(auth()->user()->nama_depan ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ auth()->user()->nama_lengkap ?? auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        <div class="text-xs text-purple-600 font-semibold mt-0.5">
                            Dinas Koperasi & UMKM Kota Padang
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                Untuk mengubah data akun atau password, hubungi administrator sistem.
            </div>
        </div>

    </div>

</div>
@endsection