<nav class="bg-red-600 text-white px-4 h-14 flex items-center gap-3 sticky top-0 z-50 shadow">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="font-bold text-lg flex-shrink-0">
        Ranah<span class="text-amber-300">Mart</span>
    </a>

    {{-- Search --}}
    <form action="{{ route('produk.index') }}" method="GET"
        class="flex-1 flex items-center bg-white/15 border border-white/25 rounded-lg overflow-hidden h-9 mx-2">
        <input type="text" name="q" placeholder="Cari produk UMKM Padang..."
            value="{{ request('q') }}"
            class="flex-1 bg-transparent border-none outline-none px-3 text-sm text-white placeholder-white/60">
        <button type="submit"
            class="w-9 h-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35" stroke-linecap="round"/>
            </svg>
        </button>
    </form>

    {{-- Subnav kategori --}}
    <div class="hidden md:flex items-center gap-1 flex-shrink-0">
        @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
            @if($loop->index < 4)
            <a href="{{ route('produk.kategori', $slug) }}"
                class="text-xs text-white/80 hover:text-white px-2 py-1 rounded hover:bg-white/15 transition whitespace-nowrap">
                {{ $label }}
            </a>
            @endif
        @endforeach
    </div>

    {{-- Auth buttons --}}
    <div class="flex items-center gap-2 flex-shrink-0">
        @auth
            {{-- Cart (pembeli saja) --}}
            @if(auth()->user()->isPembeli())
            <a href="{{ route('pembeli.keranjang.index') }}"
                class="relative w-8 h-8 bg-white/15 border border-white/25 rounded-lg flex items-center justify-center hover:bg-white/25 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                @php $cartCount = auth()->user()->keranjangs()->count(); @endphp
                @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-amber-400 text-amber-900 text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                </span>
                @endif
            </a>
            @endif

            {{-- Dashboard link --}}
            @php
                $dashRoute = match(auth()->user()->role) {
                    'admin'   => route('admin.dashboard'),
                    'penjual' => route('penjual.dashboard'),
                    'dinas'   => route('dinas.dashboard'),
                    default   => route('pembeli.dashboard'),
                };
            @endphp
            <a href="{{ $dashRoute }}"
                class="text-xs font-semibold bg-white/15 border border-white/25 px-3 py-1.5 rounded-lg hover:bg-white/25 transition">
                Dashboard
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-xs font-semibold bg-white/15 border border-white/25 px-3 py-1.5 rounded-lg hover:bg-white/25 transition">
                    Keluar
                </button>
            </form>

        @else
            <a href="{{ route('login') }}"
                class="text-xs font-semibold bg-white/15 border border-white/25 px-3 py-1.5 rounded-lg hover:bg-white/25 transition">
                Masuk
            </a>
            <a href="{{ route('register.pembeli') }}"
                class="text-xs font-semibold bg-amber-400 text-amber-900 px-3 py-1.5 rounded-lg hover:bg-amber-300 transition">
                Daftar
            </a>
        @endauth
    </div>

</nav>