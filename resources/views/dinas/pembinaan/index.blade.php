@extends('layouts.dashboard')
@section('title', 'Program Pembinaan')
@section('page-title', 'Program Pembinaan UMKM')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Form Buat Program --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">+ Buat Program Baru</h3>
            <form method="POST" action="{{ route('dinas.pembinaan.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Program *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Contoh: Pelatihan Digital Marketing UMKM 2025">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Tujuan dan manfaat program pembinaan...">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Gedung Dinas / Online">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kuota Peserta</label>
                        <input type="number" name="kuota" value="{{ old('kuota') }}" min="1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Kosongkan = tidak terbatas">
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-purple-600 text-white font-bold py-2.5 rounded-lg hover:bg-purple-700 transition text-sm">
                    Buat Program Pembinaan
                </button>
            </form>
        </div>

        {{-- Daftar Program --}}
        <div>
            <h3 class="font-bold text-gray-800 mb-4">Program Pembinaan</h3>
            @if(isset($programs) && $programs->count())
            <div class="space-y-3">
                @foreach($programs as $program)
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-gray-800 text-sm">{{ $program->nama }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $program->tanggal_mulai->format('d M') }} –
                                {{ $program->tanggal_selesai->format('d M Y') }}
                                @if($program->lokasi) · {{ $program->lokasi }} @endif
                            </div>
                            @if($program->deskripsi)
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed line-clamp-2">{{ $program->deskripsi }}</p>
                            @endif
                        </div>
                        @php
                            $progClass = match($program->status) {
                                'berjalan'     => 'bg-teal-100 text-teal-700',
                                'akan_datang'  => 'bg-blue-100 text-blue-700',
                                'selesai'      => 'bg-gray-100 text-gray-500',
                                default        => 'bg-gray-100 text-gray-500',
                            };
                            $progLabel = match($program->status) {
                                'berjalan'    => 'Berjalan',
                                'akan_datang' => 'Akan Datang',
                                'selesai'     => 'Selesai',
                                default       => ucfirst($program->status),
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $progClass }} flex-shrink-0">
                            {{ $progLabel }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-500 mb-3">
                        Peserta: {{ $program->pesertas->count() }}
                        @if($program->kuota) / {{ $program->kuota }} @endif
                        · Dibuat oleh: {{ $program->pembuat->nama_depan ?? '-' }}
                    </div>

                    {{-- Daftarkan UMKM --}}
                    @if($program->status !== 'selesai')
                    <form method="POST" action="{{ route('dinas.pembinaan.daftarkan', $program->id) }}" class="flex gap-2">
                        @csrf
                        <select name="toko_id" required class="flex-1 border border-gray-300 rounded-lg px-2 py-1.5 text-xs bg-white">
                            <option value="">Pilih UMKM untuk didaftarkan...</option>
                            @foreach($tokos ?? [] as $toko)
                            <option value="{{ $toko->id }}">{{ $toko->nama_toko }}</option>
                            @endforeach
                        </select>
                        <button class="text-xs bg-purple-100 text-purple-700 font-bold px-3 py-1.5 rounded-lg hover:bg-purple-600 hover:text-white transition flex-shrink-0">
                            + Daftarkan
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $programs->links() }}</div>
            @else
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
                <div class="text-5xl mb-3">📚</div>
                <p class="text-sm">Belum ada program pembinaan</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection