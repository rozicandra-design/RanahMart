@extends('layouts.dashboard')
@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('sidebar') @include('components.sidebar-admin') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .admin-produk * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .admin-produk { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; }
    .page-header .title { font-size: 18px; font-weight: 800; color: #1a1a1a; }
    .page-header .subtitle { font-size: 12.5px; color: #aaa; margin-top: 2px; }

    /* stat pills */
    .stat-pills { display: flex; gap: 10px; flex-wrap: wrap; }
    .stat-pill { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 12px; padding: 9px 16px; display: flex; align-items: center; gap: 8px; }
    .stat-pill .val { font-size: 15px; font-weight: 800; color: #1a1a1a; }
    .stat-pill .lbl { font-size: 11px; color: #aaa; font-weight: 600; }
    .stat-pill.amber { border-color: #fde68a; background: #fffbeb; }
    .stat-pill.amber .val { color: #d97706; }

    /* filter bar */
    .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
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
    .btn-cari {
        background: #1a1a1a; color: #fff; font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 10px 20px; cursor: pointer;
        transition: background .2s;
    }
    .btn-cari:hover { background: #333; }

    /* table card */
    .table-card { background: #fff; border-radius: 20px; border: 1px solid #ebebeb; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.04); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    thead th { padding: 13px 16px; text-align: left; font-size: 10.5px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .6px; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #f8f8f8; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafafa; }
    tbody tr.pending-row { background: #fffbeb; }
    tbody td { padding: 13px 16px; font-size: 13px; color: #555; vertical-align: middle; }

    /* badges */
    .badge { display: inline-block; font-size: 10.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
    .badge-aktif    { background: #f0fdfa; color: #059669; border: 1px solid #99f6e4; }
    .badge-pending  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-ditolak  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-nonaktif { background: #f5f5f5; color: #888; border: 1px solid #e8e8e8; }

    /* action buttons */
    .action-wrap { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .btn-action {
        font-size: 11.5px; font-weight: 700; padding: 5px 11px; border-radius: 8px;
        border: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .15s, color .15s; white-space: nowrap;
    }
    .btn-setujui  { background: #f0fdfa; color: #059669; }
    .btn-setujui:hover  { background: #059669; color: #fff; }
    .btn-tolak    { background: #fef2f2; color: #dc2626; }
    .btn-tolak:hover    { background: #dc2626; color: #fff; }
    .btn-turunkan { background: #fffbeb; color: #d97706; }
    .btn-turunkan:hover { background: #d97706; color: #fff; }
    .btn-peringatkan { background: #f5f5f5; color: #666; }
    .btn-peringatkan:hover { background: #ddd; }

    .input-alasan {
        border: 1.5px solid #e8e8e8; border-radius: 8px;
        padding: 5px 10px; font-size: 11.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none; width: 110px;
        transition: border-color .2s;
    }
    .input-alasan:focus { border-color: #dc2626; }

    /* produk info */
    .produk-name { font-size: 13.5px; font-weight: 700; color: #1a1a1a; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .produk-kat  { font-size: 11px; color: #aaa; margin-top: 2px; }
    .produk-note { font-size: 11px; color: #dc2626; margin-top: 3px; }

    .toko-name { font-size: 13px; font-weight: 600; color: #1a1a1a; }
    .produk-price { font-size: 13.5px; font-weight: 800; color: #0d9488; }

    /* pagination */
    .paging-wrap { padding: 14px 18px; border-top: 1px solid #f3f3f3; }

    /* empty */
    .empty-row td { text-align: center; padding: 48px 16px; color: #ccc; font-size: 13px; }
</style>
@endpush

@section('content')
<div class="admin-produk">

    {{-- Header ── --}}
    <div class="page-header">
        <div>
            <div class="title">Kelola Produk</div>
            <div class="subtitle">Review dan moderasi produk penjual</div>
        </div>
        <div class="stat-pills">
            <div class="stat-pill amber">
                <span class="val">{{ $pendingCount ?? 0 }}</span>
                <span class="lbl">Pending Review</span>
            </div>
            <div class="stat-pill">
                <span class="val">{{ $aktifCount ?? 0 }}</span>
                <span class="lbl">Produk Aktif</span>
            </div>
            <div class="stat-pill">
                <span class="val">{{ $totalCount ?? 0 }}</span>
                <span class="lbl">Total Produk</span>
            </div>
        </div>
    </div>

    {{-- Filter ── --}}
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="🔍  Cari nama produk atau toko..."
            class="filter-input">
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ Pending Review</option>
            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>✓ Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>○ Nonaktif</option>
            <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>✕ Ditolak</option>
        </select>
        <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
            <option value="{{ $slug }}" {{ request('kategori') === $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-cari">Cari</button>
    </form>

    {{-- Table ── --}}
    <div class="table-card">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Toko</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $produk)
                    <tr class="{{ $produk->status === 'pending' ? 'pending-row' : '' }}">

                        {{-- Produk ── --}}
                        <td>
                            <div class="produk-name" title="{{ $produk->nama }}">{{ $produk->nama }}</div>
                            <div class="produk-kat">
                                {{ $produk->kategori_friendly ?? config('ranahmart.kategori_umkm.'.$produk->kategori, ucfirst(str_replace('_',' ',$produk->kategori))) }}
                            </div>
                            @if($produk->catatan_review)
                            <div class="produk-note">✕ {{ $produk->catatan_review }}</div>
                            @endif
                        </td>

                        {{-- Toko ── --}}
                        <td>
                            <div class="toko-name">{{ $produk->toko->nama_toko ?? '—' }}</div>
                        </td>

                        {{-- Harga ── --}}
                        <td>
                            <div class="produk-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        </td>

                        {{-- Stok ── --}}
                        <td>{{ $produk->stok }}</td>

                        {{-- Status ── --}}
                        <td>
                            <span class="badge {{ match($produk->status) {
                                'aktif'    => 'badge-aktif',
                                'pending'  => 'badge-pending',
                                'ditolak'  => 'badge-ditolak',
                                default    => 'badge-nonaktif',
                            } }}">{{ ucfirst($produk->status) }}</span>
                        </td>

                        {{-- Aksi ── --}}
                        <td>
                            <div class="action-wrap">
                                @if($produk->status === 'pending')
                                    <form method="POST" action="{{ route('admin.produk.setujui', $produk->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action btn-setujui">✓ Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.produk.tolak', $produk->id) }}" style="display:flex;gap:5px;align-items:center;">
                                        @csrf @method('PATCH')
                                        <input type="text" name="catatan_review" placeholder="Alasan..." class="input-alasan">
                                        <button type="submit" class="btn-action btn-tolak">✕ Tolak</button>
                                    </form>

                                @elseif($produk->status === 'aktif')
                                    <form method="POST" action="{{ route('admin.produk.turunkan', $produk->id) }}"
                                        onsubmit="return confirm('Turunkan produk ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action btn-turunkan">↓ Turunkan</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.produk.peringatkan', $produk->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action btn-peringatkan">⚠ Peringatkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="6">
                            <div style="font-size:32px;margin-bottom:8px;">📦</div>
                            Tidak ada produk ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="paging-wrap">
            {{ $produks->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection