@extends('layouts.dashboard')
@section('title', 'Voucher Saya')
@section('page-title', 'Voucher & Promo')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Input kode voucher --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <h3 class="font-bold text-gray-800 mb-3">Masukkan Kode Voucher</h3>
        <div class="flex gap-2">
            <input type="text" id="kode-input" placeholder="Contoh: RANAHFIRST"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2.5 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button onclick="cekVoucher()"
                class="bg-blue-600 text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-blue-700 transition">
                Gunakan
            </button>
        </div>
        <div id="voucher-result" class="mt-2 text-xs hidden"></div>
    </div>

    {{-- Voucher Global Aktif --}}
    @if(isset($voucherGlobal) && $voucherGlobal->count())
    <div class="mb-6">
        <h3 class="font-bold text-gray-800 mb-3">🎁 Voucher Promo Platform</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($voucherGlobal as $v)
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl p-4 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-full -mr-8 -mt-8"></div>
                <div class="relative">
                    <div class="text-xs font-semibold opacity-80 mb-1">
                        @if($v->jenis === 'persen') Diskon {{ $v->nilai }}%
                        @elseif($v->jenis === 'nominal') Potongan Rp {{ number_format($v->nilai, 0, ',', '.') }}
                        @else Gratis Ongkir
                        @endif
                    </div>
                    <div class="font-bold font-mono text-xl mb-2">{{ $v->kode }}</div>
                    <div class="text-xs opacity-70">
                        Min. belanja Rp {{ number_format($v->min_belanja, 0, ',', '.') }} ·
                        s/d {{ $v->berlaku_hingga->format('d M Y') }}
                    </div>
                    @if($v->kuota)
                    <div class="text-xs opacity-70 mt-0.5">
                        Sisa: {{ $v->kuota - $v->terpakai }} dari {{ $v->kuota }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Voucher Saya --}}
    <h3 class="font-bold text-gray-800 mb-3">Voucher Kamu</h3>
    @if(isset($vouchersSaya) && $vouchersSaya->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($vouchersSaya as $vu)
        @if($vu->voucher)
        @php $v = $vu->voucher; $dipakai = !is_null($vu->digunakan_at); @endphp
        <div class="bg-white border-2 rounded-xl p-4 {{ $dipakai ? 'border-gray-200 opacity-60' : 'border-amber-300' }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <div class="font-bold font-mono text-gray-800">{{ $v->kode }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        @if($v->jenis === 'persen') Diskon {{ $v->nilai }}%
                        @elseif($v->jenis === 'nominal') Potongan Rp {{ number_format($v->nilai, 0, ',', '.') }}
                        @else Gratis Ongkir
                        @endif
                        · Min. Rp {{ number_format($v->min_belanja, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Berlaku hingga: {{ $v->berlaku_hingga->format('d M Y') }}
                    </div>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded flex-shrink-0
                    {{ $dipakai ? 'bg-gray-100 text-gray-400' : 'bg-amber-100 text-amber-700' }}">
                    {{ $dipakai ? 'Terpakai' : 'Aktif' }}
                </span>
            </div>
            @if(!$dipakai)
            <button onclick="navigator.clipboard.writeText('{{ $v->kode }}').then(() => alert('Kode disalin!'))"
                class="w-full mt-3 bg-amber-50 border border-amber-300 text-amber-700 font-bold py-1.5 rounded-lg text-xs hover:bg-amber-100 transition">
                📋 Salin Kode
            </button>
            @endif
        </div>
        @endif
        @endforeach
    </div>
    @else
    <div class="text-center py-12 bg-white border border-gray-200 rounded-xl text-gray-400">
        <div class="text-5xl mb-3">🏷️</div>
        <p class="text-sm">Belum punya voucher</p>
        <p class="text-xs text-gray-400 mt-1">Ikuti promo RanahMart untuk mendapatkan voucher menarik!</p>
    </div>
    @endif

</div>

@push('scripts')
<script>
function cekVoucher() {
    const kode = document.getElementById('kode-input').value.trim();
    const result = document.getElementById('voucher-result');
    if (!kode) return;
    result.className = 'mt-2 text-xs text-blue-600';
    result.textContent = 'Mengecek...';
    result.classList.remove('hidden');
    fetch("{{ route('pembeli.voucher.validasi') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ kode, total: 0 })
    }).then(r => r.json()).then(data => {
        if (data.valid) {
            result.className = 'mt-2 text-xs text-teal-600 font-semibold';
            result.textContent = '✓ Voucher valid! Gunakan saat checkout.';
        } else {
            result.className = 'mt-2 text-xs text-red-500';
            result.textContent = '✕ ' + data.message;
        }
    });
}
</script>
@endpush
@endsection