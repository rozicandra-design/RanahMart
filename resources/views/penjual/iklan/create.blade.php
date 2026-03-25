@extends('layouts.dashboard')
@section('title', 'Pasang Iklan Baru')
@section('page-title', 'Pasang Iklan Baru')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .iklan-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    .iklan-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px;
    }

    /* ══ Paket selector ══ */
    .paket-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .paket-label { cursor: pointer; display: block; }
    .paket-label input[type="radio"] { display: none; }

    .paket-card {
        background: #fff;
        border: 2px solid #e8e8e8;
        border-radius: 18px;
        padding: 18px 14px 16px;
        text-align: center;
        transition: border-color .2s, background .2s, box-shadow .2s, transform .15s;
        position: relative;
        overflow: hidden;
    }
    .paket-label input:checked + .paket-card {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 0 0 4px rgba(245,158,11,.12);
        transform: translateY(-2px);
    }
    .paket-card:hover { border-color: #fcd34d; transform: translateY(-1px); }

    .paket-badge {
        display: inline-block;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-size: 9.5px; font-weight: 800;
        padding: 3px 10px; border-radius: 999px;
        letter-spacing: .4px; text-transform: uppercase;
        margin-bottom: 10px;
    }
    .paket-spacer { height: 24px; }

    .paket-name  { font-size: 14px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .paket-price { font-size: 20px; font-weight: 800; color: #d97706; margin-bottom: 5px; }
    .paket-meta  { font-size: 11px; color: #aaa; font-weight: 500; }
    .paket-meta strong { color: #888; font-weight: 700; }

    /* ══ Main grid ══ */
    .page-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        align-items: start;
    }

    /* ══ Card ══ */
    .card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 26px 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    /* ══ Section title ══ */
    .section-title {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 18px;
    }
    .section-title .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .section-sep { height: 1px; background: #f3f3f3; margin: 22px 0; }

    /* ══ Fields ══ */
    .field-group { margin-bottom: 18px; }
    .field-label {
        display: block; font-size: 11px; font-weight: 800; color: #555;
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px;
    }
    .field-label .opt { font-weight: 500; color: #bbb; text-transform: none; letter-spacing: 0; }
    .field-required { color: #ef4444; }

    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 11px 14px; font-size: 13.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.12);
        background: #fff;
    }
    textarea.input-field { resize: none; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    /* ══ Banner upload ══ */
    .upload-zone {
        border: 2px dashed #e0e0e0; border-radius: 14px;
        overflow: hidden; position: relative;
        background: #fafafa; cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .upload-zone:hover { border-color: #f59e0b; background: #fffbeb; }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%; z-index: 2;
    }
    .banner-preview-img {
        width: 100%; height: 110px;
        object-fit: cover; display: none;
        border-radius: 12px 12px 0 0;
    }
    .upload-zone-body {
        padding: 16px; display: flex; align-items: center; gap: 12px;
    }
    .upload-zone-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: #fffbeb; border: 1.5px solid #fde68a;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .upload-zone-text .lbl { font-size: 12.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }
    .upload-zone-text .hint { font-size: 11px; color: #aaa; }
    .upload-zone-text .ok   { font-size: 11px; color: #d97706; font-weight: 600; }

    /* ══ Info note ══ */
    .info-note {
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: 12px; padding: 12px 14px;
        font-size: 12px; color: #92400e; line-height: 1.6;
        display: flex; gap: 8px; align-items: flex-start;
    }
    .info-note .ico { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

    /* ══ Submit ══ */
    .action-row { display: flex; gap: 10px; padding-top: 8px; }
    .btn-submit {
        flex: 1; background: #f59e0b; color: #fff;
        font-size: 13.5px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px; padding: 13px 20px;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(245,158,11,.35);
        transition: background .2s, transform .1s, box-shadow .2s;
        text-decoration: none;
    }
    .btn-submit:hover { background: #d97706; transform: translateY(-1px); box-shadow: 0 6px 22px rgba(245,158,11,.4); }
    .btn-submit:active { transform: translateY(0); }
    .btn-cancel {
        background: #f5f5f5; color: #666; font-size: 13.5px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #eee; border-radius: 11px; padding: 12px 20px;
        cursor: pointer; text-decoration: none;
        display: flex; align-items: center; transition: background .2s, color .2s;
    }
    .btn-cancel:hover { background: #eee; color: #333; }

    /* ══ Preview panel ══ */
    .preview-panel {
        position: sticky; top: 24px;
        display: flex; flex-direction: column; gap: 14px;
    }

    .preview-card {
        background: #fff; border-radius: 20px;
        border: 1px solid #ebebeb; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    .preview-header {
        padding: 13px 18px 11px;
        border-bottom: 1px solid #f3f3f3;
        display: flex; align-items: center; justify-content: space-between;
    }
    .preview-header .title {
        font-size: 10.5px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
    }
    .live-dot {
        width: 7px; height: 7px; border-radius: 50%; background: #22c55e;
        animation: blink 1.8s infinite;
    }
    @keyframes blink {
        0%,100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .35; transform: scale(.7); }
    }

    /* Mock banner */
    .mock-banner {
        width: 100%; height: 120px;
        background: linear-gradient(135deg, #1c1917, #292524);
        position: relative; overflow: hidden;
        display: flex; align-items: center; padding: 0 20px;
        gap: 14px;
    }
    .mock-banner-bg {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        opacity: .35;
        transition: opacity .3s;
    }
    .mock-banner-bg.has-img { opacity: .55; }
    .mock-banner-content { position: relative; z-index: 1; flex: 1; min-width: 0; }
    .mock-posisi {
        font-size: 8.5px; font-weight: 800; color: rgba(255,255,255,.45);
        text-transform: uppercase; letter-spacing: .8px; margin-bottom: 5px;
    }
    .mock-judul {
        font-size: 15px; font-weight: 800; color: #fff;
        line-height: 1.3; margin-bottom: 4px;
        word-break: break-word;
    }
    .mock-judul.empty { color: rgba(255,255,255,.25); font-style: italic; font-weight: 500; font-size: 13px; }
    .mock-sub {
        font-size: 10.5px; color: rgba(255,255,255,.55);
        line-height: 1.4; margin-bottom: 8px;
    }
    .mock-cta {
        display: inline-block;
        background: #f59e0b; color: #fff;
        font-size: 9.5px; font-weight: 800;
        padding: 4px 12px; border-radius: 999px;
        letter-spacing: .3px;
    }

    /* Paket summary */
    .paket-summary {
        padding: 16px 18px;
        border-top: 1px solid #f3f3f3;
    }
    .ps-row {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 12px; padding: 5px 0;
        border-bottom: 1px solid #f9f9f9;
    }
    .ps-row:last-child { border-bottom: none; }
    .ps-label { color: #aaa; font-weight: 500; }
    .ps-value { font-weight: 700; color: #1a1a1a; }
    .ps-value.amber { color: #d97706; font-size: 14px; }

    @media (max-width: 960px) {
        .page-grid { grid-template-columns: 1fr; }
        .preview-panel { position: static; }
    }
</style>
@endpush

@section('content')
<div class="iklan-wrap">

    {{-- ══ PAKET SELECTOR ══ --}}
    @php
    $pakets = [
        'starter'  => ['nama' => 'Starter',  'harga' => 50000,  'durasi' => '3 hari',  'desc' => 'Banner Kategori'],
        'mingguan' => ['nama' => 'Mingguan', 'harga' => 150000, 'durasi' => '7 hari',  'desc' => 'Banner Utama'],
        'bulanan'  => ['nama' => 'Bulanan',  'harga' => 450000, 'durasi' => '30 hari', 'desc' => 'Banner Premium'],
    ];
    @endphp

    <div class="paket-grid">
        @foreach($pakets as $slug => $p)
        <label class="paket-label">
            <input type="radio" name="paket_pilihan" value="{{ $slug }}"
                {{ $slug === 'mingguan' ? 'checked' : '' }}>
            <div class="paket-card">
                @if($slug === 'mingguan')
                    <div class="paket-badge">⭐ Populer</div>
                @else
                    <div class="paket-spacer"></div>
                @endif
                <div class="paket-name">{{ $p['nama'] }}</div>
                <div class="paket-price">Rp {{ number_format($p['harga'], 0, ',', '.') }}</div>
                <div class="paket-meta"><strong>{{ $p['durasi'] }}</strong> · {{ $p['desc'] }}</div>
            </div>
        </label>
        @endforeach
    </div>

    <div class="page-grid">

        {{-- ══ KOLOM KIRI: FORM ══ --}}
        <div class="card">
            <form method="POST" action="{{ route('penjual.iklan.store') }}" enctype="multipart/form-data" id="iklan-form">
                @csrf
                <input type="hidden" name="paket" id="paket-hidden" value="mingguan">

                <div class="section-title">
                    <span class="dot" style="background:#f59e0b;"></span> Detail Iklan
                </div>

                <div class="field-group">
                    <label class="field-label">Posisi Iklan <span class="field-required">*</span></label>
                    <select name="posisi" required class="input-field" id="f-posisi">
                        <option value="Banner Hero (Halaman Utama)">Banner Hero — Halaman Utama</option>
                        <option value="Banner Kategori Makanan">Banner Kategori Makanan</option>
                        <option value="Banner Kategori Fashion">Banner Kategori Fashion</option>
                        <option value="Banner Kategori Kerajinan">Banner Kategori Kerajinan</option>
                        <option value="Banner Samping Kanan">Banner Samping Kanan</option>
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label">Judul Iklan <span class="field-required">*</span></label>
                    <input type="text" name="judul" id="f-judul" value="{{ old('judul') }}" required
                        class="input-field" placeholder="Contoh: Rendang Daging Sapi Premium Padang">
                </div>

                <div class="field-group">
                    <label class="field-label">Sub Judul / Tagline <span class="opt">(opsional)</span></label>
                    <input type="text" name="sub_judul" id="f-sub"  value="{{ old('sub_judul') }}"
                        class="input-field" placeholder="Masak 6 jam dengan rempah asli Minang!">
                </div>

                <div class="grid-2 field-group">
                    <div>
                        <label class="field-label">Teks Tombol (CTA)</label>
                        <select name="teks_cta" id="f-cta" class="input-field">
                            @foreach(['Beli Sekarang','Lihat Produk','Order Sekarang','Kunjungi Toko','Dapatkan Promo'] as $cta)
                            <option value="{{ $cta }}" {{ old('teks_cta') == $cta ? 'selected' : '' }}>{{ $cta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Tanggal Mulai <span class="field-required">*</span></label>
                        <input type="date" name="tanggal_mulai" id="f-tgl"
                            value="{{ old('tanggal_mulai', date('Y-m-d', strtotime('+1 day'))) }}"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                            class="input-field">
                    </div>
                </div>

                <div class="section-sep"></div>

                <div class="section-title">
                    <span class="dot" style="background:#7c3aed;"></span> Materi Iklan
                </div>

                <div class="field-group">
                    <label class="field-label">Banner Iklan <span class="opt">(opsional · maks. 5MB)</span></label>
                    <div class="upload-zone" id="banner-zone">
                        <input type="file" name="banner" accept="image/*" id="banner-input">
                        <img class="banner-preview-img" id="banner-preview" alt="">
                        <div class="upload-zone-body">
                            <div class="upload-zone-icon">🖼️</div>
                            <div class="upload-zone-text">
                                <div class="lbl" id="banner-lbl">Klik untuk unggah banner</div>
                                <div class="hint" id="banner-hint">1200×400px · JPG, PNG</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-group" style="margin-bottom:0;">
                    <label class="field-label">Catatan untuk Admin <span class="opt">(opsional)</span></label>
                    <textarea name="catatan_pengaju" rows="2" class="input-field"
                        placeholder="Informasi tambahan untuk admin...">{{ old('catatan_pengaju') }}</textarea>
                </div>

                <div class="section-sep"></div>

                <div class="info-note">
                    <span class="ico">ℹ️</span>
                    Pengajuan iklan akan direview admin dalam <strong>1×24 jam</strong>.
                    Iklan tayang setelah disetujui. Pembayaran dilakukan setelah konfirmasi.
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-submit">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Kirim Pengajuan Iklan
                    </button>
                    <a href="{{ route('penjual.iklan.index') }}" class="btn-cancel">Batal</a>
                </div>

            </form>
        </div>

        {{-- ══ KOLOM KANAN: PREVIEW ══ --}}
        <div class="preview-panel">

            <div class="preview-card">
                <div class="preview-header">
                    <span class="title">Preview Iklan</span>
                    <span class="live-dot"></span>
                </div>

                {{-- Mock banner --}}
                <div class="mock-banner">
                    <div class="mock-banner-bg" id="mock-bg"></div>
                    <div class="mock-banner-content">
                        <div class="mock-posisi" id="mock-posisi">Banner Hero — Halaman Utama</div>
                        <div class="mock-judul empty" id="mock-judul">Judul iklan kamu...</div>
                        <div class="mock-sub" id="mock-sub"></div>
                        <span class="mock-cta" id="mock-cta">Beli Sekarang</span>
                    </div>
                </div>

                {{-- Paket summary --}}
                <div class="paket-summary">
                    <div class="ps-row">
                        <span class="ps-label">Paket</span>
                        <span class="ps-value" id="ps-nama">Mingguan</span>
                    </div>
                    <div class="ps-row">
                        <span class="ps-label">Durasi</span>
                        <span class="ps-value" id="ps-durasi">7 hari</span>
                    </div>
                    <div class="ps-row">
                        <span class="ps-label">Tanggal Mulai</span>
                        <span class="ps-value" id="ps-tgl">—</span>
                    </div>
                    <div class="ps-row">
                        <span class="ps-label">Total</span>
                        <span class="ps-value amber" id="ps-harga">Rp 150.000</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const pakets = {
        starter:  { nama: 'Starter',  harga: 50000,  durasi: '3 hari' },
        mingguan: { nama: 'Mingguan', harga: 150000, durasi: '7 hari' },
        bulanan:  { nama: 'Bulanan',  harga: 450000, durasi: '30 hari' },
    };

    /* ── Refs ── */
    const fJudul   = document.getElementById('f-judul');
    const fSub     = document.getElementById('f-sub');
    const fCta     = document.getElementById('f-cta');
    const fPosisi  = document.getElementById('f-posisi');
    const fTgl     = document.getElementById('f-tgl');

    const mockJudul  = document.getElementById('mock-judul');
    const mockSub    = document.getElementById('mock-sub');
    const mockCta    = document.getElementById('mock-cta');
    const mockPosisi = document.getElementById('mock-posisi');
    const mockBg     = document.getElementById('mock-bg');

    const psNama  = document.getElementById('ps-nama');
    const psDurasi = document.getElementById('ps-durasi');
    const psTgl   = document.getElementById('ps-tgl');
    const psHarga = document.getElementById('ps-harga');

    const paketHidden = document.getElementById('paket-hidden');

    /* ── Live text update ── */
    function updatePreview() {
        const judul = fJudul.value.trim();
        mockJudul.textContent = judul || 'Judul iklan kamu...';
        mockJudul.classList.toggle('empty', !judul);

        mockSub.textContent    = fSub.value.trim();
        mockCta.textContent    = fCta.value;
        mockPosisi.textContent = fPosisi.options[fPosisi.selectedIndex]?.text || '';

        // Tanggal
        if (fTgl.value) {
            const d = new Date(fTgl.value);
            psTgl.textContent = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } else {
            psTgl.textContent = '—';
        }
    }

    [fJudul, fSub, fCta, fPosisi, fTgl].forEach(el => {
        if (el) { el.addEventListener('input', updatePreview); el.addEventListener('change', updatePreview); }
    });

    /* ── Paket switch ── */
    document.querySelectorAll('input[name="paket_pilihan"]').forEach(radio => {
        radio.addEventListener('change', () => {
            paketHidden.value = radio.value;
            const p = pakets[radio.value];
            if (!p) return;
            psNama.textContent  = p.nama;
            psDurasi.textContent = p.durasi;
            psHarga.textContent = 'Rp ' + p.harga.toLocaleString('id-ID');
        });
    });

    /* ── Banner upload ── */
    document.getElementById('banner-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) { alert('File melebihi 5MB'); return; }
        const reader = new FileReader();
        reader.onload = ev => {
            // Form upload zone
            const prev = document.getElementById('banner-preview');
            prev.src = ev.target.result;
            prev.style.display = 'block';
            document.getElementById('banner-lbl').textContent  = file.name.length > 30 ? file.name.slice(0,28)+'…' : file.name;
            const hint = document.getElementById('banner-hint');
            hint.className = 'ok';
            hint.textContent = 'Siap diunggah · klik untuk ganti';

            // Mock banner bg
            mockBg.style.backgroundImage = `url(${ev.target.result})`;
            mockBg.classList.add('has-img');
        };
        reader.readAsDataURL(file);
    });

    updatePreview();
})();
</script>
@endpush