@extends('layouts.dashboard')
@section('title', 'Detail Monitoring UMKM')
@section('page-title', 'Detail Monitoring UMKM')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .monitoring-wrap * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page shell ── */
    .monitoring-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px 28px 48px;
    }

    /* ── Back link ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #888;
        text-decoration: none;
        margin-bottom: 24px;
        transition: color .2s;
    }
    .back-link:hover { color: #1a1a1a; }
    .back-link svg { transition: transform .2s; }
    .back-link:hover svg { transform: translateX(-3px); }

    /* ── Grid layout ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }

    /* ── Card base ── */
    .card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #ebebeb;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .card + .card { margin-top: 20px; }

    /* ── Hero card ── */
    .hero-card {
        background: linear-gradient(135deg, #1a0533 0%, #2d1052 100%);
        border-radius: 18px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .hero-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 90% 10%, rgba(167,139,250,.25) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(79,195,247,.12) 0%, transparent 45%);
        pointer-events: none;
    }
    .hero-card::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        border: 40px solid rgba(255,255,255,.04);
    }

    .store-avatar {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #a78bfa, #7c3aed);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(124,58,237,.4);
    }

    .store-name { font-size: 20px; font-weight: 800; letter-spacing: -.3px; }
    .store-meta { font-size: 12.5px; color: rgba(255,255,255,.6); margin-top: 3px; }
    .badge-active {
        background: rgba(52,211,153,.15);
        border: 1px solid rgba(52,211,153,.35);
        color: #34d399;
        font-size: 11.5px; font-weight: 700;
        padding: 4px 12px; border-radius: 999px;
        flex-shrink: 0;
    }
    .badge-verified {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(52,211,153,.12);
        border: 1px solid rgba(52,211,153,.25);
        color: #34d399;
        font-size: 11px; font-weight: 600;
        padding: 2px 9px; border-radius: 999px;
        margin-top: 6px;
    }

    /* ── Stat pills ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 20px 0;
    }
    .stat-pill {
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        backdrop-filter: blur(4px);
    }
    .stat-pill .val {
        font-size: 20px; font-weight: 800;
        color: #fff;
        display: block;
    }
    .stat-pill .lbl {
        font-size: 10.5px; color: rgba(255,255,255,.45);
        margin-top: 3px; display: block;
        font-weight: 500;
    }
    .stat-pill.accent .val { color: #c4b5fd; }

    /* ── Info grid inside cards ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .info-cell {
        background: #f9f8f7;
        border-radius: 12px;
        padding: 12px 14px;
        border: 1px solid #f0eeeb;
    }
    .info-cell .lbl { font-size: 10.5px; color: #aaa; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .info-cell .val { font-size: 13.5px; font-weight: 700; color: #1a1a1a; margin-top: 4px; }
    .info-cell .sub { font-size: 11px; color: #999; margin-top: 1px; }
    .info-cell .mono { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; }

    /* ── Sertifikat banner ── */
    .cert-banner {
        background: linear-gradient(135deg, #f0fdf8, #dcfce7);
        border: 1px solid #bbf7d0;
        border-radius: 14px;
        padding: 16px 18px;
        margin-top: 12px;
    }
    .cert-banner .cert-title {
        font-size: 10px; font-weight: 800; color: #059669;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;
    }
    .cert-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
    .cert-item .lbl { font-size: 10.5px; color: #6b7280; }
    .cert-item .val { font-size: 12.5px; font-weight: 700; color: #065f46; margin-top: 2px; }
    .cert-item .val.expired { color: #dc2626; }

    /* ── Deskripsi ── */
    .desc-box {
        background: #f9f8f7;
        border-radius: 12px;
        padding: 14px 16px;
        margin-top: 12px;
        border: 1px solid #f0eeeb;
    }
    .desc-box .lbl { font-size: 10px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
    .desc-box p { font-size: 13px; color: #555; line-height: 1.7; }

    /* ── Section titles ── */
    .section-title {
        font-size: 14px; font-weight: 800; color: #1a1a1a;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px;
    }
    .section-title .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #7c3aed; flex-shrink: 0;
    }
    .section-title .count {
        margin-left: auto;
        background: #f3f0ff;
        color: #7c3aed;
        font-size: 11px; font-weight: 700;
        padding: 2px 9px; border-radius: 999px;
    }

    /* ── Produk list ── */
    .produk-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid #f3f3f3;
    }
    .produk-item:last-child { border-bottom: none; }
    .produk-item .name { font-size: 13px; font-weight: 600; color: #1a1a1a; }
    .produk-item .rating { font-size: 11.5px; color: #f59e0b; font-weight: 600; }
    .produk-item .price { font-size: 13px; font-weight: 800; color: #7c3aed; }

    /* ── Action card ── */
    .action-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #ebebeb;
        padding: 24px;
    }

    .form-label { font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; display: block; }

    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8;
        border-radius: 10px; padding: 10px 14px;
        font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }
    .input-field:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.1); background: #fff; }

    .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px; }

    .btn-primary {
        width: 100%; background: #7c3aed; color: #fff;
        font-size: 13px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 12px;
        cursor: pointer; transition: background .2s, transform .1s, box-shadow .2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        box-shadow: 0 4px 14px rgba(124,58,237,.3);
    }
    .btn-primary:hover { background: #6d28d9; box-shadow: 0 6px 20px rgba(124,58,237,.4); transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }

    .btn-secondary {
        width: 100%; background: #f0fdf8; color: #059669;
        font-size: 13px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #bbf7d0; border-radius: 11px; padding: 11px;
        cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        margin-top: 8px;
    }
    .btn-secondary:hover { background: #059669; color: #fff; border-color: #059669; }

    /* ── Divider ── */
    .divider { height: 1px; background: #f3f3f3; margin: 16px 0; }

    /* ── Kunjungan list ── */
    .kunjungan-item {
        display: flex; gap: 14px; align-items: flex-start;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .kunjungan-item:last-child { border-bottom: none; }

    .kunjungan-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
    }
    .kunjungan-date { font-size: 13.5px; font-weight: 700; color: #1a1a1a; }
    .kunjungan-officer { font-size: 11.5px; color: #aaa; margin-top: 2px; }
    .kunjungan-note { font-size: 12px; color: #666; margin-top: 4px; line-height: 1.5; }

    .badge-selesai {
        display: inline-block; margin-top: 6px;
        background: #f0fdf8; color: #059669;
        font-size: 10.5px; font-weight: 700;
        padding: 2px 9px; border-radius: 999px;
        border: 1px solid #bbf7d0;
    }
    .badge-dijadwalkan {
        display: inline-block; margin-top: 6px;
        background: #fffbeb; color: #d97706;
        font-size: 10.5px; font-weight: 700;
        padding: 2px 9px; border-radius: 999px;
        border: 1px solid #fde68a;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 36px 16px;
    }
    .empty-state .icon { font-size: 40px; margin-bottom: 10px; opacity: .45; }
    .empty-state p { font-size: 13px; color: #bbb; }

    /* ── Column stacking ── */
    .col { display: flex; flex-direction: column; gap: 20px; }
</style>
@endpush

@section('content')
<div class="monitoring-wrap">

    <a href="{{ route('dinas.monitoring.index') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Kembali ke Monitoring
    </a>

    <div class="main-grid">

        {{-- ===== KOLOM KIRI ===== --}}
        <div class="col">

            {{-- Hero Card --}}
            <div class="hero-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div class="store-avatar">{{ strtoupper(substr($toko->nama_toko, 0, 1)) }}</div>
                        <div>
                            <div class="store-name">{{ $toko->nama_toko }}</div>
                            <div class="store-meta">{{ $toko->kategori_friendly }} &middot; {{ $toko->kecamatan }}</div>
                            @if($toko->terverifikasi_dinas)
                            <span class="badge-verified">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                Terverifikasi Dinas
                            </span>
                            @endif
                        </div>
                    </div>
                    <span class="badge-active">● Aktif</span>
                </div>

                <div class="stats-row">
                    <div class="stat-pill accent">
                        <span class="val">★ {{ $toko->rating }}</span>
                        <span class="lbl">Rating</span>
                    </div>
                    <div class="stat-pill">
                        <span class="val">{{ $toko->total_pesanan }}</span>
                        <span class="lbl">Pesanan</span>
                    </div>
                    <div class="stat-pill">
                        <span class="val">{{ $toko->produksAktif->count() }}</span>
                        <span class="lbl">Produk</span>
                    </div>
                    <div class="stat-pill">
                        <span class="val">{{ $toko->total_ulasan }}</span>
                        <span class="lbl">Ulasan</span>
                    </div>
                </div>

                <div class="info-grid" style="margin-top:4px;">
                    <div class="info-cell" style="background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.1);">
                        <div class="lbl" style="color:rgba(255,255,255,.4);">Pemilik</div>
                        <div class="val" style="color:#fff;">{{ $toko->user->nama_lengkap ?? '-' }}</div>
                        <div class="sub" style="color:rgba(255,255,255,.4);">{{ $toko->user->email ?? '' }}</div>
                    </div>
                    <div class="info-cell" style="background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.1);">
                        <div class="lbl" style="color:rgba(255,255,255,.4);">Kontak</div>
                        <div class="val" style="color:#fff;">{{ $toko->no_hp ?? '-' }}</div>
                    </div>
                    <div class="info-cell" style="background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.1);">
                        <div class="lbl" style="color:rgba(255,255,255,.4);">NIB</div>
                        <div class="val mono" style="color:#c4b5fd;">{{ $toko->nib ?? '-' }}</div>
                    </div>
                    <div class="info-cell" style="background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.1);">
                        <div class="lbl" style="color:rgba(255,255,255,.4);">Bergabung</div>
                        <div class="val" style="color:#fff;">{{ $toko->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- Sertifikat + Deskripsi --}}
            @if($toko->no_sertifikat || $toko->deskripsi)
            <div class="card">
                @if($toko->no_sertifikat)
                <div class="cert-banner">
                    <div class="cert-title">🏅 Sertifikat Dinas</div>
                    <div class="cert-grid">
                        <div class="cert-item">
                            <div class="lbl">No. Sertifikat</div>
                            <div class="val" style="font-family:'JetBrains Mono',monospace;font-size:11.5px;">{{ $toko->no_sertifikat }}</div>
                        </div>
                        <div class="cert-item">
                            <div class="lbl">Tanggal Terbit</div>
                            <div class="val">{{ $toko->tanggal_sertifikat?->format('d M Y') }}</div>
                        </div>
                        <div class="cert-item">
                            @php $expired = $toko->kadaluarsa_sertifikat?->isPast(); @endphp
                            <div class="lbl">Berlaku Hingga</div>
                            <div class="val {{ $expired ? 'expired' : '' }}">
                                {{ $toko->kadaluarsa_sertifikat?->format('d M Y') }}
                                @if($expired) <br><span style="font-size:10px;font-weight:700;">⚠ Kadaluarsa</span> @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($toko->deskripsi)
                <div class="desc-box" @if($toko->no_sertifikat) style="margin-top:12px;" @endif>
                    <div class="lbl">Deskripsi Usaha</div>
                    <p>{{ $toko->deskripsi }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Produk Aktif --}}
            @if($toko->produksAktif->count())
            <div class="card">
                <div class="section-title">
                    <span class="dot"></span>
                    Produk Aktif
                    <span class="count">{{ $toko->produksAktif->count() }}</span>
                </div>
                @foreach($toko->produksAktif->take(5) as $produk)
                <div class="produk-item">
                    <div class="name">{{ $produk->nama }}</div>
                    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                        <span class="rating">★ {{ $produk->rating }}</span>
                        <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ===== KOLOM KANAN ===== --}}
        <div class="col">

            {{-- Aksi Dinas --}}
            <div class="card">
                <div class="section-title" style="margin-bottom:20px;">
                    <span class="dot" style="background:#059669;"></span>
                    Aksi Dinas
                </div>

                <form method="POST" action="{{ route('dinas.verifikasi.kunjungan', $toko->id) }}">
                    @csrf
                    <label class="form-label">📍 Jadwalkan Kunjungan Lapangan</label>

                    <div class="input-grid">
                        <input type="date" name="tanggal_kunjungan" required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="input-field">
                        <input type="time" name="waktu_kunjungan"
                            class="input-field">
                    </div>

                    <input type="text" name="catatan" placeholder="Tujuan kunjungan..."
                        class="input-field" style="margin-bottom:12px;">

                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Jadwalkan Kunjungan
                    </button>
                </form>

                <div class="divider"></div>

                {{-- Tombol Terbitkan Sertifikat --}}
                <a href="{{ route('dinas.verifikasi.sertifikat', $toko->id) }}"
                    style="width:100%;background:linear-gradient(135deg,#1a3a5c,#2d5a8e);color:#fff;font-size:13px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;border:none;border-radius:11px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;box-shadow:0 4px 14px rgba(26,58,92,.3);transition:opacity .2s,transform .1s;margin-bottom:8px;"
                    onmouseover="this.style.opacity='.85';this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.opacity='1';this.style.transform='translateY(0)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    {{ $toko->terverifikasi_dinas ? 'Perbarui Sertifikat' : 'Terbitkan Sertifikat' }}
                </a>

                @if($toko->terverifikasi_dinas)
                <a href="{{ route('dinas.verifikasi.sertifikat.pdf', $toko->id) }}" target="_blank"
                    style="width:100%;background:#f9f8f7;color:#555;font-size:13px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;border:1.5px solid #eee;border-radius:11px;padding:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;transition:background .2s,color .2s;"
                    onmouseover="this.style.background='#1a1a1a';this.style.color='#fff';this.style.borderColor='#1a1a1a'"
                    onmouseout="this.style.background='#f9f8f7';this.style.color='#555';this.style.borderColor='#eee'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PDF Sertifikat
                </a>
                @endif
            </div>

            {{-- Riwayat Kunjungan --}}
            <div class="card">
                <div class="section-title">
                    <span class="dot" style="background:#f59e0b;"></span>
                    Riwayat Kunjungan Lapangan
                </div>

                @if($toko->kunjunganLapangans->count())
                    @foreach($toko->kunjunganLapangans->sortByDesc('tanggal_kunjungan')->take(3) as $k)
                    <div class="kunjungan-item">
                        <div class="kunjungan-icon">📍</div>
                        <div style="flex:1;min-width:0;">
                            <div class="kunjungan-date">
                                {{ $k->tanggal_kunjungan->format('d M Y') }}
                                @if($k->waktu_kunjungan)
                                <span style="color:#aaa;font-weight:500;"> · {{ $k->waktu_kunjungan }}</span>
                                @endif
                            </div>
                            <div class="kunjungan-officer">Petugas: {{ $k->user->nama_lengkap ?? '-' }}</div>
                            @if($k->hasil_kunjungan)
                            <div class="kunjungan-note">{{ $k->hasil_kunjungan }}</div>
                            @endif
                            @if($k->status === 'selesai')
                            <span class="badge-selesai">✓ Selesai</span>
                            @else
                            <span class="badge-dijadwalkan">⏳ {{ ucfirst($k->status) }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="empty-state">
                    <div class="icon">🗺️</div>
                    <p>Belum ada riwayat kunjungan lapangan</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection