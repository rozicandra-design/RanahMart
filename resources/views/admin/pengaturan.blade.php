@extends('layouts.dashboard')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('sidebar') @include('components.sidebar-admin') @endsection

@section('content')
<div class="w-full p-6">

    @if(session('success'))
    <div class="mb-5 flex items-center gap-2 bg-teal-50 border border-teal-200 text-teal-700 text-sm px-4 py-3 rounded-xl">
        <span>✅</span> {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.pengaturan.simpan') }}">
        @csrf

        <div class="space-y-5">

            {{-- Baris 1: Akun Admin (penuh) --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-bold text-gray-800 mb-4">Akun Admin</h3>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg mb-3">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-amber-400 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nama_depan, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->nama_lengkap }}</div>
                        <div class="text-xs text-gray-400">{{ auth()->user()->email }} · Super Admin</div>
                    </div>
                </div>
                <div class="text-xs text-gray-400">
                    Untuk menambah admin baru, hubungi developer sistem.
                </div>
            </div>

            {{-- Baris 2: 3 Kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Kolom 1: Kebijakan Platform --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Kebijakan Platform</h3>
                    <div class="space-y-4">
                        @foreach([
                            ['label' => 'Registrasi UMKM Baru',  'key' => 'registrasi_aktif',    'sub' => 'Izinkan pendaftaran UMKM baru masuk ke sistem'],
                            ['label' => 'Review Produk Wajib',   'key' => 'review_produk_wajib', 'sub' => 'Setiap produk baru harus disetujui admin sebelum tayang'],
                            ['label' => 'Mode Maintenance',      'key' => 'mode_maintenance',    'sub' => 'Matikan sementara akses publik ke seluruh platform'],
                        ] as $s)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-800">{{ $s['label'] }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $s['sub'] }}</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="hidden" name="{{ $s['key'] }}" value="0">
                                <input type="checkbox" name="{{ $s['key'] }}" value="1"
                                    {{ ($config[$s['key']] ?? false) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-amber-500 transition
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5
                                    after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom 2: Komisi & Biaya --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Komisi & Biaya</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Komisi Transaksi (%)</label>
                            <input type="number" name="komisi_persen"
                                value="{{ old('komisi_persen', $config['komisi_persen'] ?? 3) }}"
                                min="0" max="30" step="0.5"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 @error('komisi_persen') border-red-400 @enderror">
                            <p class="text-xs text-gray-400 mt-0.5">Persentase dipotong dari setiap transaksi</p>
                            @error('komisi_persen') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Biaya Iklan Minimum (Rp)</label>
                            <input type="number" name="iklan_min_biaya"
                                value="{{ old('iklan_min_biaya', $config['iklan_min_biaya'] ?? 50000) }}"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 @error('iklan_min_biaya') border-red-400 @enderror">
                            @error('iklan_min_biaya') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Kolom 3: Paket Iklan --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Paket Iklan</h3>
                    <div class="space-y-3">
                        @foreach(config('ranahmart.iklan_paket') as $nama => $paket)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="text-sm font-semibold text-gray-800 capitalize">{{ $nama }}</div>
                                <div class="text-xs text-gray-500">{{ $paket['durasi'] }} hari</div>
                            </div>
                            <div class="font-bold text-gray-800 text-sm">
                                Rp {{ number_format($paket['harga'], 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>{{-- end grid --}}

            {{-- Tombol Simpan --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-amber-500 text-white font-bold px-6 py-2.5 rounded-lg hover:bg-amber-600 transition text-sm">
                    Simpan Konfigurasi
                </button>
            </div>

        </div>
    </form>

</div>
@endsection