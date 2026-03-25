@extends('layouts.dashboard')
@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('notif-route', route('penjual.notifikasi'))
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .produk-page * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .produk-page { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    /* ── Header ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 22px;
    }
    .page-header-left .title { font-size: 18px; font-weight: 800; color: #1a1a1a; }
    .page-header-left .count { font-size: 12.5px; color: #aaa; font-weight: 500; margin-top: 2px; }

    .btn-tambah {
        background: #0d9488; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 11px 20px; cursor: pointer;
        display: flex; align-items: center; gap: 7px;
        box-shadow: 0 4px 14px rgba(13,148,136,.3);
        transition: background .2s, transform .1s, box-shadow .2s;
    }
    .btn-tambah:hover { background: #0f766e; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(13,148,136,.35); }
    .btn-tambah:active { transform: translateY(0); }

    /* ── Filter bar ── */
    .filter-bar {
        display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap;
    }
    .filter-input {
        flex: 1; min-width: 180px;
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-input::placeholder { color: #c5c5c5; }
    .filter-input:focus { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }

    .filter-select {
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none; cursor: pointer;
        transition: border-color .2s;
    }
    .filter-select:focus { border-color: #0d9488; }

    .btn-cari {
        background: #1a1a1a; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 10px 20px; cursor: pointer;
        transition: background .2s;
    }
    .btn-cari:hover { background: #333; }

    /* ── Product grid ── */
    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .produk-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #ebebeb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: box-shadow .2s, transform .2s;
    }
    .produk-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.08); transform: translateY(-2px); }

    .produk-img {
        height: 140px;
        background: #f9f8f7;
        display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
    }
    .produk-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .produk-img .placeholder { font-size: 40px; opacity: .4; }

    .status-badge {
        position: absolute; top: 10px; left: 10px;
        font-size: 10px; font-weight: 800;
        padding: 3px 9px; border-radius: 999px;
        letter-spacing: .3px;
    }
    .badge-aktif    { background: #0d9488; color: #fff; }
    .badge-pending  { background: #f59e0b; color: #fff; }
    .badge-ditolak  { background: #ef4444; color: #fff; }
    .badge-nonaktif { background: #6b7280; color: #fff; }

    .produk-body { padding: 14px; }
    .produk-name { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .produk-price { font-size: 14px; font-weight: 800; color: #0d9488; margin-bottom: 3px; }
    .produk-stok { font-size: 11px; color: #aaa; font-weight: 500; }
    .produk-tolak-note { font-size: 11px; color: #ef4444; background: #fef2f2; border-radius: 7px; padding: 5px 8px; margin-top: 6px; line-height: 1.4; }

    .produk-actions { display: flex; gap: 6px; margin-top: 10px; }
    .btn-edit {
        flex: 1; text-align: center; font-size: 11.5px; font-weight: 700;
        background: #f5f5f5; color: #555; border-radius: 8px;
        padding: 6px 0; text-decoration: none; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .15s, color .15s;
    }
    .btn-edit:hover { background: #e8e8e8; color: #1a1a1a; }
    .btn-hapus {
        font-size: 11.5px; font-weight: 700;
        background: #fef2f2; color: #ef4444; border-radius: 8px;
        padding: 6px 10px; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .15s;
    }
    .btn-hapus:hover { background: #fee2e2; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
    }
    .empty-state .ico { font-size: 52px; margin-bottom: 14px; opacity: .6; }
    .empty-state .title { font-size: 15px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .empty-state .desc { font-size: 13px; color: #aaa; margin-bottom: 20px; }
    .btn-tambah-first {
        background: #0d9488; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 12px 24px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        box-shadow: 0 4px 14px rgba(13,148,136,.3);
        transition: background .2s, transform .1s;
    }
    .btn-tambah-first:hover { background: #0f766e; transform: translateY(-1px); }

    /* ══════════════════════════════
       SLIDE PANEL
    ══════════════════════════════ */
    #panel-backdrop {
        position: fixed; inset: 0; z-index: 40;
        background: rgba(0,0,0,.45);
        opacity: 0; pointer-events: none;
        transition: opacity .3s ease;
    }
    #panel-backdrop.open { opacity: 1; pointer-events: all; }

    #slide-panel {
        position: fixed; top: 0; right: 0;
        height: 100vh; width: 480px; max-width: 100vw;
        z-index: 50;
        background: #fff;
        display: flex; flex-direction: column;
        box-shadow: -8px 0 40px rgba(0,0,0,.12);
        transform: translateX(100%);
        transition: transform .3s cubic-bezier(.4,0,.2,1);
    }
    #slide-panel.open { transform: translateX(0); }

    .panel-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f3f3f3;
        flex-shrink: 0;
    }
    .panel-header .ptitle { font-size: 15px; font-weight: 800; color: #1a1a1a; }
    .panel-header .psub   { font-size: 12px; color: #aaa; margin-top: 2px; }
    .btn-close {
        width: 32px; height: 32px;
        border-radius: 9px; border: 1.5px solid #eee;
        background: #fafafa; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #aaa; flex-shrink: 0;
        transition: background .15s, color .15s;
    }
    .btn-close:hover { background: #f3f3f3; color: #1a1a1a; }

    .panel-notice {
        margin: 14px 24px 0;
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: 11px; padding: 10px 13px;
        font-size: 12px; color: #92400e; font-weight: 500;
        display: flex; gap: 8px; align-items: flex-start;
        flex-shrink: 0;
    }

    .panel-body {
        flex: 1; overflow-y: auto;
        padding: 20px 24px;
    }

    /* Panel fields */
    .pfield { margin-bottom: 16px; }
    .plabel {
        display: block; font-size: 11px; font-weight: 800;
        color: #555; text-transform: uppercase; letter-spacing: .4px;
        margin-bottom: 6px;
    }
    .plabel .req { color: #ef4444; }
    .plabel .opt { font-weight: 500; color: #bbb; text-transform: none; letter-spacing: 0; }

    .pinput {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px;
        padding: 10px 13px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .pinput::placeholder { color: #c5c5c5; }
    .pinput:focus { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1); background: #fff; }
    textarea.pinput { resize: none; }

    .pgrid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .pgrid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }

    /* Price prefix */
    .pprefix-wrap { position: relative; }
    .pprefix {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        font-size: 12.5px; font-weight: 700; color: #aaa; pointer-events: none;
    }
    .pprefix-wrap .pinput { padding-left: 34px; }

    /* Upload zone */
    .pupload {
        border: 2px dashed #e0e0e0; border-radius: 12px;
        padding: 22px 16px; text-align: center; cursor: pointer;
        background: #fafafa; position: relative;
        transition: border-color .2s, background .2s;
    }
    .pupload:hover { border-color: #0d9488; background: #f0fdfa; }
    .pupload input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .pupload-icon { font-size: 28px; margin-bottom: 6px; opacity: .5; }
    .pupload-lbl { font-size: 12.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
    .pupload-lbl span { color: #0d9488; }
    .pupload-hint { font-size: 11px; color: #bbb; }

    /* Photo preview strip */
    #foto-preview { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .pthumb {
        position: relative; width: 56px; height: 56px;
        border-radius: 8px; overflow: hidden;
        border: 2px solid #e8e8e8; flex-shrink: 0;
        animation: thumbPop .2s ease;
    }
    @keyframes thumbPop { from { opacity:0; transform:scale(.8); } to { opacity:1; transform:scale(1); } }
    .pthumb.main { border-color: #0d9488; }
    .pthumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pthumb .main-badge {
        position: absolute; top: 0; left: 0; right: 0;
        background: rgba(13,148,136,.85); color: #fff;
        font-size: 8px; font-weight: 800; text-align: center; padding: 2px 0;
        text-transform: uppercase; letter-spacing: .3px;
    }

    .panel-footer {
        padding: 16px 24px;
        border-top: 1px solid #f3f3f3;
        display: flex; gap: 10px;
        flex-shrink: 0;
        background: #fafafa;
    }
    .btn-kirim {
        flex: 1; background: #0d9488; color: #fff;
        font-size: 13.5px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 13px; cursor: pointer;
        box-shadow: 0 4px 14px rgba(13,148,136,.3);
        transition: background .2s, transform .1s;
    }
    .btn-kirim:hover { background: #0f766e; transform: translateY(-1px); }
    .btn-batal {
        background: #fff; color: #666;
        font-size: 13px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #eee; border-radius: 11px;
        padding: 12px 20px; cursor: pointer;
        transition: background .15s, color .15s;
    }
    .btn-batal:hover { background: #f5f5f5; color: #333; }
</style>
@endpush

@section('content')
<div class="produk-page">

    {{-- Flash --}}
    @if(session('success'))
    <div style="background:#f0fdfa;border:1px solid #99f6e4;color:#0f766e;border-radius:13px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="title">Kelola Produk</div>
            <div class="count">{{ $produks->total() }} produk terdaftar</div>
        </div>
        <button class="btn-tambah" onclick="openPanel()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Produk
        </button>
    </div>

    {{-- Filter --}}
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="🔍  Cari nama produk..." class="filter-input">
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending Review</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="ditolak"  {{ request('status') == 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                <option value="{{ $slug }}" {{ request('kategori') == $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-cari">Cari</button>
    </form>

    {{-- Grid --}}
    @if($produks->count())
    <div class="produk-grid">
        @foreach($produks as $produk)
        @php
            $badgeCls = match($produk->status) {
                'aktif'    => 'badge-aktif',
                'pending'  => 'badge-pending',
                'ditolak'  => 'badge-ditolak',
                default    => 'badge-nonaktif',
            };
            $badgeLbl = match($produk->status) {
                'aktif'    => '● Aktif',
                'pending'  => '⏳ Review',
                'ditolak'  => '✕ Ditolak',
                default    => '○ Nonaktif',
            };
        @endphp
        <div class="produk-card">
            <div class="produk-img">
                @if($produk->foto)
                    <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama }}">
                @else
                    <div class="placeholder">🛍️</div>
                @endif
                <span class="status-badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
            </div>
            <div class="produk-body">
                <div class="produk-name" title="{{ $produk->nama }}">{{ $produk->nama }}</div>
                <div class="produk-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                <div class="produk-stok">Stok: {{ $produk->stok }}</div>
                @if($produk->status === 'ditolak' && $produk->catatan_review)
                <div class="produk-tolak-note">✕ {{ $produk->catatan_review }}</div>
                @endif
                <div class="produk-actions">
                    <a href="{{ route('penjual.produk.edit', $produk->id) }}" class="btn-edit">Edit</a>
                    <form method="POST" action="{{ route('penjual.produk.destroy', $produk->id) }}"
                        onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus">🗑</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="margin-top:24px;">{{ $produks->withQueryString()->links() }}</div>

    @else
    <div class="empty-state">
        <div class="ico">📦</div>
        <div class="title">Belum ada produk</div>
        <div class="desc">Mulai tambahkan produk pertama untuk dijual di RanahMart</div>
        <button class="btn-tambah-first" onclick="openPanel()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Produk Pertama
        </button>
    </div>
    @endif

</div>
@endsection

{{-- ══ SLIDE PANEL (luar @section agar tidak ter-wrap main) ══ --}}
@push('modals')
<div id="panel-backdrop" onclick="closePanel()"></div>

<div id="slide-panel">
    <div class="panel-header">
        <div>
            <div class="ptitle">Tambah Produk Baru</div>
            <div class="psub">Isi data produk · direview admin 1–2 hari kerja</div>
        </div>
        <button class="btn-close" onclick="closePanel()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="panel-notice">
        <span>ℹ️</span>
        <span>Produk baru direview admin sebelum tayang. Pastikan foto & data lengkap.</span>
    </div>

    <div class="panel-body">
        <form method="POST" action="{{ route('penjual.produk.store') }}"
            enctype="multipart/form-data" id="tambah-form">
            @csrf

            <div class="pfield">
                <label class="plabel">Nama Produk <span class="req">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="pinput" placeholder="Contoh: Rendang Daging Sapi Premium 500gr">
            </div>

            <div class="pgrid-2 pfield">
                <div>
                    <label class="plabel">Kategori <span class="req">*</span></label>
                    <select name="kategori" required class="pinput">
                        <option value="">Pilih kategori</option>
                        @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                            <option value="{{ $slug }}" {{ old('kategori') == $slug ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="plabel">Sub Kategori <span class="opt">(opsional)</span></label>
                    <input type="text" name="sub_kategori" value="{{ old('sub_kategori') }}"
                        class="pinput" placeholder="Cth: Rendang, Gulai...">
                </div>
            </div>

            <div class="pfield">
                <label class="plabel">Deskripsi <span class="opt">(opsional)</span></label>
                <textarea name="deskripsi" rows="3" class="pinput"
                    placeholder="Bahan, ukuran, cara pemakaian, dll">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pgrid-2 pfield">
                <div>
                    <label class="plabel">Harga Jual <span class="req">*</span></label>
                    <div class="pprefix-wrap">
                        <span class="pprefix">Rp</span>
                        <input type="number" name="harga" value="{{ old('harga') }}" required min="0"
                            class="pinput" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="plabel">Harga Coret <span class="opt">(diskon)</span></label>
                    <div class="pprefix-wrap">
                        <span class="pprefix">Rp</span>
                        <input type="number" name="harga_coret" value="{{ old('harga_coret') }}" min="0"
                            class="pinput" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="pgrid-3 pfield">
                <div>
                    <label class="plabel">Stok <span class="req">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" class="pinput">
                </div>
                <div>
                    <label class="plabel">Berat <span class="opt">(gr)</span></label>
                    <input type="number" name="berat" value="{{ old('berat') }}" min="0" class="pinput" placeholder="500">
                </div>
                <div>
                    <label class="plabel">SKU <span class="opt">(opt)</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="pinput" placeholder="—">
                </div>
            </div>

            <div class="pfield">
                <label class="plabel">
                    Foto Produk <span class="req">*</span>
                    <span class="opt">min. 1 · maks. 5 · 5MB/foto</span>
                </label>
                <div class="pupload" id="pupload-zone">
                    <input type="file" name="foto_produk[]" multiple accept="image/*" required id="panel-foto-input">
                    <div class="pupload-icon">📷</div>
                    <div class="pupload-lbl"><span>Klik untuk unggah</span> atau seret ke sini</div>
                    <div class="pupload-hint">JPG, PNG, WebP · Foto pertama jadi foto utama</div>
                </div>
                <div id="foto-preview"></div>
            </div>

        </form>
    </div>

    <div class="panel-footer">
        <button type="submit" form="tambah-form" class="btn-kirim">
            Kirim untuk Review →
        </button>
        <button type="button" class="btn-batal" onclick="closePanel()">Batal</button>
    </div>
</div>
@endpush

@push('scripts')
<script>
/* ── Panel open/close (pure CSS class toggle) ── */
function openPanel() {
    document.getElementById('panel-backdrop').classList.add('open');
    document.getElementById('slide-panel').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closePanel() {
    document.getElementById('panel-backdrop').classList.remove('open');
    document.getElementById('slide-panel').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });

@if($errors->any())
document.addEventListener('DOMContentLoaded', openPanel);
@endif

/* ── Drag-over zone ── */
const pzone = document.getElementById('pupload-zone');
pzone.addEventListener('dragover',  e => { e.preventDefault(); pzone.style.borderColor = '#0d9488'; pzone.style.background = '#f0fdfa'; });
pzone.addEventListener('dragleave', ()  => { pzone.style.borderColor = ''; pzone.style.background = ''; });
pzone.addEventListener('drop',      e  => { e.preventDefault(); pzone.style.borderColor = ''; handleFoto(e.dataTransfer.files); });

/* ── Photo preview ── */
document.getElementById('panel-foto-input').addEventListener('change', e => handleFoto(e.target.files));

function handleFoto(incoming) {
    const preview = document.getElementById('foto-preview');
    preview.innerHTML = '';
    Array.from(incoming).slice(0, 5).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = ev => {
            const wrap = document.createElement('div');
            wrap.className = 'pthumb' + (i === 0 ? ' main' : '');
            wrap.innerHTML = `
                ${i === 0 ? '<div class="main-badge">Utama</div>' : ''}
                <img src="${ev.target.result}" alt="">
            `;
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush