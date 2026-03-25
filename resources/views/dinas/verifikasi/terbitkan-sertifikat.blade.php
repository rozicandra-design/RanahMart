@extends('layouts.dashboard')
@section('title', 'Terbitkan Sertifikat')
@section('page-title', 'Terbitkan Sertifikat')
@section('sidebar') @include('components.sidebar-dinas') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .sert-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .sert-wrap { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    /* ── Back link ── */
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 600; color: #888;
        text-decoration: none; margin-bottom: 22px;
        transition: color .2s;
    }
    .back-link:hover { color: #1a1a1a; }
    .back-link:hover svg { transform: translateX(-3px); }
    .back-link svg { transition: transform .2s; }

    /* ── Page grid ── */
    .page-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* ── Left panel ── */
    .left-col { display: flex; flex-direction: column; gap: 16px; }

    /* ── Card ── */
    .card {
        background: #fff; border-radius: 20px;
        border: 1px solid #ebebeb; padding: 24px 26px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    .section-title {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 18px;
    }
    .section-title .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .section-sep { height: 1px; background: #f3f3f3; margin: 18px 0; }

    /* ── Fields ── */
    .field-group { margin-bottom: 14px; }
    .field-label {
        display: block; font-size: 11px; font-weight: 800; color: #555;
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px;
    }
    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 10px 14px; font-size: 13px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.1); background: #fff; }
    .input-mono { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; letter-spacing: .5px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* ── Info toko ── */
    .info-row {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 12px; padding: 9px 0; border-bottom: 1px solid #f5f5f5;
        font-size: 12.5px;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row:first-child { padding-top: 0; }
    .info-row .lbl { color: #aaa; font-weight: 500; flex-shrink: 0; }
    .info-row .val { font-weight: 700; color: #1a1a1a; text-align: right; }
    .info-row .val.mono { font-family: 'JetBrains Mono', monospace; font-size: 11.5px; color: #7c3aed; }

    /* ── Action buttons ── */
    .action-row { display: flex; gap: 10px; }
    .btn-submit {
        flex: 1; background: #7c3aed; color: #fff;
        font-size: 13.5px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 13px 20px;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(124,58,237,.3);
        transition: background .2s, transform .1s, box-shadow .2s;
    }
    .btn-submit:hover { background: #6d28d9; transform: translateY(-1px); box-shadow: 0 6px 22px rgba(124,58,237,.35); }
    .btn-submit:active { transform: translateY(0); }

    .btn-pdf {
        width: 100%; background: #1a1a1a; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 12px;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: background .2s, transform .1s;
        margin-top: 12px;
    }
    .btn-pdf:hover { background: #333; transform: translateY(-1px); }

    /* ── Preview panel ── */
    .preview-panel {
        position: sticky; top: 24px;
    }
    .preview-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .preview-label {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
    }
    .preview-label .dot { width: 6px; height: 6px; border-radius: 50%; background: #7c3aed; }
    .live-dot {
        width: 7px; height: 7px; border-radius: 50%; background: #22c55e;
        animation: blink 1.8s infinite;
    }
    @keyframes blink {
        0%,100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .35; transform: scale(.7); }
    }

    /* Certificate preview wrapper */
    .cert-wrapper {
        border-radius: 14px; overflow: hidden;
        border: 1px solid #e8e4dc;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
    }
    .cert-scale-outer {
        width: 100%; overflow: hidden; position: relative;
    }
    .cert-scale-inner {
        width: 860px;
        transform-origin: top left;
        /* JS sets transform scale */
    }

    @media (max-width: 1100px) {
        .page-grid { grid-template-columns: 1fr; }
        .preview-panel { position: static; }
    }

    @media print {
        /* Paksa warna background ikut tercetak */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Sembunyikan form & elemen non-sertifikat */
        .back-link,
        .left-col,
        .preview-header {
            display: none !important;
        }

        .sert-wrap {
            background: #fff !important;
            padding: 0 !important;
            min-height: auto !important;
        }
        .page-grid {
            display: block !important;
        }
        .preview-panel {
            position: static !important;
        }
        .cert-wrapper {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .cert-scale-outer {
            overflow: visible !important;
            width: 100% !important;
        }
        #cert-scale-inner {
            transform: none !important;
            width: 100% !important;
        }
        #cert-scale-inner > div {
            width: 100% !important;
        }

        @page {
            size: A4 landscape;
            margin: 6mm;
        }
    }
</style>
@endpush

@section('content')
<div class="sert-wrap">

    <a href="{{ route('dinas.verifikasi.show', $toko->id) }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Kembali ke Detail Monitoring
    </a>

    <div class="page-grid">

        {{-- ===== KIRI: FORM ===== --}}
        <div class="left-col">

            {{-- Form Data Sertifikat --}}
            <div class="card">
                <div class="section-title"><span class="dot" style="background:#7c3aed;"></span> Data Sertifikat</div>

                <form method="POST" action="{{ route('dinas.verifikasi.sertifikat.simpan', $toko->id) }}" id="form-sertifikat">
                    @csrf

                    <div class="field-group">
                        <label class="field-label">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikat" id="no_sertifikat"
                            value="{{ old('no_sertifikat', $toko->no_sertifikat) }}"
                            class="input-field input-mono" placeholder="DKUMKM/2024/001"
                            oninput="updatePreview()">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Nama Kepala Dinas</label>
                        <input type="text" name="nama_kepala_dinas" id="nama_kepala_dinas"
                            value="{{ old('nama_kepala_dinas', $toko->nama_kepala_dinas ?? 'Ir. H. Ahmad Fauzi, M.Si') }}"
                            class="input-field" oninput="updatePreview()">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Jabatan</label>
                        <input type="text" name="jabatan_kepala_dinas" id="jabatan_kepala_dinas"
                            value="{{ old('jabatan_kepala_dinas', $toko->jabatan_kepala_dinas ?? 'Kepala Dinas Koperasi & UMKM Kota Padang') }}"
                            class="input-field" oninput="updatePreview()">
                    </div>

                    <div class="grid-2">
                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label">Tanggal Terbit</label>
                            <input type="date" name="tanggal_sertifikat" id="tanggal_sertifikat"
                                value="{{ old('tanggal_sertifikat', now()->format('Y-m-d')) }}"
                                class="input-field"
                                oninput="updatePreview(); updateMinKadaluarsa();">
                        </div>
                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label">Berlaku Hingga</label>
                            <input type="date" name="kadaluarsa_sertifikat" id="kadaluarsa_sertifikat"
                                value="{{ old('kadaluarsa_sertifikat', now()->addYear()->format('Y-m-d')) }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}"
                                class="input-field" oninput="updatePreview()">
                        </div>
                    </div>

                    {{-- Error validasi --}}
                    @error('kadaluarsa_sertifikat')
                    <div style="margin-top:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:9px 13px;font-size:12px;color:#dc2626;font-weight:600;">
                        ⚠ {{ $message }}
                    </div>
                    @enderror
                    @error('tanggal_sertifikat')
                    <div style="margin-top:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:9px 13px;font-size:12px;color:#dc2626;font-weight:600;">
                        ⚠ {{ $message }}
                    </div>
                    @enderror
                </form>
            </div>

            {{-- Info Toko --}}
            <div class="card">
                <div class="section-title"><span class="dot" style="background:#0d9488;"></span> Info Toko</div>

                <div class="info-row">
                    <span class="lbl">Nama Toko</span>
                    <span class="val">{{ $toko->nama_toko }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Pemilik</span>
                    <span class="val">{{ $toko->user->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">NIB</span>
                    <span class="val mono">{{ $toko->nib ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Kategori</span>
                    <span class="val">{{ $toko->kategori_friendly }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Kecamatan</span>
                    <span class="val">{{ $toko->kecamatan }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="action-row">
                <button type="submit" form="form-sertifikat" class="btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Terbitkan & Kirim ke Penjual
                </button>
            </div>

            @if($toko->terverifikasi_dinas)
            <a href="{{ route('dinas.verifikasi.sertifikat.pdf', $toko->id) }}" target="_blank" class="btn-pdf">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                Download PDF Sertifikat
            </a>
            @endif

        </div>

        {{-- ===== KANAN: PREVIEW ===== --}}
        <div class="preview-panel">
            <div class="preview-header">
                <div class="preview-label"><span class="dot"></span> Preview Sertifikat</div>
                <span class="live-dot" title="Live preview"></span>
            </div>

            <div class="cert-wrapper">
                <div class="cert-scale-outer" id="cert-scale-outer">
                    <div class="cert-scale-inner" id="cert-scale-inner">

                        {{-- ══ CERTIFICATE ══ --}}
                        <div style="width:860px;background:#fff;border:10px solid #1a3a5c;font-family:Georgia,serif;position:relative;overflow:hidden;">
                        <div style="border:2px solid #c9a84c;margin:8px;position:relative;overflow:hidden;">

                            {{-- Watermark --}}
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-25deg);opacity:.04;pointer-events:none;text-align:center;z-index:0;user-select:none;">
                                <div style="font-size:86px;font-weight:700;color:#1a3a5c;line-height:1;">DINAS</div>
                                <div style="font-size:30px;font-weight:700;color:#1a3a5c;">KOPERASI & UMKM</div>
                                <div style="font-size:22px;color:#1a3a5c;">KOTA PADANG</div>
                            </div>

                            {{-- Header --}}
                            <div style="background:#1a3a5c;padding:14px 28px;display:flex;align-items:center;gap:16px;position:relative;z-index:1;">
                                <div style="width:56px;height:56px;background:#c9a84c;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:24px;color:#1a3a5c;flex-shrink:0;">R</div>
                                <div style="flex:1;text-align:center;">
                                    <div style="color:#c9a84c;font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;font-family:'Plus Jakarta Sans',sans-serif;">Pemerintah Kota Padang</div>
                                    <div style="color:#fff;font-size:17px;font-weight:700;margin-top:3px;">Dinas Koperasi &amp; Usaha Mikro Kecil Menengah</div>
                                    <div style="color:#e8dfc8;font-size:11px;margin-top:2px;font-family:'Plus Jakarta Sans',sans-serif;">Jl. Khatib Sulaiman No. 1, Padang — Sumatera Barat</div>
                                </div>
                                <div style="text-align:right;flex-shrink:0;">
                                    <div style="font-size:9px;color:#e8dfc8;letter-spacing:1px;text-transform:uppercase;font-family:'Plus Jakarta Sans',sans-serif;">No. Sertifikat</div>
                                    <div id="prev-nosert" style="font-size:12px;color:#c9a84c;font-weight:600;letter-spacing:1px;margin-top:3px;font-family:'Plus Jakarta Sans',sans-serif;">{{ $toko->no_sertifikat ?? '—' }}</div>
                                </div>
                            </div>

                            <div style="height:3px;background:linear-gradient(90deg,#c9a84c,#e8d48a,#c9a84c);position:relative;z-index:1;"></div>

                            {{-- Body --}}
                            <div style="padding:22px 52px 14px;text-align:center;position:relative;z-index:1;">
                                <div style="font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#999;margin-bottom:3px;font-family:'Plus Jakarta Sans',sans-serif;">Sertifikat Resmi</div>
                                <div style="font-size:28px;font-weight:700;color:#1a3a5c;line-height:1.1;margin-bottom:3px;">Sertifikat Verifikasi UMKM</div>
                                <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#c9a84c;margin-bottom:14px;font-family:'Plus Jakarta Sans',sans-serif;">Platform RanahMart &nbsp;·&nbsp; Kota Padang</div>

                                {{-- Ornament --}}
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="flex:1;height:1px;background:linear-gradient(90deg,transparent,#c9a84c);"></div>
                                    <div style="width:8px;height:8px;background:#c9a84c;transform:rotate(45deg);flex-shrink:0;"></div>
                                    <div style="flex:1;height:1px;background:linear-gradient(90deg,#c9a84c,transparent);"></div>
                                </div>

                                <div style="font-size:11px;color:#777;margin-bottom:4px;font-family:'Plus Jakarta Sans',sans-serif;">Diberikan dengan hormat kepada</div>
                                <div style="font-size:28px;font-weight:700;color:#1a3a5c;border-bottom:2px solid #c9a84c;padding-bottom:4px;margin-bottom:4px;display:inline-block;">{{ $toko->nama_toko }}</div>
                                <div style="font-size:11px;color:#666;margin-bottom:14px;font-family:'Plus Jakarta Sans',sans-serif;">
                                    Pemilik: <strong>{{ $toko->user->nama_lengkap ?? '-' }}</strong> &nbsp;|&nbsp; NIB: <strong>{{ $toko->nib ?? '-' }}</strong>
                                </div>

                                {{-- Detail grid --}}
                                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:14px;">
                                    <div style="background:#f8f5ef;border:1px solid #e8dfc8;border-radius:6px;padding:9px 12px;text-align:left;">
                                        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#999;margin-bottom:3px;font-family:'Plus Jakarta Sans',sans-serif;">Kategori Usaha</div>
                                        <div style="font-size:11px;font-weight:700;color:#1a3a5c;font-family:'Plus Jakarta Sans',sans-serif;">{{ $toko->kategori_friendly }}</div>
                                    </div>
                                    <div style="background:#f8f5ef;border:1px solid #e8dfc8;border-radius:6px;padding:9px 12px;text-align:left;">
                                        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#999;margin-bottom:3px;font-family:'Plus Jakarta Sans',sans-serif;">Kecamatan</div>
                                        <div style="font-size:11px;font-weight:700;color:#1a3a5c;font-family:'Plus Jakarta Sans',sans-serif;">{{ $toko->kecamatan }}</div>
                                    </div>
                                    <div style="background:#f8f5ef;border:1px solid #e8dfc8;border-radius:6px;padding:9px 12px;text-align:left;">
                                        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#999;margin-bottom:3px;font-family:'Plus Jakarta Sans',sans-serif;">Tanggal Terbit</div>
                                        <div id="prev-tgl" style="font-size:11px;font-weight:700;color:#1a3a5c;font-family:'Plus Jakarta Sans',sans-serif;">{{ now()->format('d M Y') }}</div>
                                    </div>
                                    <div style="background:#f8f5ef;border:1px solid #e8dfc8;border-radius:6px;padding:9px 12px;text-align:left;">
                                        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#999;margin-bottom:3px;font-family:'Plus Jakarta Sans',sans-serif;">Berlaku Hingga</div>
                                        <div id="prev-exp" style="font-size:11px;font-weight:700;color:#1a3a5c;font-family:'Plus Jakarta Sans',sans-serif;">{{ now()->addYear()->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:16px;align-items:end;padding:0 52px 18px;position:relative;z-index:1;">

                                {{-- TTD Kiri --}}
                                <div style="display:flex;flex-direction:column;align-items:center;">
                                    <div style="height:28px;"></div>
                                    <div style="width:140px;border-top:1.5px solid #1a3a5c;margin-bottom:5px;"></div>
                                    <div id="prev-nama" style="font-size:12px;font-weight:700;color:#1a3a5c;text-align:center;font-family:'Plus Jakarta Sans',sans-serif;">{{ $toko->nama_kepala_dinas ?? 'Ir. H. Ahmad Fauzi, M.Si' }}</div>
                                    <div id="prev-jabatan" style="font-size:9px;color:#888;text-align:center;line-height:1.5;font-family:'Plus Jakarta Sans',sans-serif;">{{ $toko->jabatan_kepala_dinas ?? 'Kepala Dinas Koperasi & UMKM Kota Padang' }}</div>
                                </div>

                                {{-- QR + Stempel --}}
                                <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                                    {{-- QR mock --}}
                                    <div style="width:58px;height:58px;border:2px solid #1a3a5c;border-radius:6px;display:grid;grid-template-columns:repeat(5,1fr);padding:4px;gap:1.5px;background:#fff;">
                                        @foreach([1,0,1,0,1, 0,1,0,1,0, 1,1,0,1,1, 0,1,1,0,1, 1,0,1,1,0] as $cell)
                                        <div style="background:{{ $cell ? '#1a3a5c' : '#fff' }};border-radius:1px;"></div>
                                        @endforeach
                                    </div>
                                    <div style="font-size:8px;color:#aaa;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:.5px;">Scan verifikasi</div>
                                </div>

                                {{-- Stempel Kanan --}}
                                <div style="display:flex;flex-direction:column;align-items:center;">
                                    <div style="width:64px;height:64px;border:2.5px solid #1a3a5c;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:5px;">
                                        <div style="width:52px;height:52px;border:1.5px solid #c9a84c;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a3a5c" stroke-width="1.5"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                                            <div style="font-size:6.5px;font-weight:700;color:#1a3a5c;text-align:center;line-height:1.3;letter-spacing:.3px;font-family:'Plus Jakarta Sans',sans-serif;">TERVERIFIKASI<br>RESMI</div>
                                        </div>
                                    </div>
                                    <div style="font-size:9px;color:#aaa;font-family:'Plus Jakarta Sans',sans-serif;">Stempel Dinas</div>
                                </div>

                            </div>

                            {{-- Bottom gold bar --}}
                            <div style="height:3px;background:linear-gradient(90deg,#c9a84c,#e8d48a,#c9a84c);"></div>

                        </div>
                        </div>

                    </div>{{-- cert-scale-inner --}}
                </div>{{-- cert-scale-outer --}}
            </div>{{-- cert-wrapper --}}
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
/* ── Scale certificate to fit container ── */
function scaleCert() {
    const outer = document.getElementById('cert-scale-outer');
    const inner = document.getElementById('cert-scale-inner');
    if (!outer || !inner) return;
    const scale = outer.offsetWidth / 860;
    inner.style.transform = `scale(${scale})`;
    outer.style.height = Math.round(inner.offsetHeight * scale) + 'px';
}
window.addEventListener('load', scaleCert);
window.addEventListener('resize', scaleCert);

/* ── Live preview ── */
function formatDate(val) {
    if (!val) return '—';
    const d = new Date(val);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
function updatePreview() {
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '—'; };
    set('prev-nosert',  document.getElementById('no_sertifikat')?.value);
    set('prev-nama',    document.getElementById('nama_kepala_dinas')?.value);
    set('prev-jabatan', document.getElementById('jabatan_kepala_dinas')?.value);
    set('prev-tgl',     formatDate(document.getElementById('tanggal_sertifikat')?.value));
    set('prev-exp',     formatDate(document.getElementById('kadaluarsa_sertifikat')?.value));
    scaleCert();
}

/* ── Update min date kadaluarsa saat tanggal terbit berubah ── */
function updateMinKadaluarsa() {
    const tgl = document.getElementById('tanggal_sertifikat');
    const exp = document.getElementById('kadaluarsa_sertifikat');
    if (!tgl || !exp || !tgl.value) return;

    // min kadaluarsa = tanggal terbit + 1 hari
    const minDate = new Date(tgl.value);
    minDate.setDate(minDate.getDate() + 1);
    const minStr = minDate.toISOString().split('T')[0];
    exp.min = minStr;

    // Jika kadaluarsa sudah dipilih tapi lebih kecil dari min, reset ke +1 tahun dari terbit
    if (exp.value && exp.value <= tgl.value) {
        const resetDate = new Date(tgl.value);
        resetDate.setFullYear(resetDate.getFullYear() + 1);
        exp.value = resetDate.toISOString().split('T')[0];
        updatePreview();
    }
}

// Init saat load
document.addEventListener('DOMContentLoaded', () => updateMinKadaluarsa());
</script>
@endpush