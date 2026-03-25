@extends('layouts.dashboard')
@section('title', 'Verifikasi UMKM')
@section('page-title', 'Verifikasi UMKM')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    {{-- Alert syarat kurang --}}
    @if(session('error_syarat'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-red-600 font-bold text-sm">⚠ Verifikasi Gagal — Syarat Belum Lengkap</span>
        </div>
        <ul class="list-disc list-inside space-y-1">
            @foreach(session('error_syarat') as $syarat)
            <li class="text-sm text-red-600">{{ $syarat }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-5">
        <span class="text-teal-700 font-bold text-sm">✓ {{ session('success') }}</span>
    </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="flex gap-2 mb-5 flex-wrap">
        <select name="kecamatan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
            <option value="">Semua Kecamatan</option>
            @foreach(config('ranahmart.kecamatan_padang') as $kec)
            <option value="{{ $kec }}" {{ request('kecamatan') === $kec ? 'selected' : '' }}>{{ $kec }}</option>
            @endforeach
        </select>
    </form>

    @if($tokos->count())
    <div class="space-y-4">
        @foreach($tokos as $toko)

        {{-- Cek kelengkapan syarat --}}
        @php
            $syaratKurang = [];
            if (!$toko->foto_ktp)           $syaratKurang[] = 'Foto KTP';
            if (!$toko->foto_usaha)         $syaratKurang[] = 'Foto Usaha';
            if (!$toko->foto_produk_sample) $syaratKurang[] = 'Foto Produk Sample';
            if (!$toko->no_hp)              $syaratKurang[] = 'No. HP';
            if (!$toko->alamat_lengkap)     $syaratKurang[] = 'Alamat Lengkap';
            $syaratLengkap = empty($syaratKurang);
        @endphp

        <div class="bg-white border-2 {{ $syaratLengkap ? 'border-purple-200' : 'border-red-200' }} rounded-xl p-5">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($toko->nama_toko, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ $toko->nama_toko }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $toko->kecamatan }} · {{ $toko->kategori_friendly }}
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            NIB: {{ $toko->nib ?? 'Belum ada' }} ·
                            Daftar: {{ $toko->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded bg-amber-100 text-amber-700 flex-shrink-0">
                    Menunggu Verifikasi
                </span>
            </div>

            {{-- Indikator Kelengkapan Syarat --}}
            @if(!$syaratLengkap)
            <div class="bg-red-50 border border-red-100 rounded-lg px-3 py-2 mb-3">
                <span class="text-xs font-bold text-red-500">⚠ Syarat kurang: </span>
                <span class="text-xs text-red-400">{{ implode(', ', $syaratKurang) }}</span>
            </div>
            @else
            <div class="bg-teal-50 border border-teal-100 rounded-lg px-3 py-2 mb-3">
                <span class="text-xs font-bold text-teal-600">✓ Semua syarat lengkap — siap diverifikasi</span>
            </div>
            @endif

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                <div class="bg-gray-50 rounded-lg p-2.5">
                    <div class="text-xs text-gray-400">Pemilik</div>
                    <div class="text-xs font-semibold text-gray-800 mt-0.5">{{ $toko->user->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-2.5">
                    <div class="text-xs text-gray-400">No. HP</div>
                    <div class="text-xs font-semibold text-gray-800 mt-0.5">{{ $toko->no_hp ?? '-' }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-2.5">
                    <div class="text-xs text-gray-400">Dokumen KTP</div>
                    <div class="text-xs font-semibold mt-0.5 {{ $toko->foto_ktp ? 'text-teal-600' : 'text-red-500' }}">
                        {{ $toko->foto_ktp ? '✓ Ada' : '✗ Belum ada' }}
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-2.5">
                    <div class="text-xs text-gray-400">Produk</div>
                    <div class="text-xs font-semibold text-gray-800 mt-0.5">{{ $toko->produks->count() }} produk</div>
                </div>
            </div>

            @if($toko->catatan_dinas)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                <div class="text-xs font-bold text-amber-700 mb-0.5">Catatan sebelumnya:</div>
                <p class="text-xs text-amber-800">{{ $toko->catatan_dinas }}</p>
            </div>
            @endif

            {{-- Aksi --}}
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('dinas.verifikasi.show', $toko->id) }}"
                    class="text-xs bg-purple-600 text-white font-bold px-3 py-2 rounded-lg hover:bg-purple-700 transition">
                    📋 Tinjau Lengkap
                </a>

                <form method="POST" action="{{ route('dinas.verifikasi.setujui', $toko->id) }}">
                    @csrf @method('PATCH')
                    <button
                        class="text-xs font-bold px-3 py-2 rounded-lg transition
                        {{ $syaratLengkap
                            ? 'bg-teal-600 text-white hover:bg-teal-700'
                            : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        {{ !$syaratLengkap ? 'disabled' : '' }}
                        title="{{ !$syaratLengkap ? 'Syarat kurang: ' . implode(', ', $syaratKurang) : 'Verifikasi toko ini' }}">
                        ✓ Verifikasi & Terbitkan Sertifikat
                    </button>
                </form>

                <form method="POST" action="{{ route('dinas.verifikasi.minta-dokumen', $toko->id) }}" class="flex gap-1">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan_dinas" placeholder="Dokumen yang kurang..."
                        class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs w-40">
                    <button class="text-xs bg-amber-500 text-white font-bold px-3 py-2 rounded-lg hover:bg-amber-600 transition">
                        📄 Minta Dokumen
                    </button>
                </form>

                <form method="POST" action="{{ route('dinas.verifikasi.tolak', $toko->id) }}" class="flex gap-1">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan_dinas" placeholder="Alasan penolakan..."
                        class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs w-36">
                    <button class="text-xs bg-red-100 text-red-600 font-bold px-3 py-2 rounded-lg hover:bg-red-600 hover:text-white transition">
                        ✕ Tolak
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">{{ $tokos->withQueryString()->links() }}</div>

    @else
    <div class="text-center py-16 bg-white border border-gray-200 rounded-xl text-gray-400">
        <div class="text-6xl mb-4">✅</div>
        <p class="font-semibold text-gray-600">Tidak ada UMKM menunggu verifikasi</p>
        <p class="text-sm mt-1">Semua pengajuan sudah ditangani</p>
    </div>
    @endif

</div>
@endsection