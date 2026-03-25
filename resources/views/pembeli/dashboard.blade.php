@extends('layouts.dashboard')
@section('title', 'Dashboard Pembeli')
@section('page-title', 'Beranda')
@section('notif-route', route('pembeli.notifikasi'))
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --brand:       #2563EB;
        --brand-mid:   #3B82F6;
        --brand-light: #EFF6FF;
        --ink-1:       #0F172A;
        --ink-2:       #334155;
        --ink-3:       #64748B;
        --ink-4:       #94A3B8;
        --surface-1:   #FFFFFF;
        --surface-2:   #F8FAFC;
        --surface-3:   #F1F5F9;
        --border:      #E2E8F0;
        --radius-xl:   20px;
        --radius-2xl:  28px;
        --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:   0 4px 16px rgba(0,0,0,.08);
        --shadow-lg:   0 10px 40px rgba(37,99,235,.12);
    }

    /* ────────────────────────────────────────────────────────
       FULL-WIDTH FIX
       Dari screenshot, area konten (.db-wrap) hanya mengambil
       sebagian tengah layar. Kita paksa semua container induk
       untuk mengisi lebar penuh area konten yang tersedia.
    ──────────────────────────────────────────────────────── */

    /* Paksa wrapper & semua elemen parent konten jadi full-width */
    .db-wrap,
    .db-wrap ~ *,
    #app > *:not(.sidebar):not([class*="sidebar"]),
    .container-fluid,
    .wrapper,
    .page-wrapper,
    [class*="page-content"],
    [class*="content-wrapper"],
    [class*="main-content"] {
        width: 100% !important;
        max-width: 100% !important;
    }

    .db-wrap {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: clamp(.85rem, 2.5vw, 1.75rem);
        box-sizing: border-box;
        min-width: 0;
    }
    .db-wrap * { box-sizing: border-box; }

    /* ── HERO ─────────────────────────────────────────────── */
    .hero {
        position: relative; overflow: hidden;
        border-radius: var(--radius-2xl);
        padding: clamp(1.25rem, 3vw, 2.25rem) clamp(1.25rem, 4vw, 2.5rem);
        margin-bottom: clamp(1rem, 2vw, 1.75rem);
        background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 45%, #3B82F6 100%);
        box-shadow: var(--shadow-lg);
    }
    .hero::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 90% -10%, rgba(255,255,255,.12) 0%, transparent 60%),
            radial-gradient(ellipse 40% 50% at -10% 110%, rgba(96,165,250,.25) 0%, transparent 60%);
        pointer-events: none;
    }
    .hero-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 40px 40px; pointer-events: none;
    }
    .hero-inner {
        position: relative; display: flex; flex-wrap: wrap;
        align-items: center; justify-content: space-between; gap: 1.25rem;
    }
    .hero-greeting { color: rgba(255,255,255,.7); font-size: clamp(.65rem, 1.2vw, .78rem); font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-bottom: .35rem; }
    .hero-name     { font-size: clamp(1.5rem, 3.5vw, 2.2rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: .75rem; }
    .hero-pill {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.25); border-radius: 100px;
        padding: .35rem .9rem; font-size: clamp(.65rem, 1.2vw, .78rem); color: #fff; font-weight: 600;
    }
    .hero-pill strong { color: #FCD34D; }
    .voucher-badge {
        display: flex; align-items: center; gap: 1rem;
        background: rgba(255,255,255,.12); backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.2); border-radius: var(--radius-xl);
        padding: 1rem 1.25rem; flex-shrink: 0; transition: background .2s;
    }
    .voucher-badge:hover { background: rgba(255,255,255,.2); }
    .voucher-icon { width: 48px; height: 48px; border-radius: 14px; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
    .voucher-label { font-size: .65rem; color: rgba(255,255,255,.65); font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .voucher-code  { font-family: 'DM Mono', monospace; font-size: 1.15rem; font-weight: 500; color: #fff; }

    /* ── STATS ────────────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: clamp(.6rem, 1.5vw, 1rem);
        margin-bottom: clamp(1rem, 2vw, 1.75rem);
    }
    @media (max-width: 580px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }

    .stat-card {
        background: var(--surface-1); border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: clamp(.85rem, 2vw, 1.25rem) clamp(.9rem, 2.5vw, 1.4rem);
        box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
        transition: transform .2s, box-shadow .2s; min-width: 0;
    }
    .stat-card:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .stat-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0;
        height: 3px; background: var(--card-accent, var(--brand));
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
    }
    .stat-label { font-size: clamp(.58rem, 1vw, .68rem); font-weight: 700; letter-spacing: .09em; text-transform: uppercase; color: var(--ink-4); margin-bottom: .5rem; }
    .stat-val   { font-size: clamp(1.25rem, 2.5vw, 1.65rem); font-weight: 900; color: var(--ink-1); line-height: 1; }
    .stat-card.accent-poin    { --card-accent: #F59E0B; } .stat-card.accent-poin .stat-val    { color: #D97706; }
    .stat-card.accent-voucher { --card-accent: #8B5CF6; } .stat-card.accent-voucher .stat-val { color: #7C3AED; }
    .stat-card.accent-pesanan { --card-accent: #2563EB; }
    .stat-card.accent-belanja { --card-accent: #10B981; }

    /* ── QUICK MENU ───────────────────────────────────────── */
    .eyebrow { font-size: clamp(.58rem, 1vw, .65rem); font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-4); margin-bottom: .5rem; }
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: .9rem; }
    .section-title { font-size: clamp(.9rem, 1.8vw, 1.1rem); font-weight: 800; color: var(--ink-1); }
    .section-link  { font-size: clamp(.7rem, 1.2vw, .8rem); font-weight: 700; color: var(--brand); display: flex; align-items: center; gap: .25rem; text-decoration: none; }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: clamp(.5rem, 1.5vw, .85rem);
        margin-bottom: clamp(1.25rem, 3vw, 2.25rem);
    }
    @media (max-width: 480px) { .quick-grid { grid-template-columns: repeat(3, 1fr); } }

    .quick-item {
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
        padding: clamp(.7rem, 2vw, 1.1rem) clamp(.4rem, 1.5vw, .75rem);
        background: var(--surface-1); border: 1.5px solid var(--border);
        border-radius: var(--radius-xl); text-decoration: none;
        transition: all .2s ease; min-width: 0;
    }
    .quick-item:hover { border-color: var(--brand); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37,99,235,.09); }
    .quick-icon { width: clamp(34px, 4vw, 44px); height: clamp(34px, 4vw, 44px); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: clamp(1rem, 2.2vw, 1.4rem); }
    .quick-label { font-size: clamp(.6rem, 1vw, .72rem); font-weight: 700; color: var(--ink-2); text-align: center; }
    .quick-item:hover .quick-label { color: var(--brand); }

    /* ── MAIN LAYOUT ──────────────────────────────────────── */
    .main-cols {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: clamp(.85rem, 2.5vw, 1.75rem);
        align-items: start;
    }
    @media (max-width: 860px) { .main-cols { grid-template-columns: 1fr; } }

    /* ── ORDER CARDS ──────────────────────────────────────── */
    .order-card {
        display: block; background: var(--surface-1); border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: clamp(.8rem, 2vw, 1.1rem) clamp(.9rem, 2.5vw, 1.25rem);
        text-decoration: none; transition: all .2s; box-shadow: var(--shadow-sm);
    }
    .order-card:hover { border-color: var(--brand-mid); background: var(--brand-light); box-shadow: var(--shadow-md); }
    .order-row { display: flex; align-items: center; gap: .85rem; flex-wrap: wrap; }
    .order-icon { width: clamp(36px, 4.5vw, 46px); height: clamp(36px, 4.5vw, 46px); border-radius: 12px; background: var(--surface-3); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .order-code { font-size: clamp(.78rem, 1.5vw, .92rem); font-weight: 800; color: var(--ink-1); }
    .order-meta { font-size: clamp(.63rem, 1.1vw, .72rem); color: var(--ink-3); font-weight: 500; margin-top: .15rem; }
    .order-right { margin-left: auto; display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .order-price { font-size: clamp(.8rem, 1.5vw, .95rem); font-weight: 900; color: var(--ink-1); font-family: 'DM Mono', monospace; }
    .badge { font-size: clamp(.52rem, .9vw, .6rem); font-weight: 800; letter-spacing: .08em; text-transform: uppercase; padding: .28rem .65rem; border-radius: 100px; }
    .badge-menunggu { background: #FEF3C7; color: #92400E; }
    .badge-diproses { background: #DBEAFE; color: #1E40AF; }
    .badge-dikirim  { background: #EDE9FE; color: #4C1D95; }
    .badge-selesai  { background: #D1FAE5; color: #065F46; }
    .badge-default  { background: var(--surface-3); color: var(--ink-3); }
    .empty-state { padding: 2.5rem 1.5rem; text-align: center; background: var(--surface-2); border: 2px dashed var(--border); border-radius: var(--radius-2xl); }
    .empty-state p { color: var(--ink-3); font-weight: 500; margin: 0; }

    /* ── REKOMENDASI ──────────────────────────────────────── */
    .rekom-card {
        background: var(--surface-1); border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: clamp(.7rem, 2vw, 1rem) clamp(.8rem, 2.5vw, 1.1rem);
        display: flex; align-items: center; gap: .85rem;
        text-decoration: none; transition: all .2s; box-shadow: var(--shadow-sm);
    }
    .rekom-card:hover { border-color: var(--brand-mid); box-shadow: var(--shadow-md); }
    .rekom-img { width: clamp(42px, 5.5vw, 56px); height: clamp(42px, 5.5vw, 56px); object-fit: cover; border-radius: 10px; background: var(--surface-3); flex-shrink: 0; }
    .rekom-name  { font-size: clamp(.73rem, 1.4vw, .85rem); font-weight: 700; color: var(--ink-1); margin-bottom: .2rem; line-height: 1.3; }
    .rekom-price { font-size: clamp(.7rem, 1.3vw, .8rem); font-weight: 900; color: var(--brand); font-family: 'DM Mono', monospace; }
    .rekom-store { font-size: clamp(.6rem, 1vw, .7rem); color: var(--ink-4); font-weight: 500; }

    .mb-md { margin-bottom: 1rem; }
    .space-y > * + * { margin-top: .65rem; }
</style>
@endpush

@section('content')
<div class="db-wrap">

    {{-- ── HERO ──────────────────────────────────────────── --}}
    <div class="hero">
        <div class="hero-grid"></div>
        <div class="hero-inner">
            <div>
                <p class="hero-greeting">Selamat datang kembali</p>
                <h1 class="hero-name">{{ auth()->user()->nama_depan }}! 👋</h1>
                <div class="hero-pill">
                    <span>⭐</span>
                    <span>Kamu punya <strong>{{ number_format($stats['total_poin'] ?? 0) }}</strong> poin reward</span>
                </div>
            </div>
            @if(isset($voucherAktif))
            <div class="voucher-badge">
                <div class="voucher-icon">🎟️</div>
                <div>
                    <div class="voucher-label">Voucher Aktif</div>
                    <div class="voucher-code">{{ $voucherAktif->voucher->kode }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── STATS ─────────────────────────────────────────── --}}
    <div class="stats-grid">
        <div class="stat-card accent-pesanan">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-val">{{ $stats['total_pesanan'] ?? 0 }}</div>
        </div>
        <div class="stat-card accent-belanja">
            <div class="stat-label">Total Belanja</div>
            <div class="stat-val">Rp {{ number_format(($stats['total_belanja'] ?? 0) / 1000000, 1) }}jt</div>
        </div>
        <div class="stat-card accent-poin">
            <div class="stat-label">Poin Reward</div>
            <div class="stat-val">{{ number_format($stats['total_poin'] ?? 0) }}</div>
        </div>
        <div class="stat-card accent-voucher">
            <div class="stat-label">Voucher Aktif</div>
            <div class="stat-val">{{ $stats['voucher_aktif'] ?? 0 }}</div>
        </div>
    </div>

    {{-- ── QUICK MENU ────────────────────────────────────── --}}
    <div class="eyebrow">Menu Cepat</div>
    <div class="quick-grid">
        @php
        $shortcuts = [
            ['route' => 'pembeli.pesanan.index',  'icon' => '📦', 'label' => 'Pesanan',   'bg' => '#FFF7ED'],
            ['route' => 'pembeli.keranjang.index', 'icon' => '🛒', 'label' => 'Keranjang', 'bg' => '#EFF6FF'],
            ['route' => 'pembeli.wishlist.index',  'icon' => '❤️', 'label' => 'Wishlist',  'bg' => '#FFF1F2'],
            ['route' => 'pembeli.voucher.index',   'icon' => '🏷️', 'label' => 'Voucher',   'bg' => '#F5F3FF'],
            ['route' => 'pembeli.poin.index',      'icon' => '⭐', 'label' => 'Poin',      'bg' => '#FFFBEB'],
            ['route' => 'pembeli.retur.index',     'icon' => '🔄', 'label' => 'Retur',     'bg' => '#F8FAFC'],
        ];
        @endphp
        @foreach($shortcuts as $sc)
        <a href="{{ route($sc['route']) }}" class="quick-item">
            <div class="quick-icon" style="background:{{ $sc['bg'] }}">{{ $sc['icon'] }}</div>
            <span class="quick-label">{{ $sc['label'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- ── MAIN TWO-COL ─────────────────────────────────── --}}
    <div class="main-cols">
        <div>
            <div class="section-header mb-md">
                <h2 class="section-title">Pesanan Terkini</h2>
                <a href="{{ route('pembeli.pesanan.index') }}" class="section-link">Lihat semua →</a>
            </div>
            @if(isset($pesananTerkini) && $pesananTerkini->count())
            <div class="space-y">
                @foreach($pesananTerkini as $pesanan)
                <a href="{{ route('pembeli.pesanan.show', $pesanan->id) }}" class="order-card">
                    <div class="order-row">
                        <div class="order-icon">📦</div>
                        <div>
                            <div class="order-code">{{ $pesanan->kode_pesanan }}</div>
                            <div class="order-meta">
                                {{ $pesanan->toko->nama_toko ?? 'Toko' }} &bull; {{ $pesanan->items->count() }} item &bull; {{ $pesanan->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="order-right">
                            @php
                                $badgeClass = match($pesanan->status_pesanan) {
                                    'menunggu' => 'badge-menunggu',
                                    'diproses' => 'badge-diproses',
                                    'dikirim'  => 'badge-dikirim',
                                    'selesai'  => 'badge-selesai',
                                    default    => 'badge-default',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $pesanan->status_pesanan }}</span>
                            <span class="order-price">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <p>Belum ada pesanan aktif saat ini.</p>
            </div>
            @endif
        </div>

        <div>
            <div class="section-header mb-md">
                <h2 class="section-title">Rekomendasi</h2>
            </div>
            <div class="space-y">
                @if(isset($rekomendasi))
                    @foreach($rekomendasi->take(4) as $produk)
                    <a href="{{ route('pembeli.produk.show', $produk->id) }}" class="rekom-card">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="rekom-img">
                        @else
                            <div class="rekom-img" style="display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🛍️</div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div class="rekom-name">{{ Str::limit($produk->nama_produk, 35) }}</div>
                            <div class="rekom-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <div class="rekom-store">{{ $produk->toko->nama_toko ?? '' }}</div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</div>
@endsection