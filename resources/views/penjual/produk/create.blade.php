php

@extends('layouts.dashboard')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')
@section('notif-route', route('penjual.notifikasi'))
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .produk-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    .produk-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px;
    }

    /* ── Notice banner ── */
    .notice-banner {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 11px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #92400e;
        font-weight: 500;
    }

    /* ── Two-column layout ── */
    .page-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        align-items: start;
    }

    /* ── Form card ── */
    .form-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    /* ── Preview panel ── */
    .preview-panel {
        position: sticky;
        top: 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .preview-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    .preview-header {
        padding: 14px 18px 10px;
        border-bottom: 1px solid #f3f3f3;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .preview-header .title {
        font-size: 11px;
        font-weight: 800;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: .6px;
    }
    .preview-header .live-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #22c55e;
        animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .5; transform: scale(.75); }
    }

    /* ── Main foto preview ── */
    .main-foto-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f9f8f7;
        overflow: hidden;
    }
    .main-foto-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: opacity .25s;
    }
    .main-foto-empty {
        width: 100%; height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #ccc;
    }
    .main-foto-empty .ico { font-size: 36px; opacity: .4; }
    .main-foto-empty p { font-size: 12px; font-weight: 600; color: #ccc; }

    .main-foto-badge {
        position: absolute;
        top: 10px; left: 10px;
        background: rgba(13,148,136,.85);
        color: #fff;
        font-size: 9.5px; font-weight: 800;
        padding: 3px 9px; border-radius: 999px;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    /* ── Thumbnail strip (drag-and-drop) ── */
    .thumb-strip {
        padding: 12px 14px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        min-height: 56px;
    }

    .thumb-item {
        position: relative;
        width: 56px; height: 56px;
        border-radius: 9px;
        overflow: hidden;
        border: 2px solid #e8e8e8;
        cursor: grab;
        transition: border-color .15s, transform .15s, box-shadow .15s;
        flex-shrink: 0;
    }
    .thumb-item:active { cursor: grabbing; }
    .thumb-item.is-active { border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.2); }
    .thumb-item.dragging { opacity: .35; transform: scale(.9); }
    .thumb-item.drag-over-target { border-color: #7c3aed; transform: scale(1.08); }

    .thumb-item img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        pointer-events: none;
    }
    .thumb-item .rm {
        position: absolute; top: 2px; right: 2px;
        width: 16px; height: 16px;
        background: rgba(0,0,0,.55);
        border: none; border-radius: 50%;
        color: #fff; font-size: 10px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        line-height: 1;
        transition: background .15s;
        z-index: 2;
    }
    .thumb-item .rm:hover { background: #ef4444; }

    .thumb-placeholder {
        width: 56px; height: 56px;
        border-radius: 9px;
        border: 2px dashed #e0e0e0;
        background: #fafafa;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #ddd;
        cursor: pointer;
        transition: border-color .15s, color .15s;
        flex-shrink: 0;
    }
    .thumb-placeholder:hover { border-color: #0d9488; color: #0d9488; }

    .strip-hint {
        padding: 0 14px 10px;
        font-size: 10.5px;
        color: #bbb;
        font-weight: 500;
    }

    /* ── Live info card ── */
    .live-info {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 18px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .live-name {
        font-size: 14px;
        font-weight: 800;
        color: #1a1a1a;
        min-height: 20px;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .live-name.empty { color: #ccc; font-weight: 500; font-style: italic; }

    .live-price-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .live-price {
        font-size: 18px;
        font-weight: 800;
        color: #0d9488;
        min-height: 24px;
    }
    .live-price.empty { font-size: 13px; color: #ddd; font-weight: 500; font-style: italic; }
    .live-price-coret {
        font-size: 12px;
        color: #bbb;
        text-decoration: line-through;
    }
    .live-discount {
        font-size: 10.5px;
        font-weight: 700;
        background: #fef2f2;
        color: #ef4444;
        padding: 2px 7px;
        border-radius: 999px;
    }

    .live-tags {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .live-tag {
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        background: #f3f0ff;
        color: #7c3aed;
    }
    .live-tag.teal { background: #f0fdfa; color: #0d9488; }
    .live-tag.gray { background: #f5f5f5; color: #888; }

    /* ── Field groups ── */
    .field-group { margin-bottom: 20px; }
    .field-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #555;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 7px;
    }
    .field-label .opt { font-weight: 500; color: #bbb; text-transform: none; letter-spacing: 0; }
    .field-required { color: #ef4444; }

    .input-field {
        width: 100%;
        border: 1.5px solid #e8e8e8;
        border-radius: 11px;
        padding: 11px 14px;
        font-size: 13.5px;
        color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa;
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13,148,136,.1);
        background: #fff;
    }
    textarea.input-field { resize: none; }

    .input-prefix-wrap { position: relative; }
    .input-prefix {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%);
        font-size: 13px; font-weight: 700; color: #aaa;
        pointer-events: none;
    }
    .input-prefix-wrap .input-field { padding-left: 38px; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
    .section-sep { height: 1px; background: #f3f3f3; margin: 24px 0; }

    /* ── Upload zone ── */
    .upload-zone {
        border: 2px dashed #e0e0e0;
        border-radius: 14px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
        background: #fafafa;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #0d9488;
        background: #f0fdfa;
    }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .upload-icon {
        width: 44px; height: 44px;
        background: #f0fdfa; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin: 0 auto 10px;
        border: 1.5px solid #ccfbf1;
    }
    .upload-title { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .upload-title span { color: #0d9488; }
    .upload-hint { font-size: 11.5px; color: #aaa; }
    .foto-count { font-size: 11.5px; color: #aaa; margin-top: 8px; }
    .foto-count.has-files { color: #0d9488; font-weight: 600; }

    /* ── Actions ── */
    .action-row { display: flex; gap: 10px; padding-top: 8px; }
    .btn-submit {
        background: #0d9488; color: #fff;
        font-size: 13.5px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 12px 24px; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: background .2s, transform .1s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(13,148,136,.3);
        text-decoration: none;
    }
    .btn-submit:hover { background: #0f766e; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(13,148,136,.35); }
    .btn-submit:active { transform: translateY(0); }
    .btn-cancel {
        background: #f5f5f5; color: #666;
        font-size: 13.5px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #eee; border-radius: 11px;
        padding: 11px 22px; cursor: pointer;
        text-decoration: none;
        display: flex; align-items: center;
        transition: background .2s, color .2s;
    }
    .btn-cancel:hover { background: #eee; color: #333; }

    @media (max-width: 900px) {
        .page-grid { grid-template-columns: 1fr; }
        .preview-panel { position: static; }
    }
</style>
@endpush

@section('content')
<div class="produk-wrap">

    <div class="notice-banner">
        <span style="font-size:16px;">ℹ️</span>
        Produk baru akan direview admin terlebih dahulu sebelum tayang <strong>(1–2 hari kerja)</strong>.
    </div>

    <div class="page-grid">

        {{-- ===== KOLOM KIRI: FORM ===== --}}
        <div class="form-card">
            <form method="POST" action="{{ route('penjual.produk.store') }}" enctype="multipart/form-data" id="produk-form">
                @csrf

                <div class="field-group">
                    <label class="field-label">Nama Produk <span class="field-required">*</span></label>
                    <input type="text" name="nama" id="f-nama" value="{{ old('nama') }}" required
                        class="input-field" placeholder="Contoh: Rendang Daging Sapi Premium 500gr">
                </div>

                <div class="grid-2 field-group">
                    <div>
                        <label class="field-label">Kategori <span class="field-required">*</span></label>
                        <select name="kategori" id="f-kategori" required class="input-field">
                            <option value="">Pilih kategori</option>
                            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                                <option value="{{ $slug }}" {{ old('kategori') == $slug ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Sub Kategori <span class="opt">(opsional)</span></label>
                        <input type="text" name="sub_kategori" id="f-sub" value="{{ old('sub_kategori') }}"
                            class="input-field" placeholder="Cth: Rendang, Gulai, dll">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Deskripsi Produk <span class="opt">(opsional)</span></label>
                    <textarea name="deskripsi" rows="4" class="input-field"
                        placeholder="Jelaskan produk kamu secara detail — bahan, ukuran, cara pemakaian, dll">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="section-sep"></div>

                <div class="grid-2 field-group">
                    <div>
                        <label class="field-label">Harga Jual <span class="field-required">*</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="harga" id="f-harga" value="{{ old('harga') }}" required min="0"
                                class="input-field" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">Harga Coret <span class="opt">(diskon)</span></label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rp</span>
                            <input type="number" name="harga_coret" id="f-coret" value="{{ old('harga_coret') }}" min="0"
                                class="input-field" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="grid-3 field-group">
                    <div>
                        <label class="field-label">Stok <span class="field-required">*</span></label>
                        <input type="number" name="stok" id="f-stok" value="{{ old('stok', 0) }}" required min="0" class="input-field">
                    </div>
                    <div>
                        <label class="field-label">Berat <span class="opt">(gram)</span></label>
                        <input type="number" name="berat" id="f-berat" value="{{ old('berat') }}" min="0" class="input-field" placeholder="500">
                    </div>
                    <div>
                        <label class="field-label">SKU <span class="opt">(opsional)</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="input-field" placeholder="—">
                    </div>
                </div>

                <div class="section-sep"></div>

                <div class="field-group">
                    <label class="field-label">
                        Foto Produk <span class="field-required">*</span>
                        <span class="opt">&nbsp;min. 1 · maks. 5 foto · 5MB/foto</span>
                    </label>
                    <div class="upload-zone" id="upload-zone">
                        <input type="file" name="foto_produk[]" multiple accept="image/*" id="foto-input" required>
                        <div class="upload-icon">📷</div>
                        <div class="upload-title"><span>Klik untuk unggah</span> atau seret foto ke sini</div>
                        <div class="upload-hint">JPG, PNG, WebP · Foto pertama jadi foto utama</div>
                    </div>
                    <div class="foto-count" id="foto-count">Belum ada foto dipilih</div>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-submit">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
                        Kirim untuk Review
                    </button>
                    <a href="{{ route('penjual.produk.index') }}" class="btn-cancel">Batal</a>
                </div>

            </form>
        </div>

        {{-- ===== KOLOM KANAN: PREVIEW ===== --}}
        <div class="preview-panel">

            {{-- Foto preview card --}}
            <div class="preview-card">
                <div class="preview-header">
                    <span class="title">Preview Produk</span>
                    <span class="live-dot"></span>
                </div>

                {{-- Main foto --}}
                <div class="main-foto-wrap" id="main-foto-wrap">
                    <div class="main-foto-empty" id="main-foto-empty">
                        <div class="ico">🖼️</div>
                        <p>Foto belum diunggah</p>
                    </div>
                    <img id="main-foto-img" src="" alt="" style="display:none;">
                    <span class="main-foto-badge" id="main-badge" style="display:none;">📸 Foto Utama</span>
                </div>

                {{-- Thumbnail strip --}}
                <div class="thumb-strip" id="thumb-strip"></div>
                <div class="strip-hint" id="strip-hint" style="display:none;">
                    ↔ Seret untuk ubah urutan · Foto pertama jadi foto utama
                </div>
            </div>

            {{-- Live info card --}}
            <div class="live-info">
                <div class="live-name empty" id="lv-name">Nama produk...</div>

                <div class="live-price-row">
                    <div class="live-price empty" id="lv-price">Harga belum diisi</div>
                    <div class="live-price-coret" id="lv-coret" style="display:none;"></div>
                    <div class="live-discount" id="lv-disc" style="display:none;"></div>
                </div>

                <div class="live-tags" id="lv-tags"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── State ── */
    let files        = [];   // { file, dataUrl }
    let activeIdx    = 0;
    let dragSrcIdx   = null;

    /* ── Refs ── */
    const input       = document.getElementById('foto-input');
    const zone        = document.getElementById('upload-zone');
    const countEl     = document.getElementById('foto-count');
    const strip       = document.getElementById('thumb-strip');
    const stripHint   = document.getElementById('strip-hint');
    const mainImg     = document.getElementById('main-foto-img');
    const mainEmpty   = document.getElementById('main-foto-empty');
    const mainBadge   = document.getElementById('main-badge');

    /* ── Live info refs ── */
    const lvName  = document.getElementById('lv-name');
    const lvPrice = document.getElementById('lv-price');
    const lvCoret = document.getElementById('lv-coret');
    const lvDisc  = document.getElementById('lv-disc');
    const lvTags  = document.getElementById('lv-tags');

    const fNama    = document.getElementById('f-nama');
    const fHarga   = document.getElementById('f-harga');
    const fCoret   = document.getElementById('f-coret');
    const fKat     = document.getElementById('f-kategori');
    const fSub     = document.getElementById('f-sub');
    const fStok    = document.getElementById('f-stok');
    const fBerat   = document.getElementById('f-berat');

    /* ══════════════════════════════════
       LIVE INFO
    ══════════════════════════════════ */
    function fmt(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function updateLiveInfo() {
        // Name
        const name = fNama.value.trim();
        lvName.textContent = name || 'Nama produk...';
        lvName.classList.toggle('empty', !name);

        // Price
        const harga = parseFloat(fHarga.value) || 0;
        const coret = parseFloat(fCoret.value) || 0;

        if (harga > 0) {
            lvPrice.textContent = fmt(harga);
            lvPrice.classList.remove('empty');
        } else {
            lvPrice.textContent = 'Harga belum diisi';
            lvPrice.classList.add('empty');
        }

        if (coret > 0 && coret > harga) {
            lvCoret.textContent  = fmt(coret);
            lvCoret.style.display = '';
            const disc = Math.round((1 - harga / coret) * 100);
            lvDisc.textContent   = disc + '% off';
            lvDisc.style.display = '';
        } else {
            lvCoret.style.display = 'none';
            lvDisc.style.display  = 'none';
        }

        // Tags
        lvTags.innerHTML = '';
        const kat = fKat.options[fKat.selectedIndex]?.text;
        if (kat && fKat.value) addTag(kat, 'teal');

        const sub = fSub.value.trim();
        if (sub) addTag(sub, '');

        const stok = parseInt(fStok.value) || 0;
        addTag(stok > 0 ? `Stok ${stok}` : 'Stok 0', stok > 0 ? 'gray' : 'red');

        const berat = parseInt(fBerat.value) || 0;
        if (berat > 0) addTag(berat + ' gram', 'gray');
    }

    function addTag(text, cls) {
        const s = document.createElement('span');
        s.className = 'live-tag ' + (cls || '');
        s.textContent = text;
        lvTags.appendChild(s);
    }

    [fNama, fHarga, fCoret, fKat, fSub, fStok, fBerat].forEach(el => {
        if (el) el.addEventListener('input', updateLiveInfo);
        if (el) el.addEventListener('change', updateLiveInfo);
    });

    updateLiveInfo();

    /* ══════════════════════════════════
       FILE HANDLING
    ══════════════════════════════════ */
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
    zone.addEventListener('drop',      e  => { e.preventDefault(); zone.classList.remove('drag-over'); handleFiles(e.dataTransfer.files); });
    input.addEventListener('change',   e  => handleFiles(e.target.files));

    function handleFiles(incoming) {
        const slots = 5 - files.length;
        if (slots <= 0) return;
        let added = 0;
        Array.from(incoming).forEach(f => {
            if (added >= slots) return;
            if (!f.type.startsWith('image/')) return;
            if (f.size > 5 * 1024 * 1024) { alert(`"${f.name}" melebihi 5MB`); return; }
            const reader = new FileReader();
            reader.onload = ev => {
                files.push({ file: f, dataUrl: ev.target.result });
                render();
            };
            reader.readAsDataURL(f);
            added++;
        });
    }

    function removeFile(idx) {
        files.splice(idx, 1);
        if (activeIdx >= files.length) activeIdx = Math.max(0, files.length - 1);
        syncInput();
        render();
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f.file));
        input.files = dt.files;
        input.required = files.length === 0;
    }

    function setActive(idx) {
        activeIdx = idx;
        render();
    }

    /* ══════════════════════════════════
       RENDER
    ══════════════════════════════════ */
    function render() {
        syncInput();

        // ── Main foto
        if (files.length > 0) {
            mainImg.src           = files[activeIdx].dataUrl;
            mainImg.style.display = '';
            mainEmpty.style.display = 'none';
            mainBadge.style.display = '';
        } else {
            mainImg.style.display   = 'none';
            mainEmpty.style.display = '';
            mainBadge.style.display = 'none';
            activeIdx = 0;
        }

        // ── Thumbnail strip
        strip.innerHTML = '';
        files.forEach((f, i) => {
            const div = document.createElement('div');
            div.className   = 'thumb-item' + (i === activeIdx ? ' is-active' : '');
            div.draggable   = true;
            div.dataset.idx = i;

            div.innerHTML = `
                <img src="${f.dataUrl}" alt="">
                <button type="button" class="rm" data-idx="${i}" title="Hapus">×</button>
            `;

            div.addEventListener('click',      () => setActive(i));
            div.querySelector('.rm').addEventListener('click', e => { e.stopPropagation(); removeFile(i); });

            // Drag-and-drop reorder
            div.addEventListener('dragstart', () => { dragSrcIdx = i; div.classList.add('dragging'); });
            div.addEventListener('dragend',   () => div.classList.remove('dragging'));
            div.addEventListener('dragover',  e => { e.preventDefault(); div.classList.add('drag-over-target'); });
            div.addEventListener('dragleave', () => div.classList.remove('drag-over-target'));
            div.addEventListener('drop', e => {
                e.preventDefault();
                div.classList.remove('drag-over-target');
                if (dragSrcIdx === null || dragSrcIdx === i) return;
                const moved = files.splice(dragSrcIdx, 1)[0];
                files.splice(i, 0, moved);
                activeIdx = i;
                dragSrcIdx = null;
                syncInput();
                render();
            });

            strip.appendChild(div);
        });

        // Add-more button if < 5
        if (files.length < 5) {
            const btn = document.createElement('div');
            btn.className = 'thumb-placeholder';
            btn.title     = 'Tambah foto';
            btn.textContent = '+';
            btn.addEventListener('click', () => input.click());
            strip.appendChild(btn);
        }

        // Strip hint
        stripHint.style.display = files.length > 1 ? '' : 'none';

        // Counter
        const n = files.length;
        countEl.textContent = n === 0 ? 'Belum ada foto dipilih' : `${n} / 5 foto`;
        countEl.className   = 'foto-count' + (n > 0 ? ' has-files' : '');
    }

    render();
})();
</script>
@endpush