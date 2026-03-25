@extends('layouts.dashboard')
@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@section('content')
<div class="p-6">

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Form Buat Voucher --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-4">Buat Voucher Baru</h3>
            <form method="POST" action="{{ route('penjual.promo.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Voucher *</label>
                    <input type="text" name="kode" value="{{ old('kode') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Contoh: DISKON20" style="text-transform:uppercase">
                    <p class="text-xs text-gray-400 mt-0.5">Kode akan otomatis menjadi huruf besar</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Diskon *</label>
                    <select name="jenis" id="jenis-voucher" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-500"
                        onchange="toggleNilai(this.value)">
                        <option value="persen">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="gratis_ongkir">Gratis Ongkir</option>
                    </select>
                </div>

                <div id="nilai-wrapper">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nilai Diskon *</label>
                            <input type="number" name="nilai" value="{{ old('nilai') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                                placeholder="20" min="0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Maks. Potongan (Rp)</label>
                            <input type="number" name="maks_potongan" value="{{ old('maks_potongan') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                                placeholder="Opsional">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_belanja" value="{{ old('min_belanja', 0) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                            min="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kuota</label>
                        <input type="number" name="kuota" value="{{ old('kuota') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                            placeholder="Kosongkan = tidak terbatas">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Berlaku Mulai *</label>
                        <input type="date" name="berlaku_mulai" value="{{ old('berlaku_mulai', date('Y-m-d')) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Berlaku Hingga *</label>
                        <input type="date" name="berlaku_hingga" value="{{ old('berlaku_hingga') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-teal-600 text-white font-bold py-2.5 rounded-lg hover:bg-teal-700 transition text-sm">
                    Buat Voucher
                </button>
            </form>
        </div>

        {{-- Daftar Voucher --}}
        <div>
            <h3 class="font-bold text-gray-800 mb-4">Voucher Aktif</h3>
            @if(isset($vouchers) && $vouchers->count())
            <div class="space-y-3">
                @foreach($vouchers as $voucher)
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="font-bold text-gray-800 font-mono text-base">{{ $voucher->kode }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($voucher->jenis === 'persen')
                                    Diskon {{ $voucher->nilai }}%
                                    @if($voucher->maks_potongan) · Maks. Rp {{ number_format($voucher->maks_potongan, 0, ',', '.') }} @endif
                                @elseif($voucher->jenis === 'nominal')
                                    Potongan Rp {{ number_format($voucher->nilai, 0, ',', '.') }}
                                @else
                                    Gratis Ongkir
                                @endif
                                · Min. Rp {{ number_format($voucher->min_belanja, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $voucher->berlaku_mulai->format('d M') }} – {{ $voucher->berlaku_hingga->format('d M Y') }}
                                @if($voucher->kuota)
                                · {{ $voucher->terpakai }}/{{ $voucher->kuota }} dipakai
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <span class="text-xs font-bold px-2 py-0.5 rounded
                                {{ $voucher->aktif ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $voucher->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <div class="flex gap-1">
                                <form method="POST" action="{{ route('penjual.promo.toggle', $voucher->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-blue-600 hover:underline font-semibold">
                                        {{ $voucher->aktif ? 'Nonaktif' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('penjual.promo.destroy', $voucher->id) }}"
                                    onsubmit="return confirm('Hapus voucher ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:underline font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
                <div class="text-5xl mb-3">🏷️</div>
                <p class="text-sm">Belum ada voucher</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function toggleNilai(jenis) {
    document.getElementById('nilai-wrapper').style.display =
        jenis === 'gratis_ongkir' ? 'none' : 'block';
}
document.getElementById('kode') && document.getElementById('kode').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endpush