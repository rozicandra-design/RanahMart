@extends('layouts.dashboard')
@section('title', 'Profil Toko')
@section('page-title', 'Profil Toko')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .toko-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    .toko-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px;
    }

    /* ── Alert banners ── */
    .alert {
        border-radius: 14px;
        padding: 13px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        font-weight: 500;
    }
    .alert-success { background: #f0fdfa; border: 1px solid #99f6e4; color: #0f766e; }
    .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    .alert-teal    { background: #f0fdfa; border: 1px solid #99f6e4; color: #0f766e; }
    .alert strong  { font-weight: 800; }
    .alert .icon   { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .alert-sub     { font-size: 11.5px; margin-top: 3px; opacity: .75; }

    /* ── Page grid ── */
    .page-grid {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* ── Sidebar card ── */
    .sidebar-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        position: sticky;
        top: 24px;
    }

    /* Hero strip */
    .sidebar-hero {
        background: linear-gradient(135deg, #0d9488, #0f766e);
        padding: 24px 20px 20px;
        text-align: center;
        position: relative;
    }
    .sidebar-hero::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0; right: 0;
        height: 20px;
        background: #fff;
        border-radius: 20px 20px 0 0;
    }

    .avatar-ring {
        width: 64px; height: 64px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,.5);
        overflow: hidden;
        margin: 0 auto 10px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.2);
        font-size: 26px; font-weight: 800; color: #fff;
        position: relative; z-index: 1;
    }
    .avatar-ring img { width: 100%; height: 100%; object-fit: cover; }

    .sidebar-name {
        font-size: 14px; font-weight: 800; color: #fff;
        position: relative; z-index: 1;
    }
    .sidebar-role {
        font-size: 11px; color: rgba(255,255,255,.6); font-weight: 500;
        margin-top: 2px; position: relative; z-index: 1;
    }

    /* Info rows */
    .sidebar-body { padding: 16px 18px; }
    .info-row {
        display: flex; align-items: flex-start; gap: 9px;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 12px; color: #555;
    }
    .info-row:last-of-type { border-bottom: none; }
    .info-row svg { color: #ccc; flex-shrink: 0; margin-top: 1px; }
    .info-row span { word-break: break-all; line-height: 1.5; }

    .status-badge {
        display: inline-block;
        font-size: 10.5px; font-weight: 700;
        padding: 2px 9px; border-radius: 999px;
    }
    .status-aktif    { background: #f0fdfa; color: #0d9488; border: 1px solid #99f6e4; }
    .status-nonaktif { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .status-suspend  { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }

    .sidebar-footer {
        border-top: 1px solid #f3f3f3;
        padding: 12px 18px;
    }
    .sidebar-footer a {
        display: block; text-align: center;
        font-size: 12px; font-weight: 700; color: #0d9488;
        text-decoration: none;
        transition: color .15s;
    }
    .sidebar-footer a:hover { color: #0f766e; }

    /* ── Form card ── */
    .form-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    /* ── Section title ── */
    .section-title {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px;
    }
    .section-title .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #0d9488; flex-shrink: 0;
    }

    .section-sep { height: 1px; background: #f3f3f3; margin: 24px 0; }

    /* ── Image upload zones ── */
    .img-upload-group { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }

    .img-upload-box {
        border: 2px dashed #e8e8e8;
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        position: relative;
        background: #fafafa;
        transition: border-color .2s, background .2s;
        overflow: hidden;
    }
    .img-upload-box:hover { border-color: #0d9488; background: #f0fdfa; }
    .img-upload-box input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }

    /* Logo preview */
    .logo-preview {
        width: 56px; height: 56px;
        border-radius: 12px; overflow: hidden;
        border: 2px solid #e8e8e8;
        margin: 0 auto 10px;
        display: flex; align-items: center; justify-content: center;
        background: #f3f3f3; font-size: 22px;
    }
    .logo-preview img { width: 100%; height: 100%; object-fit: cover; }

    /* Banner preview */
    .banner-preview {
        width: 100%; height: 48px;
        border-radius: 9px; overflow: hidden;
        border: 2px solid #e8e8e8;
        margin-bottom: 10px;
        display: flex; align-items: center; justify-content: center;
        background: #f3f3f3; font-size: 18px;
    }
    .banner-preview img { width: 100%; height: 100%; object-fit: cover; }

    .upload-label { font-size: 12px; font-weight: 700; color: #0d9488; margin-bottom: 2px; }
    .upload-hint  { font-size: 10.5px; color: #bbb; }

    /* ── Field groups ── */
    .field-group  { margin-bottom: 18px; }
    .field-label  {
        display: block; font-size: 11px; font-weight: 800;
        color: #555; text-transform: uppercase;
        letter-spacing: .5px; margin-bottom: 7px;
    }
    .field-label .opt { font-weight: 500; color: #bbb; text-transform: none; letter-spacing: 0; }
    .field-required { color: #ef4444; }

    .input-field {
        width: 100%;
        border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 11px 14px; font-size: 13.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13,148,136,.1);
        background: #fff;
    }
    textarea.input-field { resize: none; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

    /* ── Rekening section ── */
    .rekening-box {
        background: #f9f8f7;
        border: 1px solid #f0eeeb;
        border-radius: 14px;
        padding: 18px 20px;
    }

    /* ── Submit button ── */
    .btn-submit {
        width: 100%;
        background: #0d9488; color: #fff;
        font-size: 14px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 13px;
        padding: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(13,148,136,.3);
        transition: background .2s, transform .1s, box-shadow .2s;
        margin-top: 8px;
    }
    .btn-submit:hover {
        background: #0f766e;
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(13,148,136,.35);
    }
    .btn-submit:active { transform: translateY(0); }

    @media (max-width: 900px) {
        .page-grid { grid-template-columns: 1fr; }
        .sidebar-card { position: static; }
    }
</style>
@endpush

@section('content')
<div class="toko-wrap">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success">
        <span class="icon">✅</span>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(isset($toko) && !$toko->isAktif())
    <div class="alert alert-warning">
        <span class="icon">⚠️</span>
        <div>
            Status toko: <strong>{{ ucfirst(str_replace('_', ' ', $toko->status)) }}</strong>
            @if($toko->catatan_admin)
            <div class="alert-sub">Catatan admin: {{ $toko->catatan_admin }}</div>
            @endif
        </div>
    </div>
    @endif

    @if(isset($toko) && $toko->terverifikasi_dinas)
    <div class="alert alert-teal">
        <span class="icon">🏅</span>
        <div>
            <strong>Terverifikasi Dinas Koperasi & UMKM Kota Padang</strong>
            <div class="alert-sub">
                No. {{ $toko->no_sertifikat }} · Berlaku hingga {{ $toko->kadaluarsa_sertifikat?->format('d M Y') }}
            </div>
        </div>
    </div>
    @endif

    <div class="page-grid">

        {{-- ===== SIDEBAR ===== --}}
        <div class="sidebar-card">
            <div class="sidebar-hero">
                <div class="avatar-ring">
                    @if(auth()->user()->foto)
                        <img src="{{ Storage::url(auth()->user()->foto) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="sidebar-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-role">Penjual</div>
            </div>

            <div class="sidebar-body">
                <div class="info-row">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="info-row">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.1 1.18 2 2 0 012.11 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.46-.46a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <span>{{ auth()->user()->no_hp ?? '—' }}</span>
                </div>
                <div class="info-row">
                    @php
                        $statusAkun = match(auth()->user()->status ?? 'aktif') {
                            'aktif'    => ['label' => 'Aktif',    'cls' => 'status-aktif'],
                            'nonaktif' => ['label' => 'Nonaktif', 'cls' => 'status-nonaktif'],
                            'suspend'  => ['label' => 'Suspend',  'cls' => 'status-suspend'],
                            default    => ['label' => 'Aktif',    'cls' => 'status-aktif'],
                        };
                    @endphp
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    <span class="status-badge {{ $statusAkun['cls'] }}">{{ $statusAkun['label'] }}</span>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('penjual.pengaturan') }}">Edit Profil Akun →</a>
            </div>
        </div>

        {{-- ===== FORM ===== --}}
        <div class="form-card">
            <form method="POST" action="{{ route('penjual.toko.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Logo & Banner --}}
                <div class="section-title"><span class="dot"></span> Identitas Visual</div>

                <div class="img-upload-group">
                    {{-- Logo --}}
                    <div class="img-upload-box" id="logo-box">
                        <input type="file" name="logo" accept="image/*" id="logo-input">
                        <div class="logo-preview" id="logo-preview">
                            @if(isset($toko) && $toko->logo)
                                <img src="{{ Storage::url($toko->logo) }}" id="logo-img" alt="">
                            @else
                                <span id="logo-img" style="font-size:22px;">🏪</span>
                            @endif
                        </div>
                        <div class="upload-label">Logo Toko</div>
                        <div class="upload-hint">Maks. 2MB · JPG, PNG</div>
                    </div>

                    {{-- Banner --}}
                    <div class="img-upload-box" id="banner-box">
                        <input type="file" name="banner" accept="image/*" id="banner-input">
                        <div class="banner-preview" id="banner-preview">
                            @if(isset($toko) && $toko->banner)
                                <img src="{{ Storage::url($toko->banner) }}" id="banner-img" alt="">
                            @else
                                <span id="banner-img" style="font-size:18px;">🖼️</span>
                            @endif
                        </div>
                        <div class="upload-label">Banner Toko</div>
                        <div class="upload-hint">Ideal: 1200×300px</div>
                    </div>
                </div>

                <div class="section-sep"></div>

                {{-- Info Dasar --}}
                <div class="section-title"><span class="dot"></span> Informasi Toko</div>

                <div class="field-group">
                    <label class="field-label">Nama Toko <span class="field-required">*</span></label>
                    <input type="text" name="nama_toko"
                        value="{{ old('nama_toko', $toko->nama_toko ?? '') }}" required
                        class="input-field" placeholder="Nama toko kamu">
                </div>

                <div class="grid-2 field-group">
                    <div>
                        <label class="field-label">Kategori <span class="field-required">*</span></label>
                        <select name="kategori" required class="input-field">
                            @foreach(config('ranahmart.kategori_umkm') as $slug => $label)
                                <option value="{{ $slug }}" {{ old('kategori', $toko->kategori ?? '') == $slug ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Kecamatan <span class="field-required">*</span></label>
                        <select name="kecamatan" required class="input-field">
                            @foreach(config('ranahmart.kecamatan_padang') as $kec)
                                <option value="{{ $kec }}" {{ old('kecamatan', $toko->kecamatan ?? '') == $kec ? 'selected' : '' }}>
                                    {{ $kec }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Alamat Lengkap <span class="opt">(opsional)</span></label>
                    <textarea name="alamat_lengkap" rows="2" class="input-field"
                        placeholder="Jl. contoh No. 1, RT/RW, Kelurahan...">{{ old('alamat_lengkap', $toko->alamat_lengkap ?? '') }}</textarea>
                </div>

                <div class="field-group">
                    <label class="field-label">Deskripsi Toko <span class="opt">(opsional)</span></label>
                    <textarea name="deskripsi" rows="3" class="input-field"
                        placeholder="Ceritakan tentang toko dan produk yang kamu jual...">{{ old('deskripsi', $toko->deskripsi ?? '') }}</textarea>
                </div>

                <div class="grid-2 field-group">
                    <div>
                        <label class="field-label">No. WhatsApp Toko</label>
                        <input type="text" name="no_hp"
                            value="{{ old('no_hp', $toko->no_hp ?? '') }}"
                            class="input-field" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="field-label">Jam Operasional</label>
                        <input type="text" name="jam_operasional"
                            value="{{ old('jam_operasional', $toko->jam_operasional ?? '') }}"
                            class="input-field" placeholder="08.00–20.00 WIB">
                    </div>
                </div>

                <div class="section-sep"></div>

                {{-- Rekening --}}
                <div class="section-title"><span class="dot" style="background:#7c3aed;"></span> Info Rekening</div>

                <div class="rekening-box">
                    <div class="grid-3">
                        <div>
                            <label class="field-label">Bank</label>
                            <select name="bank" class="input-field" style="background:#fff;">
                                @foreach(['BRI','BNI','BCA','Mandiri','BSI','Bank Nagari'] as $bank)
                                <option value="{{ $bank }}" {{ old('bank', $toko->bank ?? '') == $bank ? 'selected' : '' }}>
                                    {{ $bank }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label">No. Rekening</label>
                            <input type="text" name="no_rekening"
                                value="{{ old('no_rekening', $toko->no_rekening ?? '') }}"
                                class="input-field" style="background:#fff;"
                                placeholder="1234567890">
                        </div>
                        <div>
                            <label class="field-label">Atas Nama</label>
                            <input type="text" name="atas_nama_rekening"
                                value="{{ old('atas_nama_rekening', $toko->atas_nama_rekening ?? '') }}"
                                class="input-field" style="background:#fff;"
                                placeholder="Nama pemilik rekening">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Profil Toko
                </button>

            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Live image preview for logo & banner
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const prev = document.getElementById('logo-preview');
        prev.innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
    };
    reader.readAsDataURL(file);
});

document.getElementById('banner-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const prev = document.getElementById('banner-preview');
        prev.innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
    };
    reader.readAsDataURL(file);
});
</script>
@endpush