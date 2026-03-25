@extends('layouts.dashboard')
@section('title', 'Detail UMKM')
@section('page-title', 'Detail UMKM')
@section('sidebar') @include('components.sidebar-admin') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');
    .umkm-detail * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .umkm-detail { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 600; color: #888;
        text-decoration: none; margin-bottom: 22px; transition: color .2s;
    }
    .back-link:hover { color: #1a1a1a; }
    .back-link:hover svg { transform: translateX(-3px); }
    .back-link svg { transition: transform .2s; }

    .page-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1024px) { .page-grid { grid-template-columns: 1fr; } }

    .col { display: flex; flex-direction: column; gap: 16px; }

    .card {
        background: #fff; border-radius: 18px;
        border: 1px solid #ebebeb; padding: 22px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }

    /* ── Store header ── */
    .store-avatar {
        width: 48px; height: 48px; border-radius: 13px;
        background: #ccfbf1; color: #0d9488;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 800; flex-shrink: 0;
    }
    .store-name { font-size: 17px; font-weight: 800; color: #1a1a1a; }
    .store-meta { font-size: 12.5px; color: #aaa; margin-top: 2px; }
    .store-dinas { font-size: 11px; font-weight: 700; color: #0d9488; margin-top: 4px; }

    .status-badge {
        font-size: 11px; font-weight: 700; padding: 4px 12px;
        border-radius: 999px; flex-shrink: 0;
    }
    .badge-aktif          { background: #f0fdfa; color: #059669; border: 1px solid #99f6e4; }
    .badge-pending        { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-menunggu_dinas { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
    .badge-ditolak        { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-dokumen_kurang { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .badge-default        { background: #f5f5f5; color: #888; border: 1px solid #e8e8e8; }

    /* ── Info grid — 3 kolom ── */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
    .info-cell {
        background: #f9f8f7; border-radius: 11px;
        padding: 12px 14px; border: 1px solid #f0eeeb;
    }
    .info-cell .lbl { font-size: 10px; color: #bbb; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .info-cell .val { font-size: 13px; font-weight: 700; color: #1a1a1a; }
    .info-cell .sub { font-size: 11px; color: #aaa; margin-top: 1px; }
    .info-cell .mono { font-family: 'JetBrains Mono', monospace; font-size: 12px; }

    /* ── Section title ── */
    .sec-title {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
    }
    .sec-title .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

    /* ── Catatan ── */
    .catatan-box {
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: 11px; padding: 12px 14px; margin-top: 12px;
    }
    .catatan-box .lbl { font-size: 10.5px; font-weight: 800; color: #d97706; margin-bottom: 4px; }
    .catatan-box p { font-size: 12.5px; color: #92400e; line-height: 1.6; }

    .desc-box {
        background: #f9f8f7; border-radius: 11px;
        padding: 12px 14px; border: 1px solid #f0eeeb; margin-top: 12px;
    }
    .desc-box .lbl { font-size: 10px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
    .desc-box p { font-size: 13px; color: #555; line-height: 1.7; }

    /* ── Dokumen photos ── */
    .doc-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
    .doc-item { border-radius: 11px; overflow: hidden; border: 1px solid #ebebeb; }
    .doc-item img { width: 100%; height: 90px; object-fit: cover; display: block; }
    .doc-lbl { font-size: 10.5px; font-weight: 600; color: #888; padding: 6px 10px; background: #fafafa; text-align: center; }

    /* ── Produk list ── */
    .produk-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5;
    }
    .produk-row:last-child { border-bottom: none; padding-bottom: 0; }
    .produk-row:first-child { padding-top: 0; }
    .produk-nm { font-size: 13px; font-weight: 600; color: #1a1a1a; }
    .produk-rp { font-size: 12.5px; font-weight: 700; color: #0d9488; }
    .mini-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; }

    /* ── Action buttons ── */
    .btn-full {
        width: 100%; border: none; border-radius: 11px;
        padding: 12px; cursor: pointer; font-size: 13px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        transition: background .15s, transform .1s; margin-bottom: 8px;
    }
    .btn-full:last-child { margin-bottom: 0; }
    .btn-full:hover { transform: translateY(-1px); }
    .btn-setujui { background: #0d9488; color: #fff; box-shadow: 0 3px 12px rgba(13,148,136,.25); }
    .btn-setujui:hover { background: #0f766e; }
    .btn-dinas   { background: #7c3aed; color: #fff; box-shadow: 0 3px 12px rgba(124,58,237,.25); }
    .btn-dinas:hover { background: #6d28d9; }
    .btn-dok     { background: #f59e0b; color: #fff; box-shadow: 0 3px 12px rgba(245,158,11,.25); }
    .btn-dok:hover { background: #d97706; }
    .btn-tolak   { background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; }
    .btn-tolak:hover { background: #dc2626; color: #fff; }
    .btn-nonaktif { background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; }
    .btn-nonaktif:hover { background: #dc2626; color: #fff; }

    .textarea-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px;
        padding: 10px 13px; font-size: 12.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none; resize: none; margin-bottom: 8px;
        transition: border-color .2s, box-shadow .2s;
    }
    .textarea-field:focus { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1); background: #fff; }

    .sep { height: 1px; background: #f3f3f3; margin: 12px 0; }

    /* ── Info singkat ── */
    .info-row {
        display: flex; justify-content: space-between;
        padding: 8px 0; border-bottom: 1px solid #f5f5f5;
        font-size: 12.5px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .lbl { color: #aaa; }
    .info-row .val { font-weight: 700; color: #1a1a1a; }
</style>
@endpush

@section('content')
<div class="umkm-detail">

    <a href="{{ route('admin.umkm.index') }}" class="back-link">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Kembali ke Daftar UMKM
    </a>

    <div class="page-grid">

        {{-- ===== KIRI ===== --}}
        <div class="col">

            {{-- Info Utama --}}
            <div class="card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:18px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="store-avatar">{{ strtoupper(substr($toko->nama_toko,0,1)) }}</div>
                        <div>
                            <div class="store-name">{{ $toko->nama_toko }}</div>
                            <div class="store-meta">{{ $toko->kategori_friendly }} · {{ $toko->kecamatan }}</div>
                            @if($toko->terverifikasi_dinas)
                            <div class="store-dinas">✓ Terverifikasi Dinas</div>
                            @endif
                        </div>
                    </div>
                    @php
                        $badgeCls = match($toko->status) {
                            'aktif'          => 'badge-aktif',
                            'pending'        => 'badge-pending',
                            'menunggu_dinas' => 'badge-menunggu_dinas',
                            'ditolak'        => 'badge-ditolak',
                            'dokumen_kurang' => 'badge-dokumen_kurang',
                            default          => 'badge-default',
                        };
                        $badgeLbl = match($toko->status) {
                            'aktif'          => '● Aktif',
                            'pending'        => '⏳ Pending',
                            'menunggu_dinas' => '📋 Menunggu Dinas',
                            'ditolak'        => '✕ Ditolak',
                            'dokumen_kurang' => '📄 Dokumen Kurang',
                            default          => ucfirst(str_replace('_',' ',$toko->status)),
                        };
                    @endphp
                    <span class="status-badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
                </div>

                <div class="info-grid">
                    <div class="info-cell">
                        <div class="lbl">Pemilik</div>
                        <div class="val">{{ $toko->user->nama_lengkap ?? '—' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="lbl">Email / No. HP</div>
                        <div class="val" style="font-size:12px;">{{ $toko->user->email ?? '—' }}</div>
                        <div class="sub">{{ $toko->no_hp ?? '—' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="lbl">NIB</div>
                        <div class="val mono">{{ $toko->nib ?? 'Belum ada' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="lbl">Rekening</div>
                        <div class="val" style="font-size:12px;">{{ $toko->bank ?? '—' }} · <span class="mono">{{ $toko->no_rekening ?? '—' }}</span></div>
                        <div class="sub">a/n {{ $toko->atas_nama_rekening ?? '—' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="lbl">Bergabung</div>
                        <div class="val">{{ $toko->created_at?->format('d M Y') ?? '—' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="lbl">Rating / Pesanan</div>
                        <div class="val">★ {{ $toko->rating }} · {{ $toko->total_pesanan }} pesanan</div>
                    </div>
                </div>

                @if($toko->deskripsi)
                <div class="desc-box">
                    <div class="lbl">Deskripsi Usaha</div>
                    <p>{{ $toko->deskripsi }}</p>
                </div>
                @endif

                @if($toko->catatan_admin)
                <div class="catatan-box">
                    <div class="lbl">⚠ Catatan Admin</div>
                    <p>{{ $toko->catatan_admin }}</p>
                </div>
                @endif
            </div>

            {{-- Dokumen --}}
            @if($toko->foto_ktp || $toko->foto_usaha || $toko->foto_produk_sample)
            <div class="card">
                <div class="sec-title"><span class="dot" style="background:#7c3aed;"></span> Dokumen Terlampir</div>
                <div class="doc-grid">
                    @if($toko->foto_ktp)
                    <div class="doc-item">
                        <img src="{{ Storage::url($toko->foto_ktp) }}" alt="KTP">
                        <div class="doc-lbl">🪪 Foto KTP</div>
                    </div>
                    @endif
                    @if($toko->foto_usaha)
                    <div class="doc-item">
                        <img src="{{ Storage::url($toko->foto_usaha) }}" alt="Usaha">
                        <div class="doc-lbl">🏪 Tempat Usaha</div>
                    </div>
                    @endif
                    @if($toko->foto_produk_sample)
                    <div class="doc-item">
                        <img src="{{ Storage::url($toko->foto_produk_sample) }}" alt="Produk">
                        <div class="doc-lbl">📦 Produk Sample</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Produk --}}
            @if($toko->produks->count())
            <div class="card">
                <div class="sec-title">
                    <span class="dot" style="background:#3b82f6;"></span>
                    Produk Toko
                    <span style="margin-left:auto;font-size:11px;font-weight:700;background:#eff6ff;color:#3b82f6;border:1px solid #bfdbfe;padding:2px 9px;border-radius:999px;">{{ $toko->produks->count() }}</span>
                </div>
                @foreach($toko->produks->take(5) as $produk)
                <div class="produk-row">
                    <div class="produk-nm">{{ $produk->nama }}</div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        <span class="produk-rp">Rp {{ number_format($produk->harga,0,',','.') }}</span>
                        <span class="mini-badge {{ $produk->status === 'aktif' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($produk->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ===== KANAN ===== --}}
        <div class="col">

            {{-- Aksi Verifikasi --}}
            @if(in_array($toko->status, ['pending','menunggu_dinas','dokumen_kurang']))
            <div class="card">
                <div class="sec-title"><span class="dot" style="background:#0d9488;"></span> Keputusan Verifikasi</div>

                <form method="POST" action="{{ route('admin.umkm.setujui', $toko->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-full btn-setujui">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Setujui & Aktifkan UMKM
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.umkm.teruskan-dinas', $toko->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-full btn-dinas">
                        📋 Teruskan ke Dinas
                    </button>
                </form>

                <div class="sep"></div>

                <form method="POST" action="{{ route('admin.umkm.minta-dokumen', $toko->id) }}">
                    @csrf @method('PATCH')
                    <textarea name="catatan" rows="2" required class="textarea-field"
                        placeholder="Dokumen yang masih kurang..."></textarea>
                    <button type="submit" class="btn-full btn-dok">
                        📄 Minta Lengkapi Dokumen
                    </button>
                </form>

                <div class="sep"></div>

                <form method="POST" action="{{ route('admin.umkm.tolak', $toko->id) }}">
                    @csrf @method('PATCH')
                    <textarea name="catatan" rows="2" required class="textarea-field"
                        placeholder="Alasan penolakan..."></textarea>
                    <button type="submit" class="btn-full btn-tolak">
                        ✕ Tolak Pendaftaran
                    </button>
                </form>
            </div>
            @endif

            {{-- Moderasi --}}
            @if($toko->status === 'aktif')
            <div class="card">
                <div class="sec-title"><span class="dot" style="background:#ef4444;"></span> Moderasi</div>
                <form method="POST" action="{{ route('admin.umkm.nonaktif', $toko->id) }}"
                    onsubmit="return confirm('Nonaktifkan toko ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-full btn-nonaktif">Nonaktifkan Toko</button>
                </form>
            </div>
            @endif

            {{-- Info Singkat --}}
            <div class="card">
                <div class="sec-title"><span class="dot" style="background:#aaa;"></span> Info Singkat</div>
                <div class="info-row">
                    <span class="lbl">Produk</span>
                    <span class="val">{{ $toko->produks->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Rating</span>
                    <span class="val">★ {{ $toko->rating }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Total Pesanan</span>
                    <span class="val">{{ $toko->total_pesanan }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Bergabung</span>
                    <span class="val">{{ $toko->created_at?->format('d M Y') }}</span>
                </div>
                @if($toko->terverifikasi_dinas)
                <div class="info-row">
                    <span class="lbl">Verifikasi Dinas</span>
                    <span class="val" style="color:#0d9488;">✓ Ya</span>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection