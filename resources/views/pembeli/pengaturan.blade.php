@extends('layouts.dashboard')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Akun')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    @if(session('success'))
    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="20 6 9 12 4 10"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('pembeli.pengaturan.simpan') }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-2 gap-6">

            {{-- Kolom Kiri: Preferensi Notifikasi --}}
            <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
                <div class="px-5 py-4">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        Preferensi Notifikasi
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih notifikasi yang ingin kamu terima</p>
                </div>

                @foreach([
                    ['key' => 'notif_pesanan_dikonfirmasi', 'label' => 'Pesanan dikonfirmasi penjual', 'sub' => 'Pemberitahuan saat penjual memproses pesanan'],
                    ['key' => 'notif_pesanan_dikirim',      'label' => 'Pesanan sedang dikirim',       'sub' => 'Notifikasi saat paket dikirim beserta resi'],
                    ['key' => 'notif_pesanan_selesai',      'label' => 'Pesanan tiba / selesai',       'sub' => 'Pengingat untuk konfirmasi pesanan diterima'],
                    ['key' => 'notif_promo',                'label' => 'Promo & voucher baru',         'sub' => 'Info diskon dan voucher eksklusif RanahMart'],
                    ['key' => 'notif_flash_sale',           'label' => 'Flash sale produk favorit',    'sub' => 'Notifikasi saat produk wishlist ada promo'],
                ] as $notif)
                <div class="px-5 py-4 flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $notif['label'] }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $notif['sub'] }}</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-0.5">
                        <input type="checkbox" name="{{ $notif['key'] }}" value="1"
                            {{ $user->{$notif['key']} ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition
                            after:content-[''] after:absolute after:top-0.5 after:left-0.5
                            after:bg-white after:rounded-full after:h-4 after:w-4
                            after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                @endforeach
            </div>

            {{-- Kolom Kanan: Privasi + Hapus Akun --}}
            <div class="space-y-6">

                {{-- Privasi --}}
                <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
                    <div class="px-5 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Privasi
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Kelola visibilitas informasi kamu</p>
                    </div>
                    <div class="px-5 py-4 flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Tampilkan nama di ulasan</div>
                            <div class="text-xs text-gray-400 mt-0.5">Nama kamu akan terlihat oleh penjual dan pembeli lain</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-0.5">
                            <input type="checkbox" name="privasi_tampilkan_nama" value="1"
                                {{ $user->privasi_tampilkan_nama ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-4 after:w-4
                                after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 active:scale-95 transition-all text-sm">
                    Simpan Pengaturan
                </button>

                {{-- Hapus Akun --}}
                <div class="bg-white border border-red-100 rounded-xl p-5">
                    <h3 class="font-bold text-red-600 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                        </svg>
                        Hapus Akun
                    </h3>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                        Menghapus akun bersifat permanen. Semua data, pesanan, poin, dan riwayat belanja akan dihapus dan tidak bisa dipulihkan.
                    </p>
                    <button type="button"
                        onclick="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')"
                        class="bg-red-50 border border-red-200 text-red-600 font-semibold px-5 py-2 rounded-lg text-sm hover:bg-red-600 hover:text-white transition">
                        Hapus Akun Permanen
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection