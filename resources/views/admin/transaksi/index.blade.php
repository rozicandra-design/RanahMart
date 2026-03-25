@extends('layouts.dashboard')
@section('title', 'Transaksi')
@section('page-title', 'Manajemen Transaksi')
@section('sidebar') @include('components.sidebar-admin') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');
    .trx-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .trx-wrap { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    /* ── Header ── */
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; }
    .page-header .title    { font-size: 18px; font-weight: 800; color: #1a1a1a; }
    .page-header .subtitle { font-size: 12.5px; color: #aaa; margin-top: 2px; }

    /* ── Stat cards ── */
    .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
    @media (max-width: 900px) { .stat-grid { grid-template-columns: repeat(2,1fr); } }

    .stat-card {
        background: #fff; border-radius: 16px;
        border: 1px solid #ebebeb; padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: box-shadow .2s, transform .15s;
    }
    .stat-card:hover { box-shadow: 0 5px 16px rgba(0,0,0,.07); transform: translateY(-1px); }
    .stat-lbl { font-size: 10.5px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
    .stat-val { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1; }
    .stat-sub { font-size: 11px; color: #bbb; margin-top: 4px; }

    /* ── Filter bar ── */
    .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
    .filter-input {
        flex: 1; min-width: 180px;
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
    .filter-select {
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none; cursor: pointer;
    }
    .filter-select:focus { border-color: #7c3aed; }

    .filter-date {
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none;
    }

    .btn-cari {
        background: #1a1a1a; color: #fff; font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 10px 20px; cursor: pointer;
        transition: background .2s;
    }
    .btn-cari:hover { background: #333; }

    .btn-export {
        background: #0d9488; color: #fff; font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 10px 18px; cursor: pointer;
        display: flex; align-items: center; gap: 7px; text-decoration: none;
        box-shadow: 0 3px 12px rgba(13,148,136,.25);
        transition: background .2s, transform .1s;
        white-space: nowrap;
    }
    .btn-export:hover { background: #0f766e; transform: translateY(-1px); }

    /* ── Table card ── */
    .table-card { background: #fff; border-radius: 20px; border: 1px solid #ebebeb; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.04); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    thead th { padding: 13px 16px; text-align: left; font-size: 10.5px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .6px; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #f8f8f8; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafafa; }
    tbody td { padding: 13px 16px; font-size: 13px; color: #555; vertical-align: middle; }

    .kode { font-family: 'JetBrains Mono', monospace; font-size: 11.5px; color: #7c3aed; font-weight: 600; }
    .total-val { font-size: 13.5px; font-weight: 800; color: #1a1a1a; }
    .komisi-val { font-size: 12px; font-weight: 700; color: #0d9488; }

    .badge { display: inline-block; font-size: 10.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
    .badge-selesai    { background: #f0fdfa; color: #059669; border: 1px solid #99f6e4; }
    .badge-dikirim    { background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; }
    .badge-diproses   { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
    .badge-menunggu   { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-dibatalkan { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-default    { background: #f5f5f5; color: #888; border: 1px solid #e8e8e8; }

    .btn-detail {
        font-size: 11.5px; font-weight: 700; padding: 5px 11px; border-radius: 8px;
        background: #f5f5f5; color: #555; text-decoration: none;
        transition: background .15s, color .15s; white-space: nowrap;
    }
    .btn-detail:hover { background: #e8e8e8; color: #1a1a1a; }

    .paging-wrap { padding: 14px 18px; border-top: 1px solid #f3f3f3; }

    .empty-row td { text-align: center; padding: 48px 16px; }
    .empty-row .ico { font-size: 32px; margin-bottom: 8px; opacity: .4; }
    .empty-row .txt { font-size: 13px; color: #bbb; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="trx-wrap">

    {{-- Header ── --}}
    <div class="page-header">
        <div>
            <div class="title">Manajemen Transaksi</div>
            <div class="subtitle">Monitor semua transaksi yang terjadi di platform</div>
        </div>
    </div>

    {{-- Stat cards ── --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-lbl">Total Volume</div>
            <div class="stat-val" style="font-size:18px;">Rp {{ number_format($stats['total_volume']/1000000,1) }}jt</div>
        </div>
        <div class="stat-card">
            <div class="stat-lbl">Komisi Diterima</div>
            <div class="stat-val" style="color:#0d9488;font-size:18px;">Rp {{ number_format($stats['total_komisi']/1000,0) }}rb</div>
            <div class="stat-sub">3% per transaksi</div>
        </div>
        <div class="stat-card">
            <div class="stat-lbl">Sukses Rate</div>
            <div class="stat-val">{{ $stats['sukses_persen'] }}%</div>
        </div>
        <div class="stat-card">
            <div class="stat-lbl">Batal / Retur</div>
            <div class="stat-val" style="color:#f59e0b;">{{ $stats['retur_persen'] }}%</div>
        </div>
    </div>

    {{-- Filter ── --}}
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="🔍  Cari kode pesanan..."
            class="filter-input">
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu"   {{ request('status') === 'menunggu'   ? 'selected' : '' }}>⏳ Menunggu</option>
            <option value="diproses"   {{ request('status') === 'diproses'   ? 'selected' : '' }}>🔄 Diproses</option>
            <option value="dikirim"    {{ request('status') === 'dikirim'    ? 'selected' : '' }}>🚚 Dikirim</option>
            <option value="selesai"    {{ request('status') === 'selesai'    ? 'selected' : '' }}>✓ Selesai</option>
            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>✕ Dibatalkan</option>
        </select>
        <input type="date" name="dari"   value="{{ request('dari') }}"   class="filter-date" title="Dari tanggal">
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="filter-date" title="Sampai tanggal">
        <button type="submit" class="btn-cari">Cari</button>
        <a href="{{ route('admin.transaksi.export', request()->only(['status','dari','sampai','search'])) }}"
            class="btn-export">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export CSV
        </a>
    </form>

    {{-- Table ── --}}
    <div class="table-card">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pembeli</th>
                        <th>Penjual</th>
                        <th>Total</th>
                        <th>Komisi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $p)
                    <tr>
                        <td><span class="kode">{{ $p->kode_pesanan }}</span></td>
                        <td>{{ $p->pembeli->nama_depan ?? '—' }}</td>
                        <td style="max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $p->toko->nama_toko ?? '—' }}
                        </td>
                        <td><span class="total-val">Rp {{ number_format($p->total,0,',','.') }}</span></td>
                        <td><span class="komisi-val">Rp {{ number_format($p->komisi_platform,0,',','.') }}</span></td>
                        <td>
                            @php
                                $badgeCls = match($p->status_pesanan) {
                                    'selesai'    => 'badge-selesai',
                                    'dikirim'    => 'badge-dikirim',
                                    'diproses'   => 'badge-diproses',
                                    'menunggu'   => 'badge-menunggu',
                                    'dibatalkan' => 'badge-dibatalkan',
                                    default      => 'badge-default',
                                };
                            @endphp
                            <span class="badge {{ $badgeCls }}">{{ ucfirst($p->status_pesanan) }}</span>
                        </td>
                        <td style="font-size:12px;color:#aaa;">{{ $p->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.transaksi.show', $p->id) }}" class="btn-detail">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <div class="ico">💳</div>
                            <div class="txt">Tidak ada transaksi ditemukan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="paging-wrap">
            {{ $pesanans->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection