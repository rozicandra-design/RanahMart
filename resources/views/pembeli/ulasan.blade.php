@extends('layouts.dashboard')
@section('title', 'Ulasan Saya')
@section('page-title', 'Ulasan Saya')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Belum Diulas --}}
    @if(isset($belumDiulas) && $belumDiulas->count())
    <div class="mb-6">
        <h2 class="font-bold text-gray-800 mb-3">
            Menunggu Ulasan
            <span class="text-sm font-normal text-red-500">({{ $belumDiulas->count() }} produk)</span>
        </h2>
        <div class="space-y-3">
            @foreach($belumDiulas as $item)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">🛍️</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_produk }}</div>
                        <div class="text-xs text-gray-500">Pesanan: {{ $item->pesanan->kode_pesanan }}</div>
                    </div>
                </div>

                {{-- Form ulasan --}}
                <form method="POST" action="{{ route('pembeli.ulasan.store') }}" class="mt-3 space-y-3">
                    @csrf
                    <input type="hidden" name="item_pesanan_id" value="{{ $item->id }}">

                    {{-- Rating bintang --}}
                    <div>
                        <div class="text-xs font-bold text-gray-700 mb-1.5">Rating *</div>
                        <div class="flex gap-1" id="rating-{{ $item->id }}">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" required class="sr-only">
                                <span class="text-2xl text-gray-300 hover:text-amber-400 transition"
                                    onclick="setRating({{ $item->id }}, {{ $i }})">★</span>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Komentar</label>
                        <textarea name="komentar" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-amber-400"
                            placeholder="Ceritakan pengalaman kamu dengan produk ini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            Foto Produk <span class="text-gray-400 font-normal">(opsional, maks. 3)</span>
                        </label>
                        <input type="file" name="foto_ulasan[]" multiple accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>

                    <button type="submit"
                        class="bg-amber-500 text-white font-bold px-5 py-2 rounded-lg text-sm hover:bg-amber-600 transition">
                        ⭐ Kirim Ulasan +10 Poin
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Ulasan yang sudah dikirim --}}
    <h2 class="font-bold text-gray-800 mb-3">Ulasan Saya ({{ $ulasanSaya->count() }})</h2>
    @if($ulasanSaya->count())
    <div class="space-y-4">
        @foreach($ulasanSaya as $ulasan)
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-3 mb-2">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">🛍️</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate">{{ $ulasan->produk->nama ?? '-' }}</div>
                    <div class="text-xs text-gray-400">{{ $ulasan->toko->nama_toko ?? '-' }}</div>
                    <div class="text-amber-500 mt-1">
                        {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                    </div>
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">{{ $ulasan->created_at->diffForHumans() }}</div>
            </div>
            @if($ulasan->komentar)
            <p class="text-sm text-gray-700 leading-relaxed mb-2">{{ $ulasan->komentar }}</p>
            @endif
            @if($ulasan->balasan)
            <div class="bg-teal-50 border-l-4 border-teal-400 pl-3 py-2 rounded-r-lg">
                <div class="text-xs font-bold text-teal-700 mb-0.5">Balasan Penjual:</div>
                <p class="text-xs text-teal-800">{{ $ulasan->balasan }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
        <div class="text-5xl mb-3">⭐</div>
        <p class="text-sm">Belum ada ulasan</p>
    </div>
    @endif

</div>

@push('scripts')
<script>
function setRating(itemId, rating) {
    const container = document.getElementById('rating-' + itemId);
    const stars = container.querySelectorAll('span');
    stars.forEach((star, i) => {
        star.style.color = i < rating ? '#F59E0B' : '#D1D5DB';
    });
    container.querySelector(`input[value="${rating}"]`).checked = true;
}
</script>
@endpush
@endsection