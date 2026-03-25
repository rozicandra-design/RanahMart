@extends('layouts.dashboard')
@section('title', 'Lengkapi Dokumen Toko')
@section('page-title', 'Lengkapi Dokumen Toko')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .dok-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

    .dok-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px;
    }

    .status-banner {
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .status-banner .ico { font-size: 22px; flex-shrink: 0; margin-top: 1px; }
    .status-banner .title { font-size: 13.5px; font-weight: 800; margin-bottom: 4px; }
    .status-banner .body  { font-size: 12.5px; line-height: 1.6; }
    .status-banner .hint  { font-size: 11px; margin-top: 5px; opacity: .7; }

    .banner-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    .banner-danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .banner-success { background: #f0fdfa; border: 1px solid #99f6e4; color: #065f46; }

    .page-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: start;
    }

    .card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #ebebeb;
        padding: 26px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .card + .card { margin-top: 20px; }

    .section-title {
        font-size: 11px; font-weight: 800; color: #bbb;
        text-transform: uppercase; letter-spacing: .7px;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 18px;
    }
    .section-title .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .dot-blue   { background: #3b82f6; }
    .dot-teal   { background: #0d9488; }
    .dot-amber  { background: #f59e0b; }
    .dot-purple { background: #7c3aed; }

    .section-sep { height: 1px; background: #f3f3f3; margin: 20px 0; }

    .field-group { margin-bottom: 16px; }
    .field-label {
        display: flex; align-items: center; gap: 6px;
        font-size: 11px; font-weight: 800; color: #555;
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px;
    }
    .field-ok {
        font-size: 10.5px; font-weight: 700; color: #0d9488;
        background: #f0fdfa; border: 1px solid #99f6e4;
        padding: 1px 8px; border-radius: 999px;
        text-transform: none; letter-spacing: 0;
    }

    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 11px;
        padding: 11px 14px; font-size: 13.5px; color: #1a1a1a;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fafafa; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-field::placeholder { color: #c5c5c5; }
    .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); background: #fff; }
    .input-mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; letter-spacing: .5px; }

    .rekening-box {
        background: #f9f8f7; border: 1px solid #f0eeeb;
        border-radius: 14px; padding: 16px 18px;
    }

    .upload-zone {
        border: 2px dashed #e0e0e0; border-radius: 14px;
        padding: 0; overflow: hidden; position: relative;
        background: #fafafa; cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .upload-zone:hover { border-color: #3b82f6; background: #eff6ff; }
    .upload-zone.has-file { border-style: solid; border-color: #e8e8e8; }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%; z-index: 2;
    }
    .doc-img {
        width: 100%; height: 130px;
        object-fit: cover; display: block;
        border-radius: 12px 12px 0 0;
        transition: opacity .2s;
    }
    .upload-zone:hover .doc-img { opacity: .75; }
    .upload-zone-body {
        padding: 14px 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .upload-zone-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: #eff6ff; border: 1.5px solid #bfdbfe;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .upload-zone.has-file .upload-zone-icon { background: #f0fdfa; border-color: #99f6e4; }
    .upload-zone-text .lbl { font-size: 12.5px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }
    .upload-zone-text .hint { font-size: 11px; color: #aaa; }
    .upload-zone-text .ok   { font-size: 11px; color: #0d9488; font-weight: 600; }
    .doc-preview-live {
        width: 100%; height: 130px;
        object-fit: cover; display: none;
        border-radius: 12px 12px 0 0;
    }

    .btn-submit {
        width: 100%; background: #2563eb; color: #fff;
        font-size: 14px; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 13px;
        padding: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(37,99,235,.3);
        transition: background .2s, transform .1s, box-shadow .2s;
        margin-top: 20px;
    }
    .btn-submit:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 6px 22px rgba(37,99,235,.35); }
    .btn-submit:active { transform: translateY(0); }

    @media (max-width: 900px) { .page-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="dok-wrap">

    {{-- Status banner --}}
    @if($toko->status === 'menunggu_dokumen')
    <div class="status-banner banner-warning">
        <span class="ico">📄</span>
        <div>
            <div class="title">Dinas Meminta Dokumen Tambahan</div>
            @if($toko->catatan_dinas)
            <div class="body">{{ $toko->catatan_dinas }}</div>
            @endif
            <div class="hint">Lengkapi dokumen di bawah dan submit ulang untuk verifikasi.</div>
        </div>
    </div>
    @elseif($toko->status === 'ditolak')
    <div class="status-banner banner-danger">
        <span class="ico">❌</span>
        <div>
            <div class="title">Verifikasi Ditolak</div>
            @if($toko->catatan_dinas)
            <div class="body">{{ $toko->catatan_dinas }}</div>
            @endif
        </div>
    </div>
    @elseif($toko->terverifikasi_dinas)
    <div class="status-banner banner-success">
        <span class="ico">✅</span>
        <div>
            <div class="title">Toko Telah Terverifikasi Dinas</div>
            <div class="hint">Sertifikat berlaku hingga {{ $toko->kadaluarsa_sertifikat?->format('d M Y') ?? '-' }}</div>
        </div>
    </div>
    @endif

    {{-- Banner sertifikat --}}
    @if($toko->terverifikasi_dinas)
    <div style="background:linear-gradient(135deg,#f0fdf8,#dcfce7);border:1px solid #bbf7d0;border-radius:16px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="font-size:26px;">🏅</div>
            <div>
                <div style="font-size:13px;font-weight:800;color:#065f46;margin-bottom:2px;">Toko Terverifikasi Dinas</div>
                <div style="font-size:11.5px;color:#059669;">
                    No. {{ $toko->no_sertifikat ?? '-' }} ·
                    @php $expSert = $toko->kadaluarsa_sertifikat; @endphp
                    @if($expSert && $expSert->isPast())
                        <span style="color:#dc2626;font-weight:700;">⚠ Kadaluarsa {{ $expSert->format('d M Y') }}</span>
                    @else
                        Berlaku hingga {{ $expSert?->format('d M Y') ?? '—' }}
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('penjual.toko.sertifikat') }}" target="_blank"
            style="background:#059669;color:#fff;font-size:12px;font-weight:700;padding:9px 16px;border-radius:10px;text-decoration:none;display:flex;align-items:center;gap:6px;flex-shrink:0;box-shadow:0 3px 10px rgba(5,150,105,.25);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
            </svg>
            Lihat Sertifikat
        </a>
    </div>
    @endif

    <form method="POST" action="{{ route('penjual.toko.dokumen.upload') }}"
        enctype="multipart/form-data" id="form-dokumen">
        @csrf

        <div class="page-grid">

            {{-- Kolom Kiri: Data Legal --}}
            <div>
                <div class="card">
                    <div class="section-title">
                        <span class="dot dot-blue"></span> Data Legal
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            NIB (Nomor Induk Berusaha)
                            @if($toko->nib) <span class="field-ok">✓ Sudah ada</span> @endif
                        </label>
                        <input type="text" name="nib" value="{{ old('nib', $toko->nib) }}"
                            placeholder="Contoh: 1234567890123"
                            class="input-field input-mono">
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            No. SKU / SIUP
                            @if($toko->no_sku) <span class="field-ok">✓ Sudah ada</span> @endif
                        </label>
                        <input type="text" name="no_sku" value="{{ old('no_sku', $toko->no_sku) }}"
                            placeholder="Nomor SKU atau SIUP"
                            class="input-field input-mono">
                    </div>

                    <div class="section-sep"></div>

                    <div class="section-title">
                        <span class="dot dot-amber"></span> Rekening Bank
                    </div>

                    <div class="rekening-box">
                        <div class="field-group">
                            <label class="field-label">
                                Nama Bank
                                @if($toko->bank) <span class="field-ok">✓ {{ $toko->bank }}</span> @endif
                            </label>
                            <select name="bank" class="input-field" style="background:#fff;">
                                <option value="">— Pilih Bank —</option>
                                @foreach(['BNI','BRI','BCA','Mandiri','BSI','BTN','CIMB Niaga','Danamon','Permata','Bank Nagari'] as $b)
                                <option value="{{ $b }}" {{ old('bank', $toko->bank) === $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field-group">
                            <label class="field-label">Nomor Rekening</label>
                            <input type="text" name="no_rekening"
                                value="{{ old('no_rekening', $toko->no_rekening) }}"
                                placeholder="Nomor rekening"
                                class="input-field input-mono" style="background:#fff;">
                        </div>

                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label">Atas Nama</label>
                            <input type="text" name="atas_nama_rekening"
                                value="{{ old('atas_nama_rekening', $toko->atas_nama_rekening) }}"
                                placeholder="Nama pemilik rekening"
                                class="input-field" style="background:#fff;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Upload Foto --}}
            <div>
                <div class="card">
                    <div class="section-title">
                        <span class="dot dot-purple"></span> Foto Dokumen
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            Foto KTP Pemilik
                            @if($toko->foto_ktp) <span class="field-ok">✓ Sudah ada</span> @endif
                        </label>
                        <div class="upload-zone {{ $toko->foto_ktp ? 'has-file' : '' }}" id="zone-ktp">
                            <input type="file" name="foto_ktp" accept="image/*" data-preview="prev-ktp" data-zone="zone-ktp">
                            @if($toko->foto_ktp)
                            <img src="{{ Storage::url($toko->foto_ktp) }}" class="doc-img">
                            @endif
                            <img class="doc-preview-live" id="prev-ktp" alt="">
                            <div class="upload-zone-body">
                                <div class="upload-zone-icon">🪪</div>
                                <div class="upload-zone-text">
                                    <div class="lbl">{{ $toko->foto_ktp ? 'Ganti Foto KTP' : 'Upload Foto KTP' }}</div>
                                    @if($toko->foto_ktp)
                                    <div class="ok">Foto sudah diunggah · klik untuk ganti</div>
                                    @else
                                    <div class="hint">JPG, PNG · Maks. 2MB</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            Foto Tempat Usaha
                            @if($toko->foto_usaha) <span class="field-ok">✓ Sudah ada</span> @endif
                        </label>
                        <div class="upload-zone {{ $toko->foto_usaha ? 'has-file' : '' }}" id="zone-usaha">
                            <input type="file" name="foto_usaha" accept="image/*" data-preview="prev-usaha" data-zone="zone-usaha">
                            @if($toko->foto_usaha)
                            <img src="{{ Storage::url($toko->foto_usaha) }}" class="doc-img">
                            @endif
                            <img class="doc-preview-live" id="prev-usaha" alt="">
                            <div class="upload-zone-body">
                                <div class="upload-zone-icon">🏪</div>
                                <div class="upload-zone-text">
                                    <div class="lbl">{{ $toko->foto_usaha ? 'Ganti Foto Usaha' : 'Upload Foto Usaha' }}</div>
                                    @if($toko->foto_usaha)
                                    <div class="ok">Foto sudah diunggah · klik untuk ganti</div>
                                    @else
                                    <div class="hint">Eksterior/interior tempat usaha · Maks. 2MB</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-group" style="margin-bottom:0;">
                        <label class="field-label">
                            Foto Produk Sample
                            @if($toko->foto_produk_sample) <span class="field-ok">✓ Sudah ada</span> @endif
                        </label>
                        <div class="upload-zone {{ $toko->foto_produk_sample ? 'has-file' : '' }}" id="zone-sample">
                            <input type="file" name="foto_produk_sample" accept="image/*" data-preview="prev-sample" data-zone="zone-sample">
                            @if($toko->foto_produk_sample)
                            <img src="{{ Storage::url($toko->foto_produk_sample) }}" class="doc-img">
                            @endif
                            <img class="doc-preview-live" id="prev-sample" alt="">
                            <div class="upload-zone-body">
                                <div class="upload-zone-icon">📦</div>
                                <div class="upload-zone-text">
                                    <div class="lbl">{{ $toko->foto_produk_sample ? 'Ganti Foto Produk' : 'Upload Foto Produk' }}</div>
                                    @if($toko->foto_produk_sample)
                                    <div class="ok">Foto sudah diunggah · klik untuk ganti</div>
                                    @else
                                    <div class="hint">Foto produk unggulan toko · Maks. 2MB</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                        <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
                    </svg>
                    {{ $toko->status === 'menunggu_dokumen' ? 'Upload & Ajukan Ulang ke Dinas' : 'Simpan Dokumen' }}
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.upload-zone input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const previewId = this.dataset.preview;
        const zoneId    = this.dataset.zone;
        const liveImg   = document.getElementById(previewId);
        const zone      = document.getElementById(zoneId);
        const existing  = zone.querySelector('.doc-img');
        if (existing) existing.style.display = 'none';
        const reader = new FileReader();
        reader.onload = ev => {
            liveImg.src = ev.target.result;
            liveImg.style.display = 'block';
        };
        reader.readAsDataURL(file);
        zone.classList.add('has-file');
        const lbl  = zone.querySelector('.upload-zone-text .lbl');
        const hint = zone.querySelector('.upload-zone-text .hint, .upload-zone-text .ok');
        if (lbl)  lbl.textContent  = file.name.length > 28 ? file.name.slice(0,26) + '…' : file.name;
        if (hint) { hint.className = 'ok'; hint.textContent = 'Siap diunggah · klik untuk ganti'; }
    });
});
</script>
@endpush