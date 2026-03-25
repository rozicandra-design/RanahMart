@extends('layouts.dashboard')
@section('title', 'Pengaturan Toko')
@section('page-title', 'Pengaturan Toko')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .sett-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    .sett-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px;
    }

    .sett-inner {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        align-items: start;
    }

    @media (max-width: 1024px) { .sett-inner { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 640px)  { .sett-inner { grid-template-columns: 1fr; } }

    /* ── Card ── */
    .card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 24px 26px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    /* ── Section title ── */
    .section-title {
        font-size: 13px; font-weight: 800; color: #1a1a1a;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 18px;
    }
    .section-title .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

    /* ── Toggle row ── */
    .toggle-row {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .toggle-row:last-of-type { border-bottom: none; padding-bottom: 0; }
    .toggle-row:first-of-type { padding-top: 0; }

    .toggle-info .label { font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }
    .toggle-info .desc  { font-size: 11.5px; color: #aaa; font-weight: 500; line-height: 1.4; }

    /* Custom toggle switch */
    .toggle-switch { position: relative; flex-shrink: 0; }
    .toggle-switch input { display: none; }
    .toggle-track {
        width: 44px; height: 24px;
        border-radius: 999px;
        background: #e8e8e8;
        display: block; cursor: pointer;
        transition: background .2s;
        position: relative;
    }
    .toggle-track::after {
        content: '';
        position: absolute; top: 3px; left: 3px;
        width: 18px; height: 18px;
        border-radius: 50%; background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,.2);
        transition: transform .2s;
    }
    .toggle-switch input:checked + .toggle-track { background: #0d9488; }
    .toggle-switch input:checked + .toggle-track::after { transform: translateX(20px); }

    .toggle-switch input.amber:checked + .toggle-track { background: #f59e0b; }

    /* ── Notif rows ── */
    .notif-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5;
    }
    .notif-row:last-child { border-bottom: none; padding-bottom: 0; }
    .notif-row:first-child { padding-top: 0; }
    .notif-label { font-size: 13px; color: #444; font-weight: 500; }

    /* Mini toggle for notif */
    .mini-toggle { position: relative; flex-shrink: 0; }
    .mini-toggle input { display: none; }
    .mini-track {
        width: 36px; height: 20px;
        border-radius: 999px; background: #e8e8e8;
        display: block; cursor: pointer;
        transition: background .2s; position: relative;
    }
    .mini-track::after {
        content: ''; position: absolute; top: 2px; left: 2px;
        width: 16px; height: 16px; border-radius: 50%; background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.2); transition: transform .2s;
    }
    .mini-toggle input:checked + .mini-track { background: #0d9488; }
    .mini-toggle input:checked + .mini-track::after { transform: translateX(16px); }

    /* ── Textarea ── */
    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 11px 14px; font-size: 13.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none; resize: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1); background: #fff; }

    .field-label {
        display: block; font-size: 11px; font-weight: 800; color: #555;
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px;
    }

    /* ── Buttons ── */
    .btn-primary {
        background: #0d9488; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 10px;
        padding: 10px 20px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        box-shadow: 0 3px 12px rgba(13,148,136,.25);
        transition: background .2s, transform .1s;
    }
    .btn-primary:hover { background: #0f766e; transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }

    .btn-secondary {
        background: #f5f5f5; color: #555;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #eee; border-radius: 10px;
        padding: 9px 18px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        transition: background .2s, color .2s;
    }
    .btn-secondary:hover { background: #eee; color: #333; }

    /* ── Danger zone ── */
    .danger-card {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid #fecaca;
        padding: 24px 26px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .danger-title {
        font-size: 13px; font-weight: 800; color: #dc2626;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 8px;
    }
    .danger-desc {
        font-size: 12px; color: #888; line-height: 1.6; margin-bottom: 16px;
    }
    .btn-danger {
        background: #fef2f2; color: #dc2626;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #fecaca; border-radius: 10px;
        padding: 10px 18px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        transition: background .2s, color .2s, border-color .2s;
    }
    .btn-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
</style>
@endpush

@section('content')
<div class="sett-wrap">
<div class="sett-inner">

    {{-- ── Status Toko ── --}}
    <div class="card">
        <div class="section-title">
            <span class="dot" style="background:#0d9488;"></span>
            Status Toko
        </div>

        <form method="POST" action="{{ route('penjual.pengaturan.simpan') }}">
            @csrf @method('PUT')

            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="label">Toko Aktif</div>
                    <div class="desc">Pembeli dapat melihat dan memesan dari toko kamu</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="toko_aktif" value="1"
                        {{ (auth()->user()->toko->toko_aktif ?? false) ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="label">Mode Liburan</div>
                    <div class="desc">Toko tetap terlihat tapi tidak bisa dipesan sementara</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="mode_liburan" value="1" class="amber"
                        {{ (auth()->user()->toko->mode_liburan ?? false) ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div style="margin-top:18px;">
                <button type="submit" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    {{-- ── Preferensi Notifikasi ── --}}
    <div class="card">
        <div class="section-title">
            <span class="dot" style="background:#7c3aed;"></span>
            Preferensi Notifikasi
        </div>

        @foreach([
            ['icon' => '📦', 'label' => 'Pesanan masuk baru'],
            ['icon' => '✅', 'label' => 'Pesanan selesai'],
            ['icon' => '⭐', 'label' => 'Ulasan baru dari pembeli'],
            ['icon' => '📢', 'label' => 'Iklan disetujui / ditolak'],
            ['icon' => '💰', 'label' => 'Dana siap dicairkan'],
        ] as $notif)
        <div class="notif-row">
            <div class="notif-label">
                <span style="margin-right:8px;">{{ $notif['icon'] }}</span>{{ $notif['label'] }}
            </div>
            <label class="mini-toggle">
                <input type="checkbox" checked>
                <span class="mini-track"></span>
            </label>
        </div>
        @endforeach
    </div>

    {{-- ── Pesan Otomatis ── --}}
    <div class="card">
        <div class="section-title">
            <span class="dot" style="background:#f59e0b;"></span>
            Pesan Otomatis ke Pembeli
        </div>

        <form>
            <div style="margin-bottom:16px;">
                <label class="field-label">Saat Konfirmasi Pesanan</label>
                <textarea rows="3" class="input-field"
                    placeholder="Contoh: Terima kasih telah berbelanja! Pesanan Anda sedang kami proses...">{{ auth()->user()->toko->pesan_konfirmasi ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn-secondary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Simpan Pesan
            </button>
        </form>
    </div>

    {{-- ── Zona Bahaya ── --}}
    <div class="danger-card">
        <div class="danger-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Zona Bahaya
        </div>
        <div class="danger-desc">
            Menutup toko akan menghapus semua produk dan data toko secara permanen.
            Tindakan ini <strong>tidak bisa dibatalkan</strong>.
        </div>
        <button class="btn-danger"
            onclick="return confirm('Yakin ingin menutup toko? Tindakan ini tidak bisa dibatalkan!')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            Tutup Toko Permanen
        </button>
    </div>

</div>
</div>
@endsection