@extends('layouts.dashboard')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .notif-page * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .notif-page { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    /* ── Header ── */
    .notif-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
    }
    .notif-header .title  { font-size: 18px; font-weight: 800; color: #1a1a1a; }
    .notif-header .count  { font-size: 12.5px; color: #aaa; font-weight: 500; margin-top: 2px; }
    .btn-baca-semua {
        font-size: 12px; font-weight: 700; color: #0d9488;
        background: #f0fdfa; border: 1px solid #99f6e4;
        border-radius: 9px; padding: 7px 14px; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .15s, color .15s;
    }
    .btn-baca-semua:hover { background: #0d9488; color: #fff; border-color: #0d9488; }

    /* ── Filter/Sort bar ── */
    .filter-bar {
        display: flex; gap: 10px; margin-bottom: 20px;
        flex-wrap: wrap; align-items: center;
    }
    .filter-select {
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 9px 14px; font-size: 12.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff; outline: none; cursor: pointer;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-select:focus { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }

    .filter-badge {
        font-size: 11.5px; font-weight: 700;
        padding: 5px 12px; border-radius: 999px; cursor: pointer;
        border: 1.5px solid #e8e8e8; background: #fff; color: #888;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all .15s;
    }
    .filter-badge.active { background: #0d9488; color: #fff; border-color: #0d9488; }
    .filter-badge:hover:not(.active) { border-color: #0d9488; color: #0d9488; }

    .filter-right {
        margin-left: auto;
        font-size: 12px; color: #bbb; font-weight: 500;
    }

    /* ── Notif list ── */
    .notif-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    @media (max-width: 900px) { .notif-list { grid-template-columns: 1fr; } }

    .notif-item {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #ebebeb;
        padding: 16px 18px;
        display: flex; align-items: flex-start; gap: 14px;
        cursor: pointer;
        transition: box-shadow .2s, transform .15s, border-color .2s;
        position: relative; overflow: hidden;
    }
    .notif-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); transform: translateY(-1px); }
    .notif-item.unread {
        border-left: 4px solid #0d9488;
        background: #fafffe;
    }
    .notif-item.unread::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(13,148,136,.03), transparent);
        pointer-events: none;
    }

    .notif-icon {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; font-weight: 800; flex-shrink: 0;
    }
    .icon-success { background: #f0fdfa; color: #0d9488; border: 1.5px solid #99f6e4; }
    .icon-danger  { background: #fef2f2; color: #ef4444; border: 1.5px solid #fecaca; }
    .icon-warning { background: #fffbeb; color: #d97706; border: 1.5px solid #fde68a; }
    .icon-info    { background: #eff6ff; color: #3b82f6; border: 1.5px solid #bfdbfe; }

    .notif-content { flex: 1; min-width: 0; }
    .notif-title { font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .notif-pesan {
        font-size: 12.5px; color: #666; line-height: 1.6;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .notif-time  { font-size: 11px; color: #bbb; margin-top: 6px; font-weight: 500; }
    .notif-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #0d9488; flex-shrink: 0; margin-top: 4px;
    }

    /* ── Pagination ── */
    .notif-paging {
        margin-top: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
    }
    .paging-info {
        font-size: 12.5px; color: #aaa; font-weight: 500;
    }
    .paging-info strong { color: #1a1a1a; }
    .paging-nav { display: flex; gap: 6px; align-items: center; }
    .paging-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12.5px; font-weight: 700; color: #555;
        background: #fff; border: 1.5px solid #e8e8e8;
        border-radius: 10px; padding: 8px 14px;
        text-decoration: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color .15s, color .15s, background .15s;
    }
    .paging-btn:hover { border-color: #0d9488; color: #0d9488; background: #f0fdfa; }
    .paging-btn.disabled { opacity: .35; pointer-events: none; }
    .paging-pages { display: flex; gap: 4px; }
    .paging-page {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 12.5px; font-weight: 700; color: #555;
        background: #fff; border: 1.5px solid #e8e8e8;
        text-decoration: none; cursor: pointer;
        transition: all .15s;
    }
    .paging-page:hover { border-color: #0d9488; color: #0d9488; }
    .paging-page.active { background: #0d9488; color: #fff; border-color: #0d9488; }
    .paging-page.dots { border-color: transparent; background: transparent; cursor: default; color: #aaa; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 20px; border: 1px solid #ebebeb;
        grid-column: 1 / -1;
    }
    .empty-state .ico   { font-size: 48px; margin-bottom: 12px; opacity: .5; }
    .empty-state .title { font-size: 15px; font-weight: 800; color: #1a1a1a; margin-bottom: 5px; }
    .empty-state .desc  { font-size: 13px; color: #aaa; }

    /* ══ MODAL ══ */
    #notif-backdrop {
        position: fixed; inset: 0; z-index: 40;
        background: rgba(0,0,0,.45);
        opacity: 0; pointer-events: none;
        transition: opacity .25s ease;
    }
    #notif-backdrop.open { opacity: 1; pointer-events: all; }

    #notif-modal {
        position: fixed; top: 50%; left: 50%;
        transform: translate(-50%, -48%) scale(.96);
        z-index: 50; background: #fff; border-radius: 22px;
        width: 500px; max-width: calc(100vw - 32px);
        box-shadow: 0 24px 60px rgba(0,0,0,.18);
        opacity: 0; pointer-events: none;
        transition: transform .25s cubic-bezier(.4,0,.2,1), opacity .25s ease;
        overflow: hidden;
    }
    #notif-modal.open { transform: translate(-50%,-50%) scale(1); opacity: 1; pointer-events: all; }

    .modal-header {
        padding: 22px 24px 18px; border-bottom: 1px solid #f3f3f3;
        display: flex; align-items: flex-start; gap: 14px;
    }
    .modal-icon { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .modal-title-wrap { flex: 1; min-width: 0; }
    .modal-title { font-size: 15px; font-weight: 800; color: #1a1a1a; line-height: 1.3; margin-bottom: 4px; }
    .modal-time  { font-size: 11.5px; color: #bbb; font-weight: 500; }
    .modal-close {
        width: 30px; height: 30px; border-radius: 8px;
        border: 1.5px solid #eee; background: #fafafa; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; color: #aaa;
        transition: background .15s, color .15s;
    }
    .modal-close:hover { background: #f3f3f3; color: #1a1a1a; }
    .modal-body { padding: 20px 24px; }
    .modal-pesan { font-size: 13.5px; color: #444; line-height: 1.8; white-space: pre-wrap; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid #f3f3f3; display: flex; gap: 10px; justify-content: flex-end; }
    .modal-btn-url {
        background: #0d9488; color: #fff; font-size: 12.5px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif; border: none; border-radius: 10px;
        padding: 9px 18px; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; transition: background .15s;
    }
    .modal-btn-url:hover { background: #0f766e; }
    .modal-btn-tutup {
        background: #f5f5f5; color: #666; font-size: 12.5px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif; border: 1.5px solid #eee;
        border-radius: 10px; padding: 8px 16px; cursor: pointer; transition: background .15s;
    }
    .modal-btn-tutup:hover { background: #eee; }
</style>
@endpush

@section('content')
<div class="notif-page">

    {{-- Header --}}
    <div class="notif-header">
        <div>
            <div class="title">Notifikasi</div>
            <div class="count">{{ $notifikasis->total() }} notifikasi</div>
        </div>
        <form method="POST" action="{{ route('penjual.notifikasi.baca-semua') }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn-baca-semua">✓ Tandai semua dibaca</button>
        </form>
    </div>

    {{-- Filter & Sort bar ── --}}
    <form method="GET" class="filter-bar">
        {{-- Status filter --}}
        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
            class="filter-badge {{ !request('status') ? 'active' : '' }}">Semua</a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'belum']) }}"
            class="filter-badge {{ request('status') == 'belum' ? 'active' : '' }}">Belum Dibaca</a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'sudah']) }}"
            class="filter-badge {{ request('status') == 'sudah' ? 'active' : '' }}">Sudah Dibaca</a>

        {{-- Tipe --}}
        <select name="tipe" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Tipe</option>
            <option value="success" {{ request('tipe') == 'success' ? 'selected' : '' }}>✓ Sukses</option>
            <option value="warning" {{ request('tipe') == 'warning' ? 'selected' : '' }}>⚠ Peringatan</option>
            <option value="danger"  {{ request('tipe') == 'danger'  ? 'selected' : '' }}>✕ Penting</option>
            <option value="info"    {{ request('tipe') == 'info'    ? 'selected' : '' }}>ℹ Info</option>
        </select>

        {{-- Sort --}}
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
        </select>

        <div class="filter-right">
            Hal. {{ $notifikasis->currentPage() }} / {{ $notifikasis->lastPage() }}
            &nbsp;·&nbsp; 14 per halaman
        </div>
    </form>

    {{-- List --}}
    <div class="notif-list">
        @forelse($notifikasis as $notif)
        @php
            $iconCls  = match($notif->tipe) { 'success'=>'icon-success','danger'=>'icon-danger','warning'=>'icon-warning',default=>'icon-info' };
            $iconChar = match($notif->tipe) { 'success'=>'✓','danger'=>'✕','warning'=>'⚠',default=>'ℹ' };
        @endphp
        <div class="notif-item {{ !$notif->sudah_dibaca ? 'unread' : '' }}"
            onclick="openNotif(
                {{ $notif->id }},
                {{ json_encode($notif->judul) }},
                {{ json_encode($notif->pesan) }},
                {{ json_encode($notif->created_at->format('d M Y · H:i') . ' WIB') }},
                '{{ $iconCls }}',
                '{{ $iconChar }}',
                {{ json_encode($notif->url) }}
            )">
            <div class="notif-icon {{ $iconCls }}">{{ $iconChar }}</div>
            <div class="notif-content">
                <div class="notif-title">{{ $notif->judul }}</div>
                <div class="notif-pesan">{{ $notif->pesan }}</div>
                <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @if(!$notif->sudah_dibaca)
            <div class="notif-dot"></div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <div class="ico">🔔</div>
            <div class="title">Tidak ada notifikasi</div>
            <div class="desc">
                @if(request('status') || request('tipe'))
                    Tidak ada notifikasi untuk filter ini
                @else
                    Semua notifikasi akan muncul di sini
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination ── --}}
    @if($notifikasis->lastPage() > 1)
    <div class="notif-paging">
        <div class="paging-info">
            Menampilkan <strong>{{ $notifikasis->firstItem() }}–{{ $notifikasis->lastItem() }}</strong>
            dari <strong>{{ $notifikasis->total() }}</strong> notifikasi
        </div>

        <div class="paging-nav">
            {{-- Prev --}}
            @if($notifikasis->onFirstPage())
            <span class="paging-btn disabled">
                ← Sebelumnya
            </span>
            @else
            <a href="{{ $notifikasis->previousPageUrl() }}" class="paging-btn">← Sebelumnya</a>
            @endif

            {{-- Page numbers --}}
            <div class="paging-pages">
                @foreach($notifikasis->getUrlRange(1, $notifikasis->lastPage()) as $page => $url)
                    @if($page == $notifikasis->currentPage())
                        <span class="paging-page active">{{ $page }}</span>
                    @elseif($page == 1 || $page == $notifikasis->lastPage() || abs($page - $notifikasis->currentPage()) <= 1)
                        <a href="{{ $url }}" class="paging-page">{{ $page }}</a>
                    @elseif(abs($page - $notifikasis->currentPage()) == 2)
                        <span class="paging-page dots">…</span>
                    @endif
                @endforeach
            </div>

            {{-- Next --}}
            @if($notifikasis->hasMorePages())
            <a href="{{ $notifikasis->nextPageUrl() }}" class="paging-btn">Selanjutnya →</a>
            @else
            <span class="paging-btn disabled">Selanjutnya →</span>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- Modal --}}
<div id="notif-backdrop" onclick="closeNotif()"></div>
<div id="notif-modal">
    <div class="modal-header">
        <div class="modal-icon" id="modal-icon"></div>
        <div class="modal-title-wrap">
            <div class="modal-title" id="modal-title"></div>
            <div class="modal-time"  id="modal-time"></div>
        </div>
        <button class="modal-close" onclick="closeNotif()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="modal-body">
        <div class="modal-pesan" id="modal-pesan"></div>
    </div>
    <div class="modal-footer" id="modal-footer">
        <button class="modal-btn-tutup" onclick="closeNotif()">Tutup</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openNotif(id, judul, pesan, waktu, iconCls, iconChar, url) {
    document.getElementById('modal-icon').className   = 'modal-icon ' + iconCls;
    document.getElementById('modal-icon').textContent = iconChar;
    document.getElementById('modal-title').textContent = judul;
    document.getElementById('modal-time').textContent  = waktu;
    document.getElementById('modal-pesan').textContent = pesan;

    const footer  = document.getElementById('modal-footer');
    const existing = footer.querySelector('.modal-btn-url');
    if (existing) existing.remove();
    if (url && url !== 'null') {
        const btn = document.createElement('a');
        btn.href      = url;
        btn.className = 'modal-btn-url';
        btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg> Lihat Detail';
        footer.insertBefore(btn, footer.querySelector('.modal-btn-tutup'));
    }

    document.getElementById('notif-backdrop').classList.add('open');
    document.getElementById('notif-modal').classList.add('open');
    document.body.style.overflow = 'hidden';

    // Mark as read
    fetch(`/penjual/notifikasi/${id}/baca`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
    }).then(() => {
        document.querySelectorAll('.notif-item').forEach(item => {
            if (item.getAttribute('onclick')?.includes(`${id},`)) {
                item.classList.remove('unread');
                const dot = item.querySelector('.notif-dot');
                if (dot) dot.remove();
            }
        });
    }).catch(() => {});
}

function closeNotif() {
    document.getElementById('notif-backdrop').classList.remove('open');
    document.getElementById('notif-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNotif(); });
</script>
@endpush