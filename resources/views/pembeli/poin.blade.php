@extends('layouts.dashboard')
@section('title', 'Poin Reward')
@section('page-title', 'Poin Reward')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<div class="p-6">

    {{-- Hero Poin --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm opacity-80 mb-2">Total Poin Kamu</div>
                <div class="text-5xl font-bold mb-1">{{ $totalPoin ?? 0 }}</div>
                <div class="text-blue-200 text-sm">
                    ≈ Rp {{ number_format(($totalPoin ?? 0) * config('ranahmart.poin_ke_rupiah', 10), 0, ',', '.') }} diskon
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs opacity-70 mb-1">Tier Kamu</div>
                @php
                    $tier = match(true) {
                        ($totalPoin ?? 0) >= 2500 => ['nama' => 'Platinum', 'color' => 'text-cyan-300', 'next' => null, 'nextPts' => null],
                        ($totalPoin ?? 0) >= 1000 => ['nama' => 'Gold',     'color' => 'text-yellow-300', 'next' => 'Platinum', 'nextPts' => 2500],
                        ($totalPoin ?? 0) >= 500  => ['nama' => 'Silver',   'color' => 'text-gray-300',   'next' => 'Gold',     'nextPts' => 1000],
                        default => ['nama' => 'Bronze',  'color' => 'text-amber-700',  'next' => 'Silver',   'nextPts' => 500],
                    };
                @endphp
                <div class="text-2xl font-bold {{ $tier['color'] }}">{{ $tier['nama'] }}</div>
                @if($tier['next'])
                <div class="text-xs opacity-70 mt-1">
                    Butuh {{ $tier['nextPts'] - ($totalPoin ?? 0) }} poin lagi untuk {{ $tier['next'] }}
                </div>
                @else
                <div class="text-xs opacity-70 mt-1">Tier tertinggi! 🎉</div>
                @endif
            </div>
        </div>

        {{-- Progress bar tier --}}
        @if($tier['next'])
        @php
            $tierStart = match($tier['nama']) {
                'Silver' => 500, 'Gold' => 1000, default => 0
            };
            $progress = min(100, round(($totalPoin - $tierStart) / ($tier['nextPts'] - $tierStart) * 100));
        @endphp
        <div class="mt-4">
            <div class="flex justify-between text-xs opacity-70 mb-1">
                <span>{{ $tier['nama'] }}</span>
                <span>{{ $tier['next'] }}</span>
            </div>
            <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-amber-400 rounded-full transition-all"
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- Cara Dapat Poin --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <h3 class="font-bold text-gray-800 mb-3">Cara Mendapatkan Poin</h3>
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['icon' => '🛒', 'label' => 'Setiap belanja', 'desc' => '1 poin per Rp 1.000'],
                ['icon' => '⭐', 'label' => 'Tulis ulasan',   'desc' => '+10 poin per ulasan'],
                ['icon' => '🎁', 'label' => 'Bonus daftar',   'desc' => '+100 poin pertama'],
            ] as $item)
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <div class="text-2xl mb-1.5">{{ $item['icon'] }}</div>
                <div class="text-xs font-bold text-gray-800">{{ $item['label'] }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $item['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Tukar Poin --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <h3 class="font-bold text-gray-800 mb-3">Tukar Poin jadi Diskon</h3>
        <form method="POST" action="{{ route('pembeli.poin.tukar') }}" class="space-y-3">
            @csrf
            <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-600 space-y-1">
                <div>✓ 100 poin = Rp {{ number_format(100 * config('ranahmart.poin_ke_rupiah', 10), 0, ',', '.') }} diskon</div>
                <div>✓ Minimum penukaran: 100 poin</div>
                <div>✓ Poin yang ditukar akan menjadi voucher diskon di checkout</div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Poin yang Ditukar</label>
                <div class="flex gap-2">
                    <input type="number" name="jumlah_poin" min="100" max="{{ $totalPoin ?? 0 }}" step="100"
                        placeholder="Min. 100 poin"
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="bg-blue-600 text-white font-bold px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                        Tukar
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    Poin tersedia: <strong>{{ $totalPoin ?? 0 }}</strong>
                </p>
            </div>
        </form>
    </div>

    {{-- Riwayat Poin --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat Poin</h3>
        </div>
        @if(isset($riwayat) && $riwayat->count())
        <div class="divide-y divide-gray-100">
            @foreach($riwayat as $p)
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ $p->keterangan }}</div>
                    <div class="text-xs text-gray-400">{{ $p->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="font-bold {{ $p->jumlah > 0 ? 'text-teal-600' : 'text-red-500' }} text-sm">
                    {{ $p->jumlah > 0 ? '+' : '' }}{{ $p->jumlah }} poin
                </div>
            </div>
            @endforeach
        </div>
        <div class="p-4">{{ $riwayat->links() }}</div>
        @else
        <div class="text-center py-10 text-gray-400 text-sm">Belum ada riwayat poin</div>
        @endif
    </div>

</div>
@endsection