@extends('layouts.dashboard')
@section('title', 'Pengumuman UMKM')
@section('page-title', 'Pengumuman untuk UMKM')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@section('content')
<div class="p-6">

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Form Buat Pengumuman --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Buat Pengumuman Baru</h3>
            <form method="POST" action="{{ route('dinas.pengumuman.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Pengumuman *</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Contoh: Pelatihan Digital Marketing Maret 2025">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Isi Pengumuman *</label>
                    <textarea name="isi" rows="5" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Tulis isi pengumuman untuk seluruh UMKM Kota Padang...">{{ old('isi') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Target Penerima *</label>
                    <select name="target_penerima" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="semua">Semua UMKM ({{ \App\Models\Toko::where('status','aktif')->count() }} toko)</option>
                        @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                        <option value="{{ $slug }}">UMKM {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Prioritas</label>
                    <select name="prioritas"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="normal">Normal</option>
                        <option value="penting">Penting ⚠</option>
                        <option value="mendesak">Mendesak 🔴</option>
                    </select>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-xs text-purple-700">
                    ℹ️ Pengumuman akan dikirim sebagai notifikasi ke seluruh pemilik toko yang dipilih.
                </div>
                <button type="submit"
                    class="w-full bg-purple-600 text-white font-bold py-3 rounded-xl hover:bg-purple-700 transition text-sm">
                    Kirim Pengumuman →
                </button>
            </form>
        </div>

        {{-- Riwayat Pengumuman --}}
        <div>
            <h3 class="font-bold text-gray-800 mb-4">Pengumuman Terbaru</h3>
            @if(isset($pengumumans) && $pengumumans->count())
            <div class="space-y-3">
                @foreach($pengumumans as $p)
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                @if($p->prioritas === 'mendesak')
                                <span class="text-red-500 text-sm">🔴</span>
                                @elseif($p->prioritas === 'penting')
                                <span class="text-amber-500 text-sm">⚠️</span>
                                @endif
                                <div class="font-semibold text-gray-800 text-sm truncate">{{ $p->judul }}</div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Dikirim ke {{ $p->total_terkirim }} UMKM ·
                                {{ $p->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded flex-shrink-0
                            {{ match($p->prioritas) {
                                'mendesak' => 'bg-red-100 text-red-700',
                                'penting'  => 'bg-amber-100 text-amber-700',
                                default    => 'bg-gray-100 text-gray-500',
                            } }}">
                            {{ ucfirst($p->prioritas) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">{{ $p->isi }}</p>
                    <div class="text-xs text-teal-600 mt-2 font-semibold">
                        ✓ Terkirim ke {{ $p->total_terkirim }} UMKM
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $pengumumans->links() }}</div>
            @else
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
                <div class="text-5xl mb-3">📢</div>
                <p class="text-sm">Belum ada pengumuman</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection